<?php
//-------------------------------------------------------
// Purpose: To delete group type detail
// Author : Jaineesh
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
//set_time_limit(0);
//ini_set('memory_limit','200M');

	if (!isset($REQUEST_DATA['employeeId']) || trim($REQUEST_DATA['employeeId']) == '') {
		echo INVALID_EMPLOYEE;
		die;
	}
	require_once(MODEL_PATH . "/EmployeeManager.inc.php");
	$employeeManager =  EmployeeManager::getInstance();
	$employeeId = $REQUEST_DATA['employeeId'];
	$employeeArray = $employeeManager->getCheckEmployee("AND emp.employeeId =".$employeeId);
	if ($employeeArray[0]['employeeId'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}
	$employeeInstituteArray = $employeeManager->getCheckEmployeeWithInstitute("AND emp.employeeId =".$employeeId);
	if ($employeeInstituteArray[0]['employeeId'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}
	$employeeExperienceArray = $employeeManager->getCheckEmployeeWithExperience("WHERE employeeId =".$employeeId);
	if ($employeeExperienceArray[0]['employeeId'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}
	$employeeQualificationArray = $employeeManager->getCheckEmployeeWithQualification("WHERE employeeId =".$employeeId);
	if ($employeeQualificationArray[0]['employeeId'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}
	$userArr = $employeeManager->getUser($employeeId);
	if ($userArr == false) {
		echo FAILURE;
		die;
	}
	$resultId = $userArr[0]['userId'];
	/*$checkUserDependencyArray = $employeeManager->getCheckUserDependency($resultId);
	if($checkUserDependencyArray[0]['userId'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}
	$checkEmployeeDependencyArray = $employeeManager->getCheckEmployeeDependency($employeeId);
	if($checkEmployeeDependencyArray[0]['employeeId'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}*/
	$employeeVisibleToRoleArray = $employeeManager->getEmployeeVisibleToRole("WHERE userId =".$resultId);
	if ($employeeVisibleToRoleArray == false) {
		echo FAILURE;
		die;
	}
	if ($employeeVisibleToRoleArray[0]['userId'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}
	$fileInfoArr=$employeeManager->getEmployeeImageDetail(' WHERE employeeId='.$employeeId);
	if ($fileInfoArr == false) {
		echo FAILURE;
		die;
	}
	$fileInfoThumbArr=$employeeManager->getEmployeeThumbImageDetail(' WHERE employeeId='.$employeeId);
	if ($fileInfoThumbArr == false) {
		echo FAILURE;
		die;
	}
	//echo($fileInfoThumbArr[0]['thumbImage']);
	//die('line'.__LINE__);
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		if ($employeeId!="") {
			if($resultId != 0 ) {
				$returnDeleteUserLogStatus = $employeeManager->deleteUserLog($resultId);
				if ($returnDeleteUserLogStatus == false) {
					echo FAILURE;
					die;
				}
				$returnDeleteUserPrefsStatus = $employeeManager->deleteUserPrefs($resultId);
				if ($returnDeleteUserPrefsStatus == false) {
					echo FAILURE;
					die;
				}
				$returnDeleteUserRoleStatus = $employeeManager->deleteUserRole($resultId);
				if ($returnDeleteUserRoleStatus == false) {
					echo FAILURE;
					die;
				}
				$returnDeleteEmployeeVisibleToRoleStatus = $employeeManager->deleteEmployeeVisibleToRole($resultId);
				if ($returnDeleteEmployeeVisibleToRoleStatus == false) {
					echo FAILURE;
					//die;
				}
				//$employeeManager->deleteQueryLog($resultId);
				$returnDeleteEmployeeCanTeachInStatus = $employeeManager->deleteEmployeeCanTeachIn($employeeId);
				if ($returnDeleteEmployeeCanTeachInStatus == false) {
					echo FAILURE;
					die;
				}
				$returnDeleteUserStatus = $employeeManager->deleteUser($resultId);
				if ($returnDeleteUserStatus == false) {
					echo DEPENDENCY_CONSTRAINT;
					die;
				}
				$returnDeleteEmployeeStatus = $employeeManager->deleteEmployee($employeeId);
				if ($returnDeleteEmployeeStatus == false) {
					echo DEPENDENCY_CONSTRAINT;
					die;
				}
			}
			else {
				$returnStatus = $employeeManager->deleteEmployeeCanTeachIn($employeeId);
				if($returnStatus = false) {
					echo FAILURE;
					die;
				}
				$returnDeleteEmployeeStatus = $employeeManager->deleteEmployee($employeeId);
				if($returnDeleteEmployeeStatus = false) {
					echo FAILURE;
					die;
				}
			}
			if(UtilityManager::notEmpty($fileInfoArr[0]['employeeImage'])) {
				if(file_exists(IMG_PATH.'/Employee/'.$fileInfoArr[0]['employeeImage'])) {
					@unlink(IMG_PATH.'/Employee/'.$fileInfoArr[0]['employeeImage']);
				}
			}
			if(UtilityManager::notEmpty($fileInfoThumbArr[0]['thumbImage'])) {
				if(file_exists(IMG_PATH.'/Employee/ThumbImage/'.$fileInfoThumbArr[0]['thumbImage'])) {
					@unlink(IMG_PATH.'/Employee/ThumbImage/'.$fileInfoThumbArr[0]['thumbImage']);
				}
			}
			//echo DELETE;
		}
			else {
				echo FAILURE;
				die;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo DEL;
				die;
			}
			else {
				echo FAILURE;
				die;
			}
	}
	else {
		echo FAILURE;
		die;
	}

// $History: ajaxInitDelete.php $
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 4/19/10    Time: 11:08a
//Updated in $/LeapCC/Library/Employee
//fixed bug no.3293
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 4/07/10    Time: 12:07p
//Updated in $/LeapCC/Library/Employee
//Fixed error during delete of an employee, taking too much time to
//execute.
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 4/01/10    Time: 5:20p
//Updated in $/LeapCC/Library/Employee
//fixed error
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 3/31/10    Time: 11:31a
//Updated in $/LeapCC/Library/Employee
//fixed bug no.3163
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 2/01/10    Time: 10:07a
//Updated in $/LeapCC/Library/Employee
//fixed bug no. 0002737
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/10/09    Time: 12:40p
//Updated in $/LeapCC/Library/Employee
//deleteUserRole() to delete user from user role
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/10/09    Time: 4:36p
//Updated in $/LeapCC/Library/Employee
//delete user from user_log & user_prefs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/21/09    Time: 3:10p
//Updated in $/LeapCC/Library/Employee
//fixed bug no.0000613
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Library/Employee
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/27/09    Time: 7:34p
//Updated in $/LeapCC/Library/Employee
//fixed bugs & enhancement No.1071,1072,1073,1074,1075,1076,1077,1079
//issues of Issues [25-May-09]Build# cc0006.doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/19/08   Time: 3:31p
//Updated in $/LeapCC/Library/Employee
//modified for employee can teach in
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Employee
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:40p
//Updated in $/Leap/Source/Library/Employee
//define access file
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:00p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/08    Time: 3:58p
//Updated in $/Leap/Source/Library/Employee
//modified message
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Library/Employee
//modification in employee in templates & functions
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/04/08    Time: 12:01p
//Created in $/Leap/Source/Library/Employee
//add ajax files for list of employee & delete employee
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:21p
//Created in $/Leap/Source/Library/Designation
//include ajax delete function
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/25/08    Time: 12:59p
//Created in $/Leap/Source/Library/Periods
//function is used for delete period
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>