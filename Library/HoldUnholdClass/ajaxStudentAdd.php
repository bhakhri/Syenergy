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
 

    $classId = $REQUEST_DATA['classId'];  
    $holdId = trim($REQUEST_DATA['holdId']);   
    
    $holdIdArray = array();
    if($holdId!='') {
      $holdIdArray = explode(',',$holdId);  
    }
    
    

  //****************************************************************************************************************    
  //***********************************************STRAT TRANSCATION************************************************
  //****************************************************************************************************************
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
     $condition = " classId = '$classId' ";   
     $returnStatus =  $holdUnholdClassManager->getUnHoldHoldDelete($condition); 
     if($returnStatus === false) {
       echo FAILURE;
       die;
     }
     if(SystemDatabaseManager::getInstance()->commitTransaction()) {
       $errorMessage = SUCCESS;
     } 
  }
  
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
       for($i=0;$i<count($holdIdArray);$i++) {
         $isClass = 0;  
	     $studentIdArray = explode('~',$holdIdArray[$i]); 	
	     $returnStatus =  $holdUnholdClassManager->addHoldStudentDetails($studentIdArray[0],$studentIdArray[1],$studentIdArray[2],$studentIdArray[3],$studentIdArray[4],$classId,$isClass);
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
