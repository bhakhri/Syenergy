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
    define('MODULE','BookIssue');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BookIssueManager.inc.php");
    $bookMgr = BookIssueManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 2000;
    $limit      = ' LIMIT '.$records.',2000';
    
    $classId=trim($REQUEST_DATA['classId']);
    if($classId==''){
     echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
     die;   
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    //$filter=' AND m.classId='.$classId;
    $filter==' ';
    
    $totalArray      = $bookMgr->getBookIssueList($classId);
    $studentRecordArray = $bookMgr->getBookIssueList($classId,$filter,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    $totalRecords=0;
    for($i=0;$i<$cnt;$i++) {
       
       if($studentRecordArray[$i]['status']==BOOK_PACKED or $studentRecordArray[$i]['status']==BOOK_DISPACHED){
           continue;
       } 
       $checked='';
       if($studentRecordArray[$i]['status']==BOOK_ISSUED){
           $checked='checked="checked"';
       }
       $totalRecords++;
       
       
       if($studentRecordArray[$i]['studentName']==''){
          $studentRecordArray[$i]['studentName']=NOT_APPLICABLE_STRING; 
       }
       if($studentRecordArray[$i]['rollNo']==''){
          $studentRecordArray[$i]['rollNo']=NOT_APPLICABLE_STRING; 
       }
       if($studentRecordArray[$i]['universityRollNo']==''){
          $studentRecordArray[$i]['universityRollNo']=NOT_APPLICABLE_STRING; 
       }
       if($studentRecordArray[$i]['universityRegNo']==''){
          $studentRecordArray[$i]['universityRegNo']=NOT_APPLICABLE_STRING; 
       }
       
       
       $studentString='<input type="checkbox" name="students" '.$checked.' value="'.$studentRecordArray[$i]['studentId'].'" />';

       $valueArray = array_merge(array('srNo' => $totalRecords,'students'=>$studentString ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>