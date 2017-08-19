<?php
////  This File gets the records from the temp array
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
 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
   $tempHeadArray=$sessionHandler->getSessionVariable('tempHeadArray');
   $headArray=$sessionHandler->getSessionVariable('headArray');
    
if(trim($sessionHandler->getSessionVariable('UserId')) != '' && is_array($tempHeadArray)) {
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    
     
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
    $foundArray=array();
    $valueArray=array();
    $foundArray=$tempHeadArray;
    //$foundArray = PayrollManager::getInstance()->getTempHead(' WHERE userId='.$sessionHandler->getSessionVariable('UserId'));
    for($i=0;$i<count($foundArray);$i++)
        {
            //$foundArray_heads=PayrollManager::getInstance()->getHead(' WHERE headId='.$foundArray[$i]['headId']);
                $cnt1=count($headArray);
                for($j=0;$j<$cnt1;$j++)
                {
                if($headArray[$j]['headId']==$foundArray[$i]['headId'])
                {
                $pos = $j;
                 break;
                 } 
                }
            if($headArray[$pos]['headType']==0)
            {
                $headType="Earning";
            }
            else
            {
                $headType="Deduction";
            }
           $valueArray=array('srNo'=>($records+$i+1),'headName'=>$headArray[$pos]['headName'],'headType'=>$headType,'control'=>"<input type='text' name=".$foundArray[$i]['headId']." id=".$foundArray[$i]['headId']." value=".$foundArray[$i]['amount']." onBlur=updateTotal(".$foundArray[$i]['headId'].",this.value)>");
	       
           if(trim($json_val)=='') {
                $json_val = json_encode($valueArray);
           }
           else {
                $json_val .= ','.json_encode($valueArray);           
           }
       }
       echo '{"info" : ['.$json_val.']}'; 
    
}
    else
    {
        echo 0;
    }


?>