<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FOR a specific student 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    
    $teacherManager = TeacherManager::getInstance();
    
    //get personal information of the student
    $studentDataArr=$teacherManager->getStudentInformation($_REQUEST['id']);
    
    //get course information of the student
    $studentCourseDataArr=$teacherManager->getStudentCourse($_REQUEST['id']);

// $History: initList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 13/07/09   Time: 11:59
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Added "Class" column in student display and corrected session changing
//problem
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:37p
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Corrected Student Tabs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/22/08    Time: 5:36p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/06/08    Time: 7:26p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:26p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:19p
//Created in $/Leap/Source/Library/Teacher/StudentActivity
//Initial Checkin
?>