<?php 
//This file is used as CSV format for General Survery FeedBack 
//
// Author :Rajeev Aggarwal
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(BL_PATH . '/ReportManager.inc.php');
     $reportManager = ReportManager::getInstance();
     
     require_once(MODEL_PATH . "/EmployeeManager.inc.php");
     $consultManager = EmployeeManager::getInstance();
    /////////////////////////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (c.projectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.sponsorName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.remarks LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'projectName';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND c.employeeId = '.add_slashes($REQUEST_DATA['employeeId']);
     
    ////////////
    $consultRecordArray = $consultManager->getConsultingList($condition,$orderBy);
    $cnt = count($consultRecordArray);

    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];
    for($i=0;$i<$cnt;$i++) {
        
        if($consultRecordArray[$i]['startDate']=='0000-00-00') {
           $consultRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $consultRecordArray[$i]['startDate'] = UtilityManager::formatDate($consultRecordArray[$i]['startDate']);
        }
        
        if($consultRecordArray[$i]['endDate']=='0000-00-00') {
           $consultRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $consultRecordArray[$i]['endDate'] = UtilityManager::formatDate($consultRecordArray[$i]['endDate']);
        }
        
        if( $consultRecordArray[$i]['amountFunding']=='') {
           $consultRecordArray[$i]['amountFunding'] = 0;              
        }
              
        
        $valueArray[$i] = array_merge(array('srNo' => ($records+$i+1) ),$consultRecordArray[$i]);
        $reportHead  = "Employee Name&nbsp;:&nbsp;".$consultRecordArray[$i]['employeeName'];
        $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$consultRecordArray[$i]['employeeCode'];
    }  
    $reportHead .= "<br>SearchBy&nbsp;:&nbsp;".$REQUEST_DATA['searchbox'];

 
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Consulting Report');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead = array();
    
    
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']             =    array('#',                'width="2%"  align="center"', 'align="center" valign="top"');
    $reportTableHead['projectName']         =    array('Project Name',   'width=15%   align="left" ','align="left" valign="top"  ');
    $reportTableHead['sponsorName']         =    array('Sponsor',        'width="15%" align="left" ','align="left" valign="top" ');
    $reportTableHead['startDate']           =    array('Start Date',     'width="15%" align="center" ','align="center" valign="top" ');
    $reportTableHead['endDate']             =    array('End Date',       'width="15%" align="center" ','align="center" valign="top" ');
    $reportTableHead['amountFunding']       =    array('Amount Funding', 'width="15%" align="right" ','align="right" valign="top" ');
    $reportTableHead['remarks']             =    array('Remarks',        'width="30%" align="left" ','align="left" valign="top" ');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: consultingReportPrint.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 9/11/09    Time: 3:57p
//Updated in $/LeapCC/Templates/EmployeeReports
//please check in build # CC0088
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