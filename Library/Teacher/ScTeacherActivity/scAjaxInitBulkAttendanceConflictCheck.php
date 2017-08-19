<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To check whether the data range in bulk attendance overlaps data from daily attendance
// Author : Dipanjan Bbhattacharjee
// Created on : (17.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

    $filter="";

    $bulkAttendanceRecordConflictArray=$teacherManager->checkBulkAttendanceConflict($filter); 
    $cnt=count($bulkAttendanceRecordConflictArray);
    if($cnt > 0 and is_array($bulkAttendanceRecordConflictArray)){
        echo json_encode($bulkAttendanceRecordConflictArray[0]);
    }
   else {
     echo 0;
   } 
    
// for VSS
// $History: scAjaxInitBulkAttendanceConflictCheck.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/18/08    Time: 3:29p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>