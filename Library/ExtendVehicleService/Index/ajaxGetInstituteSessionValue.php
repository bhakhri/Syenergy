<?php
//  This File fetch current institute & session 
//------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::headerNoCache();
    UtilityManager::ifLoggedIn();  
    
    require_once(MODEL_PATH."/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    
    $username = add_slashes(trim($REQUEST_DATA['username']));
    
    if($username == '') {
       //logError('The value of $REQUEST_DATA["username"] in Index/init.php is empty'); 
       die;
    }
    
    $where =     " WHERE u.userName='".$username."' AND u.roleId=r.roleId ";
    $filedName = " DISTINCT u.instituteId AS id ";
    $tableName = " role r, user u LEFT JOIN user_prefs ufs on (ufs.userId=u.userId)";
    
    $foundArray = $studentManager->getSingleField($tableName, $filedName, $where);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       echo json_encode($foundArray[0]);
    }
    else {
       echo 0;
    }
?>