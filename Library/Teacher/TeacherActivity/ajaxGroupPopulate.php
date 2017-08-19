<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE class drop down[subject centric]
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	  UtilityManager::ifTeacherNotLoggedIn(true); //for teachers
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['subjectId'])!= '' and trim($REQUEST_DATA['classId'])!= '') {

	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
	$teacherManager = TeacherManager::getInstance();
    $timeTableLabelTypeConditions='';
    $date=date('Y-m-d');
    if($sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==DAILY_TIMETABLE){
      $timeTableLabelTypeConditions=' AND t.fromDate <="'.$date.'"';
    }

	$foundArray = $teacherManager->getSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'],'',$timeTableLabelTypeConditions);

	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGroupPopulate.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in Teacher module for DAILY_TIMETABLE issues
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/04/09    Time: 16:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added class check during group populate
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/06/09    Time: 1:03p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//modified for daily & bulk attendance
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/30/09    Time: 3:46p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//updated code to show only tut groups when the teacher is taking tut
//groups else show theory groups.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/23/09    Time: 11:50a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/12/09    Time: 11:49a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//modified the files for topics taught
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/10/09    Time: 4:17p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/01/09   Time: 14:57
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Added the functionality:
//Teacher can select topics covered and enter his/her comments
//when taking attendance.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:36p
//Created in $/Leap/Source/Library/Teacher
?>