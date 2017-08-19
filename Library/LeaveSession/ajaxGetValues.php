<?php
////  This File gets  record from the session Form Table
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');  
define('ACCESS','view'); 
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['sessionId'] ) != '') {
    require_once(MODEL_PATH . "/LeaveSessionsManager.inc.php");
    $foundArray = LeaveSessionsManager::getInstance()->getSession(' WHERE leaveSessionId='.$REQUEST_DATA['sessionId']);
	
   if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
