<?php
////  This File populates month and year dropdowns for payroll report
//
// Author :Abhiraj Malhotra
// Created on : 07-May-2010
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
logError("Param is: ".$REQUEST_DATA['param'].",Month is: ".$REQUEST_DATA['month']." ,Year is: ".$REQUEST_DATA['year']);
require_once(MODEL_PATH . "/PayrollManager.inc.php");
if(trim($REQUEST_DATA['param'] ) == 'confirm' && trim($REQUEST_DATA['month'] ) !="" && trim($REQUEST_DATA['year'] ) !="") {
    $flag=0;
    $wef = trim($REQUEST_DATA['year'])."-".trim($REQUEST_DATA['month'])."-01";
    $wefDate=date('Y-m-d', strtotime($wef));
      $checkArray=PayrollManager::getInstance()->countSalariesMapped($wefDate);
      $checkCurArray=PayrollManager::getInstance()->countSalariesMappedCurrent($wefDate);
      //logError("abhiraj".$checkArray[0]['recordCount']);
      if($checkArray[0]['recordCount']==0 && $checkCurArray[0]['recordCount']==0)
      {
          
          //logError("xxxxxxxxxxxxxxxxxxxxx".substr(date('M',strtotime('-1 Month',strtotime($wef))),0,3));
          //exit;
          //$prevMonth=substr(date('M',strtotime('-1 Month',strtotime($wef))),0,3);
          //$checkGeneratedArray=PayrollManager::getInstance()->checkPrevGeneratedSalary($prevMonth);
          
          //if($checkGeneratedArray[0]['recordCount']==0)
         // {
              $flag=-1;
              echo -1;
              
          //}
      }
      if($flag!=-1)
      { 
      $foundArray=PayrollManager::getInstance()->checkSalaryGenerated();
      $cnt=count($foundArray);
      $flag=1;  
     // echo "Month: ".$REQUEST_DATA['month'] ;
      for($i=0;$i<$cnt;$i++)
      {
          if($foundArray[$i]['month']==trim($REQUEST_DATA['month']) && $foundArray[$i]['year']==trim($REQUEST_DATA['year']))
          {
              $flag=0;
              break;
          }
          else
          {
              $flag++;                                      
          }
      }
      if($flag==0)
      {
          echo "GENERATED_ALREADY";
      }
      else
      {
          //PayrollManager::getInstance()->updateGenerateBit(trim($REQUEST_DATA['month']),trim($REQUEST_DATA['year']));
          echo SUCCESS;
          
      }
    }
}
elseif(trim($REQUEST_DATA['param'] ) != 'confirm' && trim($REQUEST_DATA['month'] ) !="" && trim($REQUEST_DATA['year']) !="")
{
      $wef = trim($REQUEST_DATA['year'])."-".trim($REQUEST_DATA['month'])."-01"; 
     
      $foundArray = PayrollManager::getInstance()->getSalariedEmployee('','employeeName');
      $count_employees=count($foundArray);
      $wef=date("Y-m-d", strtotime($wef));
      for($i=0;$i<$count_employees;$i++)
      {
          $foundArray1=PayrollManager::getInstance()->getAssignedHeads("where employeeId=".$foundArray[$i]['employeeId']." and                    
          SUBSTRING(MONTHNAME(withEffectFrom),1,3)='".trim($REQUEST_DATA['month'])."' and YEAR(withEffectFrom)='".trim($REQUEST_DATA['year'])."' 
          " );
         // print_r($foundArray1);
          if(count($foundArray1)>0)
          {   
              $tempHeadsArray=array();
              $cnt=count($foundArray1);
              for($j=0;$j<$cnt;$j++)
              {
                  $tempHeadsArray[$j]=array('headId'=>$foundArray1[$j]['headId'],'amount'=>$foundArray1[$j]['headValue']);
              }
              $status=PayrollManager::getInstance()->getEmpSalaryHistory($foundArray[$i]['employeeId'],trim($REQUEST_DATA['month']),
              trim($REQUEST_DATA['year']));
              $count_status=count($status);
              $status=$status[0]['status'];
              PayrollManager::getInstance()->clearSalaryGenerated($foundArray[$i]['employeeId'], trim($REQUEST_DATA['month']), trim($REQUEST_DATA['year']));
             
              if($count_status>0 && $status!=1)
              {
                  for($j=0;$j<$cnt;$j++)
                  {
                      
                      PayrollManager::getInstance()->saveHeadsMapping($tempHeadsArray[$j],$foundArray[$i]['employeeId'],$wef);
                  }
              }
              if($status==0 && $count_status>0)
              {
                PayrollManager::getInstance()->recordSalaryGenerated($foundArray[$i]['employeeId'],trim($REQUEST_DATA['month']),
                trim($REQUEST_DATA['year']),'0'); //0 is carry forward and 1 is modified  
              }
              elseif($status==1 && $count_status>0)
              {
                 PayrollManager::getInstance()->recordSalaryGenerated($foundArray[$i]['employeeId'],trim($REQUEST_DATA['month']),
                 trim($REQUEST_DATA['year']),'1'); //0 is carry forward and 1 is modified 
              }
              else
              {
                 PayrollManager::getInstance()->recordSalaryGenerated($foundArray[$i]['employeeId'],trim($REQUEST_DATA['month']),
                 trim($REQUEST_DATA['year']),'1'); //0 is carry forward and 1 is modified 
              }
             // PayrollManager::getInstance()->recordSalaryGenerated($foundArray[$i]['employeeId'],trim($REQUEST_DATA['month']),
              //trim($REQUEST_DATA['year']),'1'); //0 is carry forward and 1 is modified
          }
          else
          {
              $empid = $foundArray[$i]['employeeId'];
               $tempHeadsArray=array();
              $foundArray2=PayrollManager::getInstance()->getAssignedHeads("where employeeId=".$foundArray[$i]['employeeId']." and
              active=1");
              $cnt_salaried=count($foundArray2);
              for($j=0;$j<$cnt_salaried;$j++)
              {
                  $tempHeadsArray[$j]=array('headId'=>$foundArray2[$j]['headId'],'amount'=>$foundArray2[$j]['headValue']);
              }
              PayrollManager::getInstance()->inactivePrevHeadMappings($foundArray[$i]['employeeId']);
              logError("Emp id is: ".$foundArray[$i]['employeeId']." count is: ".$cnt_salaried." and array count is: ".count(                            $tempHeadsArray));
              logError("The date is :". $wef);
              $wef=date("Y-m-d", strtotime($wef));
              
              for($j=0;$j<$cnt_salaried;$j++)
              { 
                  PayrollManager::getInstance()->saveHeadsMapping($tempHeadsArray[$j],$foundArray[$i]['employeeId'],$wef);
              }
              $result=PayrollManager::getInstance()->recordSalaryGenerated($foundArray[$i]['employeeId'],trim($REQUEST_DATA['month']),
              trim($REQUEST_DATA['year']),'0'); //0 is carry forward and 1 is modified
          }
      }
     PayrollManager::getInstance()->updateGenerateBit(trim($REQUEST_DATA['month']),trim($REQUEST_DATA['year'])); 
     echo SUCCESS; 
}
else
{
    echo 0;
}
	


?>