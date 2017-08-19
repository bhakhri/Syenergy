<?php 
//This file is used as printing version for hostel wise student report for management role.
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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

	//hostelId code
	$hostelId = $REQUEST_DATA['hostelId'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sc.firstName';

	$orderBy="$sortField $sortOrderBy"; 
	 
	$conditions = 	$hostelId==0?" AND sc.hostelId IS NULL":"AND sc.hostelId IS NOT NULL";
	$hostelName = 	$hostelId==0?" Day Scholar":"Hostel";
	/* END: search filter */
	//$conditions = "  AND scs.sectionId ='$sectionId'";
    $recordArray = $studentManager->getStudentHostelWiseList($conditions,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$recordArray[$i]['firstName']= $recordArray[$i]['firstName'].' '.$recordArray[$i]['lastName']; 

		$sectionName = $recordArray[$i]['sectionName'].'-'.$recordArray[$i]['sectionType'];

		$recordArray[$i]['fatherName']= $titleResults[$recordArray[$i]['fatherTitle']].' '.$recordArray[$i]['fatherName']; 
			 
		$recordArray[$i]['studentMobileNo'] = $recordArray[$i]['studentMobileNo']?$recordArray[$i]['studentMobileNo']:"-";
		$recordArray[$i]['studentEmail'] = $recordArray[$i]['studentEmail']?$recordArray[$i]['studentEmail']:"-";
		$roleName = $recordArray[$i]['roleName'];
        
        if($recordArray[$i]['hostelName']==''){
            $recordArray[$i]['hostelName']=NOT_APPLICABLE_STRING;
        }
        if($recordArray[$i]['roomName']==''){
            $recordArray[$i]['roomName']=NOT_APPLICABLE_STRING;
        }
        
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	 
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student List for '.$hostelName);
	  
	 //,dateOfAdmission,dateOfBirth,fatherTitle,fatherName   

	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%"', 'align="center" valign="top"');
	$reportTableHead['firstName']		=	array('Student','width=20% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentGender']	=	array('G','width=2% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentEmail']	=	array('Email','width="9%" align="left" ', 'align="left" valign="top"');
	$reportTableHead['studentMobileNo']	=	array('Mobile','width="9%" align="left"', 'align="right" valign="top"');
	$reportTableHead['dateOfAdmission']	=	array('Admission','width="7%" align="left"', 'align="left" valign="top"');
	$reportTableHead['dateOfBirth']		=	array('DOB','width="9%" align="left"', 'align="left" valign="top"');
	$reportTableHead['fatherName']	    =	array('Father Name','width="19%" align="left"', 'align="left" valign="top"');
    
    if($hostelId==1){
      $reportTableHead['hostelName']    =    array('Hostel','width="10%" align="left"', 'align="left" valign="top"');
      $reportTableHead['roomName']      =    array('Room','width="10%" align="left"', 'align="left" valign="top"');    
    }
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: studentHostelReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>