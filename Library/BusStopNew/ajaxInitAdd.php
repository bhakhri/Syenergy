<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A BUSSTOP
// Author : Nishu Bindal
// Created on : (22.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/BusStopManagerNew.inc.php");
$busStopManager = BusStopManagerNew::getInstance();
      
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busStopCityId']) || trim($REQUEST_DATA['busStopCityId']) == '')) {
        $errorMessage .= "Select Vehicle Stop City. \n";    
    }
    if (!isset($REQUEST_DATA['stopName']) || trim($REQUEST_DATA['stopName']) == '') {
        $errorMessage .= ENTER_STOP_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stopAbbr']) || trim($REQUEST_DATA['stopAbbr']) == '')) {
        $errorMessage .= ENTER_STOP_ABBR."\n";    
    }    
   
        $foundArray =$busStopManager->getBusStop(' AND (UCASE(bs.stopName)="'.add_slashes(strtoupper($REQUEST_DATA['stopName'])).'" OR UCASE(bs.stopAbbr)="'.add_slashes(strtoupper($REQUEST_DATA['stopAbbr'])).'")' );
        if(count($foundArray) > 0) {  //DUPLICATE CHECK
        	$errorMessage = "Vehicle Stop Name OR Vehicle Stop Abbr Alredy Exists.\n";
        }
        $busStopCityId=trim($REQUEST_DATA['busStopCityId']);
   	 if($busStopCityId == 'other'){
    		$newCity = add_slashes(trim($REQUEST_DATA['busStopCityNew']));
    		if ((!isset($newCity) || trim($newCity) == '') || $newCity == "Enter City Name") {
       			 $errorMessage .= ENTER_BUS_STOP_CITY."\n";    
   		}
   		else{
   			$busStopCityName = add_slashes(strtoupper(trim($REQUEST_DATA['busStopCityNew'])));
   			$foundArray = $busStopManager->getBusStopCity(' WHERE UCASE(cityName)="'.$busStopCityName.'"');
			if($foundArray[0]['totalRecord'] > 0){
		     		$errorMessage .= BUS_STOP_HEAD_ALREADY_EXIST."\n";
		       }
   		}
    	}
    
    if (trim($errorMessage) == '') {
    	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		if($busStopCityId == 'other'){
			$returnStatus = $busStopManager->addBusStopCity(strtoupper($busStopCityName));
			$busStopCityId =SystemDatabaseManager::getInstance()->lastInsertId();
			if($returnStatus === false){
				echo FAILURE;
				die;
			}
		}

		$returnStatus = $busStopManager->addBusStop($busStopCityId);
		if($returnStatus === false){
			echo FAILURE;
			die;
		}
		
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
			die;
		}
		else {
			echo FAILURE;
			die;
		}
		
	}
	else {
		echo FAILURE;
		die;
	}
    }
    else {
        echo $errorMessage;
    }

?>
