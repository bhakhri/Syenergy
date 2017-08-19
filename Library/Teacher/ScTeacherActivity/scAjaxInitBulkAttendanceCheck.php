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

    $bulkAttendanceRecordArray=$teacherManager->checkBulkAttendance($filter); 
    $cnt=count($bulkAttendanceRecordArray);
    if($cnt > 0 and is_array($bulkAttendanceRecordArray)){
        if($bulkAttendanceRecordArray[0]['totalRecords']>0){
            echo 1;   //record does  overlap
        }
       else{
           echo 0;   //record does not overlap
       } 
    }
   else {
     echo 0;    //record does not overlap
   } 
    
// for VSS
// $History: scAjaxInitBulkAttendanceCheck.php $
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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/19/08    Time: 10:33a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Created DailyAttendance Module
?>
