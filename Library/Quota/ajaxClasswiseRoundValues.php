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
require_once(MODEL_PATH . "/QuotaManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    
    $classId = trim($REQUEST_DATA['classId']);
    $allocationDate = trim($REQUEST_DATA['allocationDate']);
    
    if($classId=='') {
      $classId=0;  
    }
    
    $foundArray = QuotaManager::getInstance()->getClasswiseRoundList(" AND ca.classId = '$classId' AND ca.allocationDate='$allocationDate'");
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }

?>


