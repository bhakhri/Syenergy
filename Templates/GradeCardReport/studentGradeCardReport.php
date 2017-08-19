
<?php 
    //This file is used as printing version for student ICard.
    //
    // Author :Parveen Sharma
    // Created on : 26-12-2008
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
?>
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

    $ones = array("", "First", "Second", "Third", "Fourth", "Fifth", "Sixth", 
                      "Seventh", "Eighth", "Ninth", "Tenth", "Eleventh", "Twelfth", "Thirteenth", 
                      "Fourteenth", "Fifteenth", "Sixteenth", "Seventeenth", "Eightteenth", "Nineteenth"); 
    
    require_once(TEMPLATES_PATH . "/GradeCardReport/gradeCardTemplate.php"); 
        
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

    
    $degreeId = add_slashes($REQUEST_DATA['degreeId']);
    $branchId = add_slashes($REQUEST_DATA['branchId']);     
    $batchId =  add_slashes($REQUEST_DATA['batchId']);     
    
    $branchChk =  add_slashes($REQUEST_DATA['branchChk']);   
    $headerChk  =  add_slashes($REQUEST_DATA['headerChk']);   
    
    $gpaChk =  add_slashes($REQUEST_DATA['gpaChk']);   
    $cgpaChk  =  add_slashes($REQUEST_DATA['cgpaChk']);
    $stuChk= add_slashes($REQUEST_DATA['stuChk']);
    $titleChk= add_slashes($REQUEST_DATA['titleChk']);   
    $authAlign= add_slashes($REQUEST_DATA['authAlign']); 
    
    if($authAlign=='') {
      $authAlign ="'left'";
    }
    
    
    if($gpaChk=='') {
      $gpaChk='0';  
    }
    
    if($cgpaChk=='') {
      $cgpaChk='0';  
    }
    
    if($branchChk=='') {
      $branchChk='0';  
    }
    
    if($headerChk=='') {
      $headerChk='0';  
    }
    
    if($stuChk==''){
      $stuChk='0';
    }
    
    if($titleChk==''){
      $titleChk='0';
    }
    
    $sessiondate ='1';
   
    //$conditions = " AND e.branchId = '$branchId' AND b.batchId = '$batchId' ";
    $conditions = " AND e.branchId = '$branchId' AND b.batchId = '$batchId' AND d.degreeId = '$degreeId' ";
    
    $authorizedName = trim($REQUEST_DATA['authorized']);
    $designation = trim($REQUEST_DATA['designation']);
    
    $studentInformation = "";
    $conditions = "";
   
    
    // Institute Informations 
    $filter = "AND instituteId=".$sessionHandler->getSessionVariable('InstituteId');
    $instRecordArray = $instituteManager->getInstituteList($filter,''); 
    $instituteName = strtoupper($instRecordArray[0]['instituteName']);
    $insAddress  = $instRecordArray[0]['instituteAddress1'].' '.$instRecordArray[0]['instituteAddress2'].' '.$instRecordArray[0]['pin'];
    $insWebSite  = $instRecordArray[0]['instituteWebsite'];
    $fileName = IMG_PATH."/Institutes/".$instRecordArray[0]['instituteLogo']; 
    
    $icardLogo = IMG_PATH."/Icard/".$sessionHandler->getSessionVariable('I_CARD_LOGO');    
    if(file_exists($icardLogo)) {
        $icardLogo = IMG_HTTP_PATH."/Icard/".$sessionHandler->getSessionVariable('I_CARD_LOGO')."?ii=".rand(0,1000);    
        $insLogo = "<img  src=\"".$icardLogo." \"height=\"130px\" \"width=\"170px\" valign=\"middle\" >";
    }
    elseif(trim($instRecordArray[0]['instituteLogo'])=='') {
       $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"valign=\"middle\"  width='170px' height='130px' style='border:1px solid #cccccc' >";   
    }
    else if(file_exists($fileName)) {
      $fileName = IMG_HTTP_PATH.'/Institutes/'.$instRecordArray[0]['instituteLogo']."?ii=".rand(0,500);  
      $insLogo = '<img name="logo" src="'.$fileName.'" width="170px" height="130px"  border="0" style="border:1px solid #cccccc" />';
    }
    else {
      $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"valign=\"middle\" width='170px' height='130px' style='border:1px solid #cccccc' >";   
    } 

    // Assistant Controller Signature Information
    /*
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
    */
    
    // Student Information Fetch    
    $classId = '';
    $studentId = add_slashes($REQUEST_DATA['studentId']);     
    $studentArray = explode(",",$studentId); 
    $cnt=count($studentArray);
    
    
    $recordStatus ='0';          
    
    $allSemester = add_slashes($REQUEST_DATA['allSemester']); 
    $allSemesterArr = explode(",",$allSemester); 
    
    $semester = add_slashes($REQUEST_DATA['semester']); 
    $semesterArr = explode(",",$semester); 
    
    
    $studentClassId = array();
    $studentSemester = "";
    $j=0;
    for($i=0; $i< count($allSemesterArr); $i++) {
      $studentClassId[$i] = $allSemesterArr[$i];   
      if($i==0) 
        $studentSemester = $studentClassId[$i] ;
      else
        $studentSemester .= ",".$studentClassId[$i] ;
      if($semesterArr[$j]==$allSemesterArr[$i]) {  
        $j++;      
      }
      if(($j==count($semesterArr))) {
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
            <tr><th colspan="3" <?php echo $reportManager->getFooterStyle();?>>No data found</th></tr>
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
       for($i=0;$i<$cnt;$i++) {
          $conditionStudentInfo = " AND ttm.studentId = '".$studentArray[$i]."' AND sp.studyPeriodId IN (".$studentSemester.")"; 
          $semesterArray =  $gradeCardRepotManager->getStudentStudyPeriodWiseInfo($conditionStudentInfo);
         
          $showFullPeriodicityName =  strtoupper($semesterArray[0]['periodicityName']);
          $showShortPeriodicityName = strtoupper(substr($semesterArray[0]['periodicityName'],0,1));
          $currBranchName = $semesterArray[0]['currBranchName']; 
          $currDegreeName = $semesterArray[0]['currDegreeName']; 
        
         
          if(count($semesterArray)>0) { 
              
             if($i!=0) {
               if($ttPage=='') {  
                 echo "<br class='brpage'>";             
               }
             }
             
             $ttPage='';
             // Student Photo
             $studentPhoto=$semesterArray[0]['studentPhoto'];
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
             for($kk=0; $kk<count($semesterArray); $kk++) { 
                if($semesterArray[$kk]['studyPeriodId']==$semesterArr[$x]) {
                   $triPagewise[] = $semesterArray[$kk]['sessionName'];
                   $x++;        
                }
             }
             
             $sessionName='';
             $x=0;
             $tempStudyPeriodId='';
             $ttCreditCountAll=0;
             $pageCheck=0; 
             $pageBreak=0;
             
             for($j=0; $j<count($semesterArray); $j++) {
                $studyId = $semesterArray[$j]['studyPeriodId'];
                if($tempStudyPeriodId=='') {
                  $tempStudyPeriodId = $semesterArray[$j]['studyPeriodId'];
                }
                else {
                  $tempStudyPeriodId .=",".$semesterArray[$j]['studyPeriodId'];
                }
                if($x < count($semesterArr)) {
                 if($pageCheck==0) {   
                    if($triPagewise[$x]==$triPagewise[$x+1] && x  <= count($semesterArr)-1 ) {
                       $pageCheck=2;     
                       if($printTri==1) {
                         $pageCheck=1;  
                       }
                    } 
                    else if($triPagewise[$x]!=$triPagewise[$x+1] && x  <= count($semesterArr)-1 ) {
                       $pageCheck=1;
                    } 
                 }
                       
                 if($semesterArray[$j]['studyPeriodId']==$semesterArr[$x]) {
                          $recordStatus=1;             
                          $sessionName=$semesterArray[$j]['sessionName'];
                          $periodValue = $semesterArray[$j]['periodValue'];
                          $periodicityName = $semesterArray[$j]['periodicityName'];
                          $periodicityFrequency = $semesterArray[$j]['periodicityFrequency'];  
                          $startDate = UtilityManager::formatDate( $semesterArray[$j]['startDate']);
                          $endDate = UtilityManager::formatDate( $semesterArray[$j]['endDate']);
                          $timeTableName = $semesterArray[$j]['timeTableLabelName']; 
                          
                          $timeTableDate = "";
                          if($sessiondate==1) {
                            $timeTableDate = " $startDate TO $endDate";
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
                          
                       
                           if( ($pageCheck==1 || $pageCheck==2) && $pageBreak==0)  {     
                              $gradeCardContents = $contentHead; 
                              if($headerChk==1) {
                                $gradeCardContents = str_replace("<INSTITUTELOGO>",$insLogo,$gradeCardContents);
                                $gradeCardContents = str_replace("<INSTITUTENAME>",$instituteName,$gradeCardContents);
                              }
                              
                              //echo $gradeCardContents ;    
                              $studentInformation = $gradeCardContents;
                             
                              $gradeCardContents='';
                              if(add_slashes($REQUEST_DATA['address'])=='1') {
                                  $gradeCardContents = $contentAddress; 
                                  $gradeCardContents = str_replace("<To>","To<br>",$gradeCardContents);
                                  $gradeCardContents = str_replace("<ParentsName>",$semesterArray[0]['fatherName'],$gradeCardContents);
                                  $gradeCardContents = str_replace("<Address>",$semesterArray[0]['permAdd'],$gradeCardContents);  
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
                                  if($titleChk=='1') {
                                    $gradeCardContents = $contentMessage;   
                                        //$valResult = $gradeSheet;     
                                      if($headerChk==1) {          
                                        $gradeCardContents = str_replace("<INSTITUTELOGO>",$insLogo,$gradeCardContents);   
                                      }
                                      
                                      /*
                                      $headResult = strtoupper($semesterArray[0]['degreeName']);
                                      if($semesterArray[0]['branchName']!='' && $branchChk==1) {
                                        $headResult .="<br>".strtoupper($semesterArray[0]['branchName']);
                                      }
                                      */
                                      $headResult = strtoupper($currDegreeName);
                                      if($currBranchName!='' && $branchChk==1) {
                                        $headResult .="<br>".strtoupper($currBranchName);
                                      }
                                      
                                      //$headResult .= "<br>AT ".strtoupper($instRecordArray[0]['instituteName']);
                                      $valResult  = "<span style='font-size:12px;'><b>".$headResult."<br><br>";   
                                      //$valResult .= "BATCH&nbsp;".ucwords($semesterArray[0]['batchStart'])."-".ucwords($semesterArray[0]['batchEnd'])."<br>"; 
                                      $valResult .= "BATCH&nbsp;-&nbsp;".ucwords($semesterArray[0]['batchName'])."<br>"; 
                                      //$valResult .= "ACADEMIC YEAR&nbsp;".ucwords($sessionName);
                                      $valResult .= "<br><br>&nbsp;</B></span>";
                                      $gradeCardContents = str_replace("<GRADESHEET>",$valResult,$gradeCardContents);
                                      $gradeCardContents = str_replace("<studentPhoto>",$studentImage,$gradeCardContents);
                                      $gradeCardContents = str_replace("<REGNO>",strtoupper($semesterArray[0]['regNo']),$gradeCardContents);   
                                      $studentInformation .= $gradeCardContents;  
                                  }              
                              }

                              $studentInformation .= "<br><br>";
                              
                              $gradeCardContents = $contents;
                              
                              // Student Informations
                              $gradeCardContents = str_replace("<ROLLNO>",strtoupper($semesterArray[0]['rollNo']),$gradeCardContents);
                              $gradeCardContents = str_replace("<Name>",strtoupper($semesterArray[0]['studentName']),$gradeCardContents);
                              $gradeCardContents = str_replace("<SEMESTER>",strtoupper($semesterArray[0]['']),$gradeCardContents);
                              
                              $studentInformation .= $gradeCardContents;            
                                
                              $gradeCardContents ="";   
                              if(add_slashes($REQUEST_DATA['address'])=='1') {
                                 $gradeCardContents = $contentsP;
                                 $gradeCardContents = str_replace("<Programme>",strtoupper($semesterArray[0]['programme']),$gradeCardContents);
                                 $gradeCardContents = str_replace("<TimeDuration>",strtoupper($semesterArray[0]['timeDuration']),$gradeCardContents);
                                 $gradeCardContents = str_replace("<FatherName>",strtoupper($semesterArray[0]['fatherName']),$gradeCardContents);  
                              }
                              else {
                                 $gradeCardContents = $contentsF;
                                 $gradeCardContents = str_replace("<ROLLNO>",strtoupper($semesterArray[0]['rollNo']),$gradeCardContents);
                                 $gradeCardContents = str_replace("<SESSION>",strtoupper($semesterArray[0]['timeDuration']),$gradeCardContents);
                              }
                              $studentInformation .= $gradeCardContents;
                              $studentInformation .= "<br><br><br>";     
                          } 
                      
                          $pageBreak++;
                          $recordArray = $gradeCardRepotManager->getStudentGradeCardInfo(" AND ttm.studentId = '".$studentArray[$i]."' AND cls.studyPeriodId = '".$semesterArr[$j]."' AND b.batchId = '$batchId' ");
                         
                          if($stuChk=='1') {
                            $gradeCardContents = $contents1;    
                          }
                          else {
                            $gradeCardContents = $contentsCourse;      
                          }
                          
                          $strResult="";
                          if(add_slashes($REQUEST_DATA['address'])=='1') {    
                               if($sessionwiseChk!=1) { 
                                  $studyValue2 = $semesterArray[$j]['periodValue']." ".$semesterArray[$j]['periodicityName'];  
                                  //$studyValue2 .="&nbsp;(".$semesterArray[($x-1)]['sessionName'].")";  
                                  $studyValue2 .=$timeTableDate;  
                                  $strResult = "<strong>PERIOD :</strong> ".strtoupper($studyValue2);
                                  $gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);  
                               }
                               else {   
                                  $strResult = "<strong>PERIOD :</strong> ".strtoupper($studyValue);   
                                  $gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);  
                               } 
                          }                                                 
                          else {    
                               if($sessionwiseChk!=1) { 
                                  $studyValue2 = strtoupper($periodValue." ".$periodicityName.$timeTableDate); 
                                  $strResult = "<strong><center>".$studyValue2."</center></strong>";
                                  //$gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);   
                                  $timeTableDate = str_replace("(","",$timeTableDate);
                                  $timeTableDate = str_replace(")","",$timeTableDate);
                                  $num1 =  $ones[$periodValue];
                                  $gradeCardContents = str_replace("<Name>",strtoupper($semesterArray[0]['studentName']),$gradeCardContents);
                                  $gradeCardContents = str_replace("<ROLLNO>",strtoupper($semesterArray[0]['rollNo']),$gradeCardContents);  
                                  $gradeCardContents = str_replace("<SEMESTER>",$num1,$gradeCardContents); 
                                  $gradeCardContents = str_replace("<SESSION>",strtoupper($timeTableName),$gradeCardContents); 
                               }
                               else {   
                                  $strResult = "<strong><center>".strtoupper($ss." ".$periodicityName.$timeTableDate)."</center></strong>";
                                  //$gradeCardContents = str_replace("<Trimester>",$strResult,$gradeCardContents);  
                                  $timeTableDate = str_replace("(","",$timeTableDate);
                                  $timeTableDate = str_replace(")","",$timeTableDate);
                                  $num1 =  $ones[$periodValue];
                                  $gradeCardContents = str_replace("<Name>",strtoupper($semesterArray[0]['studentName']),$gradeCardContents);  
                                  $gradeCardContents = str_replace("<ROLLNO>",strtoupper($semesterArray[0]['rollNo']),$gradeCardContents); 
                                  $gradeCardContents = str_replace("<SEMESTER>",$num1,$gradeCardContents); 
                                  $gradeCardContents = str_replace("<SESSION>",strtoupper($timeTableName),$gradeCardContents); 
                               } 
                          }    
                          $studentInformation .= $gradeCardContents;
                          
                          $conditionGrade=" AND ttm.studentId = '".$studentArray[$i]."' AND cls.studyPeriodId = '".$semesterArr[$x]."' 
                                            AND b.batchId = '$batchId' ";
                          $recordArray = $gradeCardRepotManager->getStudentGradeCardInfo($conditionGrade);
                          
                          $ttCreditCount=0;
                          if(count($recordArray)>0) {
                              $ttc=0;
                              $ttotalFinal=0;
                              for($k=0;$k<count($recordArray);$k++) {
                                  $gradeCardContents = $contents2; 
                                  $examType='';
                                  if($recordArray[$k]['examType']=='1') {
                                    $examType='*';
                                    //$noteType="<b>*</b>: Grade improved in second attempt, after supplementary chance given by the University.";
                                    $noteType='';
                                    if(trim($REQUEST_DATA['reapparMsg'])!='') {
                                      $noteType="<b>*</b>:&nbsp;(<script>unescape(document.write(reapparMsg));</script>)";
                                    }
                                  }
                                  if($recordArray[$k]['credits']=='0') {
                                    $recordArray[$k]['gradeLabel'] = NOT_APPLICABLE_STRING;  
                                  }
                                  $subjectsCode = strtoupper($recordArray[$k]['subjectCode']).$examType;
                                  $gradeCardContents = str_replace("<CourseCode>",$subjectsCode,$gradeCardContents);
                                  $gradeCardContents = str_replace("<CourseName>",strtoupper($recordArray[$k]['subjectName']),$gradeCardContents);
                                  $gradeCardContents = str_replace("<Category>",strtoupper($recordArray[$k]['categoryName']),$gradeCardContents);
                                  $gradeCardContents = str_replace("<Credits>",$recordArray[$k]['credits'],$gradeCardContents);
                                  $gradeCardContents = str_replace("<Grade>","&nbsp;".strtoupper($recordArray[$k]['gradeLabel']),$gradeCardContents);
                                  $gradePoints = $recordArray[$k]['gradePoints'];
                                  $totalGradePoints =  $gradePoints * $recordArray[$k]['credits'];
                                   
                                  $ttc += $recordArray[$k]['credits'];
                                  $ttotalFinal += $totalGradePoints;  
                                  
                                  if($recordArray[$k]['gradePoints']=='') {
                                    $gradePoints = NOT_APPLICABLE_STRING;  
                                    $totalGradePoints = NOT_APPLICABLE_STRING;   
                                  }
                                  $gradeCardContents = str_replace("<GradePoint>",$gradePoints,$gradeCardContents);
                                  $gradeCardContents = str_replace("<TotalGradePoint>",$totalGradePoints,$gradeCardContents);
                                  if($recordArray[$k]['gradePoints']>0) { 
                                    $ttCreditCount = $ttCreditCount + $recordArray[$k]['credits'];
                                  }
                                  $studentInformation .=  $gradeCardContents;
                              }
                              
                              if($ttc==0) { 
                                $ttc = NOT_APPLICABLE_STRING; 
                              }
                              if($ttotalFinal==0) {
                                $ttotalFinal = NOT_APPLICABLE_STRING; 
                              }
                                  
                             /* 
                              $rr = '<tr>
                                         <td valign="top" colspan="2" style="border:none"></td>
                                         <td valign="top" align="right"><div style="padding-right:5px"><b>'.$ttc.'</b></div></td>
                                         <td valign="top" colspan="2" style="border:none"></td>
                                         <td valign="top" align="right" ><nobr><b>'.$ttotalFinal.'</b></nobr></td> 
                                     </tr>';
                             */        
                             $rr = '<tr>
                                         <td valign="top">&nbsp;</td>
                                         <td valign="top"><b>Total</b></td>
                                         <td valign="top" align="right"><div style="padding-right:5px"><b>'.$ttc.'</b></div></td>
                                         <td valign="top">&nbsp;</td>
                                         <td valign="top">&nbsp;</td>
                                         <td valign="top" align="right" ><nobr><b>'.$ttotalFinal.'</b></nobr></td> 
                                     </tr>';                                     
                             $gradeCardContents = $contents2a;                                        
                             $gradeCardContents = str_replace("<CREDITSTOTAL>",$rr,$gradeCardContents);   
                             $studentInformation .=  $gradeCardContents;  
                          }
                          
                          $ttCreditCountAll +=$ttCreditCount;
                          
                          $gradeIntoCredits = '';
                          $credits = '';
                          $gradeGPA = '';
                          $gpacredits = '';    
                          $currentCredits = '';
                           
                           // Findout GPA
                           $condition=" s.studentId = '".$studentArray[$i]."' AND c.studyPeriodId = '".$semesterArr[$x]."'
                                        AND c.batchId = '$batchId' ";
                           $resourceRecordArray = $gradeCardRepotManager->getStudentClasswiseGPA($condition);
                          
                           $condition=" s.studentId = '".$studentArray[$i]."' AND c.studyPeriodId IN (".$tempStudyPeriodId.")  AND c.batchId = '$batchId' ";
                           $resourceRecordArray1 = $gradeCardRepotManager->getStudentClasswiseCGPA($condition);
                           $cgpa = UtilityManager::decimalRoundUp($resourceRecordArray1[0]['CGPA']);
                           
                           $gpa = UtilityManager::decimalRoundUp($resourceRecordArray[0]['gpa']);
                           
                           // Student GPA and CGPA Informations
                           if($gpaChk=='1' && $cgpaChk=='0') { 
                             $gradeCardContents = $onlyGPA ;       
                           }
                           else {
                             $gradeCardContents = $contents3; 
                           }
                           
                           if(parseOutput($ttCreditCount)==NOT_APPLICABLE_STRING) {                      
                             $ttCumlative = parseOutput($ttCreditCountAll);
                           }
                           else {
                             $ttCumlative = parseOutput($ttCreditCountAll);  
                           }
                           
                           $showCredits= "<tr>
                                             <td><strong><nobr>CREDITS EARNED</nobr></strong></td>
                                             <td><strong><nobr>&nbsp;:&nbsp;&nbsp;</nobr></strong></td>
                                             <td><nobr>".parseOutput($ttCreditCount)."</nobr></td>
                                           </tr>
                                           <tr><td height='4px'></td></tr>";
                           
                           $showCumlativeCredits= "<tr>
                                             <td><strong><nobr>CUMULATIVE CREDITS EARNED</nobr></strong></td>
                                             <td><strong><nobr>&nbsp;:&nbsp;&nbsp;</nobr></strong></td>
                                             <td><nobr>".parseOutput($ttCreditCountAll)."</nobr></td>
                                           </tr>";  
                           
                           if($gpaChk=='1') {
                             $gpaAbbr=$showShortPeriodicityName."GPA";
                             if($gpaChk=='1' && $cgpaChk=='0') {
                                 $gradeCardContents = $onlyGPA;
                                 $gradeCardContents = str_replace("<SHOW_GPA>",parseOutput($gpa),$gradeCardContents);            
                             }
                             else {
                                $showGPA = "<tr>
                                                <td><strong><nobr>$showFullPeriodicityName GRADE POINT AVERAGE ($gpaAbbr)</nobr></strong></td>
                                                <td><strong><nobr>&nbsp;:&nbsp;&nbsp;</nobr></strong></td>
                                                <td><nobr>".parseOutput($gpa)."</nobr></td>
                                             </tr>
                                             <tr><td height='4px'></td></tr>";
                                 $gradeCardContents = str_replace("<SHOW_GPA>",$showGPA,$gradeCardContents);      
                             }
                           }  
                           
                           if($cgpaChk=='1') {
                             $showCGPA = "<tr>
                                            <td><strong><nobr>CUMULATIVE GRADE POINT AVERAGE (CGPA)</nobr></strong></td>
                                            <td><strong><nobr>&nbsp;:&nbsp;&nbsp;</nobr></strong></td>
                                            <td><nobr>".parseOutput($cgpa)."</nobr></td>
                                         </tr>
                                         <tr><td height='4px'></td></tr>";
                             $gradeCardContents = str_replace("<SHOW_CGPA>",$showCGPA,$gradeCardContents);              
                           }                  
                           
                           if($cgpaChk=='1' && $gpaChk=='1') {                                          
                             $gradeCardContents = str_replace("<SHOW_CREDITS_EARNED>",$showCredits,$gradeCardContents);                                                                      
                             $gradeCardContents = str_replace("<SHOW_CUMULATIVE_CREDITS_EARNED>",$showCumlativeCredits,$gradeCardContents); 
                           }
                           else if($cgpaChk=='1') {
                             $gradeCardContents = str_replace("<SHOW_CUMULATIVE_CREDITS_EARNED>",$showCumlativeCredits,$gradeCardContents);   
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
                               $gradeCardContents = $contentAlign; 
                               if($authAlign=='left'){
                                 $alignPerson="align='left'";
                               }
                               else{
                                 $alignPerson="align='right'";
                               }
                               $gradeCardContents = str_replace("<ALIGN_PERSON>",$alignPerson,$gradeCardContents);
                               $gradeCardContents = str_replace("<AUTHORIZEDNAME>",$authorizedName,$gradeCardContents);
                               $gradeCardContents = str_replace("<DESIGNATION>",$designation,$gradeCardContents);
                               $gradeCardContents = str_replace("<NOTES>",'',$gradeCardContents);
                               if($noteType!='') {
                                 $gradeCardContents = str_replace("<EXAM_TYPE_NOTE>",$noteType,$gradeCardContents);
                               }   
                               $studentInformation .=  $gradeCardContents;
                               $studentInformation .= "</table><br class='brpage'>";
                               echo $studentInformation;
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
                      $recordArray11 = $gradeCardRepotManager->getStudentGradeCardInfo(" AND ttm.studentId = '".$studentArray[$i]."' AND cls.studyPeriodId IN (".$semesterArray[$j]['studyPeriodId'].") AND b.batchId = '$batchId' ");
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
            <tr><th colspan="3"  <?php echo $reportManager->getFooterStyle();?>>No data found</th></tr>
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
<?php    
// $History: scStudentGradeCardReportPrint.php $
//
//*****************  Version 30  *****************
//User: Parveen      Date: 4/07/10    Time: 6:03p
//Updated in $/Leap/Source/Templates/ScGradeCard
//format updated
//
//*****************  Version 29  *****************
//User: Parveen      Date: 4/07/10    Time: 2:30p
//Updated in $/Leap/Source/Templates/ScGradeCard
//new format code updated
//
//*****************  Version 28  *****************
//User: Parveen      Date: 4/07/10    Time: 12:08p
//Updated in $/Leap/Source/Templates/ScGradeCard
//gradePoints > 0 check added 
//
//*****************  Version 27  *****************
//User: Parveen      Date: 1/21/10    Time: 11:09a
//Updated in $/Leap/Source/Templates/ScGradeCard
//format update
//
//*****************  Version 26  *****************
//User: Parveen      Date: 1/13/10    Time: 12:08p
//Updated in $/Leap/Source/Templates/ScGradeCard
//address field updated
//
//*****************  Version 25  *****************
//User: Parveen      Date: 1/12/10    Time: 2:34p
//Updated in $/Leap/Source/Templates/ScGradeCard
//format & validation  updated
//
//*****************  Version 24  *****************
//User: Parveen      Date: 1/12/10    Time: 1:30p
//Updated in $/Leap/Source/Templates/ScGradeCard
//format updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 1/12/10    Time: 12:07p
//Updated in $/Leap/Source/Templates/ScGradeCard
//look & feel updated (No record found updated colspan setting)
//
//*****************  Version 22  *****************
//User: Parveen      Date: 12/28/09   Time: 5:50p
//Updated in $/Leap/Source/Templates/ScGradeCard
//print format update (SessionName added)
//
//*****************  Version 21  *****************
//User: Parveen      Date: 9/11/09    Time: 12:18p
//Updated in $/Leap/Source/Templates/ScGradeCard
//link remove  Include CGPA details (student Ranges are coming up which
//are indicating the no of students in a grade) 
//
//*****************  Version 20  *****************
//User: Parveen      Date: 8/28/09    Time: 3:51p
//Updated in $/Leap/Source/Templates/ScGradeCard
//spelling correct (grade card report)
//
//*****************  Version 19  *****************
//User: Parveen      Date: 7/29/09    Time: 2:56p
//Updated in $/Leap/Source/Templates/ScGradeCard
//student batchwise condition updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 7/11/09    Time: 2:15p
//Updated in $/Leap/Source/Templates/ScGradeCard
//conditions & validation Updated (trimester skip base report generate) 
//
//*****************  Version 17  *****************
//User: Parveen      Date: 7/10/09    Time: 6:54p
//Updated in $/Leap/Source/Templates/ScGradeCard
//issue fix condition, formatting updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 7/08/09    Time: 3:40p
//Updated in $/Leap/Source/Templates/ScGradeCard
//display & formating updated (Pagewise trimester show) 
//
//*****************  Version 15  *****************
//User: Parveen      Date: 7/08/09    Time: 2:36p
//Updated in $/Leap/Source/Templates/ScGradeCard
//printing page setting and format updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 7/08/09    Time: 1:24p
//Updated in $/Leap/Source/Templates/ScGradeCard
//query & functions updated (Display in two trimester Data in One Page)
//
//*****************  Version 13  *****************
//User: Parveen      Date: 7/07/09    Time: 6:54p
//Updated in $/Leap/Source/Templates/ScGradeCard
//formating, alingnment & condition updated
//

?>
