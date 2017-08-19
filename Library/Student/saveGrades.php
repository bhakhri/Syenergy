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

global $sessionHandler;
$queryDescription =''; 
$labelId = $REQUEST_DATA['labelId'];
$subjectId = $REQUEST_DATA['subjectId'];
$degreeId = $REQUEST_DATA['degreeId'];
$gadeLabelId = $REQUEST_DATA['gadeLabelId'];
$degreeList = $degreeId;
$gradingFormula = $REQUEST_DATA['gradingFormula'];
$studentManager = StudentManager::getInstance();

$gradeRangesArray = $studentManager->getActiveSetGrades();
$gradeSetId = '';
foreach($gradeRangesArray as $gradeRange) {
	$gradeId = $gradeRange['gradeId'];
	$gradingRangeFromNew = $REQUEST_DATA['textFrom'.$gradeId];
	$gradingRangeToNew = $REQUEST_DATA['textTo'.$gradeId];
	$gradeSetId = $gradeRange['gradeSetId'];
}


require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {

	$gradeRangesArray = $studentManager->getGradeLabels($gadeLabelId);
	$whereCondition = '';
	foreach($gradeRangesArray as $gradeRange) {
		$gradingRangeFrom = $gradeRange['gradingRangeFrom'];
		$gradingRangeTo = $gradeRange['gradingRangeTo'];
		$gradeId = $gradeRange['gradeId'];
		$studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFrom, $gradingRangeTo, $degreeList,$gradingFormula);
		$str = '';
		foreach($studentsArray as $studentRecord) {
			$studentId = $studentRecord['studentId'];
			$classId = $studentRecord['classId'];
			if (!empty($str)) {
				$str .= ',';
			}

			$str .= "'$studentId#$classId#$subjectId'";

		}
		if ($str != '') {
			$whereCondition = " CONCAT(studentId,'#',classId,'#',subjectId) in ($str)";
			$return = $studentManager->updateRecordInTransaction(TOTAL_TRANSFERRED_MARKS_TABLE, "gradingLabelId = $gadeLabelId, gradeId = $gradeId, gradeSetId = $gradeSetId", $whereCondition);
			if ($return === false) {
				echo FAILURE;
				die;
			}
		}
	}
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
		$classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId = $classId");
		$className = $classNameArray[0]['className'];
		$auditTrialDescription = "Grades have been applied for Class: $className, Subject: " ;
		$type = GRADES_ARE_APPLIED; //GRADES APPLIED
		$subjectsArray = $studentManager->getSingleField('subject', 'subjectCode', " where subjectId = $subjectId");
		$subjectCode = $subjectsArray[0]['subjectCode'];
		$auditTrialDescription .= $subjectCode;
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type,$auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}

		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################

		echo SUCCESS;
	}
	else {
		echo FAILURE;
		die;
	}
}
else {
	echo FAILURE;
	die;
}


//$History: scSaveGrades.php $
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 2/15/10    Time: 12:23p
//Updated in $/Leap/Source/Library/ScStudent
//done changes to implement multi-institute in SC
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 11/09/09   Time: 3:51p
//Updated in $/Leap/Source/Library/ScStudent
//added link for apply grades(old) to facilitate user to apply same
//grade-set to multiple courses.
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 2/16/09    Time: 4:29p
//Updated in $/Leap/Source/Library/ScStudent
//corrected grade applying query part
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 1/29/09    Time: 5:11p
//Updated in $/Leap/Source/Library/ScStudent
//done the coding to make the flow work in both ways:
//1. Transfer - Grading - Promotion
//2. Promotion - Transfer - Grading
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 1/14/09    Time: 1:11p
//Updated in $/Leap/Source/Library/ScStudent
//applied access rights
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 12/27/08   Time: 6:48p
//Updated in $/Leap/Source/Library/ScStudent
//changed code
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 12/27/08   Time: 11:38a
//Updated in $/Leap/Source/Library/ScStudent
//added code for transaction
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 12/26/08   Time: 4:35p
//Updated in $/Leap/Source/Library/ScStudent
//changed code to save grades
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/20/08   Time: 1:56p
//Updated in $/Leap/Source/Library/ScStudent
//added defines for access level checks
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 11/03/08   Time: 3:45p
//Updated in $/Leap/Source/Library/ScStudent
//added code to check if the user is logged in or not
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/23/08   Time: 5:16p
//Created in $/Leap/Source/Library/ScStudent
//file added for saving grades of students
//

?>
