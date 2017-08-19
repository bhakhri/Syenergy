<?php
//-------------------------------------------------------
// Purpose: To delete existing head in payroll heads master
//
// Author : Abhiraj Malhotra
// Created on : 07-April-2010
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
    if (!isset($REQUEST_DATA['headId']) || trim($REQUEST_DATA['headId']) == '') {
        $errorMessage = BANK_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $payrollManager =  PayrollManager::getInstance();
		$recordArray = $payrollManager->checkHeadInSalaryBreakup($REQUEST_DATA['headId']);
		if($recordArray[0]['found'] > 0) {
				echo DEPENDENCY_CONSTRAINT;
		}
		else {
            if($payrollManager->deleteHead($REQUEST_DATA['headId']) ) {
                echo DELETE;
            }
		}
	}
     else {
        echo $errorMessage;
    }
   
// $History: ajaxInitDeleteHead.php $
//
//*****************  Version 1  *****************
//User: Abhiraj      Date: 4/07/10    Time: 4:41p
//Created in $/Leap/Source/Library/Payroll
//File created for Payroll Heads Master

?>