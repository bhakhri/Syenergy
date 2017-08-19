<?php
//-------------------------------------------------------
// Purpose: To get values of GeneratePO
//
// Author : Kavish Manjkhola
// Created on : (15.12.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryGRN');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
$grnManager = GRNManager::getInstance();
$POArray = $grnManager->getPO();
/*echo "<pre>";
print_r($POArray);
die;*/
	if(is_array($POArray) && count($POArray)>0 ) {
		$mainArray['PODetail'] = $POArray;
		echo json_encode($mainArray);
	}
	else {
		echo 0 ;
	}
// $History: $
//
?>