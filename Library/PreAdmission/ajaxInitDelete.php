<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PreAdmissionMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 
    
    require_once(MODEL_PATH . "/PreAdmissionManager.inc.php");
    $preAdmissionManager = PreAdmissionManager::getInstance();
    
    $id = trim($REQUEST_DATA['studentId']);
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) { 
        $returnStatus = $preAdmissionManager->deleteStudent($id);
        if($returnStatus === false) {
          echo FAILURE;
          die;
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
          echo DELETE;                            
        }
        else {
          echo FAILURE;
        }
    }
    else{
        echo FAILURE;
        die;
    }
?>    