<?php
//-------------------------------------------------------
// Purpose: To get values of fee cycle from the database
//
// Author : Nishu Bindal
// Created on : (3.feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleMasterNew');
define('ACCESS','view');       
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['feeCycleId'] ) != '') {
    require_once(MODEL_PATH . "/Fee/FeeCycleManager.inc.php");
    $foundArray = FeeCycleManager::getInstance()->getFeeCycle(' AND feeCycleId="'.$REQUEST_DATA['feeCycleId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 'Cycle Name does not exist.';
    }
}

?>

