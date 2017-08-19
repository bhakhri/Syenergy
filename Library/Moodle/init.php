<?php
// This file is to initialise data related to moodle login.
 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ShowMoodle');
    define('ACCESS','view');
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
    
    if($sessionHandler->getSessionVariable('RoleId')==4) {
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else if($sessionHandler->getSessionVariable('RoleId')==2) {
       UtilityManager::ifTeacherNotLoggedIn(true);        
    }
    UtilityManager::headerNoCache();

    $condition = " WHERE userId = '".$sessionHandler->getSessionVariable('UserId')."'"; 
    $foundArray = $studentManager->getSingleField("`user`", "userName AS uid, userPassword as pid", $condition);
    
?>
