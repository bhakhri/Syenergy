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
    require_once(MODEL_PATH . "/InstituteManager.inc.php");
    $instituteManager = InstituteManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        /// Search filter /////  
       $conditions = ' AND (ins.instituteName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteWebsite LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.employeePhone LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ins.instituteEmail LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    
    
	//$conditions = '';
	//if (count($conditionsArray) > 0) {
		//$conditions = ' AND '.implode(' AND ',$conditionsArray);
	//}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'instituteName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $instituteManager->getInstituteList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Institute Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="2%"', "align='center' ");
    $reportTableHead['instituteName']       =   array('Name','width=30% align="left"', 'align="left"');
	$reportTableHead['instituteCode']		=	array('Code','width=8% align="left"', 'align="left"');
	$reportTableHead['instituteAbbr']		=	array('Abbr.','width="8%" align="left" ', 'align="left"');
    $reportTableHead['instituteWebsite']    =   array('Website','width="19%" align="left" ', 'align="left"');
    $reportTableHead['employeePhone']       =   array('Ph. No','width="14%" align="left" ', 'align="left"');
    $reportTableHead['instituteEmail']      =   array('Email','width="20%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: institutePrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Created in $/LeapCC/Templates/Institute
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
?>