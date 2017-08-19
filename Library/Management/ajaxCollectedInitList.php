<?php
//-------------------------------------------------------
// Purpose: To store the records of fee collection in array from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (12.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifManagementNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (stu.firstName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR stu.lastName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';   
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptNo';
    $orderBy = " $sortField $sortOrderBy";
    $totalArray = count($dashboardManager->getCollectedFeesList($filter));
    $branchRecordArray = $dashboardManager->getCollectedFeesList($filter,$limit,$orderBy);
    $cnt = count($branchRecordArray);
    for($i=0;$i<$cnt;$i++) {

		$retStatus = $receiptArr[$branchRecordArray[$i]['receiptStatus']];
        // add countryId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('retStatus' =>  $retStatus,'action' => $branchRecordArray[$i]['feeReceiptId'] , 'srNo' => ($records+$i+1) ),$branchRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray.'","page":"'.$page.'","info" : ['.$json_val.']}';
  //$History : $  
?>