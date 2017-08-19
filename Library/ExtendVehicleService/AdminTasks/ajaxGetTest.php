<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE test List
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestMarks');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['testTypeId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '' and trim($REQUEST_DATA['groupId'] ) != '' and trim($REQUEST_DATA['subjectId'] ) != '' and trim($REQUEST_DATA['employeeId'] ) != '') {
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $foundArray = AdminTasksManager::getInstance()->getTest($REQUEST_DATA['testTypeId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetTest.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/01/10    Time: 12:56
//Updated in $/LeapCC/Library/AdminTasks
//Corrected Query Error
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 11:18
//Created in $/LeapCC/Library/AdminTasks
//Created "Test Marks" module in admin section
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/24/08    Time: 11:58a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>