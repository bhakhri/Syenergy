<?php
//-------------------------------------------------------
// Purpose: To store the records of states in array from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (12.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    $studentRecordArray = $dashboardManager->getCollectedFeesList($filter,$limit,$orderBy);
     
  //$History : $  
?>