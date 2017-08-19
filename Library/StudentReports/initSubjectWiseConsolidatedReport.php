<?php
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentReportsManager = StudentReportsManager::getInstance();


$arr = explode('-',$REQUEST_DATA['degree']);
$universityId = $arr[0];
$degreeId = $arr[1];
$branchId = $arr[2];
$studyPeriodId = $REQUEST_DATA['studyPeriodId'];
$subjectId = $REQUEST_DATA['subjectId'];
$sortField = $REQUEST_DATA['sortField'];

if ($sortField == 'rollNo') {
	$sortField = 'numericRollNo';
}

//fetch classId which match the criteria
$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$studyPeriodId."'";   
$classIdArray = $studentReportsManager->getClassId($classFilter);

$classId = $classIdArray[0]['classId'];
$valueArray = array();
$queryPart = '';
if ($subjectId == 'all') {
	$subjectsArray = $studentReportsManager->getClassConsolidatedSubjects($classId);
	$valueArray['subjects'] = $subjectsArray;

	foreach($subjectsArray as $subjectRecord) {
		$subjectId = $subjectRecord['subjectId'];
		$subjectCode = $subjectRecord['subjectCode'];
		$queryPart .= ", 
						(
								SELECT 
										COUNT(b.studentId) AS cnt 
								FROM	student b 
								WHERE	b.studentId 
								IN (
									SELECT 
												c.studentId 
									FROM		".TEST_TRANSFERRED_MARKS_TABLE." c 
									WHERE		c.classId = $classId AND 
												c.subjectId = $subjectId 
									GROUP BY	CONCAT(c.studentId, c.classId, c.subjectId) 
									HAVING		ROUND(SUM(c.marksScored)/SUM(c.maxMarks)*100) 
									BETWEEN		a.rangeFrom AND a.rangeTo
								   )
						) AS $subjectCode
					";

	}
	$dataArray = $studentReportsManager->getAllSubjectConsolidatedMarks($queryPart);
	$cnt = count($dataArray);
	for($i=0;$i<$cnt;$i++) {
		$dataArray2[] = array_merge(array('srNo' => ($records+$i+1) ),$dataArray[$i]);
	}
	$valueArray['data'] = $dataArray2;
	echo json_encode($valueArray);
}
else {
	$dataArray = $studentReportsManager->getSubjectWiseConsolidatedMarks($classId, $subjectId);
	$cnt = count($dataArray);
	for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$dataArray[$i]);
	}
	echo json_encode($valueArray);
}


//$History: initSubjectWiseConsolidatedReport.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:35p
//Updated in $/LeapCC/Library/StudentReports
//added multiple table defines.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/22/08    Time: 3:22p
//Updated in $/Leap/Source/Library/StudentReports
//added code for "all subjects"
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/21/08    Time: 11:28a
//Updated in $/Leap/Source/Library/StudentReports
//removed following functions:
//1. getRangeValues()
//2. getStudentsInRange()
//and added following function:
//1. getSubjectWiseConsolidatedMarks()
//for subjectwise consolidated report
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 6:40p
//Created in $/Leap/Source/Library/StudentReports
//file added for subjectwise consolidated report
//


?>

