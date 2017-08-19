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
    define('MODULE','BulkAttendance');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    $filter="";

    $dailyAttendanceRecordArray=$teacherManager->checkDailyAttendance($filter); 
    $cnt=count($dailyAttendanceRecordArray);
    if($cnt > 0 and is_array($dailyAttendanceRecordArray)){
        echo json_encode($dailyAttendanceRecordArray);
    }
   else {
     echo 0;
   } 
    
// for VSS
// $History: ajaxInitDailyAttendanceCheck.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/18/08    Time: 11:17a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial checkin
?>
