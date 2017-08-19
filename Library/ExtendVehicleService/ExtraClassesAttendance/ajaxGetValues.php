
<?php 

////  This File checks  whether record exists in Book Form Table
//
// Author :Nancy Puri
// Created on : 04-Oct-2010
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExtraClassAttendance');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 
    require_once(MODEL_PATH . "/ExtraClassAttendanceManager.inc.php");
    $extraClassAttendanceManager = ExtraClassAttendanceManager::getInstance();
    
    $condition = " AND e.extraAttendanceId = '".$REQUEST_DATA['extraAttendanceId']."'";
    $foundArray = $extraClassAttendanceManager->getExtraClassAttendanceList($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       echo json_encode($foundArray[0]);
    }
    else {
       echo 0;
    }
