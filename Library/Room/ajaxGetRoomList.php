<?php
//-------------------------------------------------------
//  This File is used for fetching block for 
//
//
// Author :Jaineesh
// Created on : 12.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
    $roomAllocationManager = RoomAllocationManager::getInstance();      
    
	$hostelId = $REQUEST_DATA['hostelId'];
    
    if($hostelId=='') {
      $hostelId ='0';  
    }
    
    $condition="";
    if($hostelId!='0') {
      $condition = " AND h.hostelId = '$hostelId' ";  
    }
    
	$hostelRoomArray = $roomAllocationManager->getHostelRoomData($condition);
	
    echo json_encode($hostelRoomArray);


?>