<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE slab LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SlabsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['slabId'] ) != '') {
    require_once(MODEL_PATH . "/SlabsManager.inc.php");
    $foundArray = SlabsManager::getInstance()->getSlabs(' WHERE slabId="'.$REQUEST_DATA['slabId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Slabs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:39a
//Updated in $/Leap/Source/Library/Slabs
//Added access rules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/12/08    Time: 11:44a
//Created in $/Leap/Source/Library/Slabs
?>