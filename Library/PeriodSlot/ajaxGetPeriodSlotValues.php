<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE PERIOD SLOT 
//
//
// Author : Jaineesh
// Created on : (15.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodSlotMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true); 
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['periodSlotId'] ) != '') {
    require_once(MODEL_PATH . "/PeriodSlotManager.inc.php");
    $foundArray = PeriodSlotManager::getInstance()->getPeriodSlot(' WHERE periodSlotId="'.$REQUEST_DATA['periodSlotId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetPeriodSlotValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:32p
//Created in $/LeapCC/Library/PeriodSlot
//get the file for populate values
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:15p
//Created in $/Leap/Source/Library/PeriodSlot
//use the ajax file for populate the values during edit data
//
?>