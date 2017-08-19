<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
   
    $classId = $REQUEST_DATA['classId'];
    if($classId=='') {
      $classId=0;  
    }
    $condition =  " c.classId = '$classId' ";
    $feeInstituteArray = $roomAllocationManager->getInstituteClass($condition); 
    $instituteId = $feeInstituteArray[0]['instituteId'];
    if($instituteId=='') {
      $instituteId=0;  
    }
    $condition =  " fc.instituteId = '$instituteId' ";
    $feeCycleArray = $roomAllocationManager->getAllInstituteFeeCycle($condition);
    
    
    echo json_encode($feeCycleArray);
?>