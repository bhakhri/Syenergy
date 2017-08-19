<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OrderMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

//print_r($REQUEST_DATA);
//die;
if(trim($REQUEST_DATA['grnId'] ) != '') {
    
    require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
    $grnManager = GRNManager::getInstance();
    
    //check whether this record is issues or not
    //if issued then it can not be edited
    $mainArray = array();
    $foundArray = $grnManager->getGRN(" AND igm.grnId=".$REQUEST_DATA['grnId']);
	
	if(is_array($foundArray) && count($foundArray)>0 ) {
		$mainArray['indentDetail'] = $foundArray;
		echo json_encode($mainArray);
	}
	else {
		echo 0;  //no record found
	}
}

// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Library/INVENTORY/IndentMaster
//Created  "Indent Master" module under "Inventory Management"
?>