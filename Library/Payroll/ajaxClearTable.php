<?php
////  This File clears the temp array with heads and values for non selected heads
//
// Author :Abhiraj Malhotra
// Created on : 20-Apr-2010
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

if(isset($REQUEST_DATA['headId']) && trim($REQUEST_DATA['headId']) != '') {
    //require_once(MODEL_PATH . "/PayrollManager.inc.php");
    //$foundArray = PayrollManager::getInstance()->deleteTempHead(trim($REQUEST_DATA['headId']));
    $headId = trim($REQUEST_DATA['headId']);
    $tempHeadArray=$sessionHandler->getSessionVariable('tempHeadArray');
    $headArray=$sessionHandler->getSessionVariable('headArray');
    
    $cnt=count($tempHeadArray);
    echo "Count is: ".$cnt;
    if($cnt>0 && is_array($tempHeadArray))
    {   
        for($i=0;$i<$cnt;$i++)
        {
            if($tempHeadArray[$i]['headId']==$headId)
            { 
                logError("inside unset");
                //unset($tempHeadArray[$i]);
                array_splice($tempHeadArray,$i,1);
                break;
            }
        }
    }
    //logError("unset: ".count($tempHeadArray));
    //logError("unset: ".$tempHeadArray[0]['headName']);
    print_r($tempHeadArray);
    $sessionHandler->setSessionVariable('tempHeadArray',$tempHeadArray);
    
}
elseif(isset($REQUEST_DATA['headId']) && trim($REQUEST_DATA['headId'])=='')
{
    $sessionHandler->setSessionVariable('tempHeadArray','');
}

?>