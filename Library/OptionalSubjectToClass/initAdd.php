<?php
//-------------------------------------------------------
// Purpose: to design the layout for add subject to class
//
// Author : Rajeev Aggarwal
// Created on : (09.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalCourseToClass');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/SubjectToClassManager.inc.php");
$subjecttoclassManager = SubjectToClassManager::getInstance();

$errorMessage ='';

    if (trim($errorMessage) == '') {

		$classId		= $REQUEST_DATA['classId'];
		$parentSubjectId		= $REQUEST_DATA['mmSubjectId'];
		$returnStatus   = $subjecttoclassManager->insertOptSubToClass($classId, $parentSubjectId);
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

// $History: initAdd.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectToClass
//added define variable for Role Permission
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectToClass
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:06p
//Created in $/Leap/Source/Library/SubjectToClass
//intial checkin
?>