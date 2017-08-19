<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','BankMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['bankId']) || trim($REQUEST_DATA['bankId']) == '') {
        $errorMessage = BANK_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BankManager.inc.php");
        $bankManager =  BankManager::getInstance();
		$recordArray = $bankManager->checkInBank($REQUEST_DATA['bankId']);
		$bankArray = $bankManager->checkInBankBranch($REQUEST_DATA['bankId']);
		$feeArray = $bankManager->checkInFeeReceiptMaster($REQUEST_DATA['bankId']);
		$feeArray1 = $bankManager->checkInFeeReceiptDetails($REQUEST_DATA['bankId']);
		if(($recordArray[0]['found'] > 0 OR $bankArray[0]['found'] > 0) || ($feeArray[0]['cnt'] > 0 || $feeArray1[0]['cnt'] > 0)) {
				echo DEPENDENCY_CONSTRAINT;
		}
		else {
			$condition = "WHERE bankId = '".$REQUEST_DATA['bankId']."'";
			$bankNameArray = $bankManager->getBankName($condition);
			$bankName = $bankNameArray[0]['bankName'];
            		if($bankManager->deleteBank($REQUEST_DATA['bankId']) ) {
				########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################				
				$auditTrialDescription = "Following Bank Name Has Been Deleted: ";
				$auditTrialDescription .= $bankName;
				$type = BANK_NAME_IS_DELETED; 
				$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
				if($returnStatus == false) {
					echo  "Error while saving data for audit trail";
					die;
				}
				########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                		echo DELETE;
            		}
		}
	}
     else {
        echo $errorMessage;
    }
   
// $History: ajaxInitDelete.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/21/09    Time: 10:39a
//Updated in $/LeapCC/Library/Bank
//fixed issues nos.0000511,  0001157, 0001154 , 0001153, 0001150
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Bank
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 11:56a
//Updated in $/Leap/Source/Library/Bank
//add define access in module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/26/08    Time: 11:20a
//Updated in $/Leap/Source/Library/Bank
//done the common messaging
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master

?>
