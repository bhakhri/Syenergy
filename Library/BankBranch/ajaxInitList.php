<?php
//-------------------------------------------------------
// Purpose: To store the records of BankBranch in array from the database, pagination and search, delete 
// functionality
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BankBranchManager.inc.php");
    $bankBranchManager = BankBranchManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  AND (a.branchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.branchAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.bankName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bankName';
    
     $orderBy = " $sortField $sortOrderBy";

    
    $totalArray = $bankBranchManager->getTotalBankBranch($filter);
    $bankBranchRecordArray = $bankBranchManager->getBankBranchList($filter,$limit,$orderBy);

    $cnt = count($bankBranchRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add bankId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $bankBranchRecordArray[$i]['bankBranchId'] , 'srNo' => ($records+$i+1) ),$bankBranchRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Library/BankBranch
//Merged Bank & BankBranch module in single module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BankBranch
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:58a
//Updated in $/Leap/Source/Library/BankBranch
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 5:49p
//Created in $/Leap/Source/Library/BankBranch
//File added for bank branch master

?>
