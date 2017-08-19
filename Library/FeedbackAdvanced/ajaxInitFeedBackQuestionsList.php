<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of FeedBack Labels in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (14.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_Questions');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedbackQuestionManager.inc.php");
    $feedBackQuestionManager = FeedbackQuestionManager::getInstance();

    /////////////////////////
                                                                                                                                                                                                                 
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (  fqs.feedbackQuestionSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffq.feedbackQuestion LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR fas.answerSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestion';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray                   = $feedBackQuestionManager->getTotalFeedBackQuestions($filter);
    $feedbackQuestionsRecordArray = $feedBackQuestionManager->getFeedBackQuestionsList($filter,$limit,$orderBy);
    $cnt = count($feedbackQuestionsRecordArray);
    
    for($i=0;$i<$cnt;$i++) { 
        if(strlen($feedbackQuestionsRecordArray[$i]['feedbackQuestion'])>100){
          $feedbackQuestionsRecordArray[$i]['feedbackQuestion']=substr($feedbackQuestionsRecordArray[$i]['feedbackQuestion'],0,97).'...';
        }
        $valueArray = array_merge(array('action' => $feedbackQuestionsRecordArray[$i]['feedbackQuestionId'] , 'srNo' => ($records+$i+1) ),$feedbackQuestionsRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitFeedBackQuestionsList.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:38p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under question master in feedback module
//

?>