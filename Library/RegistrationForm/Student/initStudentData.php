<?php
//-------------------------------------------------------
// Purpose: To store the records of subject to class in array from the database functionality
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);         
    global $sessionHandler;
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if($roleId==2) {
	  UtilityManager::ifTeacherNotLoggedIn(true);
	  $studentId  = $REQUEST_DATA['id'];
	}
	else if($roleId==3) {
	  UtilityManager::ifParentNotLoggedIn(true);
	  $studentId  =  $sessionHandler->getSessionVariable('StudentId');
	}
	else if($roleId==4) {
	   UtilityManager::ifStudentNotLoggedIn(true);
	   $studentId  =  $sessionHandler->getSessionVariable('StudentId');
	}
	else {
	   UtilityManager::ifNotLoggedIn(true);
   	   $studentId  = $REQUEST_DATA['id'];
	}
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/RegistrationForm/ReAppearLabelManager.inc.php");   
    $reAppearLabelManager = ReAppearLabelManager::getInstance();
    
    
	if($studentId=='') {
      $studentId=0;
	}

    if($studentId<>'') {
        
	    $studentDataArr = $reAppearLabelManager->getStudentData($studentId);
		$studentClassArr = $reAppearLabelManager->getStudentClass($studentDataArr[0]['classId']);
		$inActiveClass = $studentClassArr[0]['isActive']!=1 ? "READONLY = 'READONLY'" : "";
		$disableClass = $studentClassArr[0]['isActive']!=1 ? "DISABLED = 'TRUE'" : "";

		/* function to fetch student user details*/ 
		if($studentDataArr[0]['userId'])
		{
			$studentUserDataArr = $reAppearLabelManager->getUserData($studentDataArr[0]['userId']);
		}
		 
		/* function to fetch student father details*/ 
		if($studentDataArr[0]['fatherUserId'])
		{
			$fatherUserDataArr = $reAppearLabelManager->getUserData($studentDataArr[0]['fatherUserId']);
		}

		//print_r($fatherUserDataArr);
		/* function to fetch student mother details*/ 
		if($studentDataArr[0]['motherUserId'])
		{
			$motherUserDataArr = $reAppearLabelManager->getUserData($studentDataArr[0]['motherUserId']);
		}
		
		/* function to fetch student guardian details*/ 
		if($studentDataArr[0]['guardianUserId'])
		{
			$guardianUserDataArr = $reAppearLabelManager->getUserData($studentDataArr[0]['guardianUserId']);
		}
		
		$classStr = $studentClassArr[0]['className'];
		$clasStudyPeriodId = $studentClassArr[0]['studyPeriodId'];
	 
		$classArr = explode("-",$classStr);
		$batch = $classArr[0];
		$university = $classArr[1];
		//$degree = $classArr[2];
		//$branch = $classArr[3];

		$degree = $studentClassArr[0]['degreeCode'];
		$branch = $studentClassArr[0]['branchCode'];

		$periodArr  = $reAppearLabelManager->getFieldValue("study_period", "periodName",  $clasStudyPeriodId,"studyPeriodId");
		$periodName =  $periodArr[0]['periodName'];
		
		$dateOfBirth	= $studentDataArr[0]['dateOfBirth'];
		$dateOfBirthArr = explode("-",$dateOfBirth);
		$year  = $dateOfBirthArr[0];
		$month = $dateOfBirthArr[1];
		$date  = $dateOfBirthArr[2];

		$thGroupId = $studentDataArr[0]['thGroupId'];
		$prGroupId = $studentDataArr[0]['prGroupId'];

		if($clasStudyPeriodId) {
			$periodArr  = $reAppearLabelManager->getFieldValue("study_period", "periodName",  $clasStudyPeriodId,"studyPeriodId");
			$periodName =  $periodArr[0]['periodName'];
		}
        
        /*
        // Findout Max Course Limit
        $reappearClassArray = $reAppearLabelManager->getStudentReappearSubjectList($studentDataArr[0]['classId']);
        for($i=0;$i<count($reappearClassArray);$i++) {
          $maxCourseReExam = $reappearClassArray[$i]['maxCourseReExam'];
          $examLabelId = $reappearClassArray[$i]['labelId'];  
        } 
       
        $editId = "0";
        $condition =  " AND rs.labelId = '$examLabelId' AND rs.studentId = '$studentId' ";      
        $returnStatus =  $reAppearLabelManager->getReappearSubjectStudentList($condition);
        if(is_array($returnStatus) && count($returnStatus)>0 ) { 
          $editId = "1";
        }
        */
    }
?>