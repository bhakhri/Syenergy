<?php 
//This file is used as printing version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 14.01.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeedbackQuestionManager.inc.php");
    $feedbackQuestionManager = FeedbackQuestionManager::getInstance();

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
       $filter = ' AND ( ffq.feedbackQuestion LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR fqs.feedbackQuestionSetName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR fas.answerSetName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestionSetName';
    
     $orderBy = " $sortField $sortOrderBy";  


    $recordArray = $feedbackQuestionManager->getFeedBackQuestionsList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Feedback Advanced Questions Report');
    $reportManager->setReportInformation("Search By: ".trim($REQUEST_DATA['searchbox']));
	 
	$reportTableHead						  =	array();
	$reportTableHead['srNo']				  =   array('#','width="3%" align="left" valign="top"', "valign='top' align='left' ");
    $reportTableHead['feedbackQuestionSetName']   =   array('Question Set','width=20% align="left"', "valign='top' align='left' "); 
    $reportTableHead['feedbackQuestion']      =   array('Question','width=50% align="left"', "valign='top' align='left' "); 
    $reportTableHead['answerSetName']  =   array('Answer Set','width=15% align="left"', "valign='top' align='left' "); 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: feedBackQuestionsPrint.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:39p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created file under question master in feedback module
//

?>