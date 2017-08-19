<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload Event Attachment
//  Raghav Salotra
//  Date:- 22/09/2011
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

require_once(MODEL_PATH . "/EventManager.inc.php");  
require_once(MODEL_PATH . "/CropImagesManager.inc.php");    
    $cropImagesManager = CropImagesManager::getInstance();

define('MANAGEMENT_ACCESS',1);                                         
define('MODULE','EventMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);


  $errMsg=SUCCESS;

  global $sessionHandler;
  $operation=$sessionHandler->getSessionVariable('OperationMode');
  
  logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
        logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload')); 
        // Event Picture
        $fileObj = FileUploadManager::getInstance('eventPicture');

	$filename = $sessionHandler->getSessionVariable('IdToFileUpload').'-event.'.$fileObj->fileExtension;
        // if(!is_writable(IMG_PATH.'/Event/') ) {
         //      @chmod(IMG_PATH.'/Event/',777);
         //   }
        if($filename!='') {
              logError("File Upload starts....   File Name: ".$filename);     
            //  if(file_exists(IMG_PATH.'/Event/'.$filename)) {
            //    @unlink(IMG_PATH.'/Event/'.$filename);
             // }  
        } 
        $tmp_name = $fileObj->tmp; 
	$cropImagesManager->load($tmp_name);
        $cropImagesManager->resize(250,240);
      if($fileObj->fileExtension!=''){
       
	if($cropImagesManager->save(IMG_PATH.'/Event/'.$filename)){ 
         
            EventManager::getInstance()->updateAttachmentFilenameInEvent($sessionHandler->getSessionVariable('IdToFileUpload'),$filename); 
               logError("file is uploaded");
        
            // set null afer the image is uploaded
            logError($fileObj->message);
        }
        else {
           logError("file could not be uploaded");
           logError($fileObj->message);
           //we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
           //more than what "Apache" permits and so our logic will fail.
           if(trim($sessionHandler->getSessionVariable('HiddenFile'))!=''){   
              //$errMsg =FILE_NOT_UPLOAD;
              $errMsg="Maximum upload size is ".(MAXIMUM_FILE_SIZE/1024).'KB';
              //logError("ajinder:=====deleting record");
              if($operation=='1') {
                EventManager::getInstance()->deleteEventFailedUpload($sessionHandler->getSessionVariable('IdToFileUpload'));
              }
           }
        }}
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


 

  
?>
