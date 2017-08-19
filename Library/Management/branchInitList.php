<?php
//This file calls Delete Function and Listing Function and creates Global Array in Branch Module 
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	
	require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	$dashboardManager = DashBoardManager::getInstance();
     
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (branchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $totalArray = $dashboardManager->getTotalBranch($filter);
    $branchRecordArray = $dashboardManager->getBranchList($filter,$limit);

//$History: branchInitList.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>