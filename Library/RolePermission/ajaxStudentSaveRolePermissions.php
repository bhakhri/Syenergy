<?php
//-------------------------------------------------------
//  This File saves student role menu permissions
//
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . '/studentMenuItems.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentRolePermissions');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/LoginManager.inc.php");
$loginManager = LoginManager::getInstance();

$roleId = 4;

$allMenuArray = Array();
foreach($allStudentMenus as $independentMenu) {
	foreach($independentMenu as $menuItemArray) {
		if($menuItemArray[0] == MAKE_SINGLE_MENU) {
			$moduleName = $menuItemArray[2][0]; 
			$allMenuArray[] = $moduleName . '_viewPermission';
			$allMenuArray[] = $moduleName . '_addPermission';
			$allMenuArray[] = $moduleName . '_editPermission';
			$allMenuArray[] = $moduleName . '_deletePermission';
		}
		elseif($menuItemArray[0] == MAKE_MENU) {
			foreach($menuItemArray[2] as $moduleMenuItem) {
				$moduleName = $moduleMenuItem[0]; 
				$allMenuArray[] = $moduleName . '_viewPermission';
				$allMenuArray[] = $moduleName . '_addPermission';
				$allMenuArray[] = $moduleName . '_editPermission';
				$allMenuArray[] = $moduleName . '_deletePermission';
			}
		}
		elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
			$moduleArray = $menuItemArray[1];
			list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
			$allMenuArray[] = $moduleName . '_viewPermission';
			$allMenuArray[] = $moduleName . '_addPermission';
			$allMenuArray[] = $moduleName . '_editPermission';
			$allMenuArray[] = $moduleName . '_deletePermission';
		}
	}
}
$accessModulesArray = array();

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {
	$return = $loginManager->unsetAllPermissionsInTransaction($roleId);
	if ($return == false) {
		echo FAILURE;
		die;
	}
	foreach($REQUEST_DATA as $key => $value) {
		$moduleId = 0;
		if ($value == 'on') {
			$accessModulesArray[] = $key;
			if (in_array($key, $allMenuArray)) {
                $temp=explode('_', $key);
                $tempCount=count($temp);
                $moduleName='';
                if($tempCount>2){
                   for($i=0;$i<$tempCount-1;$i++){ 
                     if($moduleName!=''){
                         $moduleName .='_';
                     }  
                     $moduleName .=$temp[$i];
                   }
                   $modulePermission=$temp[$tempCount-1];
                }
                else{
                   $moduleName=$temp[0];
                   $modulePermission=$temp[1];
                }
				//list($moduleName,$modulePermission) = explode('_', $key);
				$moduleArray = $loginManager->checkModuleExists($moduleName);
				if (is_array($moduleArray) and count($moduleArray)) {
					$moduleId = $moduleArray[0]['moduleId'];
				}
				else {
					$moduleId = $loginManager->addModuleInTransaction($moduleName);
					if ($moduleId == false) {
						echo FAILURE;
						die;
					}
				}
				$accessArray = $loginManager->checkModuleAccess($roleId, $moduleId);
				if (!is_array($accessArray) or count($accessArray) == 0) {
					$return = $loginManager->addRolePermissionInTransaction($roleId,$moduleId);
					if ($return == false) {
						echo FAILURE;
						die;
					}
				}
				$return = $loginManager->updatePermissionsInTransaction($roleId,$moduleId, $modulePermission);
				if ($return == false) {
					echo FAILURE;
					die;
				}
			}
		}
	}

	$return = $loginManager->insertRolePermissionInTransaction($roleId);
	if ($return == false) {
		echo FAILURE;
		die;
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

//$History: ajaxStudentSaveRolePermissions.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/18/10    Time: 5:51p
//Updated in $/LeapCC/Library/RolePermission
//updated code as data was not saving successfully for role permissions
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Library/RolePermission
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:04a
//Created in $/LeapCC/Library/RolePermission
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:30p
//Created in $/LeapCC/Library/RolePermission
//Intial checkin
?>