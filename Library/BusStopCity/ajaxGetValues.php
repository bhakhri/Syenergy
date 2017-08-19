
<?php 

////  This File Is Used To populate Values
// Author :Nishu Bindal
// Created on : 21-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from country table
 
if(trim($REQUEST_DATA['busStopCityId'] ) != ''){
    require_once(MODEL_PATH . "/BusStopCityManager.inc.php");
    $foundArray = BusStopCityManager::getInstance()->getBusStopCity(' WHERE busStopCityId="'.$REQUEST_DATA['busStopCityId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ){  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}

?>
