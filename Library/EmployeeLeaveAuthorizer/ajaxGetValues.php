<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeaveAuthorizer');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['mappingId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php"); 
    $foundArray = EmployeeLeaveAuthorizerManager::getInstance()->getMappingDataDetails(' AND a.approvingId="'.trim($REQUEST_DATA['mappingId']).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>