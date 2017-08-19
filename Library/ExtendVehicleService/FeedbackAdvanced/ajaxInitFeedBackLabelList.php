<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of FeedBack Labels in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_Labels');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedbackLabelManager.inc.php");
    $feedBackLabelManager = FeedbackLabelManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $activeType=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $activeType=0;
       }
	   else {
		   $activeType=-1;
	   }
		
	   
	   $filter = ' AND ( ffl.feedbackSurveyLabel LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  OR ffl.noOfAttempts LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffl.isActive LIKE "%'.$activeType.'%" 
	   OR DATE_FORMAT(ffl.visibleFrom,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"
	   OR DATE_FORMAT(ffl.visibleTo,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackSurveyLabel';
    
     $orderBy = " ffl.$sortField $sortOrderBy";         

    ////////////
    $totalArray               = $feedBackLabelManager->getTotalFeedBackLabel($filter);
    $feedbackLabelRecordArray = $feedBackLabelManager->getFeedBackLabelList($filter,$limit,$orderBy);
    $cnt = count($feedbackLabelRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $feedbackLabelRecordArray[$i]['visibleFrom'] = UtilityManager::formatDate($feedbackLabelRecordArray[$i]['visibleFrom'] );
        $feedbackLabelRecordArray[$i]['visibleTo']   = UtilityManager::formatDate($feedbackLabelRecordArray[$i]['visibleTo'] );
        
        if($feedbackLabelRecordArray[$i]['isActive']==1){
            $feedbackLabelRecordArray[$i]['isActive']='Yes';
        }
        else{
            $feedbackLabelRecordArray[$i]['isActive']='No';
        }
        
        //$chkStr='&nbsp;'.NOT_APPLICABLE_STRING;
        
       /* if($feedbackLabelRecordArray[$i]['usedSurveyId']==-1){
            $chkStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$feedbackLabelRecordArray[$i]['feedbackSurveyId'].');return false;"></a>
                     <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteFeedBackLabel('.$feedbackLabelRecordArray[$i]['feedbackSurveyId'].');"/></a>';
        }*/
        $valueArray = array_merge(array('action' => $feedbackLabelRecordArray[$i]['feedbackSurveyId'] , 'srNo' => ($records+$i+1) ),$feedbackLabelRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitFeedBackLabelList.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 18/02/10   Time: 15:50
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002895,0002896,0002894,0002892,
//0002891,0002882,0002833
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/03/10    Time: 4:35p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Updated file to add edit/delete checks
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/01/10   Time: 16:03
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified "Feedback Label Master(Advanced)" as two new fields are added
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 1:10p
//Created in $/LeapCC/Library/FeedbackAdvanced
//created file under feedback advanced label module

?>