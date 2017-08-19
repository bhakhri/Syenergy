<?php

//  This File calls Edit Function used in adding Bank Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/BankManager.inc.php");
    $bankManager = BankManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['bankId']) && $REQUEST_DATA['act']=='del') {
            
        if($recordArray[0]['found']==0) {
            if($bankManager->deleteBank($REQUEST_DATA['bankId']) ) {
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
       $filter = ' AND (bk.bankName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'% or bk.bankAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $totalArray = $bankManager->getTotalBank($filter);
    $bankRecordArray = $bankManager->getBankList($filter,$limit);

// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Bank
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master

?>