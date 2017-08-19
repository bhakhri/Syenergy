<?php
/*
  This File calls addFunction used in adding heads for payroll

 Author :Abhiraj Malhotra
 Created on : 07-April-2010
 Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.

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
    if (!isset($REQUEST_DATA['headName']) || trim($REQUEST_DATA['headName']) == '') {
        $errorMessage .= ENTER_HEAD_NAME;
    }
    if (!isset($REQUEST_DATA['headType']) || trim($REQUEST_DATA['headType']) == '') {
        $errorMessage .= SELECT_HEAD_TYPE;
    }
    if (trim($REQUEST_DATA['headType']) == 1 && trim($REQUEST_DATA['dedAccountId'])=='') {
        $errorMessage .= SELECT_DEDUCTION_ACCOUNT;
    }
    if (!isset($REQUEST_DATA['headAbbr']) || trim($REQUEST_DATA['headAbbr']) == '') {
        $errorMessage .= ENTER_HEAD_ABBR;
    }
   
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $foundArray = PayrollManager::getInstance()->getHead(' WHERE headName="'.add_slashes(trim($REQUEST_DATA['headName'])).'"');  

        if(trim($foundArray[0]['headName'])=='') {  //DUPLICATE CHECK      
				$returnStatus = PayrollManager::getInstance()->addHead();
                if($returnStatus === false) {
                $errorMessage = FAILURE;
                }
                else 
                {
                echo SUCCESS;
                }
        }
        else {
            echo HEAD_NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAddHead.php $
//*****************  Version 1  *****************
//User: Abhiraj      Date: 4/07/10    Time: 12:41p
//Created in $/Leap/Source/Library/Payroll
//File created for Payroll - Heads Master
?>

