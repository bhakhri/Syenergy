<?php

//This file calls Delete Function and Listing Function and creates Global Array in Book Module 
//
// Author :Nancy Puri
// Created on : 04-Oct-2010                                      
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/BookManager.inc.php");
    $bookManager = BookManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['bookId']) && $REQUEST_DATA['act']=='del') {
            
        $recordArray = $countryManager->checkInMapping($REQUEST_DATA['bookId']);
        if($recordArray[0]['found']==0) {
            if($bookManager->deleteCountry($REQUEST_DATA['bookId']) ) {
                $message = DELETE;
            }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        }
    }
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
         $filter = ' WHERE (bookName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bookAuthor LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR uniqueCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR instituteBookCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR isbnCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';      
    }
   
    $totalArray = $bookManager->getTotalBook($filter);
    $bookRecordArray = $bookManager->getBookList($filter,$limit);   
    
?>
