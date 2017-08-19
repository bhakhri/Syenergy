<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeConcessionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from country table
 
if(trim($REQUEST_DATA['categoryId'] ) != '') {
    require_once(MODEL_PATH . "/FeeConcessionManager.inc.php");
    $foundArray = FeeConcessionManager::getInstance()->getFeeConcession(' WHERE categoryId="'.$REQUEST_DATA['categoryId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>