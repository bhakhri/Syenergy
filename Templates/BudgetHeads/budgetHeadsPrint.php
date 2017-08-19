<?php 
// This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BudgetHeadsManager.inc.php");
    $budgetManager = BudgetHeadsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $filter = ''; 
  	if($REQUEST_DATA['searchbox'] == "Guest House"){
		$REQUEST_DATA['searchbox']=1;
	}
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (headName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headAmount LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headTypeId Like"%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $budgetManager->getBudgetHeadsList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['headTypeId']=$globalBudgetHeadTypeArray[$recordArray[$i]['headTypeId']];
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Budget Heads Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead					   =   array();
	$reportTableHead['srNo']			   =   array('#','width=2% align="left"', 'align="left"');
    $reportTableHead['headName']           =   array('Head Name','width=35% align="left"', 'align="left"');
	$reportTableHead['headAmount']		   =   array('Head Amount','width=10% align="right"', 'align="right"');
	$reportTableHead['headTypeId']		   =   array('Head Type','width="10%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>