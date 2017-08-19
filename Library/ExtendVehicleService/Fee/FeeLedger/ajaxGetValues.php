<?php
//-------------------------------------------------------
// Purpose: To get values of fee cycle from the database
//
// Author : Nishu Bindal
// Created on : (3.feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


  global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeLedger');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/Fee/FeeLedgerManager.inc.php");   
    $feeLedgerManager = FeeLedgerManager::getInstance();  

    $feeLedgerDebitCreditId = $REQUEST_DATA['feeLedgerDebitCreditId'];     	
 	
    $foundArray = $feeLedgerManager->getFeeLedgerValues($feeLedgerDebitCreditId);

    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 'Wrong Parameters';
    }


?>

