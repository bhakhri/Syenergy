<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackLabelManager = FeedBackManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (ffc.feedbackCategoryName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackCategoryName';
    
    $orderBy = " ffc.$sortField $sortOrderBy"; 


    $recordArray = $feedBackLabelManager->getFeedBackCategoryList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Feedback Category Report');
    $reportManager->setReportInformation("Search By: ".trim($REQUEST_DATA['searchbox']));
	 
	$reportTableHead						 =	array();
	$reportTableHead['srNo']				 =   array('#','width="3%"', "align='center' ");
    $reportTableHead['feedbackCategoryName'] =   array('Category Name','width=97% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: feedBackCategoryPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:13
//Created in $/LeapCC/Templates/FeedBack
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 7  *****************
//User: Administrator Date: 12/06/09   Time: 10:55
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//bug ids---0000046,0000048,0000050
//
//*****************  Version 6  *****************
//User: Administrator Date: 11/06/09   Time: 12:13
//Updated in $/LeapCC/Templates/TestType
//Corrected spelling mistakes
//
//*****************  Version 5  *****************
//User: Administrator Date: 1/06/09    Time: 13:09
//Updated in $/LeapCC/Templates/TestType
//Corrected bugs------bug2_30-05-09.doc
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 12:55
//Updated in $/LeapCC/Templates/TestType
//Corrected bugs -----issues.doc.
//Bug ids-1,2,3
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Templates/TestType
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Templates/TestType
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TestType
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 2:10p
//Created in $/Leap/Source/Templates/TestType
//Added functionality for TestType report print and export to csv
?>