<?php
//-------------------------------------------------------
// Purpose: To get noticeStatus
//
// Author : Nancy Puri
// Created on : 17-Nov-2010
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');     
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$noticeId =  $REQUEST_DATA['noticeId'];

    if (!isset($noticeId) || trim($noticeId) == '') {
        
        $errorMessage = 'Invalid Notice';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/NoticeManager.inc.php");
        $noticeManager =  NoticeManager::getInstance();
        $noticeStatusArray = $noticeManager->getNoticeStatus($noticeId);
        $noticeStatus = $noticeStatusArray[0]['noticeStatus'];
        echo $noticeStatus;
    }  
   else {
        echo $errorMessage;
    }
   
?>

