<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DESIGNATION LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (29.04.09 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TemporaryDesignationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['tempDesignationId'] ) != '') {
    require_once(MODEL_PATH . "/DesignationTempManager.inc.php");
    $foundArray = DesignationTempManager::getInstance()->getDesignation(' WHERE tempDesignationId="'.$REQUEST_DATA['tempDesignationId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
}


?>