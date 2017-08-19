<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices
//
// Author : Dipanjan Bbhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    

    $filter=" AND att.attendanceType=1 ";    
    $bulkAttLastTakenRecordArray = $teacherManager->checkAttendanceNotTaken($filter);
    //print_r($bulkAttLastTakenRecordArray);
// $History: initBulkAttendanceLastTaken.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/25/08    Time: 6:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>