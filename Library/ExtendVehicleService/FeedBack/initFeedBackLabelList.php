<?php
//------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "feedback_survey" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    define('MODULE','CreateFeedBackLabels');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    $feedBackLabelManager = FeedBackManager::getInstance();
    
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
       //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (ffl.feedbackSurveyLabel LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';  
    }
    
     ////////////   
    $totalArray = $feedBackLabelManager->getTotalFeedBackLabel($filter);
    $feedbackLabelRecordArray = $feedBackLabelManager->getFeedBackLabelList($filter,$limit);

// $History: initFeedBackLabelList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:37a
//Updated in $/LeapCC/Library/FeedBack
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeedBack
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>