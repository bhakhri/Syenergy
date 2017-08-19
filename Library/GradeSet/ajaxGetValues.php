<?php
//  This File gets  record from the gradeset Form Table
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeSetMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['gradeSetId'] ) != '') {
    require_once(MODEL_PATH . "/GradeSetManager.inc.php");
    $foundArray = GradeSetManager::getInstance()->getGradeSet(' WHERE gradeSetId='.$REQUEST_DATA['gradeSetId']);
	
   if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}

?>