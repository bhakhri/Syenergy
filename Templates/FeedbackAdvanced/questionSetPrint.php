<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/FeedBackQuestionSetAdvancedManager.inc.php");
    $fbMgr = FeedBackQuestionSetAdvancedManager::getInstance();
    //////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       $filter = '  AND ( qs.feedbackQuestionSetName LIKE "%'.$search.'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestionSetName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $fbRecordArray = $fbMgr->getFeedbackQuestionSetList($filter,' ',$orderBy);
    $cnt = count($fbRecordArray);
    
    $valueArray=array();
    for($i=0;$i<$cnt;$i++) {
       $valueArray[] = array_merge(array('actionString' => $actionString , 'srNo' => ($records+$i+1) ),$fbRecordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Feedback Question Set (Advanced) Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead						    =	array();
	$reportTableHead['srNo']				    =   array('#','width="2%" align="left"', "align='left' ");
    $reportTableHead['feedbackQuestionSetName'] =   array('Question Set','width=98% align="left"', 'align="left"');
	
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: questionSetPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
?>