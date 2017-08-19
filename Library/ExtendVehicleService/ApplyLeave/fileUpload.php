<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload document Attachment
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
$operation=$sessionHandler->getSessionVariable('OperationMode');   
define('MODULE','COMMON');
define('ACCESS','add');
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



  require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php");
  $applyLeaveManager = ApplyLeaveManager::getInstance();

  $errMsg=SUCCESS;

  
  
  logError("File Upload starts....".$sessionHandler->getSessionVariable('IdLeaveToFileUpload'));
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdLeaveToFileUpload'))) {
        logError("File Upload starts....".$sessionHandler->getSessionVariable('IdLeaveToFileUpload'));

        $fileObj = FileUploadManager::getInstance('documentAttachment');
        
            $filename = $sessionHandler->getSessionVariable('IdLeaveToFileUpload').'-leave.'.$fileObj->fileExtension;
            //$fileObj->allowedExtensions('doc','txt','rar');
            if($fileObj->upload(IMG_PATH.'/EmployeeLeave/',$filename)) {
                //update logo image name in university table
                $applyLeaveManager->updateAttachmentFilenameInLeave($sessionHandler->getSessionVariable('IdLeaveToFileUpload'),$filename); 
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
                    $applyLeaveManager->deleteLeaveFailedUpload($sessionHandler->getSessionVariable('IdLeaveToFileUpload'));
                  }
               }
            }
  }
  else {
     $fileUploadFlag=0;  
     logError('Upload Error: Session ID missing.'); 
  }
 
  $sessionHandler->setSessionVariable('IdLeaveToFileUpload',NULL);
  $sessionHandler->setSessionVariable('OperationMode',NULL); 
  $sessionHandler->setSessionVariable('HiddenFile',NULL);
   
  if($operation=='') {
     $operation=0;
  }
  echo '<script language="javascript"> parent.globalFL=1; </script>';
  echo '<script language="javascript"> parent.fileUploadError("'.$errMsg.'",'.$operation.');</script>';
  die;   
  
?>
