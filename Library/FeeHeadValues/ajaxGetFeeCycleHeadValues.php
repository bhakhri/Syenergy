<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE QUOTA Seats LIST
//
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");   
$feeHeadValuesManager = FeeHeadValuesManager::getInstance(); 
    
/* $feeCycleId = $REQUEST_DATA['feeCycleId'];
    if($feeCycleId=='') {
      $feeCycleId=0;  
    }
*/    
    $classId = $REQUEST_DATA['classId'];
    if($classId=='') {
      $classId=0;  
    }
    
    $condition = " fh.classId=$classId  AND ff.isVariable=0 ";
    $foundArray = $feeHeadValuesManager->getFeeCycleHeadList($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
?>


