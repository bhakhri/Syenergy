<?php
//
//  This File calls Edit Function used in adding Bank Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','BankMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['bankName']) || trim($REQUEST_DATA['bankName']) == '') {
        $errorMessage .= ENTER_BANK_NAME;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['bankAbbr']) || trim($REQUEST_DATA['bankAbbr']) == '')) {
        $errorMessage .= ENTER_BANK_ABBR;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BankManager.inc.php");
        $foundArray = BankManager::getInstance()->getBank(' WHERE UCASE(bankName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['bankName']))).'" AND bankId!='.$REQUEST_DATA['bankId']);

		if(trim($foundArray[0]['bankName'])=='') {  //DUPLICATE Bank NAME CHECK
        
			$foundArray2 = BankManager::getInstance()->getBank(' WHERE UCASE(bankAbbr)= "'.add_slashes(trim(strtoupper($REQUEST_DATA['bankAbbr']))).'" AND bankId!='.$REQUEST_DATA['bankId']);
			if(trim($foundArray2[0]['bankAbbr'])=='') {  //DUPLICATE Bank YEAR CHECK
				$returnStatus = BankManager::getInstance()->editBank($REQUEST_DATA['bankId']);
				if($returnStatus === false) {
					$errorMessage = FAILURE;
				}
				else {
					########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
					$auditTrialDescription = "Following Bank Name Has Been Edited ";
					$auditTrialDescription .= $REQUEST_DATA['bankName'];
					$type = BANK_NAME_IS_EDITED; 
					$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
					if($returnStatus == false) {
						echo  "Error while saving data for audit trail";
						die;
					}
					########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
					echo SUCCESS;
				}
			}
			else {
				echo BANK_ABBR_ALREADY_EXIST;
			}
        }
        else {
            echo BANK_NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
//$History: ajaxInitEdit.php $	
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/20/09    Time: 10:21a
//Updated in $/LeapCC/Library/Bank
//fixed bug nos.0001145,  0001127, 0001126, 0001125, 0001119, 0001101,
//0001110
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
