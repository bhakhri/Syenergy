<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/ClassSessionUpdateManager.inc.php");
    define('MODULE','ClassGradesheetMaster');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    $classUpdateManager = ClassUpdateManager::getInstance(); 

  
    global $sessionHandler;

    $classIdArray = $REQUEST_DATA['chb1'];
    $titleNameArray = $REQUEST_DATA['chb'];
    $chbOrderArray = $REQUEST_DATA['chbOrder'];
	$chbInternal = $REQUEST_DATA['chbIntrnl'];
	$chbExternal = $REQUEST_DATA['chbExtrnl']; 


  //****************************************************************************************************************    
  //***********************************************STRAT TRANSCATION************************************************
  //****************************************************************************************************************
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
		
	   for($i=0;$i<count($classIdArray);$i++) {
	     $returnStatus =  $classUpdateManager->addUpdateTitle($titleNameArray[$i],$classIdArray[$i],$chbOrderArray[$i],$chbInternal[$i],$chbExternal[$i]);
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
