<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','delete');
    define('MANAGEMENT_ACCESS',1);  
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    $htmlManager = HtmlFunctions::getInstance();
    
    require_once(MODEL_PATH . "/CollectFeesManager.inc.php");   
    $collectFeesManager = CollectFeesManager::getInstance(); 

	$receiptId =  add_slashes(trim($REQUEST_DATA['receiptId']));
	
	if (!isset($REQUEST_DATA['receiptId']) || trim($REQUEST_DATA['receiptId']) == '') {
        $errorMessage = 'Invalid Receipt';
    }
    
    if($receiptId=='') {
      $receiptId=0;  
    }
	
	if(trim($errorMessage) == '') {
	  if(SystemDatabaseManager::getInstance()->startTransaction()) {
		 $returnStatus = $collectFeesManager->deleteReceipt($receiptId);
         if($returnStatus === false) {
           echo FAILURE; 
           die;
         }
         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
           echo DELETE;  
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