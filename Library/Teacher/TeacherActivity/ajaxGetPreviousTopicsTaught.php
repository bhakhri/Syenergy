<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DailyAttendance');
define('ACCESS','view');

global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$employeeId = $REQUEST_DATA['employeeId'];

$conditions =" AND adl.employeeId='$employeeId'";
$foundArray = TeacherManager::getInstance()->getPreviousTopicsTaught($conditions);
if(is_array($foundArray) && count($foundArray)>0 ) {
    echo json_encode($foundArray[0]);
}
else{
    echo 0;
}
// $History: ajaxGetPreviousTopicsTaught.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 17/04/10   Time: 15:51
//Created in $/LeapCC/Library/Teacher/TeacherActivity
?>