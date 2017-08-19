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
define('MODULE','ApplyGrades');
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
$manualChoice=$REQUEST_DATA['manualChoice']; 
/*
$pendingStudentsArray = $studentManager->checkSubjectNotTransferredStudents($subjectId, $degreeList);
$pendingStudents = $pendingStudentsArray[0]['cnt'];
*/
$pendingStudents = 0;
if ($pendingStudents > 0) {
	echo MARKS_NOT_TRANSFERRED_FOR_ALL_STUDENTS;
	die;
}

//$gradeRangesArray = $studentManager->getGrades();
$gradeRangesArray = $studentManager->getActiveSetGrades();

$studentCountArray = array();
if($manualChoice == ''){
$i = 1;
foreach($gradeRangesArray as $gradeRange) {
	$gradeId = $gradeRange['gradeId'];
	$gradeLabel = $gradeRange['gradeLabel'];
	$gradePoints = $gradeRange['gradePoints'];

	$gradingRangeFromNew = $REQUEST_DATA['textFrom'.$gradeId];
	$gradingRangeToNew = $REQUEST_DATA['textTo'.$gradeId];
	if (!is_numeric($gradingRangeFromNew) or !is_numeric($gradingRangeToNew)) {
		echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
		die;
	}
	if ($gradingRangeToNew < $gradingRangeFromNew) {
		echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
		die;
	}
	if ($i > 1) {
		if ($gradingRangeFromNew >= $gradingRangeFrom and $gradingRangeFromNew <= $gradingRangeTo) {
			echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
			die;
		}
		if ($gradingRangeToNew >= $gradingRangeFrom and $gradingRangeToNew <= $gradingRangeTo) {
			echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
			die;
		}
	}
	$gradingRangeFrom = $gradingRangeFromNew;
	$gradingRangeTo = $gradingRangeToNew;
	$i++;
}
$studentCountArray = array();
foreach($gradeRangesArray as $gradeRange) {
	$gradeId = $gradeRange['gradeId'];
	$gradingRangeFromNew = $REQUEST_DATA['textFrom'.$gradeId];
	$gradingRangeToNew = $REQUEST_DATA['textTo'.$gradeId];
	$gradePoints = $gradeRange['gradePoints'];
	
	$studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFromNew, $gradingRangeToNew, $degreeList,$gradingFormula);
	$studentCountArray[$gradeId] = $gradeRange;
	$studentCountArray[$gradeId]['studentCount'] = count($studentsArray);
	$studentCountArray[$gradeId]['gradePoints'] = $gradePoints;
  }
}

else {

$i=1;
foreach($gradeRangesArray as $gradeRange) {
	$gradeId = $gradeRange['gradeId'];
	$gradeLabel = $gradeRange['gradeLabel'];
	$gradePoints = $gradeRange['gradePoints'];

	$gradingRangeFromNew = $REQUEST_DATA['txtfrom1'.$gradeId];
	$gradingRangeToNew = $REQUEST_DATA['txtto1'.$gradeId];
	if (!is_numeric($gradingRangeFromNew) or !is_numeric($gradingRangeToNew)) {
		echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
		die;
	}
	if ($gradingRangeToNew < $gradingRangeFromNew) {
		echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
		die;
	}
	if ($i > 1) {
		if ($gradingRangeFromNew >= $gradingRangeFrom and $gradingRangeFromNew <= $gradingRangeTo) {
			echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
			die;
		}
		if ($gradingRangeToNew >= $gradingRangeFrom and $gradingRangeToNew <= $gradingRangeTo) {
			echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
			die;
		}
	}
	$gradingRangeFrom = $gradingRangeFromNew;
	$gradingRangeTo = $gradingRangeToNew;
	$i++;
}
$studentCountArray = array();
foreach($gradeRangesArray as $gradeRange) {
	$gradeId = $gradeRange['gradeId'];
	$gradingRangeFromNew = $REQUEST_DATA['txtfrom1'.$gradeId];
	$gradingRangeToNew = $REQUEST_DATA['txtto1'.$gradeId];
	$gradePoints = $gradeRange['gradePoints'];
	
	$studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFromNew, $gradingRangeToNew, $degreeList,$gradingFormula);
	$studentCountArray[$gradeId] = $gradeRange;
	$studentCountArray[$gradeId]['studentCount'] = count($studentsArray);
	$studentCountArray[$gradeId]['gradePoints'] = $gradePoints;
  }

}
$totalArray = Array('gradeArray'=>$gradeRangesArray,'studentCountArray' => $studentCountArray);

echo json_encode($totalArray);


//$History: scShowGradesMGPA.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/26/09   Time: 1:38p
//Updated in $/Leap/Source/Library/ScStudent
//done changes for not showing Absent students in grading.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/24/09   Time: 10:49a
//Updated in $/Leap/Source/Library/ScStudent
//done the changes to make grading work based on grade-set selected.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/20/09    Time: 5:20p
//Created in $/Leap/Source/Library/ScStudent
//file added for grading-advanced.
//



?>
