<?php
// This File updates the total after selection or deselection of a head and also while populating all heads
// for the first time
// Author :Ajinder Singh
// Created on : 23-July-2008
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
 $headArray=$sessionHandler->getSessionVariable('headArray');  
   if(trim($REQUEST_DATA['headId'])!="" && trim($REQUEST_DATA['amount'])!="" && is_array($tempHeadArray))
   {
    $headId = trim($REQUEST_DATA['headId']);
    $amount = trim($REQUEST_DATA['amount']);  
    
    $cnt=count($tempHeadArray);
    $foundArray=array();
    $totalArray=array();
    if($cnt>0)
    {   
        for($i=0;$i<$cnt;$i++)
        {
            if($tempHeadArray[$i]['headId']==$headId)
            {
               $tempHeadArray[$i] = array_merge($tempHeadArray[$i],array('amount'=>$amount));
               break;  
            }
        }
    }
    $sessionHandler->setSessionVariable('tempHeadArray',$tempHeadArray);
    //PayrollManager::getInstance()->updateAmount(trim($REQUEST_DATA['headId']),trim($REQUEST_DATA['amount']));
    //$foundArray = PayrollManager::getInstance()->getTempHead(' WHERE userId='.$sessionHandler->getSessionVariable('UserId'));
     $foundArray = $tempHeadArray;
    
   	
   if(is_array($foundArray) && count($foundArray)>0 ) {
        $total=0;
        $cnt1=count($headArray);
        for($i=0;$i<count($foundArray);$i++)
        {
            //$foundArray_heads=PayrollManager::getInstance()->getHead(' WHERE headId='.$foundArray[$i]['headId']);
            //logError("abhiraj----->".$foundArray_heads[$i]['headType']);
            if($foundArray[$i]['headType']==1)
            {
                $total=$total-$foundArray[$i]['amount'];
            }
            elseif($foundArray[$i]['headType']==0)
            {
                $total=$total+$foundArray[$i]['amount'];
            }
        }
        $totalArray=array('total'=>$total);  
        echo json_encode($totalArray);
    }
    else
    {
        echo json_encode($totalArray=array('total'=>0));
    }
   }
    elseif(trim($REQUEST_DATA['param'])=="none" && is_array($tempHeadArray)) {
        //logError("Abhiraj-->inside elseif");
     //$foundArray = PayrollManager::getInstance()->getTempHead(' WHERE userId='.$sessionHandler->getSessionVariable('UserId'     ));
    $cnt=count($tempHeadArray);
    if($cnt>0)
    {   
        for($i=0;$i<$cnt;$i++)
        {
            if($tempHeadArray[$i]['headId']==$headId)
            {
               $tempHeadArray[$i] = array_merge($tempHeadArray[$i],array('amount'=>$amount));
               break;  
            }
        }
    }
    
    $sessionHandler->setSessionVariable('tempHeadArray',$tempHeadArray);
    $foundArray = $tempHeadArray;
      if(is_array($foundArray) && count($foundArray)>0 ) {
        $total=0;
        $cnt1=count($headArray);
        for($i=0;$i<count($foundArray);$i++)
        {
            //$foundArray_heads=PayrollManager::getInstance()->getHead(' WHERE headId='.$foundArray[$i]['headId']);
            //logError("abhiraj----->".$foundArray_heads[$i]['headType']);
            //logError("abhiraj----->".$foundArray_heads[$i]['headType']);
            if($foundArray[$i]['headType']==1)
            {
                $total=$total-$foundArray[$i]['amount'];
            }
            elseif($foundArray[$i]['headType']==0)
            {
                $total=$total+$foundArray[$i]['amount'];
            }
        }
        $totalArray=array('total'=>$total);  
        echo json_encode($totalArray);   
    }
    else
    {
         echo json_encode($totalArray=array('total'=>0)); 
    }
    }
    else
    {
        echo json_encode($totalArray=array('total'=>0));
    }


?>