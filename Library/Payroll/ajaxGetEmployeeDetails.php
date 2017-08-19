<?php
////  This File gets employee data from employee table
//
// Author :Abhiraj Malhotra
// Created on : 20-Apr-2010
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
logError("Hello".$REQUEST_DATA['employeeCode']);
if(trim($REQUEST_DATA['employeeCode'] ) != '') {
    $startPos=strpos(trim($REQUEST_DATA['employeeCode']),"(")+1;
    $endPos=strripos(trim($REQUEST_DATA['employeeCode']),"(")-1;
    $len=($endPos-$startPos)+1;
    $employeeCode=substr(trim($REQUEST_DATA['employeeCode']),$startPos,$len);
    if(trim($employeeCode)=="")
    {
        echo -1;
    }
    else
    {
    if(is_array($sessionHandler->getSessionVariable('tempHeadArray')))
    {
       $sessionHandler->unsetSessionVariable('tempHeadArray');        
    }
    $startPos=strpos(trim($REQUEST_DATA['employeeCode']),"(")+1;
    $endPos=strripos(trim($REQUEST_DATA['employeeCode']),"(")-1;
    $len=($endPos-$startPos)+1;
    $employeeCode=substr(trim($REQUEST_DATA['employeeCode']),$startPos,$len);
    $employeeCode=trim($employeeCode);
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $conditions="and employeeCode like '".$employeeCode."'";
    $foundArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
   logError("count.....".count($foundArray)); 
   if(is_array($foundArray) && count($foundArray)>0 && substr(trim($REQUEST_DATA['employeeCode']),0,$startPos-1)==trim($foundArray[0]['employeeName'])) {
       if(trim($foundArray[0]['departmentId'])!="" && trim($foundArray[0]['designationId'])!="")
       {
        $departmentName = PayrollManager::getInstance()->getSingleField('department','departmentName','where departmentId='.$foundArray[0]['departmentId']);
        $designation = PayrollManager::getInstance()->getSingleField('designation','designationName','where designationId='.$foundArray[0]['designationId']);  
        //logError("department:".$departmentName[0]['departmentName']);
        //logError("designation:".$array_temp['designation']);
        $foundArray[0] = array_merge($foundArray[0],$departmentName[0],$designation[0]); 
       }
       else
       {
           $departmentName[0]['departmentName']='Not Available';
           $designation[0]['designationName']='Not Available';
           $foundArray[0] = array_merge($foundArray[0],$departmentName[0],$designation[0]);
       }
   echo json_encode($foundArray[0]); 
   }
    
    else {
        echo 0;
    }
    }
	
}
else
{
    echo 0;
}
?>