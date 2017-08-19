<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FOR a specific student 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
?>
<?php
    //Paging code goes here
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    
    $teacherManager = ScTeacherManager::getInstance();
    $commonAttendanceArr = CommonQueryManager::getInstance();
    
    //get personal information of the student
    $studentDataArr=$teacherManager->getStudentInformation($REQUEST_DATA['id']);
    
    //get course information of the student
    $studentCourseDataArr=$teacherManager->getStudentCourse($REQUEST_DATA['id']);
    
    //get theory group information of the student
    //$studentThDataArr=$teacherManager->getStudentThGroup($REQUEST_DATA['id']);
    
    //get practical group information of the student
    //$studentPrDataArr=$teacherManager->getStudentPrGroup($REQUEST_DATA['id']);
    
    /* function to fetch subjects details of <parameter> class Id*/
    //$studentSubjectArray = $teacherManager->getSubjectClass($studentDataArr[0]['classId']);
    
    //functions for showing student marks
    //$studentSubjectArray = $teacherManager->getStudentMarks($REQUEST_DATA['id']);
    
    
    //$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    //$records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    /* function to fetch section details of a student*/
    //$studentSectionArray = $teacherManager->getStudentSection($REQUEST_DATA['id'],$sessionHandler->getSessionVariable('rClasId'),' subjectName',$limit);
    
    /* function to fetch attendance details of a student*/   
    //$studentAttArray = $commonAttendanceArr->getScAttendance1($REQUEST_DATA['id'],'','');
    
    
    
    
    //functions for showing course wise resource list for the student
    //$resourceRecordArray = $teacherManager->getStudentCourseResourceList($REQUEST_DATA['id'],'', ' subject',$limit);
    
    //functions for showing total course wise resource list for the student
    //$resourceRecordTotalArray = $teacherManager->getTotalStudentCourseResource($REQUEST_DATA['id'],'');
    

// $History: scInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScStudentActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 11/12/08   Time: 11:58a
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added Fully Ajax Enabled Student Tabs in Teacher Module
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/11/08   Time: 10:43a
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added resource sorting and paging in student tab view
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 11/05/08   Time: 4:37p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added Resourc eDetails view and download capability in student tab
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 10/20/08   Time: 6:12p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//Mofified attendance and grades showing
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/20/08    Time: 7:13p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/17/08    Time: 2:23p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
?>