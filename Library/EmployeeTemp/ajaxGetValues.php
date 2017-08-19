<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE TEMPORARY EMPLOYEE LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (29.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TemporaryEmployee');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['tempEmployeeId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeTempManager.inc.php");
    $foundArray = TempEmployeeManager::getInstance()->getTempEmployee(' WHERE tempEmployeeId="'.$REQUEST_DATA['tempEmployeeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>

