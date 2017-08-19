<?php
//-------------------------------------------------------
// Purpose: To get values of financial data from the database
//
// Author : Abhiraj malhotra
// Created on : 29.04.10
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EmployeeManager.inc.php");
define('MODULE','EmployeeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;

$employeeManager    = EmployeeManager::getInstance();    
$employeeId = add_slashes(trim($REQUEST_DATA['employeeId']));

//Changes by Jaineesh on 12.05.2010 to get Bank Name, Branch Name and Account No.

if($employeeId!='') { 
    
    $foundArray = $employeeManager->getEmployeeFinancialInfo($employeeId);
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
    
		echo json_encode($foundArray[0]);
    }
    else
    {
        echo 0;
    }
}

?>