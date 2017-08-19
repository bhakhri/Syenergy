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
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    $errorMessage ='';
     if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectId']) || trim($REQUEST_DATA['subjectId']) == '')) {
        $errorMessage .= SELECT_SUBJECT."\n";
    }
    if (!isset($REQUEST_DATA['resourceType']) || trim($REQUEST_DATA['resourceType']) == '') {
        $errorMessage .= SELECT_CATEGORY."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['description']) || trim($REQUEST_DATA['description']) == '')) {
        $errorMessage .= ENTER_DESCRIPTION."\n";
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
        $returnStatus = ScTeacherManager::getInstance()->editResource($REQUEST_DATA['courseResourceId']);
        if($returnStatus === false) {
              $errorMessage = FAILURE;
        }
       else {
                //Id to upload file
                $sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['courseResourceId']); 
                echo SUCCESS;
            }
    }
    else {
        echo $errorMessage;
    }
// $History: scAjaxEditCourseResource.php $
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
