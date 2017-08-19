<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $foundArray = CommonQueryManager::getInstance()->getStudentAllocatedSubject($sessionHandler->getSessionVariable('StudentId'),trim($REQUEST_DATA['classId']));
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>