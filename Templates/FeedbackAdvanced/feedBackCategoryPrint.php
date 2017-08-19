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

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

   require_once(MODEL_PATH . "/FeedBackCategoryAdvancedManager.inc.php");
   $fbMgr = FeedBackCategoryAdvancedManager::getInstance();
    
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       $relId=-1;
       if(strtoupper($search)==strtoupper('General')){
          $relId=1;
       }
       else if(strtoupper($search)==strtoupper('Hostel')){
           $relId=2;
       }
       else if(strtoupper($search)==strtoupper('Transport')){
           $relId=3;
       }
       else if(strtoupper($search)==strtoupper('Subject')){
           $relId=4;
       }
       else{
           $relId=-1;
       }
       //$filter = ' AND ( fc.feedbackCategoryName LIKE "'.$search.'%" OR fc1.feedbackCategoryName LIKE "'.$search.'%" OR fs.feedbackSurveyLabel LIKE "'.$search.'%" OR fc.printOrder LIKE "'.$search.'%" OR st.subjectTypeName LIKE "'.$search.'%" OR fc.feedbackType LIKE "'.$relId.'%"  )';
       $filter = ' WHERE ( fc.feedbackCategoryName LIKE "'.$search.'%" OR fc1.feedbackCategoryName LIKE "'.$search.'%" OR fc.printOrder LIKE "'.$search.'%" OR st.subjectTypeName LIKE "'.$search.'%" OR fc.feedbackType LIKE "'.$relId.'%"  )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackCategoryName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $fbRecordArray = $fbMgr->getFeedbackCategoryList($filter,' ',$orderBy);
    $cnt = count($fbRecordArray);
    //fetch used categoryId list  
    $usageArray    = $fbMgr->getCategoryUsageList();
    
    for($i=0;$i<$cnt;$i++) {
       if($fbRecordArray[$i]['parentFeedbackCategoryId']==''){
           $fbRecordArray[$i]['parentFeedbackCategoryId']=NOT_APPLICABLE_STRING;
       }
       if($fbRecordArray[$i]['subjectTypeName']==''){
           $fbRecordArray[$i]['subjectTypeName']=NOT_APPLICABLE_STRING;
       }
       if($fbRecordArray[$i]['parentCategoryName']==''){
           $fbRecordArray[$i]['parentCategoryName']=NOT_APPLICABLE_STRING;
       }
       //check with global relationship array
       if(array_key_exists($fbRecordArray[$i]['feedbackType'],$advFeedBackRelationship)){
           $fbRecordArray[$i]['feedbackType']=$advFeedBackRelationship[$fbRecordArray[$i]['feedbackType']];
       }
       else{
           $fbRecordArray[$i]['feedbackType']=NOT_APPLICABLE_STRING;
       }
       
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$fbRecordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Feedback Category (Advanced) Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead						    =	array();
	$reportTableHead['srNo']				    =   array('#','width="2%" align="left"', "align='left' ");
    $reportTableHead['feedbackCategoryName']    =   array('Category','width=15% align="left"', 'align="left"');
    $reportTableHead['parentCategoryName']      =   array('Parent Category','width=15% align="left"', 'align="left"');
    $reportTableHead['feedbackType']            =   array('Relationship','width=10% align="left"', 'align="left"');
    $reportTableHead['subjectTypeName']         =   array('Subject Type','width=10% align="left"', 'align="left"');
    $reportTableHead['printOrder']              =   array('Print Order','width=8% align="right"', 'align="right"');
	
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: feedBackCategoryPrint.php $
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