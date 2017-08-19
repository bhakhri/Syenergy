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
define('MODULE','EmployeeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

	$currentClassName = CommonQueryManager::getInstance();

    /*if (!isset($REQUEST_DATA['userName']) || trim($REQUEST_DATA['userName']) == '') {
        $errorMessage .= 'Enter user name <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['userPassword']) || trim($REQUEST_DATA['userPassword']) == '')) {
        $errorMessage .= 'Enter password <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roleName']) || trim($REQUEST_DATA['roleName']) == '')) {
        $errorMessage .= 'Enter role name <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeName']) || trim($REQUEST_DATA['employeeName']) == '')) {
        $errorMessage .= 'Enter employee name <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeCode']) || trim($REQUEST_DATA['employeeCode']) == '')) {
        $errorMessage .= 'Enter employee code <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeAbbreviation']) || trim($REQUEST_DATA['employeeAbbreviation']) == '')) {
        $errorMessage .= 'Enter employee abbr. <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['isTeaching']) || trim($REQUEST_DATA['isTeaching']) == '')) {
        $errorMessage .= 'Enter teaching employee <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designation']) || trim($REQUEST_DATA['designation']) == '')) {
        $errorMessage .= 'Enter designation <br/>';
    }
  if ($errorMessage == '' && (!isset($REQUEST_DATA['gender']) || trim($REQUEST_DATA['gender']) == '')) {
        $errorMessage .= 'Enter gender <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['branch']) || trim($REQUEST_DATA['branch']) == '')) {
        $errorMessage .= 'Enter branch <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['states']) || trim($REQUEST_DATA['states']) == '')) {
        $errorMessage .= 'Enter state <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['pin']) || trim($REQUEST_DATA['pin']) == '')) {
        $errorMessage .= 'Enter pin <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['qualification']) || trim($REQUEST_DATA['qualification']) == '')) {
        $errorMessage .= 'Enter qualification <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['isMarried']) || trim($REQUEST_DATA['isMarried']) == '')) {
        $errorMessage .= 'Enter Married <br/>';
    }
  if ($errorMessage == '' && (!isset($REQUEST_DATA['spouseName']) || trim($REQUEST_DATA['spouseName']) == '')) {
        $errorMessage .= 'Enter spouse <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['fatherName']) || trim($REQUEST_DATA['fatherName']) == '')) {
        $errorMessage .= 'Enter father name <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['motherName']) || trim($REQUEST_DATA['motherName']) == '')) {
        $errorMessage .= 'Enter mother name <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['contactNumber']) || trim($REQUEST_DATA['contactNumber']) == '')) {
        $errorMessage .= 'Enter contact number <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['email']) || trim($REQUEST_DATA['email']) == '')) {
        $errorMessage .= 'Enter email <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mobileNumber']) || trim($REQUEST_DATA['mobileNumber']) == '')) {
        $errorMessage .= 'Enter mobile number <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['address1']) || trim($REQUEST_DATA['address1']) == '')) {
        $errorMessage .= 'Enter address1 <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['address2']) || trim($REQUEST_DATA['address2']) == '')) {
        $errorMessage .= 'Enter address2 <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['city']) || trim($REQUEST_DATA['city']) == '')) {
        $errorMessage .= 'Enter city <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['country']) || trim($REQUEST_DATA['country']) == '')) {
        $errorMessage .= 'Enter country <br/>';
    } */

	$teachingininstitutes = $REQUEST_DATA['teachingininstitutes'];
		//print_r($REQUEST_DATA);
		//Added by Sachin to accommodate remarks field in database
		$remarks = add_slashes(trim($REQUEST_DATA['remarks']));
		//echo "remarks = ".$remarks;
		//die;
	$leavingYear = $REQUEST_DATA['leavingYear'];
	$leavingMonth = $REQUEST_DATA['leavingMonth'];
	$leavingDate = $REQUEST_DATA['leavingDate'];
	$isActive = $REQUEST_DATA['isActive'];
	$roleName = $REQUEST_DATA['roleName'];
	$panNo = $REQUEST_DATA['panNo'];


	$sessionHandler->setSessionVariable('DUPLICATE_USER','');
	$sessionHandler->setSessionVariable('OPERATION_MODE',2);
	$sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
	$sessionHandler->setSessionVariable('HiddenThumbFile',$REQUEST_DATA['hiddenThumbImage']);

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeManager.inc.php");
		if ($roleName == 1) {
			echo 'Administrator cannot be created';
			die;
		}

		if($panNo != '' ) {
			$foundPanArray = EmployeeManager::getInstance()->getEmployee(' AND (UCASE(emp.panNo)="'.add_slashes(trim(strtoupper($REQUEST_DATA['panNo']))).'") AND emp.employeeId!='.$REQUEST_DATA['employeeId']);
			 if(trim($foundPanArray[0]['panNo']) != '') {
				if(strtoupper($foundPanArray[0]['panNo'])==trim(strtoupper($REQUEST_DATA['panNo']))) {
				 echo EMPLOYEE_PAN_NO_ALREADY_EXIST;
				 $sessionHandler->setSessionVariable('DUPLICATE_USER',EMPLOYEE_PAN_NO_ALREADY_EXIST);
				 die;
			   }
			}
		}

        $foundArray = EmployeeManager::getInstance()->getEmployee(' AND (UCASE(emp.employeeCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['employeeCode']))).'" OR UCASE(emp.employeeAbbreviation)="'.add_slashes(trim(strtoupper($REQUEST_DATA['employeeAbbreviation']))).'") AND emp.employeeId!='.$REQUEST_DATA['employeeId']);
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
          if(trim($foundArray[0]['employeeCode'])=='') {  //DUPLICATE CHECK
		   if($REQUEST_DATA['userId'] != 0) {
				$returnStatus = EmployeeManager::getInstance()->editEmployee($REQUEST_DATA['employeeId']);
				if($returnStatus===false){
					 echo FAILURE;
					 die;
				 }
				$returnStatus1 = EmployeeManager::getInstance()->editUser($REQUEST_DATA['userId']);
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
				/*$returnDeleteUserRole = EmployeeManager::getInstance()->deleteUserRole($REQUEST_DATA['userId']);
					if($returnDeleteUserRole===false){
						echo FAILURE;
						die;
					}*/
				$userId = $REQUEST_DATA['userId'];
				$previousRoleArray = EmployeeManager::getInstance()->getPreviousRoleArray($userId,$teachingininstitutes);
				$previousRoleId = $previousRoleArray[0]['defaultRoleId'];
				$employeeRoleId = $REQUEST_DATA['roleName'];
				if($previousRoleId == ''){
					$selectUserRoleExistance = EmployeeManager::getInstance()->selectUserRoleExistance($employeeRoleId,$teachingininstitutes,$userId);
						if($selectUserRoleExistance===false){
							echo FAILURE;
							die;
						}
					$count = count($selectUserRoleExistance);
					if($count != 0){
						$employeeRoleId = $REQUEST_DATA['roleName'];
						$returnDeleteUserRole = EmployeeManager::getInstance()->addEmployeeUserRole($REQUEST_DATA['userId'],$employeeRoleId,$teachingininstitutes);
						if($returnDeleteUserRole===false){
							echo FAILURE;
							die;
						}
					}
				}
				else{
					$updateUserRole = EmployeeManager::getInstance()->updateUserRole($REQUEST_DATA['employeeId'],$employeeRoleId,$teachingininstitutes,$previousRoleId,$userId);
					if($updateUserRole===false){
						echo FAILURE;
						die;
					}
				}
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
                        if($employeeRoleId!='' && $REQUEST_DATA['employeeId'] != '' && $insituteId != '' && $userId!='' ) {
						    $insertIntoUserRole = EmployeeManager::getInstance()->insertIntoUserRole($REQUEST_DATA['employeeId'],$employeeRoleId,$insituteId,$userId);
						    if($insertIntoUserRole === false){
							    echo FAILURE;
							    die;
						    }
                        }
					}
				}
				$deleteUserRole = EmployeeManager::getInstance()->deleteFromUserRole($teachingininstitutes,$userId);
						if($deleteUserRole === false){
								echo FAILURE;
								die;
						}
				}
				else {
					$returnStatus2 = EmployeeManager::getInstance()->deleteEmployeeCanTeachIn($REQUEST_DATA['employeeId']);
					if($returnStatus2===false){
						 echo FAILURE;
						 die;
					 }
				}

				if ($isActive == 1) {
					$returnStatus4 = EmployeeManager::getInstance()->updateUserActive($REQUEST_DATA['userId']);
				}
				else {
					$returnStatus4 = EmployeeManager::getInstance()->updateUser($REQUEST_DATA['userId']);
				}
				if($returnStatus4===false){
					 echo FAILURE;
					 die;
				 }

				if($leavingYear=="" && $leavingMonth=="" && $leavingDate=="") {
					if($REQUEST_DATA['isActive']==1) {
					$returnStatus5 = EmployeeManager::getInstance()->updateEmployeeActive($REQUEST_DATA['employeeId']);
					$returnStatus5 = EmployeeManager::getInstance()->updateUserActive($REQUEST_DATA['userId']);
				}
				}
				else {
					$returnStatus5 = EmployeeManager::getInstance()->updateEmployee($REQUEST_DATA['employeeId']);
					$returnStatus5 = EmployeeManager::getInstance()->updateUser($REQUEST_DATA['userId']);
				}
				if($returnStatus5===false){
					 echo FAILURE;
					 die;
				 }

				/*if($returnStatus === false && $returnStatus1===false && $returnStatus2===false && $returnStatus3===false) {
					$errorMessage = FAILURE;
				}
				else {
					echo SUCCESS;
				}*/
		  }
		  else {
			 if($REQUEST_DATA['userName'] != '') {
				 $returnStatus1 = EmployeeManager::getInstance()->addUser();
					if($returnStatus1===false){
						 echo FAILURE;
						 die;
					 }
				$userId=SystemDatabaseManager::getInstance()->lastInsertId();
				$conditions = ", userId = ".$userId;
			  }
			$returnStatus = EmployeeManager::getInstance()->editEmployee($REQUEST_DATA['employeeId'],$conditions);
			if($returnStatus===false){
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
				/*if($userId != 0 AND $userId != '') {
					$returnDeleteUserRole = EmployeeManager::getInstance()->deleteUserRole($userId);
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
			else {
					/*************************
					1. old role = teacher, new role = dean, old roles = a,b,c, teacher
					insert new role : dean, remove old role: teacher. New roles will become: dean,a,b,c
					----------------------------------------------
					2. old role = teacher, new role = dean, old roles = a,b,c, teacher, dean
					since dean is already in the role list, we need to change default role to dean
					*************************/

					/*$returnDeleteUserRole = EmployeeManager::getInstance()->addEmployeeUserRole($userId,$employeeRoleId,$teachingininstitutes);
					if($returnDeleteUserRole===false){
						echo FAILURE;
						die;
					}*/
				$returnStatus2 = EmployeeManager::getInstance()->deleteEmployeeCanTeachIn($REQUEST_DATA['employeeId']);
				if($returnStatus2===false){
					 echo FAILURE;
					 die;
				 }
			}
			if($userId != 0 && $userId != '') {
				if ($isActive == 1) {
					$returnStatus4 = EmployeeManager::getInstance()->updateUserActive($REQUEST_DATA['userId']);
				}
				else {
					$returnStatus4 = EmployeeManager::getInstance()->updateUser($REQUEST_DATA['userId']);
				}
				if($returnStatus4===false){
					 echo FAILURE;
					 die;
				 }
			}
			if ($userId != 0 && $userId != '') {
				if($leavingYear=="" && $leavingMonth=="" && $leavingDate=="") {
					if($REQUEST_DATA['isActive']==1) {
						$returnStatus5 = EmployeeManager::getInstance()->updateEmployeeActive($REQUEST_DATA['employeeId']);
						$returnStatus5 = EmployeeManager::getInstance()->updateUserActive($REQUEST_DATA['userId']);
					}
				}
				else {
					$returnStatus5 = EmployeeManager::getInstance()->updateEmployee($REQUEST_DATA['employeeId']);
					$returnStatus5 = EmployeeManager::getInstance()->updateUser($REQUEST_DATA['userId']);
				}
				if($returnStatus5===false){
					 echo FAILURE;
					 die;
				 }
			}
			else {
					if($leavingYear=="" && $leavingMonth=="" && $leavingDate=="") {
						if($REQUEST_DATA['isActive']==1) {
							$returnStatus5 = EmployeeManager::getInstance()->updateEmployeeActive($REQUEST_DATA['employeeId']);
							//$returnStatus5 = EmployeeManager::getInstance()->updateUserActive($REQUEST_DATA['userId']);
						}
					}
				else {
					$returnStatus5 = EmployeeManager::getInstance()->updateEmployee($REQUEST_DATA['employeeId']);
					//$returnStatus5 = EmployeeManager::getInstance()->updateUser($REQUEST_DATA['userId']);
				}
				if($returnStatus5===false){
					 echo FAILURE;
					 die;
				 }
			}
		  }
        }
        else {
            if(strtoupper($foundArray[0]['employeeCode'])==trim(strtoupper($REQUEST_DATA['employeeCode']))) {
			 echo EMPLOYEE_ALREADY_EXIST;
			 $sessionHandler->setSessionVariable('DUPLICATE_USER',EMPLOYEE_ALREADY_EXIST);
			 die;
		   }
		   else if(strtoupper($foundArray[0]['employeeAbbreviation'])==trim(strtoupper($REQUEST_DATA['employeeAbbreviation']))) {
			   echo EMPLOYEE_ABBR_ALREADY_EXIST;
			   $sessionHandler->setSessionVariable('DUPLICATE_USER',EMPLOYEE_ABBR_ALREADY_EXIST);
			   die;
		   }
        }
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			$sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['employeeId']);
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

