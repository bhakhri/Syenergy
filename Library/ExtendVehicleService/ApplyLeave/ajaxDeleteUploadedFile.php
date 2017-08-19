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
define('MODULE','COMMON');
define('ACCESS','delete');
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
 UtilityManager::ifNotLoggedIn();
}
else if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==5){
  UtilityManager::ifManagementNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();  
}




    if (!isset($REQUEST_DATA['leaveId']) || trim($REQUEST_DATA['leaveId']) == '') {
        $errorMessage = 'Invalid Notice';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php");
        $noticeManager =  ApplyLeaveManager::getInstance();
        $fileArray = $noticeManager->checkLeaveExists(trim($REQUEST_DATA['leaveId']));
 		//delete associated file with these file(IF EXISTS)				
		if(UtilityManager::notEmpty($fileArray[0]['attachmentFile'])) {
			 if(file_exists(IMG_PATH.'/Notice/'.$fileArray[0]['attachmentFile'])) {
                @unlink(IMG_PATH.'/EmployeeLeave/'.$fileArray[0]['attachmentFile']);
             }
        }
        $ret=$noticeManager->updateAttachmentFilenameInNotice(trim($REQUEST_DATA['leaveId']),'');
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

