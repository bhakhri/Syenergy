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
    require_once(MODEL_PATH . "/DegreeManager.inc.php");
    $degreeManager = DegreeManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();

    //search filter
    $search = $REQUEST_DATA['searchbox'];

  /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (dg.degreeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeCode LIKE 
	   "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR studentId LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeAbbr LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';  
	 // $filter = ' AND (dg.degreeName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeCode LIKE 
	 // "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR dg.degreeAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';  
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'degreeName';
    
     
    if ($sortField == "studentId") {
		 $orderBy = " $sortField $sortOrderBy";
    }
	 else {
		 $orderBy = " dg.$sortField $sortOrderBy";
	 }

    ////////////
    $recordArray = $degreeManager->getDegreeList($filter,'',$orderBy);
    $cnt = count($recordArray);




	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Degree Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"',"align='left'");
    $reportTableHead['degreeName']          =   array('Degree Name','width=35% align="left"', 'align="left"');
	$reportTableHead['degreeCode']			=	array('Degree Code','width=25% align="left"', 'align="left"');
	$reportTableHead['degreeAbbr']			=	array('Abbr.','width="20%" align="left" ', 'align="left"');
	$reportTableHead['studentId']			=	array('Student Count','width=35% align="left"', 'align="right"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: degreePrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 31/07/09   Time: 14:38
//Updated in $/LeapCC/Templates/Degree
//Done bug fixing.
//bug ids---0000803,0000804
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Templates/Degree
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Degree
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:23p
//Created in $/Leap/Source/Templates/Degree
//Added functionality for degree  report print
?>