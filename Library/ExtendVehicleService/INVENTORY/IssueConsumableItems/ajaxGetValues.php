<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Jaineesh
// Created on : (8.03.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
define('MODULE','IssueConsumableItems');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['invConsumableIssuedId'] ) != '' and trim($REQUEST_DATA['invConsumableIssuedId'])!='') {
    
    $itemsManager = IssueItemsManager::getInstance();
    
    //check whether this record is issues or not
    //if issued then it can not be edited
        
    $foundArray = $itemsManager->getConsumableItemsDetails(' AND icii.invConsumableIssuedId="'.add_slashes(trim($REQUEST_DATA['invConsumableIssuedId'])).'"' );
	//print_r($foundArray);
	//die;
	$resultsCount = count($foundArray);
	
    if(is_array($foundArray) && count($foundArray)>0 ) {
		$jsonTimeTableArray  = array();
		for($s = 0; $s<$resultsCount; $s++) {
			$jsonTimeTableArray[] = $foundArray[$s];
		}
	}

	$resultArray = array('consumableArr' => $jsonTimeTableArray);
	echo json_encode($resultArray);
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:08a
//Created in $/Leap/Source/Library/INVENTORY/IssueConsumableItems
//new files for issue consumable items
//
//
?>