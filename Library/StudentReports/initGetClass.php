<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();


	 
	$timeTable = $REQUEST_DATA['timeTable'];

	if ($REQUEST_DATA['timeTable'] != '') {

		$groupsArray = $studentReportsManager->getLabelClass($timeTable);
		echo json_encode($groupsArray);

	}
	
	 
	//fetching subject data only if any one class is selected

	
	



// $History: initGetClass.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/25/09    Time: 5:27p
//Created in $/LeapCC/Library/StudentReports
//new ajax file to get class
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/12/09    Time: 6:39p
//Updated in $/LeapCC/Library/StudentReports
//code updated to make final internal marks report teacher compatible.
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:12p
//Created in $/LeapCC/Library/StudentReports
//intial checkin
?>