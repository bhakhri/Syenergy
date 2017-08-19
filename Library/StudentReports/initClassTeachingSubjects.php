<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$reportManager = StudentReportsManager::getInstance();
	 
	$arr=explode('-',$REQUEST_DATA['mixedValue']);

	//fetch the classes for the user selected data
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$REQUEST_DATA['studyPeriod']."'";   
	$classIdArray = $reportManager->getClassId($classFilter);
	$classId = $classIdArray[0]['classId'];

	//fetch the subjects for the required classes.
	$subjectsArray = $reportManager->getClassTeachingSubjectList($classId);

	echo json_encode($subjectsArray);
?>