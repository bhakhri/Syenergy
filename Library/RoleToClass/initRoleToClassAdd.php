<?php
//-------------------------------------------------------
// Purpose: to design the layout for add role to class
//
// Author : Jaineesh
// Created on : (28.09.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleToClass');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/RoleToClassManager.inc.php");
$roleToClassManager = RoleToClassManager::getInstance();

$errorMessage ='';

    if (trim($errorMessage) == '') {

	
		$classId	= $REQUEST_DATA['classId'];
		$roleId		= $REQUEST_DATA['roleId'];
		$employeeId = $REQUEST_DATA['teacher'];
		$group = $REQUEST_DATA['group'];

		$chb  = $REQUEST_DATA['chb'];
		$count = count($chb);

		$getUserIdArray	= $roleToClassManager->getEmployeeUserId("WHERE employeeId=".$employeeId);
		$userId = $getUserIdArray[0]['userId'];
		
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			
			/*$insertUserRole	= $roleToClassManager->insertUserRole("WHERE userId=".$userId." AND roleId=".$roleId,$userId,$roleId);
			if($insertUserRole===false) {
				echo FAILURE;
				die;
			}*/
			//foreach ($chb as $classId) {
				$deleteRoleToClass = $roleToClassManager->deleteRoleToClass($userId,$roleId);
			//}
			if($deleteRoleToClass===false) {
				echo FAILURE;
				die;
			}
			if($count > 0 ) {
				foreach($chb as $classId) {
					foreach($group[$classId] as $classGroup) {
						//echo 'classId = '.$classId. ' groupId = '.$classGroup;
						$insertRoleToClass = $roleToClassManager->insertRoleToClass($userId,$classId,$classGroup,$roleId);
					}
				}
				if($insertRoleToClass===false) {
					echo FAILURE;
					die;
				}
			}
			

			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo ASSIGN_ROLE_SUCCESS;
				die;
			}
			else {
				echo FAILURE;
			}
		}
		else {
			echo FAILURE;
			die;
		}
	}
	else {
        echo $errorMessage;
    }

// $History: initRoleToClassAdd.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Library/RoleToClass
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/07/09   Time: 4:58p
//Updated in $/LeapCC/Library/RoleToClass
//fixed bug nos.0001727, 0001725, 0001724, 0001723, 0001721, 0001720,
//0001719, 0001718, 0001729
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:03p
//Created in $/LeapCC/Library/RoleToClass
//new ajax files for add, edit, list
//
?>