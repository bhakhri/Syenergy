<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "degree" TABLE AND PAGING
//
// Author : Rajeev Aggarwal
// Created on : (12.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	$dashboardManager = DashBoardManager::getInstance();

	 /////pagination code    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  

	////filter code
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
	   $filter = ' WHERE (dg.degreeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR dg.degreeCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR dg.degreeAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
	}

	$totalArray = $dashboardManager->getTotalDegree($filter);
	$degreeRecordArray = $dashboardManager->getDegreeList($filter,$limit);
		
// $History: initDegreeList.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>