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
	define('MODULE','VehicleRouteAllocation');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();

    if(trim($REQUEST_DATA['param'])!=''){
        $foundArray=$VehicleRouteAllocationManager->getStudentData(' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['param'])).'" OR regNo="'.add_slashes(trim($REQUEST_DATA['param'])).'"');
        if(is_array($foundArray) && count($foundArray)>0 ) {
             echo $foundArray[0]['studentId'].'~'.$foundArray[0]['studentName'].'~'.$foundArray[0]['className'];
        }
       else{
           echo 0;
       } 
    }
    else{
        echo 0;
    }

?>
