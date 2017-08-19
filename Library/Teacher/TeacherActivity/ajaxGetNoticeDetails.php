<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['noticeId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $foundArray = TeacherManager::getInstance()->getNoticeDetail($REQUEST_DATA['noticeId']);
	
	$cnt = count($foundArray);
	for($i=0;$i<$cnt;$i++){
		$foundArray[$i][noticeSubject] = trim($foundArray[$i][noticeSubject]);
		$foundArray[$i][noticeText] = html_entity_decode($foundArray[$i][noticeText]);
	}
	//print_r($foundArray);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetNoticeDetails.php $
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
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/16/08    Time: 4:10p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>