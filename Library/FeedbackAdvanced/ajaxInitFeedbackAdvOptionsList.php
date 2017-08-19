<?php
//----------------------------------------------------------------------------------
// Purpose: To store the records of FeedBackOptions
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (12.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_Options');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedbackOptionsManager.inc.php");
    $feedbackOptionsManager = FeedbackOptionsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (faso.optionLabel LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR faso.optionPoints LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR fas.answerSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR faso.printOrder LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'answerSetName';
    
     $orderBy = "  $sortField $sortOrderBy";         

    ////////////
    
    $totalArray                 = $feedbackOptionsManager->getTotalFeedBackOptions($filter);
    $feedbackOptionsRecordArray  = $feedbackOptionsManager->getFeedBackOptionsList($filter,$limit,$orderBy);
    $cnt = count($feedbackOptionsRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $feedbackOptionsRecordArray[$i]['answerSetOptionId'] , 'srNo' => ($records+$i+1) ),$feedbackOptionsRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitFeedbackAdvOptionsList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:19p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module
//

?>