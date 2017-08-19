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
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DutyLeaveEntry');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['studentId'])!= '' && trim($REQUEST_DATA['classId'])!= '' && trim($REQUEST_DATA['groupId'])!= '' && trim($REQUEST_DATA['subjectId'])!= '') {
	
	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
	$teacherManager = TeacherManager::getInstance();
	
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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/05/09   Time: 18:58
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Duty Leave" module
?>