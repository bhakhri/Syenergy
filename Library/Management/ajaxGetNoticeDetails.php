<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div
//
// Author : Rajeev Aggarwal
// Created on : (12.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
UtilityManager::headerNoCache();
 
if(trim($REQUEST_DATA['noticeId'] ) != '') {
    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $foundArray = DashBoardManager::getInstance()->getNoticeDetail($REQUEST_DATA['noticeId']);
	$cnt = count($foundArray);
	for($i=0;$i<$cnt;$i++){
		$foundArray[$i][noticeSubject] = trim($foundArray[$i][noticeSubject]);
		$foundArray[$i][noticeText] = html_entity_decode($foundArray[$i][noticeText]);
	}
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetNoticeDetails.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>