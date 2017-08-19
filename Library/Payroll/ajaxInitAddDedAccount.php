<?php
/*
  This File calls addFunction used in adding PAYROLL DEDUCTION ACCOUNTS

 Author :Abhiraj Malhotra
 Created on : 14-April-2010
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
    if (!isset($REQUEST_DATA['accountName']) || trim($REQUEST_DATA['accountName']) == '') {
        $errorMessage .= ENTER_ACCOUNT_NAME;
    }
    if (!isset($REQUEST_DATA['accountNumber']) || trim($REQUEST_DATA['accountNumber']) == '') {
        $errorMessage .= ENTER_ACCOUNT_NUMBER;
    }
   
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $foundArrayName = PayrollManager::getInstance()->getDedAccount(' WHERE accountName="'.add_slashes(trim($REQUEST_DATA['accountName'])).'"');  
        $foundArrayNumber = PayrollManager::getInstance()->getDedAccount(' WHERE accountNumber="'.add_slashes(trim($REQUEST_DATA['accountNumber'])).'"');  

        if(trim($foundArrayName[0]['accountName'])=='') {  //DUPLICATE CHECK
             if(trim($foundArrayNumber[0]['accountNumber'])=='')
             {
				$returnStatus = PayrollManager::getInstance()->addAccount();
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                    }
                else 
                {
                    echo SUCCESS;
                }
             }
             else
             {
                echo ACCOUNT_NUMBER_ALREADY_EXISTS; 
             }
                
        }
        else {
            echo ACCOUNT_NAME_ALREADY_EXISTS;
        }
    }
    else {
        echo $errorMessage;
    }

?>

