<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AppraisalQuestionMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Appraisal/QuestionManager.inc.php");
    $questionManager = QuestionManager::getInstance();
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
         $isActive=1;
         $isProof=1;
       }
       elseif(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
         $isActive=0;
         $isProof=0;
       }
       else{
          $isActive=-1;
          $isProof=-1;
       }
       $filter = ' AND ( am.appraisalText LIKE "'.$search.'%" OR am.appraisalWeightage LIKE "'.$search.'%" OR am.isActive LIKE "'.$isActive.'%" OR am.appraisalProof LIKE "'.$isProof.'%" OR ap.appraisalProofName LIKE "'.$search.'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'appraisalText';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $questionManager->getTotalQuestion($filter);
    $questionRecordArray = $questionManager->getQuestionList($filter,$limit,$orderBy);
    $cnt = count($questionRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       if($questionRecordArray[$i]['isActive']==1){
          $questionRecordArray[$i]['isActive']='Yes'; 
       }
       else{
          $questionRecordArray[$i]['isActive']='No'; 
       }
       if($questionRecordArray[$i]['appraisalProof']==1){
          $questionRecordArray[$i]['appraisalProof']='Yes'; 
       }
       else{
          $questionRecordArray[$i]['appraisalProof']='No'; 
       }
       if(trim($questionRecordArray[$i]['appraisalProofName'])==''){
          $questionRecordArray[$i]['appraisalProofName']=NOT_APPLICABLE_STRING; 
       }
       
       if(strlen(trim($questionRecordArray[$i]['appraisalText']))>100){
          $questionRecordArray[$i]['appraisalText']=substr($questionRecordArray[$i]['appraisalText'],0,97).'...'; 
       }
       if(strlen(trim($questionRecordArray[$i]['appraisalProofName']))>50){
          $questionRecordArray[$i]['appraisalProofName']=substr($questionRecordArray[$i]['appraisalProofName'],0,47).'...'; 
       }
       
       $valueArray = array_merge(array('action' => $questionRecordArray[$i]['appraisalId'] , 'srNo' => ($records+$i+1) ),$questionRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
?>