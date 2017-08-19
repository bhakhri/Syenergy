<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUs Stop Route mapping
// Author : NISHU BINDAL
// Created on : (22.FEB.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleStopRouteMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['busRouteStopMapping'] ) != '') {
  require_once(MODEL_PATH . "/BusStopRouteMappingManager.inc.php");
$busStopRouteMapping = BusStopRouteMappingManager::getInstance();
    $foundArray = $busStopRouteMapping->getBusStopRouteMapping($REQUEST_DATA['busRouteStopMapping']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>


