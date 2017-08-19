<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATIONS REGARDING ATTENDANCE  
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (15.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (sub.subjectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    } 
    */
    $filter="";
    
    $totalArray = $teacherManager->getTotalStudent($filter);
    $studentRecordArray = $teacherManager->getStudentList($filter,$limit);  
    $attendanceRecordArray=$teacherManager->getBulkAttendanceList($filter,$limit); 
    
// $History: scInitBulkAttendanceList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
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
//User: Dipanjan     Date: 7/17/08    Time: 5:20p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//ifTeacherNotLoggedIn
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/16/08    Time: 7:13p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>