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
    define('MODULE','CreateFeedBackLabels');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackLabelManager = FeedBackManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='general feedback') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='teacher feedback') {
           $type=2;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='general') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='teacher') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $activeType=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $activeType=0;
       }
	   else {
		   $activeType=-1;
	   }
			
		$filter = ' AND (ffl.feedbackSurveyLabel LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffl.surveyType LIKE "%'.$type.'%" OR ffl.noAttempts LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffl.isActive LIKE "%'.$activeType.'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackSurveyLabel';
    
     $orderBy = " ffl.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $feedBackLabelManager->getTotalFeedBackLabel($filter);
    $feedbackLabelRecordArray = $feedBackLabelManager->getFeedBackLabelList($filter,$limit,$orderBy);
    $cnt = count($feedbackLabelRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $feedbackLabelRecordArray[$i]['visibleFrom'] =UtilityManager::formatDate($feedbackLabelRecordArray[$i]['visibleFrom'] );
        $feedbackLabelRecordArray[$i]['visibleTo'] =UtilityManager::formatDate($feedbackLabelRecordArray[$i]['visibleTo'] );
        
        $valueArray = array_merge(array('action' => $feedbackLabelRecordArray[$i]['feedbackSurveyId'] , 'srNo' => ($records+$i+1) ),$feedbackLabelRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitFeedBackLabelList.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Library/FeedBack
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/29/09    Time: 11:29a
//Updated in $/LeapCC/Library/FeedBack
//put search on isative field
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Library/FeedBack
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Library/FeedBack
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>