<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE StudyPeriod LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (2.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudyPeriodMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['studyPeriodId'] ) != '') {
    require_once(MODEL_PATH . "/StudyPeriodManager.inc.php");
    $foundArray = StudyPeriodManager::getInstance()->getStudyPeriod(' WHERE studyPeriodId="'.$REQUEST_DATA['studyPeriodId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}

// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudyPeriod
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:58a
//Updated in $/Leap/Source/Library/StudyPeriod
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/02/08    Time: 6:48p
//Updated in $/Leap/Source/Library/StudyPeriod
//Created "StudyPeriod Master"  Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/02/08    Time: 4:00p
//Created in $/Leap/Source/Library/StudyPeriod
//Initial Checkin
?>