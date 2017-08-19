<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 30-Mar-2009
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
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	if (isset($REQUEST_DATA['degree'])) {
		$classId = $REQUEST_DATA['degree'];
	}
	elseif (isset($REQUEST_DATA['class1'])) {
		$classId = $REQUEST_DATA['class1'];
	}
	$subjectId = $REQUEST_DATA['subjectId'];
	//fetching subject data only if any one class is selected

    if(trim($classId)=='' or trim($subjectId)==''){
        echo 'Required Parameters Missing';
        die;
    }
    
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	$groupsArray = $studentReportsManager->getSubjectTestGroups($classId,$subjectId);
	echo json_encode($groupsArray);



//// $History: initGetSubjectTestGroups.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/12/09    Time: 14:53
//Updated in $/LeapCC/Library/StudentReports
//Added server side checks for missing paramters
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 12/01/09   Time: 6:46p
//Updated in $/LeapCC/Library/StudentReports
//done changes as per FCNS No. 891 
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/21/09    Time: 10:47a
//Updated in $/LeapCC/Library/StudentReports
//code updated to make report working.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/01/09    Time: 6:43p
//Updated in $/LeapCC/Library/StudentReports
//corrected from class1 to degree as part of checking/fixing all reports.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/30/09    Time: 5:35p
//Created in $/LeapCC/Library/StudentReports
//file added for fetching groups for which tests have taken place
//


?>