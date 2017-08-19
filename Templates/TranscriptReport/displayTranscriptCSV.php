<?php 
//This file is used as CSV for student Percentage wise Attendance Reports 
//
// Author :Parveen Sharma
// Created on : 05-12-2008
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

    
    
    //--------------------------------------------------------       
    //Purpose:To escape any newline or comma present in data
    //Author: Dipanjan Bhattacharee
    //Date: 31.10.2008
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------   
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
           return $comments;    
         }
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
    $classArray = $transcriptReportManager->getStudentClassList($rollNo);             

    $studentCGPAArray = $transcriptReportManager->getStudentCGPACal($studentId);
    $cgpa = UtilityManager::decimalRoundUp($studentCGPAArray[0]['cgpa']);
    if (empty($cgpa)) {
      $cgpa = NOT_APPLICABLE_STRING;
    } 
 
    
    $csvData = "StudentName".' , '.parseCSVComments($studentName)."\n";
    $csvData .= "Roll`No.".' , '.parseCSVComments($rollNo)."\nFather'sName".' , '.parseCSVComments($fatherName)."\n";
//."\nCurrent CGPA,".parseCSVComments($cgpa)."\n";
for($i=0;$i<count($classArray);$i++) {
    $periodName = $classArray[$i]['periodName'];
    
    $csvData .= "# \",\" Period Name \",\" CourseCode \",\" Course  Name \",\" Credits \",\" LetterGrade \",\" GradePoint \",\" "; 
    }
    $gradePointSum = 0;
    $totalCredits = 0;
    $valueArray = array();
    $i=0;
for($i=0;$i<count($classArray);$i++) {   
    $cnt = count($studentGradesArray);
for($j=0;$j<$cnt;$j++) {
        $subjectCode = $studentGradesArray[$j]['subjectCode'];
        $subjectName = $studentGradesArray[$j]['subjectName'];   
        $credits = $studentGradesArray[$j]['credits']; 
        $gradeLabel = $studentGradesArray[$j]['gradeLabel'];
        $gradePoints = $studentGradesArray[$j]['gradePoints'];
   }     
}
        $csvData .= "\n";
        $csvData .= ($records+$j+1).' , '.parseCSVComments($periodName).' , '.parseCSVComments($subjectCode).' , '.parseCSVComments($subjectName);
        $csvData .= ' , '.parseCSVComments($credits).' , '.parseCSVComments($gradeLabel).' , '.parseCSVComments($gradePoints);
        $csvData .= "\n";

     

    if($i==0) {
      $csvData .= "No Data Found";  
    }
    
    
    UtilityManager::makeCSV($csvData,'DisplayStudentGradeReport.csv');      
    die;
?>
