<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['yearId']) || trim($REQUEST_DATA['yearId']) == '') {
        $errorMessage = BANK_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $payrollManager =  PayrollManager::getInstance();
		$recordArray = $payrollManager->checkInSalaryBreakup($REQUEST_DATA['yearId']);
		if($recordArray[0]['found'] > 0) {
				echo DEPENDENCY_CONSTRAINT;
		}
		else {
            if($payrollManager->deleteYear($REQUEST_DATA['yearId']) ) {
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