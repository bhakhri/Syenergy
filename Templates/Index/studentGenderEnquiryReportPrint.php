<?php 
//This file is used as printing version for city wise employee for management role.
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
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
	$conditionsArray = array();
	$qryString = "";

	$genderId = $REQUEST_DATA['genderId'];

	/* END: search filter */
	$conditions = "  AND sc.studentGender = '$genderId'";
    $recordArray = $dashboardManager->getStudentEnquiryGenderRecord($conditions," ORDER BY sc.enquiryDate DESC");

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$recordArray[$i]['studentName']= $recordArray[$i]['firstName'].' '.$recordArray[$i]['lastName']; 

		$sepText ="";
		$recordArray[$i]['mobileNumber']= $recordArray[$i]['mobileNumber']; 
		$cityName = $recordArray[$i]['cityName']; 
		if($recordArray[$i]['contactNumber'])
		{
			if($recordArray[$i]['mobileNumber'])
			{
				$sepText =", ";
			}
			$recordArray[$i]['mobileNumber'] .=$sepText.$recordArray[$i]['contactNumber'];
		}
		$sepText ="";
		$recordArray[$i]['mobileNumber'] = $recordArray[$i]['studentMobileNo']?$recordArray[$i]['studentMobileNo']:"-";

		$recordArray[$i]['studentPhone'] = $recordArray[$i]['studentPhone']?$recordArray[$i]['studentPhone']:"-";

		$recordArray[$i]['studentEmail'] = $recordArray[$i]['studentEmail']?$recordArray[$i]['studentEmail']:"-";
		$roleName = $recordArray[$i]['roleName'];
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	$userName =$recordArray[0]['userName'];
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student List for Consular '.$userName);
	   
	$reportTableHead				 =	array();
	$reportTableHead['srNo']		 =	array('#','width="3%"', 'align="center" valign="top"');
	$reportTableHead['studentName']	 =	array('Student Name','width=20% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentGender']=	array('G','width=1% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentPhone'] =	array('Contact No','width="9%" align="left"', 'align="left" valign="top"');
	$reportTableHead['mobileNumber'] =	array('Mobile','width="9%" align="left"', 'align="left" valign="top"');
	$reportTableHead['fatherName'] =	array('Father Name','width="15%" align="left"', 'align="left" valign="top"');
	$reportTableHead['studentEmail'] =	array('Email','width="9%" align="left"', 'align="left" valign="top"'); 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: studentGenderEnquiryReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/03/09    Time: 12:12p
//Created in $/LeapCC/Templates/Index
//Intial checkin for student enquiry demographics for admin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/02/09    Time: 6:16p
//Created in $/LeapCC/Templates/Index
//Intial checkin to display print report for student Enquiry
?>