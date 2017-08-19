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
require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);

  $errMsg=SUCCESS;
  $mode=$sessionHandler->getSessionVariable('OperationMode');
  
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
  
    $fileObj = FileUploadManager::getInstance('resourceFile');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->name;
    if(@$fileObj->upload(IMG_PATH.'/CourseResource/',$filename)) {
        //update file name in  course_resource table
        TeacherManager::getInstance()->updateCourseResourceFile($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the file is uploaded
        $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
    else {
        logError($fileObj->message);
        if(trim($sessionHandler->getSessionVariable('HiddenFile'))!=''){
         $errMsg='Invalid file extension or maximum upload size exceeds';
         if($mode==1){
            //delete the new record as file upload fails
            TeacherManager::getInstance()->deleteResource($sessionHandler->getSessionVariable('IdToFileUpload'));
         }
        }
        
    }
  }
  else {
      logError('Upload Error: Session ID missing.');
      $errMsg='File is not uploaded';
      $mode=0; 
  }
  
  echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'",'.$mode.');</script>';
  $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
  $sessionHandler->setSessionVariable('OperationMode',NULL);
  $sessionHandler->setSessionVariable('HiddenFile',NULL);
// $History: resourceFileUpload.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/11/10    Time: 11:44a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//fixed bug no.0002831
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 22/10/09   Time: 17:27
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//0001556
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/09/09   Time: 17:36
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//00001502,00001496
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/04/08   Time: 11:20a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Upload Resource" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:47p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>