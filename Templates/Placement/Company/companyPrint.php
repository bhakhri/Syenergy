<?php 
// This file is used as printing version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/CompanyManager.inc.php");
    $companyManager = CompanyManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $filter = ''; 
    if (!empty($search)) {
        $filter = ' AND ( companyName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR companyCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR landline LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR mobileNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR contactPerson LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emailId LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyName';
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $companyManager->getCompanyList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       
       $recordArray[$i]['companyCode']=htmlentities($recordArray[$i]['companyCode']);
       $recordArray[$i]['companyName']=htmlentities($recordArray[$i]['companyName']);
       $recordArray[$i]['landline']=htmlentities($recordArray[$i]['landline']);
       $recordArray[$i]['mobileNo']=htmlentities($recordArray[$i]['mobileNo']);
       $recordArray[$i]['contactPerson']=htmlentities($recordArray[$i]['contactPerson']);
       $recordArray[$i]['emailId']=htmlentities($recordArray[$i]['emailId']);
       
	   $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Company Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead				  =	array();
	$reportTableHead['srNo']		  =   array('#','width=2% align="left"', 'align="left"');
    $reportTableHead['companyName']   =   array('Name','width=25% align="left"', 'align="left"');
	$reportTableHead['companyCode']	  =   array('Code','width=12% align="left"', 'align="left"');
	$reportTableHead['landline']	  =   array('Landline','width="10%" align="left" ', 'align="left"');
    $reportTableHead['mobileNo']      =   array('Mobile No.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['contactPerson'] =   array('Contact Person','width="15%" align="left" ', 'align="left"');
    $reportTableHead['emailId']       =   array('Email Id','width="15%" align="left" ', 'align="left"');
    
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>