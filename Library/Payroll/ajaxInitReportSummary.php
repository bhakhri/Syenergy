<?php
/*
  This File initialises the salary report summary

 Author :Abhiraj Malhotra
 Created on : 04-May-2010
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
require_once(MODEL_PATH . "/PayrollManager.inc.php");
$foundArray = CommonQueryManager::getInstance()->getEmployee('employeeName','and instituteId='.$sessionHandler->getSessionVariable("InstituteId"));  
        if(trim($REQUEST_DATA['month'])!='' && trim($REQUEST_DATA['year'])!="") 
        {   
            $month=substr(trim($REQUEST_DATA['month']),0,3);
            //logError("The Month Is:".$month);
            $year=trim($REQUEST_DATA['year']);   
			$cnt=count($foundArray);
            $count=0;
            $amount=0;
            $totalEarning=0;
            $totalDeduction=0;
            $holdCount=0;
            for($i=0;$i<$cnt;$i++)
            {
               $employeeId=$foundArray[$i]['employeeId'];
                $holdArray = PayrollManager::getInstance()->getSalaryHoldDetails($employeeId,$month,$year);
                if(count($holdArray)>0 && $holdArray[0]['status']==1)
                {
                    $holdCount++;
                   // echo "empId1:".$employeeId; 
                }
                else
                {
                   //  echo "empId2:".$employeeId; 
               $conditions="where employeeId=".$employeeId." and substring(MONTHNAME(withEffectFrom),1,3) like '".$month."' and YEAR(withEffectFrom) like '".$year."'";
               $foundAssignedArray = PayrollManager::getInstance()->getAssignedHeads($conditions);
               $cnt1=count($foundAssignedArray); 
               if($cnt1>0)
               {
                   $count=$count+1;
                   for($j=0;$j<$cnt1;$j++)
                   {
                       $foundHeadArray=PayrollManager::getInstance()->getHead('where headId='.$foundAssignedArray[$j]['headId']);
                       if($foundHeadArray[0]['headType']==0)
                       {
                           $totalEarning=$totalEarning+$foundAssignedArray[$j]['headValue'];
                       }
                       elseif($foundHeadArray[0]['headType']==1)
                       { 
                           $totalDeduction=$totalDeduction+$foundAssignedArray[$j]['headValue'];
                       }
                   } 
                 }
                } 
            }
            $amount=$totalEarning-$totalDeduction;
            $valueArray=array('amount'=>$amount, 'count'=>$count, 'empCount'=>$cnt, 'holdCount'=>$holdCount);
            echo json_encode($valueArray);	
        }
        else 
        {
            echo 0;
        }
        
?>

