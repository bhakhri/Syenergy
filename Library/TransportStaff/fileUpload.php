<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload employee image
// Author : Jaineesh
// Created on : (31.08.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
//require_once(BL_PATH.'/HtmlFunctions.inc.php');
require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
define('MODULE','TransportStaffMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);

$errMsg = SUCCESS;
$fileUploadFlag=0;
$staffId=$sessionHandler->getSessionVariable('IdToFileUpload');
$userId=$sessionHandler->getSessionVariable('IdToFileUpload1');
$mode = $sessionHandler->getSessionVariable('OPERATION_MODE');
//echo($sessionHandler->getSessionVariable('DUPLICATE_USER'));
$transportStaffManager = TransportStaffManager::getInstance();

if ($sessionHandler->getSessionVariable('DUPLICATE_USER') != '') {
	echo '<script language="javascript">parent.fileUploadError("'.$sessionHandler->getSessionVariable('DUPLICATE_USER').'",'.$mode.');</script>';
	die;
}

$filename='';
$tempExtenision = $allowedExtensionsArray;
$allowedExtensionsArray = array('gif','jpg','jpeg','png','bmp');

  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
	  //$userArr = $employeeManager->getUser($REQUEST_DATA['employeeId']);
      //$resultId = $userArr[0]['userId'];

    $fileObj = FileUploadManager::getInstance('staffPhoto');
	$fileObj1 = FileUploadManager::getInstance('drivingLicencePhoto');

	$filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->fileExtension;
	$filename1 = 'DL_'.$sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj1->fileExtension;
	if($fileObj->fileExtension != '') {
	if(!in_array($fileObj->fileExtension,$allowedExtensionsArray)) {
		$errMsg = UPLOAD_IMAGE;
		echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
		//echo "Upload only image";
		if ($mode == 1) {
			$transportStaffManager->deleteTransportStaff($staffId);
		 }
		 die;
		}
	}
	
	if($fileObj1->fileExtension != '') {
	if(!in_array($fileObj1->fileExtension,$allowedExtensionsArray)) {
		$errMsg = UPLOAD_IMAGE;
		echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
		//echo "Upload only image";
		if ($mode == 1) {
			$transportStaffManager->deleteTransportStaff($staffId);
		 }
		 die;
		}
	}

    if($fileObj->upload(IMG_PATH.'/TransportStaff/',$filename)) {
        $transportStaffManager->updateStaffImage($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        $fileUploadFlag=1;
        //$sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
	else {
		logError($fileObj->message);
        $fileUploadFlag=0;
		//we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
        //more than what "Apache" permits and so our logic will fail.
        if(trim($sessionHandler->getSessionVariable('HiddenFile'))!=''){
          $errMsg =FILE_NOT_UPLOAD;
		  echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
          //logError("ajinder:=====deleting record");
		  if ($mode == 1) {
			$transportStaffManager->deleteTransportStaff($staffId);
		 }
		 die;
        }
	}

	if($fileObj1->upload(IMG_PATH.'/TransportStaff/DLImage/',$filename1)) {
        $transportStaffManager->updateDLImage($sessionHandler->getSessionVariable('IdToFileUpload'),$filename1);
        $fileUploadFlag=1;
        //$sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
	else {
		logError($fileObj->message);
        $fileUploadFlag=0;
		//we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
        //more than what "Apache" permits and so our logic will fail.
        if(trim($sessionHandler->getSessionVariable('HiddenDLFile'))!=''){
          $errMsg =FILE_NOT_UPLOAD;
		  echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
          //logError("ajinder:=====deleting record");
		  if ($mode == 1) {
			$transportStaffManager->deleteTransportStaff($staffId);
		 }
		 die;
        }
	}
  }
  else {
      logError('Upload Error: Session ID missing.');
      $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
      $fileUploadFlag=0;
  }
  echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
  $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
  $sessionHandler->setSessionVariable('IdToFileUpload1',NULL);
  $sessionHandler->setSessionVariable('OPERATION_MODE',NULL);
  $allowedExtensionsArray = $tempExtenision;

  //send email to all the concerned people

// $History: fileUpload.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Library/TransportStaff
//put DL image in transport staff and changes in modules
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/10/09   Time: 3:52p
//Created in $/Leap/Source/Library/TransportStaff
//new files for image delete & upload
//
//
?>