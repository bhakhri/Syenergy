<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of FeedBack Labels in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (14.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeedBackQuestionsMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackQuestionManager = FeedBackManager::getInstance();

    /////////////////////////
                                                                                                                                                                                                                 
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND ( ffq.feedbackQuestion LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffl.feedbackSurveyLabel LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffc.feedbackCategoryName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestion';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray                   = $feedBackQuestionManager->getTotalFeedBackQuestions($filter);
    $feedbackQuestionsRecordArray = $feedBackQuestionManager->getFeedBackQuestionsList($filter,$limit,$orderBy);
    $cnt = count($feedbackQuestionsRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add quotaId in actionId to populate edit/delete icons in User Interface   
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
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 2  *****************
//User: Administrator Date: 14/05/09   Time: 12:59
//Updated in $/Leap/Source/Library/FeedBack
//Done bug fixing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>