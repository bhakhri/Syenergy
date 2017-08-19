<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE VEHICLE TYPE LIST
//
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TyreRetreading');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['retreadingId'] ) != '') {
    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $foundArray = TyreRetreadingManager::getInstance()->getRetreading(' AND retreadingId="'.$REQUEST_DATA['retreadingId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
  }

// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:35p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//
?>