// $History: ajaxInitEdit.php $
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Library/Employee
//changes for gap analysis in employee master
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:30p
//Updated in $/LeapCC/Library/Employee
//fixed bug no.0002326
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 11/05/09   Time: 5:33p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos.0001936,0001938,0001939
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/21/09   Time: 6:50p
//Updated in $/LeapCC/Library/Employee
//Fixed bug nos. 0001822, 0001823, 0001824, 0001847, 0001850, 0001825
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/01/09    Time: 2:08p
//Updated in $/LeapCC/Library/Employee
//Modification in code while saving & edit record in IE browser.
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/19/09    Time: 6:00p
//Updated in $/LeapCC/Library/Employee
//put die after messages
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Library/Employee
//Remove administrator role from role type so that no new administrator
//can be made and syenergy will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/11/09    Time: 4:22p
//Updated in $/LeapCC/Library/Employee
//check userStatus
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/28/09    Time: 3:45p
//Updated in $/LeapCC/Library/Employee
//update conditions during employee active/inactive
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/21/09    Time: 7:25p
//Created in $/LeapCC/Library/Employee
//update user table if employee will be active/inactive
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/17/09    Time: 6:13p
//Updated in $/Leap/Source/Library/Employee
//put transactions & update userStatus in user table during add & edit
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/17/09    Time: 4:23p
//Created in $/Leap/Source/Library/Employee
//put transactions
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/17/09    Time: 4:05p
//Updated in $/LeapCC/Library/Employee
//put transactions
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/30/09    Time: 5:29p
//Updated in $/LeapCC/Library/Employee
//make some enhancement in employee and in room print show capacity also
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/30/09    Time: 12:01p
//Updated in $/LeapCC/Library/Employee
//Make the correction in employee code should be unique
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/22/09    Time: 4:07p
//Updated in $/LeapCC/Library/Employee
//fixed issue nos.0000181
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/28/09    Time: 10:14a
//Updated in $/LeapCC/Library/Employee
//make update in query during employee inactive
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
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:40p
//Updated in $/Leap/Source/Library/Employee
//define access file
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:00p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Library/Employee
//modification in employee in templates & functions
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/04/08    Time: 11:08a
//Updated in $/Leap/Source/Library/Employee
//modified for role name
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:55p
//Created in $/Leap/Source/Library/Employee
//checkin
?>
