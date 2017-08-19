<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','InsuranceClaim');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/InsuranceClaimManager.inc.php");
    $insuranceClaimManager = InsuranceClaimManager::getInstance();

    /////////////////////////
    
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    //////
    /// Search filter /////  
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		
       $filter = ' AND (b.busNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR vic.claimAmount LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR vic.totalExpenses LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR vic.selfExpenses LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR vic.loggingClaim LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $insuranceClaimManager->getTotalInsuranceClaim($filter);
    $insuranceClaimRecordArray = $insuranceClaimManager->getInsuranceClaimList($filter,$limit,$orderBy);
    $cnt = count($insuranceClaimRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $insuranceClaimRecordArray[$i]['claimDate'] = UtilityManager::formatDate($insuranceClaimRecordArray[$i]['claimDate']);
		$insuranceClaimRecordArray[$i]['dateOfSettlement'] = UtilityManager::formatDate($insuranceClaimRecordArray[$i]['dateOfSettlement']);
        $valueArray = array_merge(array('action' => $insuranceClaimRecordArray[$i]['claimId'] , 'srNo' => ($records+$i+1) ),$insuranceClaimRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:45a
//Created in $/Leap/Source/Library/InsuranceClaim
//new ajax files for vehicle insurance claim
//
//
?>