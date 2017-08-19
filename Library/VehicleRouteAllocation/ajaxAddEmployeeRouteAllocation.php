<?php

//-------------------------------------------------------
// Purpose: To add room detail
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteAllocation');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
    
    $errorMessage ='';
    
    if ((!isset($REQUEST_DATA['employeeId']) || trim($REQUEST_DATA['employeeId']) == '')) {
        $errorMessage .= "Employee Not Exists . <br/>";
    }
 
    if ($errorMessage == '' && (!isset($REQUEST_DATA['rootId']) || trim($REQUEST_DATA['rootId']) == '')) {
        $errorMessage .=   "Select Vechile Route.\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stopId']) || trim($REQUEST_DATA['stopId']) == '')) {
        $errorMessage .=  "Select Stop Name.\n";
    }
    
    $validFrom = $REQUEST_DATA['validFrom'];
    $validTo = $REQUEST_DATA['validTo']; 
    

    if(trim($errorMessage) == '') {
       
         //check for already exist employee where checkOut date is filled
         $condition = " WHERE employeeId = '".$REQUEST_DATA['employeeId']."'
                        AND ('$validFrom' BETWEEN validFrom AND validTo)  ";
         $ret1=$VehicleRouteAllocationManager->checkEmployeeData($condition);
         if(count($ret1) >0 ) {    
           echo "Already mapped";   
           die;
         } 
          
         $dataArr = $VehicleRouteAllocationManager->getBusStopRouteMappingId($REQUEST_DATA['stopId'],$REQUEST_DATA['rootId']);
         $busRouteStopMappingId = $dataArr[0]['busRouteStopMappingId'];
         
         $busRouteId = $dataArr[0]['busRouteId'];
         $busStopId = $dataArr[0]['busStopId'];
         $busStopCityId = $dataArr[0]['busStopCityId']; 

         $condition = " seatNumber='".add_slashes(strtoupper($REQUEST_DATA['seatNumber']))."' 
                        AND busRouteId    = '".$busRouteId."'			
                        AND busStopCityId = '".$busStopCityId."'";
         $foundArray = $VehicleRouteAllocationManager->checkForSeatNumber($condition);
         if($foundArray[0]['cnt'] > 0) {  //DUPLICATE CHECK     
           echo "Seat Number already exists";
           die;
         }
          
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
          
             $result = $VehicleRouteAllocationManager->insertIntoBusStudentRouteMapping($busRouteStopMappingId,$busRouteId,$busStopId,$busStopCityId);


             if($result===false){
               echo FAILURE;
               die;
             }
             //$result1 =$VehicleRouteAllocationManager->updateStudentTable($REQUEST_DATA['employeeId']);
             //if($result1===false){
              // echo FAILURE;
              // die;
            // }
             //****************************************************************************************************************    
             //***********************************************COMMIT TRANSCATION************************************************
             //****************************************************************************************************************
             if(SystemDatabaseManager::getInstance()->commitTransaction()) {
               echo SUCCESS;    
             }
             else {
                echo FAILURE;
                die;     
             }   
          }
    }
    else {
       echo $errorMessage;
    }

//$History : $
    
?>
