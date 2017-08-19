<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();


    if (!isset($REQUEST_DATA['courseResourceId']) || trim($REQUEST_DATA['courseResourceId']) == '') {
        $errorMessage = 'Invalid Resource';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
        $teacherManager =  ScTeacherManager::getInstance();
        $fileArray = $teacherManager->checkResourceExists($REQUEST_DATA['courseResourceId']);

		// delete record
        if($teacherManager->deleteResource($REQUEST_DATA['courseResourceId']) ) {
		
		//delete associated file with these file(IF EXISTS)				
		 if(UtilityManager::notEmpty($fileArray[0]['attachmentFile'])) {
			 if(file_exists(IMG_PATH.'/CourseResource/'.$fileArray[0]['attachmentFile'])) {
                @unlink(IMG_PATH.'/CourseResource/'.$fileArray[0]['attachmentFile']);
             }
         }

		 echo DELETE;
        }
        else {
                echo DEPENDENCY_CONSTRAINT;
        }
	} 
    else {
        echo $errorMessage;
    }
// $History: scAjaxDeleteCourseResource.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:44p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>

