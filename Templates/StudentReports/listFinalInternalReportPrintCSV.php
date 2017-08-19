<?php
//This file is used as internal file for csv part
//
// Author :Ajinder Singh
// Created on : 21-may-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
	if($sessionHandler->getSessionVariable('RoleId') != 2) {
	  UtilityManager::ifNotLoggedIn();
	}
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();

	$consolidated=1;

	$labelId = $REQUEST_DATA['timetable']; //1
	$classId = $REQUEST_DATA['degree']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$showUnivRollNo = $REQUEST_DATA['showUnivRollNo'];
    $showGraceMarks = $REQUEST_DATA['showGraceMarks']; 
    $showTestMarks = $REQUEST_DATA['showMarks'];
	$classNameArray = $studentReportsManager->getSingleField('`class`', 'className', "WHERE classId  = $classId");
	$className = $classNameArray[0]['className'];

	$subjectCodeArray = $studentReportsManager->getSingleField('`subject`', 'concat(subjectName," (",subjectCode,")") as subjectCode1, subjectCode', "WHERE subjectId  = $subjectId");
	$subjectCode = $subjectCodeArray[0]['subjectCode'];
	$subjectCode1 = $subjectCodeArray[0]['subjectCode1'];


	$groupId = $REQUEST_DATA['groupId']; //3
	$allDetailsArray = array();
	$allDetailsArray['grace'] = $REQUEST_DATA['grace'];
	$showGraceMarks = $REQUEST_DATA['grace'];


	//fetch class students
	$conditions = '';
	if ($groupId != 'all') {
		$conditions = "";
		$groupCodeArray = $studentReportsManager->getSingleField('`group`', 'groupShort', "WHERE groupId  = $groupId");
		$groupCode = $groupCodeArray[0]['groupShort'];
	}

	//fetch distinct types of test taken on this class

	$groupStr = '';
	$groupStr2 = '';
	$testGroup = '';
	if ($groupId != 'all') {
		$groupStr .= " AND sg.groupId = $groupId ";
		$groupStr2 .= " AND groupId = $groupId ";
		$testGroup = " AND groupId = $groupId";
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
	$limit      = '';//' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$studentArray = $studentReportsManager->getStudents($classId, $subjectId, $tableName, $groupId, $sortBy, $limit);
	$studentIdList = UtilityManager::makeCSList($studentArray, 'studentId');
	if (empty($studentIdList)) {
		$studentIdList = 0;
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

	/*$dutyLeaveArray = $studentReportsManager->getDutyLeaveSum($studentIdList, $classId, $subjectId);
	$dutyLeaveNewArray = array();
	foreach($dutyLeaveArray as $record) {
		$dutyLeaveNewArray[$record['studentId']] = $record['dutyLeave'];
	}
	$dutyLeaveArray = null;*/
	
	$cond="AND att.classId ='$classId' AND att.subjectId ='$subjectId' AND  att.studentId  IN ($studentIdList)  ";
	$dutyMedicalLeaveDetailsArray = $commonQueryManager->getStudentAttendanceReport($cond,'',$consolidated);
	$dutyLeaveArray = array();
	$medicalLeaveArray = array();
	foreach($dutyMedicalLeaveDetailsArray as $record) {
	    $dutyLeaveArray[$record['studentId']] = $record['leaveTaken'];
	    $medicalLeaveArray[$record['studentId']] = $record['transferMarksMedicalLeaveTaken'];
	}
	$dutyMedicalLeaveDetailsArray = null;


	//$allDetailsArray['testTypes'] = $testTypeArray;
	$queryPart = '';
	$testTypeList = '';
    if ($testTypeArray[0]['testTypeId'] != '') {
    $i=0;
	foreach($testTypeArray as $testTypeRecord) {
		$testTypeId = $testTypeRecord['testTypeId'];
		$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
		$testTypeName = $testTypeRecord['testTypeName'];
		$isAttendanceCategory = $testTypeRecord['isAttendanceCategory'];
		$testTypeMaxMarks = $testTypeRecord['maxMarks'];

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
            //
            if ($showTestMarks == 1) {
                    $testTypeMaxMarks = " b.maxMarks ";
                    $testTypeMarksArray = $studentReportsManager->getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks);
                    foreach($testTypeMarksArray as $record) {
                        $testTypeArray[$i]['testMaxMarks'] = round($record['maxMarks']); 
                    }
                }
                else {
                    $testTypeArray[$i]['testMaxMarks'] = round($testTypeMaxMarks);
                }
            //
			$testMarksArray[] = $studentReportsManager->getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks);

			$testIndexArray = $studentReportsManager->getDistinctTests($testTypeCategoryId, $classId, $subjectId, $testGroup);
			$allDetailsArray[$testTypeCategoryId] = $testIndexArray;
		}
        $i++;
	}
    }
    //echo "<pre>";
    //print_r($testMarksArray);
    //exit;
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

	$graceMarksArray = $studentReportsManager->getGraceMarks($studentIdList, $classId, $subjectId);
	$graceMarksNewArray = array();
	foreach($graceMarksArray as $record) {
			$studentId = $record['studentId'];
			$marksScored = $record['graceMarks'];
			$graceMarksNewArray[$studentId] = $marksScored;
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
		$studentArray[$i]['dutyLeave'] = $dutyLeaveArray[$studentId];
		$studentArray[$i]['medicalLeave'] = $medicalLeaveArray[$studentId];
		$studentArray[$i]['totalAttended'] = $lectureAttendedNewArray[$studentId] + $dutyLeaveArray[$studentId] + $medicalLeaveArray[$studentId];
		$studentArray[$i]['ms_attendance'] = $attendanceNewArray[$studentId];
		if (array_key_exists($studentId, $studentTestArray)) {
			foreach($studentTestArray[$studentId] as $testName => $marksScored) {
				$studentArray[$i][$testName] = $marksScored;
			}
		}
		if (array_key_exists($studentId, $testTransferredMarksNewArray)) {
			foreach($testTransferredMarksNewArray[$studentId] as $testName => $marksScored) {
				$studentArray[$i][$testName] = $marksScored;
			}
		}
		$studentArray[$i]['ms_grace'] = $graceMarksNewArray[$studentId];
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

	$allDetailsArray['resultData'] = $valueArray;
	$totalTests = count($allDetailsArray['testTypes']);

	$str = '';
	$str2 = '';
	$str3 = '';
	$totalTestTypeMaxMarks = 0;
	for($i = 0; $i < $totalTests; $i++) {
		$testTypeId = $allDetailsArray['testTypes'][$i]['testTypeId'];
		$testTypeCategoryId = $allDetailsArray['testTypes'][$i]['testTypeCategoryId'];
		$isAttendanceCategory = $allDetailsArray['testTypes'][$i]['isAttendanceCategory'];
		$testTypeName = $allDetailsArray['testTypes'][$i]['testTypeName'];
		$testTypeAbbr = $allDetailsArray['testTypes'][$i]['testTypeAbbr'];
		$testTypeMaxMarks = $allDetailsArray['testTypes'][$i]['testMaxMarks'];
		$totalTestTypeMaxMarks += round(intval($testTypeMaxMarks));

		if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
			$str .= ",Attendance,,,,,,";
			$str2 .= ",Held, Attended, DL, ML, Total, %,M.M. ".round($testTypeMaxMarks);
			$str3 .= ",,,,,,,";

		}
		else {
			$str .= $testTypeName;
			$m = 0;
			echo $testTypeCategoryIdTests = count($allDetailsArray[$testTypeCategoryId]);
			while ($m < $testTypeCategoryIdTests) {
				$str .= ",";
				$str2 .= ",". $testTypeAbbr.''.$allDetailsArray[$testTypeCategoryId][$m]['testIndex'];
				$str3 .= ",".$testTypeMaxMarks;
				$m++;
			}
			$str .= ",";
			$str2 .= ",M.M. ".round($testTypeMaxMarks);
			$str3 .= ",";
		}
	}
	$str2 .= ",$totalTestTypeMaxMarks";
	$csvData = "$className \n {$subjectCode1} \n";
	$csvData .= "Sr";
	if ($showUnivRollNo) {
		$csvData .= ",Univ.RollNo";
	}
	$csvData .= ",RollNo,Student Name $str Total";
	if ($showGraceMarks == 'yes') {
		$csvData .= ",Grace";
	}
	$csvData .= ", G.Total \n";

	$csvData .= ", , ,$str2 \n";
	$csvData .= ", , $str3 \n";

	$x=0;
	$allSumArray = array();
	
	/*echo '<pre>';
	print_r($resultDataArray);
	echo '</pre>';
	die;
	*/
	
	foreach($resultDataArray as $record) {
		$x++;
		$studentTotalMarks = 0;
		$csvData .= $x.',';
		if($showUnivRollNo) {
				$csvData .= $record['universityRollNo'] . ',';
		}
		$csvData .= $record['rollNo'] . ',' . $record['studentName'] . ',' . $record['lectureDelivered'] . ',' . $record['lectureAttended'] . ',' . $record['dutyLeave'] . ',' . $record['medicalLeave'] . ',' . $record['totalAttended'] . ',' . ceil($record['totalAttended']*100/$record['lectureDelivered']) . ',' . $record['ms_attendance'];
		$studentTotalMarks += floatval($record['ms_attendance']);
		$graceMarks = $record['ms_grace'];
		for($i=0; $i< $totalTests; $i++) {
			$testTypeId = $allDetailsArray['testTypes'][$i]['testTypeId'];
			$testTypeCategoryId = $allDetailsArray['testTypes'][$i]['testTypeCategoryId'];
			$isAttendanceCategory = $allDetailsArray['testTypes'][$i]['isAttendanceCategory'];
			$testTypeName = $allDetailsArray['testTypes'][$i]['testTypeName'];
			$testTypeMaxMarks = $allDetailsArray['testTypes'][$i]['maxMarks'];
			$testTypeAbbr = $allDetailsArray['testTypes'][$i]['testTypeAbbr'];
			if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
				continue;
			}
			else {
				$testTypeCategoryIdTests = count($allDetailsArray[$testTypeCategoryId]);
				$testMarksName = 'ms_'.$testTypeCategoryId;
				for ($m = 0; $m < $testTypeCategoryIdTests; $m++) {
					$testMaxMarks = $allDetailsArray[$testTypeCategoryId][$m]['maxMarks'];
					$testIndex = $allDetailsArray[$testTypeCategoryId][$m]['testIndex'];
					$testName = 'ms_'.$testTypeCategoryId.'_'.$testIndex;
					$studentMarks = $record[$testName];
					//$studentMarks = round($studentMarks,1);
					if (is_null($studentMarks) || $studentMarks == "null") {
						$studentMarks = "---";
					}
					elseif ($studentMarks == "A" or $studentMarks == "N/A") {
						$studentMarks = "$studentMarks";
					}
					else {
						$studentMarks = round($studentMarks,1);
					}
					//$studentTotalMarks += floatval($studentMarks);
					$csvData .= ",".$studentMarks;
				}
				$studentMarks2 = $record[$testMarksName];
				$studentTotalMarks += floatval($studentMarks2);
				$csvData .= ",".$studentMarks2;
			}
		}
		$studentFinalTotalMarks = $record['ms_total'];

		$csvData .= ",".$studentTotalMarks;
		//$studentTotalMarks += floatval($studentMarks);
		if (is_null($graceMarks) || $graceMarks == "null") {
			$graceMarks = 0;
		}

		if ($showGraceMarks == 'yes') {
			$csvData .= ",".$graceMarks;
			$grandTotal = $studentFinalTotalMarks + floatval($graceMarks);
		}
		else {
			$grandTotal = $studentFinalTotalMarks;
		}
		$csvData .= ",".$grandTotal;
		$csvData .= "\n";
		} 
		require_once(BL_PATH . "/UtilityManager.inc.php");
		UtilityManager::makeCSV($csvData, "$className --- $subjectCode.csv");
		die;

//$History: listFinalInternalReportPrintCSV.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/25/09   Time: 6:42p
//Updated in $/LeapCC/Templates/StudentReports
//improved marks transfer page designing, done changes in final internal
//report as per requirement from sachin sir
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 11/23/09   Time: 10:45a
//Updated in $/LeapCC/Templates/StudentReports
//done changes for final internal report
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/12/09   Time: 11:14a
//Updated in $/LeapCC/Templates/StudentReports
//done changes to fix following bug no.s:
//0001987
//0001986
//0001985
//0001984
//0001983
//0001777
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 10/28/09   Time: 1:49p
//Updated in $/LeapCC/Templates/StudentReports
//done changes for making on/off for grace marks.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:26p
//Updated in $/LeapCC/Templates/StudentReports
//applied multiple table defines.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/01/09    Time: 6:50p
//Updated in $/LeapCC/Templates/StudentReports
//corrected from class1 to degree as part of checking/fixing all reports.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/21/09    Time: 6:56p
//Created in $/LeapCC/Templates/StudentReports
//file added for final report csv version
//

?>
