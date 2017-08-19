<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
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
		$toDateArr = explode('-',$toDateD);
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

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();

	$totalRecordsArray = $studentManager->countRecords($conditions);
	$totalRecords = $totalRecordsArray[0]['cnt'];
	$studentRecordArray = $studentManager->getAllDetails($conditions, $REQUEST_DATA['sortField'].' '.$REQUEST_DATA['sortOrderBy'], $limit);

    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$valueArray = array_merge(array('act' =>  $showlink, 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
		if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
		}
		else {
			$json_val .= ','.json_encode($valueArray);           
		}
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalRecords.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 

//$History: initAllDetailsReport.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Library/StudentReports
//added code for multiple tables.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/23/08   Time: 11:59a
//Updated in $/LeapCC/Library/StudentReports
//changed subjectId to courseId
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/22/08   Time: 6:11p
//Updated in $/LeapCC/Library/StudentReports
//added code for groups and subjects
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/26/08    Time: 1:08p
//Updated in $/Leap/Source/Library/StudentReports
//fixed minor issue related to DOB
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/22/08    Time: 12:21p
//Updated in $/Leap/Source/Library/StudentReports
//changed periodicityId to studyPeriodId
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/13/08    Time: 2:47p
//Created in $/Leap/Source/Library/StudentReports
//file added for "allDetailsReport.php"
//

?>