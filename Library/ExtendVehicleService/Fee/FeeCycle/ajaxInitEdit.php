<?php
//-------------------------------------------------------
// Purpose: To update feecycle table data
//Author : Nishu Bindal
// Created on : 13.feb.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 require_once(MODEL_PATH . "/Fee/FeeCycleManager.inc.php");
 $feeCycleManager = FeeCycleManager::getInstance();
define('MODULE','FeeCycleMasterNew'); 
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	$errorMessage ='';
	global $sessionHandler;
	$queryDescription =''; 
	if (!isset($REQUEST_DATA['cycleName']) || trim($REQUEST_DATA['cycleName']) == '') {
		$errorMessage .= ENTER_FEECYCLE_NAME."\n";
	}
	if (!isset($REQUEST_DATA['cycleAbbr']) || trim($REQUEST_DATA['cycleAbbr']) == '') {
		$errorMessage .= ENTER_FEECYCLE_ABBR."\n";
	}
	if($REQUEST_DATA['active'] == 1){
	    	$foundArray =  $feeCycleManager->getFeeCycle(' AND status =1  AND feeCycleId!='.$REQUEST_DATA['feeCycleId']);
		if(count($foundArray) > 0) {  //DUPLICATE CHECK
			$errorMessage .= "Only One Fee Cycle Can be Active at a time.\n";
		}
	}
	$foundArray =  $feeCycleManager->getFeeCycle(' AND UCASE(cycleName)= "'.add_slashes(trim(strtoupper($REQUEST_DATA['cycleName']))).'"  AND feeCycleId!='.$REQUEST_DATA['feeCycleId']);
	if(count($foundArray) > 0) {  //DUPLICATE CHECK
		$errorMessage .= CYCLE_NAME_EXIST."\n";
	}

	$foundArray =  $feeCycleManager->getFeeCycle(' AND UCASE(cycleAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['cycleAbbr']))).'" AND feeCycleId!='.$REQUEST_DATA['feeCycleId']);
	if(count($foundArray) > 0) {  //DUPLICATE CHECK
		$errorMessage .= CYCLE_ABBR_EXIST."\n";
	}
	
	if(trim($errorMessage) == ''){
		$returnStatus =  $feeCycleManager->editFeeCycle($REQUEST_DATA['feeCycleId']);
		if($returnStatus === false){
			echo FAILURE;
		}
		else{
			echo SUCCESS;           
		}
	}
	else{
		echo $errorMessage;
	}
    

?>

