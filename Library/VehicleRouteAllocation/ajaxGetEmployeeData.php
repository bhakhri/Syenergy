<?php
//-------------------------------------------------------
// Purpose: To store the records of room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (2.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoomAllocation');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $vehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();

    $param = add_slashes(trim($REQUEST_DATA['param']));
    $condition = " AND (e.employeeCode = '".$param."') ";
    $employeeArray=$vehicleRouteAllocationManager->getEmployeeAllData($condition);
    
  //print_r($employeeArray);die;
    if(count($employeeArray) > 0) {
       
       //echo count($employeeArray);die;
       //$condition = " degreeId = '$degreeId' AND branchId = '$branchId'  AND batchId = '$batchId' AND isActive IN (1,2,3) ";
     //  $classArray=$vehicleRouteAllocationManager->getAllClass($condition);
       
      // $condition = " AND e.employeeId = '".$employeeArray[0]['employeeId']."'";
      // $previousArray=$vehicleRouteAllocationManager->getVehicleRouteAllocationList($condition);
       
       echo json_encode($employeeArray);
       die;
    }
    
    echo 0;
?>
