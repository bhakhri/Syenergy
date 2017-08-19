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
	define('MODULE','RoomAllocation');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
    $roomManager = RoomAllocationManager::getInstance();

    if(trim($REQUEST_DATA['param'])!=''){
        $foundArray=$roomManager->getStudentData(' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['param'])).'" OR regNo="'.add_slashes(trim($REQUEST_DATA['param'])).'"');
        if(is_array($foundArray) && count($foundArray)>0 ) {
            //if($foundArray[0]['hostelFacility']==1){ //if hostel facility is ON for this student
             echo $foundArray[0]['studentId'].'~'.$foundArray[0]['studentName'].'~'.$foundArray[0]['className'];
            //}
            //else{
            //    echo STUDENT_WITH_NO_HOSTEL_FACILITY;
            //    die;
            //}
        }
       else{
           echo 0;
       } 
    }
    else{
        echo 0;
    }
// for VSS
// $History: ajaxGetStudentData.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/08/09   Time: 13:34
//Created in $/LeapCC/Library/Room
//Added files for "Room Allocation Master"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/04/09   Time: 17:57
//Created in $/Leap/Source/Library/Room
//Created "Room Allocation Master"
?>