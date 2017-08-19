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
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '' and trim($REQUEST_DATA['labelId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $labelId = trim($REQUEST_DATA['labelId']);
    $teacherId = trim($REQUEST_DATA['employeeId']);
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    $type=trim($REQUEST_DATA['type']);
    if($type==2){
     $foundArray=FeedBackReportAdvancedManager::getInstance()->getAllCategories($labelId);
    }
    else{
     $foundArray=FeedBackReportAdvancedManager::getInstance()->getCategory($labelId,$teacherId,$timeTableLabelId);   
    }
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
//User: Gurkeerat    Date: 2/16/10    Time: 12:08p
//Created in $/LeapCC/Library/FeedbackAdvanced
//created file under feedback teacher final report
//

?>