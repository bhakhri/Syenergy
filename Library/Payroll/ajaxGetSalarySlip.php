<?php
/*
  This File generates salary slip for an employee

 Author :Abhiraj Malhotra
 Created on : 05-May-2010
 Copyright 2008-2009: syenergy Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
if($sessionHandler->getSessionVariable('RoleId')==2)
{
    UtilityManager::ifTeacherNotLoggedIn(true);
}
else
{
    UtilityManager::ifNotLoggedIn(true); 
}
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
require_once(MODEL_PATH . "/PayrollManager.inc.php");
//logError("xxxxxxxxxxxxxxxxxxxxxxx".substr(trim($REQUEST_DATA['salMonth']),0,3));
          
        if(trim($REQUEST_DATA['salMonth'])!='' && trim($REQUEST_DATA['salYear'])!="" && trim($REQUEST_DATA['empId'])!='') 
        {   
            //For showing salary held in case the salary is on hold
            $holdArray = PayrollManager::getInstance()->getSalaryHoldDetails(trim($REQUEST_DATA['empId']),
            substr(trim($REQUEST_DATA['salMonth']),0,3),trim($REQUEST_DATA['salYear']));
             if(count($holdArray)>0 && $holdArray[0]['status']==1)
             {
                 $str="-1|".$holdArray[0]['reason'];
                 echo $str;
             }
             else
             {
                $empId=trim($REQUEST_DATA['empId']);
                //logError("xxxxxxxxxxxxxx".$empId);
                $foundArray = CommonQueryManager::getInstance()->getEmployee('employeeName','and employeeId='.$empId);
                $month=trim($REQUEST_DATA['salMonth']);
                $year=trim($REQUEST_DATA['salYear']);   
		        $cnt=count($foundArray);
                $totalEarning=0;
                $totalDeduction=0;
                $cnt_earning=0;
                $cnt_deduction=0;
                $json_val_Earning="";
                $json_val_Deduction="";
                $amount=0;
                   if($cnt>0)
                   {
                       $department = PayrollManager::getInstance()->getSingleField('department','departmentName','where departmentId='.
                       $foundArray[0]['departmentId']);
                       $designation = PayrollManager::getInstance()->getSingleField('designation','designationName','where 
                       designationId='.$foundArray[0]['designationId']);
                       $employeeArray=json_encode(array('employeeName'=>$foundArray[0]['employeeName'], 
                       'employeeCode'=>$foundArray[0]['employeeCode'], 'esiNumber'=>$foundArray[0]['esiNumber'], 
                       'panNo'=>$foundArray[0]['panNo'],'providentFundNo'=>$foundArray[0]['providentFundNo'], 
                       'employeeDept'=>$department[0]['departmentName'], 'employeeDesignation'=>$designation[0]['designationName']));
                       if($month==date('M')&& $year==date('Y'))
                       {
                         $conditions="where employeeId=".$empId." and MONTHNAME(withEffectFrom) like '".$month."' and YEAR(withEffectFrom) 
                         like '".$year."' and active=1 and generated=1";  
                       }
                       else
                       {
                       $conditions="where employeeId=".$empId." and MONTHNAME(withEffectFrom) like '".$month."' and YEAR(withEffectFrom) 
                       like '".$year."' and generated=1";
                       }
                       $foundAssignedArray = PayrollManager::getInstance()->getAssignedHeads($conditions);
                       $cnt1=count($foundAssignedArray);
                       if($cnt1>0)
                       {
                           for($j=0;$j<$cnt1;$j++)
                           {
                               $foundHeadArray=PayrollManager::getInstance()->getHead('where headId='.$foundAssignedArray[$j]['headId']);
                               if($foundHeadArray[0]['headType']==0)
                               {
                                   
                                   $totalEarning=$totalEarning+$foundAssignedArray[$j]['headValue'];
                                   $valueArray_earning[$cnt_earning]=array('headName'=>$foundHeadArray[0]['headName'], 'headValue'=>                                            $foundAssignedArray[$j]['headValue']);
                                    if(trim($json_val_Earning)=='') {
                                    $json_val_Earning = json_encode($valueArray_earning[$cnt_earning]);
                                    }
                                     else {
                                     $json_val_Earning .= ','.json_encode($valueArray_earning[$cnt_earning]);           
                                     }
                                     $cnt_earning++;
                               }
                               elseif($foundHeadArray[0]['headType']==1)
                               { 
                                   $valueArray_deduction[$cnt_deduction]=array('headName'=>$foundHeadArray[0]['headName'], 'headValue'=>                                      $foundAssignedArray[$j]['headValue']);
                                   $totalDeduction=$totalDeduction+$foundAssignedArray[$j]['headValue'];
                                   if(trim($json_val_Deduction)=='') {
                                    logError("value: ".$valueArray_deduction[$cnt_deduction]."<br>");   
                                    $json_val_Deduction = json_encode($valueArray_deduction[$cnt_deduction]);
                                    }
                                     else {
                                     $json_val_Deduction .= ','.json_encode($valueArray_deduction[$cnt_deduction]);           
                                     }
                                     $cnt_deduction++;
                               }
                           } 
                       }
                       $amount=$totalEarning-$totalDeduction;
                       $valueArray_total=array('totalEarning'=>$totalEarning, 'totalDeduction'=>$totalDeduction, 'net'=>$amount);
                       $json_totals=json_encode($valueArray_total);
                       echo '{"infoEarning" : ['.$json_val_Earning.'],"infoDeduction" : ['.$json_val_Deduction.'],"total" : ['.$json_totals.'],"employeeInfo" : ['.$employeeArray.']}'; 
                       } 
            
            else
            {
                logError("test1");
                echo 0;
            }
           }               	
        }
        else 
        {
            logError("test2");
            echo 0;
        }
        
?>

