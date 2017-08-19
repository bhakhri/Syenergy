<?php 
// This file is used as printing version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/FollowUpManager.inc.php");;
    $followUpManager = FollowUpManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $filter = ''; 
    if (!empty($search)) {
       $search=strtoupper(trim($REQUEST_DATA['searchbox']));
       $contactedVia=-1;
       if($search=='EMAIL'){
           $contactedVia=1;
       }
       elseif($search=='LANDLINE'){
           $contactedVia=2;
       }
       elseif($search=='MOBILE'){
           $contactedVia=3;
       }
       elseif($search=='SMS'){
           $contactedVia=4;
       }
       $filter = ' AND ( c.companyCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.contactedPerson LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.designation LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.contactedVia LIKE "'.$contactedVia.'%")';         
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyCode';
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $followUpManager->getFollowUpList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $recordArray[$i]['contactedOn'] =UtilityManager::formatDate($recordArray[$i]['contactedOn']);
       if($recordArray[$i]['contactedVia']==1){
           $recordArray[$i]['contactedVia']='Email';
       }
       elseif($recordArray[$i]['contactedVia']==2){
           $recordArray[$i]['contactedVia']='Landline';
       }
       elseif($recordArray[$i]['contactedVia']==3){
           $recordArray[$i]['contactedVia']='Mobile';
       }
       else{
           $recordArray[$i]['contactedVia']='SMS';
       }
       
 	  $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Follow Ups Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead				    =	array();
	$reportTableHead['srNo']		    =   array('#','width=2% align="left"', 'align="left"');
    $reportTableHead['companyCode']     =   array('Company','width=15% align="left"', 'align="left"');
	$reportTableHead['contactedOn']	    =   array('Contacted On','width=10% align="center"', 'align="center"');
	$reportTableHead['contactedVia']	=   array('Contacted Via','width="10%" align="left" ', 'align="left"');
    $reportTableHead['contactedPerson'] =   array('Contacted Person.','width="15%" align="left" ', 'align="left"');
    $reportTableHead['designation']     =   array('Designation','width="10%" align="left" ', 'align="left"');
    
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>