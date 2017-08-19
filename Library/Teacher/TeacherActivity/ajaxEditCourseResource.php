<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A RESOURCE
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CourseResourceMaster');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
     if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectId']) || trim($REQUEST_DATA['subjectId']) == '')) {
        $errorMessage .= SELECT_SUBJECT."\n";
    }
	 if ($errorMessage == '' && (!isset($REQUEST_DATA['groupId']) || trim($REQUEST_DATA['groupId']) == '')) {
        $errorMessage .= SELECT_GROUP."\n";
    }
    if (!isset($REQUEST_DATA['resourceType']) || trim($REQUEST_DATA['resourceType']) == '') {
        $errorMessage .= SELECT_CATEGORY."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['description']) || trim($REQUEST_DATA['description']) == '')) {
        $errorMessage .= ENTER_DESCRIPTION."\n";
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
		$returnStatus = TeacherManager::getInstance()->editResource($REQUEST_DATA['courseResourceId']);
	
        if($returnStatus === false) {
              $errorMessage = FAILURE;
        }
       else {
                //Id to upload file
                $sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['courseResourceId']);
                $sessionHandler->setSessionVariable('OperationMode',2);
                $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
                echo SUCCESS;
            }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxEditCourseResource.php $
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
