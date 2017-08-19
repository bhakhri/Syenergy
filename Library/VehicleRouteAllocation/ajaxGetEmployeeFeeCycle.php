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
    define('MODULE','VehicleRouteAllocation');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $vehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
  
    global $sessionHandler;   
       
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
   
    $condition =  "WHERE instituteId = '$instituteId'";
    $feeCycleArray =  $vehicleRouteAllocationManager->getFeeCycleNew($condition);
    
    echo json_encode($feeCycleArray);
?>
