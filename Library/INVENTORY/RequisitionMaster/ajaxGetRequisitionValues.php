<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DESIGNATION LIST
//
//
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RequisitionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['requisitionId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
    $requisitionManager = RequisitionManager::getInstance();
	$mainArray = array();
    $foundArray = $requisitionManager->getRequisition(" AND irm.requisitionId=".$REQUEST_DATA['requisitionId']);
	
	if(is_array($foundArray) && count($foundArray)>0 ) {
		$mainArray['requisitionDetail'] = $foundArray;
		echo json_encode($mainArray);
	}
	else {
		echo 0;  //no record found
	}
	
}

// $History: $
//
?>