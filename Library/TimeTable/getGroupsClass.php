<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Subject
// Author : Jaineesh
// Created on : (28.10.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MoveTeacherTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['subjectId'] ) != '') {
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
	$timeTableManager = TimeTableManager::getInstance();
	$fromDate = $REQUEST_DATA['fromDate'];
	$subjectId = $REQUEST_DATA['subjectId'];

	$fromDate = $REQUEST_DATA['fromDate'];
	$gFromDate = explode('-',$fromDate);
	$gYear = $gFromDate[0];
	$gMonth = $gFromDate[1];
	$gDate = $gFromDate[2];

	$getDay = date("w", mktime(0, 0, 0, $gMonth, $gDate, $gYear));

    $foundArray = $timeTableManager->getGroupsClassFromTimeTable(' gr.groupShort ',' AND t.daysOfWeek = '.$getDay.'  AND t.subjectId ='.$subjectId);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: getGroupsClass.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/02/09   Time: 10:31a
//Created in $/LeapCC/Library/TimeTable
//new file for move/copy time table
//
?>