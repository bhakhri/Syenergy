<?php 
//This file is used as printing version for payment history.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudyPeriodManager.inc.php");
    $studyPeriodManager = StudyPeriodManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions = ' AND (stp.periodName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR stp.periodValue LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR per.periodicityName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'periodName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


	$totalArray = $studyPeriodManager->getTotalStudyPeriod($conditions);
    $recordArray = $studyPeriodManager->getStudyPeriodList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Study Period Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%"', "align='center' ");
    $reportTableHead['periodValue']         =   array('Period Value','width=10% align="right"', 'align="right"');
    $reportTableHead['periodicityCode']     =   array('Periodicity','width="35%" align="left" ', 'align="left"');
    $reportTableHead['periodName']          =   array('Period Name','width=35% align="left"', 'align="left"');
	
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: studyPeriodPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/08/09    Time: 14:28
//Updated in $/LeapCC/Templates/StudyPeriod
//Done bug fixing.
//bug ids---
//0000825,0000826,0000833,0000834,0000835,0000836,0000837
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/07/09    Time: 19:29
//Updated in $/LeapCC/Templates/StudyPeriod
//Done bug fixing.
//Bug ids---
//0000483,0000484,0000487,000489,0000485,0000486,0000488,
//0000490,0000491,0000492
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudyPeriod
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:33p
//Created in $/Leap/Source/Templates/StudyPeriod
//Added functionality for study period report print
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:23p
//Created in $/Leap/Source/Templates/Degree
//Added functionality for degree  report print
?>