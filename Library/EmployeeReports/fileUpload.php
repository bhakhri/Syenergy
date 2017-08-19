<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload publishing files
//
// Author : Parveen Sharma
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
require_once(MODEL_PATH . "/EmployeeManager.inc.php");   

define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);

  $errMsg = $sessionHandler->getSessionVariable('ErrorMsg');
  if($errMsg=='') {
    $errMsg = ACCESS_DENIED;  
  }

  global $sessionHandler;
  $operation=$sessionHandler->getSessionVariable('OperationMode');
  
  logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
    
        logError("File Upload starts....".$sessionHandler->getSessionVariable('IdToFileUpload'));
        
        // Publisher Attachment
        $fileObj = FileUploadManager::getInstance('publisherAttachment');
        $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'-pub.'.$fileObj->fileExtension;
        //$fileObj->allowedExtensions('doc','txt','rar');
        if($fileObj->upload(IMG_PATH.'/Teacher/Publishing/',$filename)) {
            //update logo image name in university table
            EmployeeManager::getInstance()->updateFilenameInPublisher($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
            // set null afer the image is uploaded
            logError($fileObj->message);
        }
        else {
          logError($fileObj->message);
          //we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
          //more than what "Apache" permits and so our logic will fail.
          if(trim($sessionHandler->getSessionVariable('HiddenFile1'))!=''){   
              $errMsg =FILE_NOT_UPLOAD;
              //logError("ajinder:=====deleting record");
              if($operation=='1') {
                EmployeeManager::getInstance()->deletePublisherFailedUpload($sessionHandler->getSessionVariable('IdToFileUpload'));
              }
          }
        }
        
        
        // Publisher Accp. Letter
        $fileObj = FileUploadManager::getInstance('publisherAccpLet');
        $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'-accp.'.$fileObj->fileExtension;
        //$fileObj->allowedExtensions('doc','txt','rar');
        if($fileObj->upload(IMG_PATH.'/Teacher/Publishing/',$filename)) {
            //update logo image name in university table
            EmployeeManager::getInstance()->updateAccpLetFilenameInPublisher($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
            // set null afer the image is uploaded
            logError($fileObj->message);
        }
        else {
          logError($fileObj->message);
          //we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
          //more than what "Apache" permits and so our logic will fail.
          if(trim($sessionHandler->getSessionVariable('HiddenFile2'))!=''){   
              $errMsg =FILE_NOT_UPLOAD;
              //logError("ajinder:=====deleting record");
              if($operation=='1') {
                EmployeeManager::getInstance()->deletePublisherFailedUpload($sessionHandler->getSessionVariable('IdToFileUpload'));
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
  $sessionHandler->setSessionVariable('HiddenFile1',NULL);
  $sessionHandler->setSessionVariable('HiddenFile2',NULL);
  die;  


/* if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
    //logError("File Upload starts....");
    
    $fileObj = FileUploadManager::getInstance('publisherAttachment');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/Teacher/Publishing/',$filename)) {
        //update logo image name in institute table

        EmployeeManager::getInstance()->updateFilenameInPublisher($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the image is uploaded
        $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
    else {
        logError($fileObj->message); 
    }
    
    $fileObj = FileUploadManager::getInstance('publisherAccpLet');
    $filename = "accp".$sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/Teacher/Publishing/',$filename)) {
        //update logo image name in institute table
        EmployeeManager::getInstance()->updateAccpLetFilenameInPublisher($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
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
*/

// $History: fileUpload.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/04/09    Time: 11:16a
//Updated in $/LeapCC/Library/EmployeeReports
//publisher file attchment & publisher save message updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/31/09    Time: 3:15p
//Updated in $/LeapCC/Library/EmployeeReports
//file upload coding updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/21/09    Time: 12:41p
//Updated in $/LeapCC/Library/EmployeeReports
//new enhancement added "attachmentAcceptationLetter" in Employee
//Publisher tab 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/16/09    Time: 5:13p
//Updated in $/LeapCC/Library/EmployeeReports
//new enhancements added (publisher file uploads)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/16/09    Time: 12:18p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Institute
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:31p
//Updated in $/Leap/Source/Library/Institute
//Added access rules
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:44p
//Updated in $/Leap/Source/Library/Institute
//Tested the code and refined it
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/16/08    Time: 7:22p
//Updated in $/Leap/Source/Library/Institute
//just logerror added for testing, it will be removed later
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/16/08    Time: 11:40a
//Created in $/Leap/Source/Templates/Institute
//initial checkin
//
?>