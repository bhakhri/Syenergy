<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BookClassMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BookClassMappingManager.inc.php");
    $bookMgr = BookClassMappingManager::getInstance();

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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookNo';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $filter=' AND m.classId='.$classId;
    
    $totalArray      = $bookMgr->getBookMappingList($filter);
    $bookRecordArray = $bookMgr->getBookMappingList($filter,$limit,$orderBy);
    $cnt = count($bookRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
       $checked='';
       if($bookRecordArray[$i]['isMapped']==1){
           $checked='checked="checked"';
       }
       
       if($bookRecordArray[$i]['bookNo']==''){
          $bookRecordArray[$i]['bookNo']=NOT_APPLICABLE_STRING; 
       }
       if($bookRecordArray[$i]['bookName']==''){
          $bookRecordArray[$i]['bookName']=NOT_APPLICABLE_STRING; 
       }
       if($bookRecordArray[$i]['bookAuthor']==''){
          $bookRecordArray[$i]['bookAuthor']=NOT_APPLICABLE_STRING; 
       }
       if($bookRecordArray[$i]['uniqueCode']==''){
          $bookRecordArray[$i]['uniqueCode']=NOT_APPLICABLE_STRING; 
       }
       
       $bookString='<input type="checkbox" name="books" '.$checked.' value="'.$bookRecordArray[$i]['bookId'].'" />';

       $valueArray = array_merge(array('srNo' => ($records+$i+1),'books'=>$bookString ),$bookRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>