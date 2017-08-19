<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A BUSROUTE
// Author : Nishu Bindal
// Created on : (19.April.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteMasterNew');
define('ACCESS','add');
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
    
        require_once(MODEL_PATH . "/BusRouteManagerNew.inc.php");
    	$busRouteManagerNew = BusRouteManagerNew::getInstance();
        
        $foundArray = $busRouteManagerNew->getBusRoute(' WHERE UCASE(routeCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['routeCode']))).'"');
        if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
		$errorMessage .= ROUTE_ALREADY_EXIST."\n";         
	}
	else{
		$foundArray = $busRouteManagerNew->getBusRoute(' WHERE brm.busId = "'.$busId.'"');
		if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
			$errorMessage .= "Bus Is Already Mapped With Route.\n";         
		}
		else{
        		$foundArray = $busRouteManagerNew->getBusRoute(' WHERE br.routeName= "'.$routeName.'" ');
        		if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
         			$errorMessage .= "Vehicle Route Name Already Exists.\n";
        		}
        	}
	}
      
        
    
    if (trim($errorMessage) == '') {
      
		   //***********************************************STRAT TRANSCATION************************************************
           //****************************************************************************************************************
           if(SystemDatabaseManager::getInstance()->startTransaction()) {
               $str = "('$routeName', '$routeCode')";
               $returnStatus = $busRouteManagerNew->addBusRoute($str);
               if($returnStatus === false) {
                  echo FAILURE; 
                  die;
               }
               $busRouteId = SystemDatabaseManager::getInstance()->lastInsertId();
              
                 $returnStatus = $busRouteManagerNew->addBus($busRouteId,$busId);
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
