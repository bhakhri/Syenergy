<?php
//-------------------------------------------------------
//  This File saves role menu permissions
//
//
// Author :Ajinder Singh
// Created on : 06-Nov-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . '/menuItems.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RolePermissions');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/LoginManager.inc.php");
$loginManager = LoginManager::getInstance();


global $sessionHandler;
$sessionRoleId = $sessionHandler->getSessionVariable('RoleId');
$roleId = $REQUEST_DATA['roleId'];



if ($sessionRoleId != 1) {
	$allMenuArray = Array();
	foreach($allMenus as $independentMenu) {
		foreach($independentMenu as $menuItemArray) {
			if($menuItemArray[0] == MAKE_SINGLE_MENU) {
				$moduleName = $menuItemArray[2][0];
				//echo "\n--".$moduleName;
				if (!isset($_SESSION[$moduleName])) {
					continue;
				}
				elseif ($_SESSION[$moduleName]['view'] == 1) {
					$allMenuArray[] = $moduleName . '_viewPermission';
				}
				elseif ($_SESSION[$moduleName]['add'] == 1) {
					$allMenuArray[] = $moduleName . '_addPermission';
				}
				elseif ($_SESSION[$moduleName]['edit'] == 1) {
					$allMenuArray[] = $moduleName . '_editPermission';
				}
				elseif ($_SESSION[$moduleName]['delete'] == 1) {
					$allMenuArray[] = $moduleName . '_deletePermission';
				}
			}
			elseif($menuItemArray[0] == MAKE_MENU) {
				foreach($menuItemArray[2] as $moduleMenuItem) {
					$moduleName = $moduleMenuItem[0];
					if (!isset($_SESSION[$moduleName])) {
						continue;
					}
					if ($_SESSION[$moduleName]['view'] == 1) {
						$allMenuArray[] = $moduleName . '_viewPermission';
					}
					if ($_SESSION[$moduleName]['add'] == 1) {
						$allMenuArray[] = $moduleName . '_addPermission';
					}
					if ($_SESSION[$moduleName]['edit'] == 1) {
						$allMenuArray[] = $moduleName . '_editPermission';
					}
					if ($_SESSION[$moduleName]['delete'] == 1) {
						$allMenuArray[] = $moduleName . '_deletePermission';
					}
				}
			}
			elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
				$moduleArray = $menuItemArray[1];
				list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
				//echo "\n***".$moduleName;
				if (!isset($_SESSION[$moduleName])) {
					continue;
				}
				if ($_SESSION[$moduleName]['view'] == 1) {
					$allMenuArray[] = $moduleName . '_viewPermission';
				}
				if ($_SESSION[$moduleName]['add'] == 1) {
					$allMenuArray[] = $moduleName . '_addPermission';
				}
				if ($_SESSION[$moduleName]['edit'] == 1) {
					$allMenuArray[] = $moduleName . '_editPermission';
				}
				if ($_SESSION[$moduleName]['delete'] == 1) {
					$allMenuArray[] = $moduleName . '_deletePermission';
				}
			}
		}
	}
}
else {
	$allMenuArray = Array();
	foreach($allMenus as $independentMenu) {
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
				$moduleName = trim($moduleName);
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


//$History: ajaxSaveRolePermissions.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/18/10    Time: 5:51p
//Updated in $/LeapCC/Library/RolePermission
//updated code as data was not saving successfully for role permissions
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Library/RolePermission
//added role permission module for user other than admin
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Library/RolePermission
//changed queries to add instituteId
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:35p
//Updated in $/LeapCC/Library/RolePermission
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:30p
//Created in $/LeapCC/Library/RolePermission
//Intial checkin
?>