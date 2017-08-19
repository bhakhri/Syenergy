<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE Fee Cycle Classes
// Author : Parveen Sharma
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");   
$commonQueryManager = CommonQueryManager::getInstance(); 
    
  /*
    $feeCycleId = $REQUEST_DATA['feeCycleId'];
    if($feeCycleId=='') {
      $feeCycleId=0;  
    }
    if($classId=='') {
      $classId=0;  
    }                   
    $condition = " AND fc.feeCycleId=$feeCycleId ";
    $foundArray = $commonQueryManager->getFeeCycleClasses($condition);
  */
    $foundArray = $commonQueryManager->getAllClasses();
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
?>