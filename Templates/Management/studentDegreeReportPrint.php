<?php 
//This file is used as printing version for degree wise student report for management role.
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/Management/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";

	//employee role
	$degreeId = $REQUEST_DATA['degreeId'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sc.firstName';

	$orderBy="$sortField $sortOrderBy"; 

	/* END: search filter */
	$conditions = "  AND dr.degreeId =$degreeId";
    $recordArray = $studentManager->getStudentDegreeWiseList($conditions,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$recordArray[$i]['firstName']= $recordArray[$i]['firstName'].' '.$recordArray[$i]['lastName']; 

		//$recordArray[$i]['dateOfAdmission']= (UtilityManager::formatDate($recordArray[0]['dateOfAdmission'])); 
		//$recordArray[$i]['dateOfBirth']= (UtilityManager::formatDate($recordArray[0]['dateOfBirth'])); 
		$degreeName = $recordArray[$i]['degreeName'];
		$recordArray[$i]['fatherName']= $titleResults[$recordArray[$i]['fatherTitle']].' '.$recordArray[$i]['fatherName']; 
			 
		$recordArray[$i]['studentMobileNo'] = $recordArray[$i]['studentMobileNo']?$recordArray[$i]['studentMobileNo']:"-";
		$recordArray[$i]['studentEmail'] = $recordArray[$i]['studentEmail']?$recordArray[$i]['studentEmail']:"-";
		$roleName = $recordArray[$i]['roleName'];
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	 
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student List for '.$degreeName.' Branch');
	  
	 //,dateOfAdmission,dateOfBirth,fatherTitle,fatherName   

	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%"', 'align="center" valign="top"');
	$reportTableHead['firstName']		=	array('Student','width=20% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentGender']	=	array('G','width=2% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentEmail']	=	array('Email','width="9%" align="left" ', 'align="left" valign="top"');
	$reportTableHead['studentMobileNo']	=	array('Mobile','width="9%" align="left"', 'align="right" valign="top"');
	$reportTableHead['dateOfAdmission']	=	array('Admission','width="7%" align="left"', 'align="left" valign="top"');
	$reportTableHead['dateOfBirth']		=	array('DOB','width="9%" align="left"', 'align="left" valign="top"');

	$reportTableHead['fatherName']	=	array('Father Name','width="19%" align="left"', 'align="left" valign="top"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: studentDegreeReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
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