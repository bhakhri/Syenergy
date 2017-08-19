<?php
//-------------------------------------------------------
// Purpose: To edit Vehicle Room Allocation
// Author : Nishu Bindal
// Created on : (29.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteAllocation');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

   require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
   $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
 //  echo $REQUEST_DATA['busRouteStudentMappingId'];die;
	
    $errorMessage ='';
      
       if ($errorMessage == '' && (!isset($REQUEST_DATA['busRouteStudentMappingId']) || trim($REQUEST_DATA['busRouteStudentMappingId']) == '')) {
        $errorMessage .=  "Required Parameter Is Missing.";
    }
    
    if($errorMessage == ''){    
       $busRouteStudentMappingId = $REQUEST_DATA['busRouteStudentMappingId']; 
         $employeeId = $REQUEST_DATA['employeeId']; 
     //check for already exist student where checkOut date is filled
     $condition = " WHERE employeeId = '".$REQUEST_DATA['employeeId']."' 
                                            AND ('$validFrom' BETWEEN validFrom AND validTo)  ";
         $ret1=$VehicleRouteAllocationManager->checkEmployeeData($condition);
         if(count($ret1) >0 ) {    
           echo "Already Mapped In Valid Dates";   
           die;
         } 
          
           // echo $employeeId;die;
         $dataArr = $VehicleRouteAllocationManager->getBusStopRouteMappingId($REQUEST_DATA['stopId'],$REQUEST_DATA['rootId']);
         $busRouteStopMappingId = $dataArr[0]['busRouteStopMappingId'];
         
         $busRouteId = $dataArr[0]['busRouteId'];
         $busStopId = $dataArr[0]['busStopId'];
         $busStopCityId = $dataArr[0]['busStopCityId']; 

         $condition = " seatNumber='".add_slashes(strtoupper($REQUEST_DATA['seatNumber']))."' 
                        AND busRouteId    = '".$busRouteId."'
                        AND busStopCityId = '".$busStopCityId."'
                        AND employeeId = '".$REQUEST_DATA['employeeId']."'";
                      
         $foundArray = $VehicleRouteAllocationManager->checkForSeatNumber($condition);
         if($foundArray[0]['cnt'] > 0) {  //DUPLICATE CHECK     
           echo "Seat Number already exists";
           die;
         }
          $busRouteStudentMappingId=$REQUEST_DATA['busRouteStudentMappingId']; 
           $employeeId=$REQUEST_DATA['employeeId']; 
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
           
             $result = $VehicleRouteAllocationManager->insertIntoBusStudentRouteMapping($busRouteStopMappingId,$busRouteId,$busStopId,$busStopCityId,$busRouteStudentMappingId);
             if($result===false){
               echo FAILURE;
               die;
             }
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
    
    
/*    
   if($errorMessage == ''){
         $routeCharges = trim($REQUEST_DATA['routeCharges']);
          $classId  = trim($REQUEST_DATA['classId']);
   	$busRouteStudentMappingId = $REQUEST_DATA['busRouteStudentMappingId'];
	
    	$condition = "WHERE studentId = '".$REQUEST_DATA['studentId']."' 
			AND busRouteStudentMappingId NOT IN ($busRouteStudentMappingId)";
    	$recordArray = $VehicleRouteAllocationManager->checkForAlReadyMapped($condition);
    	if($recordArray[0]['totalRecord'] > 0){
    		 $errorMessage .= "Student is Already Mapped.";
    	}
    }
        
 
    	if (trim($errorMessage) == '') { 
    	       $dataArr = $VehicleRouteAllocationManager->getBusStopRouteMappingId($REQUEST_DATA['stopName'],$REQUEST_DATA['rootName']);
               $busRouteStopMappingId = $dataArr[0]['busRouteStopMappingId'];
               
               $busRouteStudentMappingId = $REQUEST_DATA['busRouteStudentMappingId'];
              	
	           $condition = " WHERE 
                             seatNumber='".add_slashes(strtoupper($REQUEST_DATA['seatNumber']))."' 
                             AND busRouteStudentMappingId = '".$busRouteStopMappingId."'";
               $foundArray = $VehicleRouteAllocationManager->checkForSeatNumber('WHERE 
				        seatNumber="'.add_slashes(strtoupper($REQUEST_DATA['seatNumber'])).'" 
				AND busRouteStudentMappingId!='.$REQUEST_DATA['busRouteStudentMappingId']);

	 if(trim($foundArray[0]['busRouteStudentMappingId'])=='') {  //DUPLICATE CHECK
   
               $ret=$VehicleRouteAllocationManager->editBusRouteStudentMapping($REQUEST_DATA['studentId'],$busRouteStopMappingId,	$busRouteStudentMappingId,$REQUEST_DATA['seatNumber'],$REQUEST_DATA['validFrom'],$REQUEST_DATA['validTo'],$REQUEST_DATA['comments']);    
     
              if($ret===true){
                  echo SUCCESS;
                  die;
              }
              else{
                  echo FAILURE;
                  die;
              }
   	 }else{
                  echo "Seat Number already exists";
                  die;
              }
}
else {
        echo $errorMessage;
    }
*/
//$History : $
    
?>
