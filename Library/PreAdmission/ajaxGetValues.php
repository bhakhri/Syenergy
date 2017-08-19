<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/PreAdmissionManager.inc.php");
    $preAdmissionManager = PreAdmissionManager::getInstance();
    
    $studentId = $REQUEST_DATA['studentId'];
    
    if($studentId=='') {
      $studentId='0';  
    }
   
 
 //Function gets data from books_master table
    $foundArray = $preAdmissionManager->getPopulateList($studentId);
    if(count($foundArray)>0 ) {  
       echo $foundArray;
    }
    else {
       echo 0;
    }
