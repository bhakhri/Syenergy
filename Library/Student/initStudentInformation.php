<?php
//-------------------------------------------------------
// Purpose: To show student personal information
// Author : Jaineesh
// Created on : (17.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    if($sessionHandler->getSessionVariable('RoleId')==4) {
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else {   
	  UtilityManager::ifNotLoggedIn(true);
    }
	UtilityManager::headerNoCache();
	
	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    
    
    $errorMessage='';
    
	 $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceUrl LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    
     $orderBy = " $sortField $sortOrderBy";

    if (trim($errorMessage=='')){
        
		require_once($FE . "/Library/common.inc.php"); //for studentid
		
		$studentInformationManager = StudentInformationManager::getInstance();
        
		global $sessionHandler;
        
        if($sessionHandler->getSessionVariable('RoleId')==4) {   
           $studentId = $sessionHandler->getSessionVariable('StudentId');
        }
        else {
           $studentId = add_slashes(trim($REQUEST_DATA['studentId'])); 
        }
        
        $studentInformationArray = $studentInformationManager->getStudentInformationList($studentId);
		//print_r($studentInformationArray);
        
        /***********FETCH STUDENT PAYMENT DETAILS***********/
        $studentPaymanetDetailsArray=$studentInformationManager->getStudentPaymentDetails($studentId);
        /***********FETCH STUDENT PAYMENT DETAILS***********/
        

		require_once(MODEL_PATH."/CommonQueryManager.inc.php");    

		$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
		$classId = $classIdArray[count($classIdArray)-1]['classId'];
		
		$academicRecordArray = $studentInformationManager->getStudentAcademicList( " WHERE sa.studentId = ".$studentId,'previousClassId');
		//echo '<pre>';
		//print_r($academicRecordArray);
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

		//$totalArray = $studentInformationManager->getTotalStudentCourseResourceList($studentId,$classId,$filter);
				
		//$studentResourceArray = $studentInformationManager->getStudentCourseResourceList($studentId,$classId,$filter,$orderBy,$limit);
        

    }
    else{
        $errorMessage="The studentId has not been recognised";
    }
    
       

// for VSS
// $History: initStudentInformation.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/08/10    Time: 4:23p
//Updated in $/LeapCC/Library/Student
//role permission added
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/24/09   Time: 3:50p
//Updated in $/LeapCC/Library/Student
//fixed bug nos. 0001883, 0001877 and modification in query
//getStudentCourseResourceList() to get courses of current class and make
//searchable course
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/07/09    Time: 6:32p
//Updated in $/LeapCC/Library/Student
//modification in academic record during student login
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/07/09    Time: 5:23p
//Updated in $/LeapCC/Library/Student
//modified code
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:40p
//Updated in $/LeapCC/Library/Student
//modifcation in queries for paging
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:31p
//Updated in $/LeapCC/Library/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/23/08    Time: 7:40p
//Updated in $/Leap/Source/Library/Student
//contain the student information
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/21/08    Time: 6:53p
//Updated in $/Leap/Source/Library/Student
//stored the data base information 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/18/08    Time: 11:10a
//Updated in $/Leap/Source/Library/Student
//list the student information
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/17/08    Time: 1:59p
//Created in $/Leap/Source/Library/Student
//give student information
//

?>