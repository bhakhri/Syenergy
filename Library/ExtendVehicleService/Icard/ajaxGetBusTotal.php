<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE subject and groups lists
// Author : Dipanjan Bhattacharjee
// Created on : (14.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 
   
    $busId = $REQUEST_DATA['busId'];
    
    if($busId=='') {
      $busId=0;  
    }
    
    $condition = " WHERE b.busId = $busId";
    $busWiseTotal = $commonQueryManager->getRouteBusTotal($condition,'Bus');
    
    echo json_encode($busWiseTotal);
die;    