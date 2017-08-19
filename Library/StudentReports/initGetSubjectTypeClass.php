<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	$classId = $REQUEST_DATA['class1'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    if ($classId == '') {
        echo 'Required Parameters Missing'; 
        die;
    }
	//fetching subject data only if any one class is selected

	$groupsArray = $studentReportsManager->getSubjectTypeClass($classId,$subjectTypeId);
	echo json_encode($groupsArray);



// $History: initGetSubjectTypeClass.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/19/10    Time: 11:25a
//Updated in $/LeapCC/Library/StudentReports
//added check if degree or subjectTypeId is null then die
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:12p
//Created in $/LeapCC/Library/StudentReports
//intial checkin
?>