<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A Fine Amount
// Author : Saurabh Thukral
// Created on : (30.07.2012 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineList');
define('ACCESS','edit');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
	$fineManager = FineManager::getInstance();

    $errorMessage ='';

	$id=$REQUEST_DATA['studentId'];
	$amount=$REQUEST_DATA['changeAmount'];
	
    if(trim($errorMessage) == '') {
   	  $foundArray = $fineManager->updateFineAmount($id,$amount);
	  if($foundArray===true) {
	     echo SUCCESS;	
	  }	
	  else {
	     echo FAILURE;
	  }
	}
	else {
       echo $errorMessage;
    }

?>