<?php
//-------------------------------------------------------
// Purpose: To store the records of subject to class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	$commonAttendanceArr = CommonQueryManager::getInstance();
	//echo $_REQUEST['id'];
	 
    if(UtilityManager::notEmpty($_REQUEST['id'])) 
	{
		$studentId  = trim($_REQUEST['id']);
		if (empty($studentId)) {
			redirectBrowser(UI_HTTP_PATH.'/searchStudent.php');
		}
		$studentId = add_slashes($studentId);

		//check if student belongs to current institute.
		$studentInstituteArray = $studentManager->getStudentClassInstitute($studentId);
		$studentInstituteId = $studentInstituteArray[0]['instituteId'];

		global $sessionHandler;
		if ($studentInstituteId != $sessionHandler->getSessionVariable('InstituteId') or empty($studentInstituteId)) {
			redirectBrowser(UI_HTTP_PATH.'/searchStudent.php');
		}

		 // student Id for photo name
		 
		$sessionHandler->setSessionVariable('IdToFileUpload',$studentId); 

		$studentDataArr = $studentManager->getStudentData($studentId);
		/*
		echo '<pre>';
		print_r($studentDataArr);
		die;
		*/
		$corrAddress1 = $studentDataArr[0]['corrAddress1'];
		$corrAddress2 = $studentDataArr[0]['corrAddress2'];
		$permAddress1 = $studentDataArr[0]['permAddress1'];
		$permAddress2 = $studentDataArr[0]['permAddress2'];
		if($studentDataArr[0]['hostelRoomId']!=''){
		  
		  $studentRoomFacilitiesArr = $studentManager->getRoomFacilitiesData(' AND hs.studentId='.$studentDataArr[0]['studentId'].' AND hr.hostelRoomId='.$studentDataArr[0]['hostelRoomId'].' AND hs.dateOfCheckOut="0000-00-00"');
		  if(count($studentRoomFacilitiesArr)>0 and is_array($studentRoomFacilitiesArr)){
			  $studentRoomFacilitiesArr[0]['roomType']=trim($studentRoomFacilitiesArr[0]['roomType'])!='' ? $studentRoomFacilitiesArr[0]['roomType'] : NOT_APPLICABLE_STRING;
			  $studentRoomFacilitiesArr[0]['airConditioned']=trim($studentRoomFacilitiesArr[0]['airConditioned'])!='' ? $studentRoomFacilitiesArr[0]['airConditioned'] : NOT_APPLICABLE_STRING;
			  $studentRoomFacilitiesArr[0]['attachedBath']=trim($studentRoomFacilitiesArr[0]['attachedBath'])!='' ? $studentRoomFacilitiesArr[0]['attachedBath'] : NOT_APPLICABLE_STRING;
			  $studentRoomFacilitiesArr[0]['internetFacility']=trim($studentRoomFacilitiesArr[0]['internetFacility'])!='' ? $studentRoomFacilitiesArr[0]['internetFacility'] : NOT_APPLICABLE_STRING;
		  }
		}

		$studentAilmentDataArr = $studentManager->getStudentAilmentData($studentId);


		$studentClassArr = $studentManager->getStudentClass($studentDataArr[0]['classId']);
		$inActiveClass = $studentClassArr[0]['isActive']!=1 ? "READONLY = 'READONLY'" : "";
		$disableClass = $studentClassArr[0]['isActive']!=1 ? "DISABLED = 'TRUE'" : "";

		/* function to fetch student user details*/ 
		if($studentDataArr[0]['userId'])
		{
			$studentUserDataArr = $studentManager->getUserData($studentDataArr[0]['userId']);
		}
		 
		/* function to fetch student father details*/ 
		if($studentDataArr[0]['fatherUserId'])
		{
			$fatherUserDataArr = $studentManager->getUserData($studentDataArr[0]['fatherUserId']);
		}

		//print_r($fatherUserDataArr);
		/* function to fetch student mother details*/ 
		if($studentDataArr[0]['motherUserId'])
		{
			$motherUserDataArr = $studentManager->getUserData($studentDataArr[0]['motherUserId']);
		}
		
		/* function to fetch student guardian details*/ 
		if($studentDataArr[0]['guardianUserId'])
		{
			$guardianUserDataArr = $studentManager->getUserData($studentDataArr[0]['guardianUserId']);
		}
		
		$classStr = $studentClassArr[0]['className'];
		$clasStudyPeriodId = $studentClassArr[0]['studyPeriodId'];
	 
		$classArr = explode("-",$classStr);
		$batch = $classArr[0];
		$university = $classArr[1];
		$degree = $classArr[2];
		$branch = $classArr[3];
		
		$dateOfBirth	= $studentDataArr[0]['dateOfBirth'];
		$dateOfBirthArr = explode("-",$dateOfBirth);
		$year  = $dateOfBirthArr[0];
		$month = $dateOfBirthArr[1];
		$date  = $dateOfBirthArr[2];

		$dateOfAdmission	= $studentDataArr[0]['dateOfAdmission'];
		$dateOfAdmissionArr = explode("-",$dateOfAdmission);
		$admissionYear  = $dateOfAdmissionArr[0];
		$admissionMonth = $dateOfAdmissionArr[1];
		$admissionDate  = $dateOfAdmissionArr[2];

		$thGroupId = $studentDataArr[0]['thGroupId'];
		$prGroupId = $studentDataArr[0]['prGroupId'];

		if($clasStudyPeriodId)
		{
			$periodArr  = $studentManager->getFieldValue("study_period", "periodName",  $clasStudyPeriodId,"studyPeriodId");
			$periodName =  $periodArr[0]['periodName'];
		}

		$quotaId   = $studentDataArr[0]['quotaId'];
		if($quotaId)
		{
			$quotaArr   = $studentManager->getFieldValue("quota", "quotaName", $quotaId,"quotaId");
			$quotaName  = $quotaArr[0]['quotaName'];
		}

		/* function to fetch student Previous Academic*/
		$academicRecordArray = $studentManager->getStudentAcademicList( " WHERE sa.studentId = ".$studentId,'previousClassId');
		$academicCount = COUNT($academicRecordArray);
		for($i=0;$i<$academicCount;$i++){
		
			//echo "--".$academicRecordArray[$i]['previousClassId'];
			$rollArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousRollNo'];

			$sessionArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousSession'];

			$instituteArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousInstitute'];

			$boardArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousBoard'];

			$marksArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousMarks'];

			$educationArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousEducationStream'];

			$maxMarksArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousMaxMarks'];

			$perArr[$academicRecordArray[$i]['previousClassId']] = $academicRecordArray[$i]['previousPercentage'];
		}
		 
		  
		/* function to fetch fees details of <parameter> student Id*/
		//$feesClassArr = $studentManager->getStudentFeesClass($studentDataArr[0]['studentId']);

		/* function to fetch subjects details of <parameter> class Id*/
		//$studentSubjectArray = $studentManager->getSubjectClass($studentDataArr[0]['classId']);

		//$studentSubjectArray = $studentManager->getStudentMarks($studentId);

		//$studentRecordArray = $studentManager->getStudentTimeTable($studentId);
		
		//$where = " AND s.studentId = '$studentId'";
		//$studentAttendanceArray = $commonAttendanceArr->getAttendance($where);

    }
	 
