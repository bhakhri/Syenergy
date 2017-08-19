<?php
////  This File gets head description from the salary heads table
//
// Author :Abhiraj Malhotra
// Created on : 08-April-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['headId'] ) != '') {
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $foundArray = PayrollManager::getInstance()->getHead(' WHERE headId='.$REQUEST_DATA['headId']);
	
   if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
	
}
//*****************  Version 1  *****************
//User: Abhiraj      Date: 4/08/10    Time: 12:50p
//Created in $/Leap/Source/Library/Payroll
//File created for Payroll heads master

?>