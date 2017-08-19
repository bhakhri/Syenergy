<?php
//-------------------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION for feedback category
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    define('MODULE','FeedBackCategoryMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    $feedbackCategoryManager = FeedBackManager::getInstance();
    
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
       //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' WHERE (ffc.feedbackCategoryName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
    
     ////////////   
    $totalArray                  = $feedbackCategoryManager->getTotalFeedBackCategory($filter);
    $feedbackCategoryRecordArray = $feedbackCategoryManager->getFeedBackCategoryList($filter,$limit);
    
    

// $History: initFeedBackCategoryList.php $
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