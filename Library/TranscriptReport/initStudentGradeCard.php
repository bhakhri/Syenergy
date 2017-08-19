<?php
//This file contains the student grades
//
// Author :Ajinder Singh
// Created on : 10-Nov-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    
    
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 5000;
    $limit      = ' LIMIT '.$records.',5000';
    

    $studentId='';
    $fatherName=NOT_APPLICABLE_STRING;
    $studentArray = $transcriptReportManager->getSingleField("student s, class c, degree d",
            "DISTINCT CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
             s.studentId, IFNULL(s.rollNo,'') AS rollNo, IFNULL(s.regNo,'') AS regNo,
             IFNULL(s.fatherName,'') AS fatherName", 
             " WHERE s.classId = c.classId AND d.degreeId = c.degreeId ".$condition); 
    if(is_array($studentArray) && count($studentArray)>0 ) { 
       $studentId = $studentArray[0]['studentId'];  
       if($studentArray[0]['fatherName']!='') {
         $fatherName = $studentArray[0]['fatherName']; 
       }
       $studentName = $studentArray[0]['studentName']; 
    } 
     
    if($studentId=='') {
      echo INVALID_ROLL_NO;
      die;  
    } 
    
    $results ='';
    
    if(GRADE_CARD_DESIGN_FORMAT==1) {
        
       $classArray = $transcriptReportManager->getStudentTransferClassList($studentId);  
       
       $srNo=0;
       $results .= "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'>
                      <td width='5%'  class='searchhead_text' align='left'></td>
                      <td width='20%'  class='searchhead_text' align='left'><strong>Course Code</strong></td>
                      <td width='40%' class='searchhead_text' align='left'><strong>Course Name</strong></td>
                      <td width='15%' class='searchhead_text' align='center'><strong>Credits</strong></td>
                      <td width='15%' class='searchhead_text' align='center'><strong>Grade</strong></td>
                    </tr>";   
       
     
       // Regular/ Reappear Subject List
       $reapperSubjectId='';
       $conditionRegular='';
       $conditionReappear='';
       $tempStudyPeriodId = '0';
       for($cc=0;$cc<count($classArray);$cc++) {
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
                   $results .= "<td width='5%' $rowSpan class='searchhead_text' valign='middle' align='left'><b><nobr>$studyPeriodValue</b></td>";
                 }
                 $results .= "<td valign='top' class='padding_top' align='left'>".$subjectCode."</td> 
                              <td valign='top' class='padding_top' align='left'>".$subjectName."</td>
                              <td valign='top' class='padding_top' align='center'>".$credits."</td>
                              <td valign='top' class='padding_top' align='center'>".$gradeLabel."</td>
                             </tr>"; 
              $srNo=$srNo+1; 
              $rowSpan='';                               
            }
            
            if(count($studentSubjectReappearArray)>0) {
                  $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                  $results .= "<tr class='$bg'>
                                <td width='5%' colspan='7' class='searchhead_text' align='left'><b><nobr></b></td>
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
                       $results .= "<td width='5%' $rowSpan class='searchhead_text' valign='middle' align='left'><b><nobr>$studyPeriodValue</b></td>";
                     }
                     
                     $results .= "<td valign='top' class='padding_top' align='left'>".$subjectCode."</td> 
                                  <td valign='top' class='padding_top' align='left'>".$subjectName."</td>
                                  <td valign='top' class='padding_top' align='center'>".$credits."</td>
                                  <td valign='top' class='padding_top' align='center'>".$gradeLabel."</td>
                                 </tr>"; 
                  $srNo=$srNo+1; 
                  $rowSpan='';                               
                }
         }
         $bg = $bg =='trow0' ? 'trow1' : 'trow0';
         $results .= "<tr class='$bg'>
                        <td width='5%' class='searchhead_text' align='left'><b><nobr></b></td>
                        <td width='5%' class='searchhead_text' align='left'><b><nobr></b></td>
                        <td width='5%' class='searchhead_text' align='left'><b><nobr></b></td>
                        <td width='5%' class='searchhead_text' align='center'><b>SGPA&nbsp;:&nbsp;<nobr>".UtilityManager::decimalRoundUp($gpa)."</b></td>
                        <td width='5%' class='searchhead_text' align='center'><b>CGPA&nbsp;:&nbsp;<nobr>".UtilityManager::decimalRoundUp($cgpa)."</b></td>
                      </tr>"; 
                      
      } // End for Class Loop
      
    }
    else {
        $cgpa = NOT_APPLICABLE_STRING;
      
        $studentCGPAArray = $transcriptReportManager->getStudentCGPACal($studentId);
        $cgpa = UtilityManager::decimalRoundUp($studentCGPAArray[0]['cgpa']);
        if (empty($cgpa)) {
          $cgpa = NOT_APPLICABLE_STRING;
        }
        
        
        $classArray = $transcriptReportManager->getStudentClassList($rollNo);             

        $gradePointSum = 0;
        $totalCredits = 0;
        $valueArray = array();
         
         
        $results = "<table  border='1' width='100%' border='0' cellspacing='2' cellpadding='0'><tr>";
        for($i=0;$i<count($classArray);$i++) {
            $tableData="";
            $classId = $classArray[$i]['classId'];
            $periodName = $classArray[$i]['periodName'];
            $cgpa=$studentCGPAArray[0]['cgpa'];


            $condition =  " AND e.classId = '$classId'";                
            $studentGradesArray = $transcriptReportManager->getScStudentGradeDetails($studentId,$condition,$orderBy1);                        
            $cnt = count($studentGradesArray);
            $tableData = "<td valign='top'>
                          <table border='1' width='10%' border='0' cellspacing='2' cellpadding='0'>
                            <tr>
                                <td  border='1' colspan='6' class='searchhead_text' align='center'><b><nobr>$periodName</nobr></b></td>
                            </tr>
                            <tr class='rowheading'>
                              <td width='2px'  class='searchhead_text' align='left'><b><nobr>#</b></td>
                              <td width='5px'  class='searchhead_text' align='left'><strong>Course Code</strong></td>
                              <td width='20px' class='searchhead_text' align='left'><strong>Course Name</strong></td>
                              <td width='20px' class='searchhead_text' align='left'><strong>Credits</strong></td>
                              <td width='20px' class='searchhead_text' align='left'><strong>Letter Grade</strong></td>
                              <td width='20px' class='searchhead_text' align='left'><strong>Grade Point</strong></td>
                            </tr>";  
            
                for($j=0;$j<$cnt;$j++) {
                        $subjectCode = $studentGradesArray[$j]['subjectCode'];
                        $subjectName = $studentGradesArray[$j]['subjectName']; 
                        $credits    = $studentGradesArray[$j]['credits'];   
                        $gradeLabel = $studentGradesArray[$j]['gradeLabel'];
                        $gradePoints = $studentGradesArray[$j]['gradePoints'];
     
                        /*  if($updateExamType=='1') {
                              $subjectCode .="<font color='red'>*</font>";
                            }
                            $updateExamType = $studentGradesArray[$j]['updatedExamType'];
                            $gradePoints = $studentGradesArray[$j]['gradePoints'];
                            $credits = $studentGradesArray[$j]['credits'];
                            $totalCredits += $credits;
                            $gradePointSum += $gradePoints * $credits;
                        */
                        
                        $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                        $tableData .= "<tr class='$bg'>
                                          <td valign='top' class='padding_top' align='left'>".($j+1)."</td> 
                                          <td valign='top' class='padding_top' align='left'>".$subjectCode."</td> 
                                          <td valign='top' class='padding_top' align='left'>".$subjectName."</td>
                                          <td valign='top' class='padding_top' align='left'>".$credits."</td>
                                          <td valign='top' class='padding_top' align='left'>".$gradeLabel."</td>
                                          <td valign='top' class='padding_top' align='left'>".$gradePoints."</td>
                                       </tr>";
         /*  $tableData .=  "<table border='1' width='10%' border='0' cellspacing='2' cellpadding='0'><tr>
                            <td valign='top' border='1' colspan='4' class='searchhead_text' align='center'><b><nobr>$cgpa</nobr></b></td>
                                    </tr>";
           $tableData .="</table>";*/

                        /*
                        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentGradesArray[$i]);
                        if(trim($json_val)=='') {
                          $json_val = json_encode($valueArray);
                        }
                        else {
                          $json_val .= ','.json_encode($valueArray);           
                        }
                        */
                    }    
                      if($j==($cnt-1)) {                    
                      $tableData .= "<tr class='$bg'>
                                       <td valign ='top' colspan='5'  class='padding_top'  align='center'><strong>".$periodName."-GPA</strong></td>
                                       <td valign ='top' class='padding_top' align='left'>".$cgpa."</td>
                                    </tr>"; 
                   }
           // }
            $tableData .="</table>
                          </td>";
            $results .= $tableData;
        }
        $results .="</tr><tr><td colspan='4'><strong>Current CGPA : ".$cgpa."</td</tr></table>";
    }
    echo $cgpa.'!~~!'.$fatherName.'!~~!'.$studentName.'!~~!'.$results.'!~~!'.$studentId;
    
    die;
    //echo '{"StudentName":"'.$studentName.'","Id":"'.$studentId.'","FatherName":"'.$fatherName.'","CurrentCGPA":"'.$cgpa.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","results":"'.$results.'"}';
?>
