<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
if($sessionHandler->getSessionVariable('RoleId')==5){
	 UtilityManager::ifManagementNotLoggedIn(true);
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
    

if(trim($REQUEST_DATA['subjectTypeId'] ) != '') {

	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $foundArray = StudentManager::getInstance()->getTestTypeData('WHERE ttc.subjectTypeId="'.$REQUEST_DATA['subjectTypeId'].'"');
    
	if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
?>
