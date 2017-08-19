<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE QUOTA Seats LIST
//
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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

require_once(MODEL_PATH . "/ClassWiseHeadMappingManager.inc.php");   
 $classWiseHeadValuesManager = ClassWiseHeadValuesManager::getInstance();
    
/* $feeCycleId = $REQUEST_DATA['feeCycleId'];
    if($feeCycleId=='') {
      $feeCycleId=0;  
    }
*/    
    $classId = $REQUEST_DATA['classId'];
    if($classId=='') {
      $classId=0;  
    }
    
    $condition = " fcc.classId=$classId  AND ff.isConsessionable=1 ";
    $foundArray = $classWiseHeadValuesManager->getClassWiseHeadListing($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
?>


