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
require_once(MODEL_PATH . "/EmployeeManager.inc.php");
define('MODULE','EmployeeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);

logError("ajinder in iframe file");


$errMsg = SUCCESS;
$fileUploadFlag=0;
$employeeCanTeachId=$sessionHandler->getSessionVariable('IdToFileUpload');
$userId=$sessionHandler->getSessionVariable('IdToFileUpload1');
$mode = $sessionHandler->getSessionVariable('OPERATION_MODE');
//echo($sessionHandler->getSessionVariable('DUPLICATE_USER'));

if ($sessionHandler->getSessionVariable('DUPLICATE_USER') != '') {
	echo '<script language="javascript">parent.fileUploadError("'.$sessionHandler->getSessionVariable('DUPLICATE_USER').'",'.$mode.');</script>';
	die;
}

$filename='';
$employeeManager =  EmployeeManager::getInstance();
$tempExtenision = $allowedExtensionsArray;
$allowedExtensionsArray = array('gif','jpg','jpeg','png','bmp');

  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
	  //$userArr = $employeeManager->getUser($REQUEST_DATA['employeeId']);
      //$resultId = $userArr[0]['userId'];

    $fileObj = FileUploadManager::getInstance('employeePhoto');
	$fileObj1 = FileUploadManager::getInstance('thumbImage');

	$filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->fileExtension;
	$filename1 = 'TH_'.$sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj1->fileExtension;
	//echo($filename);
	if($fileObj->fileExtension != '') {
	if(!in_array($fileObj->fileExtension,$allowedExtensionsArray)) {
		$errMsg = UPLOAD_IMAGE;
		echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
		//echo "Upload only image";
		if ($mode == 1) {
			if($employeeCanTeachId != '') {
				$employeeManager->deleteEmployee($employeeCanTeachId);
			}
			if($userId != '') {
				$employeeManager->deleteUser($userId);
			}
		 }
		 die;
	  }
	}

    if($fileObj->upload(IMG_PATH.'/Employee/',$filename)) {
        EmployeeManager::getInstance()->updateEmployeeImage($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
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
			if($employeeCanTeachId != '') {
				$employeeManager->deleteEmployee($employeeCanTeachId);
			}
			if($userId != '') {
				$employeeManager->deleteUser($userId);
			}
		 }
		 die;
      }
	}

	if($fileObj1->upload(IMG_PATH.'/Employee/ThumbImage/',$filename1)) {
        $employeeManager->updateThumbImage($sessionHandler->getSessionVariable('IdToFileUpload'),$filename1);
        $fileUploadFlag=1;
        //$sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
	else {
		logError($fileObj->message);
        $fileUploadFlag=0;
		//we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
        //more than what "Apache" permits and so our logic will fail.
        if(trim($sessionHandler->getSessionVariable('HiddenThumbFile'))!=''){
          $errMsg =FILE_NOT_UPLOAD;
		  echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
          //logError("ajinder:=====deleting record");
		  if ($mode == 1) {
			if($employeeCanTeachId != '') {
				$employeeManager->deleteEmployee($employeeCanTeachId);
			}
			if($userId != '') {
				$employeeManager->deleteUser($userId);
			}
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
//*****************  Version 7  *****************
//User: Jaineesh     Date: 4/06/10    Time: 7:27p
//Updated in $/LeapCC/Library/Employee
//issue resolved No. 0003219
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Library/Employee
//changes for gap analysis in employee master
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 12:44p
//Updated in $/LeapCC/Library/Employee
//updated access defines
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/01/09    Time: 2:08p
//Updated in $/LeapCC/Library/Employee
//Modification in code while saving & edit record in IE browser.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/31/09    Time: 4:04p
//Created in $/LeapCC/Library/Employee
//new files for photo upload & delete
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/21/09    Time: 7:04p
//Created in $/SnS/Library/Employee
//add new files for file upload and delete image
//

?>