<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['staffId'] ) != '') {
    require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
    $foundArray = TransportStaffManager::getInstance()->getTransportStaff(' WHERE staffId="'.$REQUEST_DATA['staffId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/09   Time: 6:08p
//Updated in $/Leap/Source/Library/TransportStaff
//change in menu item from bus master to fleet management and doing
//changes in transport staff
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:01
//Created in $/Leap/Source/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>