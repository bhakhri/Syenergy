<?php
//This file is used as printing version for testwise marks report.
//
// Author :Ajinder Singh
// Created on : 14-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	ini_set('MEMORY_LIMIT','200M');
	set_time_limit(0);

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
        define('MODULE','COMMON');
    define('ACCESS','view');
	if ($sessionHandler->getSessionVariable('RoleId') != 2) {
		UtilityManager::ifNotLoggedIn();
	}
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	
	$consolidated=1;


/*
	$labelId = $REQUEST_DATA['timetable']; //1
	$classId = $REQUEST_DATA['degree']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3


	$allDetailsArray = array();
	$allDetailsArray['grace'] = $REQUEST_DATA['grace'];


	//fetch class students
	$conditions = '';
	$groupCode = 'All';
	if ($groupId != 'all') {
		$conditions = "";
		$groupCodeArray = $studentReportsManager->getSingleField('`group`', 'groupShort', "WHERE groupId  = $groupId");
		$groupCode = $groupCodeArray[0]['groupShort'];
	}

	//fetch distinct types of test taken on this class
	$str2 = "";
	$str2 = " AND a.subjectId = $subjectId ";

	$groupStr = '';
	$groupStr2 = '';
	if ($groupId != 'all') {
		$groupStr .= " AND sg.groupId = $groupId ";
		$groupStr2 .= " AND groupId = $groupId ";
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


	$allDetailsArray['testTypes'] = $testTypeArray;
	$queryPart = '';
	foreach($testTypeArray as $testTypeRecord) {
		$testTypeId = $testTypeRecord['testTypeId'];
		$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];

		$testTypeName = $testTypeRecord['testTypeName'];
		$isAttendanceCategory = $testTypeRecord['isAttendanceCategory'];
		$testTypeMaxMarks = $testTypeRecord['maxMarks'];

		if ($isAttendanceCategory == 1) {
			$queryPart .= ",(SELECT SUM(IF(isMemberOfClass=0,0, lectureDelivered)) from ".ATTENDANCE_TABLE." att where att.classId = $classId and att.subjectId = $subjectId and att.studentId = stu.studentId) as lectureDelivered, (SELECT ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS attended FROM  ".ATTENDANCE_TABLE." att  LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId WHERE att.classId = $classId and att.subjectId = $subjectId and att.studentId = stu.studentId) as lectureAttended, (SELECT ifnull(SUM(leavesTaken),0) FROM attendance_leave WHERE classId = $classId and subjectId = $subjectId and studentId = stu.studentId) as dutyLeave, (SELECT ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS attended FROM  ".ATTENDANCE_TABLE." att  LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId WHERE att.classId = $classId and att.subjectId = $subjectId and att.studentId = stu.studentId) + (SELECT ifnull(SUM(leavesTaken),0) FROM attendance_leave WHERE classId = $classId and subjectId = $subjectId and studentId = stu.studentId) as totalAttended, (select marksScored from ".TEST_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId = stu.studentId and testTypeId = $testTypeId) as `ms_attendance`";
		}
		else {
			$testIndexArray = $studentReportsManager->getDistinctTests($testTypeCategoryId, $classId, $subjectId, $groupStr2);
			$allDetailsArray[$testTypeCategoryId] = $testIndexArray;
			foreach($testIndexArray as $testIndexRecord) {
				$testIndex = $testIndexRecord['testIndex'];
				$queryPart .= ",(select IF(CONCAT(tm1.isPresent,tm1.isMemberOfClass)=11,round((tm1.marksScored/tm1.maxMarks) * $testTypeMaxMarks,3),IF(CONCAT(tm1.isPresent,tm1.isMemberOfClass)=01,'A','N/A')) from  ".TEST_MARKS_TABLE."  tm1,  ".TEST_TABLE."  tm where tm.testId = tm1.testId and tm.classId = $classId and tm.subjectId = $subjectId and tm.testTypeCategoryId = $testTypeCategoryId and tm.testIndex = $testIndex and tm1.studentId = stu.studentId) AS `ms_".$testTypeCategoryId."_".$testIndex."`";
			}
			$queryPart .= ",(select round(marksScored,1) from ".TEST_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId = stu.studentId and testTypeId = $testTypeId) as `ms_".$testTypeCategoryId."`";
		}
	}

	$queryPart .= ",(select graceMarks from ".TEST_GRACE_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId = stu.studentId) as `ms_grace`, (select round(SUM(marksScored),1) from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId = stu.studentId) as `ms_total`";

	if ($tableName == 'student_optional_subject') {
		$groupStr .= " and sg.subjectId = $subjectId";
	}

	$totalRecordArray = $studentReportsManager->countFinalReportData($classId,$groupStr, $tableName);
	$cnt1 = $totalRecordArray[0]['cnt'];

	$sortBy = '';

	$sorting = $REQUEST_DATA['sorting'];
	if ($sorting == 'cRollNo') {
		$sortBy = ' right(stu.rollNo,3) ';
	}
	elseif ($sorting == 'uRollNo') {
		$sortBy = ' right(stu.universityRollNo,3) ';
	}
	elseif ($sorting == 'name') {
		$sortBy = ' studentName ';
	}
	$sortBy .= $REQUEST_DATA['ordering'];

	$resultDataArray = $studentReportsManager->getFinalReportData($classId, $groupStr, $queryPart,$sortBy,'', $tableName);


	$cnt = count($resultDataArray);

	$allDetailsArray['totalRecords'] = $cnt1;
	*/

	$labelId = $REQUEST_DATA['timetable']; //1
	$classId = $REQUEST_DATA['degree']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3
	$allDetailsArray = array();
	$allDetailsArray['grace'] = $REQUEST_DATA['grace'];
	$showUnivRollNo = $REQUEST_DATA['showUnivRollNo'];

	$showGraceMarks = $REQUEST_DATA['showGraceMarks'];
	$showTestMarks = $REQUEST_DATA['showMarks'];
	$includeGrace = false;


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

	if ($testTypeArray[0]['testTypeId'] != '') {
		foreach($testTypeArray as $testTypeRecord) {
			$testTypeId = $testTypeRecord['testTypeId'];
			$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
			if (in_array($testTypeCategoryId, $uniqueTTCategoryId)) {
				continue;
			}
			$uniqueTTCategoryArray[] = $testTypeRecord;
			$uniqueTTCategoryId[] = $testTypeCategoryId;
		}
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
	
	$studentArray2 = $studentReportsManager->getStudents($classId, $subjectId, $tableName, $groupId, $sortBy);
	$studentIdList2 = UtilityManager::makeCSList($studentArray2, 'studentId');
	if (empty($studentIdList2)) {
		$studentIdList2 = 0;
	}


	$lectureDeliveredArray = $studentReportsManager->getLectureDeliveredSum($studentIdList, $classId, $subjectId);
	$lectureDeliveredNewArray = array();

	if ($lectureDeliveredArray[0]['studentId'] != '') {
		foreach($lectureDeliveredArray as $record) {
			$lectureDeliveredNewArray[$record['studentId']] = $record['lectureDelivered'];
		}
	}
	$lectureDeliveredArray = null;

	$lectureAttendedArray = $studentReportsManager->getLectureAttendedSum($studentIdList, $classId, $subjectId);
	$lectureAttendedNewArray = array();

	if ($lectureAttendedArray[0]['studentId'] != '') {
		foreach($lectureAttendedArray as $record) {
			$lectureAttendedNewArray[$record['studentId']] = $record['lectureAttended'];
		}
	}
	$lectureAttendedArray = null;
	
	
	$cond="AND att.classId ='$classId' AND att.subjectId ='$subjectId' AND  att.studentId  IN ($studentIdList)  ";
	$dutyMedicalLeaveDetailsArray = $commonQueryManager->getStudentAttendanceReport($cond,'',$consolidated);
	$dutyLeaveArray = array();
	$medicalLeaveArray = array();
	foreach($dutyMedicalLeaveDetailsArray as $record) {
	    $dutyLeaveArray[$record['studentId']] = $record['leaveTaken'];
	    $medicalLeaveArray[$record['studentId']] = $record['transferMarksMedicalLeaveTaken'];
	}
	$dutyMedicalLeaveDetailsArray = null;

	/*$dutyLeaveArray = $studentReportsManager->getDutyLeaveSum($studentIdList, $classId, $subjectId);
	$dutyLeaveNewArray = array();

	if ($dutyLeaveArray[0]['studentId'] != '') {
		foreach($dutyLeaveArray as $record) {
			$dutyLeaveNewArray[$record['studentId']] = $record['dutyLeave'];
		}
	}
	$dutyLeaveArray = null;*/



	$queryPart = '';
	$testTypeList = '';
	if ($testTypeArray[0]['testTypeId'] != '') {
		$i = 0;
		foreach($testTypeArray as $testTypeRecord) {
			$testTypeId = $testTypeRecord['testTypeId'];
			$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
			$testTypeName = $testTypeRecord['testTypeName'];
			$isAttendanceCategory = $testTypeRecord['isAttendanceCategory'];
			$testTypeMaxMarks = $testTypeRecord['maxMarks'];

			if ($isAttendanceCategory == 1) {
				$attendanceArray = $studentReportsManager->getAttendanceResultMarks($studentIdList, $classId, $subjectId, $testTypeId);
				$attendanceNewArray = array();

				if ($attendanceArray[0]['studentId'] != '') {
					foreach($attendanceArray as $record) {
						$attendanceNewArray[$record['studentId']] = $record['ms_attendance'];
					}
				}

				$attendanceArray = null;

			}
			else {
				$testTransferredMarksArray[$testTypeCategoryId] = $studentReportsManager->getTestTransferredResultMarks($studentIdList, $classId, $subjectId, $testTypeId);
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

				$testMarksArray[] = $studentReportsManager->getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks);

				$testIndexArray = $studentReportsManager->getDistinctTests($testTypeCategoryId, $classId, $subjectId, $testGroup);
				$allDetailsArray[$testTypeCategoryId] = $testIndexArray;
			}
			$i++;
		}
	}
	$allDetailsArray['testTypes'] = $testTypeArray;

	$testTransferredMarksNewArray = array();
	if (count($testTransferredMarksArray)) {
		foreach($testTransferredMarksArray as $testTypeCategoryId => $recordArray) {
			if ($recordArray[0]['studentId'] != '') {
				foreach($recordArray as $record) {
					$studentId = $record['studentId'];
					$marksScored = $record['marksScored'];
					$testTransferredMarksNewArray[$studentId]['ms_'.$testTypeCategoryId] = $marksScored;
				}
			}
		}
	}
	$testTransferredMarksArray = null;

	//external Marks


	
	 $externalArray = $studentReportsManager->getStudentExteralMarks($studentIdList, $classId, $subjectId);     
	 $externalMarksArray = array();
	 if ($externalMarksArray[0]['studentId'] != '') {
	 foreach($externalArray as $record) {
		$studentId = $record['studentId'];
		$externalMarksArray[$studentId]['maxMarks'] =  $record['maxMarks'];   
		$externalMarksArray[$studentId]['marksScored'] = $record['marksScored'];   
	    }
	}
    
	$graceMarksArray = $studentReportsManager->getGraceMarks($studentIdList, $classId, $subjectId);
	$graceMarksNewArray = array();

	if ($graceMarksArray[0]['studentId'] != '') {
		foreach($graceMarksArray as $record) {
				$studentId = $record['studentId'];
				$marksScored = $record['graceMarks'];
				$graceMarksNewArray[$studentId]['grace'] =  $record['graceMarks'];   
				$graceMarksNewArray[$studentId]['Int'] =  $record['internalGraceMarks'];   
				$graceMarksNewArray[$studentId]['Ext'] =  $record['externalGraceMarks'];   
				$graceMarksNewArray[$studentId]['Tot'] =  $record['totalGraceMarks'];   
				//$graceMarksNewArray[$studentId] = $marksScored;
		}
	}

	$graceMarksArray = null;

	$totalTransferredMarksArray = $studentReportsManager->getTotalTransferredResultMarks($studentIdList, $classId, $subjectId);
	$totalTransferredMarksNewArray = array();

	if ($totalTransferredMarksArray[0]['studentId'] != '') {
		foreach($totalTransferredMarksArray as $record) {
			$studentId = $record['studentId'];
			$marksScored = $record['marksScored'];
			$totalTransferredMarksNewArray[$studentId] = $marksScored;
		}
	}
	$totalTransferredMarksArray = null;

	$studentTestArray = array();
	if (is_array($testMarksArray)) {
		foreach($testMarksArray as $testRecordArray) {
			if ($testRecordArray[0]['studentId'] != '') {
				foreach($testRecordArray as $record) {
					$studentId = $record['studentId'];
					$testName = $record['testName'];
					$marksScored = $record['marksScored'];
					$maxMarks = $record['maxMarks'];
					$studentTestArray[$studentId][$testName] = $marksScored;
					$studentMaxMarksArray[$studentId][$testName] = $maxMarks;
				}
			}
		}
	}
	$testMarksArray = null;

	$i = 0;

	if ($studentArray[0]['studentId'] != '') {
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
			if (!is_array($studentMaxMarksArray[$studentId])) {
				$studentMaxMarksArray[$studentId] = array();
			}
			foreach($studentMaxMarksArray[$studentId] as $testName => $maxMarks) {
				$studentTestMaxMarksArray[$i][$testName]['maxMarks'] = round($maxMarks,1);
			}
			if (array_key_exists($studentId, $testTransferredMarksNewArray)) {
				foreach($testTransferredMarksNewArray[$studentId] as $testName => $marksScored) {
					$studentArray[$i][$testName] = $marksScored;
				}
			}
			 if($graceMarksNewArray[$studentId]['Int']!='') { 
				$studentArray[$i]['ms_grace'] = $graceMarksNewArray[$studentId];
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

//echo "<pre>"; print_r($allDetailsArray); die;

	$classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
	$className = $classNameArray[0]['className'];
	$className2 = str_replace("-",' ',$className);

	$subCode = 'All';
	if ($subjectId != 'all') {
		$subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode', "WHERE subjectId  = $subjectId");
		$subCode = $subCodeArray[0]['subjectCode'];
	}

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$reportManager->setReportWidth(900);
	$reportManager->setReportHeading('Final Internal Marks Report');


##########################################################
	 $totalMarksArray = $studentReportsManager->getSubjectTotalMarks($studentIdList2, $classId, $subjectId);
	 $totalMarksScored = $totalMarksArray[0]['marksScored'] + $totalGracemarks;
	 $maxMarks = $totalMarksArray[0]['maxMarks'];

	 if ($maxMarks == 0) {
		 $average = 0;
	 }
	 else {
		$average = round(($totalMarksScored*100) / $maxMarks,2);
	 }

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
	$allDetailsArray['maxMarks'] = $studentTestMaxMarksArray;

	$rangeArray = $studentReportsManager->getRanges();
	$rangeStr = '<table align="center" rules="cols" border="1"><tr><th '.$reportManager->getReportHeadingStyle().'><u>Marks Scored</u></td><th '.$reportManager->getReportHeadingStyle().'><u>Student Count</u></td></tr>';
	foreach($rangeArray as $rangeRecord) {
		$lowMarksValue = $rangeRecord['lowMarksValue'];
		$highMarksValue = $rangeRecord['highMarksValue'];
		if ($includeGrace == false) {
			$studentCountArray = $studentReportsManager->getRangeStudentCount($studentIdList2, $classId, $subjectId, $lowMarksValue, $highMarksValue);
		}
		else {
			$studentCountArray = $studentReportsManager->getRangeStudentCountWithGrace($studentIdList2, $classId, $subjectId, $lowMarksValue, $highMarksValue);
		}


		$studentCount = $studentCountArray[0]['studentCount'];
		if ($rangeStr != '') {
			//$rangeStr .= '<br>';
		}
		$rangeStr .= "<tr><td ".$reportManager->getReportDataStyle().">$lowMarksValue - $highMarksValue</td><td ".$reportManager->getReportDataStyle().">$studentCount</td></tr>";
	}
	$rangeStr .= "</table>";


##########################################################

	$reportManager->setReportInformation("$className2, Subjects : $subCode, Group : $groupCode<br>Average : $average | Teachers : $teacherName | Subject : $subjectName");

	$reportTableHead = array();
	//associated key col.label,col. width,data align
	$reportTableHead['srNo'] = array('#','width="2%" align=right rowspan="3"', "align='right' ");
	if ($showUnivRollNo) {
		$reportTableHead['universityRollNo'] = array('U.Roll No.','width="5%" rowspan="3" align="left" ', 'align="left"');
	}
	$reportTableHead['rollNo'] = array('Roll No.','width=5% align="left" rowspan="3"' , 'align="left"');
	$reportTableHead['studentName']	= array('Student Name','width="10%" rowspan="3" align="left"', 'align="left"');

	$reportManager->setRecordsPerPage(22);
	$reportManager->setReportData($reportTableHead, $allDetailsArray);
	$reportManager->showFinalInternalMarksReport();
	//echo "<br class='page'>";
	//echo $rangeStr;

//$History : listFinalInternalReportPrint.php $
//




?>
