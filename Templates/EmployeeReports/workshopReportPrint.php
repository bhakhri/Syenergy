<?php 
//This file is used as Print Workshop
//
// Author :Parveen Sharma
// Created on : 06-01-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
    $workshopManager = EmployeeManager::getInstance();

     
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (w.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.sponsored LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.sponsoredDetail LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.location LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'topic';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND w.employeeId = '.add_slashes($REQUEST_DATA['employeeId']);
     
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];
    
    ////////////
    $workshopRecordArray = $workshopManager->getWorkshopList($condition,$orderBy);
    $cnt = count($workshopRecordArray);
    for($i=0;$i<$cnt;$i++) {
       if($workshopRecordArray[$i]['startDate']=='0000-00-00') {
         $workshopRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
       }
       else {
         $workshopRecordArray[$i]['startDate'] = UtilityManager::formatDate($workshopRecordArray[$i]['startDate']);
       }
        
       if($workshopRecordArray[$i]['endDate']=='0000-00-00') {
         $workshopRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
       }
       else {
         $workshopRecordArray[$i]['endDate'] = UtilityManager::formatDate($workshopRecordArray[$i]['endDate']);
       }
        
       if($workshopRecordArray[$i]['sponsored']=='N') {
         $workshopRecordArray[$i]['sponsoredDetail'] = NOT_APPLICABLE_STRING;
       }
       else {
         $workshopRecordArray[$i]['sponsoredDetail'] = $workshopRecordArray[$i]['sponsoredDetail'];
       }
       
       $valueArray[$i] = array_merge(array('srNo' => ($records+$i+1) ),$workshopRecordArray[$i]);
       
       $reportHead  = "Employee Name&nbsp;:&nbsp;".$workshopRecordArray[$i]['employeeName'];
       $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$workshopRecordArray[$i]['employeeCode'];
    }  
    $reportHead .= "<br>SearchBy&nbsp;:&nbsp;".$REQUEST_DATA['searchbox'];

 
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Workshop Report');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead = array();
    
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']             =    array('#',                'width="2%"  align="center"', 'align="center" valign="top"');
    $reportTableHead['topic']            =    array('Topic',            'width=20%   align="left" ','align="left" valign="top"  ');
    $reportTableHead['startDate']        =    array('Start Date',       'width="8%" align="center" ','align="center" valign="top" ');
    $reportTableHead['endDate']          =    array('End Date',         'width="8%" align="center" ','align="center" valign="top" ');
    $reportTableHead['sponsoredDetail']  =    array('Sponsored',        'width="15%" align="left" ','align="left" valign="top" ');
    $reportTableHead['audience']         =    array('Audience',         'width="15%" align="left" ','align="left" valign="top" ');
    $reportTableHead['location']         =    array('Location',         'width="12%" align="left" ','align="left" valign="top" ');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: workshopReportPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/EmployeeReports
//record limits remove,format & new enhancements added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Templates/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:49p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//


?>