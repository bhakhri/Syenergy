<?php 

//  This File calls Edit Function used in adding BookRecords
//
// Author :Nancy Puri
// Created on : 04.10.10
//
//--------------------------------------------------------

    
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BookMaster');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    $errorMessage ='';
    $bookId = $REQUEST_DATA['bookId'];
    $bookName = $REQUEST_DATA['bookName'];   
    $bookAuthor = $REQUEST_DATA['bookAuthor'];   
    $uniqueCode = $REQUEST_DATA['uniqueCode'];   
    $instituteBookCode = $REQUEST_DATA['instituteBookCode'];   
    $isbnCode = $REQUEST_DATA['isbnCode'];   
    $noOfBooks = $REQUEST_DATA['noOfBooks']; 
    
    if ($errorMessage == '' && !isset($bookName) || trim($bookName) == '') {
        $errorMessage .= ENTER_BOOK_NAME."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($bookAuthor) || trim($bookAuthor) == '')) {
        $errorMessage .= ENTER_BOOK_AUTHOR."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($uniqueCode) || trim($uniqueCode) == '')) {
        $errorMessage .= ENTER_UNIQUE_CODE."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($instituteBookCode) || trim($instituteBookCode) == '')) {
        $errorMessage .= ENTER_INSTITUE_BOOK_CODE."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($isbnCode) || trim($isbnCode) == '')) {
        $errorMessage .= ENTER_ISBN_CODE."\n";
        die;
    }
     if ($errorMessage == '' && (!isset($noOfBooks) || trim($noOfBooks) == '')) {
        $errorMessage .= ENTER_NO_OF_BOOKS."\n";
        die;
    }
        require_once(MODEL_PATH . "/BookManager.inc.php");
        $bookManager = BookManager::getInstance(); 
        $foundArray = $bookManager->getBook(' WHERE UCASE(bookName)="'.add_slashes(trim(strtoupper($bookName))).
'" AND UCASE(bookAuthor)="'.add_slashes(strtoupper($bookAuthor)).'"  AND UCASE(bookId) !="'.add_slashes(strtoupper($bookId)).'"');
        if ($foundArray[0]['bookName'] != '') { 
        echo BOOK_ALREADY_EXISTS;
        die;
        }    
        $returnStatus =  $bookManager->editBook($bookId);
        if($returnStatus === false) {  
            echo FAILURE;
            die;  
          }
        echo SUCCESS;
  
  
    
?>