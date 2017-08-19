<?php
//-------------------------------------------------------
// Purpose: To delete busstop detail
//
// Author :Nishu Bindal
// Created on : (28.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/BusStopManagerNew.inc.php");
$busStopManager = BusStopManagerNew::getInstance();

    if (!isset($REQUEST_DATA['busStopId']) || trim($REQUEST_DATA['busStopId']) == '') {
        $errorMessage = 'Invalid BusStop';
    }
    else{
    	 $recordArr = $busStopManager->checkForBusStopMapping($REQUEST_DATA['busStopId']);
    	 if($recordArr[0]['totalRecord'] > 0 ){
    	 	$errorMessage = DEPENDENCY_CONSTRAINT;
    	 }
    }
    
    if (trim($errorMessage) == '') { 
	if($busStopManager->deleteBusStop($REQUEST_DATA['busStopId']) ) {
		echo DELETE;
	}
    }
    else {
        echo $errorMessage;
    }

?>

