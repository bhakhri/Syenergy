<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseRequest');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['allocationId'] ) != '') {
    require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
    $foundArray = GuestHouseManager::getInstance()->getGuestHouseRequest(' WHERE allocationId="'.$REQUEST_DATA['allocationId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        $startTime=explode(':',$foundArray[0]['arrivalTime']);
        $endTime=explode(':',$foundArray[0]['departureTime']);
        $foundArray[0]['arrivalTime']=$startTime[0].':'.$startTime[1];
        $foundArray[0]['departureTime']=$endTime[0].':'.$endTime[1];
        
        if(trim($foundArray[0]['alternativeArrangement'])==''){
         $foundArray[0]['alternativeArrangement']='';
        }
        if(trim($foundArray[0]['reason'])==''){
         $foundArray[0]['reason']='';
        }
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>