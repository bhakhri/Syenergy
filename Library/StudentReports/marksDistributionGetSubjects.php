<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$reportManager = StudentReportsManager::getInstance();

	$degree = $REQUEST_DATA['degree'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];

	//fetch the subjects for the required classes.
	$subjectsArray = $reportManager->getSubjectListByType($degree, $subjectTypeId);

	echo json_encode($subjectsArray);
?>