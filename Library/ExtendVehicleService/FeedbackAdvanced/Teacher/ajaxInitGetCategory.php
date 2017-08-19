<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Gurkeerat Sidhu
// Created on : (15.02.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '' and trim($REQUEST_DATA['labelId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $labelId = trim($REQUEST_DATA['labelId']);
    $teacherId = $sessionHandler->getSessionVariable('EmployeeId');
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    $foundArray=FeedBackReportAdvancedManager::getInstance()->getCategory($labelId,$teacherId,$timeTableLabelId);
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
// $History: ajaxInitGetCategory.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/03/10   Time: 16:45
//Created in $/LeapCC/Library/FeedbackAdvanced/Teacher
//Created Feedback Teacher Final Report (Advanced) for Teacher login
?>