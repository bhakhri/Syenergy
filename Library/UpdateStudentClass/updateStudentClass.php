<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of update student class/rollno
// Created on : (20.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	define('MODULE','StudentClassRollNo');
	define('ACCESS','Add');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentClassManager = StudentManager::getInstance();

	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

	$currentClassName = CommonQueryManager::getInstance();

	$rollNo = $REQUEST_DATA['rollNo'];
	$newRollNo = $REQUEST_DATA['newRollNo'];
	$classId = $REQUEST_DATA['classId'];
	$classId1 = $REQUEST_DATA['classId'];
	$studentId = $REQUEST_DATA['studentId'];
	$newClassId = $REQUEST_DATA['newClass'];
	$newClassId1 = $REQUEST_DATA['newClass'];
	$userId = $REQUEST_DATA['userId'];
	$userName = $REQUEST_DATA['userName'];
	$reason = $REQUEST_DATA['reason'];

	if ($classId == '') {
		echo 'Class of giving roll no. is not defined';
		die;
	}

	if($userName != '') {
		if ($userId == '' OR $userId == 0) {
			echo 'User Name has not assigned to this particular roll no.';
			die;
		}
	}

	if ($studentId == '') {
		echo 'Student Id of particular roll no. should not blank';
		die;
	}

	if ($rollNo == '') {
		echo 'Roll No. should not blank';
		die;
	}

	if ($newRollNo == '') {
		echo 'New Roll No. should not blank';
		die;
	}


	$checkStudentGroups = $studentClassManager->getSelectedStudentGroups("WHERE studentId='".$studentId."' AND classId='".$classId."'");
	//echo ($checkStudentGroups[0]['totalRecords']);
	if ($checkStudentGroups[0]['totalRecords'] > 0 ) {
		$checkStudentAttendance = $studentClassManager->getStudentAttendance("WHERE studentId='".$studentId."' AND classId='".$classId."'");
		if ($checkStudentAttendance[0]['totalRecords'] > 0 ) {
			$studentCurrentClassSubjectArray = $studentClassManager->getStudentCurrentClassSubject("AND s.studentId='".$studentId."' AND stc.classId='".$classId."'");
			$currentSubjectIds = UtilityManager::makeCSList($studentCurrentClassSubjectArray, 'subjectId', ',');
				
			$array1 = explode(',',$currentSubjectIds);

			$cntOldSubjectId = count($array1);

			if ($newClassId != '') {
				$studentNewClassSubjectArray = $studentClassManager->getStudentNewClassSubject_New("stc.classId='".$newClassId."'");

				$newSubjectIds = UtilityManager::makeCSList($studentNewClassSubjectArray, 'subjectId', ',');

				$array2 = explode(',',$newSubjectIds);

				$result = array_intersect($array1, $array2);
				
				$cntNewSubjectId = count($result);

				/*if($cntOldSubjectId != $cntNewSubjectId) {
					echo SELECT_SAME_SUBJECT;
					die();
				}*/

				if($classId == $newClassId) {
					echo SELECT_SAME_CLASS;
					die();
				}
			}

            $checkExistanceRollNoArray = $studentClassManager->getExistanceRollNo("WHERE  scs.studentId != $studentId AND scs.rollNo = '".$newRollNo."'");
			if(count($checkExistanceRollNoArray) > 0 && is_array($checkExistanceRollNoArray)) {
			   echo ROLL_NO_EXIST;
			   die();
			}
            
			if ($newClassId != '') {
                $studentPreviousClass = $studentClassManager->getPrevClass($classId);
			    $studentNewClass = $studentClassManager->getPrevClass($newClassId);
			    $checkPreviousClass=2;
			    for(;$studentPreviousClass[0]['classId'] > 0;) {
				    $studentPreviousClass = $studentClassManager->getPrevClass($studentPreviousClass[0]['classId']);
				    if($studentPreviousClass[0]['studyPeriodId'] >= 1) {
					    $checkPreviousClass++;
				    }
				    else {
					    break;
				    }
			    }
			//echo($checkPreviousClass);
			$checkNewClass=2;
			
			for (;$studentNewClass[0]['classId'] > 0;) {
				$studentNewClass = $studentClassManager->getPrevClass($studentNewClass[0]['classId']);
				//echo($studentNewClass[0]['studyPeriodId'].'<br>');
				if($studentNewClass[0]['studyPeriodId'] >= 1) {
					//$studentPreviousClass = $studentPreviousClass[0]['studyPeriodId'];
					$checkNewClass++;
				}
				else {
					break;
				}
			}
			//echo($checkNewClass);
			/*if ($checkPreviousClass != $checkNewClass) {
				echo NOT_UPDATE_CLASS;
				//echo ('Cannot update the class');
				die();
			 }*/
			}

			if ($newClassId == '') {
				if ($newRollNo == '') {
				echo SELECT_ONE_OPTION;
				die();
				}
			}
			
	}
	}

