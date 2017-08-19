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
define('MODULE','COMMON');  
define('ACCESS','view'); 
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  //$labelId = add_slashes(trim($REQUEST_DATA['labelId']));
  //if($labelId=='') {
    //$labelId=0;  
  //}
$batchId = add_slashes(trim($REQUEST_DATA['batchId']));
$degreeId = add_slashes(trim($REQUEST_DATA['degreeId']));
$branchId = add_slashes(trim($REQUEST_DATA['branchId']));

  $foundArray = HoldUnholdClassManager::getInstance()->getClass($batchId,$degreeId,$branchId);
	
  if(is_array($foundArray) && count($foundArray)>0 ) {  
    echo json_encode($foundArray);
  }
  else {
    echo 0;
  }
?>
