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
define('MODULE','SwapTeacherTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $foundArray = CommonQueryManager::getInstance()->getAdjustedEmployeesFromTimeTable(' e.employeeName ',' AND ttl.timeTableLabelId="'.$REQUEST_DATA['timeTableLabelId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: getAdjustedTeachersForTimeTimeLabel.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/10/09   Time: 13:19
//Created in $/LeapCC/Library/TimeTable
//Added code "time table adjustment cancellation"
?>