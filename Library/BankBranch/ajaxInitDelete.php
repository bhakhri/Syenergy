<?php
//-------------------------------------------------------
// Purpose: To delete Bank Branch
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BankMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['bankBranchId']) || trim($REQUEST_DATA['bankBranchId']) == '') {
        $errorMessage = BRANCH_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BankBranchManager.inc.php");
        $bankBranchManager =  BankBranchManager::getInstance();
		$recordArray = $bankBranchManager->checkInBankBranch($REQUEST_DATA['bankBranchId']);
		  if ($recordArray[0]['found'] > 0) {
			echo DEPENDENCY_CONSTRAINT;
		  }
		  else {
              if($bankBranchManager->deleteBankBranch($REQUEST_DATA['bankBranchId']) ) {
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