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
define('MODULE','DutyLeaveEvents');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['eventId'] ) != '') {
    require_once(MODEL_PATH . "/DutyLeaveEventsManager.inc.php");
    $foundArray = DutyLeaveEventsManager::getInstance()->getEvent(' WHERE eventId="'.trim($REQUEST_DATA['eventId']).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>