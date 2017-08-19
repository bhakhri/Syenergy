<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/HoldUnholdClassManager.inc.php");
    define('MODULE','HoldUnholdClassResult');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    $holdUnholdClassManager = HoldUnholdClassManager::getInstance(); 

    global $sessionHandler;
    
  
    $batchId = $REQUEST_DATA['batchId'];  
    $degreeId = $REQUEST_DATA['degreeId'];  
    $branchId = $REQUEST_DATA['branchId'];  
    $holdId = trim($REQUEST_DATA['holdId']);   
    $unHoldId = trim($REQUEST_DATA['unHoldId']);   
  
    $holdIdArray = array();
    if($holdId!='') {
      $holdIdArray = explode(',',$holdId);  
    }

  //****************************************************************************************************************    
  //***********************************************STRAT TRANSCATION************************************************
  //****************************************************************************************************************
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
       if($unHoldId!='') {
         $returnStatus =  $holdUnholdClassManager->unHoldClasses($unHoldId);
         if($returnStatus === false) {
           echo FAILURE;
           die;
         } 
       }
	   for($i=0;$i<count($holdIdArray);$i++) {
	     $classIdArray = explode('~',$holdIdArray[$i]); 
         $returnStatus =  $holdUnholdClassManager->addHoldClasses($classIdArray[0],$classIdArray[1],$classIdArray[2],$classIdArray[3],$classIdArray[4]);
         if($returnStatus === false) {
           echo FAILURE;
           die;
         }
	   }
       
       //*****************************COMMIT TRANSACTION************************* 
       if(SystemDatabaseManager::getInstance()->commitTransaction()) {
         $errorMessage = SUCCESS;
       }
  }
  echo $errorMessage;

?>
