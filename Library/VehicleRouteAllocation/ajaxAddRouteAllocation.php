<?php

//-------------------------------------------------------
// Purpose: To add room detail
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
    
    if ((!isset($REQUEST_DATA['studentId']) || trim($REQUEST_DATA['studentId']) == '')) {
        $errorMessage .=  STUDENT_NOT_EXISTS. '<br/>';
    }
 if ((!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '')) {
        echo STUDENT_NOT_EXISTS;
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['rootId']) || trim($REQUEST_DATA['rootId']) == '')) {
        $errorMessage .=   "Select Vechile Route.\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stopId']) || trim($REQUEST_DATA['stopId']) == '')) {
        $errorMessage .=  "Select Stop Name.\n";
    }
    
    $validFrom = $REQUEST_DATA['validFrom'];
    $validTo = $REQUEST_DATA['validTo']; 
    
	$feeCycleId=$REQUEST_DATA['feeCycleId'];
    if(trim($errorMessage) == '') {
		
          // duplicate value check added
          $condition = " studentId='".$REQUEST_DATA['studentId']."' AND classId = '".$REQUEST_DATA['classId']."'";
          $ret1=$VehicleRouteAllocationManager->getStudentAlreadyExist($condition);
          if(count($ret1) >0 ) {    
            echo "Student already exist";
            die;
          }



        /* $ret1=$VehicleRouteAllocationManager->checkStudentData(' WHERE studentId='.$REQUEST_DATA['studentId']);
           $validTo=explode(',',UtilityManager::makeCSList($ret1,'validTo'));
           if($ret1[0]['studentId'] !='' and in_array('0000-00-00',$validTo)){
              echo "Student already exist";
              die;
           }
           if($ret1[0]['studentId'] !='' and in_array(date('Y-m-d'),$validTo)){
              echo "Dates cannot be same!!!";
              die;
           }         
        */
        
         //check for already exist student where checkOut date is filled
         $condition = " WHERE studentId = '".$REQUEST_DATA['studentId']."'
                        AND ('$validFrom' BETWEEN validFrom AND validTo)  ";
         $ret2=$VehicleRouteAllocationManager->checkStudentData($condition);
         if(count($ret2) >0 ) {    
           echo "Student already exists";   
           die;
         } 
          
         $dataArr = $VehicleRouteAllocationManager->getBusStopRouteMappingId($REQUEST_DATA['stopId'],$REQUEST_DATA['rootId']);
         $busRouteStopMappingId = $dataArr[0]['busRouteStopMappingId'];
         
         $busRouteId = $dataArr[0]['busRouteId'];
         $busStopId = $dataArr[0]['busStopId'];
         $busStopCityId = $dataArr[0]['busStopCityId']; 

         $condition = " seatNumber='".add_slashes(strtoupper($REQUEST_DATA['seatNumber']))."' 
                        AND busRouteId    = '".$busRouteId."'
                       AND feeCycleId ='".$feeCycleId."'
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
          	//Check for Generate Fee Student Table		start		
		 if($sessionHandler->getSessionVariable('STUDENT_GENERATE_FEE')=='1'){
		 	$studentId =$REQUEST_DATA['studentId'];
			 $classId = $REQUEST_DATA['classId'];
			 $transportFee =trim($REQUEST_DATA['transportCharges']);	
			  $checkGenerateStudent = $VehicleRouteAllocationManager->getGenerateStudentFeeValue($studentId,$classId);
 					$strQuery ="		
					   transportFee ='$transportFee',
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
           
             $result = $VehicleRouteAllocationManager->insertIntoBusStopRouteMapping($busRouteStopMappingId,$busRouteId,$busStopId,$busStopCityId);
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

//$History : $
    
?>
