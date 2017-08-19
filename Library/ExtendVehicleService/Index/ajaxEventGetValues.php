<?php 
//  This File  Get the event details
//
// Author :Rajeev Aggarwal
// Created on : 04-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['eventId'] ) != '') {
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $foundArray = DashBoardManager::getInstance()->getEvent(' AND e.eventId="'.$REQUEST_DATA['eventId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
//$History: ajaxEventGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Index
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/04/08    Time: 12:58p
//Created in $/Leap/Source/Library/Index
//intial checkin
?>