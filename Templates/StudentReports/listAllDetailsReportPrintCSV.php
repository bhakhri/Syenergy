<?php 
//This file is used as internal file for csv part
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
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

	$csvData = '';
	$csvData .= "Sr, Student Name, Roll No, Programme, Period Name \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.$record['studentName'].','.$record['rollNo'].','.$record['programme'].','.$record['periodName'];
		$csvData .= "\n";
	}

ob_end_clean();
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
// It will be called downloaded.pdf
header('Content-Disposition: attachment; content-length: '.strlen($csvData).' filename="allDetailsReport.csv"');
// The PDF source is in original.pdf
echo $csvData;
die;
//$History: listAllDetailsReportPrintCSV.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Templates/StudentReports
//added code for multiple tables.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/23/08   Time: 11:59a
//Updated in $/LeapCC/Templates/StudentReports
//changed subjectId to courseId
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/22/08   Time: 6:11p
//Updated in $/LeapCC/Templates/StudentReports
//added code for groups and subjects
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/22/08    Time: 3:39p
//Updated in $/Leap/Source/Templates/StudentReports
//improved code.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/22/08    Time: 3:27p
//Updated in $/Leap/Source/Templates/StudentReports
//changed to study period as told by kabir sir
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/13/08    Time: 2:49p
//Created in $/Leap/Source/Templates/StudentReports
//file added for "allDetailsReport.php" - csv part
//

?>