<?php
//-------------------------------------------------------
// Purpose: to design the layout for add subject to class
//
// Author : Ajinder Singh
// Created on : (28-jan-2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignCourseToClass');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/SubjectToClassManager.inc.php");
$subjecttoclassManager = SubjectToClassManager::getInstance();

$errorMessage ='';

    if (trim($errorMessage) == '') {

		$classId		= $REQUEST_DATA['classId'];
		$returnStatus   = $subjecttoclassManager->insertSubToClass($classId);
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	else {
        echo $errorMessage;
    }

// $History: classSubjectsAdd.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:41p
//Created in $/LeapCC/Library/PromoteStudentsAdvanced
//file added for new interface of session end activities
//


?>