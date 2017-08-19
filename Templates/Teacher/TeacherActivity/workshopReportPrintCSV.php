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

     require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
     $workshopManager = EmployeeManager::getInstance();
    /////////////////////////

     // CSV data field Comments added 
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
    
    $employeeId = $sessionHandler->getSessionVariable('EmployeeId');     
    
   /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (w.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.sponsored LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.sponsoredDetail LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR w.location LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'topic';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND w.employeeId = '.add_slashes($employeeId);
     
    $csvData  = "Employee Name:,".parseCSVComments($REQUEST_DATA['employeeName']).",Employee Code:,".parseCSVComments($REQUEST_DATA['employeeCode']);
    $csvData .= "\n SearchBy:,".parseCSVComments($REQUEST_DATA['searchbox'])."\n";
    $csvData .= "Sr. No., Topic, Start Date, End Date, Sponsored, Audience, Location \n";

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
       $csvData .= ($i+1).','.parseCSVComments($workshopRecordArray[$i]['topic']).','.parseCSVComments($workshopRecordArray[$i]['startDate']).','.parseCSVComments($workshopRecordArray[$i]['endDate']).','.parseCSVComments($workshopRecordArray[$i]['sponsoredDetail']).','.parseCSVComments($workshopRecordArray[$i]['audience']).','.parseCSVComments($workshopRecordArray[$i]['location']);
       $csvData .= "\n";
    }  
   
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="EmployeeWorkshopReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: workshopReportPrintCSV.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//record limits remove,format & new enhancements added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:50p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:49p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 6:01p
//Updated in $/LeapCC/Templates/EmployeeReports
//report heading updated (employeeName, employeeCode Added)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/17/09    Time: 3:37p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/10/09    Time: 5:33p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//condition, formatting & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:20p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//initial checkin 
//

?>