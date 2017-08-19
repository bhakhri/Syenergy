<?php
$errorMessage = '';
require_once(MODEL_PATH . "/LoginManager.inc.php");
$loginManager = LoginManager::getInstance();
//get active session's details
$activeSessionDetail = $loginManager->getActiveSessionDetail();
require_once(MODEL_PATH.'/CommonQueryManager.inc.php');

require_once(MODEL_PATH . "/StudentPendingDuesManager.inc.php");
$studentPendingDuesManager = StudentPendingDuesManager::getInstance();

if(isset($REQUEST_DATA['imgSubmit_x']) ) {

	if (!isset($REQUEST_DATA['username']) || trim($REQUEST_DATA['username']) == '') {
		$errorMessage = "Username cannot be left empty";
		logError('The value of $REQUEST_DATA["username"] in Index/init.php is empty');
	}

	if ($errorMessage == '' && (!isset($REQUEST_DATA['password']) || trim($REQUEST_DATA['password']) == '')) {
		$errorMessage = "Password cannot be left empty";
		logError('The value of $REQUEST_DATA["password"] in Index/init.php is empty');
	}

	if (trim($errorMessage) == '') {
		//require_once(MODEL_PATH . "/LoginManager.inc.php");
		//$loginManager = LoginManager::getInstance();

        // Enter User PassWord use for Moodle Login
        $sessionHandler->setSessionVariable('EnterUserPassWord',$REQUEST_DATA['password']);

		$returnStatus = $loginManager->authenticateUser($REQUEST_DATA['username'], $REQUEST_DATA['password']);

		if (is_array($returnStatus) && count($returnStatus)>0 ) {
            //check whether this user is active or not
            if($returnStatus[0]['userStatus']==0){
               $sessionHandler->destroySession();
               $errorMessage = "Your account is suspended.<br/>Please contact your system administrator to get it activated.";
               logError('User : '.$REQUEST_DATA["username"].'\'s account is in inactive state');
               return false;
            }


			//if not administrator, then check for instituteId of user.
			if ($returnStatus[0]['roleId'] != 1) {
				$userInstituteId = $returnStatus[0]['instituteId'];
				if ($userInstituteId != $REQUEST_DATA['instituteId']) {
					$sessionHandler->destroySession();
					$errorMessage = "That username and/or password has not been recognised";
					logError("user " . $REQUEST_DATA['username'] . " trying to login");
					 return false;
				}
			}


            $sessionHandler->setSessionVariable('staticfooter','on');   //session variable for the still footer
            $sessionHandler->setSessionVariable('UserId',$returnStatus[0]['userId']);
            $sessionHandler->setSessionVariable('RoleId',$returnStatus[0]['roleId']);
			$sessionHandler->setSessionVariable('RoleType',$returnStatus[0]['roleType']);
			$sessionHandler->setSessionVariable('UserThemeId',$returnStatus[0]['themeId']);
            $sessionHandler->setSessionVariable('UserExpandCollapseGrouping',$returnStatus[0]['grouping']); //used for "grouping" facility
            $sessionHandler->setSessionVariable('UserName',$REQUEST_DATA['username']);
            $sessionHandler->setSessionVariable('RoleName',strip_slashes($returnStatus[0]['roleName']));
            $sessionHandler->setSessionVariable('RemoteIp',$_SERVER['REMOTE_ADDR']);
            $sessionHandler->setSessionVariable('ApplicationPath',HTTP_PATH);

            //this line is redundant but required
            $employeeRet = $loginManager->getEmployeeDetail($returnStatus[0]['userId']);
            if(is_array($employeeRet) && count($employeeRet) >0) {
                $sessionHandler->setSessionVariable('EmployeeId',$employeeRet[0]['employeeId']);
                $sessionHandler->setSessionVariable('EmployeeName',$employeeRet[0]['employeeName']);
                $sessionHandler->setSessionVariable('EmployeeCode',$employeeRet[0]['employeeCode']);
                
                $sessionHandler->setSessionVariable('EmployeeDOB',$employeeRet[0]['dateOfBirth']);
                $sessionHandler->setSessionVariable('EmployeeDOM',$employeeRet[0]['dateOfMarriage']);
                $sessionHandler->setSessionVariable('EmployeeDOJ',$employeeRet[0]['dateOfJoining']);
            }

            // code to get modules and role permissions goes here //// to be done later

            // get institute detail and store this into session variable
            $instituteRet = $loginManager->getInstituteDetail($REQUEST_DATA['instituteId']);
            $sessionRet   = $loginManager->getSessionDetail($REQUEST_DATA['sessionId']);

            if(is_array($instituteRet) && is_array($sessionRet) && count($sessionRet) >0 && count($instituteRet)>0) {
                $sessionHandler->setSessionVariable('InstituteId',$instituteRet[0]['instituteId']);
                $sessionHandler->setSessionVariable('InstituteAbbr',$instituteRet[0]['instituteAbbr']);
                $sessionHandler->setSessionVariable('InstituteCode',$instituteRet[0]['instituteCode']);
                $sessionHandler->setSessionVariable('InstituteName',$instituteRet[0]['instituteName']);
                $sessionHandler->setSessionVariable('InstituteLogo',$instituteRet[0]['instituteLogo']);
                $sessionHandler->setSessionVariable('SessionName',$sessionRet[0]['sessionName']);
                $sessionHandler->setSessionVariable('SessionCode',$sessionRet[0]['abbreviation']);
                $sessionHandler->setSessionVariable('SessionId',$sessionRet[0]['sessionId']);
                $sessionHandler->setSessionVariable('SessionYear',$sessionRet[0]['sessionYear']);

                
                /*FINDING DEFAULT ROLE OF THE LOGGED IN USER*/
            $defaultRoleArray=$loginManager->getDefaultUserRole($sessionHandler->getSessionVariable('UserId'),$sessionHandler->getSessionVariable('InstituteId'));

            if(is_array($defaultRoleArray) and count($defaultRoleArray)>0){
              if($defaultRoleArray[0]['defaultRoleId']!='' and $defaultRoleArray[0]['defaultRoleId']!=0){
               $sessionHandler->setSessionVariable('DefaultRoleId',$defaultRoleArray[0]['defaultRoleId']);
               $sessionHandler->setSessionVariable('RoleId',$defaultRoleArray[0]['defaultRoleId']);
              }
              else{
                $sessionHandler->setSessionVariable('DefaultRoleId',$sessionHandler->getSessionVariable('RoleId'));
              }
            }
            else{
               $sessionHandler->setSessionVariable('DefaultRoleId',$sessionHandler->getSessionVariable('RoleId'));
            }

                ####CODE FOR MAKING ARRAYS FOR INSTITUTE NAME, CODE AND ABBR. AS REQUIRED##############
				$instituteArray = CommonQueryManager::getInstance()->getInstitute(' instituteId');
				$thisInstituteNameArray = array();
				$thisInstituteCodeArray = array();
				$thisInstituteAbbrArray = array();
				foreach($instituteArray as $instituteRecord) {
					$thisInstituteNameArray[$instituteRecord['instituteId']] = $instituteRecord['instituteName'];
					$thisInstituteCodeArray[$instituteRecord['instituteId']] = $instituteRecord['instituteCode'];
					$thisInstituteAbbrArray[$instituteRecord['instituteId']] = $instituteRecord['instituteAbbr'];
				}
				$sessionHandler->setSessionVariable('InstituteNameArray', $thisInstituteNameArray);
				$sessionHandler->setSessionVariable('InstituteCodeArray', $thisInstituteCodeArray);
				$sessionHandler->setSessionVariable('InstituteAbbArray', $thisInstituteAbbrArray);

				//FETCH VALUES FROM CONFIG TABLE AND STORE INTO SESSION
				$configArray = $loginManager->getConfigSettings();
				if (is_array($configArray) && count($configArray)) {
					foreach($configArray as $configRecord) {
						$sessionHandler->setSessionVariable($configRecord['param'],$configRecord['value']);
					}
				}

				//FETCH VALUES FROM USER_ROLE TABLE AND STORE INTO SESSION

				$allRoleArray = $loginManager->getAllUserRole();
				if (is_array($allRoleArray) && count($allRoleArray)) {
					$sessionHandler->setSessionVariable('roleArray',$allRoleArray);
				}

				/********DELETE MENU FILE FOR ROLE(s)*********/
				/** COMMENTED BECAUSE CACHING IS NOT WORKING PROPERLY
				$allRoleAllInstituteArray = $loginManager->getAllUserRoleForAllInstitutes();
				if (is_array($allRoleAllInstituteArray) && count($allRoleAllInstituteArray)) {
					foreach($allRoleAllInstituteArray as $key=>$value){
						//delete menu files for all roles of this user.so that new files are created when user
						//changes role dropdown new are created for the first time
						$fileName = TEMPLATES_PATH . '/Xml/menuContents'.trim($allRoleAllInstituteArray[$key]['roleId']).'.html';
						chmod($fileName,0777);
						$fh = fopen($fileName, 'wb+');
						if ($fh) {

							fclose($fh);
						}
						chmod($fileName,0777);
					}
				}
				**/

            /********DELETE MENU FILE FOR ROLE(s)*********/


				$accessArray = $loginManager->getAccessArray();
				foreach($accessArray as $accessRecord) {
					$sessionHandler->setSessionVariable($accessRecord['moduleName'],
						Array (
						'view'	=>	$accessRecord['viewPermission'],
						'add'	=>	$accessRecord['addPermission'],
						'edit'	=>	$accessRecord['editPermission'],
						'delete'=>	$accessRecord['deletePermission']
						)
					);
				}

				// function to fetch dashboard permissions
				$dashboardAccessArray = $loginManager->getDashboardAccessArray();
				foreach($dashboardAccessArray as $dashboardAccessRecord) {

					$sessionHandler->setSessionVariable($dashboardAccessRecord['frameName'],$dashboardAccessRecord['frameId'] );
				}

                // roleId 1: administrator, 2: teacher, 3: parent, 4: student
                // get student Id , Name as it will save one query further in the code if we store student id during login
				//if($returnStatus[0]['roleId']==3 || $returnStatus[0]['roleId']==4) {
                if($sessionHandler->getSessionVariable('RoleId')==3 || $sessionHandler->getSessionVariable('RoleId')==4){
                    $studentRet = $loginManager->getStudentDetail($returnStatus[0]['userId']);
                    if(is_array($studentRet) && count($studentRet)>0) {
                        // ====================== START (Block Pending Fee Student) ======================== 
                            $pendingDate = $sessionHandler->getSessionVariable('PENDING_FEE_DATE_ALERT'); 
                            if($pendingDate!='') {
                              $serverDate = date('Y-m-d'); 
                              if($pendingDate < $serverDate) { 
                                $errorMessageBlockStudent='';
                                $pendingCondition = " AND stu.studentId = '".$studentRet[0]['studentId']."'";    
                                $pendingClassArray = $studentPendingDuesManager->getClassList($studentRet[0]['classId']);
                                $pendingClassIds =0;
                                for($i=0;$i<count($pendingClassArray);$i++) {
                                  $pendingClassIds .= ",".$pendingClassArray[$i]['classId'];  
                                }
                                $pendingStudentCondition = " stu.classId IN (".$pendingClassIds.") ".$pendingCondition;
                                $pendingStudentArray = $studentPendingDuesManager->getClassStudentList($pendingStudentCondition,'studentName',1);
                                 for($ss=0;$ss<count($pendingStudentArray);$ss++) { 
                                     $pendingClassId = $studentRet[0]['classId']; 
                                     $pendingStudentArray[$ss]['academicDues']=0;
                                     $pendingStudentArray[$ss]['transportDues']=0;
                                     $pendingStudentArray[$ss]['hostelDues']=0;
                                     $pendingStudentArray[$ss]['prevDues']=0;
                                     $pendingStudentArray[$ss]['total']=0;
                                     $pendingStudentArray[$ss]['feeClassName']='';
                                     $valueArray = getFeeList($pendingStudentArray[$ss],$pendingClassId);      
                                     if($valueArray!=0) {  
                                       $errorMessageBlockStudent = PENDING_FEE_MESSAGE;
                                       $sessionHandler->destroySession();
                                       logError("$errorMessageBlockStudent : Username " . $REQUEST_DATA['username']." Trying to login" );
                                       return false;  
                                     }
                                }
                              }
                            }
                        // ====================== END (Block Pending Fee Student) ======================== 
                        
                        
                        // ====================== START (Block Student) ======================== 
                        $returnStatus1 = $loginManager->blockedUser($studentRet[0]['studentId']);
                        if(is_array($returnStatus1) && count($returnStatus1)>0) {
                           $errorMessageBlockStudent = "Chalkpad Blocked due to : ".$returnStatus1[0]['message'];
                           $sessionHandler->destroySession();
                           logError("$errorMessageBlockStudent : Username " . $REQUEST_DATA['username']." Trying to login" );
                           return false;
                        }
                        // ========================== END (Block Student) ========================
                        
                        $sessionHandler->setSessionVariable('StudentMobileNo',$studentRet[0]['studentMobileNo']);
                        $sessionHandler->setSessionVariable('FatherMobileNo',$studentRet[0]['fatherMobileNo']);
                        $sessionHandler->setSessionVariable('MotherMobileNo',$studentRet[0]['motherMobileNo']);
                        $sessionHandler->setSessionVariable('GuardianMobileNo',$studentRet[0]['guardianMobileNo']);
                        $sessionHandler->setSessionVariable('CorrPhoneNo',$studentRet[0]['corrPhone']);
                        $sessionHandler->setSessionVariable('PermPhoneNo',$studentRet[0]['permPhone']);
                        
                        $sessionHandler->setSessionVariable('StudentId',$studentRet[0]['studentId']);
                        $sessionHandler->setSessionVariable('StudentName',$studentRet[0]['studentName']);
                        $sessionHandler->setSessionVariable('FatherName',$studentRet[0]['fatherName']);
                        $sessionHandler->setSessionVariable('RollNo',$studentRet[0]['rollNo']);
                        $sessionHandler->setSessionVariable('RegNo',$studentRet[0]['regNo']);
                        $sessionHandler->setSessionVariable('UniversityRollNo',$studentRet[0]['universityRollNo']);
                        $sessionHandler->setSessionVariable('ClassId',$studentRet[0]['classId']);             
                        $sessionHandler->setSessionVariable('LoggedName',$studentRet[0]['studentName']);
                        $sessionHandler->setSessionVariable('ClassName',$studentRet[0]['className']);
                        
                        $sessionHandler->setSessionVariable('StudentAllClass',$studentRet[0]['sAllClass']); 
                        $sessionHandler->setSessionVariable('ClassBatchId',$studentRet[0]['batchId']);
                        $sessionHandler->setSessionVariable('ClassDegreeId',$studentRet[0]['degreeId']);
                        $sessionHandler->setSessionVariable('ClassBranchId',$studentRet[0]['branchId']);
                        $sessionHandler->setSessionVariable('ClassBranchId',$studentRet[0]['branchId']);
                        $sessionHandler->setSessionVariable('StudentMigrationStudyPeriod',$studentRet[0]['migrationStudyPeriod']); 
                        
                        $sessionHandler->setSessionVariable('StudentDOB',$studentRet[0]['dateOfBirth']);
                        $sessionHandler->setSessionVariable('StudentDOA',$studentRet[0]['dateOfAdmission']);    
                        

                        // update user's login time in user_log table
                        $loginManager->updateUserLogTimeIn();

                        if($returnStatus[0]['roleId']==3) {
                            // this is redundant but to make a check for parent, this is being used
                            $sessionHandler->setSessionVariable('ParentId',$returnStatus[0]['userId']);
                            //get parent name
                             $parentName=$loginManager->getParentName($returnStatus[0]['userId']);
                            //set parent name in seesion variable
                            $sessionHandler->setSessionVariable('ParentName',$parentName[0]['name']);
                            $sessionHandler->setSessionVariable('ParentType',$parentName[0]['parentType']);

                            // save array into session variable to populate all the students of one parent
                            $sessionHandler->setSessionVariable('StudentArray',$studentRet);
                            if(CURRENT_PROCESS_FOR =='sc')
                                redirectBrowser(UI_HTTP_PATH . "/Parent/scIndex.php");
                            else {
                                redirectBrowser(UI_HTTP_PATH . "/Parent/index.php");
                            }

                         }
                         else {
							if(trim($studentRet[0]['studentPhoto'])!=''){
							  $sessionHandler->setSessionVariable('userPhoto',"Student/".$studentRet[0]['studentPhoto']);
                            }
                            if(CURRENT_PROCESS_FOR =='sc')
                                redirectBrowser(UI_HTTP_PATH . "/Student/scIndex.php");
                            else {
                                redirectBrowser(UI_HTTP_PATH . "/Student/index.php");
                            }

                         }
                    }
                    else {

                        $sessionHandler->destroySession();
                        $errorMessage = "No student/parent found associated with this username.";
                        logError("$errorMessage : Username " . $REQUEST_DATA['username']." Trying to login" );
                    }
                }
                //else if($returnStatus[0]['roleId']==2) { // teacher
                else if($sessionHandler->getSessionVariable('RoleId')==2){
                    //get employee id and name
					//die("111");
                    $employeeRet = $loginManager->getEmployeeDetail($returnStatus[0]['userId']);
                    if(is_array($employeeRet) && count($employeeRet) >0) {
                        $sessionHandler->setSessionVariable('EmployeeId',$employeeRet[0]['employeeId']);
                        $sessionHandler->setSessionVariable('EmployeeName',$employeeRet[0]['employeeName']);
                        $sessionHandler->setSessionVariable('EmployeeCode',$employeeRet[0]['employeeCode']);
                        $sessionHandler->setSessionVariable('EmployeeEmail',$employeeRet[0]['emailAddress']);
                        $sessionHandler->setSessionVariable('EmployeeAbbreviation',$employeeRet[0]['employeeAbbreviation']);
                        $sessionHandler->setSessionVariable('LoggedName',$studentRet[0]['employeeName']);


								//pick default instituteId of employee and replace.
                                /*
								 $empInstituteArray = $loginManager->getEmployeeInstitute($returnStatus[0]['userId']);
								 if ($empInstituteArray[0]['instituteId'] != '') {
									 $sessionHandler->setSessionVariable('InstituteId',$empInstituteArray[0]['instituteId']);
									 $sessionHandler->setSessionVariable('InstituteAbbr',$empInstituteArray[0]['instituteAbbr']);
									 $sessionHandler->setSessionVariable('InstituteCode',$empInstituteArray[0]['instituteCode']);
									 $sessionHandler->setSessionVariable('InstituteName',$empInstituteArray[0]['instituteName']);
									 $sessionHandler->setSessionVariable('InstituteLogo',$empInstituteArray[0]['instituteLogo']);
								 }
                                */



                        //set session variable related to time table type
                        $timeTableRecord = $loginManager->getTimeTableLabelType($sessionHandler->getSessionVariable('EmployeeId'));
                        $sessionHandler->setSessionVariable('TeacherTimeTableLabelType',$timeTableRecord[0]['timeTableType']);

                        // update user's login time in user_log table
                        $loginManager->updateUserLogTimeIn();

                        if(trim($employeeRet[0]['employeeImage'])!=''){
						 $sessionHandler->setSessionVariable('userPhoto',"Employee/".$employeeRet[0]['employeeImage']);
                        }

                        if(CURRENT_PROCESS_FOR =='sc')
                            redirectBrowser(UI_HTTP_PATH . "/Teacher/scIndex.php");
                        else {
                            redirectBrowser(UI_HTTP_PATH . "/Teacher/index.php");
                        }
                    }
                    else {
                        $sessionHandler->destroySession();
                        $errorMessage = "The Institute And/or Session has not been recognised.";
                        logError("InstituteId " . $REQUEST_DATA['instituteId'] . ", sessionId " . $REQUEST_DATA['sessionId'] . " trying to login");
                    }
                }
				//else if($sessionHandler->getSessionVariable('RoleId')==5) { // Management
                else if($sessionHandler->getSessionVariable('RoleId')==5){

                    //get employee id and name

                    $employeeRet = $loginManager->getEmployeeDetail($returnStatus[0]['userId']);
                    if(is_array($employeeRet) && count($employeeRet) >0) {
                        $sessionHandler->setSessionVariable('EmployeeId',$employeeRet[0]['employeeId']);
                        $sessionHandler->setSessionVariable('EmployeeName',$employeeRet[0]['employeeName']);
                        $sessionHandler->setSessionVariable('EmployeeCode',$employeeRet[0]['employeeCode']);
                        $sessionHandler->setSessionVariable('EmployeeEmail',$employeeRet[0]['emailAddress']);
                        $sessionHandler->setSessionVariable('EmployeeAbbreviation',$employeeRet[0]['employeeAbbreviation']);                         // update user's login time in user_log table
                        $loginManager->updateUserLogTimeIn();


                        if(CURRENT_PROCESS_FOR =='sc')
                            redirectBrowser(UI_HTTP_PATH . "/Management/scIndex.php");
                        else {
                            redirectBrowser(UI_HTTP_PATH . "/Management/index.php");
                        }
                    }
                    else {
                        $sessionHandler->destroySession();
                        $errorMessage = "The Institute And/or Session has not been recognised.";
                        logError("InstituteId " . $REQUEST_DATA['instituteId'] . ", sessionId " . $REQUEST_DATA['sessionId'] . " trying to login");
                    }
                }
                else { // administrator
                    // to display Name along with username
                    $employeeRet = $loginManager->getEmployeeDetail($returnStatus[0]['userId']);
                    if(is_array($employeeRet) && count($employeeRet) >0) {
                      $sessionHandler->setSessionVariable('EmployeeName',$employeeRet[0]['employeeName']);
                    }

                    // update user's login time in user_log table
                    $loginManager->updateUserLogTimeIn();

                    $sessionHandler->setSessionVariable('AdminId',$returnStatus[0]['userId']);
                    redirectBrowser(UI_HTTP_PATH . "/indexHome.php");
                    // role permissions section goes here
                }
            }
            else {
                $sessionHandler->destroySession();
                $errorMessage = "The Institute And/or Session has not been recognised.";
                logError("InstituteId " . $REQUEST_DATA['instituteId'] . ", sessionId " . $REQUEST_DATA['sessionId'] . " trying to login");
            }
		}
		else {
            $sessionHandler->destroySession();
            $errorMessage = "That username and/or password has not been recognised";
            logError("user " . $REQUEST_DATA['username'] . " trying to login");
		}
	}
}


