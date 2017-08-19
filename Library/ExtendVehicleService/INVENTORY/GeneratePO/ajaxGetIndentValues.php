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
define('MODULE','InventoryGeneratePO');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
$poManager = POManager::getInstance();
$indentArray = $poManager->getIndentValues();
	if(is_array($indentArray) && count($indentArray)>0 ) {
		$mainArray['indentDetail'] = $indentArray;
		echo json_encode($mainArray);
	}
	else {
		echo 0 ;
	}
// $History: $
//
?>