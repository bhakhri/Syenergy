<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A BUSROUTE
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRouteMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['routeName']) || trim($REQUEST_DATA['routeName']) == '') {
        $errorMessage .= ENTER_ROUTE_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['routeCode']) || trim($REQUEST_DATA['routeCode']) == '')) {
        $errorMessage .= ENTER_ROUTE_CODE."\n";    
    }
    
    $routeName = trim($REQUEST_DATA['routeName']);
    $routeCode = trim($REQUEST_DATA['routeCode']);
    $busId = trim($REQUEST_DATA['busId']);
    $id = $REQUEST_DATA['busRouteId'];
    
    if($busId!='') {
      $busIdArray = explode(',',$busId);
    }
    
      require_once(MODEL_PATH . "/BusRouteManager.inc.php");
		$busRouteManager = BusRouteManager::getInstance();
        $foundArray = $busRouteManager->getBusRoute(' WHERE UCASE(routeCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['routeCode']))).'" AND br.busRouteId!='.$REQUEST_DATA['busRouteId']);
        if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
         	 $errorMessage .= ROUTE_ALREADY_EXIST."\n";
         }
        else {
        	$foundArray = $busRouteManager->getBusRoute(' WHERE brm.busId= "'.$busId.'" AND br.busRouteId!='.$REQUEST_DATA['busRouteId']);
        	if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
         		$errorMessage .= "Bus Is Already Mapped With Route.\n";
        	}
        }
        
    
    if (trim($errorMessage) == '') {
      
            //***********************************************STRAT TRANSCATION************************************************
            //****************************************************************************************************************
            if(SystemDatabaseManager::getInstance()->startTransaction()) { 
                $returnStatus = BusRouteManager::getInstance()->editBusRoute($routeName,$routeCode,$id);
                if($returnStatus === false) {
                  echo FAILURE; 
                  die;
                }
                
                $returnStatus = BusRouteManager::getInstance()->deleteBusMapping($id);
                if($returnStatus === false) {
                  echo FAILURE; 
                  die;
                }          
                   $returnStatus = BusRouteManager::getInstance()->addBus($id,$busId);
                   if($returnStatus === false) {
                     echo FAILURE; 
                     die;
                   }        
                //*****************************COMMIT TRANSACTION************************* 
                if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                  echo SUCCESS;  
                }
                else {
                  echo FAILURE;  
                } 
            }
       
    }
    else {
        echo $errorMessage;
    }
?>
