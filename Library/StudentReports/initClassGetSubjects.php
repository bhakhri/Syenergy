<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 15-July-2008
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
	$reportManager = StudentReportsManager::getInstance();

	if (isset($REQUEST_DATA['degree'])) {
		$classId = $REQUEST_DATA['degree'];
	}
	elseif (isset($REQUEST_DATA['class1'])) {
		$classId = $REQUEST_DATA['class1'];
	}
	 
	
	if (isset($REQUEST_DATA['labelId'])) {
		$timeTable = $REQUEST_DATA['labelId'];
	}
	elseif (isset($REQUEST_DATA['timeTable'])) {
		$timeTable = $REQUEST_DATA['timeTable'];
	}
    
    if(trim($classId)=='' or trim($timeTable)==''){
        echo 'Required Parameters Missing';
        die;
    }


	//fetching subject data only if any one class is selected

	if ($classId != 'all') {
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		if ($roleId == 1) {
			$subjectsArray = $reportManager->getTransferredSubjectList($classId);
		}
		elseif ($roleId == 2) {
			$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
			$subjectsArray = $reportManager->getTeacherSubjectList($classId, $timeTable, $employeeId);
		}
		else {
			$userId = $sessionHandler->getSessionVariable('UserId');
			$subjectsArray = $reportManager->getTransferredSubjectList($classId);
		}
		echo json_encode($subjectsArray);
	}




	// $History: initClassGetSubjects.php $
//
//*****************  Version 11  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/12/09    Time: 14:53
//Updated in $/LeapCC/Library/StudentReports
//Added server side checks for missing paramters
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 12/01/09   Time: 6:46p
//Updated in $/LeapCC/Library/StudentReports
//done changes as per FCNS No. 891 
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 10/28/09   Time: 1:49p
//Updated in $/LeapCC/Library/StudentReports
//done changes for making on/off for grace marks.
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/31/09    Time: 4:57p
//Updated in $/LeapCC/Library/StudentReports
//added time table label.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/21/09    Time: 3:44p
//Updated in $/LeapCC/Library/StudentReports
//made changes so that other roles can see the report. earlier only admin
//and teacher could see the report.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/21/09    Time: 10:47a
//Updated in $/LeapCC/Library/StudentReports
//code updated to make report working.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/28/09    Time: 5:19p
//Updated in $/LeapCC/Library/StudentReports
//changed 'class' variable to 'degree' as this was causing problems in
//IE6
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/12/09    Time: 6:38p
//Updated in $/LeapCC/Library/StudentReports
//code updated to make final internal report teacher compatible.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:55p
//Updated in $/Leap/Source/Library/StudentReports
//applied code for make it working in IE
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/29/08    Time: 1:05p
//Created in $/Leap/Source/Library/StudentReports
//file added for "attendance not entered report"


?>