<?php
//-------------------------------------------------------
// Purpose: To store the records of fine receipt
//
// Author : SAURABH THUKRAL
// Created on : (25.08.2012)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CollectFine');
    define('ACCESS','add');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
	
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();
  
    global $sessionHandler;   
    
    $studentId=trim($REQUEST_DATA['studentId']);
    $classId = trim($REQUEST_DATA['studentClass']);
    $paymentMode = trim($REQUEST_DATA['paidAt']);
    $bankScrollNo = trim(add_slashes($REQUEST_DATA['bankScrollNo']));

    if($studentId=='') {
      echo "Parameter missing. Please try again";
      die;  
    }    
    
    if($classId=='') {
      echo "Parameter missing. Please try again";
      die;  
    }    

	if($paymentMode=='1') {
  	  if($bankScrollNo=='') {
        echo "Parameter missing. Please try again";
        die;  
	  }
	}
    
    
    $amountByCash = trim($REQUEST_DATA['cashAmount']);
    $amountByDD = $REQUEST_DATA['amount'];
    $rollNo = trim(add_slashes($REQUEST_DATA['studentRoll']));

	$totalAmount=0;
	for($i=0;$i<count($amountByDD);$i++){
	  $count += $amountByDD[$i];
	}
	$totalAmount=$amountByCash+$count;

	/* START: function to fetch student Serial Number */
    // $paymentMode:  2=> On Desk, 1=> Bank
  	if($paymentMode==''){
	  echo "Select Payment Mode";
	  die;
	}

        
    /* END: function to fetch student Serial Number */
    //****************************************************************************************************************    
    //***********************************************STRAT TRANSCATION************************************************
    //****************************************************************************************************************
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        $returnStatus = $fineStudentManager->insertFine($totalAmount);
		if($returnStatus===false) {            
		  echo FAILURE;
          die;
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
          echo SUCCESS."~".$returnStatus;  
        }
    }

?>
