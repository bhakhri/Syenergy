<?php 
//  This File calls addFunction used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 require_once(MODEL_PATH . "/Fee/FeeConcessionManager.inc.php");
 $feeConcessionManager = FeeConcessionManager::getInstance();
 
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

	$foundArray = $feeConcessionManager->getFeeConcession(' AND UCASE(categoryName)="'.add_slashes(strtoupper($REQUEST_DATA['categoryName'])).'"');
	if(count($foundArray) > 0) {  //DUPLICATE CHECK
		$errorMessage .= CATEGORY_NAME_ALREADY_EXISTS."\n";
	}

	$foundArray = $feeConcessionManager->getFeeConcession(' AND categoryOrder ="'.add_slashes($REQUEST_DATA['categoryOrder']).'"');	
	if(count($foundArray) > 0) {  //DUPLICATE CHECK
		$errorMessage .= CATEGORY_ORDER_ALREADY_EXISTS."\n";
	}
   
	if(trim($errorMessage) == '') {
		$returnStatus = $feeConcessionManager->addFeeConcession();
		if($returnStatus === false) {
			$errorMessage = FAILURE;
		}
		else {
			echo SUCCESS;           
		}
	}
	else{
		echo $errorMessage;
	}
 
?>
