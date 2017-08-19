<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_TeacherMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '' and trim($REQUEST_DATA['subjectId'] ) != '' and trim($REQUEST_DATA['groupId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
    //first check in mapping table
    $selectionString .="'".trim($REQUEST_DATA['timeTableLabelId'])."~".trim($REQUEST_DATA['classId'])."~".trim($REQUEST_DATA['groupId'])."~".trim($REQUEST_DATA['subjectId'])."'";
    $foundArray=FeedBackTeacherMappingManager::getInstance()->fetchMappedValues($selectionString);
    if(is_array($foundArray) and count($foundArray)>0){
       echo json_encode($foundArray);
       die; 
    }
    //if no values are there then check in time table  
    $foundArray=FeedBackTeacherMappingManager::getInstance()->getTimeTableTeachers(' AND ttc.timeTableLabelId="'.trim($REQUEST_DATA['timeTableLabelId']).'" AND c.classId="'.trim($REQUEST_DATA['classId']).'" AND t.subjectId="'.trim($REQUEST_DATA['subjectId']).'" AND t.groupId="'.trim($REQUEST_DATA['groupId']).'"');
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
// $History: ajaxGetTimeTableTeachers.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/02/10    Time: 14:45
//Created in $/LeapCC/Library/FeedbackAdvanced
?>