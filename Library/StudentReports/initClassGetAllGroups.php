<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
	 
	$classId = $REQUEST_DATA['degree'];
	//fetching subject data only if any one class is selected

	if ($classId != 'all') {
		$subjectsArray = $studentReportsManager->getClassGroups($classId);
		echo json_encode($subjectsArray);
	}



//// $History: initClassGetAllGroups.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/28/09    Time: 5:55p
//Updated in $/LeapCC/Library/StudentReports
//changed 'class' variable to 'degree' to make it working in IE6.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/31/09    Time: 3:00p
//Created in $/LeapCC/Library/StudentReports
//file added for fetching all groups for a class.
//


?>