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

/*
$pendingStudentsArray = $studentManager->checkSubjectNotTransferredStudents($subjectId, $degreeList);
$pendingStudents = $pendingStudentsArray[0]['cnt'];
*/


$gradeRangesArray = $studentManager->getGradeLabels($gadeLabelId);

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


//$History: scShowGrades.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 1/29/09    Time: 5:11p
//Updated in $/Leap/Source/Library/ScStudent
//done the coding to make the flow work in both ways:
//1. Transfer - Grading - Promotion
//2. Promotion - Transfer - Grading
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 1/14/09    Time: 1:11p
//Updated in $/Leap/Source/Library/ScStudent
//applied access rights
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 12/27/08   Time: 11:39a
//Updated in $/Leap/Source/Library/ScStudent
//improved query logic
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/20/08   Time: 1:56p
//Updated in $/Leap/Source/Library/ScStudent
//added defines for access level checks
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 11/14/08   Time: 4:43p
//Updated in $/Leap/Source/Library/ScStudent
//added code for checking if marks have not been transferred for all
//students.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/03/08   Time: 3:52p
//Created in $/Leap/Source/Library/ScStudent
//file added for showing grades
//

?>