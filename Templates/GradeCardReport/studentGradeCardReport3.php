<?php 
    //This file is used as printing version for student ICard.
    // Author :Parveen Sharma
    // Created on : 26-12-2008
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //--------------------------------------------------------
?>
<script>
    var str=unescape(location.search);
    var strArray=str.split('&reapparMsg=');
    var len=strArray.length;
    var str=str.split('&headValue=');
    var reapparMsg=str[0];
    var headValue=str[1];    
    var str=reapparMsg.split('&reapparMsg=');     
    var reapparMsg=str[1]; 
</script>
<?php              
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentGradeCardReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();  

   
    
    $ones = array("", "First", "Second", "Third", "Four", "Five", "Six", 
                      "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
                      "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen"); 
    
    require_once(TEMPLATES_PATH . "/GradeCardReport/gradeCardTemplate3.php"); 
        
    require_once(MODEL_PATH . "/InstituteManager.inc.php");
    $instituteManager = InstituteManager::getInstance();  
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/GradeCardRepotManager.inc.php");
    $gradeCardRepotManager = GradeCardRepotManager::getInstance();
   
   
    global $sessionHandler;   

    function parseOutput($data){
       return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
    }
     
    
     
    // number_format($number, 2, '.', '');
    // echo '{"totalCGPA":"'.$cgpa.'"}'; 
    global $sessionHandler;   
    
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    global $sessionHandler;    
  
    $degreeId = add_slashes($REQUEST_DATA['degreeId']);     
    $batchId =  add_slashes($REQUEST_DATA['batchId']);     
    $branchChk =  add_slashes($REQUEST_DATA['branchChk']); 
    $sessiondate = add_slashes($REQUEST_DATA['sessiondate']); 
    $printTri = add_slashes($REQUEST_DATA['printTri']); 
    $reexamChk = add_slashes($REQUEST_DATA['reexamChk']);
	$specializationChk = add_slashes($REQUEST_DATA['specializationChk']);    
    
    
    if($reexamChk=='') {
      $reexamChk=0;  
    }
    
    if($printTri=='') {
      $printTri=2;  
    }
    
    if($degreeId=='') {
      $degreeId=0;  
    }
    
    if($batchId=='') {
      $batchId=0;  
    }
    
    if($branchChk=='') {
      $branchChk=0;  
    }
   
    if($sessiondate=='') {
      $sessiondate=0;  
    }
   
    $conditions = " AND b.degreeId = '$degreeId' AND b.batchId = '$batchId' ";
    $sessionwiseChk =  add_slashes($REQUEST_DATA['sessionChk']);
    
    $headValue = trim($REQUEST_DATA['headValue']);

    
    
    // $studentRecordArray = $studentManager->getAllDetailsStudentList($conditions, $orderBy, '');
    // $cnt = count($studentRecordArray);
    
    $studentInformation = "";
    
    $conditions = "";
    $instituteManager = InstituteManager::getInstance();  
    $commonQueryManager = CommonQueryManager::getInstance();
    
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


    // Assistant Controller Signature Information
    $gradeCardContents5 = $contents5;    
    
    $fileName = IMG_PATH."/Icard/signdemo.jpg"; 
    $img='';
    if(file_exists($fileName)) {
      $signLogo = "<img  src=\"".IMG_HTTP_PATH."/Icard/signdemo.jpg \"height=\"45px\" valign=\"middle\" >";
    }
    else {
      $signLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"45px\" \"valign=\"middle\" >";   
    } 
    $gradeCardContents5 = str_replace("<signatureImage>",$signLogo,$gradeCardContents5);           
    
    // Student Information Fetch    
    $classId = '';
    $studentId = add_slashes($REQUEST_DATA['studentId']);     
    $studentArray = explode(",",$studentId); 
    $cnt=count($studentArray);
    
    
    
    $recordStatus ='0';          
    
    $allTrimester = add_slashes($REQUEST_DATA['allTrimester']); 
    $allTrimesterArr = explode(",",$allTrimester); 
    
    $trimester = add_slashes($REQUEST_DATA['trimester']); 
    $trimesterArr = explode(",",$trimester); 
    
    
    $studentClassId = array();
    $studentTrimester = "";
    $j=0;
    for($i=0; $i< count($allTrimesterArr); $i++) {
      $studentClassId[$i] = $allTrimesterArr[$i];   
      if($i==0) 
        $studentTrimester = $studentClassId[$i] ;
      else
        $studentTrimester .= ",".$studentClassId[$i] ;
      if($trimesterArr[$j]==$allTrimesterArr[$i]) {  
        $j++;      
      }
      if(($j==count($trimesterArr))) {
        break;
      }
    }
    
    
    if($cnt==0)  { 
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(800); 
        $reportManager->setReportHeading('Student Grade Card Report');
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
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getFooterStyle();?>>No record found</th></tr>
        </table>  
        
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                <td height="30px"></td>
            </tr>
        </table>        
<?php
    }
    else {
        /*  
          if(add_slashes($REQUEST_DATA['cgpaDetails'])=='1') {  
              $countStudentCGPA = $studentCPGAManager->getStudentClasswiseCGPACount($studentTrimester,$degreeId,$batchId);
              $cgpaGradeCardContents = $cgpaContents;
              if(count($countStudentCGPA)>0){
                $cgpaGradeCardContents = str_replace("<G9>",$countStudentCGPA[0]['total9'],$cgpaGradeCardContents);
                $cgpaGradeCardContents = str_replace("<G8>",$countStudentCGPA[0]['total8'],$cgpaGradeCardContents);
                $cgpaGradeCardContents = str_replace("<G7>",$countStudentCGPA[0]['total7'],$cgpaGradeCardContents);
                $cgpaGradeCardContents = str_replace("<G6>",$countStudentCGPA[0]['total6'],$cgpaGradeCardContents);
                $cgpaGradeCardContents = str_replace("<G5>",$countStudentCGPA[0]['total5'],$cgpaGradeCardContents);
                $cgpaGradeCardContents = str_replace("<G4>",$countStudentCGPA[0]['total4'],$cgpaGradeCardContents);
                $cgpaGradeCardContents = str_replace("<G0>",$countStudentCGPA[0]['total0'],$cgpaGradeCardContents);
                $cgpaGradeCardContents .= "</table></td></tr></table>";
              }
          }
        */   
       $gradePrintArray = array();
       for($i=0;$i<$cnt;$i++) {
          $conditionStudentInfo = " AND ttm.studentId = '".$studentArray[$i]."' AND sp.studyPeriodId IN (".$studentTrimester.")"; 
          $trimesterArray = $gradeCardRepotManager->getStudentStudyPeriodWiseInfo($conditionStudentInfo);
          
          $showFullPeriodicityName =  strtoupper($trimesterArray[0]['periodicityName']);
          $showShortPeriodicityName = strtoupper(substr($trimesterArray[0]['periodicityName'],0,1));
          
          if(count($trimesterArray)>0) { 
             if($i!=0) {
               if($ttPage=='') {  
                 echo "<br class='brpage'>";             
               }
             }
             
             $ttPage='';
             // Student Photo
             $studentPhoto=$trimesterArray[0]['studentPhoto'];
             if($studentPhoto != ''){ 
               $File = STORAGE_PATH."/Images/Student/".$studentPhoto;
               if(file_exists($File)){
                 $imgSrc= STUDENT_PHOTO_PATH."/".$studentPhoto."?xx=".rand(0,1000);
               }
               else{
                 $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
               }
             }
             else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
             }
             $studentImage = "<img src='".$imgSrc."' width='60px' height='60px' style='border:1px solid #cccccc' class='imgLinkRemove' />"; 
             // Student Photo
             
             $triPagewise = array();
             $x=0;
             $count=0;
             for($kk=0; $kk<count($trimesterArray); $kk++) { 
                if($trimesterArray[$kk]['studyPeriodId']==$trimesterArr[$x]) {
                   $triPagewise[] = $trimesterArray[$kk]['sessionName'];
                   $x++;        
                }
             }
          
             $sessionName='';
             $x=0;
             $tempStudyPeriodId='';
             $ttCreditCountAll=0;
             $pageCheck=0; 
             $pageBreak=0;
             
             for($j=0; $j<count($trimesterArray); $j++) {
                $studyId = $trimesterArray[$j]['studyPeriodId'];
                if($tempStudyPeriodId=='') {
                  $tempStudyPeriodId = $trimesterArray[$j]['studyPeriodId'];
                }
                else {
                  $tempStudyPeriodId .=",".$trimesterArray[$j]['studyPeriodId'];
                }
                if($x < count($trimesterArr)) {
                 if($pageCheck==0) {   
                    if($triPagewise[$x]==$triPagewise[$x+1] && x  <= count($trimesterArr)-1 ) {
                       $pageCheck=2;     
                       if($printTri==1) {
                         $pageCheck=1;  
                       }
                    } 
                    else if($triPagewise[$x]!=$triPagewise[$x+1] && x  <= count($trimesterArr)-1 ) {
                       $pageCheck=1;
                    } 
                 }
                    
                 
                 if($trimesterArray[$j]['studyPeriodId']==$trimesterArr[$x]) {
                     
                          $recordStatus=1;             
                          $sessionName=$trimesterArray[$j]['sessionName'];
                          $periodValue = $ones[$trimesterArray[$j]['periodValue']];
                          $periodicityName = $trimesterArray[$j]['periodicityName'];
                          $periodicityFrequency = $trimesterArray[$j]['periodicityFrequency'];  
                          $startDate = UtilityManager::formatDate( $trimesterArray[$j]['startDate']);
                          $endDate = UtilityManager::formatDate( $trimesterArray[$j]['endDate']);
                          $sessionTitleName = $trimesterArray[$j]['sessionTitleName'];
                          
                          $timeTableDate = "";
                          if($sessiondate==1) {
                            $timeTableDate = " ($startDate TO $endDate)";
                          }
                          
                          if( ($periodValue%$periodicityFrequency)==0) {
                            $studyValue = $periodicityFrequency; 
                            $ss = $studyValue; 
                          }
                          else {
                            $studyValue = ($periodValue%$periodicityFrequency);    
                            $ss = $studyValue; 
                          }
                          $studyValue .= " ".$periodicityName.$timeTableDate;    
                          
                          if($pageCheck==1) {
                             $triPagewise1 = $studyValue; 
                          }     
                          
                       if($pageCheck==1 || $pageCheck==2 && $pageBreak==0)  {     
                          $gradeCardContents = $contentHead; 
                          $gradeCardContents = str_replace("<CollegeLogo>",$insLogo,$gradeCardContents);
                          //echo $gradeCardContents ;    
                          $studentInformation = $gradeCardContents;
                         
                          $gradeCardContents='';
                          if(add_slashes($REQUEST_DATA['address'])=='1') {
                              $gradeCardContents = $contentAddress; 
                              $gradeCardContents = str_replace("<To>","To<br>",$gradeCardContents);
                              $gradeCardContents = str_replace("<ParentsName>",$trimesterArray[0]['fatherName'],$gradeCardContents);
                              $gradeCardContents = str_replace("<Address>",$trimesterArray[0]['permAdd'],$gradeCardContents);  
                              $gradeCardContents = str_replace("<STUDENTPHOTO1>",$studentImage,$gradeCardContents);  
                            
                              $studentInformation .= $gradeCardContents;
                          
                              $triMsg = '<br>The performance of your ward is appended below:&nbsp;&nbsp;</p><br>';
                              $gradeCardContents = $contentMessage1;  
                              $gradeCardContents = str_replace("<DEAR>","Dear Parent,",$gradeCardContents);  
                              $gradeCardContents = str_replace("<TRIMESTER>",$triMsg,$gradeCardContents);                             
                            
                              //echo $gradeCardContents ;    
                              $studentInformation .= $gradeCardContents;                
                          }
                          else { 
                              $gradeCardContents = $contentMessage;   
                                //$valResult = $gradeSheet;
                              $headResult = strtoupper($trimesterArray[0]['degreeName']);
                              //if($trimesterArray[0]['branchName']!='' && $branchChk==1) {
                              //  $headResult .=" (".strtoupper($trimesterArray[0]['branchName']).")";
                              //}
                              if($specializationChk==1){
                              	$headResult .= "<br>".strtoupper($trimesterArray[0]['presentBranchName']);
                              }
                              
                              $headResult .= "<br>".strtoupper($instRecordArray[0]['instituteName'])."<br>";

                              $valResult  = "<span style='font-size:12px;'><b>".$headResult."<br>";   
                              $valResult .= "BATCH&nbsp;".ucwords($trimesterArray[0]['batchStart'])."-".ucwords($trimesterArray[0]['batchEnd'])."<br>"; 
                              $valResult .= "SESSION&nbsp;".ucwords($sessionName);
                              if($headValue!='') {
                                $valResult .= "&nbsp;(<script>unescape(document.write(headValue));</script>)";   
                              }
                              $valResult .= "<br><br>&nbsp;</B></span>";
                              $gradeCardContents = str_replace("<GRADESHEET>",$valResult,$gradeCardContents);
                              $gradeCardContents = str_replace("<studentPhoto>",$studentImage,$gradeCardContents);
                              $studentInformation .= $gradeCardContents;                
                          }

                          $studentInformation .= "<br><br>";
                          
                          $gradeCardContents = $contents;
                          // Student Informations
                          $gradeCardContents = str_replace("<IdNumber>",strtoupper($trimesterArray[0]['rollNo']),$gradeCardContents);
                          $gradeCardContents = str_replace("<Name>",strtoupper($trimesterArray[0]['studentName']),$gradeCardContents);
                          $studentInformation .= $gradeCardContents;            
                            
                          $gradeCardContents ="";   
                          if(add_slashes($REQUEST_DATA['address'])=='1') {
                             $gradeCardContents = $contentsP;
                             $gradeCardContents = str_replace("<Programme>",strtoupper($trimesterArray[0]['programme']),$gradeCardContents);
                             $gradeCardContents = str_replace("<TimeDuration>",strtoupper($trimesterArray[0]['timeDuration']),$gradeCardContents);
                             $gradeCardContents = str_replace("<FatherName>",strtoupper($trimesterArray[0]['fatherName']),$gradeCardContents);  
                          }
                          else {
                             $gradeCardContents = $contentsF;
                             $gradeCardContents = str_replace("<FatherName>",strtoupper($trimesterArray[0]['fatherName']),$gradeCardContents);
                          }
                          $studentInformation .= $gradeCardContents;
                          //$studentInformation .= "<br><br><br>";     
                      } 
                      
                          $pageBreak++;
                          $recordArray = $gradeCardRepotManager->getStudentGradeCardInfo(" AND ttm.studentId = '".$studentArray[$i]."' AND cls.studyPeriodId = '".$trimesterArr[$j]."' AND b.batchId = '$batchId' ");
                         
                          $gradeCardContents = $contents1;    
                          $strResult="";
                          if(add_slashes($REQUEST_DATA['address'])=='1') {    
                               if($sessionwiseChk!=1) { 
                                  $studyValue2 = $trimesterArray[$j]['periodValue']." ".$trimesterArray[$j]['periodicityName'];  
                                  //$studyValue2 .="&nbsp;(".$trimesterArray[($x-1)]['sessionName'].")";  
                                  $studyValue2 .=$timeTableDate;  
                                  $strResult = "<strong>PERIOD :</strong> ".strtoupper($studyValue2);
                                  $gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);   
                               }
                               else {   
                                  //$strResult = "<strong>PERIOD :</strong> ".strtoupper($studyValue);                                
                                  $strResult = "<strong>PERIOD :</strong> ".strtoupper($sessionTitleName); 
                                  $gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);  
                               } 
                          }                                                 
                          else {    
                               if($sessionwiseChk!=1) { 
                                  $studyValue2 = strtoupper($periodValue." ".$periodicityName.$timeTableDate); 
                                  $strResult = "<strong><center>".$studyValue2."</center></strong>";
                                  $gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);   
                               }
                               else {   
                                  //$strResult = "<strong><center>".strtoupper($ss." ".$periodicityName.$timeTableDate)."</center></strong>";
                                  $strResult = "<strong><center>".strtoupper($sessionTitleName.$timeTableDate)."</center></strong>";
                                  $gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);  
                               } 
                          }    
                          $studentInformation .= $gradeCardContents;
                          
                          $conditionGrade=" AND ttm.studentId = '".$studentArray[$i]."' AND cls.studyPeriodId = '".$trimesterArr[$x]."' 
                                            AND b.batchId = '$batchId' ";
                          $recordArray = $gradeCardRepotManager->getStudentGradeCardInfo($conditionGrade);
                       
                          
                          $ttCreditCount=0;
                          for($k=0;$k<count($recordArray);$k++) {
                              $gradeCardContents = $contents2; 
                              $examType='';
                              if($recordArray[$k]['examType']=='1') {
                                $examType='';  
                                if($reexamChk==1) {    
                                  $examType='*';
                                }
                                //$noteType="<b>*</b>: Grade improved in second attempt, after supplementary chance given by the University.";
                                $noteType='';
                                if(trim($REQUEST_DATA['reapparMsg'])!='') {
                                  $noteType="<b>*</b>:&nbsp;(<script>unescape(document.write(reapparMsg));</script>)";
                                }
                              }
                       /*       
                              $replaceSubjectsCode = '';
                              if(trim(strtoupper($recordArray[$k]['subjectCode']))=='CSL4209') {
                                $replaceSubjectsCode = 'AML4209'; 
                              }
                              else if(trim(strtoupper($recordArray[$k]['subjectCode']))=='CSL4205') {
                                $replaceSubjectsCode = 'ECL4209'; 
                              }
                              else if(trim(strtoupper($recordArray[$k]['subjectCode']))=='CSP1205') {
                                $replaceSubjectsCode = 'ECP1209'; 
                              }
                              if(trim(strtoupper($recordArray[$k]['rollNo']))=='CUN110103010' || trim(strtoupper($recordArray[$k]['rollNo']))=='CUN110103017' ||
                                 trim(strtoupper($recordArray[$k]['rollNo']))=='CUN110103019' || trim(strtoupper($recordArray[$k]['rollNo']))=='CUN110104016') {
                                 if(trim($replaceSubjectsCode)!='') {
                                   $recordArray[$k]['subjectCode'] = $replaceSubjectsCode;  
                                 }   
                              }
                     */         
                              
                              $subjectsCode = strtoupper($recordArray[$k]['subjectCode']).$examType;
                              $gradeCardContents = str_replace("<CourseCode>",$subjectsCode,$gradeCardContents);
                              $gradeCardContents = str_replace("<CourseName>",strtoupper($recordArray[$k]['subjectName']),$gradeCardContents);
                              $gradeCardContents = str_replace("<Category>",strtoupper($recordArray[$k]['categoryName']),$gradeCardContents);
                              $gradeCardContents = str_replace("<Credits>",$recordArray[$k]['credits'],$gradeCardContents);
                              $gradeCardContents = str_replace("<Grade>","&nbsp;".strtoupper($recordArray[$k]['gradeLabel']),$gradeCardContents);
                              if($recordArray[$k]['gradePoints']>0) { 
                                $ttCreditCount = $ttCreditCount + $recordArray[$k]['credits'];
                              }
                              $studentInformation .=  $gradeCardContents;
                          }
                          // To store student Information
                          $gradePrintArray[] = $studentInformation; 
                          $studentInformation='';
                          
                          $ttCreditCountAll +=$ttCreditCount;
                          
                          $gradeIntoCredits = '';
                          $credits = '';
                          $gradeGPA = '';
                          $gpacredits = '';    
                          $currentCredits = '';
                           
                           // Findout GPA
                           $condition=" s.studentId = '".$studentArray[$i]."' AND c.studyPeriodId = '".$trimesterArr[$x]."'
                                        AND c.batchId = '$batchId' ";
                           $resourceRecordArray = $gradeCardRepotManager->getStudentClasswiseGPA($condition);
                           $gpa1 = UtilityManager::decimalRoundUp($resourceRecordArray[0]['gpa1']);
                           
                          
                          
                           $condition=" s.studentId = '".$studentArray[$i]."' AND c.studyPeriodId IN (".$tempStudyPeriodId.")  AND c.batchId = '$batchId' ";
                           $resourceRecordArray1 = $gradeCardRepotManager->getStudentClasswiseCGPA($condition);
                           $cgpa = UtilityManager::decimalRoundUp($resourceRecordArray1[0]['CGPA']);
                           
                           // Student GPA and CGPA Informations
                           $gradeCardContents = $contents3;       
                           $gradeCardContents = str_replace("<SHOW_FULL_NAME>",$showFullPeriodicityName,$gradeCardContents);                                
                           $gradeCardContents = str_replace("<SHOW_SHORT_NAME>",$showShortPeriodicityName,$gradeCardContents);
                           
                           $gradeCardContents = str_replace("<GradePointAverage>",parseOutput($gpa1),$gradeCardContents);
                           $gradeCardContents = str_replace("<CumulativeGradePointAverage>",parseOutput($cgpa),$gradeCardContents);
                           //$gradeCardContents = str_replace("<CurrentCredits>",parseOutput($resourceRecordArray[0]['credits']),$gradeCardContents); 
                           //$gradeCardContents = str_replace("<EarnedCredits>",parseOutput($resourceRecordArray1[0]['credits']),$gradeCardContents);
                           $gradeCardContents = str_replace("<CurrentCredits>",parseOutput($ttCreditCount),$gradeCardContents); 
                           if(parseOutput($ttCreditCount)==NOT_APPLICABLE_STRING) {
                             //$ttCreditCountAll='';    
                             $gradeCardContents = str_replace("<EarnedCredits>",parseOutput($ttCreditCountAll),$gradeCardContents);    
                           } 
                           else{    
                             $gradeCardContents = str_replace("<EarnedCredits>",parseOutput($ttCreditCountAll),$gradeCardContents);
                           } 
                           $studentInformation .= $gradeCardContents;
                           $studentInformation .= "<br><br>";
                          
                          if($pageBreak==$pageCheck) {
                               if($recordStatus == '1') {
                                  if(add_slashes($REQUEST_DATA['signature'])=='1') {
                                     $studentInformation .=  $gradeCardContents5;   
                                  }
                                  else {
                                     //$gradeCardContents = $contents6; 
                                    /// $gradeCardContents = str_replace("<NOTES>",'',$gradeCardContents);
                                     //$studentInformation .=  $gradeCardContents;
                                  }
                               }
                               $gradeCardContents = $contents6; 
                               $gradeCardContents = str_replace("<NOTES>",'',$gradeCardContents);
                               if($noteType!='') {
                                 $gradeCardContents = str_replace("<EXAM_TYPE_NOTE>",$noteType,$gradeCardContents);
                               }   
                               $studentInformation .=  $gradeCardContents;
                               $studentInformation .= "</table><br class='brpage'>";
                               //echo $studentInformation;
                               $gradePrintArray[] = $studentInformation;
                               $ttPage=1;
                               $studentInformation = "";
                               $pageBreak=0;
                               $pageCheck=0; 
                               $noteType='';
                               $examType='';
                         }
                         $x++;
                   }
                   else {
                      // missing Cumulative Credits Earned               
                      $recordArray11 = $gradeCardRepotManager->getStudentGradeCardInfo(" AND ttm.studentId = '".$studentArray[$i]."' AND cls.studyPeriodId IN (".$trimesterArray[$j]['studyPeriodId'].") AND b.batchId = '$batchId' ");
                      for($kk=0;$kk<count($recordArray11);$kk++) {
                        if($recordArray11[$kk]['gradePoints']>0) { 
                          $ttCreditCountAll = $ttCreditCountAll + $recordArray11[$kk]['credits'];
                        }
                      }
                   }
                }
             }
          }
       }     
    } // else Statement 
    
   if(is_array($gradePrintArray) && count($gradePrintArray)>0 ) {      
      for($i=0;$i<count($gradePrintArray);$i++) {
        echo trim($gradePrintArray[$i]); 
      } 
   }
   else {
      $recordStatus == '0'; 
   }
   
           if($recordStatus == '0') {
                $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
                $reportManager->setReportWidth(800); 
                $reportManager->setReportHeading('Student Grade Card Report');
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
                    <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
                    <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
                    <tr><th colspan="3"  <?php echo $reportManager->getFooterStyle();?>>No record found</th></tr>
                </table>  
                
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                        <td height="30px"></td>
                    </tr>
                </table>        
     <?php    
        }
?>  