<?php 
//This file is used as printing version for attendance report.
//
// Author :Ajinder Singh
// Created on : 17-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$studentReportsManager = StudentReportsManager::getInstance();

 	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	foreach($REQUEST_DATA as $key => $values) {
		$$key = $values;
	}
	$conditionsArray = array();
	
	if (!empty($rollNo)) {
		$conditionsArray[] = " a.rollNo = '$rollNo' ";
	}
	if (!empty($studentName)) {
		$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
	}
	if (!empty($degreeId)) {
		$conditionsArray[] = " b.degreeId in ($degreeId) ";
	}
	if (!empty($branchId)) {
		$conditionsArray[] = " b.branchId in ($branchId) ";
	}
	if (!empty($periodicityId)) {
		$conditionsArray[] = " b.studyPeriodId IN ($periodicityId) ";
	}

	if (!empty($fromDateA) and $fromDateA != '--') {
		$fromDateArr = explode('-',$fromDateA);
		$fromDateAM = intval($fromDateArr[0]);
		$fromDateAD = intval($fromDateArr[1]);
		$fromDateAY = intval($fromDateArr[2]);
		if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
			$thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
			$conditionsArray[] = " a.dateOfAdmission >= '$thisDate' ";
		}
	}

	if (!empty($toDateA) and $toDateA != '--') {
		$toDateArr = explode('-',$toDateA);
		$toDateAM = intval($toDateArr[0]);
		$toDateAD = intval($toDateArr[1]);
		$toDateAY = intval($toDateArr[2]);
		if (false !== checkdate($toDateAM, $toDateAD, $toDateAY)) {
			$thisDate = $toDateAY.'-'.$toDateAM.'-'.$toDateAD;
			$conditionsArray[] = " a.dateOfAdmission <= '$thisDate' ";
		}
	}

	if (!empty($fromDateD) and $fromDateD != '--') {
		$fromDateArr = explode('-',$fromDateD);
		$fromDateDM = intval($fromDateArr[0]);
		$fromDateDD = intval($fromDateArr[1]);
		$fromDateDY = intval($fromDateArr[2]);
		if (false !== checkdate($fromDateDM, $fromDateDD, $fromDateDY)) {
			$thisDate = $fromDateDY.'-'.$fromDateDM.'-'.$fromDateDD;
			$conditionsArray[] = " a.dateOfBirth >= '$thisDate' ";
		}
	}

	if (!empty($toDateD) and $toDateD != '--') {
		$toDateArr = explode('-',$toDateA);
		$toDateDM = intval($toDateArr[0]);
		$toDateDD = intval($toDateArr[1]);
		$toDateDY = intval($toDateArr[2]);
		if (false !== checkdate($toDateDM, $toDateDD, $toDateDY)) {
			$thisDate = $toDateDY.'-'.$toDateDM.'-'.$toDateDD;
			$conditionsArray[] = " a.dateOfBirth <= '$thisDate' ";
		}
	}

	if (!empty($gender)) {
		$conditionsArray[] = " a.studentGender = '$gender' ";
	}
	if (!empty($categoryId)) {
		$conditionsArray[] = " a.managementCategory = $categoryId ";
	}
	if (!empty($quotaId)) {
		$conditionsArray[] = " a.quotaId IN ($quotaId) ";
	}
	if (!empty($hostelId)) {
		$conditionsArray[] = " a.hostelId IN ('$hostelId') ";
	}
	if (!empty($busStopId)) {
		$conditionsArray[] = " a.busStopId IN ('$busStopId') ";
	}
	if (!empty($busRouteId)) {
		$conditionsArray[] = " a.busRouteId IN ($busRouteId) ";
	}
	if (!empty($cityId)) {
		$conditionsArray[] = " a.permCityId IN ($cityId) ";
	}
	if (!empty($stateId)) {
		$conditionsArray[] = " a.permStateId IN ($stateId) ";
	}
	if (!empty($countryId)) {
		$conditionsArray[] = " a.permCountryId IN ($countryId) ";
	}
	if (!empty($courseId)) {
		$conditionsArray[] = " a.studentId IN (SELECT studentId from ".ATTENDANCE_TABLE." WHERE subjectId IN ($courseId)) ";
	}
	if (!empty($groupId)) {
		$conditionsArray[] = " a.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
	}
	if (!empty($universityId)) {
		$conditionsArray[] = " b.universityId IN ($universityId) ";
	}
	if (!empty($instituteId)) {
		$conditionsArray[] = " b.instituteId IN ($instituteId) ";
	}

	$conditions = '';
	if (count($conditionsArray) > 0) {
		$conditions = ' AND '.implode(' AND ',$conditionsArray);
	}


	$studentRecordArray = $studentReportsManager->getAllDetails($conditions, $REQUEST_DATA['sortField'].' '.$REQUEST_DATA['sortOrderBy'], $limit);

    $cnt = count($studentRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => $i+1),$studentRecordArray[$i]);
    }

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('All Details Report');
	$reportManager->setReportInformation("");

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']					=	array('#',						'width="5%" align=right', "align='right' ");
	$reportTableHead['studentName']				=	array('Student Name',			'width=20% align="left"', 'align="left"');
	$reportTableHead['rollNo']					=	array('Roll No',				'width="25%" align="left" ', 'align="left"');
	$reportTableHead['programme']				=	array('Programme',					'width="30%" align="left"', 'align="left"');
	$reportTableHead['periodName']				=	array('Period Name',				'width="20%" align="left"','align="left"');



	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History : listMarksNotEnteredReportPrint.php $
//
?>