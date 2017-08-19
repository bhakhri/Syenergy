<?php
////  This File gets  record from the bank Form Table
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;

if(trim($REQUEST_DATA['empId'] ) != '' && trim($REQUEST_DATA['month'] ) != ''&& trim($REQUEST_DATA['year'] ) != '') {
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $holdArray= PayrollManager::getInstance()->getSalaryHoldDetails(trim($REQUEST_DATA['empId']),$REQUEST_DATA['month'],$REQUEST_DATA['year']); 
   if(count($holdArray)>0 ) {  
       if($holdArray[0]['status']==0)
       {
           $status=1;
       }
       else
       {
           $status=0;
       }
       PayrollManager::getInstance()->clearPrevHolds(trim($REQUEST_DATA['empId']),$REQUEST_DATA['month'],$REQUEST_DATA['year']);
       $result=PayrollManager::getInstance()->holdUnholdSalary(trim($REQUEST_DATA['empId']),$REQUEST_DATA['month'],$REQUEST_DATA['year'],trim($REQUEST_DATA['reason']),$sessionHandler->getSessionVariable('UserId'),$status);
       echo "saved";
      //logError("Hello Abhiraj 3:".$sessionHandler->getSessionVariable('EmployeeId')); 
    }
    else {
           $status=1;
           $result=PayrollManager::getInstance()->holdUnholdSalary(trim($REQUEST_DATA['empId']),$REQUEST_DATA['month'],$REQUEST_DATA['year'],trim($REQUEST_DATA['reason']),$sessionHandler->getSessionVariable('UserId'),$status);
           echo "saved";
       
    }
	
}

?>