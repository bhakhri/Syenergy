<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload message Attachment
//
// Author : Rajeev Aggarwal  
// Created on : (28.01.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  require_once(BL_PATH . "/FileUploadManager.inc.php");
  require_once(MODEL_PATH . "/AdminMessageManager.inc.php");   
  $adminMessageManager = AdminMessageManager::getInstance();
  define('MANAGEMENT_ACCESS',1); 
  define('MODULE','COMMON');
  define('ACCESS','edit');
  if($sessionHandler->getSessionVariable('RoleId')==2) {     
    UtilityManager::ifTeacherNotLoggedIn(true);
  }
  else {
    UtilityManager::ifNotLoggedIn(true);   
  }
  UtilityManager::headerNoCache();
  
  global $sessionHandler;
  $operation=$sessionHandler->getSessionVariable('OperationMode');
  
  
  if($sessionHandler->getSessionVariable('SuperUserId')!=''){
    if($operation==''){
      $operation=0;
    }
    echo '<script language="javascript"> 
            parent.globalFL=1; 
            parent.fileUploadError("'.ACCESS_DENIED.'",'.$operation.');
          </script>';
    $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
    $sessionHandler->setSessionVariable('OperationMode',NULL); 
    $sessionHandler->setSessionVariable('HiddenFile',NULL);  
    die;
  }
  
  //check of max. file size
  if($_FILES['noticeAttachment']['name']!='' and $_FILES['noticeAttachment']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",'.$operation.')</script>';
     die;
  }
  
  $errMsg=MSG_SENT_OK;
  
  logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
    logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
    $fileObj = FileUploadManager::getInstance('noticeAttachment');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'-M.'.$fileObj->fileExtension;
    //$fileObj->allowedExtensions('doc','txt','rar');
    if($fileObj->upload(IMG_PATH.'/StudentMessage/',$filename)) {
        //update logo image name in university table
        $adminMessageManager->updateAttachmentFilenameInMessage($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the image is uploaded
        logError($fileObj->message);
    }
    else {
        logError($fileObj->message);
        //we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
        //more than what "Apache" permits and so our logic will fail.
        if(trim($sessionHandler->getSessionVariable('HiddenFile'))!=''){   
          $errMsg =FILE_NOT_UPLOAD;
          //logError("ajinder:=====deleting record");
          ParentTeacherManager::getInstance()->deleteTeacherAttachementOnFailedUpload($sessionHandler->getSessionVariable('IdToFileUpload'));
        }

    }
  }
  else {
     $fileUploadFlag=0;  
     logError('Upload Error: Session ID missing.'); 
  }
  if($operation==''){
      $operation=0;
  }
  
  echo '<script language="javascript"> 
           parent.globalFL=1; 
           parent.fileUploadError("'.$errMsg.'",'.$operation.');
        </script>';
  $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
  $sessionHandler->setSessionVariable('OperationMode',NULL); 
  $sessionHandler->setSessionVariable('HiddenFile',NULL);
  
  die;  
// $History: messageFileUpload.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-01   Time: 1:15p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated with Session check
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 2/09/09    Time: 5:40p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Fixed bugs and alignment
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:25p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Intial checkin
?>