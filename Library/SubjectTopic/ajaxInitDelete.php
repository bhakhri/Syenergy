<?php
//-------------------------------------------------------
// Purpose: To delete subject topic
//
// Author : Parveen Sharma
// Created on : 15.01.2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
  $mod = $REQUEST_DATA['mod'];
define('MODULE',$mod);
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage='';
    if (!isset($REQUEST_DATA['subjectTopicId']) || trim($REQUEST_DATA['subjectTopicId']) == '') {
        $errorMessage = 'Invalid Subject Topic.';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SubjectTopicManager.inc.php");
        $subjectTopicManager = SubjectTopicManager::getInstance();
        $recordArray = $subjectTopicManager->checkInSubjectTopic($REQUEST_DATA['subjectTopicId']);
        if($recordArray[0]['found']==0) {
            if($subjectTopicManager->deleteSubjectTopic($REQUEST_DATA['subjectTopicId']) ) {
                echo DELETE;
            }
            else {
                echo DEPENDENCY_CONSTRAINT;
            }
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/30/09    Time: 4:15p
//Updated in $/LeapCC/Library/SubjectTopic
//updated role permission
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectTopic
//added define variable for Role Permission
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/16/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectTopic
//subject topic file added
//


?>