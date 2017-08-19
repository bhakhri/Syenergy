<?php

//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
require_once(MODEL_PATH . "/FeeConcessionManager.inc.php");

define('MODULE','FeeConcessionMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;
$queryDescription =''; 

    $errorMessage ='';
    if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage .=  ENTER_CATEGORY_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryOrder']) || trim($REQUEST_DATA['categoryOrder']) == '')) {
        $errorMessage .= ENTER_CATEGORY_ORDER."\n";  
    }

	$categoryName = add_slashes(strtoupper(trim($REQUEST_DATA['categoryName'])));
	$categoryOrder = add_slashes(strtoupper(trim($REQUEST_DATA['categoryOrder'])));
	$categoryId = add_slashes(strtoupper(trim($REQUEST_DATA['categoryId'])));
   
	
    if (trim($errorMessage) == '') {
  	    
		$condition = " WHERE (UCASE(categoryName)= '".$categoryName."' OR UCASE(categoryOrder)='".$categoryOrder."') AND categoryId != ".$categoryId;

        $foundArray = FeeConcessionManager::getInstance()->getFeeConcession($condition);
        if(trim($foundArray[0]['categoryName'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeeConcessionManager::getInstance()->editFeeConcession($REQUEST_DATA['categoryId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
		$auditTrialDescription = "Following fee Concession has been edited: ";
		$auditTrialDescription .= $REQUEST_DATA['categoryName']  ;
		$type = FEE_CONCESSION_EDITED; //Fee Head is created
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
		  if(strtoupper(trim($foundArray[0]['categoryName']))==$categoryName){  
			echo CATEGORY_NAME_ALREADY_EXISTS;
			die;
		  }
		  if(strtoupper(trim($foundArray[0]['categoryOrder']))==$categoryOrder){  
			echo CATEGORY_ORDER_ALREADY_EXISTS;
			die;
		  }
        }
    }
    else {
        echo $errorMessage;
    }
?>
