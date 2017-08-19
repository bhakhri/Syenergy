<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload Notcie Attachment
//
// Author : Arvind Singh Rawat  
// Created on : (02.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

require_once(MODEL_PATH . "/NoticeManager.inc.php");  

define('MANAGEMENT_ACCESS',1);                                         
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);


  $errMsg=SUCCESS;

  global $sessionHandler;
  $operation=$sessionHandler->getSessionVariable('OperationMode');
  
  logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
    
        logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
        
        // Notice Attachment
        $fileObj = FileUploadManager::getInstance('noticeAttachment');
        $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'-notice.'.$fileObj->fileExtension;
        //$fileObj->allowedExtensions('doc','txt','rar');
        if($fileObj->upload(IMG_PATH.'/Notice/',$filename)) {
            //update logo image name in university table
            NoticeManager::getInstance()->updateAttachmentFilenameInNotice($sessionHandler->getSessionVariable('IdToFileUpload'),$filename); 
            // set null afer the image is uploaded
            logError($fileObj->message);
        }
        else {
           logError($fileObj->message);
           //we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
           //more than what "Apache" permits and so our logic will fail.
           if(trim($sessionHandler->getSessionVariable('HiddenFile'))!=''){   
              //$errMsg =FILE_NOT_UPLOAD;
              $errMsg="Maximum upload size is ".(MAXIMUM_FILE_SIZE/1024).'KB';
              //logError("ajinder:=====deleting record");
              if($operation=='1') {
                NoticeManager::getInstance()->deleteNoticeFailedUpload($sessionHandler->getSessionVariable('IdToFileUpload'));
              }
           }
        }
  }
  else {
     $fileUploadFlag=0;  
     logError('Upload Error: Session ID missing.'); 
  }
  if($operation=='') {
      $operation=0;
  }
  echo '<script language="javascript"> parent.globalFL=1; </script>';
  echo '<script language="javascript"> parent.fileUploadError("'.$errMsg.'",'.$operation.');</script>';
      
      $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
      $sessionHandler->setSessionVariable('OperationMode',NULL); 
      $sessionHandler->setSessionVariable('HiddenFile',NULL);
  die;   
  
// $History: fileUpload.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/08/09   Time: 11:02a
//Updated in $/LeapCC/Library/Notice
//function addNotice set lastInsertId
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/02/09    Time: 2:14p
//Updated in $/LeapCC/Library/Notice
//file attachment validation format updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Notice
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/Notice
//Define Module, Access  Added
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/Notice
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/03/08   Time: 1:11p
//Created in $/Leap/Source/Library/Notice
//intial checkin
?>