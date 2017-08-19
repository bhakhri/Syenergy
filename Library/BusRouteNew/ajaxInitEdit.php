<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A BUSROUTE
//
//
// Author : Nishu Bindal
// Created on : (17.April.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteMasterNew');
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
    
     	require_once(MODEL_PATH . "/BusRouteManagerNew.inc.php");
    	$busRouteManagerNew = BusRouteManagerNew::getInstance();
        $foundArray = $busRouteManagerNew->getBusRoute(' WHERE UCASE(routeCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['routeCode']))).'" AND br.busRouteId!='.$REQUEST_DATA['busRouteId']);
        if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
         	 $errorMessage .= ROUTE_ALREADY_EXIST."\n";
         }
        else {
        	$foundArray = $busRouteManagerNew->getBusRoute(' WHERE brm.busId= "'.$busId.'" AND br.busRouteId!='.$REQUEST_DATA['busRouteId']);
        	if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
         		$errorMessage .= "Bus Is Already Mapped With Route.\n";
        	}
        	else{
        		$foundArray = $busRouteManagerNew->getBusRoute(' WHERE br.routeName= "'.$routeName.'" AND br.busRouteId!='.$REQUEST_DATA['busRouteId']);
        		if(trim($foundArray[0]['routeCode'])!='') {  //DUPLICATE CHECK
         			$errorMessage .= "Vehicle Route Name Already Exists.\n";
        		}
        	}
        }
        
    
    if (trim($errorMessage) == '') {
      
            //***********************************************STRAT TRANSCATION************************************************
            //****************************************************************************************************************
            if(SystemDatabaseManager::getInstance()->startTransaction()) { 
                $returnStatus =$busRouteManagerNew->editBusRoute($routeName,$routeCode,$id);
                if($returnStatus === false) {
                  echo FAILURE; 
                  die;
                }
                
                $returnStatus = $busRouteManagerNew->deleteBusMapping($id);
                if($returnStatus === false) {
                  echo FAILURE; 
                  die;
                }          
                   $returnStatus = $busRouteManagerNew->addBus($id,$busId);
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
