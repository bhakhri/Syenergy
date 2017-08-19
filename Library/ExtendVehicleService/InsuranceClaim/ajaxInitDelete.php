<?php
//-------------------------------------------------------
// Purpose: To delete Insurance Vehicle detail
//
// Author : Jaineesh
// Created on : (03.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceClaim');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['claimId']) || trim($REQUEST_DATA['claimId']) == '') {
        $errorMessage = INVALID_INSURANCE_CLAIM;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/InsuranceClaimManager.inc.php");
		$insuranceClaimManager = InsuranceClaimManager::getInstance();
		
		if($insuranceClaimManager->deleteInsuranceClaim($REQUEST_DATA['claimId'])) {
			echo DELETE;
		}
		else {
			echo DEPENDENCY_CONSTRAINT;
		}
	 }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:45a
//Created in $/Leap/Source/Library/InsuranceClaim
//new ajax files for vehicle insurance claim
//
//
?>