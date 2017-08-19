<?php
//-------------------------------------------------------
// Purpose: To edit Vehicle Room Allocation
// Author : Nishu Bindal
// Created on : (29.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
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
   
    $errorMessage ='';
    
   /* if ((!isset($REQUEST_DATA['studentId']) || trim($REQUEST_DATA['studentId']) == '')) {
        $errorMessage .=  STUDENT_NOT_EXISTS. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stopCity']) || trim($REQUEST_DATA['stopCity']) == '')) {
        $errorMessage .=  "Select Stop City. \n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stopName']) || trim($REQUEST_DATA['stopName']) == '')) {
        $errorMessage .=  "Select Stop Name";
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['rootName']) || trim($REQUEST_DATA['rootName']) == '')) {
        $errorMessage .=  "Select Root Name";
    }
 if ($errorMessage == '' && (!isset($REQUEST_DATA['seatNumber']) || trim($REQUEST_DATA['seatNumber']) == '')) {
        $errorMessage .=  "Select Seat Number";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['validFrom']) || trim($REQUEST_DATA['validFrom']) == '')) {
        $errorMessage .=  "Select valid From Date";
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['validTo']) || trim($REQUEST_DATA['validTo']) == '')) {
		$errorMessage .=  "Select valid To Date";
	    }
	
  */  


    if ($errorMessage == '' && (!isset($REQUEST_DATA['busRouteStudentMappingId']) || trim($REQUEST_DATA['busRouteStudentMappingId']) == '')) {
        $errorMessage .=  "Required Parameter Is Missing.";
    }
    $feeCycleId=$REQUEST_DATA['feeCycleId'];
    if($errorMessage == ''){    
       $busRouteStudentMappingId = $REQUEST_DATA['busRouteStudentMappingId']; 
     //check for already exist student where checkOut date is filled
         $condition = " WHERE studentId = '".$REQUEST_DATA['studentId']."' 
                        AND classId != '".$REQUEST_DATA['classId']."'
                        AND ('$validFrom' BETWEEN validFrom AND validTo)  ";
         $ret1=$VehicleRouteAllocationManager->checkStudentData($condition);
         if(count($ret1) >0 ) {    
           echo "No more seats left";   
           die;
         } 
          
         $dataArr = $VehicleRouteAllocationManager->getBusStopRouteMappingId($REQUEST_DATA['stopId'],$REQUEST_DATA['rootId']);
         $busRouteStopMappingId = $dataArr[0]['busRouteStopMappingId'];
         
         $busRouteId = $dataArr[0]['busRouteId'];
         $busStopId = $dataArr[0]['busStopId'];
         $busStopCityId = $dataArr[0]['busStopCityId']; 
		
         $condition = " seatNumber='".add_slashes(strtoupper($REQUEST_DATA['seatNumber']))."' 
         				AND feeCycleId ='".$feeCycleId."'
                        AND busRouteId    = '".$busRouteId."'
                        AND busStopCityId = '".$busStopCityId."'
                        AND studentId = '".$REQUEST_DATA['studentId']."' 
                        AND classId != '".$REQUEST_DATA['classId']."'";
         $foundArray = $VehicleRouteAllocationManager->checkForSeatNumber($condition);
         if($foundArray[0]['cnt'] > 0) {  //DUPLICATE CHECK     
           echo "Seat Number already exists";
           die;
         }
          
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
           		 	//Check for Generate Fee Student Table		start		
		 if($sessionHandler->getSessionVariable('STUDENT_GENERATE_FEE')=='1'){
		 	$studentId =$REQUEST_DATA['studentId'];
			 $classId = $REQUEST_DATA['classId'];
			 $transportFee =trim($REQUEST_DATA['transportCharges']);	
			  $checkGenerateStudent = $VehicleRouteAllocationManager->getGenerateStudentFeeValue($studentId,$classId);
 					$strQuery ="transportFee ='$transportFee',
					   busRouteId ='$busRouteId',  
					   busStopId ='$busStopId', 
					   busStopCityId ='$busStopCityId',
					   busRouteStopMappingId ='$busRouteStopMappingId'";	
			 if(count($checkGenerateStudent) >0){											
								
				 $updateGenerateStudent = $VehicleRouteAllocationManager->updateGenerateStudentFeeValue($studentId,$classId,$strQuery);	
				 if($updateGenerateStudent===false){		  		
					echo FAILURE;
			  	}
			
			 }else{
				 	$strQuery .=",studentId ='$studentId',
								classId ='$classId'";
				$insertGenerateStudent = $VehicleRouteAllocationManager->insertGenerateStudentFeeValue($strQuery);	
				 if($insertGenerateStudent===false){		  		
					echo FAILURE;
			  	}
			 }
		 }
		 //Check for Generate Fee Student Table		END
             $result = $VehicleRouteAllocationManager->insertIntoBusStopRouteMapping($busRouteStopMappingId,$busRouteId,$busStopId,$busStopCityId,$busRouteStudentMappingId);
             if($result===false){
               echo FAILURE;
               die;
             }
             $result1 =$VehicleRouteAllocationManager->updateStudentTable($REQUEST_DATA['studentId']);
             if($result1===false){
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
