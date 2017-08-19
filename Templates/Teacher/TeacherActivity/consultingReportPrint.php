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
    define('MODULE','EmployeeInformation');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);  
    UtilityManager::headerNoCache();

     require_once(BL_PATH . '/ReportManager.inc.php');
     $reportManager = ReportManager::getInstance();
     
     require_once(MODEL_PATH . "/ConsultingManager.inc.php");
     $consultManager = ConsultingManager::getInstance();
    /////////////////////////

   $condition="";
        
   $employeeId=$sessionHandler->getSessionVariable('EmployeeId');    

  
      /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (c.projectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.sponsorName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.remarks LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'projectName';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND c.employeeId = '.add_slashes($employeeId);
     
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
    $reportTableHead['srNo']             =    array('#',                'width="2%"  align="left"', 'align="left" valign="top"');
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
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/17/09    Time: 4:03p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved issue 440
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//record limits remove,format & new enhancements added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//report Heading updated (employeeName, employeeCode Show)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 3:23p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//


?>