<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
  

    if (!isset($REQUEST_DATA['userWishEventId']) || trim($REQUEST_DATA['userWishEventId']) == '') {
        $errorMessage = 'Invalid Event';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EventManager.inc.php");
        $eventManager =  EventManager::getInstance();
        $fileArray = $eventManager->checkEventExists(trim($REQUEST_DATA['userWishEventId']));
 		//delete associated file with these file(IF EXISTS)				
		if(UtilityManager::notEmpty($fileArray[0]['eventPhoto'])) {
			 if(file_exists(IMG_PATH.'/Event/'.$fileArray[0]['eventPhoto'])) {
                @unlink(IMG_PATH.'/Event/'.$fileArray[0]['eventPhoto']);
             }
        }
        $ret=$eventManager->updateAttachmentFilenameInNotice(trim($REQUEST_DATA['userWishEventId']),'');
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

