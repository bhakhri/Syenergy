<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload message Attachment
//
// Author : Parveen Sharma 
// Created on : (04.02.2009 )
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  require_once(BL_PATH . "/FileUploadManager.inc.php");
  require_once(MODEL_PATH . "/Parent/ParentMessageManager.inc.php");

  define('MODULE','COMMON');
  define('ACCESS','edit');
  UtilityManager::ifParentNotLoggedIn(true);       
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
  
  $errMsg=MSG_SENT_OK;
  
  
  logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
    logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
	$fileObj = FileUploadManager::getInstance('noticeAttachment');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'-M.'.$fileObj->fileExtension;
    //$fileObj->allowedExtensions('doc','txt','rar');
    if($fileObj->upload(IMG_PATH.'/StudentMessage/',$filename)) {
        //update logo image name in university table
        ParentTeacherManager::getInstance()->updateAttachmentFilenameInMessage($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
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
  
//  }
//  else {
//      logError('Upload Error: Session ID missing.'); 
//  }
  
// $History: messageFileUpload.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 4/09/10    Time: 4:35p
//Updated in $/Leap/Source/Library/ScParent
//role permission and validation updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/21/09    Time: 6:32p
//Updated in $/Leap/Source/Library/ScParent
//fileUpload function condition updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/Leap/Source/Library/ScParent
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/19/09    Time: 6:55p
//Updated in $/Leap/Source/Library/ScParent
//formating & validation updated
//1132, 1130, 54, 1045, 1044, 500, 1042, 1043 issue resolve
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/18/09    Time: 1:23p
//Updated in $/Leap/Source/Library/ScParent
//formating & alingments
//bug fix 1097, 1096, 1056, 1049, 1048,
//1043, 1008 1042, 506  
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/17/09    Time: 7:02p
//Updated in $/Leap/Source/Library/ScParent
//bug fix  (file attachement & format updated)
//1041, 1097, 1040, 1041, 1105, 1106, 1109 
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/07/09    Time: 5:10p
//Updated in $/Leap/Source/Library/ScParent
//fixed bug no. 0000478
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 4:27p
//Created in $/Leap/Source/Library/ScParent
//initial checkin 
//

?>