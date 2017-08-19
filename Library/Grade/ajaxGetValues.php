<?php
//  This File gets  record from the grade Form Table
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['gradeId'] ) != '') {
    require_once(MODEL_PATH . "/GradeManager.inc.php");
    $foundArray = GradeManager::getInstance()->getGrade(' WHERE g.gradeSetId='.$REQUEST_DATA['gradeId']);

   if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
	
}

?>
