<?php
//-------------------------------------------------------
// Purpose: To store the records of room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (2.07.2008 )
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

    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $vehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();

    $param = add_slashes(trim($REQUEST_DATA['param']));
    
    $condition = " AND (s.rollNo = '".$param."' OR s.regNo = '".$param."' OR s.universityRollNo = '".$param."') ";
    $studentArray=$vehicleRouteAllocationManager->getStudentAllData($condition);
    if(count($studentArray) > 0) {
       $degreeId = $studentArray[0]['degreeId']; 
       $branchId = $studentArray[0]['branchId']; 
       $batchId = $studentArray[0]['batchId']; 
       
       $condition = " degreeId = '$degreeId' AND branchId = '$branchId'  AND batchId = '$batchId' AND isActive IN (1,2,3) ";
       $classArray=$vehicleRouteAllocationManager->getAllClass($condition);
       
       $condition = " AND s.studentId = '".$studentArray[0]['studentId']."'";
       $previousArray=$vehicleRouteAllocationManager->getVehicleRouteAllocations($condition);
       
       echo json_encode($studentArray).'!~!!~!'.json_encode($classArray).'!~!!~!'.json_encode($previousArray);
       die;
    }
    
    echo 0;
?>
