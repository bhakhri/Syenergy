<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['forDate'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $dateArr=explode("-",$REQUEST_DATA['forDate']);
    $daysofWeek= date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
    if($daysofWeek==0){ $daysofWeek=7;} //we consider sunday as 7     
    $foundArray = TeacherManager::getInstance()->getTeacherPeriod(" AND daysOfWeek=".$daysofWeek." AND c.classId=".$REQUEST_DATA['classId']." AND t.subjectId=".$REQUEST_DATA['subjectId']." AND t.groupId=".$REQUEST_DATA['groupId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetPeriodNames.php $
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
//User: Dipanjan     Date: 8/29/08    Time: 3:19p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/05/08    Time: 3:01p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>