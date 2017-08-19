<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit a salary head
//
//
// Author : Rajeev Aggarwal
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAssignment');
define('ACCESS','edit');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
     
	if($sessionHandler->getSessionVariable('SuperUserId')!=''){
	  echo ACCESS_DENIED;
	  die;
	}
    
    if(trim($REQUEST_DATA['messageText'])==''){
        die(ENTER_ASSIGNMENT_TEXT);
    }
    
    if(trim($REQUEST_DATA['assignmentId'])=='' or trim($REQUEST_DATA['assignmentDetailId'])==''){
        die("This assignment does not exists");
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Student/StudentAssignmentManager.inc.php");
		$fileUploadId = trim($REQUEST_DATA['assignmentId']).'_'.trim($REQUEST_DATA['assignmentDetailId']);
		$returnStatus = StudentAssignmentManager::getInstance()->editStudentAssignment($fileUploadId);
		if($returnStatus === false) {
			$errorMessage = FAILURE;
		}
		else {
			echo SUCCESS;           
		}
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitStudentAssignmentEdit.php $
?>