function getFeeList($resultArray,$classId) {    
    
      global $studentPendingDuesManager;
      global $sessionHandler; 
      global $recCount;
      
      
      $valueArray = array();
      
      $studentId = $resultArray['studentId']; 
      $quotaId = $resultArray['quotaId']; 
      $isLeet = $resultArray['isLeet'];  
      $tIsLeet=2; 
      if($isLeet==1) {
        $tIsLeet=1;  
      } 
      
      $conessionFormatId =  $sessionHandler->getSessionVariable('CONCESSION_FORMAT'); 
      if($conessionFormatId=='') {
        $conessionFormatId=1;  
      }                                      
      $conessionFormatId=3;  
      
      
      // Findout Student Details
      $condition = " AND stu.studentId='".$studentId."'";
      
      $studentFeesArray = $studentPendingDuesManager->getStudentDetailClass($condition,$classId);     
      if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {
         if($studentFeesArray[0]['feeClassId']==-1) {
            return 0;
         }
      }  
      else {
         return 0;
      }
      $resultArray['feeClassName']=$studentFeesArray[0]['feeClassName'];
      
      // Check Adhoc Concession 
      $adhocConcession=0; 
      $adhocCondition = " feeClassId = '$classId' AND studentId = '$studentId' "; 
      $adhocConcessionArray = $studentPendingDuesManager->getCheckStudentConcession($adhocCondition); 
      if(is_array($adhocConcessionArray) && count($adhocConcessionArray)>0) {
         $adhocConcession = 1; 
         $conessionFormatId = 4;
      }  
      
      
      // ======== Prev Dues START ===========
            $resultArray['prevDues']=0;
            $showTDuesAmt=0;
            $prevCondition = " AND fsf.studentId = '$studentId' AND fsf.classId <= '$classId' ";  
            $prevClassFeeArray = $studentPendingDuesManager->getPendingDuesList($prevCondition);  
            for($i=0; $i<count($prevClassFeeArray); $i++) {
               if($prevClassFeeArray[$i]['dues'] != $prevClassFeeArray[$i]['paid']) {
                  $srNo=$rSrNo;  
                  $duesClassId = $prevClassFeeArray[$i]['classId'];
                  $duesAmt = $prevClassFeeArray[$i]['dues'];
                  if($duesAmt=='') {
                    $duesAmt=0;  
                  }
                  $showTDuesAmt += doubleval($duesAmt);
               }
            }
            $resultArray['prevDues']=$showTDuesAmt;
      // ======== Prev Dues END ===========
      
      
      // ======== Acadmeic  START ===========
            $resultArray['academicDues']=0;
            $showTFeeAmt=0;
            $feeId = "-1";
            $havingConditon = " COUNT(fhv.feeHeadId) = 1 "; 
            $foundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,$havingConditon);
            for($i=0; $i<count($foundArray); $i++) {
              $feeId .=",".$foundArray[$i]['feeId'];  
            }        
            
            $havingConditon = " COUNT(fhv.feeHeadId) >= 2"; 
            $isLeetCheck = "1,2,3";
            $foundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,$havingConditon,'',0,$isLeetCheck); 
            for($i=0; $i<count($foundArray); $i++) {
               $tFeeHeadId = $foundArray[$i]['feeHeadId']; 
               if($quotaId!='') {
                  $feeHeadCondition = " AND fhv.quotaId = $quotaId AND fhv.feeHeadId = $tFeeHeadId";  
                  $quotaFoundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                  if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                    $feeId .=",".$quotaFoundArray[0]['feeId'];  
                  }
                  else {
                    $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                    $quotaFoundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                    if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                      $feeId .=",".$quotaFoundArray[0]['feeId'];  
                    }
                    else {
                       $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                       $quotaFoundArray = $studentPendingDuesManager->getCountFeeHeadNew($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                       if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                         $feeId .=",".$quotaFoundArray[0]['feeId'];  
                       }
                    }
                  }
               }
               else {
                 $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                 $quotaFoundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                 if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                   $feeId .=",".$quotaFoundArray[0]['feeId'];  
                 } 
               }
            }        
            if($feeId=='') {
              $feeId = "-1"; 
            }

            //================================FEE HEAD DETAILS (Start)=======================================
            $foundArray = $studentPendingDuesManager->getStudentFeeHeadDetail($classId,$feeId,$studentId);
            $feeHeadIds = "-1";
            for($i=0;$i<count($foundArray);$i++) {
              $feeHeadIds .= ",".$foundArray[$i]['feeHeadId'];   
            }
         
            // Student Concession Findout is Leet & Non Leet Base 
            $concessionArray = $studentPendingDuesManager->getStudentConcession($classId,$studentId,$feeHeadIds,$tIsLeet,$condition='');
            $concessionFeeHeadIds = "-1"; 
            for($i=0;$i<count($concessionArray);$i++) {
              $concessionFeeHeadIds .= ",".$concessionArray[$i]['feeHeadId'];   
            }
            
            $concessionCondition = " AND fcv.feeHeadId NOT IN ($concessionFeeHeadIds)";
            $concessionFinalArray = $studentPendingDuesManager->getStudentFinalConcession($classId,$studentId,$feeHeadIds,$tIsLeet,$concessionCondition);
             
            $i=0;
            $concession=0;    
            for($i=0; $i<count($foundArray); $i++) {
               $feeHeadDetailFind=1;
               $foundArray[$i]['concession']=0;
               $feeId = $foundArray[$i]['isVariable'].'_'.$foundArray[$i]['feeId'];
               $totalFees +=$foundArray[$i]['feeHeadAmt'];
               $salFeeHeadId =  $foundArray[$i]['feeHeadId'];
               
               $concession =0;     
               // Categories wise Concession 
               if($adhocConcession==1) {
                  for($jj=0;$jj<count($adhocConcessionArray);$jj++) {
                     if($adhocConcessionArray[$jj]['feeHeadId']==$salFeeHeadId) {  
                       $concession = $adhocConcessionArray[$jj]['concessionAmount']; 
                     }
                  }
               }
               else if($adhocConcession==0) {
                   $maxConcession = 0;
                   $minConcession = 0;
                   $reducingConcession = 0;   
                   $chk=0;               
                   for($jj=0;$jj<count($concessionFinalArray);$jj++) {
                      if($concessionFinalArray[$jj]['feeHeadId']==$salFeeHeadId) {
                          if($concessionFinalArray[$jj]['concessionType']=='2') {
                            $concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
                            if($chk==1) {
                              $reducingConcession = doubleval($reducingConcession) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
                            }
                          }
                          if($concessionFinalArray[$jj]['concessionType']=='1') {
                            $concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - (doubleval($foundArray[$i]['feeHeadAmt']) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
                            if($chk==1) {
                              $reducingConcession = doubleval($reducingConcession) - (doubleval($reducingConcession) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
                            }
                          }
                         
                          if($chk==0) {
                             $maxConcession = $concessionAmt;
                             $minConcession = $concessionAmt; 
                             $reducingConcession = $concessionAmt;
                          }
                          if($concessionAmt < $maxConcession) {
                            $maxConcession = $concessionAmt;  
                          }
                          if($concessionAmt > $minConcession) {
                            $minConcession = $concessionAmt;  
                          }
                          $chk=1;        
                      }
                   }
                   
                   if($conessionFormatId==1) {
                     $concession = $maxConcession; 
                   }
                   if($conessionFormatId==2) {
                     $concession = $minConcession; 
                   }
                   if($conessionFormatId==3) {
                     $concession = $reducingConcession; 
                   }
               }
               
               if($concession==0 || $concession=='') {
                 $conn = 0;    
               }
               else {
                  if($adhocConcession==0) { 
                    $conn = doubleval($foundArray[$i]['feeHeadAmt'])-doubleval($concession);
                  }
                  else {
                    $conn = doubleval($concession);  
                  }
               }
               $foundArray[$i]['concession'] = $conn;
               $totalConcession += doubleval($foundArray[$i]['concession']);  
               $feesAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($foundArray[$i]['concession']);
               $showTFeeAmt +=$feesAmt;
            }
            $resultArray['academicDues']=$showTFeeAmt;
      // ======== Acadmeic END ===========
      
      
     // ======== Transport  START ===========    
         $resultArray['transportDues']=0;
         $showTransportAmt=0;
         $condition  = " fsf.studentId = $studentId AND fsf.classId = '$classId' AND IFNULL(fsf.facilityType,'') = 1 ";    
         $facilityArrayCheck = $studentPendingDuesManager->getFacility($condition);   
         if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
            $trCharges = $facilityArrayCheck[0]['charges'];
            $trConcession = $facilityArrayCheck[0]['concession'];
            $showTransportAmt = doubleval($trCharges) - doubleval($trConcession);
         }
         $resultArray['transportDues']=$showTransportAmt;
     // ======== Transport  END ===========     
      
      
     // ======== Hostel  START ===========    
         $resultArray['hostelDues']=0;
         $showHostelAmt=0;
         $condition  = " fsf.studentId = $studentId AND fsf.classId = '$classId' AND IFNULL(fsf.facilityType,'') = 2 ";                    
         $facilityArrayCheck = $studentPendingDuesManager->getFacility($condition);  
         if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
            $trCharges = $facilityArrayCheck[0]['charges'];
            $trConcession = $facilityArrayCheck[0]['concession'];
            $showHostelAmt = doubleval($trCharges) - doubleval($trConcession);
         }
         $resultArray['hostelDues']=$showHostelAmt;
     // ======== Hostel  END ===========     
     
     
       //$condition  = " fsf.studentId = $studentId AND fsf.classId = '$classId' AND IFNULL(fsf.facilityType,'') = 2 ";                    
       //$prevFeeArray= $studentPendingDuesManager->getPreviousFeePaymentDetail(
       
       $condition  = " AND f.studentId = $studentId AND f.feeClassId = '$classId'"; 
       $paidArray = $studentPendingDuesManager->getPreviousFeePaymentDetail($condition); 
       
       $hostelPaid=0;
       $transportPaid=0;
       $feePaid=0;
       $duesPaid=0;
       for($i=0;$i<count($paidArray);$i++) {
          $showTFeeAmt += doubleval($paidArray[$i]['prevFeeFine']);
          $showTransportAmt += doubleval($paidArray[$i]['prevTransportFine']); 
          $showHostelAmt += doubleval($paidArray[$i]['prevHostelFine']); 
          
          $hostelPaid += doubleval($paidArray[$i]['prevHostelPaid']);  
          $transportPaid+= doubleval($paidArray[$i]['prevTransportPaid']);  
          $feePaid += doubleval($paidArray[$i]['prevFeePaid']);  
          $duesPaid += doubleval($paidArray[$i]['prevDuesPaid']);  
       }
       
       $resultArray['prevDues']=doubleval($showTDuesAmt)-doubleval($duesPaid);
       $resultArray['academicDues']=doubleval($showTFeeAmt)-doubleval($feePaid);
       $resultArray['transportDues']=doubleval($showTransportAmt)-doubleval($transportPaid );
       $resultArray['hostelDues']=doubleval($showHostelAmt)-doubleval($hostelPaid);
       
       $total = doubleval($showTDuesAmt)+doubleval($showTFeeAmt)+doubleval($showTransportAmt)+doubleval($showHostelAmt);
       $paid = doubleval($duesPaid)+doubleval($feePaid)+doubleval($transportPaid)+doubleval($hostelPaid);
       
       $net = doubleval($total) -  doubleval($paid); 
       
       $resultArray['total'] = doubleval($resultArray['prevDues'])+doubleval($resultArray['academicDues'])+doubleval($resultArray['transportDues'])+doubleval($resultArray['hostelDues']);
      
       if(doubleval($net)!=0) {
       
         $resultArray['academicDues']=$resultArray['academicDues']." ";
         $resultArray['transportDues']=$resultArray['transportDues']." ";
         $resultArray['hostelDues']=$resultArray['hostelDues']." ";
         $resultArray['prevDues']=$resultArray['prevDues']." ";
         $resultArray['total']=$resultArray['total']." ";
         
         $valueArray = array_merge(array('srNo' => ($recCount+1)), $resultArray);
         $recCount++;
         return $valueArray;
       } 
       return 0;
}
?>
