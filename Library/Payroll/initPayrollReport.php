<?php
//-------------------------------------------------------
// Purpose: To initialise the payroll report for all employees or a selected employee
//
// Author : Abhiraj Malhotra
// Created on : 03-May-2010
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

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
    
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $payrollManager = PayrollManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = " $sortField $sortOrderBy";
    if($sessionHandler->getSessionVariable('month')=="")
    {         
        $month=$REQUEST_DATA['month'];
    }
    else
    {
        $month=$sessionHandler->getSessionVariable('month'); 
    }
    if($sessionHandler->getSessionVariable('year')=="")
    {
        $year=$REQUEST_DATA['year'];
    }
    else
    {
        $year=$sessionHandler->getSessionVariable('year');        
    }
    logError("Month is:".$month);
    logError("year is:".$year);
    $json_val="";
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    if(!UtilityManager::notEmpty($REQUEST_DATA['employee']))
    {
    //logError("hello".$REQUEST_DATA['employee']);
     logError($orderBy.$limit);
    $foundArray = CommonQueryManager::getInstance()->getEmployee($orderBy.$limit,' and instituteId='.$sessionHandler->getSessionVariable("InstituteId"));
    $countArray = CommonQueryManager::getInstance()->getEmployee('employeeName','and instituteId='.$sessionHandler->getSessionVariable("InstituteId"));
    $cnt=count($foundArray);
    $totalCount=count($countArray);
    $totalArray[0]['totalRecords']=$totalCount;
    logError("hello+".$cnt);    
    $totalEarning=0;
    $totalDeduction=0;
    for($i=0;$i<$cnt;$i++)
    {
        $totalEarning=0;
        $totalDeduction=0;
        $employeeId=$foundArray[$i]['employeeId'];
        $conditions="where employeeId=".$employeeId." and MONTHNAME(withEffectFrom) like '".$month."' and YEAR(withEffectFrom) like '".$year."'";
        $foundAssignedArray = PayrollManager::getInstance()->getAssignedHeads($conditions);
       
       $cnt1=count($foundAssignedArray);
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
        if($totalEarning==0 && $totalDeduction==0)
        {
            $salary="Not Set";
        }
        else
        {
            $net=$totalEarning-$totalDeduction;
            $salary="Earn : Rs.".$totalEarning."/-<br>";
            $salary=$salary."Ded : Rs.".$totalDeduction."/-<br>";
            $salary=$salary."Net : Rs.".$net."/-";
        }
       if($foundArray[$i]['departmentId']=="")
       {
           $departmentName[0]['departmentName']="NA";
       }
       else
       { 
           $departmentName = PayrollManager::getInstance()->getSingleField('department','departmentName',
           'where departmentId='.$foundArray[$i]['departmentId']);
       }
       if($foundArray[$i]['designationId']=="")
       {
           $departmentName[0]['departmentName']="NA";
       }
       else
       {
            $designation = PayrollManager::getInstance()->getSingleField('designation','designationName',
            'where designationId='.$foundArray[$i]['designationId']);
       }
       $holdArray = PayrollManager::getInstance()->getSalaryHoldDetails($foundArray[$i]['employeeId'],substr($month,0,3),$year);
       //$act='<a href=# onclick=showSalaryDetail('.$employeeId.',"'.$month.'","'.$year.'");>Salary Slip</a>';
       logError("sdafsdfsf".$holdArray[0]['status']);
       if(count($holdArray)>0 && $holdArray[0]['status']==1)
       {
           $act='<a href=# onclick=showHoldReason('.$employeeId.',"'.substr($month,0,3).'","'.$year.'");>On Hold</a>';
       }
       else
       {
           $act='<a href=# onclick=showSalaryDetail('.$employeeId.',"'.$month.'","'.$year.'"); style="color:#0033cc">Salary Slip</a>';
       }
       
       $totalAssignedArray[$i]=array('employeeName'=>$foundArray[$i]['employeeName'], 'employeeCode'=>$foundArray[$i]['employeeCode']
       , 'employeeDesignation'=>$designation[0]['designationName'], 'employeeDepartment'=>$departmentName[0]['departmentName'], 'dateOfJoining'=>date("d-M-y",strtotime($foundArray[$i]['dateOfJoining']))
       , 'salary'=>$salary, 'srNo'=>($records+$i+1), 'act'=>$act);
       if(trim($json_val)=='') {
            $json_val = json_encode($totalAssignedArray[$i]);
       }
       else {
            $json_val .= ','.json_encode($totalAssignedArray[$i]);           
       } 
    }
    
    }
    else
    {
        logError("hello".$REQUEST_DATA['employee']);
        $startPos=strpos(trim($REQUEST_DATA['employee']),"(")+1;
        $endPos=strripos(trim($REQUEST_DATA['employee']),"(")-1;
        $len=($endPos-$startPos)+1;
        $employeeCode=substr(trim($REQUEST_DATA['employee']),$startPos,$len);
        $employeeCode=trim($employeeCode);
         $act='';
        $conditions="and employeeCode like '".$employeeCode."' and instituteId=".$sessionHandler->getSessionVariable("InstituteId");
        $foundArray = CommonQueryManager::getInstance()->getEmployee('employeeName LIMIT 0,1',$conditions);
        $employeeId=$foundArray[0]['employeeId'];
        logError("xxxxxxxxxxxx".$employeeId); 
        $conditions="where employeeId=".$employeeId." and MONTHNAME(withEffectFrom) like '".$month."' and YEAR(withEffectFrom) like '".$year."'";
        $foundAssignedArray = PayrollManager::getInstance()->getAssignedHeads($conditions); 
        
        $cnt1=count($foundAssignedArray);
        
        for($j=0;$j<$cnt1;$j++)
        {
           $foundHeadArray=PayrollManager::getInstance()->getHead('where headId='.$foundAssignedArray[$j]['headId']);
           logError("hellox".$foundHeadArray[0]['headType']);  
           if($foundHeadArray[0]['headType']==0)
           {
            
               $totalEarning=$totalEarning+$foundAssignedArray[$j]['headValue'];
           }
           elseif($foundHeadArray[0]['headType']==1)
           {
                 $totalDeduction=$totalDeduction+$foundAssignedArray[$j]['headValue'];
           }
        }
       // logError("hellox".$foundHeadArray[0]['headType']);
        //echo($foundHeadArray);
        if($totalEarning==0 && $totalDeduction==0)
        {
            $salary="Not Set";
        }
        else
        { 
            $net=$totalEarning-$totalDeduction;
            $salary="Earn : Rs.".$totalEarning."/-<br>";
            $salary=$salary."Ded : Rs.".$totalDeduction."/-<br>";
             $salary=$salary."Net : Rs.".$net."/-";
        }
        $holdArray = PayrollManager::getInstance()->getSalaryHoldDetails($foundArray[0]['employeeId'],$month,$year);
       //$act='<a href=# onclick=showSalaryDetail('.$employeeId.',"'.$month.'","'.$year.'");>Salary Slip</a>';
       if(count($holdArray)>0 && $holdArray[0]['status']==1)
       {
           $act='<a href=# onclick=showHoldReason('.$employeeId.',"'.$month.'","'.$year.'");>On Hold</a>';
       }
       else
       {
           $act='<a href=# onclick=showSalaryDetail('.$employeeId.',"'.$month.'","'.$year.'"); style="color:#0033cc">Salary Slip</a>';
       }
        $departmentName = PayrollManager::getInstance()->getSingleField('department','departmentName',
        'where departmentId='.$foundArray[0]['departmentId']);
            $designation = PayrollManager::getInstance()->getSingleField('designation','designationName',
            'where designationId='.$foundArray[0]['designationId']);
            $totalAssignedArray=array('employeeName'=>$foundArray[0]['employeeName'], 'employeeCode'=>$foundArray[0]['employeeCode']
            , 'employeeDesignation'=>$designation[0]['designationName'], 'employeeDepartment'=>$departmentName[0]['departmentName'], 'dateOfJoining'=>date("d-M-y",strtotime($foundArray[0]['dateOfJoining']))
            , 'salary'=>$salary,'srNo'=>($records+$i+1), 'act'=>$act); 
            $totalArray[0]['totalRecords']=1;
            $json_val = json_encode($totalAssignedArray);
    }
               
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 


?>
