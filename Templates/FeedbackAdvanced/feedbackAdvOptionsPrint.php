<?php 
//This file is used as printing version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeedbackOptionsManager.inc.php");
    $feedbackOptionsManager = FeedbackOptionsManager::getInstance();

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
       $filter = ' AND (faso.optionLabel LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR faso.optionPoints LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR fas.answerSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR faso.printOrder LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'answerSetName';
    
    $orderBy = "  $sortField $sortOrderBy";

    $recordArray = $feedbackOptionsManager->getFeedBackOptionsList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Feedback Advanced Answer Set Options Report');
    $reportManager->setReportInformation("Search By: ".trim($REQUEST_DATA['searchbox']));
	 
	$reportTableHead						 =	array();
	$reportTableHead['srNo']				 =   array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['answerSetName'] =   array('Answer Set','width=28% align="left"', 'align="left"');
    $reportTableHead['optionLabel']   =   array('Option Text','width=28% align="left"', 'align="left"');
    $reportTableHead['optionPoints']  =   array('Option Weight','width=15% align="right"', 'align="right"');
    $reportTableHead['printOrder']    =   array('Print Order','width=15% align="right"', 'align="right"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: feedbackAdvOptionsPrint.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/14/10    Time: 6:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Resolved issue: 0002609,0002607,0002608,0002610,0002611,
//0002612,0002613
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/13/10    Time: 11:46a
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//set alignment of s.no.
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:20p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module
//

?>