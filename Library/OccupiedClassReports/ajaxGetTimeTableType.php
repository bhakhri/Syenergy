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
define('MODULE','OccupiedFreeClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/OccupiedClassManager.inc.php");
    $foundArray = OccupiedClassManager::getInstance()->getTimeTableType($REQUEST_DATA['timeTableLabelId']);
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
}

// $History: $
//
?>