<?php 
//  This File calls addFunction used in adding FeeHead Records
// Author :Nishu Bindal
// Created on : 2-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
$feeHeadManager = FeeHeadManager::getInstance();
define('MODULE','FeeHeadsNew');     
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);           
UtilityManager::headerNoCache(); 


global $sessionHandler;
$queryDescription =''; 

    $errorMessage ='';

  
	if(!isset($REQUEST_DATA['headName']) || trim($REQUEST_DATA['headName']) == '') {
		$errorMessage .= ENTER_FEEHEAD_NAME."\n";
	}
	if($errorMessage == '' && (!isset($REQUEST_DATA['headAbbr']) || trim($REQUEST_DATA['headAbbr']) == '')) {
		$errorMessage .= ENTER_FEEHEAD_ABBR."\n";
	}
	$headName  = trim($REQUEST_DATA['headName']); 
	$headAbbr  = trim($REQUEST_DATA['headAbbr']); 
	$sortOrder = trim($REQUEST_DATA['sortOrder']);


	$foundArray = $feeHeadManager->getFeeHead(' AND UCASE(sortingOrder)="'.$sortOrder.'"');
	if(count($foundArray) > 0){  //DUPLICATE CHECK 
		$errorMessage .= FEEHEAD_DISPLAY_ORDER_EXIST."\n";
	}

	$foundArray = $feeHeadManager->getFeeHead(' AND UCASE(headName)="'.add_slashes(strtoupper($headName)).'"');
	if(count($foundArray) > 0){   //DUPLICATE CHECK
	  	$errorMessage .= FEEHEAD_NAME_EXIST."\n";
	}

	$foundArray = $feeHeadManager->getFeeHead(' AND UCASE(headAbbr)="'.add_slashes(strtoupper($headAbbr)).'"');
	if(count($foundArray) > 0){  //DUPLICATE CHECK
		$errorMessage .= FEEHEAD_ABBR_EXIST."\n";
	}
        
	if(trim($errorMessage) == ''){ 
		$returnStatus = $feeHeadManager->addFeeHead();
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
