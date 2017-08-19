<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_TeacherMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
    $foundArray=FeedBackTeacherMappingManager::getInstance()->getTimeTableGroups(' AND ttc.timeTableLabelId="'.trim($REQUEST_DATA['timeTableLabelId']).'" AND c.classId="'.trim($REQUEST_DATA['classId']).'"');
    if(is_array($foundArray) and count($foundArray)>0){
        echo json_encode($foundArray);
        die;
    }
    else{
        echo 0;
        die;
    }
}
else{
    echo 0;
    die;
}
// $History: ajaxGetTimeTableGroups.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/02/10    Time: 16:33
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Class->Group->Subject->Teacher" mapping module for "Adv.
//Feedback Modules"
?>