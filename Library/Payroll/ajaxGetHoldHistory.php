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
require_once(MODEL_PATH . "/PayrollManager.inc.php");
if(trim($REQUEST_DATA['param'] ) == 'getReason')
{
 $holdArray = PayrollManager::getInstance()->getSalaryHoldDetails(trim($REQUEST_DATA['empId']));
 if(count($holdArray)>0)
 {
     echo $holdArray[0]['reason'];
 }  
 else
 {
     echo 0;
 }
   
}
elseif(trim($REQUEST_DATA['empId'] ) != '') {
    
    $foundArray = PayrollManager::getInstance()->getAllSalaryHoldDetails(trim($REQUEST_DATA['empId']));
	
   if(is_array($foundArray) && count($foundArray)>0 ) {  
        $str='<table width="100%" border=0 cellspacing=1 cellpadding=2 >'.
        $str.='<tr class="rowheading">
        <td width=3% align=center  style=padding:5px;><b>#</b></td>
        <td width=15% align=center style=padding:5px;><b>Action</b></td>
        <td width=15% align=center style=padding:5px;><b>For</b></td>
        <td width=17% align=center style=padding:5px;><b>On</b></td>
        <td width=10% align=center style=padding:5px;><b>By</b></td>
        <td width=40% align=center style=padding:5px;><b>Reason</b></td>';
        $str.='</tr><tr class="contenttab_internal_rows">';
        
        for($i=0;$i<count($foundArray);$i++)
        {
            $str.='<td width=3% align=center  style=padding:5px;>'.($i+1).'</td>'; 
            if($foundArray[$i]['status']==0)
            {
                $str.='<td width=15% align=center  style=padding:5px;>Unheld</td>';
            }
            else
            {
                $str.='<td width=15% align=center  style=padding:5px;>On Hold</td>';  
            }
            $str.='<td width=15% align=center style=padding:5px;>'.$foundArray[$i]['month'].' '.$foundArray[$i]['year'].'</td>';
            $str.='<td  width=17% align=center  style=padding:5px;>'.date('d-M-y',strtotime($foundArray[$i]['takenon'])).'</td>';
            $idToName=PayrollManager::getInstance()->getSingleField('employee','employeeName','where userId='.$foundArray[$i]['actionby']);
            if(count($idToName)==0)
            {
                $nm='---';
            }
            else
            {
                $nm=$idToName[0]['employeeName'];
            }
            $str.='<td width=10% align=left  style=padding:5px;>'.$nm.'</td>';
            $str.='<td width=40% align=left  style=padding:5px;>'.$foundArray[$i]['reason'].'</td>';
            $str.='</tr>';
        }
        $str.='</tr></table>';
        echo $str;
    }
    else {
        echo 0;
    }
	
}

?>