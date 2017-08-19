<?php 
//This file is used as CSV format for General Survery FeedBack 
//
// Author :Rajeev Aggarwal
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
    $publishingManager = EmployeeManager::getInstance();

    
    global $publisherScopeArr;
    
   // Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       foreach($publisherScopeArr as $key=>$value)
       {
          if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $scopeId = " OR scopeId LIKE '%$key%' ";
           break;
         }
       }      
       $condition = ' AND (p.type LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.publishedBy LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.description LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$scopeId.')';
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'type';
    
    $orderBy = " $sortField $sortOrderBy";         

    $condition .= ' AND p.employeeId = '.add_slashes($REQUEST_DATA['employeeId']);
     
    ////////////
    $publishingRecordArray = $publishingManager->getPublishingList($condition,$orderBy);
    $cnt = count($publishingRecordArray);
    
    $reportHead = "";
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];

      
    
    for($i=0;$i<$cnt;$i++) {
        if($publishingRecordArray[$i]['publishOn']=='0000-00-00') {
           $publishingRecordArray[$i]['publishOn'] = NOT_APPLICABLE_STRING;
        }
        else {
           $publishingRecordArray[$i]['publishOn'] = UtilityManager::formatDate($publishingRecordArray[$i]['publishOn']);
        }
        if($publishingRecordArray[$i]['scopeId']==0 || $publishingRecordArray[$i]['scopeId']=="") {
          $publishingRecordArray[$i]['scopeId'] = NOT_APPLICABLE_STRING;
        }
        else {
          $publishingRecordArray[$i]['scopeId'] = $publisherScopeArr[$publishingRecordArray[$i]['scopeId']];      
        }
        $valueArray[$i] = array_merge(array('srNo' => ($records+$i+1) ),$publishingRecordArray[$i]);
        $reportHead  = "Employee Name&nbsp;:&nbsp;".$publishingRecordArray[$i]['employeeName'];
        $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$publishingRecordArray[$i]['employeeCode'];
    }  
    $reportHead .= "<br>SearchBy&nbsp;:&nbsp;".$REQUEST_DATA['searchbox'];
 
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Publishing Report ');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead                        =    array();
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']             =    array('#',                'width="2%"  align="left"', 'align="left" valign="top"');
    $reportTableHead['type']                =    array('Type',          'width=15% align="left" ','valign="top" align="left" ');
    $reportTableHead['scopeId']             =    array('Scope',         'width=15% align="left" ','valign="top" align="left" ');
    $reportTableHead['publishOn']           =    array('Publish On',    'width="10%" align="center" ','valign="top" align="center"');
    $reportTableHead['publishedBy']         =    array('Published By',  'width="15%" align="left" ','valign="top" align="left"');
    $reportTableHead['description']         =    array('Description',   'width="40%" align="left" ','valign="top" align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: publisherReportPrint.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 9/01/09    Time: 5:06p
//Updated in $/LeapCC/Templates/EmployeeReports
//search condition updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 9/01/09    Time: 12:56p
//Updated in $/LeapCC/Templates/EmployeeReports
//scopeId checks updated & file format correct (workshopList)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/19/09    Time: 6:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//formating & validation updated
//1132, 1130, 54, 1045, 1044, 500, 1042, 1043 issue resolve
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/EmployeeReports
//record limits remove,format & new enhancements added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Templates/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/24/09    Time: 6:01p
//Updated in $/LeapCC/Templates/EmployeeReports
//report heading updated (employeeName, employeeCode Added)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Templates/EmployeeReports
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/17/09    Time: 3:37p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:20p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//initial checkin 
//

?>