<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ChangeTestCategory');
	define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$classId = $REQUEST_DATA['degree']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3
	$labelId = $REQUEST_DATA['labelId']; //3
	$testId = $REQUEST_DATA['testId']; //3

	if (empty($classId) or empty($subjectId) or empty($groupId) or empty($labelId) or empty($testId)) {
		echo REQUIRED_PARAMETERS_MISSING;
		die;
	}

	$currentTestIdArray = $studentManager->getTests($classId, $subjectId, $groupId, " AND a.testId = $testId");
	if (!count($currentTestIdArray) or $currentTestIdArray[0]['testId']  == '') {
		echo INVALID_TEST_SELECTED;
		die;
	}

	$currentTestTypeCategoryId = $currentTestIdArray[0]['testTypeCategoryId'];

	$subjectTypeArray = $studentManager->getSubjectTypeId($subjectId);
	$subjectTypeId = $subjectTypeArray[0]['subjectTypeId'];

	$otherTestCategoryArray = $studentManager->getOtherTestCategory($currentTestTypeCategoryId, $subjectTypeId);

	$resultArray = array('currentCategory' => $currentTestIdArray, 'otherCategory' => $otherTestCategoryArray);
	echo json_encode($resultArray);

?>