<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo
//
// Author : Rajeev Aggarwal
// Created on : (11.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
global $sessionHandler;

//logError('Upload Error: Session ID missing.----'.$REQUEST_DATA['studentId'].'----');
  if(UtilityManager::notEmpty($REQUEST_DATA['studentId'])) {

   //logError("File Upload starts....");
    $fileObj = FileUploadManager::getInstance('studentPhoto');
    $filename = $REQUEST_DATA['studentId'].'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/Student/',$filename)) {
        //update logo image name in institute table
        require_once(MODEL_PATH . "/StudentManager.inc.php");
        StudentManager::getInstance()->updatePhotoFilenameInStudent($REQUEST_DATA['studentId'],$filename);
        // set null afer the image is uploaded
        $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
		$imgSrc=STUDENT_PHOTO_PATH."/".$filename;
        echo '<script language="javascript">parent.photoUpload("'.$imgSrc.'")</script>';
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
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Library/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/06/08    Time: 3:11p
//Updated in $/Leap/Source/Library/Student
//changed folder name to upload student photo
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/08    Time: 2:16p
//Updated in $/Leap/Source/Library/Student
//updated file upload function
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:44p
//Created in $/Leap/Source/Library/Student
//intial checkin
?>