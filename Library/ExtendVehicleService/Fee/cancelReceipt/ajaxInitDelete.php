<?php
//-------------------------------------------------------
// Purpose: To delete Fee Receipt
// Author : Nishu Bindal
// Created on : (9.05.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;

require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Fee/CanceledReceiptsManager.inc.php");   
$PaymentHistoryReportManager = CanceledReceiptsManager::getInstance();
define('MODULE','CanceledReceipts');     
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


global $sessionHandler;
$queryDescription =''; 


    if (!isset($REQUEST_DATA['receiptId']) || trim($REQUEST_DATA['receiptId']) == '') {
        $errorMessage = 'Invalid fee Receipt.\n';
    }
    if (!isset($REQUEST_DATA['receiptNo']) || trim($REQUEST_DATA['receiptNo']) == '') {
        $errorMessage = 'Invalid fee Receipt No.\n';
    }
    if (!isset($REQUEST_DATA['delReason']) || trim($REQUEST_DATA['delReason']) == '') {
        $errorMessage = 'Enter Reason For Delete.\n';
    }
    $deleteReason = add_slashes(trim($REQUEST_DATA['delReason']));
    
    if (trim($errorMessage) == '') {
    	 if(SystemDatabaseManager::getInstance()->startTransaction()){
    		// logical deletion of fee receipt details entry's
		$returnStatus = $PaymentHistoryReportManager->deleteFromReceiptDetails($REQUEST_DATA['receiptId'],$REQUEST_DATA['receiptNo'],$deleteReason);
		if($returnStatus === false) {
                   echo DEPENDENCY_CONSTRAINT;
                  die;
               }
		// logical deletion of fee Head Collection entry's
		$returnStatus1 = $PaymentHistoryReportManager->deleteFromHeadCollection($REQUEST_DATA['receiptId'],$REQUEST_DATA['receiptNo'],$deleteReason);
		if($returnStatus1 === false) {
                   echo DEPENDENCY_CONSTRAINT;
                   die;
               }
               
	     //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		   echo DELETE;
              }
              else {
                 echo DEPENDENCY_CONSTRAINT;
                 die;
              }    
        }
        else {
		echo FAILURE;
		die;
	}
	    
    }
    else {
        echo $errorMessage;
    }
   
    

?>

