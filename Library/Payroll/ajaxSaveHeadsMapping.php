<?php
////  This File Saves the mapped heads to the db
//
// Author :Abhiraj Malhotra
// Created on : 21-apr-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 logError("abhiraj1:".$REQUEST_DATA['headId']);  
 logError("abhiraj2:".$REQUEST_DATA['val']);
 require_once(MODEL_PATH . "/PayrollManager.inc.php");
 $tempHeadArray=$sessionHandler->getSessionVariable('tempHeadArray');
   if($REQUEST_DATA['param']=="check")
   {
        $startPos=strpos(trim($REQUEST_DATA['employeeCode']),"(")+1;
        $endPos=strripos(trim($REQUEST_DATA['employeeCode']),"(")-1;
        $len=($endPos-$startPos)+1;
        $employeeCode=substr(trim($REQUEST_DATA['employeeCode']),$startPos,$len);
        $employeeCode=trim($employeeCode);
        require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
        $conditions="and employeeCode like '".$employeeCode."'";
        $empCodeArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
        require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
        $conditions="and employeeCode like '".$employeeCode."'";
        $empCodeArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
        $checkHeadExist = PayrollManager::getInstance()->checkHeadOverwrite(trim($REQUEST_DATA['wef']),$empCodeArray[0]['employeeId']);
        if($checkHeadExist[0]['found']>0)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }          
   }
   elseif($REQUEST_DATA['param']=="remove")
   {
        $startPos=strpos(trim($REQUEST_DATA['employeeCode']),"(")+1;
        $endPos=strripos(trim($REQUEST_DATA['employeeCode']),"(")-1;
        $len=($endPos-$startPos)+1;
        $employeeCode=substr(trim($REQUEST_DATA['employeeCode']),$startPos,$len);
        $employeeCode=trim($employeeCode);
        require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
        $conditions="and employeeCode like '".$employeeCode."'";
        $empCodeArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
        require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
        $conditions="and employeeCode like '".$employeeCode."'";
        $empCodeArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
        PayrollManager::getInstance()->removeHeads(trim($REQUEST_DATA['wef']),$empCodeArray[0]['employeeId']);
   }
   elseif($REQUEST_DATA['param']=="save" && is_array($tempHeadArray) && $tempHeadArray[0]['headName']!="" && $REQUEST_DATA['employeeCode']!="")
   {
        $startPos=strpos(trim($REQUEST_DATA['employeeCode']),"(")+1;
        $endPos=strripos(trim($REQUEST_DATA['employeeCode']),"(")-1;
        $len=($endPos-$startPos)+1;
        $employeeCode=substr(trim($REQUEST_DATA['employeeCode']),$startPos,$len);
        $employeeCode=trim($employeeCode);
        require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
        $conditions="and employeeCode like '".$employeeCode."'";
        $empCodeArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
        $cnt=count($tempHeadArray);
        
       PayrollManager::getInstance()->inactivePrevHeadMappings($empCodeArray[0]['employeeId']);
       for($i=0;$i<$cnt;$i++)
       {
            $foundArray = PayrollManager::getInstance()->saveHeadsMapping($tempHeadArray[$i],$empCodeArray[0]['employeeId'],trim($REQUEST_DATA['wef'])); 
       }
       echo $foundArray;
   }
   else
   {
       echo -1;
   }

?>