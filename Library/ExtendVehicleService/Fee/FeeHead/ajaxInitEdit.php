<?php 

//  This File calls Edit Function used in adding "FeeHead" Records
//
// Author :Nishu Bindal
// Created on : 2-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;

require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");  
$feeHeadManager = FeeHeadManager::getInstance();
define('MODULE','FeeHeadsNew');     
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 


	global $sessionHandler;
	$queryDescription =''; 

	$feeHeadId   = trim($REQUEST_DATA['feeHeadId']); 
	$headName    = addslashes(trim($REQUEST_DATA['headName'])); 
	$headAbbr    = addslashes(trim($REQUEST_DATA['headAbbr'])); 
	$sortOrder   = trim($REQUEST_DATA['sortOrder']); 

	$errorMessage ='';
	if (!isset($headName) || trim($headName) == '') {
		$errorMessage .= ENTER_FEEHEAD_NAME."\n";
	}
	
	if ($errorMessage == '' && (!isset($headAbbr) || trim($headAbbr) == '')) {
		$errorMessage .= ENTER_FEEHEAD_ABBR."\n";
	}
	$foundArray = $feeHeadManager->getFeeHead(' AND UCASE(sortingOrder)="'.$sortOrder.'" AND feeHeadId!='.$feeHeadId);

	if(count($foundArray) > 0) {  //DUPLICATE CHECK 
		$errorMessage .= FEEHEAD_DISPLAY_ORDER_EXIST."\n";
	}


	$foundArray = $feeHeadManager->getFeeHead(' AND UCASE(headName)="'.strtoupper($headName).'" AND feeHeadId!='.$feeHeadId);
	if(count($foundArray)>0) {  //DUPLICATE CHECK
		$errorMessage .= FEEHEAD_NAME_EXIST."\n";
	}


	$foundArray = $feeHeadManager->getFeeHead(' AND UCASE(headAbbr)="'.strtoupper($headAbbr).'" AND feeHeadId!='.$feeHeadId);
	if(count($foundArray) > 0) {  //DUPLICATE CHECK 
		$errorMessage .= FEEHEAD_ABBR_EXIST."\n";
	}
              
        
        if($errorMessage == ''){        
		$returnStatus = $feeHeadManager->editFeeHead($feeHeadId);            
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;           
		}
	}
	else{
		echo $errorMessage;
	}

?>
