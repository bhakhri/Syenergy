<?php
//This file calls Delete Function and Listing Function and creates Global Array in notice Module 
//
// Author :Rajeev Aggarwal
// Created on : 15-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    $dashboardManager = DashBoardManager::getInstance();
    
     
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
     $filter = ' AND (noticeSubject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR visibleFromDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR visibleToDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';            
    }
	
	//$curDate=date('Y')."-".date('m')."-".date('d');
    //$filter .=" AND ( '$curDate' >= n.visibleFromDate AND '$curDate' <= n.visibleToDate)"; 
	//if(UtilityManager::notEmpty($REQUEST_DATA['mgmt'])) {
	//	$filter .= ' AND nvr.roleId=5';
	//}
	 //AND nvr.roleId=5
    $totalArray = $dashboardManager->getTotalNotice($filter);
	$totalArray[0]['totalRecords']= count($totalArray); 
    $noticeRecordArray = $dashboardManager->getNoticeList($filter,$limit);  
	//$totalArray[0]['totalRecords']=count($noticeRecordArray);
?>
<?php 
//$History: initList.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Updated in $/LeapCC/Library/Management
//Updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Management
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/22/08   Time: 11:53a
//Updated in $/Leap/Source/Library/Management
//updated with validations for mangement role
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:27p
//Created in $/Leap/Source/Library/Management
//intial checkin
?>