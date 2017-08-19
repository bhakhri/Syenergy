<?php
//-------------------------------------------------------
// Purpose: To delete payroll deduction account detail
//
// Author : Abhiraj Malhotra
// Created on : 15-April-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['dedAccountId']) || trim($REQUEST_DATA['dedAccountId']) == '') {
        $errorMessage = BANK_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $payrollManager =  PayrollManager::getInstance();
		$recordArray = $payrollManager->checkInSalaryHead($REQUEST_DATA['dedAccountId']);
		if($recordArray[0]['found'] > 0) {
				echo DEPENDENCY_CONSTRAINT;
		}
		else {
            if($payrollManager->deleteAccount($REQUEST_DATA['dedAccountId']) ) {
                echo DELETE;
            }
		}
	}
     else {
        echo $errorMessage;
    }

?>