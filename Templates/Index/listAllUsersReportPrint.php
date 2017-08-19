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
	 
	$dateSelected = $REQUEST_DATA['dateSelected'];
	
	//$totalArray = $dashboardManager->getTotalUserActivityLog($dateSelected);
    $recordArray = $dashboardManager->getUserLogActivityList($dateSelected,' userName,usr.roleId,dateTimeIn DESC');

	$formattedDate = (UtilityManager::formatDate($dateSelected));//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	 
	$valueArray = array();
	$roleName ="";
    for($i=0;$i<$cnt;$i++) {
		 
		if($recordArray[$i]['roleUserName']=='')
			$recordArray[$i]['roleUserName']="Administrator";
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportInformation("As On $formattedDate ");
	$reportManager->setReportHeading("User Logged In List Report");
	 
//userName  roleId  dateTimeIn  roleUserName  
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%"', "align='center' ");
	$reportTableHead['roleUserName']		=	array('Name','width=25% align="left"', 'align="left"');
	$reportTableHead['userName']			=	array('UserName','width="10%" align="left" ', 'align="left"');
	$reportTableHead['roleName']				=	array('Role','width="9%" align="left"', 'align="left"');
	$reportTableHead['dateTimeIn']			=	array('Date/Time','width="15%" align="left"','align="left"');
	 
	$reportManager->setRecordsPerPage(50);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listAllUsersReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:06p
//Created in $/LeapCC/Templates/Index
//Intial Checkin
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 11/14/08   Time: 6:36p
//Updated in $/Leap/Source/Templates/ScIndex
//Updated unique user activity
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 11/14/08   Time: 3:14p
//Created in $/Leap/Source/Templates/ScIndex
//intial checkin
?>