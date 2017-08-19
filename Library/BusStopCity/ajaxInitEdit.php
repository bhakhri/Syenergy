<?php 

//  This File calls Edit Function used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCityMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/BusStopCityManager.inc.php");
$BusStopCityManager = BusStopCityManager::getInstance();
 
    $errorMessage ='';
    if (!isset($REQUEST_DATA['cityName']) || trim($REQUEST_DATA['cityName']) == '') {
       $errorMessage =ENTER_BUS_STOP_CITY."\n"; 
    }
     $cityName = add_slashes(strtoupper(trim($REQUEST_DATA['cityName'])));
     $cityId = $REQUEST_DATA['busStopCityId'];
   
     $foundArray = $BusStopCityManager->getBusStopCity(' WHERE UCASE(cityName)="'.$cityName.'" AND busStopCityId!='.$cityId);
     if(count($foundArray)>0){
     	$errorMessage .=BUS_STOP_CITY_ALREADY_EXIST."\n";
     }
     
    if (trim($errorMessage) == '') {
	$returnStatus = $BusStopCityManager->editBusStopCity($cityName,$cityId);            
	if($returnStatus === false) {
		echo FAILURE;
		die;
	}
	else {
		echo SUCCESS;
		die;           
	}
             
    }
    else {
        echo $errorMessage;
    }

?>


