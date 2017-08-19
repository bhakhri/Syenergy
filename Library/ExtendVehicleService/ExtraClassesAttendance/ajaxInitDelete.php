<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExtraClassAttendance');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 
    require_once(MODEL_PATH . "/ExtraClassAttendanceManager.inc.php");
    $extraClassAttendanceManager = ExtraClassAttendanceManager::getInstance();
    
    $id = trim($REQUEST_DATA['extraAttendanceId']);
    
    $ret = $extraClassAttendanceManager->deleteExtraClassAttendance($id);
    if($ret===true ) {
      echo DELETE;
    }
    else {
      echo FAILURE;     
    }

?>    