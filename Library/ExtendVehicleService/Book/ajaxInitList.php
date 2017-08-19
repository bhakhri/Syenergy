<?php
//-------------------------------------------------------
// Purpose: To store the records of books in array from the database, pagination and search, delete 
// functionality
//
// Author : Nancy Puri
// Created on : (04.10.2010 )
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BookMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BookManager.inc.php");
    $bookManager =BookManager::getInstance();
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (bookNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bookName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bookAuthor LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR uniqueCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR instituteBookCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR isbnCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR noOfBooks LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
 }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookName';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $bookManager->getTotalBook($filter);
    $bookRecordArray = $bookManager->getBookList($filter,$limit,$orderBy);
    
    $cnt = count($bookRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add bookId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $bookRecordArray[$i]['bookId'] , 'srNo' => ($records+$i+1) ),$bookRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
?>