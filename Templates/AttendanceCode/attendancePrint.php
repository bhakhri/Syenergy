<?php 
//This file is used as printing version for TestType.
//
// Author :Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AttendanceCodesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AttendanceCodeManager.inc.php");
    $attendanceCodeManager = AttendanceCodeManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='YES')  {
        $filter = ' WHERE (attendanceCodeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCodePercentage LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR showInLeaveType = 1 )';
      }
      else
      if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='NO')  {
        $filter = ' WHERE (attendanceCodeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCodePercentage LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR showInLeaveType = 0 )';                  
      }
      else {
        $filter = ' WHERE (attendanceCodeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCodePercentage LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';                  
      }        
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'attendanceCodeName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    $attendanceCodeRecordArray = $attendanceCodeManager->getAttendanceCodeList($filter,'',$orderBy);
    
    $cnt = count($attendanceCodeRecordArray);
    for($i=0;$i<$cnt;$i++) {
      // add attendanceCodeId in actionId to populate edit/delete icons in User Interface   
      $valueArray[] = array_merge(array('action' => $attendanceCodeRecordArray[$i]['attendanceCodeId'] , 
                                        'srNo' => ($records+$i+1)), $attendanceCodeRecordArray[$i]);
    }
    
     
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Attendance Code Report');
    $reportManager->setReportInformation("SearchBy: ".$REQUEST_DATA['searchbox']);
	 
	$reportTableHead						         =  array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				         =  array('#','width="3%"', "align='center' ");
    $reportTableHead['attendanceCodeName']           =  array('Attendance Name','width=25% align="left"', 'align="left"');
	$reportTableHead['attendanceCode']		         =  array('Attendance Code','width=15% align="left"', 'align="left"');
    $reportTableHead['attendanceCodePercentage']     =  array('Percentage','width="15%" align="right" ', 'align="right"');
    $reportTableHead['showInLeaveType']              =  array('Show in Leave Type','width=15% align="center"', 'align="center"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: attendancePrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/10/09    Time: 5:28p
//Updated in $/LeapCC/Templates/AttendanceCode
//formating, validation updated
//issue fix 994, 9943, 992, 991, 989, 987, 
//986, 985, 981, 914, 913, 911
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Templates/AttendanceCode
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/AttendanceCode
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 4:31p
//Created in $/LeapCC/Templates/AttendanceCode
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/20/09    Time: 1:55p
//Updated in $/LeapCC/Templates/Subject
//new enhancement categoryId (link subject_category table) new field
//added 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Templates/Subject
//Added "Print" and "Export to excell" in subject and subjectType modules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Templates/TestType
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TestType
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 2:10p
//Created in $/Leap/Source/Templates/TestType
//Added functionality for TestType report print and export to csv
?>