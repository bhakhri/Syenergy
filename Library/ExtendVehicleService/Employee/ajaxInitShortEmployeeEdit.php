<?php

//-------------------------------------------------------
// THIS FILE IS USED TO EDIT EXISTING EMPLOYEE AND USER & CHECK THE DUPLICACY ALSO
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
define('MODULE','ShortEmployeeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

	$currentClassName = CommonQueryManager::getInstance();


	$teachingininstitutes = $REQUEST_DATA['teachingininstitutes'];
	$roleName = $REQUEST_DATA['roleName'];

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeManager.inc.php");
		if ($roleName == 1) {
			echo 'Administrator cannot be created';
			die;
		}
        $foundArray = EmployeeManager::getInstance()->getEmployee(' AND (UCASE(emp.employeeCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['employeeCode']))).'" OR UCASE(emp.employeeAbbreviation)="'.add_slashes(trim(strtoupper($REQUEST_DATA['employeeAbbreviation']))).'") AND emp.employeeId!='.$REQUEST_DATA['employeeId']);
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			  if(trim($foundArray[0]['employeeCode'])=='') {  //DUPLICATE CHECK
				   if($REQUEST_DATA['userId'] != 0) {
					$returnStatus = EmployeeManager::getInstance()->editShortEmployee($REQUEST_DATA['employeeId']);
					if($returnStatus===false){
						 echo FAILURE;
						 die;
					 }
					$returnStatus1 = EmployeeManager::getInstance()->editShortEmployeeUser($REQUEST_DATA['userId']);
					if($returnStatus1===false){
						 echo FAILURE;
						 die;
					 }
					if ($teachingininstitutes != '') {
						$returnStatus2 = EmployeeManager::getInstance()->deleteEmployeeCanTeachIn($REQUEST_DATA['employeeId']);
						if($returnStatus2===false){
							 echo FAILURE;
							 die;
						 }
						$returnStatus3 = EmployeeManager::getInstance()->addEmployeeCanTeachIn($REQUEST_DATA['employeeId'],$teachingininstitutes);
						if($returnStatus3===false){
							 echo FAILURE;
							 die;
						 }
						/*($returnDeleteUserRole = EmployeeManager::getInstance()->deleteUserRole($REQUEST_DATA['userId']);
						if($returnDeleteUserRole===false){
							echo FAILURE;
							die;
						}*/
					$userId = $REQUEST_DATA['userId'];
					$previousRoleArray = EmployeeManager::getInstance()->getPreviousRoleArray($userId,$teachingininstitutes);
					$previousRoleId = $previousRoleArray[0]['defaultRoleId'];
					if ($previousRoleId == '') {
						$selectUserRoleExistance = EmployeeManager::getInstance()->selectUserRoleExistance($employeeRoleId,$teachingininstitutes,$userId);
							if($selectUserRoleExistance===false){
								echo FAILURE;
								die;
							}
						$count = count($selectUserRoleExistance);
						if($count != 0){
							$insertUserRole = 	$employeeRoleId = $REQUEST_DATA['roleName'];
							$addEmployeeUserRole = EmployeeManager::getInstance()->addEmployeeUserRole($REQUEST_DATA['userId'],$employeeRoleId,$teachingininstitutes);
							if($addEmployeeUserRole===false){
								echo FAILURE;
								die;
							}
						}
					}
					else {
						$employeeRoleId = $REQUEST_DATA['roleName'];
						$updateUserRole = EmployeeManager::getInstance()->updateUserRole($REQUEST_DATA['employeeId'],$employeeRoleId,$teachingininstitutes,$previousRoleId,$userId);
						if($updateUserRole===false){
							echo FAILURE;
							die;
						}
					}
					$employeeRoleId = $REQUEST_DATA['roleName'];
					$updateDefaultRoleId = EmployeeManager::getInstance()->updateDefaultRoleId($REQUEST_DATA['employeeId'],$employeeRoleId,$userId,$previousRoleId);
						if($updateDefaultRoleId === false){
							echo FAILURE;
							die;
						}
					$teachingInstituteId = explode(',', $teachingininstitutes);
					$count= count($teachingInstituteId);
					for($i=0;$i<$count;$i++){
						$insituteId = $teachingInstituteId[$i];
						$selectInsituteId = EmployeeManager::getInstance()->selectInsituteId($userId,$employeeRoleId,$insituteId);
							if($selectInsituteId === false){
								echo FAILURE;
								die;
							}
						$cnt = count($selectInsituteId);
						if($cnt==0){
							$insertIntoUserRole = EmployeeManager::getInstance()->insertIntoUserRole($REQUEST_DATA['employeeId'],$employeeRoleId,$insituteId,$userId);
								if($insertIntoUserRole === false){
									echo FAILURE;
									die;
								}
						}
					}
					$deleteUserRole = EmployeeManager::getInstance()->deleteFromUserRole($teachingininstitutes,$userId);
						if($deleteUserRole === false){
								echo FAILURE;
								die;
						}

					}
			  }
        }
        else {
            if(strtoupper($foundArray[0]['employeeCode'])==trim(strtoupper($REQUEST_DATA['employeeCode']))) {
			 echo EMPLOYEE_ALREADY_EXIST;
			 die;
		   }
		   else if(strtoupper($foundArray[0]['employeeAbbreviation'])==trim(strtoupper($REQUEST_DATA['employeeAbbreviation']))) {
			   echo EMPLOYEE_ABBR_ALREADY_EXIST;
			   die;
		   }
        }
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
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
	}
    else {
        echo $errorMessage;
    }

// $History: $
//
?>