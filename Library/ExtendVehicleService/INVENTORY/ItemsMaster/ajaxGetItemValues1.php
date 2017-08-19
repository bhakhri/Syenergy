<?php
//-------------------------------------------------------
// Purpose: To get values of hostel from the database
//
// Author : DB
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ItemsMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
        $categoryCode = $REQUEST_DATA['categoryCode'];

	$condition='AND im.itemCategoryId="'.$REQUEST_DATA['categoryCode'].'"';
	$foundArray = ItemsManager::getInstance()->getItemNew($condition);

	if(is_array($foundArray) && count($foundArray)>0 ) {
	   echo json_encode($foundArray);
	}
	else {
	   echo 0 ;
	}

?>