//}

// $History: initData.php $
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 2/06/10    Time: 1:58p
//Updated in $/LeapCC/Library/Student
//fixed issue: 0002790
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 09-10-12   Time: 11:53a
//Updated in $/LeapCC/Library/Student
//Updated with Access right parameters
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 09-10-01   Time: 10:23a
//Updated in $/LeapCC/Library/Student
//- Updated administrative tab with hostel details
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/13/09    Time: 5:31p
//Updated in $/LeapCC/Library/Student
//added check, to redirect page to find student, if current institute and
//student institute are not same
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/16/09    Time: 10:53a
//Updated in $/LeapCC/Library/Student
//Updated student detail module formatting when session is changed from
//top
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/15/09    Time: 1:29p
//Updated in $/LeapCC/Library/Student
//Updated code with transaction in admit student if there is an error in
//query
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/11/09    Time: 11:01a
//Updated in $/LeapCC/Library/Student
//made enhancement to exchange max marks and marks obtained field and
//validations
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/28/09    Time: 4:10p
//Updated in $/LeapCC/Library/Student
//Updated with student academic data in student tab
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Library/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Library/Student
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/16/08    Time: 4:55p
//Updated in $/Leap/Source/Library/Student
//updated files according to subject centric
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/01/08    Time: 12:08p
//Updated in $/Leap/Source/Library/Student
//updated with default list of attendance details under attendance tab as
//said by Sachin sir
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/05/08    Time: 5:36p
//Updated in $/Leap/Source/Library/Student
//updated marks query
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/29/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Student
//updated image upload function
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/17/08    Time: 3:00p
//Updated in $/Leap/Source/Library/Student
//updated with user validations
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/16/08    Time: 1:41p
//Updated in $/Leap/Source/Library/Student
//updated student profile with student marks 
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/15/08    Time: 11:18a
//Updated in $/Leap/Source/Library/Student
//added attendance module
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:19p
//Updated in $/Leap/Source/Library/Student
//made ajax based
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:44p
//Created in $/Leap/Source/Library/Student
//intial checkin
?>