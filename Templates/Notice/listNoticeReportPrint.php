 <?php 
//This file is used as CSV version for display countries.
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');     
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/NoticeManager.inc.php");
    $noticeManager =NoticeManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

	/// Search filter ///// 
    $filter = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {                         
        $filter = ' AND  (noticeSubject LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           DATE_FORMAT(visibleFromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           DATE_FORMAT(visibleToDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           departmentName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                           noticePublishTo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                           visibleMode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeSubject';
    $orderBy = " $sortField $sortOrderBy";         

    
    $noticeRecordArray = $noticeManager->getNoticeListNew($filter,'',$orderBy);
    $cnt = count($noticeRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $noticeRecordArray[$i]['visibleFromDate']=UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate']));
        $noticeRecordArray[$i]['visibleToDate']=UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate']));
        $noticeRecordArray[$i]['roleName']=UtilityManager::getTitleCase(strip_slashes($noticeRecordArray[$i]['roleName']));
        $noticeRecordArray[$i]['departmentName']=UtilityManager::getTitleCase(strip_slashes($noticeRecordArray[$i]['departmentName'])); 
        $noticeRecordArray[$i]['noticeSubject'] = chunk_split($noticeRecordArray[$i]['noticeSubject'],28," "); 
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$noticeRecordArray[$i]);
    }
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Manage Notices Report Print');
    $reportManager->setReportInformation("Search by: ".$search);


    $reportTableHead                        =    array();
    $reportTableHead['srNo']		    =    array('#',             ' width="2%"  align="left"', "align='left'");
    $reportTableHead['visibleFromDate']     =    array('Visible From',  ' width="12%" align="center" ','align="center"');
    $reportTableHead['visibleToDate']       =    array('Visible To',    ' width="12%" align="center" ','align="center"');    
    $reportTableHead['noticeSubject']	    =    array('Subject ',      ' width=20%   align="left" ','align="left" ');
    $reportTableHead['departmentName']	    =    array(' Issuing Dept.',    ' width="20%" align="left" ','align="left"');
    $reportTableHead['visibleMode']            =    array('Mode',    ' width="15%" align="left" ','align="left"');
    $reportTableHead['noticePublishTo']            =    array('Publish To',    ' width="15%" align="left" ','align="left"');
    
    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
