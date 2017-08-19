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
    define('MODULE','ADVFB_CategoryMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackCategoryAdvancedManager.inc.php");
    $fbMgr = FeedBackCategoryAdvancedManager::getInstance();
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       $relId=-1;
	   if(stristr('general', strtolower(add_slashes($REQUEST_DATA['searchbox'])))) {  
          $relId=1;
       }
	   else if(stristr('hostel', strtolower(add_slashes($REQUEST_DATA['searchbox'])))) {  
          $relId=2;
       }
	   else if(stristr('transport', strtolower(add_slashes($REQUEST_DATA['searchbox'])))) {  
          $relId=3;
       }
	   else if(stristr('subject', strtolower(add_slashes($REQUEST_DATA['searchbox'])))) {  
          $relId=4;
       }
       else{
           $relId=-1;
       }
       //$filter = ' AND ( fc.feedbackCategoryName LIKE "'.$search.'%" OR fc1.feedbackCategoryName LIKE "'.$search.'%" OR fs.feedbackSurveyLabel LIKE "'.$search.'%" OR fc.printOrder LIKE "'.$search.'%" OR st.subjectTypeName LIKE "'.$search.'%" OR fc.feedbackType LIKE "'.$relId.'%"  )';
       $filter = ' WHERE ( fc.feedbackCategoryName LIKE "%'.$search.'%" OR fc1.feedbackCategoryName LIKE "%'.$search.'%" OR fc.printOrder LIKE "%'.$search.'%" OR st.subjectTypeName LIKE "%'.$search.'%" OR fc.feedbackType LIKE "%'.$relId.'%"  )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackCategoryName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray    = $fbMgr->getTotalFeedbackCategory($filter);
    $fbRecordArray = $fbMgr->getFeedbackCategoryList($filter,$limit,$orderBy);
    $cnt = count($fbRecordArray);
    //fetch used categoryId list  
    $usageArray    = $fbMgr->getCategoryUsageList();
    
    for($i=0;$i<$cnt;$i++) {
       if($fbRecordArray[$i]['parentFeedbackCategoryId']==''){
           $fbRecordArray[$i]['parentFeedbackCategoryId']=NOT_APPLICABLE_STRING;
       }
       if($fbRecordArray[$i]['subjectTypeName']==''){
           $fbRecordArray[$i]['subjectTypeName']=NOT_APPLICABLE_STRING;
       }
       if($fbRecordArray[$i]['parentCategoryName']==''){
           $fbRecordArray[$i]['parentCategoryName']=NOT_APPLICABLE_STRING;
       }
       //check with global relationship array
       if(array_key_exists($fbRecordArray[$i]['feedbackType'],$advFeedBackRelationship)){
           $fbRecordArray[$i]['feedbackType']=$advFeedBackRelationship[$fbRecordArray[$i]['feedbackType']];
       }
       else{
           $fbRecordArray[$i]['feedbackType']=NOT_APPLICABLE_STRING;
       }
       
       //if this is used then do not allow to edit/delete
       if(in_array($fbRecordArray[$i]['feedbackCategoryId'],$usageArray)){
           $actionString=NOT_APPLICABLE_STRING.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       }
       else{
           $actionString='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$fbRecordArray[$i]['feedbackCategoryId'].');return false;"></a>
                          <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteCategory('.$fbRecordArray[$i]['feedbackCategoryId'].');"/></a>';
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
// $History: ajaxAdvFeedBackCategoryList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/01/10    Time: 18:29
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Updated "Advanced Feedback Category" module as feedbackSurveyId is
//removed from table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:47
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created module "Advanced Feedback Category Module"
?>