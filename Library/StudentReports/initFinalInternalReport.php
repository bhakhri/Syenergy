<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	ini_set('MEMORY_LIMIT','200M');
	set_time_limit(0);
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


	$labelId = $REQUEST_DATA['timetable']; //1
	$classId = $REQUEST_DATA['degree']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3
	$showGraceMarks = $REQUEST_DATA['showGraceMarks'];
	$showTestMarks = $REQUEST_DATA['showMarks'];


    
    $internalExternalArray = $studentReportsManager->getInternalExternalSubjectMarks($classId,$subjectId);
    






	$totalGracemarks = 0;

	$allDetailsArray = array();
    
    //fetch class students
	$conditions = '';
	if ($groupId != 'all') {
		$conditions = "";
		$groupCodeArray = $studentReportsManager->getSingleField('`group`', 'groupShort', "WHERE groupId  = '$groupId' ");
		$groupCode = $groupCodeArray[0]['groupShort'];
	}

	//fetch distinct types of test taken on this class

	$groupStr = '';
	$groupStr2 = '';
	$testGroup = '';
	if ($groupId != 'all') {
		$groupStr .= " AND sg.groupId = '$groupId' ";
		$groupStr2 .= " AND groupId = '$groupId' ";
		$testGroup = " AND groupId = '$groupId' ";
	}
	$testTypeArray = $studentReportsManager->getClassInternalTestTypes($classId, $subjectId);
	$mmSubjectArray = $studentReportsManager->checkSubjectMM($classId, $subjectId);
	$mmSubjectCount = $mmSubjectArray[0]['cnt'];

	$tableName = 'student_groups';
	if ($mmSubjectCount > 0) {
		$tableName = 'student_optional_subject';
	}
	$uniqueTTCategoryArray = array();
	$uniqueTTCategoryId = array();

	foreach($testTypeArray as $testTypeRecord) {
		$testTypeId = $testTypeRecord['testTypeId'];
		$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
		if (in_array($testTypeCategoryId, $uniqueTTCategoryId)) {
			continue;
		}
		$uniqueTTCategoryArray[] = $testTypeRecord;
		$uniqueTTCategoryId[] = $testTypeCategoryId;
	}

	$testTypeArray = $uniqueTTCategoryArray;

	$sortBy = '';

	$sorting = $REQUEST_DATA['sorting'];
	if ($sorting == 'cRollNo') {
		$sortBy = ' length(rollNo)+0,rollNo ';
	}
	elseif ($sorting == 'uRollNo') {
		$sortBy = ' length(universityRollNo)+0,universityRollNo ';
	}
	elseif ($sorting == 'name') {
		$sortBy = ' studentName ';
	}
	$sortBy .= $REQUEST_DATA['ordering'];

	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$studentArray = $studentReportsManager->getStudents($classId, $subjectId, $tableName, $groupId, $sortBy, $limit);
	$studentIdList = UtilityManager::makeCSList($studentArray, 'studentId');
	if (empty($studentIdList)) {
		$studentIdList = 0;
	}
	$studentArray2 = $studentReportsManager->getStudents($classId, $subjectId, $tableName, $groupId, $sortBy);
	$studentIdList2 = UtilityManager::makeCSList($studentArray2, 'studentId');
	if (empty($studentIdList2)) {
		$studentIdList2 = 0;
	}


   
    
    
    
	$lectureDeliveredArray = $studentReportsManager->getLectureDeliveredSum($studentIdList, $classId, $subjectId);
	$lectureDeliveredNewArray = array();
	foreach($lectureDeliveredArray as $record) {
		$lectureDeliveredNewArray[$record['studentId']] = $record['lectureDelivered'];
	}
	$lectureDeliveredArray = null;

	$lectureAttendedArray = $studentReportsManager->getLectureAttendedSum($studentIdList, $classId, $subjectId);
	$lectureAttendedNewArray = array();
	foreach($lectureAttendedArray as $record) {
		$lectureAttendedNewArray[$record['studentId']] = $record['lectureAttended'];
	}
	$lectureAttendedArray = null;

	$dutyLeaveArray = $studentReportsManager->getDutyLeaveSum($studentIdList, $classId, $subjectId);
	$dutyLeaveNewArray = array();
	foreach($dutyLeaveArray as $record) {
		$dutyLeaveNewArray[$record['studentId']] = $record['dutyLeave'];
	}
	$dutyLeaveArray = null;



	$queryPart = '';
	$testTypeList = '';
	$i = 0;
	foreach($testTypeArray as $testTypeRecord) {
		$testTypeId = $testTypeRecord['testTypeId'];
		$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
		$testTypeName = $testTypeRecord['testTypeName'];
		$isAttendanceCategory = $testTypeRecord['isAttendanceCategory'];
		$testTypeMaxMarks = $testTypeRecord['maxMarks'];
		$totalMaxMarks += $testTypeMaxMarks;

		if ($isAttendanceCategory == 1) {
			$attendanceArray = $studentReportsManager->getAttendanceResultMarks($studentIdList, $classId, $subjectId, $testTypeId);
			$attendanceNewArray = array();
			foreach($attendanceArray as $record) {
				$attendanceNewArray[$record['studentId']] = $record['ms_attendance'];
			}
			$attendanceArray = null;

		}
		else {
			$testTransferredMarksArray[$testTypeCategoryId] = $studentReportsManager->getTestTransferredResultMarks($studentIdList, $classId, $subjectId, $testTypeId);

			if ($showTestMarks == 1) {
				$testTypeMaxMarks = " b.maxMarks ";
				$testTypeMarksArray = $studentReportsManager->getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks);
				foreach($testTypeMarksArray as $record) {
					$testTypeArray[$i]['testMaxMarks'] = $record['maxMarks'];
				}
			}
			else {
				$testTypeArray[$i]['testMaxMarks'] = $testTypeMaxMarks;
			}
			$testTypeMaxMarks = $testTypeArray[$i]['testMaxMarks'];
			$testMarksArray[] = $studentReportsManager->getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks);


			$testIndexArray = $studentReportsManager->getDistinctTests($testTypeCategoryId, $classId, $subjectId, $testGroup);
			$allDetailsArray[$testTypeCategoryId] = $testIndexArray;
		}
		$i++;
	}
	$allDetailsArray['testTypes'] = $testTypeArray;

	$testTransferredMarksNewArray = array();
	foreach($testTransferredMarksArray as $testTypeCategoryId => $recordArray) {
		foreach($recordArray as $record) {
			$studentId = $record['studentId'];
			$marksScored = $record['marksScored'];
			$testTransferredMarksNewArray[$studentId]['ms_'.$testTypeCategoryId] = $marksScored;
		}
	}
	$testTransferredMarksArray = null;

    
     // External Marks


    $externalArray = $studentReportsManager->getStudentExteralMarks($studentIdList, $classId, $subjectId);     
    $externalMarksArray = array();
    foreach($externalArray as $record) {
        $studentId = $record['studentId'];
        $externalMarksArray[$studentId]['maxMarks'] =  $record['maxMarks'];   
        $externalMarksArray[$studentId]['marksScored'] = $record['marksScored'];   
    }
    
	$graceMarksArray = $studentReportsManager->getGraceMarks($studentIdList, $classId, $subjectId);
	$graceMarksNewArray = array();
	foreach($graceMarksArray as $record) {
		$studentId = $record['studentId'];
		$marksScored = $record['graceMarks'];
		$graceMarksNewArray[$studentId]['grace'] =  $record['graceMarks'];   
		$graceMarksNewArray[$studentId]['Int'] =  $record['internalGraceMarks'];   
		$graceMarksNewArray[$studentId]['Ext'] =  $record['externalGraceMarks'];   
		$graceMarksNewArray[$studentId]['Tot'] =  $record['totalGraceMarks'];   
	}
	$graceMarksArray = null;

	$totalTransferredMarksArray = $studentReportsManager->getTotalTransferredResultMarks($studentIdList, $classId, $subjectId);
	$totalTransferredMarksNewArray = array();
	foreach($totalTransferredMarksArray as $record) {
		$studentId = $record['studentId'];
		$marksScored = $record['marksScored'];
		$totalTransferredMarksNewArray[$studentId] = $marksScored;
	}
	$totalTransferredMarksArray = null;

	$studentTestArray = array();
	foreach($testMarksArray as $testRecordArray) {
		foreach($testRecordArray as $record) {
			$studentId = $record['studentId'];
			$testName = $record['testName'];
			$marksScored = $record['marksScored'];
			$maxMarks = $record['maxMarks'];
			$studentTestArray[$studentId][$testName] = $marksScored;
			$studentMaxMarksArray[$studentId][$testName] = $maxMarks;
		}
	}
	$testMarksArray = null;

	$i = 0;
	foreach($studentArray as $record) {
		$studentId = $record['studentId'];
		$studentArray[$i]['lectureDelivered'] = $lectureDeliveredNewArray[$studentId];
		$studentArray[$i]['lectureAttended'] = $lectureAttendedNewArray[$studentId];
		$studentArray[$i]['dutyLeave'] = $dutyLeaveNewArray[$studentId];
		$totalAttended = $lectureAttendedNewArray[$studentId] + $dutyLeaveNewArray[$studentId];
		$studentArray[$i]['totalAttended'] = "$totalAttended";
		$studentArray[$i]['ms_attendance'] = $attendanceNewArray[$studentId];
		if (array_key_exists($studentId, $studentTestArray)) {
			foreach($studentTestArray[$studentId] as $testName => $marksScored) {
				$studentArray[$i][$testName] = $marksScored;
			}
			foreach($studentMaxMarksArray[$studentId] as $testName => $maxMarks) {
				$studentTestMaxMarksArray[$i][$testName]['maxMarks'] = round($maxMarks,1);
			}
		}
		if (array_key_exists($studentId, $testTransferredMarksNewArray)) {
			foreach($testTransferredMarksNewArray[$studentId] as $testName => $marksScored) {
				$studentArray[$i][$testName] = $marksScored;
			}
		}
		
        if($graceMarksNewArray[$studentId]['Int']!='') { 
           $studentArray[$i]['ms_grace'] = $graceMarksNewArray[$studentId]['grace']; 
           $studentArray[$i]['ms_int_grace'] = $graceMarksNewArray[$studentId]['Int'];
           $studentArray[$i]['ms_ext_grace'] = $graceMarksNewArray[$studentId]['Ext'];
           $studentArray[$i]['ms_tot_grace'] = $graceMarksNewArray[$studentId]['Tot'];
        }
        else {
           $studentArray[$i]['ms_grace'] = '0';  
           $studentArray[$i]['ms_int_grace'] = '0';
           $studentArray[$i]['ms_ext_grace'] = '0';
           $studentArray[$i]['ms_tot_grace'] = '0';  
        }
        
        if($externalMarksArray[$studentId]['marksScored']!='') {
          $studentArray[$i]['ext_maxMarks'] = $externalMarksArray[$studentId]['maxMarks'];
          $studentArray[$i]['ext_scored'] = $externalMarksArray[$studentId]['marksScored'];
        }
        else {
          $studentArray[$i]['ext_maxMarks'] = NOT_APPLICABLE_STRING;
          $studentArray[$i]['ext_scored'] = NOT_APPLICABLE_STRING;  
        }
        
		$studentArray[$i]['ms_total'] = $totalTransferredMarksNewArray[$studentId];
        
		$i++;
	}
	$studentTestArray = null;
	$testTransferredMarksNewArray = null;
	$graceMarksNewArray = null;
	$totalTransferredMarksNewArray = null;


	$totalRecordArray = $studentReportsManager->countStudents($classId, $subjectId, $tableName, $groupId);
	$cnt1 = $totalRecordArray[0]['cnt'];



	$resultDataArray = $studentArray;//$studentReportsManager->getFinalReportData($classId, $groupStr, $queryPart, $sortBy, $limit, $tableName);
	$studentArray = null;
	$cnt = count($resultDataArray);

	$allDetailsArray['totalRecords'] = $cnt1;


    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$resultDataArray[$i]);
    }

	 $totalMarksArray = $studentReportsManager->getSubjectTotalMarks($studentIdList2, $classId, $subjectId);
	 if ($showGraceMarks == '1' or $showGraceMarks == 1) {
		 $graceMarksArray = $studentReportsManager->getTotalGraceMarks($studentIdList2, $classId, $subjectId);
		 $totalGracemarks = $graceMarksArray[0]['marksScored'];
	 }
	 $totalMarksScored = $totalMarksArray[0]['marksScored'] + $totalGracemarks;
	 $maxMarks = $totalMarksArray[0]['maxMarks'];
	 $average = round(($totalMarksScored*100) / $maxMarks,2);
	 $average = "$average";

	$str = '';
	if ($groupId != 'all') {
		$str = " AND a.groupId = $groupId ";
	}
	else {
		$str = " AND a.groupId IN (select groupId from `group` where classId = $classId)";
	}
	$teacherArray = $studentReportsManager->getSubjectTeachers($subjectId, $str);
	$teacherName = UtilityManager::makeCSList($teacherArray,'employeeName');
	$subjectNameArray = $studentReportsManager->getSingleField('subject','subjectName'," WHERE subjectId = $subjectId");
	$subjectName = $subjectNameArray[0]['subjectName'];


	$allDetailsArray['resultData'] = $valueArray;
	$allDetailsArray['average'] = $average;
	$allDetailsArray['teachers'] = $teacherName;
	$allDetailsArray['subjectName'] = $subjectName;
	//$allDetailsArray['maxMarks'] = $studentTestMaxMarksArray;

	echo json_encode($allDetailsArray);



