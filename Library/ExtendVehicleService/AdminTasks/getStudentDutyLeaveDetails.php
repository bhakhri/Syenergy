<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE group drop down based upon selection of class
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DutyLeaves');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['studentId'])!= '' && trim($REQUEST_DATA['classId'])!= '' && trim($REQUEST_DATA['groupId'])!= '' && trim($REQUEST_DATA['subjectId'])!= '') {
	
	require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
	$teacherManager = AdminTasksManager::getInstance();
	
	$foundArray = $teacherManager->getStudentDutyLeaveDetails(trim($REQUEST_DATA['studentId']),trim($REQUEST_DATA['classId']),trim($REQUEST_DATA['groupId']),trim($REQUEST_DATA['subjectId']));
	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
else{
    echo -1;
}
// $History: getStudentDutyLeaveDetails.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Created in $/LeapCC/Library/AdminTasks
//Created "Duty Leave" Module
?>