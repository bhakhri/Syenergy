<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE HOSTEL VISITOR LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (20.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelVisitor');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['visitorId'] ) != '') {
    require_once(MODEL_PATH . "/HostelVisitorManager.inc.php");
    $foundArray = HostelVisitorManager::getInstance()->getHostelVisitor(' WHERE visitorId="'.$REQUEST_DATA['visitorId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>

