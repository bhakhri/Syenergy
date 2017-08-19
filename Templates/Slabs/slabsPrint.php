<?php 
//This file is used as printing version for slabs.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/SlabsManager.inc.php");
    $slabsManager = SlabsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' AND (sl.deliveredFrom LIKE "'.add_slashes($search).'%" OR sl.deliveredTo LIKE "'.add_slashes($search).'%")';                      
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'deliveredFrom';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" sl.$sortField $sortOrderBy"; 


	$totalArray  = $slabsManager->getTotalSlabs($conditions);
    $recordArray = $slabsManager->getSlabsList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Slabs Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%"', "align='center' ");
    $reportTableHead['deliveredFrom']            =   array('From','width=32% align="left"', 'align="left"');
    $reportTableHead['deliveredTo']            =   array('To','width=32% align="left"', 'align="left"');
    $reportTableHead['marks']            =   array('Marks','width=32% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: slabsPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Slabs
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 12:15p
//Created in $/Leap/Source/Templates/Slabs
//Added functionality for slabs report print
?>