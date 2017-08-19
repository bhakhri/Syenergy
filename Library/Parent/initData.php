<?php
//-------------------------------------------------------------------------------------------
// Purpose: To store the records of subject to class in array from the database functionality
//
// Author : Parveen Sharma
// Created on : 10.12.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------
 
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    $commonAttendanceArr = CommonQueryManager::getInstance();

    if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('StudentId'))) 
    {
        $studentId  = $sessionHandler->getSessionVariable('StudentId');
         
         // student Id for photo name
         
        $sessionHandler->setSessionVariable('IdToFileUpload',$studentId); 

        $studentDataArr = $studentManager->getStudentData($studentId);
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
        $Id =  $studentDataArr[0]['studentId'];
        $studentId =  $studentDataArr[0]['studentId'];
        
        $dateOfBirth    = $studentDataArr[0]['dateOfBirth'];
        $dateOfBirthArr = explode("-",$dateOfBirth);
        $year  = $dateOfBirthArr[0];
        $month = $dateOfBirthArr[1];
        $date  = $dateOfBirthArr[2];

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
      
     /* function to fetch student Previous Academic*/
     /*           
        $academicRecordArray = $studentManager->getStudentAcademicList( " WHERE sa.studentId = ".$studentId,'previousClassId');
        $academicCount = COUNT($academicRecordArray);
        for($i=1;$i<=$academicCount;$i++){
            $rollArr[$i] = $academicRecordArray[$i-1]['previousRollNo'];
            $sessionArr[$i] = $academicRecordArray[$i-1]['previousSession'];
            $instituteArr[$i] = $academicRecordArray[$i-1]['previousInstitute'];
            $boardArr[$i] = $academicRecordArray[$i-1]['previousBoard'];
            $marksArr[$i] = $academicRecordArray[$i-1]['previousMarks'];
            $maxMarksArr[$i] = $academicRecordArray[$i-1]['previousMaxMarks'];
            $perArr[$i] = $academicRecordArray[$i-1]['previousPercentage'];
            $perEduStrArr[$i] = $academicRecordArray[$i-1]['previousEducationStream'];
        }
      */  
      
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
//*****************  Version 5  *****************
//User: Parveen      Date: 9/01/09    Time: 2:15p
//Updated in $/LeapCC/Library/Parent
//function to fetch student Previous Academic values
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/02/09    Time: 5:02p
//Updated in $/LeapCC/Library/Parent
//academic information updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/23/08   Time: 1:55p
//Updated in $/LeapCC/Library/Parent
//file updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/10/08   Time: 1:17p
//Updated in $/LeapCC/Library/Parent
//issue fix
//

?>