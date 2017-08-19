<?php
/*
  This File calls addFunction used in adding Bank Records

 Author :Ajinder Singh
 Created on : 23-July-2008
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['fromYear']) || trim($REQUEST_DATA['fromYear']) == '') {
        $errorMessage .= ENTER_FROM_YEAR;
    }
    if (!isset($REQUEST_DATA['toYear']) || trim($REQUEST_DATA['toYear']) == '') {
        $errorMessage .= ENTER_TO_YEAR;
    }
    if (trim($REQUEST_DATA['toYear'])!='' && trim($REQUEST_DATA['fromYear'])!='' && (trim($REQUEST_DATA['toYear'])-trim($REQUEST_DATA['fromYear']))!=1)
    {
        $errorMessage .= ENTER_VALID_YEAR;
    }
   
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $foundArray = PayrollManager::getInstance()->getFromYear(' WHERE startYear="'.add_slashes(trim($REQUEST_DATA['fromYear'])).'"');  

        if(trim($foundArray[0]['startYear'])=='') {  //DUPLICATE CHECK
			//check for bankAbbr duplicacy
			//$foundArray2 = BankManager::getInstance()->getBankAbbr(' WHERE UCASE(bankAbbr) = "'.add_slashes(trim(strtoupper($REQUEST_DATA['bankAbbr']))).'"');
			//if (trim($foundArray2[0]['bankAbbr'] == '')) {       
				$returnStatus = PayrollManager::getInstance()->addYear();
                if($returnStatus === false) {
                $errorMessage = FAILURE;
                }
                else 
                {
                echo SUCCESS;
                }
        }
        else {
            echo START_YEAR_EXISTS;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
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

