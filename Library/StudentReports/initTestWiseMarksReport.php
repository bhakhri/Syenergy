<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	$classId = $REQUEST_DATA['class1']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3

	$allDetailsArray = array();

	//fetch class students
	$conditions = '';
	if ($groupId != 'all') {
		$conditions = "";
		$groupCodeArray = $studentReportsManager->getSingleField('`group`', 'groupShort', "WHERE groupId  = $groupId");
		$groupCode = $groupCodeArray[0]['groupShort'];
	}

	//fetch distinct types of test taken on this class
	$str2 = "";
	if ($subjectId != 'all') {
		$str2 = " AND a.subjectId = $subjectId ";
	}
	if ($groupId != 'all') {
		$str2 .= " AND a.groupId = $groupId ";
	}
	$testTypeArray = $studentReportsManager->getClassTestTypes($classId, $str2);


	$allDetailsArray['testTypes'] = $testTypeArray;

	$firstString = '';
	$secondString = '';
	$thirdString = '';
	$fourthString = '';

	//for each type, fetch the different tests undertaken.
	$testArray = array();
	foreach($testTypeArray as $testTypeRecord) {
		$str = "";
		if ($subjectId != 'all') {
			$str .= " AND a.subjectId = $subjectId ";
		}
		if ($groupId != 'all') {
			$str .= " AND a.groupId = $groupId ";
		}


		$testDetailsArray = $studentReportsManager->getTestDetails($testTypeRecord['testTypeCategoryId'], $str);

		foreach($testDetailsArray as $testRecord) {
			$testId = $testRecord['testId'];
			$firstString .= ",(select IF(CONCAT(tm$testId.isPresent,tm$testId.isMemberOfClass)=11,tm$testId.marksScored,IF(CONCAT(tm$testId.isPresent,tm$testId.isMemberOfClass)=01,'A','N/A')) from ".TEST_MARKS_TABLE." tm$testId, ".TEST_TABLE." tm where tm.testId = tm$testId.testId and tm.groupId = sg.groupId  and a.studentId = tm$testId.studentId and tm$testId.testId = $testId) AS ms$testId";
			$secondString = " and		sg.groupId = $groupId ";
		}
		//foreach student, foreach test fetch the student marks
		$testArray[$testTypeRecord['testTypeCategoryId']] = $testDetailsArray;
	}

	$allDetailsArray['testDetails'] = $testArray;

	$totalRecordArray = $studentReportsManager->countTestWiseMarksResult($classId,$groupId);
	$cnt1 = $totalRecordArray[0]['cnt'];
	if ($cnt1 == 0) {
		$cnt1 = $totalRecordArray[1]['cnt'];
	}

	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$mmSubjectArray = $studentReportsManager->checkSubjectMM($classId, $subjectId);
	$mmSubjectCount = $mmSubjectArray[0]['cnt'];

	$tableName = 'student_groups';
	if ($mmSubjectCount > 0) {
		$tableName = 'student_optional_subject';
	}

	$sortBy = '';

	$sorting = $REQUEST_DATA['sorting'];
	if ($sorting == 'cRollNo') {
		$sortBy = ' length(a.rollNo)+0,a.rollNo ';
	}
	elseif ($sorting == 'uRollNo') {
		$sortBy = ' length(a.universityRollNo)+0,a.universityRollNo ';
	}
	elseif ($sorting == 'name') {
		$sortBy = 'studentName ';
	}
	$sortBy .= $REQUEST_DATA['ordering'];


	$resultDataArray = $studentReportsManager->getTestWiseMarksResult($classId, $firstString, $secondString, $limit, $tableName, $sortBy);
	$cnt = count($resultDataArray);

	$allDetailsArray['totalRecords'] = $cnt1;


    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$resultDataArray[$i]);
    }

	$allDetailsArray['resultData'] = $valueArray;
	echo json_encode($allDetailsArray);



//// $History: initTestWiseMarksReport.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Library/StudentReports
//added code for multiple tables.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/30/09    Time: 5:50p
//Updated in $/LeapCC/Library/StudentReports
//code modified related to test type category
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 3/17/09    Time: 5:45p
//Updated in $/LeapCC/Library/StudentReports
//added code for pagination
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/16/09    Time: 12:56p
//Updated in $/LeapCC/Library/StudentReports
//changed query and logic as per new db design of group allocation.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 9/10/08    Time: 2:53p
//Updated in $/Leap/Source/Library/StudentReports
//fixed the IE related bug.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 9/08/08    Time: 12:26p
//Updated in $/Leap/Source/Library/StudentReports
//fixed bug related to data not shown subject wise.
//added code for making report flexible for all subjects.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/28/08    Time: 10:56a
//Updated in $/Leap/Source/Library/StudentReports
//added code for N/A in query
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/25/08    Time: 1:26p
//Updated in $/Leap/Source/Library/StudentReports
//query modified, reason: marks not fetched properly
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/14/08    Time: 4:06p
//Created in $/Leap/Source/Library/StudentReports
//file added for test wise marks report
//

?>