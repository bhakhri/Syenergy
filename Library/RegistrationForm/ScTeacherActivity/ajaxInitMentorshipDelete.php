<?php
//-------------------------------------------------------
// Purpose: To delete document detail
//
// Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
   if (!isset($REQUEST_DATA['mentorshipCommentId']) || trim($REQUEST_DATA['mentorshipCommentId']) == '') {
      $errorMessage = 'Invalid Mentorship';
}

    if (trim($errorMessage) == '') {
       	
require_once(MODEL_PATH . "/RegistrationForm/MenteesManager.inc.php");
$teacherManager = MenteesManager::getInstance();

		if($teacherManager->deleteMentorship(add_slashes($REQUEST_DATA['mentorshipCommentId']))) {
		   echo DELETE;
		}
		else {
		   echo DEPENDENCY_CONSTRAINT;
	    }
	}
    else {
        echo $errorMessage;
    }
   ?>