<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DESIGNATION LIST
//
//
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','IndentMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['indentId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
	$indentManager = IndentManager::getInstance();
	$mainArray = array();
    $foundArray = $indentManager->getIndent(" AND iim.indentId=".$REQUEST_DATA['indentId']);
	
	if(is_array($foundArray) && count($foundArray)>0 ) {
		$mainArray['indentDetail'] = $foundArray;
		echo json_encode($mainArray);
	}
	else {
		echo 0;  //no record found
	}
	
}

// $History: $
//
?>