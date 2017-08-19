<?php 
//This file is used as printing version for bus stop wise student report for management role.
//
// Author :Rajeev Aggarwal
// Created on : 23-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/Management/ScDashboardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";

	//city code
	$busStopId = $REQUEST_DATA['busStopId'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sc.firstName';

	$orderBy="$sortField $sortOrderBy"; 

	/* END: search filter */
	$conditions = "  AND sc.busStopId =$busStopId";
    $recordArray = $dashboardManager->getStudentBusStopWiseList($conditions,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$recordArray[$i]['firstName']= $recordArray[$i]['firstName'].' '.$recordArray[$i]['lastName']; 

		//$recordArray[$i]['dateOfAdmission']= (UtilityManager::formatDate($recordArray[0]['dateOfAdmission'])); 
		//$recordArray[$i]['dateOfBirth']= (UtilityManager::formatDate($recordArray[0]['dateOfBirth'])); 
		$stopName = $recordArray[$i]['stopName'].'('.$recordArray[$i]['stopAbbr'].')';
		$recordArray[$i]['fatherName']= $titleResults[$recordArray[$i]['fatherTitle']].' '.$recordArray[$i]['fatherName']; 
			 
		$recordArray[$i]['studentMobileNo'] = $recordArray[$i]['studentMobileNo']?$recordArray[$i]['studentMobileNo']:"-";
		$recordArray[$i]['studentEmail'] = $recordArray[$i]['studentEmail']?$recordArray[$i]['studentEmail']:"-";
		$roleName = $recordArray[$i]['roleName'];
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	 
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student List for '.$stopName.' State');
	  
	 //,dateOfAdmission,dateOfBirth,fatherTitle,fatherName   

	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%"', 'align="center" valign="top"');
	$reportTableHead['firstName']		=	array('Student','width=20% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentGender']	=	array('Gender','width=5% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentEmail']	=	array('Email','width="9%" align="left" ', 'align="left" valign="top"');
	$reportTableHead['studentMobileNo']	=	array('Mobile','width="9%" align="left"', 'align="right" valign="top"');
	$reportTableHead['dateOfAdmission']	=	array('Admission','width="7%" align="left"', 'align="left" valign="top"');
	$reportTableHead['dateOfBirth']		=	array('DOB','width="9%" align="left"', 'align="left" valign="top"');

	$reportTableHead['fatherName']	=	array('fatherName','width="9%" align="left"', 'align="left" valign="top"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: scStudentBusStopReportPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/24/08   Time: 5:56p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>