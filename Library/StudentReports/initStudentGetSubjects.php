<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$reportManager = StudentReportsManager::getInstance();
	 
	$arr=explode('-',$REQUEST_DATA['mixedValue']);

	//fetch the classes for the user selected data
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$REQUEST_DATA['studyPeriod']."'";   
	$classIdArray= $reportManager->getClassId($classFilter);

	$fetchedClassIdArray = Array();

	foreach($classIdArray as $classIds) {
		$fetchedClassIdArray[] = $classIds['classId'];
	}

	//create a list from the fetched classes.
	$fetchedClassesList = implode(",", $fetchedClassIdArray);

	//fetch the subjects for the required classes.
	$subjectsArray = $reportManager->getSubjectList($fetchedClassesList);

	echo json_encode($subjectsArray);
?>