<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestMarksSummary');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $foundArray = TeacherManager::getInstance()->getTeacherAllClass(' AND t.timeTableLabelId='.trim($REQUEST_DATA['timeTableLabelId']));
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetClass.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 16:13
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Test Summary" module in teacher login
?>