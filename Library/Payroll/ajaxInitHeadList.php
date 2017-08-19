<?php
//-------------------------------------------------------
// Purpose: To store the records of salary heads in array from the database, pagination and search, delete 
// functionality
//
// Author : Abhiraj Malhotra
// Created on : 08-April-2010
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
       $filter = '  WHERE headName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'Asc';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $payrollManager->getTotalHeads($filter);
    $headRecordArray = $payrollManager->getHeadList($filter,$limit,$orderBy);
    $cnt = count($headRecordArray);
    
    for($i=0;$i<$cnt;$i++) { 
        $actionStr1 = "<img src=".IMG_HTTP_PATH."/eye.png    
                        align='center' onClick='head_desc(".$headRecordArray[$i]['headId']."); return false;' />&nbsp";
        if($headRecordArray[$i]['headType']==0)
        {
              $headType_txt="Earning";
        }
        else
        {
             $headType_txt="Deduction";
        }
        if($headRecordArray[$i]['headType']==0)
        {
            $dedAccount_txt="--";
        }
        else
        {
        $dedAccount=PayrollManager::getInstance()->getDedAccount($conditions='Where dedAccountId='.$headRecordArray[$i]['dedAccountId']);
        $dedAccount_txt=$dedAccount[0]['accountName']."(".$dedAccount[0]['accountNumber'].")";
        }
        
        
        $valueArray = array_merge(array('headTypeText' =>  $headType_txt, 'dedAccountIdText' =>  $dedAccount_txt, 'desc' =>  $actionStr1, 'action' =>  $headRecordArray[$i]['headId'] , 'srNo' => ($records+$i+1) ),$headRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitHeadList.php $
//
//*****************  Version 1  *****************
//User: Abhiraj      Date: 4/08/10    Time: 12:41p
//Created in $/Leap/Source/Library/Payroll
//File created for Payroll Heads Master


?>
