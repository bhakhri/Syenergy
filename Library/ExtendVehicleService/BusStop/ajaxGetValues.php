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
define('MODULE','BusStopCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['busStopId'] ) != '') {
    require_once(MODEL_PATH . "/BusStopManager.inc.php");
    $foundArray = BusStopManager::getInstance()->getBusStop(' WHERE busStopId="'.$REQUEST_DATA['busStopId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>

<?php
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BusStop
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:17p
//Updated in $/Leap/Source/Library/BusStop
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:33p
//Updated in $/Leap/Source/Library/BusStop
//Added AjaxListing Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:29p
//Updated in $/Leap/Source/Library/BusStop
//Created BusStop Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 4:01p
//Created in $/Leap/Source/Library/BusStop
//Initial Checkin
?>