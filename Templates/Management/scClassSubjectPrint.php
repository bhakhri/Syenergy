<?php 
//This file is used as printing version for class wise subject.
//
// Author :Rajeev Aggarwal
// Created on : 20-10-2008
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

	//classId
	$classId = $REQUEST_DATA['classId'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : ' cls.classId';

	$orderBy="$sortField $sortOrderBy"; 

	/* END: search filter */
	$conditions = " AND stc.classId = $classId";
    $recordArray = $dashboardManager->getClassSubjectList($conditions,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$className = $recordArray[$i]['className'];
		$recordArray[$i]['subjectName']=$recordArray[$i]['subjectName'].' ('.$recordArray[$i]['subjectCode'].')';
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Subject List for '.$className);
	   
	$reportTableHead					 =	array();
	$reportTableHead['srNo']			 =	array('#','width="3%"', "align='center' ");
	$reportTableHead['subjectName']		 =	array('Subject','width=50% align="left"', 'align="left"');
	$reportTableHead['optional']		 =	array('Optional','width=6% align="left"', 'align="left"');
	$reportTableHead['offered']			 =	array('Offered','width="6%" align="left" ', 'align="left"');
	$reportTableHead['credits']			 =	array('Credits','width="6%" align="right" ', 'align="right"');
	$reportTableHead['midSemTestDate']	 =	array('Mid-Sem','width="10%" align="left"', 'align="left"');
	$reportTableHead['finalExamDate']	 =	array('Final Exam','width="10%" align="left"', 'align="left"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: scClassSubjectPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/30/08   Time: 2:45p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>