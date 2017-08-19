<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo
//
// Author : Parveen Sharma
// Created on : 16.12.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

UtilityManager::ifParentNotLoggedIn();
global $sessionHandler;
 
//logError('Upload Error: Session ID missing.----'.$sessionHandler->getSessionVariable('StudentId').'----'); 
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('StudentId'))) {
  
   //logError("File Upload starts....");
    $fileObj = FileUploadManager::getInstance('studentPhoto');
    $filename =  $sessionHandler->getSessionVariable('StudentId').'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/Student/',$filename)) {
        //update logo image name in institute table
        require_once(MODEL_PATH . "/StudentManager.inc.php");
        StudentManager::getInstance()->updatePhotoFilenameInStudent($sessionHandler->getSessionVariable('StudentId'),$filename);
        // set null afer the image is uploaded
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

// $History: fileUpload.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/23/08   Time: 1:55p
//Updated in $/LeapCC/Library/Parent
//file updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/16/08   Time: 10:27a
//Created in $/LeapCC/Library/Parent
//intial checkin
//


?>