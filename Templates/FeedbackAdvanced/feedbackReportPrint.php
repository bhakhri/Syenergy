<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
       
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbMgr=FeedBackReportAdvancedManager::getInstance();
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
    global $sessionHandler;   
    
    $valueArray = array();  
    $valueArrayPoint = array();  
    
    $valueArray=$sessionHandler->getSessionVariable('IdToFeedbackScoreReport');   
    $valueArrayPoint=$sessionHandler->getSessionVariable('IdToFeedbackPointReport');   
    
    $timeTableName=trim($REQUEST_DATA['timeTableName']);      
    $labelName=trim($REQUEST_DATA['labelName']);      
    $teacherName=trim($REQUEST_DATA['teacherName']);      
    $className=trim($REQUEST_DATA['className']);   
    $categoryName=trim($REQUEST_DATA['categoryName']);    
 
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $categoryId=trim($REQUEST_DATA['categoryId']);  
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($labelId=='') {
      $labelId=0;  
    } 

    $search ='';
    $search .= "Time Table: ".$timeTableName;
    $search .= "<br>Label: ".$labelName;
    if($teacherName!='All') {
      $search .= "<br>Teacher Name: ".$teacherName;
    }
    if($className!='All') {
      $search .= "<br>Class: ".$className;
    } 
    if($categoryName!='All') {
      $search .= "<br>Category: ".$categoryName;
    }
    
    // Search filter 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'questionName';
    $orderBy = " $sortField $sortOrderBy";


    $condition = " WHERE feedbackadv_survey.timeTableLabelId = '$timeTableLabelId' AND 
                      feedbackadv_survey.feedbackSurveyId = '$labelId' AND feedbackadv_survey_mapping.roleId = '4' ";
                      
    if($classId!='' && $classId!='all') {
      $condition .= " AND feedbackadv_survey_mapping.classId = '$classId'";  
    }
    
    if($employeeId!='' && $employeeId!='all') {
      $condition .= " AND employee.employeeId = '$employeeId'";  
    }
    
    if($categoryId!='' && $categoryId!='all') {
      $condition .= " AND feedbackadv_survey_mapping.feedbackCategoryId = '$categoryId'";  
    }
    
  /*
    $feedbackRecordArray = $fbMgr->getFeedbackList($condition,$orderBy);
    $cnt = count($feedbackRecordArray);
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$feedbackRecordArray[$i]);
    }
 */   
   
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Feedback Report Print');
    $reportManager->setReportInformation("SearchBy: $search");
	 
    $reportTableHead		            = array();
	//associated key			                col.label,	col. width,	  data align	
	$reportTableHead['srNo']            = array('#',              'width="3%" align="left"', "align='left' ");
    $reportTableHead['questionName']    = array('Question',       'width="30%" align="left"', "align='left' ");
    for($i=0;$i<count($valueArrayPoint);$i++) { 
       $pp = "p_".$i; 
       $point = $valueArrayPoint[$i];
       $reportTableHead[$pp]            = array($point, 'width="4%" align="right"', "align='right' ");
    }    
    $reportTableHead['avg']             = array('Weight Average', 'width="10%" align="right"', "align='right' ");
    $reportTableHead['response']        = array('Response',       'width="10%" align="right"', "align='right' ");
    
/*  $reportTableHead['rollNo']                  =  array('Roll No.',    'width="10%" align="left"', "align='left' ");
    $reportTableHead['studentName']             =  array('Student Name','width=15% align="left"', 'align="left"');
	$reportTableHead['className']		        =  array('Class Name',  'width=22% align="left"', 'align="left"');
	$reportTableHead['tSubjectName']            =  array('Subject',     'width="18%" align="left" ', 'align="left"');
    $reportTableHead['employeeName']            =  array('Teacher',     'width="12%" align="left" ', 'align="left"');
    $reportTableHead['feedbackCategoryName']    =  array('Category',    'width=12% align="left"', 'align="left"');
    $reportTableHead['points']                  =  array('Points',      'width=15% align="right"', 'align="right"');  */
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
    
die;
?>