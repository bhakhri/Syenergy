<?php
//This file saves student grades
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");

require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyGrade');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();


$labelId = $REQUEST_DATA['labelId'];
$subjectId = $REQUEST_DATA['subjectId'];
$degreeId = $REQUEST_DATA['degreeId'];
$gadeLabelId = $REQUEST_DATA['gadeLabelId'];
$degreeList = $degreeId;
$gradingFormula = $REQUEST_DATA['gradingFormula'];
$ttGradeFrom = $REQUEST_DATA['ttGradeFrom'];   
$ttGradeTo = $REQUEST_DATA['ttGradeTo'];   
$hiddenGradeId = $REQUEST_DATA['hiddenGradeId'];   
    
$pendingStudents='0';
$gradeRangesArray = array();

$j=0;
$rangArray = $studentManager->getGradeLabels($gadeLabelId);    
for($i=0;$i<count($rangArray);$i++) {
  for($k=0;$k<count($ttGradeFrom);$k++) {  
      if($rangArray[$i]['gradeId']==$hiddenGradeId[$k]) {
        $gradeRangesArray[$j]['gradingRangeFrom'] = $ttGradeFrom[$k];  
        $gradeRangesArray[$j]['gradingRangeTo'] = $ttGradeTo[$k];  
        $gradeRangesArray[$j]['gradeId'] = $hiddenGradeId[$k];  
        $gradeRangesArray[$j]['gradePoints'] = $rangArray[$i]['gradePoints']; 
        $gradeRangesArray[$j]['gradeLabel'] = $rangArray[$i]['gradeLabel'];
        $gradeRangesArray[$j]['studentCount'] = 0;   
        $j++;
      }
  }
}

$studentCountArray = array();
$i = 0;
foreach($gradeRangesArray as $gradeRange) {
    $gradingRangeFrom = $gradeRange['gradingRangeFrom'];
    $gradingRangeTo = $gradeRange['gradingRangeTo'];
    $gradeId = $gradeRange['gradeId'];
    $studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFrom, $gradingRangeTo, $degreeList,$gradingFormula);
    $studentCountArray[$i] = $gradeRange;
    $studentCountArray[$i]['studentCount'] = count($studentsArray);
    $i++;
}




$totalArray = Array('pendingStudents' => $pendingStudents, 'studentArray' => $studentCountArray);
echo json_encode($totalArray);
?>