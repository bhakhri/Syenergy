<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Ajinder Singh
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	$timeTable = $REQUEST_DATA['timeTable'];
    if(trim($timeTable)==''){
        echo 'Required Parameters Missing';
        die;
    }
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if ($roleId == 1) {
		$groupsArray = $studentReportsManager->getLabelMarksTransferredClass($timeTable);
		echo json_encode($groupsArray);
	}
	elseif($roleId == 2) {
		$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
		$groupsArray = $studentReportsManager->getLabelMarksTransferredClassTeacher($timeTable, $employeeId);
		echo json_encode($groupsArray);
	}
	else {
		$classArray = $studentReportsManager->getClassesVisibleToRole();
		$conditions = "";
		if ($classArray[0]['classId'] != '') {
			$classIdList = UtilityManager::makeCSList($classArray, 'classId');
			$conditions = " and c.classId in ($classIdList)";
		}
		$groupsArray = $studentReportsManager->getLabelMarksTransferredClass($timeTable, $conditions);
		echo json_encode($groupsArray);
	}

	 

// $History: initGetLabelMarksTransferredClass.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/12/09    Time: 14:53
//Updated in $/LeapCC/Library/StudentReports
//Added server side checks for missing paramters
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/01/09   Time: 6:46p
//Updated in $/LeapCC/Library/StudentReports
//done changes as per FCNS No. 891 
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/12/09   Time: 11:16a
//Created in $/LeapCC/Library/StudentReports
//file added to fix bug no.
//0001985




?>