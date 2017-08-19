<?php
////  This File gets already assigned heads and fills the array with it
//
// Author :Abhiraj Malhotra
// Created on : 19-Apr-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
//logError("Hello".$REQUEST_DATA['employeeCode']);
if(trim($REQUEST_DATA['employeeCode'] ) != '' && trim($REQUEST_DATA['txtDate'] )!="") {
    $startPos=strpos(trim($REQUEST_DATA['employeeCode']),"(")+1;
    $endPos=strripos(trim($REQUEST_DATA['employeeCode']),"(")-1;
    $len=($endPos-$startPos)+1;
    $headArray=$sessionHandler->getSessionVariable('headArray');
    $employeeCode=substr(trim($REQUEST_DATA['employeeCode']),$startPos,$len);
    $employeeCode=trim($employeeCode);
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $conditions="and employeeCode like '".$employeeCode."'";
    $foundArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
    $employeeId=$foundArray[0]['employeeId'];
    $conditions="where employeeId=".$employeeId." and withEffectFrom='".trim($REQUEST_DATA['txtDate']."'" );
    $foundArray = PayrollManager::getInstance()->getAssignedHeads($conditions);
    $cnt=count($foundArray);
    $cnt1=count($headArray);
    for($i=0;$i<$cnt;$i++)
    {
        for($j=0;$j<$cnt1;$j++)
        {
            if($headArray[$j]['headId']==$foundArray[$i]['headId'])
            {
                $pos = $j;
                break;
            } 
        }
        $tempHeadArray[$i]['headId']=$foundArray[$i]['headId'];
        $tempHeadArray[$i]['headName']=$headArray[$pos]['headName'];
        $tempHeadArray[$i]['headType']=$headArray[$pos]['headType'];
        $tempHeadArray[$i]['amount']=$foundArray[$i]['headValue'];
        
    }   
    if(is_array($foundArray) && count($foundArray)>0 ) {
    $json_val = json_encode($foundArray);
    $sessionHandler->setSessionVariable('tempHeadArray',$tempHeadArray); 
    echo $json_val;          
    }
    else {
        echo 0;
    }
	
}


?>