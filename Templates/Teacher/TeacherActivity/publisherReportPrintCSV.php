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
    $publishingManager = EmployeeManager::getInstance();

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
    
    global $publisherScopeArr;     
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');                    
    
     
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

    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');                    
    $condition .= ' AND p.employeeId = '.add_slashes($employeeId);
     
    ////////////
    $publishingRecordArray = $publishingManager->getPublishingList($condition,$orderBy);
    $cnt = count($publishingRecordArray);
    
    $csvData  = "Employee Name:,".parseCSVComments($REQUEST_DATA['employeeName']).",Employee Code:,".parseCSVComments($REQUEST_DATA['employeeCode']);
    $csvData .= "\n SearchBy:,".parseCSVComments($REQUEST_DATA['searchbox'])."\n";
    $csvData .= "Sr. No., Type, Scope, Publish On, Published By, Description \n";    
    
    global $publisherScopeArr; 
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
        $csvData .= ($i+1).','.parseCSVComments($publishingRecordArray[$i]['type']).','.parseCSVComments($publishingRecordArray[$i]['scopeId']).','.parseCSVComments($publishingRecordArray[$i]['publishOn']).','.parseCSVComments($publishingRecordArray[$i]['publishedBy']).','.parseCSVComments($publishingRecordArray[$i]['description']);
        $csvData .= "\n";
    }  
   
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="EmployeePublisherReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: publisherReportPrintCSV.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 9/01/09    Time: 5:06p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//search condition updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 9/01/09    Time: 12:56p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//scopeId checks updated & file format correct (workshopList)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/21/09    Time: 12:18p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//function added stristr
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/20/09    Time: 1:27p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/06/09    Time: 12:57p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//correct name publish on instead of publisher date
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