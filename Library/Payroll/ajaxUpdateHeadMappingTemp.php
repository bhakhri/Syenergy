<?php
////  This File updates the array with the head details everytime a new heads is selected.
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
//logError("Hello".$REQUEST_DATA['employeeCode']);
if(trim($REQUEST_DATA['headId'] ) != '') {
    $headId=trim($REQUEST_DATA['headId']);
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    //$result=PayrollManager::getInstance()->truncateTable('salary_head_temp');
    $tempHeadArray=$sessionHandler->getSessionVariable('tempHeadArray');
    $headArray=$sessionHandler->getSessionVariable('headArray');    
    $cnt=0;
    $cnt1=count($headArray);
    $pos;
    for($i=0;$i<$cnt1;$i++)
    {
       if($headArray[$i]['headId']==$headId)
       {
           $pos = $i;
           break;
       } 
    }
    if(is_array($tempHeadArray))
    {
    $cnt=count($tempHeadArray);
    }
    logError("abhiraj says count is: ".$cnt);  
    if($cnt>0)
    {   
        logError("Inside array loop");
        $flag=0;
        for($i=0;$i<$cnt;$i++)
        {
            if($tempHeadArray[$i]['headId']==$headId)
            {
                $flag=0;
                break;
            }
            else
            {
                ++$flag;
            }
        }
        if($flag!=0)
        {
            $tempHeadArray[$i]=array('headId'=>$headId, 'headName'=>
            $headArray[$pos]['headName'],'headType'=>$headArray[$pos]['headType'],'amount'=>0);
        }
    }
    else
    {
         logError("hello xxxx");
         $tempHeadArray=array();   
        $tempHeadArray[]=array('headId'=>$headId, 'headName'=>$headArray[$pos]['headName'],'headType'=>$headArray[$pos]['headType'],'amount'=>0);
        print_r($tempHeadArray);
    }
    //logError($foundArray);
    $count1=count($tempHeadArray);
    logError("yyyyyy".$count1);
    logError("yyyyyy".$tempHeadArray[0]['headName']);
   $sessionHandler->setSessionVariable('tempHeadArray',$tempHeadArray);
    print_r($tempHeadArray);
   echo $tempHeadArray; 
	
}

?>