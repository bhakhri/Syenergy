<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
  echo $REQUEST_DATA['subjectTypeId'];  
if(trim($REQUEST_DATA['subjectTypeId'] ) != '') {
    require_once(MODEL_PATH . "/studentManager.inc.php");
    $foundArray = StudentManager::getInstance()->getTestTypeData(' WHERE subjectTypeId="'.$REQUEST_DATA['subjectTypeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>