    <?php
    //-------------------------------------------------------
    // THIS FILE IS USED TO ADD NEW EMPLOYEE AND NEW USER
    //
    //
    // Author : Jaineesh
    // Created on : (14.06.2008 )
    // Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	define('MODULE','ShortEmployeeMaster');
	define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	//logError("ajinder in ajax add file");
	
	$currentClassName = CommonQueryManager::getInstance();
        $errorMessage ='';
		/*
        if (!isset($REQUEST_DATA['userName']) || trim($REQUEST_DATA['userName']) == '') {
            $errorMessage .= ENTER_USER_NAME. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['userPassword']) || trim($REQUEST_DATA['userPassword']) == '')) {
            $errorMessage .= ENTER_USER_PASSWORD. '<br/>';
        }   
        if ($errorMessage == '' && (!isset($REQUEST_DATA['roleName']) || trim($REQUEST_DATA['roleName']) == '')) {
            $errorMessage .= 'Enter role name <br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeName']) || trim($REQUEST_DATA['employeeName']) == '')) {
            $errorMessage .=  ENTER_EMPLOYEE_NAME. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeCode']) || trim($REQUEST_DATA['employeeCode']) == '')) {
            $errorMessage .= ENTER_EMPLOYEE_CODE. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeAbbreviation']) || trim($REQUEST_DATA['employeeAbbreviation']) == '')) {
            $errorMessage .= ENTER_EMPLOYEE_ABBR. '<br/>';
        }   */
      /*  if ($errorMessage == '' && (!isset($REQUEST_DATA['isTeaching']) || trim($REQUEST_DATA['isTeaching']) == '')) {
            $errorMessage .= 'Enter teaching employee <br/>';
        }*/
        /*if ($errorMessage == '' && (!isset($REQUEST_DATA['designation']) || trim($REQUEST_DATA['designation']) == '')) {
            $errorMessage .= CHOOSE_EMPLOYEE_DESIGNATION. '<br/>';
        } */
        /*if ($errorMessage == '' && (!isset($REQUEST_DATA['gender']) || trim($REQUEST_DATA['gender']) == '')) {
            $errorMessage .= 'Enter gender <br/>';
        }*/
        /*if ($errorMessage == '' && (!isset($REQUEST_DATA['branch']) || trim($REQUEST_DATA['branch']) == '')) {
            $errorMessage .= CHOOSE_EMPLOYEE_BRANCH. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['states']) || trim($REQUEST_DATA['states']) == '')) {
            $errorMessage .= SELECT_STATE. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['pin']) || trim($REQUEST_DATA['pin']) == '')) {
            $errorMessage .= ENTER_PIN. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['qualification']) || trim($REQUEST_DATA['qualification']) == '')) {
            $errorMessage .= ENTER_EMPLOYEE_QUALIFICATION. '<br/>';
        }*/   
       /* if ($errorMessage == '' && (!isset($REQUEST_DATA['isMarried']) || trim($REQUEST_DATA['isMarried']) == '')) {
            $errorMessage .= 'Enter Married <br/>';
        }*/
       /* if ($errorMessage == '' && (!isset($REQUEST_DATA['spouseName']) || trim($REQUEST_DATA['spouseName']) == '')) {
            $errorMessage .= 'Enter spouse <br/>';
        }*/
        /*if ($errorMessage == '' && (!isset($REQUEST_DATA['fatherName']) || trim($REQUEST_DATA['fatherName']) == '')) {
            $errorMessage .= ENTER_FATHER_NAME. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['motherName']) || trim($REQUEST_DATA['motherName']) == '')) {
            $errorMessage .= ENTER_MOTHER_NAME. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['contactNumber']) || trim($REQUEST_DATA['contactNumber']) == '')) {
            $errorMessage .= ENTER_CONTACT_NUMBER. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['email']) || trim($REQUEST_DATA['email']) == '')) {
            $errorMessage .= ENTER_EMAIL. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['mobileNumber']) || trim($REQUEST_DATA['mobileNumber']) == '')) {
            $errorMessage .= ENTER_MOBILE_NUMBER. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['address1']) || trim($REQUEST_DATA['address1']) == '')) {
            $errorMessage .= ENTER_EMPLOYEE_ADDRESS1. '<br/>';
        }
        
        if ($errorMessage == '' && (!isset($REQUEST_DATA['city']) || trim($REQUEST_DATA['city']) == '')) {
            $errorMessage .= SELECT_CITY. '<br/>';
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['country']) || trim($REQUEST_DATA['country']) == '')) {
            $errorMessage .= SELECT_COUNTRY. '<br/>';
        }     

		*/
		
		$teachingininstitutes = $REQUEST_DATA['teachingininstitutes'];
		$roleName = $REQUEST_DATA['roleName'];
		
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
					die;
				}
			}
				//if($REQUEST_DATA['userName'] != '') {
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
										
										$returnStatus = EmployeeManager::getInstance()->addShortEmployee($userId);
										if($returnStatus===false){
											 echo FAILURE;
											 die;
										 }
										$employeeCanTeachId=SystemDatabaseManager::getInstance()->lastInsertId();
										if ($teachingininstitutes != '') {
										$returnStatus = EmployeeManager::getInstance()->addEmployeeCanTeachIn($employeeCanTeachId,$teachingininstitutes);
										}
										
									}
									else {
										echo DATA_UNSAVED;
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
			//}	
		}
        else {
			echo $errorMessage;
		}

// $History: $
//
//
?>