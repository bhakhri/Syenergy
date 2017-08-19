<?php 
//This file is used as printing version for blocks.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BuildingManager.inc.php");
    $buildingManager = BuildingManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' WHERE (bu.buildingName LIKE "%'.add_slashes($search).'%" OR bu.abbreviation LIKE "%'.add_slashes($search).'%")';         
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'buildingName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy="bu.$sortField $sortOrderBy"; 


    $recordArray = $buildingManager->getBuildingList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Building Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['buildingName']           =   array('Building Name','width=50% align="left"', 'align="left"');
	$reportTableHead['abbreviation']		=	array('Abbr.','width=40% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: buildingPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Templates/Building
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Building
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Building
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 10:37a
//Created in $/Leap/Source/Templates/Building
//Added functionality for building report print
?>