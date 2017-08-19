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
require_once(MODEL_PATH . "/ClassSessionUpdateManager.inc.php");
define('MODULE','COMMON');  
define('ACCESS','view'); 
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
	$batchId = add_slashes(trim($REQUEST_DATA['batchId']));
  	
  
  $foundArray1 = ClassUpdateManager::getInstance()->getDegree($batchId);
	
  if(is_array($foundArray1) && count($foundArray1)>0 ) {  
    echo json_encode($foundArray1);
  }
  else {
    echo 0;
  }
?>
