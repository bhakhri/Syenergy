<?php 
//-------------------------------------------------------
// Purpose: to design the layout for add subject to class
//
// Author : Rajeev Aggarwal
// Created on : (09.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BusStopRouteMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BusStopManager.inc.php");
    $busStopManager = BusStopManager::getInstance();
    
    $timeTablelabelId = $REQUEST_DATA['labelId'];
    $routeId = $REQUEST_DATA['routeId'];
    $chb  = $REQUEST_DATA['chb'];
    
    if($timeTablelabelId=='') {
      $timeTablelabelId=0;  
    }
    
    if($routeId=='') {
      $routeId=0;  
    }
    
    
    $cnt = count($chb);
    
    $insertValue = '';            
    if($routeId!=0 && $timeTablelabelId!=0) {
      for($i=0;$i<$cnt; $i++) { 
        if($insertValue!=''){
          $insertValue = ",";
        }
        $insertValue .= " ('".$timeTablelabelId."','".$routeId."','".$chb[$i]."')";
      }
    }
            
    if(SystemDatabaseManager::getInstance()->startTransaction()) {      
        
        $condition = " routeId = $routeId AND timeTablelabelId = $timeTablelabelId";
        $returnStatus   = $busStopManager->deleteRouteStopMapping($condition);   
        if($returnStatus === false) {
           echo FAILURE;
           die;
        }
        
        if($insertValue!='') {  
          $returnStatus   = $busStopManager->insertStopToRoute($insertValue);
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
           if($returnStatus === false) {
			  echo FAILURE;
		   }
		   else {
			  echo SUCCESS;
		   }
        }
     }

?>
