<?php

//  This File calls Edit Function used in adding BankBranch Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/BankBranchManager.inc.php");
    $bankBranchManager = BankBranchManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['bankBranchId']) && $REQUEST_DATA['act']=='del') {
            
        if($recordArray[0]['found']==0) {
            if($bankBranchManager->deleteBankBranch($REQUEST_DATA['bankBranchId']) ) {
                $message = DELETE;
            }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        }
    }
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (a.branchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.branchAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.bankName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $totalArray = $bankBranchManager->getTotalBankBranch($filter);
    $bankBranchRecordArray = $bankBranchManager->getBankBranchList($filter,$limit);

// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BankBranch
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 5:49p
//Created in $/Leap/Source/Library/BankBranch
//File added for bank branch master


?>