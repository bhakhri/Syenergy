<?php
/*
  This File calls addFunction used in adding Bank Branch Records

 Author :Ajinder Singh
 Created on : 23-July-2008
 Copyright 2008-2009: syenergy Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['bankId']) || trim($REQUEST_DATA['bankId']) == '') {
        $errorMessage .= SELECT_BANK;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['branchName']) || trim($REQUEST_DATA['branchName']) == '')) {
        $errorMessage .= ENTER_BRANCH_NAME;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['branchAbbr']) || trim($REQUEST_DATA['branchAbbr']) == '')) {
        $errorMessage .= ENTER_BRANCH_ABBR;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['accountType']) || trim($REQUEST_DATA['accountType']) == '')) {
        $errorMessage .= ENTER_ACCOUNT_TYPE;
    }
    /*if ($errorMessage == '' && (!isset($REQUEST_DATA['accountNumber']) || trim($REQUEST_DATA['accountNumber']) == '')) {
        $errorMessage .= ENTER_ACCOUNT_NUMBER;
    }*/
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BankBranchManager.inc.php");
        $foundArray = BankBranchManager::getInstance()->getBankBranchName(' WHERE UCASE(branchName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['branchName']))).'"');  

        if(trim($foundArray[0]['branchName'])=='') {  //DUPLICATE CHECK
			//check for bankAbbr duplicacy
			$foundArray2 = BankBranchManager::getInstance()->getBankBranchAbbr(' WHERE UCASE(branchAbbr) = "'.add_slashes(trim(strtoupper($REQUEST_DATA['branchAbbr']))).'"');

			if (trim($foundArray2[0]['branchAbbr'] == '')) {
				$foundArray3 = BankBranchManager::getInstance()->getBankBranchAccountNumber(' WHERE UCASE(accountNumber) = "'.add_slashes(strtoupper($REQUEST_DATA['accountNumber'])).'"');
				if (trim($foundArray3[0]['accountNumber'] == '')) {
					$returnStatus = BankBranchManager::getInstance()->addBankBranch();
					if($returnStatus === false) {
						$errorMessage = FAILURE;
					}
					else {
						echo SUCCESS;           
					}
				}
				else {
					echo ACCOUNT_NUMBER_ALREADY_EXIST;
				}
			}
			else {
				echo BRANCH_ABBR_ALREADY_EXIST;
			}
        }
        else {
            echo BRANCH_NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Library/BankBranch
//updated with all the fees enhancements
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Library/BankBranch
//Merged Bank & BankBranch module in single module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/21/09    Time: 10:39a
//Updated in $/LeapCC/Library/BankBranch
//fixed issues nos.0000511,  0001157, 0001154 , 0001153, 0001150
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BankBranch
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 11:58a
//Updated in $/Leap/Source/Library/BankBranch
//add define access in module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/26/08    Time: 11:20a
//Updated in $/Leap/Source/Library/BankBranch
//done the common messaging
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 5:49p
//Created in $/Leap/Source/Library/BankBranch
//File added for bank branch master
?>

