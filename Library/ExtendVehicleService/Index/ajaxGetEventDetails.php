<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE event div
//
//
// Author : Rajeev Aggarwal
// Created on : (10.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define("MANAGEMENT_ACCESS",1);
define('MODULE','COMMON');
define('ACCESS','view'); 
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['eventId'] ) != '') {
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $foundArray = DashBoardManager::getInstance()->getEventDetail($REQUEST_DATA['eventId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetEventDetails.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Index
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:04p
//Created in $/LeapCC/Library/Index
//Intial checkin
?>