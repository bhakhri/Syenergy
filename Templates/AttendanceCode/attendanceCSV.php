<?php 
//This file is used as printing version for TestType.
//
// Author :Parveen sharma
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

    
    //to parse csv values    
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return chr(160).$comments; 
         }
    }
    
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

    $record = $attendanceCodeManager->getAttendanceCodeList($filter,'',$orderBy);
    
    $search = $REQUEST_DATA['searchbox'];
    
    $csvData = '';
    $csvData .= "SearchBy:,".$search.",\n";
    $csvData .= "#,Attendance Name,Attendance Code, Percentage, Show in Leave Type \n";
    $cnt=count($record);
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $csvData .= ($i+1).", ".parseCSVComments($record[$i]['attendanceCodeName']).", ".parseCSVComments($record[$i]['attendanceCode']);
       $csvData .= ", ".parseCSVComments($record[$i]['attendanceCodePercentage']).", ".parseCSVComments($record[$i]['showInLeaveType'])." \n";
    }
    
    if($cnt==0){
        $csvData .=",".NO_DATA_FOUND;
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="attendancecodeList.csv"');

	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: attendanceCSV.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Templates/AttendanceCode
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/08/09    Time: 4:58p
//Updated in $/LeapCC/Templates/AttendanceCode
//condition remove (showInLeavetype  extra)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/08/09    Time: 4:44p
//Updated in $/LeapCC/Templates/AttendanceCode
//search condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 4:31p
//Created in $/LeapCC/Templates/AttendanceCode
//initial checkin
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/27/09    Time: 6:23p
//Updated in $/LeapCC/Templates/Subject
//bug fix (csvData print format bracket updated)
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
?>