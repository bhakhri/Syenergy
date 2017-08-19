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
    define('MODULE','DailyAttendance');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
     UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

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
// $History: ajaxInitBulkAttendanceCheck.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Daily Attenance" module in admin end
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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/19/08    Time: 10:33a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Created DailyAttendance Module
?>
