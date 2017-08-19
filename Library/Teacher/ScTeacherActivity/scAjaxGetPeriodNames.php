<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['forDate'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    
    $dateArr=explode("-",$REQUEST_DATA['forDate']);
    $daysofWeek= date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
    if($daysofWeek==0){ $daysofWeek=7;} //we consider sunday as 7     
    
    $foundArray = ScTeacherManager::getInstance()->getTeacherPeriod(" AND daysOfWeek=".$daysofWeek." AND t.subjectId=".$REQUEST_DATA['subjectId']." AND t.sectionId=".$REQUEST_DATA['sectionId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: scAjaxGetPeriodNames.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>