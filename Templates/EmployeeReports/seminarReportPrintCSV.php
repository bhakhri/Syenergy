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

     require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
     $seminarManager = EmployeeManager::getInstance();
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
    

    global $seminarParticipationArr;
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        foreach($seminarParticipationArr as $key=>$value)
       {
          if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $participationId = " OR participationId LIKE '%$key%' ";
           break;
         }
       }       
       $condition = ' AND (s.organisedBy LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.topic LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.seminarPlace LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR s.fee LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" '.$participationId.')'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'organisedBy';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= ' AND s.employeeId = '.add_slashes($REQUEST_DATA['employeeId']);
     
    ////////////
    $seminarRecordArray = $seminarManager->getSeminarsList($condition,$orderBy);
    $cnt = count($seminarRecordArray);


    
    $csvData  = "Employee Name:,".parseCSVComments($REQUEST_DATA['employeeName']).",Employee Code:,".parseCSVComments($REQUEST_DATA['employeeCode']);
    $csvData .= "\n SearchBy:,".parseCSVComments($REQUEST_DATA['searchbox'])."\n";
    $csvData .= "Sr. No., Organised By, Topic, Start Date, End Date, Seminar Place, Participation, Fee \n";
    
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

        if($seminarRecordArray[$i]['participationId']==0 || $seminarRecordArray[$i]['participationId']=="") {
           $seminarRecordArray[$i]['participationId'] = NOT_APPLICABLE_STRING;
        }
        else {
          $seminarRecordArray[$i]['participationId'] = $seminarParticipationArr[$seminarRecordArray[$i]['participationId']];      
        }
        if($seminarRecordArray[$i]['fee']=="") {
           $seminarRecordArray[$i]['fee'] = 0;
        } 
        
        $csvData .= ($i+1).','.parseCSVComments($seminarRecordArray[$i]['organisedBy']).','.parseCSVComments($seminarRecordArray[$i]['topic']).','.parseCSVComments($seminarRecordArray[$i]['startDate']).','.parseCSVComments($seminarRecordArray[$i]['endDate']).','.parseCSVComments($seminarRecordArray[$i]['seminarPlace']).','.parseCSVComments($seminarRecordArray[$i]['participationId']).','.parseCSVComments($seminarRecordArray[$i]['fee']);
        $csvData .= "\n";
    }  
   
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="EmployeeSeminarReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: seminarReportPrintCSV.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Templates/EmployeeReports
//search & conditions updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/11/09    Time: 5:28p
//Updated in $/LeapCC/Templates/EmployeeReports
//search condition updated 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/05/09    Time: 5:31p
//Updated in $/LeapCC/Templates/EmployeeReports
//bug fix: (search condition) updated condition format updated 
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