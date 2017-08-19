<?php
//  This File calls addFunction used in adding Subject Records
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   //define('MODULE','COMMON');
   //UtilityManager::ifNotLoggedIn(true);
   UtilityManager::headerNoCache();


	require_once(MODEL_PATH . "/GuidersManager.inc.php");
	
	$moduleName = $REQUEST_DATA['moduleName'];
	$returnStatus = GuidersManager::getInstance()->getGuidersEntry($moduleName);
	if($returnStatus===true) {
	   echo SUCCESS;
	}
	else {
	   echo FAILURE;
	}









?>

