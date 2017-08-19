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
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();


	 
	$timeTable = $REQUEST_DATA['timeTable'];
    if(trim($timeTable)==''){
       echo 'Required Parameters missing'; 
       die;
    }
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if ($roleId != 2) {
		$groupsArray = $studentReportsManager->getLabelClass($timeTable);
		echo json_encode($groupsArray);
	}
	elseif($roleId == 2) {
		$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
		$groupsArray = $studentReportsManager->getLabelClassTeacher($timeTable, $employeeId);
		echo json_encode($groupsArray);
	}

	 
	//fetching subject data only if any one class is selected

	
	



// $History: initGetLabelClass.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 6:13p
//Updated in $/LeapCC/Library/StudentReports
//resolved issue 0002209,0002208,0002206,0002169,0002148,0002147,0002151,
//0002219,0002095
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
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