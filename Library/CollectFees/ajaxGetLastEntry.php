<?php

//The file contains data base functions for marks
//
// Author :Rajeev Aggarwal
// Created on : 21.11.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CollectFees');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
    $collectStudentFeeManager = CollectStudentFeeManager::getInstance();  
    
    $foundArray = $collectStudentFeeManager->getLastEntry();

    $lastEntryDate = date('Y-m-d');
    $lastEntry = NOT_APPLICABLE_STRING;
    $lastFeeCycleId = '';
    
    if(is_array($foundArray) && count($foundArray)>0 ) {   
      $lastEntryDate = $foundArray[0]['receiptDate'];
      $lastEntry = $foundArray[0]['receiptNo'].' ('.UtilityManager::formatDate($foundArray[0]['receiptDate']).")";  
      $lastFeeCycleId = $foundArray[0]['feeCycleId'];
    }
    
    echo $lastEntry."!~~!".$lastEntryDate."!~~!".$lastFeeCycleId;
    die;
?> 