//// $History: initFinalInternalReport.php $
//
//*****************  Version 11  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 12/06/09   Time: 2:36p
//Updated in $/LeapCC/Library/StudentReports
//done changes in files for marks transfer
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 11/25/09   Time: 6:42p
//Updated in $/LeapCC/Library/StudentReports
//improved marks transfer page designing, done changes in final internal
//report as per requirement from sachin sir
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/23/09   Time: 10:45a
//Updated in $/LeapCC/Library/StudentReports
//done changes for final internal report
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:35p
//Updated in $/LeapCC/Library/StudentReports
//added multiple table defines.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 6/01/09    Time: 6:43p
//Updated in $/LeapCC/Library/StudentReports
//corrected from class1 to degree as part of checking/fixing all reports.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 5/29/09    Time: 1:34p
//Updated in $/LeapCC/Library/StudentReports
//corrected query part. it was picking all lectures delivered. changed
//this to only for when the student was member of class.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/21/09    Time: 6:58p
//Updated in $/LeapCC/Library/StudentReports
//done the rounding of marks to 1 decimal.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/12/09    Time: 6:40p
//Updated in $/LeapCC/Library/StudentReports
//code updated to make final internal report teacher compatible.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 5/08/09    Time: 4:04p
//Created in $/LeapCC/Library/StudentReports
//got this file from Ajinder updated it at Rajpura
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/07/09    Time: 8:14p
//Created in $/LeapCC/Library/StudentReports
//file added for internal file report.
//

?>
