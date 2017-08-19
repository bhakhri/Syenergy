<?php
//-------------------------------------------------------
// Purpose: To store the records of feedback categories 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (14.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeedBackCategoryMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedbackCategoryManager = FeedBackManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (ffc.feedbackCategoryName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackCategoryName';
    
     $orderBy = " ffc.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray                  = $feedbackCategoryManager->getTotalFeedBackCategory($filter);
    $feedbackCategoryRecordArray = $feedbackCategoryManager->getFeedBackCategoryList($filter,$limit,$orderBy);
    $cnt = count($feedbackCategoryRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add quotaId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $feedbackCategoryRecordArray[$i]['feedbackCategoryId'] , 'srNo' => ($records+$i+1) ),$feedbackCategoryRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitFeedBackCategoryList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Library/FeedBack
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
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
