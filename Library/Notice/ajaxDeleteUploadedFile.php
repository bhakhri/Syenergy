<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AddNotices');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    if (!isset($REQUEST_DATA['noticeId']) || trim($REQUEST_DATA['noticeId']) == '') {
        $errorMessage = 'Invalid Notice';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/NoticeManager.inc.php");
        $noticeManager =  NoticeManager::getInstance();
        $fileArray = $noticeManager->checkNoticeExists(trim($REQUEST_DATA['noticeId']));
 		//delete associated file with these file(IF EXISTS)				
		if(UtilityManager::notEmpty($fileArray[0]['attachmentFile'])) {
			 if(file_exists(IMG_PATH.'/Notice/'.$fileArray[0]['attachmentFile'])) {
                @unlink(IMG_PATH.'/Notice/'.$fileArray[0]['attachmentFile']);
             }
        }
        $ret=$noticeManager->updateAttachmentFilenameInNotice(trim($REQUEST_DATA['noticeId']),'');
        if($ret==false){
            echo FAILURE;
            die;
        }
		 echo DELETE;
         die;
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxDeleteUploadedFile.php $    
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/10    Time: 15:10
//Created in $/LeapCC/Library/Teacher/TeacherActivity
?>

