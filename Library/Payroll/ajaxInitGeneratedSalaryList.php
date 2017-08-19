<?php
//-------------------------------------------------------
// Purpose: To display employees after salary is generated 
//
// Author : Abhiraj Malhotra
// Created on : 10-May-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
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
    $payrollManager = PayrollManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 :                                $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $dataArray = $payrollManager->getSalariedEmployee('where SUBSTRING(MONTHNAME(withEffectFrom),1,3)='."'".trim($REQUEST_DATA['month'])."'".' and YEAR(withEffectFrom)='."'".trim($REQUEST_DATA['year'])."'",$orderBy,$limit);
    $totalArray[0]['totalRecords']=count($dataArray);
    //$headRecordArray = $payrollManager->getHeadList($filter,$limit,$orderBy);
    $cnt = count($dataArray);
    
    for($i=0;$i<$cnt;$i++) { 
        //$historyArray= $payrollManager->getEmpSalaryHistory($dataArray[$i]['employeeId'],$REQUEST_DATA['month'],$REQUEST_DATA['year']);
        //$historyCount=count($historyArray);
        //if($historyArray[0]['status']==0 && $historyCount>0)
        //{
        //     // $status_txt="Generated (Carry Forward)";
        //      $status_txt="Generated";
        //}
        //else
        //{
             //$status_txt="Generated (Newly Defined)";
        //     $status_txt="Generated";
        //}
        $generated = PayrollManager::getInstance()->getSingleField('employee_salary_breakup','generated','where employeeId='.$dataArray[$i]['employeeId']);
        if($generated[0]['generated']==0)
        {
            $status_txt="Not Generated";
        }
        else
        {
            $status_txt="Generated";
        }
         $departmentName = PayrollManager::getInstance()->getSingleField('department','departmentName','where departmentId='.$dataArray[$i]         ['departmentId']);
        $designation = PayrollManager::getInstance()->getSingleField('designation','designationName','where designationId='.$dataArray[$i][         'designationId']);
        
        $valueArray = array('employeeName' =>  $dataArray[$i]['employeeName'], 'employeeCode' => $dataArray[$i]['employeeCode'], 'status'        =>  $status_txt, 'department' =>  $departmentName[0]['departmentName'] , 'designation' =>$designation[0]['designationName'],'srNo'         => ($records+$i+1));

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 



?>
