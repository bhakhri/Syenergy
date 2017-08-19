<?php 
//This file is used as printing version for Marital employee for management role.
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

	//employee role
	$maritalId = $REQUEST_DATA['maritalId'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'emp.employeeName';

	$orderBy="$sortField $sortOrderBy"; 

	/* END: search filter */
	$conditions = "  AND isMarried =$maritalId";
    $recordArray = $dashboardManager->getEmployeeTeachList($conditions,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$recordArray[$i]['employeeName']= $recordArray[$i]['employeeName'].' ('.$recordArray[$i]['employeeCode'].')'; 

		$sepText ="";
		$recordArray[$i]['mobileNumber']= $recordArray[$i]['mobileNumber']; 
		if($recordArray[$i]['contactNumber'])
		{
			if($recordArray[$i]['mobileNumber'])
			{
				$sepText =", ";
			}
			$recordArray[$i]['mobileNumber'] .=$sepText.$recordArray[$i]['contactNumber'];
		}
		$sepText ="";
		$recordArray[$i]['mobileNumber'] = $recordArray[$i]['mobileNumber']?$recordArray[$i]['mobileNumber']:"-";
		$recordArray[$i]['emailAddress'] = $recordArray[$i]['emailAddress']?$recordArray[$i]['emailAddress']:"-";
		$roleName = $recordArray[$i]['roleName'];
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

    $maritalRole = $maritalId=="1"?"Married":"Un-Married";
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Employee List for '.$maritalRole.' Staff');
	   
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%"', 'align="center" valign="top"');
	$reportTableHead['employeeName']	=	array('Employee','width=20% align="left"', 'align="left" valign="top"');
	$reportTableHead['designationCode']	=	array('Dsgn','width=5% align="left"', 'align="left" valign="top"');
	$reportTableHead['gender']			=	array('Gender','width="9%" align="left" ', 'align="left" valign="top"');
	$reportTableHead['branchCode']		=	array('Branch','width="9%" align="left"', 'align="left" valign="top"');
	$reportTableHead['mobileNumber']	=	array('Contact','width="9%" align="left"', 'align="left" valign="top"');
	$reportTableHead['emailAddress']	=	array('Email','width="9%" align="left"', 'align="left" valign="top"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: scMaritalReportPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/23/08   Time: 2:55p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>