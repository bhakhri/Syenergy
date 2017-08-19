<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSROUTE LIST
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteMasterNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['busRouteId'] ) != '') {
    require_once(MODEL_PATH . "/BusRouteManagerNew.inc.php");
    $foundArray = BusRouteManagerNew::getInstance()->getBusRoute(' WHERE br.busRouteId="'.$REQUEST_DATA['busRouteId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BusRoute
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:00p
//Updated in $/Leap/Source/Library/BusRoute
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:58p
//Updated in $/Leap/Source/Library/BusRoute
//Added AjaxList Funtioality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 7:07p
//Updated in $/Leap/Source/Library/BusRoute
//Created BusRoute Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:32p
//Created in $/Leap/Source/Library/BusRoute
//Initial Checkin
?>
