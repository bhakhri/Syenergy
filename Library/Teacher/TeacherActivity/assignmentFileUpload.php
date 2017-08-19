<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload message Attachment
//
// Author : Rajeev Aggarwal
// Created on : (28.01.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
//define('MANAGEMENT_ACCESS',1);
//define('MODULE','AddNotices');
//define('ACCESS','add');
/*global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
	UtilityManager::ifNotLoggedIn(true);
}*/

  global $sessionHandler;
	//logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {

   //logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
    $fileObj = FileUploadManager::getInstance('msgLogo');
	$extensionArray = explode('.',$fileObj->name);
	$ext = "";
	if(count($extensionArray)>0) { 
	  $ext = ".".trim($extensionArray[count($extensionArray)-1]);
    } 
	
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'_'.time().$ext;
    //$fileObj->allowedExtensions('doc','txt','rar');
    if($fileObj->upload(IMG_PATH.'/TeacherAssignment/',$filename)) {
        //update logo image name in university table
        require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
        TeacherManager::getInstance()->updateTeacherAssignmentFile($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the file is uploaded
        $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
    else {
        logError($fileObj->message);
    }
  }
  else {
      logError('Upload Error: Session ID missing.');
  }
// $History: assignmentFileUpload.php $
?>