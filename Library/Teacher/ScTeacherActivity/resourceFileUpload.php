<?php
//--------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO upload files when a teacher uploads resources corresponding to a course
//
// Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

UtilityManager::ifTeacherNotLoggedIn();

  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
  
    $fileObj = FileUploadManager::getInstance('resourceFile');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->name;
    if($fileObj->upload(IMG_PATH.'/CourseResource/',$filename)) {
        //update file name in  course_resource table
        require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
        ScTeacherManager::getInstance()->updateCourseResourceFile($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the file is uploaded
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
// $History: resourceFileUpload.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:47p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>