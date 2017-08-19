<?php
//-------------------------------------------------------
// Purpose: To make Add STUDENT HOSTEL FEES
// Author : Nishu Bindal
// Created on : (17.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Fee/BusFeeManager.inc.php");   
$busFeeManager = BusFeeManager::getInstance(); 
define('MODULE','VehicleFeeMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  
global $sessionHandler;
$queryDescription ='';

	
	$classIds      =   $REQUEST_DATA['classId'];  
	$feeAmountData  =  $REQUEST_DATA['feeAmountData'];
	$studyPeriodId =  $REQUEST_DATA['studyPeriodId'];

	$errorMessages = '';
	
	if($classIds == ''){
	  $errorMessages .= SELECT_CLASS."\n";
	}
	if($feeAmountData ==''){
	  $errorMessages .= "Required Parameter Is Missing\n";
	}
	/*
	$recordArray = $busFeeManager->checkForFeeGeneration($classIds);
	if($recordArray[0]['cnt'] > 0){
	  echo "Transport Fee Can't be Edited.\nThis Class Fees is Already Generated.\n";
      die;
	}
    */
	
    if (trim($errorMessages) == '') {
       //****************************************************************************************************************    
       //***********************************************STRAT TRANSCATION************************************************
       //****************************************************************************************************************
       if(SystemDatabaseManager::getInstance()->startTransaction()) {
		  	$dataArray = array();
		  	$dataArray = explode(',',$feeAmountData);	
            $classArray = explode(',',$classIds);
		      
		    $busRouteId ='';
		    $values ='';
		    $busStopCityId ='';
            for($i=0;$i<count($classArray);$i++) {
               $classId = $classArray[$i];   
               $values = '';
		       foreach($dataArray as $key =>$value) {
		           $subArray = array();
  		       	   $subArray = explode('-',$value);
		       		
		       	   if($busRouteId != ''){
		       			$busRouteId .=',';
		       			$busStopCityId .=',';
		       		}
		       		//busFeesId,busRouteId,busStopCityId,batchId,studyPeriodId,amount
		       		// 0 => Amount, 1 => busRouteId, 2-> busStopCityId
		       		if($subArray[0] != '' && $subArray[0] > 0){
		       			if($values != ''){
		       				$values .=', ';
		       			}
		       			$values .="('','$subArray[1]','$subArray[2]','$classId','$subArray[0]')";
		       		}
		       		else{
		       			echo "Please Enter Bus Fee";
		       			die;
		       		}
		       		$busStopCityId .="$subArray[2]";
		       		$busRouteId .="$subArray[1]";
		       }
		       $condition ="WHERE  busRouteId IN ($busRouteId) AND busStopCityId IN ($busStopCityId) AND classId='$classId' ";
		       // to delete existing fee amount of same filters fields 
		       $deleteStatus = $busFeeManager->deleteFeeValues($condition);
	       		if($deleteStatus === false){
	          		echo FAILURE; 
                    die;
	       		}
		        if($values != ''){
		       		// to insert new values 
		       		$returnStatus = $busFeeManager->insertIntoFeeValues($values);
		       		if($returnStatus === false) {
		          		echo FAILURE; 
                        die;
		       		 }
		        }
           }
        
              //*****************************COMMIT TRANSACTION************************* 
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		      $errorMessages .= SUCCESS;
           }
           else {
              $errorMessages .=  FAILURE;
           }    
        }
    }
 
    echo $errorMessages;
 ?>
