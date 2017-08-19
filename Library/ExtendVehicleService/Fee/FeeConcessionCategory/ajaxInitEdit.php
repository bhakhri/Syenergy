<?php

//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
// Author : Nishu Bindal
// Created on : (4.feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance(); 
require_once(MODEL_PATH . "/Fee/FeeConcessionManager.inc.php");
$feeConcessionManager = FeeConcessionManager::getInstance();
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
	$categoryId = trim($REQUEST_DATA['categoryId']);
   
   	$condition = " AND UCASE(categoryName)= '".$categoryName."' AND categoryId != ".$categoryId;
        $foundArray = $feeConcessionManager->getFeeConcession($condition);
        if(count($foundArray) > 0) {  //DUPLICATE CHECK
            $errorMessage .=CATEGORY_NAME_ALREADY_EXISTS."\n";
        }
        
        $condition = " AND UCASE(categoryOrder)='".$categoryOrder."' AND categoryId != ".$categoryId;
        $foundArray = $feeConcessionManager->getFeeConcession($condition);
        if(count($foundArray) > 0) {  //DUPLICATE CHECK
            $errorMessage .=CATEGORY_ORDER_ALREADY_EXISTS."\n";
        }
      
	if (trim($errorMessage) == '') {
		$returnStatus = $feeConcessionManager->editFeeConcession($categoryId);
		if($returnStatus === false) {
			$errorMessage = FAILURE;
		}
		else {
			echo SUCCESS;           
		}	    
	}
	else {
		echo $errorMessage;
	}
?>
