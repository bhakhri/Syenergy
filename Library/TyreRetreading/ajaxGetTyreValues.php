<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE INSURANCE LIST
//
// Author : Jaineesh
// Created on : (26.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TyreRetreadingReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['tyreNo'] ) != '') {
     require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();
    $getTyreArray = $tyreRetreadingManager->getTyreList($REQUEST_DATA['tyreNo']);
	$tyreNo = $getTyreArray[0]['tyreRecords'];
	if($tyreNo == 0) {
		echo 0;// No record found
	}
	else {
		echo 1;
	}
  }

// $History: ajaxGetTyreValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/02/10    Time: 5:17p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//
?>