<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A STUDENT
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
   

    global $sessionHandler;
    $admitOptionalField = $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD');
    
    function clearSpecialChar($text) {
       if($text!="") {
         $text=strtolower($text);
         $code_entities_match = array(' ');
         $code_entities_replace = array('');
         $text = str_replace($code_entities_match, $code_entities_replace, $text);
       }
       return $text;
    }    
$migratedStudyPeriod =urldecode($REQUEST_DATA['migratedStudyPeriod']);
   /* if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteName']) || trim($REQUEST_DATA['instituteName']) == '')) {
        $errorMessage .= "Enter institute name \n";
    }
    if (!isset($REQUEST_DATA['instituteCode']) || trim($REQUEST_DATA['instituteCode']) == '') {
        $errorMessage .= "Enter institute code \n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteAbbr']) || trim($REQUEST_DATA['instituteAbbr']) == '')) {
        $errorMessage .= "Enter institute abbreviation \n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteEmail']) || trim($REQUEST_DATA['instituteEmail']) == '')) {
        $errorMessage .= "Enter institute email \n";
    }
    */
    //print_r($REQUEST_DATA);
	//die('line'.__LINE__);
	$hostelFacility = urldecode($REQUEST_DATA['hostelFacility']);
	//$completedGraduation = $REQUEST_DATA['completedGraduation'];
	
    if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/StudentManager.inc.php");
		$studentManager = StudentManager::getInstance();
		
        if($admitOptionalField==1) {
           // To Optional Field Added 
           $optionalFieldStatus = $studentManager->insertOptionalFieldStudent(urldecode($REQUEST_DATA[studentId]));
           if($optionalFieldStatus===false){
              $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
              echo FAILURE;
              die;
           }
        }


		//checking role permission for edit 
		
		global $sessionHandler;
        $roleId=$sessionHandler->getSessionVariable('RoleId');
					
        /* START: to check rollno*/
		$userRollArr = $studentManager->checkStudentRollNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentRoll]))),urldecode($REQUEST_DATA[studentId]));	
		if($userRollArr[0]['rollExists'] && $REQUEST_DATA[studentRoll]!='')
		{
			echo "Student roll number already exists";
			return false;
		}
		/* END: to check rollno*/

		/* START: to check rollno in quarantine table*/
		$userRollArr = $studentManager->checkStudentQuarantineRollNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentRoll]))),urldecode($REQUEST_DATA[studentId]));	
		if($userRollArr[0]['rollExists'] && urldecode($REQUEST_DATA[studentRoll])!='')
		{
			echo "Student roll number already exists in deleted Records";
			return false;
		}
		/* END: to check rollno in quarantine table*/
        
        
		/* START: to check reg no*/
		$userRegArr = $studentManager->checkStudentRegNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentReg]))),urldecode($REQUEST_DATA[studentId]));	
		if($userRegArr[0]['regExists'] && urldecode($REQUEST_DATA[studentReg])!='')
		{
			echo "College reg no. already exists";
			return false;
		}
		/* END: to check univ no*/

		/* START: to check reg no quarantine table*/
		$userRegArr = $studentManager->checkStudentQuarantineRegNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentReg]))),urldecode($REQUEST_DATA[studentId]));	
		if($userRegArr[0]['regExists'] && urldecode($REQUEST_DATA[studentReg])!='')
		{
			echo "College reg no. already exists in deleted records";
			return false;
		}
		/* END: to check univ no quarantine table*/

        
        /* START: to check univ no*/
		$userRegArr = $studentManager->checkStudentUnivNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentUniversityNo]))),urldecode($REQUEST_DATA[studentId]));	
		if($userRegArr[0]['univExists'] && urldecode($REQUEST_DATA[studentUniversityNo])!='')
		{
			echo "University no. already exists";
			return false;
		}
		/* END: to check univ no*/
        
        
		/* START: to check univ no quarantine table*/
		$userRegArr = $studentManager->checkStudentQuarantineUnivNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentUniversityNo]))),urldecode($REQUEST_DATA[studentId]));	     
                if($userRegArr[0]['univExists'] && urldecode($REQUEST_DATA[studentUniversityNo])!='')
		{
            echo "University no. already exists in deleted records";     
            return false;
		}
		/* END: to check univ no quarantine table*/
       

           /* START: to check uni reg no*/
		$userRegArr = $studentManager->checkStudentUnivRegNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentUniversityRegNo]))),urldecode($REQUEST_DATA[studentId]));	
		if($userRegArr[0]['univRegExists'] && $REQUEST_DATA[studentUniversityRegNo]!='')
		{
			echo "University reg no. already exists";
			return false;
		}
		/* END: to check univ no*/

   /* START: to check uni reg no quarantine table*/
		$userRegArr = $studentManager->checkStudentQuarantineUnivRegNo(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentUniversityRegNo]))),urldecode($REQUEST_DATA[studentId]));	
		if($userRegArr[0]['univRegExists'] && urldecode($REQUEST_DATA[studentUniversityRegNo])!='')
		{
			echo "University reg no. already exists in deleted records";
			return false;
		}
		/* END: to check univ no quarantine table*/

		/* START: to check fee receipt no*/
		$userRegArr = $studentManager->checkStudentFeeReceipt(trim(urldecode($REQUEST_DATA[feeReceiptNo])),urldecode($REQUEST_DATA[studentId]));	
		if($userRegArr[0]['studentFeeReceipt'] && urldecode($REQUEST_DATA[feeReceiptNo])!='')
		{
			echo "Fee receipt no. already exists";
			return false;
		}
		/* END: to check fee receipt no*/

		/* START: to check fee receipt no quarantine table*/
		$userRegArr = $studentManager->checkStudentQuarantineFeeReceipt(trim(urldecode($REQUEST_DATA[feeReceiptNo])),urldecode($REQUEST_DATA[studentId]));	
		if($userRegArr[0]['studentFeeReceipt'] && urldecode($REQUEST_DATA[feeReceiptNo])!='')
		{
			echo "Fee receipt no. already exists in deleted records";
			return false;
		}
		
        /*
		//if($hostelFacility != 1) {
		$studentHostelArr = $studentManager->checkHostelStudent($REQUEST_DATA[studentId]);
		if($studentHostelArr[0]['hostelId'] != '' AND $studentHostelArr[0]['hostelRoomId'] != '') {
			if($hostelFacility == 0) {
				echo "This student is already check in, if you want to change student hostel facility then please make the student checkout";
				return false;
			}
		}
        */

		//die('line'.__LINE__);
		//}

		/* END: to check fee receipt no quarantine table*/

		/* START: managing user details of student */
		$studentUserId = urldecode($REQUEST_DATA[studentUserId]);
		$studentUser   = urldecode($REQUEST_DATA[studentUser]);
		$passwordId    = urldecode($REQUEST_DATA[studentPassword]);
		$studentPref   =''; 
		if($studentUser){

			$userExistArr = $studentManager->checkUser($studentUser);
            
			if($userExistArr[0]['userExists']){

				/* function to fetch user id*/
				$stUserId1 = $studentManager->getFieldNullValue("user","userId",$studentUser,"userName");
				$stUserId  = $stUserId1[0]['userId'];
				 
				/* function to fetch student userid from table*/
				$studentUserId = $studentManager->getFieldNullValue("student","userId",urldecode($REQUEST_DATA[studentId]),"studentId");
				
				if($stUserId1[0]['userId'] == $studentUserId[0]['userId']){
					if($passwordId!='1****1'){
						$studentManager->updateUser($passwordId,$stUserId);
                        
                        $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$stUserId);
                        if(count($foundUserRole) == 0 ) {
                           // Insert User Role 
                           $returnStatus = $studentManager->insertStudentParentUserRole($stUserId,4); 
                           if($returnStatus===false) {
                             echo FAILURE;
                             die;
                           }
                        }
					} 
				}
				else{
					
					echo "Student username already exists";
					return false;
				}
			}
			else{
				$stUserId = $studentManager->insertUser($studentUser,$passwordId,4);
                if($stUserId===false) {
                    echo FAILURE;
                    die;
                }   
                // Insert User Role 
                $userId=SystemDatabaseManager::getInstance()->lastInsertId();      
                $returnStatus = $studentManager->insertStudentParentUserRole($userId,4); 
                if($returnStatus===false) {
                   echo FAILURE;
                   die;
                }
			}
		}
		else
		{
			$stUserId = '';
		} 
		/* END: managing user details of student */


		/* START: managing user details of father */
		$fatherUserId   = urldecode($REQUEST_DATA[fatherUserId]);
		$fatherUserName = urldecode($REQUEST_DATA[fatherUserName]);
		$fatherPassword = urldecode($REQUEST_DATA[fatherPassword]);
		 
		if($fatherUserName)
		{
			$userExistArr = $studentManager->checkUser($fatherUserName);	
			if($userExistArr[0]['userExists'])
			{
				/* function to fetch user id*/
				$stUserId1 = $studentManager->getFieldNullValue("user","userId",$fatherUserName,"userName");
				$fatherId  = $stUserId1[0]['userId'];
				 
				/* function to fetch father userid from table*/
				$studentUserId = $studentManager->getFieldNullValue("student","fatherUserId",urldecode($REQUEST_DATA[studentId]),"studentId");
				if($stUserId1[0]['userId'] == $studentUserId[0]['fatherUserId'])
				{
					if($fatherPassword!='1****1') {
					   $studentManager->updateUser($fatherPassword,$fatherId);
                       $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$fatherId);
                       if(count($foundUserRole) == 0 ) {
                           // Insert User Role 
                           $returnStatus = $studentManager->insertStudentParentUserRole($fatherId,3); 
                           if($returnStatus===false) {
                             echo FAILURE;
                             die;
                           }
                       }
                    }
				}
				else
				{
					echo "Father username already exists";
					return false;
				}
			}
			else
			{
				$fatherId = $studentManager->insertUser($fatherUserName,$fatherPassword,3);
                if($fatherId===false) {
                    echo FAILURE;
                    die;
                }   
                // Insert User Role 
                $userId=SystemDatabaseManager::getInstance()->lastInsertId();      
                $returnStatus = $studentManager->insertStudentParentUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE;
                   die;
                }
			}
		}
		else
		{
			$fatherId = '';
		} 
		/* START: managing user details of father */

        
		/* START: managing user details of mother */
		$motherUserId   = urldecode($REQUEST_DATA[motherUserId]);
		$motherUserName = urldecode($REQUEST_DATA[motherUserName]);
		$motherPassword = urldecode($REQUEST_DATA[motherPassword]);
		 
		if($motherUserName)
		{
			$userExistArr = $studentManager->checkUser($motherUserName);	
			if($userExistArr[0]['userExists'])
			{
				/* function to fetch user id*/
				$stUserId1 = $studentManager->getFieldNullValue("user","userId",$motherUserName,"userName");
				$motherId  = $stUserId1[0]['userId'];
				 
				/* function to fetch mother userid from table*/
				$studentUserId = $studentManager->getFieldNullValue("student","motherUserId",urldecode($REQUEST_DATA[studentId]),"studentId");
				
				if($stUserId1[0]['userId'] == $studentUserId[0]['motherUserId'])
				{
					if($motherPassword!='1****1') {
					  $studentManager->updateUser($motherPassword,$motherId);
                      $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$motherId);
                       if(count($foundUserRole) == 0 ) {
                           // Insert User Role 
                           $returnStatus = $studentManager->insertStudentParentUserRole($motherId,3); 
                           if($returnStatus===false) {
                             echo FAILURE;
                             die;
                           }
                       }
                    }
					 
				}
				else
				{
					echo "Mother username already exists";
					return false;
				}
			}
			else
			{
				$motherId = $studentManager->insertUser($motherUserName,$motherPassword,3);
                if($motherId===false) {
                    echo FAILURE;
                    die;
                }   
                // Insert User Role 
                $userId=SystemDatabaseManager::getInstance()->lastInsertId();      
                $returnStatus = $studentManager->insertStudentParentUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE;
                   die;
                }
			}
		}
		else
		{
			$motherId = '';
		} 
		/* START: managing user details of mother */
		
		/* START: managing user details of guardian */
		$guardianUserId   = urldecode($REQUEST_DATA[guardianUserId]);
		$guardianUserName = urldecode($REQUEST_DATA[guardianUserName]);
		$guardianPassword = urldecode($REQUEST_DATA[guardianPassword]);
		 
		if($guardianUserName)
		{
			$userExistArr = $studentManager->checkUser($guardianUserName);	
			if($userExistArr[0]['userExists'])
			{
				/* function to fetch user id*/
				$stUserId1 = $studentManager->getFieldNullValue("user","userId",$guardianUserName,"userName");
				$guardianId  = $stUserId1[0]['userId'];
				 
				/* function to fetch guardian userid from table*/
				$studentUserId = $studentManager->getFieldNullValue("student","guardianUserId",urldecode($REQUEST_DATA[studentId]),"studentId");
				
				if($stUserId1[0]['userId'] == $studentUserId[0]['guardianUserId'])
				{
					if($guardianPassword!='1****1') { 
                       $studentManager->updateUser($guardianPassword,$guardianId);
                       $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$guardianId);
                       if(count($foundUserRole) == 0 ) {
                           // Insert User Role 
                           $returnStatus = $studentManager->insertStudentParentUserRole($guardianId,3); 
                           if($returnStatus===false) {
                             echo FAILURE;
                             die;
                           }
                       }
                    }
					 
				}
				else
				{
					echo "Guardian username already exists";
					return false;
				}
			}
			else
			{
				$guardianId = $studentManager->insertUser($guardianUserName,$guardianPassword,3);
                if($guardianId===false) {
                    echo FAILURE;
                    die;
                }   
                // Insert User Role 
                $userId=SystemDatabaseManager::getInstance()->lastInsertId();      
                $returnStatus = $studentManager->insertStudentParentUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE;
                   die;
                }
			}
		}
		else
		{
			$guardianId = '';
		} 
		//ADD SaLLCLASS IN STUDENT TABLE
			 $studentClassStatus = $studentManager->updateEditStudentAllClasses(urldecode($REQUEST_DATA['studentId']));
			 if($studentClassStatus===false){
               $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
               echo FAILURE;
            } 
			 $allClass ='';
			 for($i=0;$i<count($studentClassStatus);$i++){			 
			 if($allClass !=''){
			 	$allClass .='~';				
			 }	
				$allClass .=$studentClassStatus[$i]['classId'];	
			 }
            $sAllClass ="'~".$allClass."~'";	//SS:17-Sep-13:Original $sAllClass ="~".$allClass."~"
			
			
		$studentManager->updateStudent($stUserId,$fatherId,$motherId,$guardianId,$sAllClass);
		if($returnStatus === false) {
			$errorMessage = FAILURE;
		}

		$regularAilment	= urldecode($REQUEST_DATA['medicalAttention']);

		$familyAilment	= urldecode($REQUEST_DATA['familyAilment']);
		//$regularAilmentNo	= $REQUEST_DATA['regularAilmentNo'];
		$cnt = count($familyAilment);
		if($cnt > 0 AND is_array($familyAilment)) { 
		 $familyAilmentList = implode(",",$familyAilment);
		}

		if($regularAilment == 1) {
			//$regularAilment = $REQUEST_DATA['regularAilmentYes'];
			$natureAilment = add_slashes(urldecode($REQUEST_DATA['natureAilment']));
			$familyAilment = $familyAilmentList;
			$otherAilment = add_slashes(urldecode($REQUEST_DATA['otherAilment']));
		
		}
		else {
			$natureAilment = '';
			$familyAilment = '';
			$otherAilment = '';
		}

		$studentId = urldecode($REQUEST_DATA['studentId']);

		$getStudentAilment = $studentManager->getStudentAilment($studentId);
		if($getStudentAilment[0]['totalRecords'] == 0) {
			$studentManager->insertStudentAilment($studentId,$regularAilment,$natureAilment,$familyAilment,$otherAilment);	
		}
		else {
			$studentManager->updateStudentAilment($regularAilment,$natureAilment,$familyAilment,$otherAilment,$studentId);
		}
		if($returnStatus === false) {
			$errorMessage = FAILURE;
		}
		else {
			//Id to upload logo

			
			$rollNo		= $REQUEST_DATA['rollNo'];
			$session	= $REQUEST_DATA['session'];
			$institute	= $REQUEST_DATA['institute'];
			$board		= $REQUEST_DATA['board'];
			$marks		= $REQUEST_DATA['marks'];
			$maxMarks	= $REQUEST_DATA['maxMarks'];
            $educationStream    = $REQUEST_DATA['educationStream'];
			$percentage	= $REQUEST_DATA['percentage'];
			$previousClass	= $REQUEST_DATA['previousClass'];
			
			$studentManager->insertStudentAcademics($rollNo,$session,$institute,$board,$marks,$maxMarks,$percentage,$educationStream,$previousClass,urldecode($REQUEST_DATA[studentId]));

			$sessionHandler->setSessionVariable('IdToFileUpload',urldecode($REQUEST_DATA['id'])); 
			if($stUserId=='')
				$stUserId = '';
			if($fatherId=='')
				$fatherId = '';
			if($motherId=='')
				$motherId = '';
			if($guardianId=='')
				$guardianId = '';
			echo SUCCESS.'~'.$stUserId.'~'.$fatherId.'~'.$motherId.'~'.$guardianId;
		}
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10-04-16   Time: 12:29p
//Created in $/LeapCC/Library/Student
//added again as file was corrupted
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 10-04-09   Time: 4:12p
//Updated in $/LeapCC/Library/Student
//commented university roll no check from quarantine student table
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 09-10-24   Time: 4:28p
//Updated in $/LeapCC/Library/Student
//fixed bug no 0001821,0001880,0001816,0001852,0001851,0001637,0001329,00
//01244,0001855
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 09-10-12   Time: 11:53a
//Updated in $/LeapCC/Library/Student
//Updated with Access right parameters
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/11/09    Time: 1:47p
//Updated in $/LeapCC/Library/Student
//Reviewed the files and updated the formatting
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 7/24/09    Time: 11:41a
//Updated in $/LeapCC/Library/Student
//Added roll no, univ roll no, reg no and univ reg no validation on
//student quaratine table
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 7/22/09    Time: 11:31a
//Updated in $/LeapCC/Library/Student
//fixed issue no 646 and 647
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/20/09    Time: 4:02p
//Updated in $/LeapCC/Library/Student
//Fixed bugs and enhancements 0000616-0000620
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/30/09    Time: 11:38a
//Updated in $/LeapCC/Library/Student
//removed issue of motheruser name already exists
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 16/06/09   Time: 18:51
//Updated in $/LeapCC/Library/Student
//corrected codes
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Library/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Library/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:52p
//Updated in $/LeapCC/Library/Student
//added Offense tab
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 11/06/08   Time: 11:57a
//Updated in $/Leap/Source/Library/ScStudent
//updated with "Access" rights parameter
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/23/08    Time: 11:21a
//Updated in $/Leap/Source/Library/ScStudent
//changed the roles of parent
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/20/08    Time: 8:48p
//Updated in $/Leap/Source/Library/ScStudent
//updated with username validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/16/08    Time: 5:20p
//Updated in $/Leap/Source/Library/ScStudent
//updated model file name
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/06/08    Time: 2:30p
//Created in $/Leap/Source/Library/ScStudent
//intial checkin
?>
