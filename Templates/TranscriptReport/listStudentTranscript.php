<?php
//This file contains the student grades
//
// Author :Ajinder Singh
// Created on : 10-Nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TranscriptReport');
    define('ACCESS','view');
    global $sessionHandler;
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='4') {
       UtilityManager::ifStudentNotLoggedIn(); 
    }
    else if($roleId=='3') {
        UtilityManager::ifParentNotLoggedIn();   
    }
    else {
       UtilityManager::ifNotLoggedIn(); 
    }
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/TranscriptReportManager.inc.php");
    $transcriptReportManager = TranscriptReportManager::getInstance(); 
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");     
    $studentManager =  StudentManager::getInstance();      
    
    require_once(MODEL_PATH . "/GradeCardRepotManager.inc.php");
    $gradeCardRepotManager = GradeCardRepotManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    
    require_once(TEMPLATES_PATH . "/TranscriptReport/listTranscriptTemplate.php");
    $transcriptContent = $content;
    

    global $sessionHandler;
    $gradeCard = $sessionHandler->getSessionVariable('ST_ALLOW_GRADE_CARD');  


    $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
                    'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
                    'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
                    
    function getInt2Roman($num='') {
       
       global $lookup;
       $result ='';
       foreach ($lookup as $roman => $value){
         // Determine the number of matches
         $matches = intval($num / $value);

         // Store that many characters
         $result .= str_repeat($roman, $matches);

         // Substract that from the number
         $num = $num % $value;
       }
       return $result;
     }                                           
    
    if($roleId==3 || $roleId==4) {
      $studentId = $sessionHandler->getSessionVariable('StudentId');  
      $condition = " AND s.studentId = '".$studentId."'";
    }
    else {     
      $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));  
      $condition = " AND (s.universityRollNo LIKE '".$rollNo."' OR s.rollNo LIKE '".$rollNo."')";
    }
 
 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    $orderBy1 = "classId, $sortField $sortOrderBy";   
    
    

    $studentId='';
    $fatherName=NOT_APPLICABLE_STRING;
    $studentArray = $transcriptReportManager->getSingleField("student s, class c, degree d, branch b, institute i ",
            "DISTINCT CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
             s.studentId, IFNULL(s.rollNo,'') AS rollNo, IFNULL(s.regNo,'') AS regNo, b.branchCode, b.branchName, 
             d.degreeName,  d.degreeCode, IFNULL(s.fatherName,'') AS fatherName,
             i.instituteName, i.instituteCode, i.instituteLogo
             ", 
             " WHERE 
                    s.classId = c.classId AND d.degreeId = c.degreeId AND 
                    b.branchId = c.branchId AND i.instituteId = c.instituteId".$condition); 
    if(is_array($studentArray) && count($studentArray)>0 ) { 
       $studentId = $studentArray[0]['studentId'];  
       $studentName = $studentArray[0]['studentName']; 
       $fatherName = $studentArray[0]['fatherName']; 
       $rollNo = $studentArray[0]['rollNo'];  
       $regNo = $studentArray[0]['regNo'];  
       $degreeName = $studentArray[0]['degreeName'];  
       $branchCode = $studentArray[0]['branchCode']; 
       $branchName = $studentArray[0]['branchName']; 
       $instituteName = $studentArray[0]['instituteName'];  
       $instituteCode = $studentArray[0]['instituteCode'];  
       $instituteLogo = $studentArray[0]['instituteLogo'];  
    } 
     
    if($studentId=='') {
      echo INVALID_ROLL_NO;
      die;  
    } 
    
     
    $results ='';
    if(GRADE_CARD_DESIGN_FORMAT==1) {
       $printHeader = trim($REQUEST_DATA['printHeader']);
       $printStudyPeriod = trim($REQUEST_DATA['printStudyPeriod']);
       
       if($printHeader=='') {
         $printHeader ='0';   
       }
       if($printStudyPeriod=='') {
         $printStudyPeriod='2';  
       } 
        
       $classArray = $transcriptReportManager->getStudentTransferClassList($studentId);  
       if(is_array($classArray) && count($classArray)>0 ) { 
           if($printHeader=='1') {
              $fileName = IMG_PATH."/Institutes/".$instituteLogo;    
              if(trim($instituteLogo)=='') {
                $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"valign=\"middle\"  width='170px' height='130px' style='border:1px solid #cccccc' >";   
              }
              else if(file_exists($fileName)) {
                $fileName = IMG_HTTP_PATH.'/Institutes/'.$instituteLogo."?ii=".rand(0,500);  
                $insLogo = '<img name="logo" src="'.$fileName.'" width="170px" height="130px"  border="0" style="border:1px solid #cccccc" />';
              }
              else {
                $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"valign=\"middle\" width='170px' height='130px' style='border:1px solid #cccccc' >";   
              } 
              $transcriptContent = str_replace("<SrNoLabel>","Sr. No.:&nbsp;",$transcriptContent);
              $transcriptContent = str_replace("<INSTITUTELOGO>",$insLogo,$transcriptContent);
              $transcriptContent = str_replace("<INSTITUTENAME>",strtoupper($instituteName),$transcriptContent);
           }
           $transcriptContent = str_replace("<IssueDate>",strtoupper(date('d-M-Y')),$transcriptContent);
           $transcriptContent = str_replace("<StudentName>",strtoupper($studentName),$transcriptContent);
           $transcriptContent = str_replace("<FatherName>",strtoupper($fatherName),$transcriptContent);
           $transcriptContent = str_replace("<RollNo>",strtoupper($rollNo),$transcriptContent);
           $transcriptContent = str_replace("<RegNo>",strtoupper($regNo),$transcriptContent);
           $transcriptContent = str_replace("<ProgramName>",strtoupper($degreeName)." (".strtoupper($branchName).")",$transcriptContent);
            
           $results = "";
           $srNo=0;
           
           $transcriptContentHeaderDetail = $transcriptContent;
           
            
           // Regular/ Reappear Subject List
           $reapperSubjectId='';
           $conditionRegular='';
           $conditionReappear='';
           $tempStudyPeriodId = '0';
           $ttPrintStudyPeriod=1;
           $checkPrintStudyPeriod=0;
           for($cc=0;$cc<count($classArray);$cc++) {
                 if($cc!='0') {
                   if($checkPrintStudyPeriod=='1') {
                     $transcriptContent .= "<br class='page'>" ;  
                     $transcriptContent .= $transcriptContentHeaderDetail;  
                     $ttPrintStudyPeriod=1;
                     $checkPrintStudyPeriod=0;
                   }
                 }
                 $classId = $classArray[$cc]['classId'];
                 $ttStudyPeriodId = $classArray[$cc]['studyPeriodId']; 
                 $ttBatchId = $classArray[$cc]['batchId']; 
                 
                  
                 $tempStudyPeriodId .=",".$ttStudyPeriodId;
                  
                 // Findout GPA & CGPA
                 $condition=" s.studentId = '".$studentId."' AND c.studyPeriodId = '".$ttStudyPeriodId."' AND c.batchId = '$ttBatchId' ";
                 $resourceRecordArray = $gradeCardRepotManager->getStudentClasswiseGPA($condition);
              
                 $condition=" s.studentId = '".$studentId."' AND c.studyPeriodId IN (".$tempStudyPeriodId.")  AND c.batchId = '$ttBatchId' ";
                 $resourceRecordArray1 = $gradeCardRepotManager->getStudentClasswiseCGPA($condition);
                 $cgpa = UtilityManager::decimalRoundUp($resourceRecordArray1[0]['CGPA']);
                 $gpa = UtilityManager::decimalRoundUp($resourceRecordArray[0]['gpa']);
                  
                   
                  $studentSubjectRegularArray = array();
                  $studentSubjectReappearArray = array();
                  $studentGradeArray = array();
                  $conditionRegular='';
                  $conditionReappear='';
                  
                  
                  // Check for Regular Subject
                  if($reapperSubjectId!='') {
                    $conditionRegular = " AND b.subjectId NOT IN ($reapperSubjectId) ";
                  }
                  $studentSubjectRegularArray = $transcriptReportManager->getStudentSubjectList($classId,$studentId,$conditionRegular);

                  // Check for Reappear Subject
                  if($reapperSubjectId!='') {
                    $conditionReappear = " AND b.subjectId IN ($reapperSubjectId) ";              
                    $studentSubjectReappearArray = $transcriptReportManager->getStudentSubjectList($classId,$studentId,$conditionReappear);
                  }
                  
                  // Student Grade List
                  $studentGradeArray = $transcriptReportManager->getStudentGradeList($classId,$studentId);
                  
                  $rowSpan = '';
                  $dif = count($studentSubjectRegularArray)+count($studentSubjectReappearArray);
                  if(count($studentSubjectReappearArray)>0) {
                    $dif++;  
                  }
                  if($dif > 0) {
                    $rowSpan = " rowspan='$dif' ";  
                  }
                 
                  // Check for Regular Subject 
                  for($i=0;$i<count($studentSubjectRegularArray);$i++) {
                     $ttSubjectId = $studentSubjectRegularArray[$i]['subjectId']; 
                     $ttClassId = $studentSubjectRegularArray[$i]['classId']; 
                     
                     
                     if($reapperSubjectId=='') {
                       $reapperSubjectId = $ttSubjectId; 
                     }
                     else {
                       $reapperSubjectId .=",".$ttSubjectId; 
                     }
                     
                     
                     
                     $subjectCode = $studentSubjectRegularArray[$i]['subjectCode'];
                     $credits = $studentSubjectRegularArray[$i]['credits'];    
                     $subjectName = $studentSubjectRegularArray[$i]['subjectName'];  
                     $periodName = $studentSubjectRegularArray[$i]['periodicityName'];  
                     $periodValue = $studentSubjectRegularArray[$i]['periodValue'];  
                     $studyPeriodValue = ucwords(strtolower($periodName))."-".getInt2Roman(intval($periodValue));
                
                     $updateExamType = '';
                     $gradeLabel = NOT_APPLICABLE_STRING;
                     
                     for($j=0;$j<count($studentGradeArray);$j++) {
                        if($ttClassId==$studentGradeArray[$j]['classId'] && $ttSubjectId == $studentGradeArray[$j]['subjectId'] && $studentId == $studentGradeArray[$j]['studentId'] ) {
                          $updateExamType = $studentGradeArray[$j]['updatedExamType'];
                          $gradeLabel = $studentGradeArray[$j]['gradeLabel'];  
                          $gradePoints = $studentGradeArray[$j]['gradePoints'];
                          break;
                        }
                     }
                     $gradePoints = $recordArray[$k]['gradePoints'];
                     $totalGradePoints =  $gradePoints * $recordArray[$k]['credits'];
                     
                     if($gradeLabel=='F') {
                       $subjectCode = $studentSubjectRegularArray[$i]['subjectCode']."<font color='red'>*</font>";  
                       $gradeLabel = "F";  
                     }
                     $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                     $results .= "<tr class='$bg'>";
                     if($rowSpan!='') { 
                       $results .= "<td width='5%' $rowSpan valign='middle' align='left'><b><nobr>$studyPeriodValue</b></td>";
                     }
                     $results .= "<td valign='top' align='left'>".$subjectCode."</td> 
                                  <td valign='top' align='left'>".$subjectName."</td>
                                  <td valign='top' align='center'>".$credits."</td>
                                  <td valign='top' align='center'>".$gradeLabel."</td>
                                 </tr>"; 
                  $srNo=$srNo+1; 
                  $rowSpan='';                               
                }
            
                    if(count($studentSubjectReappearArray)>0) {
                          $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                          $results .= "<tr class='$bg'>
                                        <td width='5%' colspan='7'  align='left'><b><nobr></b></td>
                                      </tr>";
                          
                          // Check for Regular Subject 
                          for($i=0;$i<count($studentSubjectReappearArray);$i++) {
                             $ttSubjectId = $studentSubjectReappearArray[$i]['subjectId']; 
                             $ttClassId = $studentSubjectReappearArray[$i]['classId']; 
                             
                             if($reapperSubjectId=='') {
                               $reapperSubjectId = $ttSubjectId; 
                             }
                             else {
                               $reapperSubjectId .=",".$ttSubjectId; 
                             }
                             
                             $subjectCode = $studentSubjectReappearArray[$i]['subjectCode']."<font color='red'>*</font>";
                             $credits = $studentSubjectReappearArray[$i]['credits'];    
                             $subjectName = $studentSubjectReappearArray[$i]['subjectName'];  
                             $periodName = $studentSubjectReappearArray[$i]['periodicityName'];  
                             $periodValue = $studentSubjectReappearArray[$i]['periodValue'];  
                             $studyPeriodValue = ucwords(strtolower($periodName))."-".getInt2Roman(intval($periodValue));
                        
                             $updateExamType = '';
                             $gradeLabel = NOT_APPLICABLE_STRING;
                             
                             for($j=0;$j<count($studentGradeArray);$j++) {
                                if($ttClassId==$studentGradeArray[$j]['classId'] && $ttSubjectId == $studentGradeArray[$j]['subjectId'] && $studentId == $studentGradeArray[$j]['studentId'] ) {
                                  $updateExamType = $studentGradeArray[$j]['updatedExamType'];
                                  $gradeLabel = $studentGradeArray[$j]['gradeLabel'];  
                                  $gradePoints = $studentGradeArray[$j]['gradePoints'];
                                  break;
                                }
                             }
                             
                             if($gradeLabel=='F') {
                               $subjectCode = $studentSubjectReappearArray[$i]['subjectCode']."<font color='red'>*</font>";  
                               $gradeLabel = "F";  
                             }
                             $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                             $results .= "<tr class='$bg'>";
                             if($rowSpan!='') { 
                               $results .= "<td width='5%' $rowSpan  valign='middle' align='left'><b><nobr>$studyPeriodValue</b></td>";
                             }
                             
                             $results .= "<td valign='top' align='left'>".$subjectCode."</td> 
                                          <td valign='top' align='left'>".$subjectName."</td>
                                          <td valign='top' align='center'>".$credits."</td>
                                          <td valign='top' align='center'>".$gradeLabel."</td>
                                         </tr>"; 
                          $srNo=$srNo+1; 
                          $rowSpan='';                               
                        }
                 }
                 $bg = $bg =='trow0' ? 'trow1' : 'trow0';
                 $results .= "<tr class='$bg'>
                                <td width='5%' align='left'><b><nobr></b></td>
                                <td width='5%' align='left'><b><nobr></b></td>
                                <td width='5%' align='left'><b><nobr></b></td>
                                <td width='5%' align='center'><b>SGPA&nbsp;:&nbsp;<nobr>".UtilityManager::decimalRoundUp($gpa)."</b></td>
                                <td width='5%' align='center'><b>CGPA&nbsp;:&nbsp;<nobr>".UtilityManager::decimalRoundUp($cgpa)."</b></td>
                              </tr>"; 
                              
                if($printStudyPeriod==$ttPrintStudyPeriod) {
                   $transcriptContent = str_replace("<CourseDetail>",$results,$transcriptContent);  
                   $results='';
                   $checkPrintStudyPeriod=1;                     
                }
                else {
                  $ttPrintStudyPeriod++;              
                }
              } // End for Class Loop
              $transcriptContent = str_replace("<CourseDetail>",$results,$transcriptContent); 
              
              echo $transcriptContent;
              die;
       }
    }
    

    
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(800); 
        $reportManager->setReportHeading('Transcript Report Print');
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

