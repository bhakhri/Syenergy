<?php
//-------------------------------------------------------
// Purpose: to design the layout for add subject to class
//
// Author : Arvind Singh Rawat
// Created on : (28.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalSubjectToStudents');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/OptionalSubjectMapping.inc.php");
$optionalSubjectMappingManager = OptionalSubjectMappingManager::getInstance();

$errorMessage ='';   


   if (trim($errorMessage) == '') {

		$returnStatus   = $optionalSubjectMappingManager->insertStudents($REQUEST_DATA);
   
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
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/OptionalSubjectMapping
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/06/08   Time: 1:27p
//Updated in $/Leap/Source/Library/OptionalSubjectMapping
//define module,access
//
//*****************  Version 1  *****************
//User: Arvind       Date: 8/28/08    Time: 8:06p
//Created in $/Leap/Source/Library/OptionalSubjectMapping
//added a new file for student to optional subject mapping
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:06p
//Created in $/Leap/Source/Library/SubjectToClass
//intial checkin
?>