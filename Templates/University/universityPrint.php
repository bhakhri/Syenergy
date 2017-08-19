<?php 
//This file is used as printing version for payment history.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/UniversityManager.inc.php");
    $universityManager = UniversityManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        /// Search filter /////  
       $conditions = ' AND (un.universityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityWebsite LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.contactPerson LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.contactNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityEmail LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $universityManager->getUniversityList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('University Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="2%"', "align='center' ");
    $reportTableHead['universityName']      =   array('Name','width=25% align="left"', 'align="left"');
	$reportTableHead['universityCode']		=	array('Code','width=12% align="left"', 'align="left"');
	$reportTableHead['universityAbbr']		=	array('Abbr.','width="8%" align="left" ', 'align="left"');
    $reportTableHead['universityWebsite']   =   array('Website','width="18%" align="left" ', 'align="left"');
    $reportTableHead['contactPerson']       =   array('Contact Person','width="15%" align="left" ', 'align="left"');
    $reportTableHead['contactNumber']       =   array('Ph. No.','width="15%" align="left" ', 'align="left"');
    $reportTableHead['universityEmail']     =   array('Email','width="20%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: universityPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:41
//Created in $/LeapCC/Templates/University
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
?>