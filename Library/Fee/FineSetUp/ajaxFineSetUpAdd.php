<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Nishu Bindal
// Created on : (7.Feb.212 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassFineSetUp');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


require_once(MODEL_PATH . "/Fee/FineSetUpManager.inc.php");   
$fineSetUpManager = FineSetUpManager::getInstance(); 
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
  
global $sessionHandler;

    
	$classId  =   $REQUEST_DATA['fineClassId'];	
	$fineTypeId  =  $REQUEST_DATA['fineTypeId'];
	$ttFromDate = $REQUEST_DATA['fromDate'];
	$ttToDate = $REQUEST_DATA['toDate'];	
	$ttChargesFormat = $REQUEST_DATA['chargesFormat'];	
	$ttCharges = $REQUEST_DATA['charges'];

    if($classId=='') {
	  $classId =0;
	}
    $classArray = explode(',',$classId);

	// Delete
    $deletePreviousFine = $fineSetUpManager->deletePreviousFineDetail($classId,$fineTypeId);
	if($deletePreviousFine === false){
  	  echo FAILURE;  
      die;
	}
	
	$str='';
    for($j=0;$j<count($classArray);$j++) {
        $classId = $classArray[$j];
        for($i=0;$i<count($ttFromDate);$i++) {
           $fromDate = $ttFromDate[$i];  
           $toDate = $ttToDate[$i];  
           $chargesFormat = $ttChargesFormat[$i];  
           $charges =$ttCharges[$i];  
           if($str!='') {
             $str .=",";
           }  
           $str .= "('$classId', '$fineTypeId', '$fromDate', '$toDate', '$chargesFormat', '$charges')";
        }
    }
    
    
	if($str!='') {
        //****************************************************************************************************************    
        //***********************************************STRAT TRANSCATION************************************************
        //****************************************************************************************************************
        if(SystemDatabaseManager::getInstance()->startTransaction()) { 
           $returnStatus = $fineSetUpManager->addFineSetUpDetail($str);
	       if($returnStatus === false) {
		     echo FAILURE;
             die;
	       }
           //*****************************COMMIT TRANSACTION************************* 
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
             echo SUCCESS;             
           }
        }
    } 
    else {
      echo SUCCESS;           
    }
 ?>
