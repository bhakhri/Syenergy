    <?php
    //-------------------------------------------------------
    // THIS FILE IS USED TO ADD NEW EMPLOYEE AND NEW USER
    //
    //
    // Author : Jaineesh
    // Created on : (14.06.2008 )
    // Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	define('MODULE','EmployeeMaster');
	define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	//logError("ajinder in ajax add file");
	
	$currentClassName = CommonQueryManager::getInstance();
        $errorMessage ='';
		$teachingininstitutes = $REQUEST_DATA['teachingininstitutes'];
		//print_r($REQUEST_DATA);
		$remarks = add_slashes(trim($REQUEST_DATA['remarks']));
		//Added by Sachin to accommodate remarks field in database
		//echo "remarks = ".$remarks;
		//die;
		$leavingYear = $REQUEST_DATA['leavingYear'];
		$leavingMonth = $REQUEST_DATA['leavingMonth'];
		$leavingDate = $REQUEST_DATA['leavingDate'];
		$isActive = $REQUEST_DATA['isActive'];
		$roleName = $REQUEST_DATA['roleName'];
		$panNo = $REQUEST_DATA['panNo'];
		
		$sessionHandler->setSessionVariable('DUPLICATE_USER','');
		$sessionHandler->setSessionVariable('OPERATION_MODE',1);
		$sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
		$sessionHandler->setSessionVariable('HiddenThumbFile',$REQUEST_DATA['hiddenThumbImage']);
        
        if (trim($errorMessage) == '') {
            require_once(MODEL_PATH . "/EmployeeManager.inc.php");
			if ($roleName == 1) {
				echo 'Administrator cannot be created';
				die;
			}
			if($REQUEST_DATA['userName'] != '') {
				
			$userName = EmployeeManager::getInstance()->getUserName('WHERE userName = "'.add_slashes(trim($REQUEST_DATA['userName'])).'"');
				if (trim($userName[0]['found'])>0){
					echo DUPLICATE_USER;
					$sessionHandler->setSessionVariable('DUPLICATE_USER',DUPLICATE_USER);
					die;
				}
			}
			if($panNo != '' ) {
				$foundPanArray = EmployeeManager::getInstance()->getEmployee(' AND (UCASE(emp.panNo)="'.add_slashes(trim(strtoupper($REQUEST_DATA['panNo']))).'")');
				 if(trim($foundPanArray[0]['panNo']) != '') { 
					if(strtoupper($foundPanArray[0]['panNo'])==trim(strtoupper($REQUEST_DATA['panNo']))) {
					 echo EMPLOYEE_PAN_NO_ALREADY_EXIST;
					 $sessionHandler->setSessionVariable('DUPLICATE_USER',EMPLOYEE_PAN_NO_ALREADY_EXIST);
					 die;
				   }
				}
			}
                     $foundArray = EmployeeManager::getInstance()->getEmployee('AND (UCASE(emp.employeeCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['employeeCode']))).'" OR UCASE(emp.employeeAbbreviation)="'.add_slashes(trim(strtoupper($REQUEST_DATA['employeeAbbreviation']))).'")');
					if(SystemDatabaseManager::getInstance()->startTransaction()) {
                        if(trim($foundArray[0]['employeeCode'])=='') {  //DUPLICATE CHECK
						 if($REQUEST_DATA['userName'] != '') {
                            $returnStatus = EmployeeManager::getInstance()->addUser();
							if($returnStatus===false){
								 echo FAILURE;
								 die;
							 }
							 $userId=SystemDatabaseManager::getInstance()->lastInsertId();
								if(UtilityManager::notEmpty($userId)) {
										
										$returnUserStatus = EmployeeManager::getInstance()->getUserDetailEmployee($userId);
										if($returnUserStatus===false){
											 echo FAILURE;
											 die;
										 }
										$employeeUserId = 	$returnUserStatus[0]['userId'];
										$employeeRoleId = 	$returnUserStatus[0]['roleId'];

										if($employeeUserId != '' AND $teachingininstitutes != '') {
											$returnUserRoleStatus = EmployeeManager::getInstance()->addEmployeeUserRole($employeeUserId,$employeeRoleId,$teachingininstitutes);
											if($returnUserRoleStatus===false){
												 echo FAILURE;
												 die;
											 }
										}
										
										$returnStatus = EmployeeManager::getInstance()->addEmployee($userId);
										if($returnStatus===false){
											 echo FAILURE;
											 die;
										 }
										$employeeCanTeachId=SystemDatabaseManager::getInstance()->lastInsertId();
										if ($teachingininstitutes != '') {
										$returnStatus = EmployeeManager::getInstance()->addEmployeeCanTeachIn($employeeCanTeachId,$teachingininstitutes);
										}
										
										if($leavingYear!="" && $leavingMonth!="" && $leavingDate!="") {
											$returnStatus = EmployeeManager::getInstance()->updateEmployee($employeeCanTeachId);
											$returnStatus = EmployeeManager::getInstance()->updateUserStatus($userId);
										}
										else {
											if ($isActive == 1) {
											$returnStatus = EmployeeManager::getInstance()->updateUserActive($userId);
										 }
										 else {
											$returnStatus = EmployeeManager::getInstance()->updateUser($userId);
										 }
										}
										if($returnStatus===false){
											 echo FAILURE;
											 die;
										 }
											
									}
									else {
										echo DATA_UNSAVED;
									}
						       }
							   else {
									$returnStatus = EmployeeManager::getInstance()->addEmployeeWithoutUser();
									if($returnStatus===false){
										 echo FAILURE;
										 die;
									 }
									$employeeCanTeachId=SystemDatabaseManager::getInstance()->lastInsertId();
									if ($teachingininstitutes != '') {
										$returnStatus = EmployeeManager::getInstance()->addEmployeeCanTeachIn($employeeCanTeachId,$teachingininstitutes);
									}
									if($leavingYear!="" && $leavingMonth!="" && $leavingDate!="") {
										$returnStatus = EmployeeManager::getInstance()->updateEmployee($employeeCanTeachId);
									}
									if($returnStatus===false){
										 echo FAILURE;
										 die;
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
								$sessionHandler->setSessionVariable('IdToFileUpload',$employeeCanTeachId);
								$sessionHandler->setSessionVariable('IdToFileUpload1',$userId);
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

// $History: ajaxInitAdd.php $
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
//User: Jaineesh     Date: 9/01/09    Time: 2:08p
//Updated in $/LeapCC/Library/Employee
//Modification in code while saving & edit record in IE browser.
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/19/09    Time: 6:00p
//Updated in $/LeapCC/Library/Employee
//put die after messages
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Library/Employee
//Remove administrator role from role type so that no new administrator
//can be made and syenergy will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/11/09    Time: 5:06p
//Updated in $/LeapCC/Library/Employee
//check userStatus
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
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/17/09    Time: 4:05p
//Updated in $/LeapCC/Library/Employee
//put transactions
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/22/09    Time: 4:07p
//Updated in $/LeapCC/Library/Employee
//fixed issue nos.0000181
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
//*****************  Version 15  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:39p
//Updated in $/Leap/Source/Library/Employee
//define access file
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 11/03/08   Time: 11:44a
//Updated in $/Leap/Source/Library/Employee
//put if in condition
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 10/23/08   Time: 10:33a
//Updated in $/Leap/Source/Library/Employee
//remove the comments
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/29/08    Time: 4:22p
//Updated in $/Leap/Source/Library/Employee
//modified for user name
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:01p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:48p
//Updated in $/Leap/Source/Library/Employee
//modified for messages
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/20/08    Time: 6:31p
//Updated in $/Leap/Source/Library/Employee
//modified in messages
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:05p
//Updated in $/Leap/Source/Library/Employee
//fixed the bug
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Library/Employee
//modification in employee in templates & functions
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/04/08    Time: 12:29p
//Updated in $/Leap/Source/Library/Employee
//modification in coding
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/25/08    Time: 2:46p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:55p
//Created in $/Leap/Source/Library/Employee
//checkin
?>