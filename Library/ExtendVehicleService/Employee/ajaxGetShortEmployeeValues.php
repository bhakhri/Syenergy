<?php

//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE EMPLOYEE LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ShortEmployeeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['employeeId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
	$foundArray = null;
	$foundArray = EmployeeManager::getInstance()->getShortEmployee(' AND emp.employeeId='.$REQUEST_DATA['employeeId']);
	if(is_array($foundArray) && count($foundArray)>0 ) {
		echo '{"edit":'.json_encode($foundArray).'}';
		//echo json_encode($foundArray);
	}
	else {
		echo 0; // no record found
	}
}

// $History: $
//
?>