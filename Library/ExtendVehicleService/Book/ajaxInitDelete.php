<?php
//-------------------------------------------------------
// Purpose: To delete book detail
//
// Author : Nancy Puri
// Created on : (04.10.2010 )
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  define('MODULE','BookMaster');
  define('ACCESS','delete');
  UtilityManager::ifNotLoggedIn(true);
  UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['bookId']) || trim($REQUEST_DATA['bookId']) == '') {
        $errorMessage = 'Invalid book';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BookManager.inc.php");
        $bookManager =  BookManager::getInstance();
  
        // Checks dependency constraint
        $recordArray = $bookManager->checkInMapping($REQUEST_DATA['bookId']);
        if($recordArray[0]['found']==0) { 
         if($bookManager->deleteBook($REQUEST_DATA['bookId']) ) {
                echo DELETE;
        }
           }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }      
    
    else {
        echo $errorMessage;
    }
   
