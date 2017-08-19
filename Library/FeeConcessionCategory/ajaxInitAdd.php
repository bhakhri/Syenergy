<?php 
//  This File calls addFunction used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
define('MODULE','FeeConcessionMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;
$queryDescription =''; 

    $errorMessage ='';
    if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage .= ENTER_CATEGORY_NAME."\n";
    }
	if (!isset($REQUEST_DATA['categoryOrder']) || trim($REQUEST_DATA['categoryOrder']) == '') {
        $errorMessage .= ENTER_CATEGORY_ORDER."\n";
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeConcessionManager.inc.php");
         $foundArray = FeeConcessionManager::getInstance()->getFeeConcession(' WHERE  UCASE(categoryName)="'.add_slashes(strtoupper($REQUEST_DATA['categoryName'])).'"');
         if(trim($foundArray[0]['categoryName'])=='') {  //DUPLICATE CHECK
		   $foundArray = FeeConcessionManager::getInstance()->getFeeConcession(' WHERE categoryOrder ="'.add_slashes($REQUEST_DATA['categoryOrder']).'"');
            if(trim($foundArray[0]['categoryOrder'])=='') {  //DUPLICATE CHECK
			        $returnStatus = FeeConcessionManager::getInstance()->addFeeConcession();

                    if($returnStatus === false) {
                        $errorMessage = FAILURE;
                    }
                    else {
			########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
			$auditTrialDescription = "Following fee Concession has been added: ";
			$auditTrialDescription .= $REQUEST_DATA['categoryName']  ;
			$type =FEE_CONCESSION_ADDED; //Fee Head is created
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
			if($returnStatus == false) {
				echo  "Error while saving data for audit trail";
				die;
			}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                        echo SUCCESS;           
                    }
              }
              else {
                echo CATEGORY_ORDER_ALREADY_EXISTS;
            }
		 }
			else {
				echo CATEGORY_NAME_ALREADY_EXISTS;
        }
	}
    else {
        echo $errorMessage;
    }
 
?>
