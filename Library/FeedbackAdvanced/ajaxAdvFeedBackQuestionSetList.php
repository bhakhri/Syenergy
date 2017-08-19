<?php
//---------------------------------------------------------------------------------
// Purpose: To disply adv. category list with pagination and search , edit & delete 
// Author : Dipanjan Bbhattacharjee
// Created on : (09.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_QuestionSet');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackQuestionSetAdvancedManager.inc.php");
    $fbMgr = FeedBackQuestionSetAdvancedManager::getInstance();
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       $filter = '  AND ( qs.feedbackQuestionSetName LIKE "%'.$search.'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestionSetName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray    = $fbMgr->getTotalFeedbackQuestionSet($filter);
    $fbRecordArray = $fbMgr->getFeedbackQuestionSetList($filter,$limit,$orderBy);
    $cnt = count($fbRecordArray);
    
    
    for($i=0;$i<$cnt;$i++) {
       //if this is used then do not allow to edit/delete
       if($fbRecordArray[$i]['usedQuestionSetId']!=-1){
           $actionString=NOT_APPLICABLE_STRING.'&nbsp;&nbsp;&nbsp;';
       }
       else{
           $actionString='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$fbRecordArray[$i]['feedbackQuestionSetId'].');return false;"></a>
                          <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteQuestionSet('.$fbRecordArray[$i]['feedbackQuestionSetId'].');"/></a>';
       }
       
       $valueArray = array_merge(array('actionString' => $actionString , 'srNo' => ($records+$i+1) ),$fbRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxAdvFeedBackQuestionSetList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 12:30
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created  "Question Set Master"  module
?>