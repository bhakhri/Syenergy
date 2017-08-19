<?php
//This file is used as printing for student Percentage wise Attendance Reports
//
// Author :Parveen Sharma
// Created on : 05-12-2008
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
    
    require_once(BL_PATH . '/ReportManager.inc.php');       
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/TranscriptReportManager.inc.php");
    $transcriptReportManager = TranscriptReportManager::getInstance();   
   
    global $sessionHandler;
    $gradeCard = $sessionHandler->getSessionVariable('ST_ALLOW_GRADE_CARD');  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    $orderBy1 = "classId, $sortField $sortOrderBy";   
    

   if($roleId==3 || $roleId==4) {
      $studentId = $sessionHandler->getSessionVariable('StudentId');  
      $condition = " WHERE studentId = '".$studentId."'";
    }
    else { 	
      $id = add_slashes(trim($REQUEST_DATA['id']));   
      $condition = " WHERE universityRollNo LIKE '".$id."' OR rollNo LIKE '".$id."'";
    }

    
    
    $fatherName=NOT_APPLICABLE_STRING;
    $rollNo=NOT_APPLICABLE_STRING;
    $studentArray = $transcriptReportManager->getSingleField("student","CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,IFNULL(rollNo,'') AS rollNo, studentId,IFNULL(fatherName,'') AS fatherName", $condition); 
    if(is_array($studentArray) && count($studentArray)>0 ) { 
       $studentId = $studentArray[0]['studentId'];  
       if($studentArray[0]['fatherName']!='') {
         $fatherName = $studentArray[0]['fatherName']; 
       }
       if($studentArray[0]['rollNo']!='') {
         $rollNo = $studentArray[0]['rollNo']; 
       }
       $studentName = $studentArray[0]['studentName'];  
    } 
    
    if($studentId=='') {
      $studentId =0;
    }
   
    $studentGradesArray = $transcriptReportManager->getScStudentGradeDetails($studentId,'',$orderBy1);
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
                              <table border='0' width='10%' border='0' cellspacing='2' cellpadding='0'>
                                <tr>
                                    <td  border='0' colspan='10' class='searchhead_text' align='center'><b>$periodName</b></td>
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
 
                    /*
                    if($updateExamType=='1') {
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
	           $tableData .= "<tr class='$bg'>
				   <td valign ='top' colspan='5'  class='padding_top'  align='center'><strong>".$periodName."-GPA</strong></td>
                                   <td valign ='top' class='padding_top' align='left'>".$cgpa."</td>
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
       // }
        $tableData .="</table>
                      </td>";
        $results .= $tableData;
    }
    $results .="</tr><tr><td colspan='4'><strong>Current CGPA : ".$cgpa."</td</tr></table>";

              
    echo ' <b>Student Name:</b>'.$studentName. ' <b>Father\'s Name:</b>'.$fatherName.$results;
    die;
   
    //echo '{"StudentName":"'.$studentName.'","Id":"'.$studentId.'","FatherName":"'.$fatherName.'","CurrentCGPA":"'.$cgpa.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","results":"'.$results.'"}';
?>
