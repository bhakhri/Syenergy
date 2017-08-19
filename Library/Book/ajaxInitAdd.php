<?php 
//  This File calls addFunction used in adding Book Records
//
// Author :Nancy Puri
// Created on : 04-Oct-2010
//
//--------------------------------------------------------
    global $FE;
    $flag = 0;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BookMaster');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    $errorMessage ='';
    $bookName = $REQUEST_DATA['bookName'];   
    $bookAuthor = $REQUEST_DATA['bookAuthor'];   
    $uniqueCode = $REQUEST_DATA['uniqueCode'];   
    $instituteBookCode = $REQUEST_DATA['instituteBookCode'];   
    $isbnCode = $REQUEST_DATA['isbnCode'];   
    $noOfBooks = $REQUEST_DATA['noOfBooks']; 
    if (!isset($bookName) || trim($bookName) == '') {
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
        $errorMessage .= ENTER_INSTITUTE_BOOK_CODE."\n";
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
    $foundArray = $bookManager->getBook(' WHERE UCASE(bookName)="'.add_slashes(strtoupper($bookName)).
'" AND bookAuthor = "'.$bookAuthor.'"');
    if ($foundArray[0]['bookId'] != '') {
        echo BOOK_ALREADY_EXISTS;
        die;
    }
    $returnStatus = $bookManager->addBook();
    if($returnStatus === false) {
        echo FAILURE;
        die;
    }
    $bid = SystemDatabaseManager::getInstance()->lastInsertId();
  //  $zeroes = str_repeat("0",BOOK_CODE_LENGTH - (strlen($bid)) - (strlen(BOOK_CODE_PREFIX)));      
    $bookNo = BOOK_CODE_PREFIX.$zeroes.$bid;     
    $returnStatus2 = $bookManager->updBookNo($bookNo,$bid);    
    if($returnStatus2 === false) {  
        echo FAILURE;
        die;
    }            
    echo SUCCESS;
?>