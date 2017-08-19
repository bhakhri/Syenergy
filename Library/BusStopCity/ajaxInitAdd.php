<?php 
//  This File calls addFunction used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCityMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

 require_once(MODEL_PATH . "/BusStopCityManager.inc.php");
 $busStopCityManager = BusStopCityManager::getInstance();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['busStopCity']) || trim($REQUEST_DATA['busStopCity']) == '') {
        $errorMessage .= ENTER_BUS_STOP_CITY."\n";
    }
    
     $busStopCityName = add_slashes(strtoupper(trim($REQUEST_DATA['busStopCity'])));
     $foundArray = $busStopCityManager->getBusStopCity(' WHERE UCASE(cityName)="'.$busStopCityName.'"');
     if(count($foundArray) > 0){
     	$errorMessage .= BUS_STOP_CITY_ALREADY_EXIST."\n";
     }
    if(trim($errorMessage) == ''){
	$returnStatus = $busStopCityManager->addBusStopCity($busStopCityName);
	if($returnStatus === false){
		echo FAILURE;
		die;
	}
	else{
		echo SUCCESS;
		die;           
	}
    }
    else{
        echo $errorMessage;
    }
?>
