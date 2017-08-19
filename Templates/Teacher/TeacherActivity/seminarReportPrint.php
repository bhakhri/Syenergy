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

    define('MODULE','EmployeeInformation');
    define('ACCESS','view');
    
    UtilityManager::ifTeacherNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $seminarManager = EmployeeManager::getInstance();
    /////////////////////////
    
    global $seminarParticipationArr;           
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');  
    
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
     
       foreach($seminarParticipationArr as $key=>$value) {
          if(stristr($value,$REQUEST_DATA['searchbox'])) {
            $part = " OR participationId LIKE '%$key%' ";  
            break;
          } 
       }
       $condition = ' AND (s.organisedBy LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.seminarPlace LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.fee LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$part.')'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'organisedBy';
    
     $orderBy = " $sortField $sortOrderBy";         
     $condition .= ' AND s.employeeId = '.add_slashes($employeeId);
     
    ////////////
    $seminarRecordArray = $seminarManager->getSeminarsList($condition,$orderBy);
    $cnt = count($seminarRecordArray);
    
         
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];
    
    for($i=0;$i<$cnt;$i++) {
        
        if($seminarRecordArray[$i]['startDate']=='0000-00-00') {
           $seminarRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $seminarRecordArray[$i]['startDate'] = UtilityManager::formatDate($seminarRecordArray[$i]['startDate']);
        }
        
        if($seminarRecordArray[$i]['endDate']=='0000-00-00') {
           $seminarRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
        }
        else {
           $seminarRecordArray[$i]['endDate'] = UtilityManager::formatDate($seminarRecordArray[$i]['endDate']);
        }
       
       $seminarRecordArray[$i]['participationId'] = $seminarParticipationArr[$seminarRecordArray[$i]['participationId']];      
       if($seminarRecordArray[$i]['participationId']=="") {
          $seminarRecordArray[$i]['participationId']=NOT_APPLICABLE_STRING;
       }
        
        if($seminarRecordArray[$i]['fee']==0 || $seminarRecordArray[$i]['fee']=="") {
            $seminarRecordArray[$i]['fee'] = 0;
        } 
        $valueArray[$i] = array_merge(array('srNo' => ($records+$i+1) ),$seminarRecordArray[$i]);
        
        $reportHead  = "Employee Name&nbsp;:&nbsp;".$seminarRecordArray[$i]['employeeName'];
        $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$seminarRecordArray[$i]['employeeCode'];
    } 
    $reportHead .= "<br>SearchBy&nbsp;:&nbsp;".$REQUEST_DATA['searchbox'];
 
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Seminar Report');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead = array();
    
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']             =    array('#',                'width="2%"  align="center"', 'align="center" valign="top"');
    $reportTableHead['organisedBy']         =    array('Organised By',  'width=20%   align="left" ','align="left"  valign="top"  ');
    $reportTableHead['topic']               =    array('Topic',         'width="15%" align="left" ','align="left" valign="top" ');
    $reportTableHead['startDate']           =    array('Start Date',    'width="10%" align="center" ','align="center" valign="top" ');
    $reportTableHead['endDate']             =    array('End Date',      'width="10%" align="center" ','align="center" valign="top" ');
    $reportTableHead['seminarPlace']        =    array('Seminar Place', 'width="15%" align="left" ','align="left" valign="top" ');
    $reportTableHead['participationId']     =    array('Participation', 'width="15%" align="left" ','align="left" valign="top" ');
    $reportTableHead['fee']                 =    array('Fee',           'width="15%" align="right" ','align="right" valign="top" ');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: seminarReportPrint.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/11/09    Time: 5:28p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//search condition updated 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/05/09    Time: 5:31p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//bug fix: (search condition) updated condition format updated 
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