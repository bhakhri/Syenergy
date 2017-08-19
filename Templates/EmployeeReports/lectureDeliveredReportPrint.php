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
   define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");     
    $employeeReportsManager = EmployeeReportsManager::getInstance(); 

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();


    
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy"; 
    
    $lectureGroupType = add_slashes($REQUEST_DATA['lectureGroupType']);  
    $lectureGroupTypeArr = explode(',',$lectureGroupType);
    
    $lectureGroupTypeName = add_slashes($REQUEST_DATA['lectureGroupTypeName']);  
    $lectureGroupTypeNameArr = explode(',',$lectureGroupTypeName);
    
 
    
    $employeeId=add_slashes($REQUEST_DATA['employeeId']);    
    
    $filter1 = '';
    $filter2 = '';
    
    $cnt = count($lectureGroupTypeArr);
    
    for($i=0; $i<$cnt; $i++) {
      $filter1 .= " IF(grp.groupTypeId=".trim($lectureGroupTypeArr[$i]).",MAX(att.lectureDelivered),0) AS ss".trim($lectureGroupTypeArr[$i]).", ";        
      $filter2 .= " SUM(t.ss".trim($lectureGroupTypeArr[$i]).") AS s".trim($lectureGroupTypeArr[$i]).", ";
    }
    

    $condition = " AND ttc.timeTableLabelId = ".add_slashes($REQUEST_DATA['timeTableLabelId'])." AND att.employeeId=".add_slashes($employeeId);  
    
    $cnt = 0; 
    if(add_slashes($REQUEST_DATA['timeTableLabelId'])!='' && add_slashes($employeeId) != '') {  
      $recordArray = $employeeReportsManager->getLectureDelivered($condition,$orderBy,'',$filter1,$filter2);  
      $cnt = count($recordArray);
    }
    
         
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];

    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$recordArray[$i]);
    }  
 
    $reportManager->setReportWidth(750);
    $reportManager->setReportHeading('Lecture Details Report');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead = array();
    
    
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']            =   array('#',                'width="2%"  align="left"', 'align="left" valign="top"');
    $reportTableHead['subjectName']     =    array('Subject Name', 'width=20%   align="left" ','align="left" valign="top" ');
    $reportTableHead['subjectCode']     =    array('Subject Code', 'width="10%" align="left" ','align="left" valign="top" ');
    
    $cnt = count($lectureGroupTypeArr);   
    for($i=0; $i<$cnt; $i++) { 
       $id = trim("s".trim($lectureGroupTypeArr[$i]));
       $reportTableHead[$id]    =    array($lectureGroupTypeNameArr[$i],     'width="8%" align="right" ','align="right" valign="top" ');    
    }
    $reportTableHead['total']           =    array('Total',        'width="8%" align="right" ','align="right" valign="top" ');
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: lectureDeliveredReportPrint.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/04/09   Time: 12:44p
//Updated in $/LeapCC/Templates/EmployeeReports
//lectureDetails function timeTableLabelId checks updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/23/09   Time: 5:47p
//Updated in $/LeapCC/Templates/EmployeeReports
//report format update lecture report (groupTypeId base checks added)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/23/09   Time: 5:42p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/23/09   Time: 4:37p
//Updated in $/LeapCC/Templates/EmployeeReports
//subjectTypeId condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/23/09   Time: 3:56p
//Updated in $/LeapCC/Templates/EmployeeReports
//lectureDelivered Report Format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/23/09   Time: 3:46p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/23/09   Time: 3:33p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//report formating updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/01/09   Time: 10:47a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//check attendance, marks condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/01/09   Time: 10:29a
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//file added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/EmployeeReports
//record limits remove,format & new enhancements added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Templates/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
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