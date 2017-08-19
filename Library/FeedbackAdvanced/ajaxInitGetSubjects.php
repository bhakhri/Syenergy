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
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '' and trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    
    $foundArray=FeedBackReportAdvancedManager::getInstance()->getSubjectsFromAnswerTable(trim($REQUEST_DATA['timeTableLabelId']),trim($REQUEST_DATA['labelId']),trim($REQUEST_DATA['classId']));
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
// $History: ajaxInitGetSubjects.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:59p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Feedback Comments Report"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/02/10   Time: 11:56
//Created in $/LeapCC/Library/FeedbackAdvanced
?>