<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$reportManager = StudentReportsManager::getInstance();

	if (isset($REQUEST_DATA['degree'])) {
		$classId = $REQUEST_DATA['degree'];
	}
	elseif (isset($REQUEST_DATA['class1'])) {
		$classId = $REQUEST_DATA['class1'];
	}
//$classId = $REQUEST_DATA['degree'];
	
	if (isset($REQUEST_DATA['labelId'])) {
		$timeTable = $REQUEST_DATA['labelId'];
	}
	elseif (isset($REQUEST_DATA['timeTable'])) {
		$timeTable = $REQUEST_DATA['timeTable'];
	}

	if ($classId != 'all') {
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		if ($roleId != 2) {
			$subjectsArray = $reportManager->getTTSubjectList($classId);
			echo json_encode($subjectsArray);
		}
	}


// $History: initClassTTSubjects.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/29/09   Time: 6:10p
//Created in $/LeapCC/Library/StudentReports
//initial check-in
//


?>