<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE class drop down[subject centric]
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);  
}
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['subjectId'] ) != '') {
    
    $employeeId=trim($REQUEST_DATA['employeeId']);
	
	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    if($roleId==2){
	  $foundArray = TeacherManager::getInstance()->getTeacherSubjectTopic($REQUEST_DATA['subjectId']);
    }
    else{
      $foundArray = TeacherManager::getInstance()->getTeacherSubjectTopic($REQUEST_DATA['subjectId'],$employeeId);
    }
	if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxAutoPopulateTopic.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Daily Attenance" module in admin end
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
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