<?php
//-------------------------------------------------------
// Purpose: To get values of state from the database
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentProgramFee');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['programFeeId'] ) != '') {
    require_once(MODEL_PATH . "/StudentProgramFeeManager.inc.php");
    $foundArray = StudentProgramFeeManager::getInstance()->getProgramFee(' WHERE programFeeId="'.$REQUEST_DATA['programFeeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0; // no record found
    }
}
?>