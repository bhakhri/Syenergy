<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CourseResourceMaster');
define('ACCESS','delete');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();


    if (!isset($REQUEST_DATA['courseResourceId']) || trim($REQUEST_DATA['courseResourceId']) == '') {
        $errorMessage = 'Invalid Resource';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
        $teacherManager =  TeacherManager::getInstance();
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
// $History: ajaxDeleteCourseResource.php $    
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
//User: Dipanjan     Date: 11/05/08   Time: 2:44p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>

