<?php
//-------------------------------------------------------
// Purpose: To store the records of payroll deduction accounts in array from the database, pagination and search, delete 
// functionality
//
// Author : Abhiraj Malhotra
// Created on : 14-Apr-2010
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
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE accountName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" or accountNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'accountName';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $payrollManager->getTotalAccounts($filter);
    $yearsRecordArray = $payrollManager->getAccountList($filter,$limit,$orderBy);
    $cnt = count($yearsRecordArray);
    
    for($i=0;$i<$cnt;$i++) { 

        $valueArray = array_merge(array('action' =>  $yearsRecordArray[$i]['dedAccountId'] , 'srNo' => ($records+$i+1) ),$yearsRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>
