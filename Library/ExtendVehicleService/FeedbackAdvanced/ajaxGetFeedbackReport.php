<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
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
    
    ////////////
    $feedbackTotalArray = $fbMgr->getFeedbackListCount($condition);
    $totalRecord = $feedbackTotalArray[0]['cnt'];
    
    $feedbackRecordArray = $fbMgr->getFeedbackList($condition,$orderBy,$limit);
    $cnt = count($feedbackRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),$feedbackRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecord.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>