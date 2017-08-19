<?php 
//This file is used as printing version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	 
	$subjectId = $REQUEST_DATA['subId'];
	$testCategoryId = $REQUEST_DATA['categoryId'];
	$classId = $REQUEST_DATA['classId'];
	
	//$totalArray = $dashboardManager->getTotalUserActivityLog($dateSelected);
    $recordArray = $dashboardManager->getCountTest($subjectId,$testCategoryId,$classId);
	$formattedDate = date('d-M-y');
	//$formattedDate = (UtilityManager::formatDate($dateSelected));//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	 
	$valueArray = array();
	$roleName ="";
    for($i=0;$i<$cnt;$i++) {
		 
		$recordArray[$i]['testDate']=UtilityManager::formatDate($recordArray[$i]['testDate']); 
		$recordArray[$i]['testTypeName']=$recordArray[$i]['testTypeName'].'-'.$recordArray[$i]['testIndex'];
		$recordArray[$i]['sectionName']=$recordArray[$i]['sectionName']." (".$recordArray[$i]['sectionType'].")"; 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportInformation("As On ".$formattedDate."<br> <b>Subject:</b>".$recordArray[0]['subjectCode']." <b>Test Type:</b>".$recordArray[0]['testTypeName']);
	$reportManager->setReportHeading("Test Type List Report");
	 
//userName  roleId  dateTimeIn  roleUserName  
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']		=	array('#','width="3%"', "align='center' ");

	$reportTableHead['groupShort']	=	array('Section Name','width=8% align="left"', 'align="left"');
	$reportTableHead['employeeName']=	array('Teacher','width=15% align="left"', 'align="left"');
	$reportTableHead['testTypeName']=	array('Test Type','width=15% align="left"', 'align="left"');
	$reportTableHead['testTopic']	=	array('Test Topic','width="15%" align="left" ', 'align="left"');
	$reportTableHead['testDate']	=	array('Date','width="8%" align="left"','align="left"');
	 
	$reportManager->setRecordsPerPage(50);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listAllTestTypeReportPrint.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/26/09    Time: 1:21p
//Updated in $/LeapCC/Templates/Index
//Updated test type distribution graph format with scroll div
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/22/09    Time: 2:58p
//Updated in $/LeapCC/Templates/Index
//Updated test type distribution to have unique value for class
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/19/09    Time: 5:56p
//Created in $/LeapCC/Templates/Index
//Updated Admin dashboard with role permission, test type and average
//attendance
?>