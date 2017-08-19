<script>
    //alert(location.search);
    var str=unescape(location.search);
    var strArray=str.split('&heading=');
    var len=strArray.length;
    var str=strArray[1];
    var strArray=str.split('&message=');
    var heading = strArray[0];
    var message=strArray[1];
</script>
<style>
.imgLinkRemove{
    cursor: default;
}
</style>
<?php 
    //This file is used as printing version for Student Attendance Short Report Print
    //
    // Author :Parveen Sharma
    // Created on : 26-12-2008
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(MODEL_PATH . "/InstituteManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentAttendanceShortTemplate.php"); 
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    
    define('MODULE','StudentAttendanceShortReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);

    global $sessionHandler;          
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
    $instituteManager = InstituteManager::getInstance();  
    $commonQueryManager = CommonQueryManager::getInstance();
    $studentReportManager = StudentReportsManager::getInstance();
    $reportManager = ReportManager::getInstance();
    $studentManager = StudentManager::getInstance();
   
    $isMessageUpdate='0';
    
    $studentIds = add_slashes($REQUEST_DATA['studentId']);     
    $signature = add_slashes(trim($REQUEST_DATA['signature']));
    $address = add_slashes($REQUEST_DATA['address']);
    $photo = add_slashes($REQUEST_DATA['photo']);
    $heading = add_slashes(trim($REQUEST_DATA['heading'])); 
    $message = add_slashes(trim($REQUEST_DATA['message'])); 
    $dutyLeaveId  = add_slashes(trim($REQUEST_DATA['dutyLeave']));   
    $medicalLeaveId = add_slashes(trim($REQUEST_DATA['medicalLeave']));   
    
    $mmSignature = $signature;
    
    if($dutyLeaveId=='') {
      $dutyLeaveId=0;  
    }
    
    if($medicalLeaveId=='') {
      $medicalLeaveId=0;  
    }
     
    $timeTableLabelId = add_slashes(trim($REQUEST_DATA['labelId']));
    $classId = add_slashes(trim($REQUEST_DATA['classId']));
    $rollNo = add_slashes(trim($REQUEST_DATA['rollno']));
    $percentage = add_slashes(trim($REQUEST_DATA['percentage']));
    
    
    $notes='<b>Note:&nbsp;</b><i>This is a computer generated report and requires no signatures.</i>';  
     
    if($studentIds=='') {
      $studentIds = 0;
    }
    
    function parseOutput($data){
       return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
    }
    
    
    $courseTable = '';
    
    if($dutyLeaveId=='1' && $medicalLeaveId=='1') {
        $courseTable= '<tr>
                          <td width="8%"  align="center" nowrap rowspan="2" scope="row"><b>Sr. No.</b></td>
                          <td width="45%" align="left"   rowspan="2"><b>Subject Name </b></td>
                          <td width="15%" align="left"   rowspan="2"><b>Subject Code </b></td>
                          <td width="27%" align="center" colspan="3"><b>Attendance Details<br>
                          <span style="font-size:9px">Percentage [ (Attendance+Duty Leaves+Medical Leaves) / Delivered * 100 ]</span></b></td>
                      </tr>
                      <tr>
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Attendance</b></td>
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Duty Leaves</b></td> 
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Medical Leaves</b></td> 
                          <td width="17%" align="right" nowrap><b>&nbsp;&nbsp;Percentage</b></td>
                      </tr>';
    }
    else if($dutyLeaveId=='1') {
        $courseTable= '<tr>
                          <td width="8%"  align="center" nowrap rowspan="2" scope="row"><b>Sr. No.</b></td>
                          <td width="45%" align="left"   rowspan="2"><b>Subject Name </b></td>
                          <td width="15%" align="left"   rowspan="2"><b>Subject Code </b></td>
                          <td width="27%" align="center" colspan="2"><b>Attendance Details<br>
                          <span style="font-size:9px">Percentage [ (Attendance+Duty Leaves) / Delivered * 100 ]</span></b></td>
                      </tr>
                      <tr>
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Attendance</b></td>
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Duty Leaves</b></td> 
                          <td width="17%" align="right" nowrap><b>&nbsp;&nbsp;Percentage</b></td>
                      </tr>';
    }
    else if($medicalLeaveId=='1') {
        $courseTable= '<tr>
                          <td width="8%"  align="center" nowrap rowspan="2" scope="row"><b>Sr. No.</b></td>
                          <td width="45%" align="left"   rowspan="2"><b>Subject Name </b></td>
                          <td width="15%" align="left"   rowspan="2"><b>Subject Code </b></td>
                          <td width="27%" align="center" colspan="2"><b>Attendance Details<br>
                          <span style="font-size:9px">Percentage [ (Attendance+Medical Leaves) / Delivered * 100 ]</span></b></td>
                      </tr>
                      <tr>
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Attendance</b></td>
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Medical Leaves</b></td> 
                          <td width="17%" align="right" nowrap><b>&nbsp;&nbsp;Percentage</b></td>
                      </tr>';
    }
    else {
        $courseTable= '<tr>
                          <td width="8%"  align="center" nowrap scope="row"><b>Sr. No.</b></td>
                          <td width="45%" align="left"  ><b>Subject Name </b></td>
                          <td width="15%" align="left"  ><b>Subject Code </b></td>
                          <td width="10%" align="right" nowrap><b>&nbsp;&nbsp;Attendance</b></td>
                          <td width="17%" align="right" nowrap><b>&nbsp;&nbsp;Percentage</b></td>
                      </tr>';
    }

    
    function courseResult($id,$subjectName,$subjectCode,$attendance,$dutyLeave,$medicalLeave,$percentage) {
        
        global $dutyLeaveId, $medicalLeaveId;
        
        $courseInfo = '<tr>
                          <td  align="center" >'.$id.'</td>
                          <td  align="left" >'.parseOutput($subjectName).'</td>
                          <td  align="left" >'.parseOutput($subjectCode).'</td>
                          <td  align="right" >'.parseOutput($attendance).'</td>';
                          if($dutyLeaveId=='1') {
                            $courseInfo .= '<td  align="right" >'.parseOutput($dutyLeave).'</td>';  
                          }
                          if($medicalLeaveId=='1') {
                            $courseInfo .= '<td  align="right" >'.parseOutput($medicalLeave).'</td>';
                          }
                          $courseInfo .= '<td  align="right" >'.parseOutput($percentage).'</td>';
        $courseInfo .= '</tr>';
        
        return $courseInfo;
    }
    
 

    
    
    // Search filter //
       $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
       $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
       
       if($sortField=="rollNo"){
           $sortField1=" IF(IFNULL(rollNo,'')='',stu.studentId,rollNo)";
       }
       else if($sortField=="studentName"){
           $sortField1=" IF(IFNULL(studentName,'')='',stu.studentId,studentName)";
       }
       else if($sortField=="fatherName"){
           $sortField1=" IF(IFNULL(fatherName,'')='' ,stu.studentId,fatherName)";
       }
       else if($sortField=="universityRollNo"){
           $sortField1=" IF(IFNULL(universityRollNo,'')='' ,stu.studentId,universityRollNo)";
       }
       else if($sortField=="studentMobileNo"){
           $sortField1=" IF(IFNULL(studentMobileNo,'')='' ,stu.studentId,studentMobileNo)";
       }
       else {
           $sortField1 =" IF(IFNULL(rollNo,'')='',stu.studentId,rollNo)";
           $sortField =" rollNo";
       }   
       $consolidated=1;
       $orderBy = " $sortField1 $sortOrderBy";           
    
     // Institute Informations 
        $filter = "AND instituteId=".$sessionHandler->getSessionVariable('InstituteId');
        $instRecordArray = $instituteManager->getInstituteList($filter,''); 
        $insAddress  = $instRecordArray[0]['instituteAddress1'].' '.$instRecordArray[0]['instituteAddress2'].' '.$instRecordArray[0]['pin'];
        $insWebSite  = $instRecordArray[0]['instituteWebsite'];
        $fileName = IMG_PATH."/Institutes/".$instRecordArray[0]['instituteLogo']; 
        $img='';
        if(file_exists($fileName)) {
          $insLogo = '<img name="logo" src="'.IMG_HTTP_PATH.'/Institutes/'.$instRecordArray[0]['instituteLogo'].'" border="0" width="140" />';
        }
        else {
          $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"valign=\"middle\" >";   
        } 
    

    // Subject Information    
        $conditions = " AND a.timeTableLabelId  = $timeTableLabelId  AND d.classId = $classId "; 
        $concatStr = "'0#0'";   
        $findSubject = $studentReportManager->getClassSubjects($conditions);
        $concatStrSubArray = array();
        foreach($findSubject as $teacherSubjectRecord) {
            $subjectId = $teacherSubjectRecord['subjectId'];
            $classId = $teacherSubjectRecord['classId'];
            if ($concatStr != '') {
                $concatStr .= ',';
            }
            $concatStr .= "'$subjectId#$classId'";
        }
        
        
    //  Attendance List        
        $condition1 = '';    
        if($rollNo!='') {
          $condition1 = " AND s.rollNo LIKE '$rollNo%' "; 
        }
        $condition1 .= " AND CONCAT(att.subjectId,'#',att.classId) IN ($concatStr) ";
   
      
       $attCondition = " AND att.studentId IN ($studentIds) AND att.classId = $classId ";
       $orderBy = 'classId, studentId, subjectName, subjectCode, subjectId';
       $studentShortAtt = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$orderBy,$consolidated);  
       $studentShortArr = count($studentShortAtt);
      

    // Student List
       $field = " DISTINCT 
                            CONCAT(IFNULL(corrAddress1,''),' ',IFNULL(corrAddress2,''),'<br>',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(stu.corrPinCode IS NULL OR stu.corrPinCode='','',CONCAT('-',stu.corrPinCode))) AS corrAdd, 
                            CONCAT(IFNULL(permAddress1,''),' ',IFNULL(permAddress2,''),'<br>',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(stu.permPinCode IS NULL OR stu.permPinCode='','',CONCAT('-',stu.permPinCode))) AS permAdd, 
                            CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                            IFNULL(fatherName,'') AS fatherName, stu.studentId,  
                            stu.rollNo AS rollNo,  IFNULL(studentPhoto,'') AS studentPhoto,
                            stu.universityRollNo AS universityRollNo, 
                            IF(stu.studentMobileNo='','".NOT_APPLICABLE_STRING."',stu.studentMobileNo) AS studentMobileNo ,   
                            SUBSTRING_INDEX(cls.classname,'".CLASS_SEPRATOR."',-4)  AS className";
        $table = "student stu, class cls, student_groups sss";
        $cond = "WHERE 
                        sss.studentId = stu.studentId AND
                        cls.classId = sss.classId AND
                        cls.classId = $classId AND
                        stu.studentId IN ($studentIds) ";
       
        $cond .= " ORDER BY cls.classId, $sortField1 $sortOrderBy";                  
        
        $studentList = $studentManager->getSingleField($table, $field, $cond);                
     
        
         //echo "<pre>"; 
         //print_r($findSubject);
        // print_r($studentShortAtt);
         //die;
       
       
       if(count($studentList) > 0) {
         $recordStatus = 1;    
         for($i=0;$i<count($studentList);$i++) {  // Student List
              $studentId = $studentList[$i]['studentId'];  
              
              if($studentList[$i]['studentPhoto'] != ''){ 
                $File = STORAGE_PATH."/Images/Student/".$studentList[$i]['studentPhoto'];
                if(file_exists($File)){
                   $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentList[$i]['studentPhoto'];
                }
                else{
                   $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
                }
            }
            else{
              $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
            }
            
            $imgSrc = "<img src='".$imgSrc."' width='50' height='50' style='border:1px solid #cccccc' id='studentImageId' class='imgLinkRemove' />";
            $studentList[$i]['imgSrc'] =  $imgSrc;
              
              $student = "";
              $wardList = "";
              $wardList = $contentHead;
               
              $wardCardContents = $contentAddress; 
              if($heading!='') {
                 $mmHead = $heading;
                 $mmHead1 = nl2br("<script>unescape(document.write(heading));</script>");    
                 if($mmHead1!='') {
                    $mmHead1 .= "<br>";  
                 }
                 $wardCardContents = str_replace("<Heading>",$mmHead1,$wardCardContents); 
              }
              
              $fatherName1 = "Mr. ".$studentList[$i]['fatherName'];
              $wardCardContents = str_replace("<ParentsName>",$fatherName1,$wardCardContents);
              if($address=='1') {   
                 $wardCardContents = str_replace("<Address>",$studentList[$i]['corrAdd'],$wardCardContents);  
              }
              else if($address=='2') {   
                 $wardCardContents = str_replace("<Address>",$studentList[$i]['permAdd'],$wardCardContents);  
              }
              
              if($photo=='1') {   
                 $wardCardContents = str_replace("<studentPhoto>",$studentList[$i]['imgSrc'],$wardCardContents);  
              }
              
              $wardList .= trim($wardCardContents);
              
              $wardCardContents = $contentMessage; 
              $wardCardContents = str_replace("<DEAR>","Dear Parent,",$wardCardContents);
              $wardList .= trim($wardCardContents);

              $student .= "<b>Name&nbsp;:&nbsp;</b>".$studentList[$i]['studentName']; 
              $student .= "<b>&nbsp;&nbsp;Univ. Roll No.&nbsp;:&nbsp;</b>".$studentList[$i]['universityRollNo'];
              $student .= "<b>&nbsp;&nbsp;Coll. Roll No.&nbsp;:&nbsp;</b>".$studentList[$i]['rollNo'];
              $student .= "<b>&nbsp;&nbsp;Class&nbsp;:&nbsp;</b>".$studentList[$i]['className'];
              $wardCardContents = $contents; 
              
              if($message!='') {                          
                 $mmMsg = $message;    
                 $mmMsg1 = nl2br("<script>unescape(document.write(message));</script>"); 
                 if($mmMsg1!='') {
                   $mmMsg1 .= "<br>";  
                 }
                 $wardCardContents = str_replace("<PrintMessage>",$mmMsg1,$wardCardContents); 
              }
              
              if($isMessageUpdate=='0') {
                  if(SystemDatabaseManager::getInstance()->startTransaction()) {    
                    $attCommentArray = $studentReportManager->getAttendanceShortComments($mmHead,$mmMsg,$mmSignature);
                    if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
                    }
                  }
                  $isMessageUpdate='1';
              }
              
              $wardCardContents = str_replace("<StudentName>",$studentList[$i]['studentName'],$wardCardContents);
              $wardCardContents = str_replace("<StudentNameId>",$student,$wardCardContents);
              $wardCardContents = str_replace("<Percentage>",$percentage,$wardCardContents);
              $wardList .= trim($wardCardContents);
              $wardList .= trim($contents1);
              $wardList .= trim($courseTable);
              
              $find1=0;
              $courseList = "";
              for($ss=0;$ss<count($findSubject); $ss++) {   // Find Subject
                 $id = ($ss+1);
                 $subjectName = $findSubject[$ss]['subjectName'];
                 $subjectCode = $findSubject[$ss]['subjectCode'];
                 $subjectId = $findSubject[$ss]['subjectId'];   
                 
                 $attendance =  NOT_APPLICABLE_STRING;
                 $attendancePer =  NOT_APPLICABLE_STRING;
                 if($find1==0) {
                   $att = 0;
                   while($att<$studentShortArr) { // Student Attendance
                     if($studentShortAtt[$att]['studentId']==$studentId) {
                        $find1 =1; 
                        break;
                     }
                     $att++;
                   }
                 }
                 while($att<$studentShortArr) { // Student Attendance
                     if($studentShortAtt[$att]['studentId']==$studentId && $studentShortAtt[$att]['subjectId']==$subjectId) {  
                        $per=0; 
                        
                        if($dutyLeaveId=='1' && $medicalLeaveIdId=='1') {
                          if($studentShortAtt[$att]['delivered']==0) {
                            $per=0; 
                          }
                          else {
                            $per= ROUND($studentShortAtt[$att]['per'],2);  
                          }  
                        }
                        else {
                           $ccDelivered = $studentShortAtt[$att]['delivered'];
                           if($ccDelivered >0) {
                              $ccAttended = $studentShortAtt[$att]['attended'];
                              $ccDelivered = $studentShortAtt[$att]['delivered'];
                              $ccLeaveTaken = $studentShortAtt[$att]['leaveTaken'];
                              $ccMedicalLeaveTaken = $studentShortAtt[$att]['medicalLeaveTaken'];  
                              if($dutyLeaveId=='1') {  
                                if($ccLeaveTaken>0) {  
                                  $ccAttended =  $ccAttended + $ccLeaveTaken; 
                                }
                              }
                              if($medicalLeaveId=='1') {  
                                if($ccMedicalLeaveTaken>0) {    
                                  $ccAttended =  $ccAttended + $ccMedicalLeaveTaken;
                                }
                              }
                              $per = ROUND(($ccAttended/$ccDelivered)*100,2);
                            }
                        }
                        
                        if($studentShortAtt[$att]['delivered']==0) {
                          $attendance = NOT_APPLICABLE_STRING;
                          $attendancePer =  NOT_APPLICABLE_STRING;    
                          $dutyLeave =  NOT_APPLICABLE_STRING;  
                          $medicalLeave =  NOT_APPLICABLE_STRING;  
                        }  
                        
                        //if($dutyLeaveId=='1' && $medicalLeaveIdId=='1') {
                           if($per<$percentage) {
                              $attendance    = "<u><b>". ROUND($studentShortAtt[$att]['attended'],0)."/".ROUND($studentShortAtt[$att]['delivered'],0)."</b></u>";
                              $attendancePer = "<u><b>". $per."%</b></u>"; 
                              $dutyLeave =  "<u><b>".ROUND($studentShortAtt[$att]['leaveTaken'],0)."</b></u>"; 
                              $medicalLeave =  "<u><b>".ROUND($studentShortAtt[$att]['medicalLeaveTaken'],0)."</b></u>"; 
                           }
                           if($per>=$percentage)  { 
                              $attendance = ROUND($studentShortAtt[$att]['attended'],0)."/".ROUND($studentShortAtt[$att]['delivered'],0);
                              $attendancePer = $per."%";            
                              $dutyLeave =  ROUND($studentShortAtt[$att]['leaveTaken'],0); 
                              $medicalLeave =  ROUND($studentShortAtt[$att]['medicalLeaveTaken'],0); 
                           }    
                        //}
                        $att++;
                     }
                     else {
                       $attendance =  NOT_APPLICABLE_STRING;
                       $attendancePer =  NOT_APPLICABLE_STRING;
                       $dutyLeave =  NOT_APPLICABLE_STRING;          
                       $medicalLeave =  NOT_APPLICABLE_STRING;               
                     }
                     break;
                 }
                 $courseList .= courseResult($id,$subjectName,$subjectCode,$attendance,$dutyLeave,$medicalLeave,$attendancePer);     
              }
                 
               $wardCardContents = $contents2; 
               $wardCardContents = str_replace("<AttendanceMarksDetail>",$courseList,$wardCardContents);
               $wardList .= trim($wardCardContents);
               $wardList .= trim($contents3);     
               $wardList .= trim($contents4);
               
                if(trim($signature)!='') {   
                   //$wardList .= trim($contents5);   
                   $wardCardContents = $contents5; 
                   $wardCardContents = str_replace("<SIGNATURE>",html_entity_decode(strip_slashes($signature)),$wardCardContents);
                   $wardList .= trim($wardCardContents);
                }
                else {
                   $wardCardContents = $contents6; 
                   $wardCardContents = str_replace("<NOTES>",$notes,$wardCardContents);
                   $wardList .= trim($wardCardContents);
                }
                $wardList .= "</table></table>";
                echo $wardList;
                if($i!=0 || ($i+1)!=count($studentList) ) {
                  echo "<br class='brpage'>";  
                }
             }
     }
      
   
   
    if($recordStatus == '0') {
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(780); 
        $reportManager->setReportHeading('Student Attendance Short Report');
        //$reportManager->setReportInformation("For ".$studentName." As On $formattedDate ");
   ?>
        <table border="0" cellspacing="0" cellpadding="0" width="<?php echo $reportManager->reportWidth?>">
            <tr>
                <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
                <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
                <td align="right" colspan="1" width="25%" class="">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo $formattedDate ;?></td>
                        </tr>
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
            <tr><th colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>>No record found</th></tr>
        </table>  
        
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                <td height="30px"></td>
            </tr>
            <tr>
                
            </tr>
        </table>        
<?php    
}
?>
  
<?php    
// $History: studentAttendanceShortReportPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 3/22/10    Time: 11:33a
//Updated in $/LeapCC/Templates/StudentReports
//format updated (corr. address show)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/22/10    Time: 10:34a
//Updated in $/LeapCC/Templates/StudentReports
//file name updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/10    Time: 12:02p
//Updated in $/LeapCC/Templates/StudentReports
//format & validation updated 
//

?>
