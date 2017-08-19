<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Arvind Singh Rawat
// Created on : (16.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['subjectId'] ) != '' && trim($REQUEST_DATA['testTypeId'] ) != '' && trim($REQUEST_DATA['testIndex'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $foundArray = ScTeacherManager::getInstance()->getTestMaxMarks(" WHERE subjectId=".$REQUEST_DATA['subjectId']." AND testTypeId=".$REQUEST_DATA['testTypeId']." AND testIndex=".$REQUEST_DATA['testIndex']." ");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: scAjaxGetMaxMarks.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/16/08   Time: 3:01p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>