//die('============');
//****************************************************************************************************************    
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************

if(SystemDatabaseManager::getInstance()->startTransaction()) {

  //started insert and update operations
 if ($newClassId1 != '') {
  if($checkPreviousClass == $checkNewClass) {
    $updateScStudent=$studentClassManager->studentUpdate($studentId,$newClassId1);
	 if($updateScStudent===false){
		 echo FAILURE;
		 die;
	 }
	
	 // this function delete the recrod of student from student_group because we are changing the class of student 
	 // and we have to allocate new group against this student

	$updateSectionSubject=$studentClassManager->updateStudentGroups($studentId,$classId1);
	if($updateSectionSubject===false) {
		 echo FAILURE;
		 die;
	 }
	
	$updateAttendance=$studentClassManager->updateAttendance($studentId,$classId1,$newClassId1);
	 if($updateAttendance===false){
		 echo FAILURE;
		 die;
	 }
	
	// Cannot update test_marks table due to non-presence of classId
	/*$updateTestMarks=$studentClassManager->updateTestMarks($studentId,$classId1,$newClassId1);
	if($updateTestMarks===false){
		 echo FAILURE;
		 die;
	 }*/

	$updateCgpa=$studentClassManager->updateStudentOptionalSubject($studentId,$classId1);
	 if($updateCgpa===false){
		 echo FAILURE;
		 die;
	 }

	$updateTransferredMarks=$studentClassManager->updateTestTransferredMarks($studentId,$classId1,$newClassId1);
	if($updateTransferredMarks===false){
		 echo FAILURE;
		 die;
	 }
	$updateTotalTransferredMarks=$studentClassManager->updateTotalTransferredMarks($studentId,$classId1,$newClassId1);
	if($updateTotalTransferredMarks===false){
		 echo FAILURE;
		 die;
	 }

	$studentPreClass = $studentClassManager->getPrevClass($classId1);
	$studentNClass = $studentClassManager->getPrevClass($newClassId1);

	while($studentPreClass and $studentNClass) {
			$updateSectionSubject=$studentClassManager->updateStudentGroups($studentId,$studentPreClass[0]['classId'],$studentNClass[0]['classId']);
			if($updateSectionSubject===false) {
				echo FAILURE;
				die;
			 }

			 // Cannot update attendance, as old attendance is entered in old groups. and new groups have to be allocated.
			 // coding related to attendance has been commented, as this needs to be discussed with Sirs PC, SS and KK 
			 // Cannot update test_marks table due to non-presence of classId

			/*
			
			$updateAttendance=$studentClassManager->updateAttendance($studentId,$studentPreClass[0]['classId'],$studentNClass[0]['classId']);
			if($updateAttendance===false){
				 echo FAILURE;
				 die;
			 }
			
			
			$updateTestMarks=$studentClassManager->updateTestMarks($studentId,$studentPreClass[0]['classId'],$studentNClass[0]['classId']);
			if($updateTestMarks===false){
				 echo FAILURE;
				 die;
			 }*/
			 
			 // this function delete the recrod of student from student_optional_subject because we are changing the class of student 
			 // and we have to allocate new group against this student

			$updateCgpa=$studentClassManager->updateStudentOptionalSubject($studentId,$studentPreClass[0]['classId'],$studentNClass[0]['classId']);
			if($updateCgpa===false){
				 echo FAILURE;
				 die;
			 }
			$updateTransferredMarks=$studentClassManager->updateTestTransferredMarks($studentId,$studentPreClass[0]['classId'],$studentNClass[0]['classId']);
			if($updateTransferredMarks===false){
				 echo FAILURE;
				 die;
			 }
			$updateTotalTransferredMarks=$studentClassManager->updateTotalTransferredMarks($studentId,$studentPreClass[0]['classId'],$studentNClass[0]['classId']);
			if($updateTotalTransferredMarks===false){
				 echo FAILURE;
				 die;
			 }
			$studentPreClass = $studentClassManager->getPrevClass($studentPreClass[0]['classId']);
			$studentNClass = $studentClassManager->getPrevClass($studentNClass[0]['classId']);
	}
  }
 }
	 
	if ($newRollNo != "") {
	 $updateRollNo=$studentClassManager->updateCurrentStudentRollNo($studentId,$newRollNo);
	 if($updateRollNo===false){
		 echo FAILURE;
		 die;
	 }
	}
	if ($userName == 1) {
	 $updateUserName=$studentClassManager->updateStudentUserName($userId,$newRollNo);
	 if($updateUserName===false){
		 echo FAILURE;
		 die;
	 }
	}
	
	if ($newClassId1=='') {
		$classId1 = 'NULL';
	$insertData=$studentClassManager->insertRollClassUpdationLog($studentId,$classId1,$rollNo,$reason);
	 if($insertData===false){
		echo FAILURE;
		die;
	 }
	}

	if ($newRollNo=='') {
		$rollNo = 'NULL';
	$insertData=$studentClassManager->insertRollClassUpdationLog($studentId,$classId1,$rollNo,$reason);
	 if($insertData===false){
		echo FAILURE;
		die;
	 }
	}

	if ($newClassId1!='' && $newRollNo!='') {
	$insertData=$studentClassManager->insertRollClassUpdationLog($studentId,$classId1,$rollNo,$reason);
	 if($insertData===false){
		echo FAILURE;
		die;
	 }
	}
    
    //*****************************COMMIT TRANSACTION************************* 
     if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo SUCCESS;
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


// for VSS
// $History: updateStudentClass.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/14/09   Time: 3:11p
//Updated in $/LeapCC/Library/UpdateStudentClass
//fixed query error:-
//UPDATE sc_student SET rollNo = 'b080010523' WHERE studentId = 876 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/09   Time: 10:31a
//Updated in $/LeapCC/Library/UpdateStudentClass
//fixed query problem if userId will be blank
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/30/09    Time: 2:39p
//Updated in $/LeapCC/Library/UpdateStudentClass
//put checks on groups and attendance
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/14/09    Time: 6:37p
//Updated in $/LeapCC/Library/UpdateStudentClass
//modified in queries, delete record student_groups,
//student_optional_subject
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/14/09    Time: 5:35p
//Created in $/LeapCC/Library/UpdateStudentClass
//new files copy from sc
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/26/09    Time: 10:54a
//Updated in $/Leap/Source/Library/ScUpdateStudentClass
//issue solved to save reason
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/25/09    Time: 7:37p
//Updated in $/Leap/Source/Library/ScUpdateStudentClass
//put new field reason in update student class/roll no
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/25/09    Time: 3:31p
//Updated in $/Leap/Source/Library/UpdateStudentClass
//modification in messages & check if classId or roll no. is not selected
//then one of them should be selected
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/25/09    Time: 12:17p
//Created in $/Leap/Source/Library/UpdateStudentClass
//new ajax file to update student class/rollno
//
?>
