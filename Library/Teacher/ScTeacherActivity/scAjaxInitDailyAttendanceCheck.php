<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To check whether the data range in bulk attendance overlaps data from daily attendance
// Author : Dipanjan Bbhattacharjee
// Created on : (17.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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

    $dailyAttendanceRecordArray=$teacherManager->checkDailyAttendance($filter); 
    $cnt=count($dailyAttendanceRecordArray);
    if($cnt > 0 and is_array($dailyAttendanceRecordArray)){
        echo json_encode($dailyAttendanceRecordArray);
    }
   else {
     echo 0;
   } 
    
// for VSS
// $History: scAjaxInitDailyAttendanceCheck.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/18/08    Time: 11:17a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial checkin
?>
