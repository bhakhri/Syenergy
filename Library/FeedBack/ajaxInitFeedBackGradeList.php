<?php
//----------------------------------------------------------------------------------
// Purpose: To store the records of FeedBackGrades
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeedBackGradesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedbackGradesManager = FeedBackManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ffg.feedbackGradeLabel LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffg.feedbackGradeValue LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffs.feedbackSurveyLabel LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackSurveyLabel';
    
     $orderBy = "  $sortField $sortOrderBy";         

    ////////////
    
    $totalArray                 = $feedbackGradesManager->getTotalFeedBackGrades($filter);
    $feedbackGradesRecordArray  = $feedbackGradesManager->getFeedBackGradesList($filter,$limit,$orderBy);
    $cnt = count($feedbackGradesRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $feedbackGradesRecordArray[$i]['feedbackGradeId'] , 'srNo' => ($records+$i+1) ),$feedbackGradesRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitFeedBackGradeList.php $
//
//*****************  Version 3  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Library/FeedBack
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Library/FeedBack
//modified in feedback label & role wise graph
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 13/01/09   Time: 16:34
//Updated in $/Leap/Source/Library/FeedBack
//Modified Code as one field is added in feedback_grade table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>