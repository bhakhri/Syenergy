<?php
//-------------------------------------------------------
// Purpose: To delete country detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
define('MODULE','FeeConcessionMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


global $sessionHandler;
$queryDescription =''; 

    if (!isset($REQUEST_DATA['categoryId']) || trim($REQUEST_DATA['categoryId']) == '') {
        $errorMessage = 'Invalid category';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeConcessionManager.inc.php");
        $FeeConcessionManager =  FeeConcessionManager::getInstance();
		$condition = "WHERE categoryId =".$REQUEST_DATA['categoryId'];
		$catagoryNameArray = $FeeConcessionManager->getFeeConcession($condition);
		$catagoryName=$catagoryNameArray[0]['categoryName'];          
		 if($FeeConcessionManager->deleteFeeConcession($REQUEST_DATA['categoryId']) ) {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
		$auditTrialDescription = "Following fee Concession has been deleted: ";
		$auditTrialDescription .= $catagoryName;
		$type = FEE_CONCESSION_DELETED; //Fee Head is created
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                echo DELETE;
            }
        }
      
    else {
        echo $errorMessage;
    }
   ?>
