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
	define('MODULE','AllocateAssignment');
	define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    $errorMessage='';
    if (!isset($REQUEST_DATA['assignmentId']) || trim($REQUEST_DATA['assignmentId']) == '') {
        $errorMessage = 'Invalid Assignment';
    }
    if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
		$teacherManager = TeacherManager::getInstance();
        $fileArray = $teacherManager->checkAssignmentExists(trim($REQUEST_DATA['assignmentId']));
 		//delete associated file with these file(IF EXISTS)
		if(UtilityManager::notEmpty($fileArray[0]['attachmentFile'])) {
			 if(file_exists(IMG_HTTP_PATH.'/TeacherAssignment/'.$fileArray[0]['attachmentFile'])) {
                @unlink(IMG_HTTP_PATH.'/TeacherAssignment/'.$fileArray[0]['attachmentFile']);
             }
        }
		if(SystemDatabaseManager::getInstance()->startTransaction()){
			$ret=$teacherManager->updateAttachmentFilenameInAssignment(trim($REQUEST_DATA['assignmentId']));
			if($ret==false){
				echo FAILURE;
				die;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo DELETE;
				die;
			}
		}
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

