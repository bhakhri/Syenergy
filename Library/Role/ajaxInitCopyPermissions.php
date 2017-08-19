<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A Role
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$roleId = trim($REQUEST_DATA['roleId']);
$copyFrom = trim($REQUEST_DATA['copyFrom']);
$copyTo = trim($REQUEST_DATA['copyTo']);

if (empty($copyTo)) {
	echo PLEASE_SELECT_INSTITUTE_TO_COPY_ROLE_PERMISSIONS;
	die;
}

if (empty($roleId) or empty($copyFrom)) {
	echo INVALID_DETAILS_FOUND;
	die;
}

$copyToArray = explode(',', $copyTo);

if (in_array($copyFrom, $copyToArray)) {
	echo ROLE_PERMISSIONS_CAN_NOT_BE_COPIED_TO_SELF;
	die;
}

require_once(MODEL_PATH . "/RoleManager.inc.php");
$roleManager = UserRoleManager::getInstance();

$oldPermissionArray = $roleManager->checkInRolePermission($roleId, " And instituteId = $copyFrom");
$cnt = $oldPermissionArray[0]['cnt'];
if ($cnt == 0) {
	echo NO_PERMISSION_FOUND_TO_COPY;
	die;
}

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {

	foreach($copyToArray as $newInstitute) {
		$return = $roleManager->deleteOldPermission($roleId, $newInstitute);
		if ($return == false) {
			echo FAILURE;
			die;
		}
		$return = $roleManager->copyNewPermission($roleId, $newInstitute, $copyFrom);
		if ($return == false) {
			echo FAILURE;
			die;
		}
	}

	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		echo SUCCESS;
	}
	else {
		echo FAILURE;
	}
}
else {
	echo FAILURE;
}



// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Library/Role
//added role permission module for user other than admin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Role
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:32a
//Updated in $/Leap/Source/Library/Role
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/08    Time: 12:28p
//Updated in $/Leap/Source/Library/Role
//Added Standard Messages
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:22p
//Updated in $/Leap/Source/Library/Role
//Created Role(Role Master) Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 2:58p
//Created in $/Leap/Source/Library/Role
//Initial checkin
?>
