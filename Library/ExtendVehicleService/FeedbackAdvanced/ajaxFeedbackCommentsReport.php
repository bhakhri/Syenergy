<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_CommentsReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbManager = FeedBackReportAdvancedManager::getInstance();

    /////////////////////////
    
    $classId          = trim($REQUEST_DATA['classId']);
    $labelId          = trim($REQUEST_DATA['labelId']);
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    $subjectId        = trim($REQUEST_DATA['subjectId']);
    
    if($labelId=='' or $timeTableLabelId==''){
        echo 'Required Pamameters Missing';
        die;
    }
    
    //check type of label.if it is of "subject",then classes can be fetched otherwise not
    $typeArray=$fbManager->getSurveyLabelType($labelId);
    
    if($typeArray[0]['cnt']!=0){
      if($classId==''){
         echo 'Required Pamameters Missing';
         die;
      }    
    }
    
    if($classId!=''){
      $filter=' AND f.feedbackSurveyId='.$labelId.' AND f.classId='.$classId.' AND fs.timeTableLabelId='.$timeTableLabelId;
    }
    else{
      $filter=' AND f.feedbackSurveyId='.$labelId.' AND fs.timeTableLabelId='.$timeTableLabelId;  
    }
    if($subjectId!=''){
        $filter .=' AND f.subjectId='.$subjectId;
    }
    
    $filter .=" AND trim(f.comments)!=''";
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (c.className LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR s.subjectCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR e.employeeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.comments LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    */
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    if($classId!=''){ 
     $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    }
    else{
     $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackSurveyLabel';   
    }
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
  /*  
   if($classId!=''){ 
     $totalArray = $fbManager->getTotalFeedbackComments($filter);
     $fbRecordArray = $fbManager->getFeedbackCommentsList($filter,$limit,$orderBy);
   }
   else{
     $totalArray = $fbManager->getTotalFeedbackCommentsFromEmployees($filter);
     $fbRecordArray = $fbManager->getFeedbackCommentsFromEmployeesList($filter,$limit,$orderBy);
   }
  */ 
    $totalArray = $fbManager->getTotalFeedbackCommentsFromEmployees($filter);
    $fbRecordArray = $fbManager->getFeedbackCommentsFromEmployeesList($filter,$limit,$orderBy);
    
    $cnt = count($fbRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        if(trim($fbRecordArray[$i]['comments'])==''){
            $fbRecordArray[$i]['comments']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['className'])==''){
            $fbRecordArray[$i]['className']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['subjectCode'])==''){
            $fbRecordArray[$i]['subjectCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['employeeName'])==''){
            $fbRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$fbRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxFeedbackCommentsReport.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:59p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Feedback Comments Report"
?>