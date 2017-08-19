<?php
//-------------------------------------------------------
// Purpose: To get values of block from the database
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BlockCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['blockId'] ) != '') {
    require_once(MODEL_PATH . "/BlockManager.inc.php");
    $foundArray = BlockManager::getInstance()->getBlock(' WHERE blockId="'.$REQUEST_DATA['blockId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0; // no record found
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Block
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:41p
//Updated in $/Leap/Source/Library/Block
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/11/08    Time: 12:43p
//Updated in $/Leap/Source/Library/Block
//Created "Block" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 7:05p
//Created in $/Leap/Source/Library/Block
//Initial Checkin
?>

