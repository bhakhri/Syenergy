<?php
  //--------------------------------------------------------
//This file returns the array of bank name
//
// Author :Gurkeerat Sidhu
// Created on : 08-Oct-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/BankBranchManager.inc.php");
    $bankBranchManager = BankBranchManager::getInstance();
     
    $bankId = $REQUEST_DATA['bankId'];
    //fetching subject data only if any one class is selected

   // if ($bankId != 'all') {
        $bankBranchRecordArray = $bankBranchManager->getBank($bankId);
        echo json_encode($bankBranchRecordArray);
    //}

?>
