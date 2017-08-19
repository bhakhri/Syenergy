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
define('MODULE','StudentFineHistoryReport');     
define('ACCESS','delete');
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
$fineManager = FineManager::getInstance();


global $sessionHandler;
$queryDescription =''; 


    if (!isset($REQUEST_DATA['receiptId']) || trim($REQUEST_DATA['receiptId']) == '') {
        $errorMessage = 'Invalid fee Receipt.\n';
    }
    
    if (!isset($REQUEST_DATA['delReason']) || trim($REQUEST_DATA['delReason']) == '') {
        $errorMessage = 'Enter Reason For Delete.\n';
    }
    $deleteReason = add_slashes(trim($REQUEST_DATA['delReason']));
    
    if (trim($errorMessage) == '') {
    	 if(SystemDatabaseManager::getInstance()->startTransaction()){
    		// logical deletion of fee receipt details entry's
			$returnStatus = $fineManager->deleteFromReceiptDetails($REQUEST_DATA['receiptId'],$deleteReason);
			if($returnStatus === false) {
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

