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
    define('MODULE','EmployeeInformation');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ConsultingManager.inc.php");
    $consultManager = ConsultingManager::getInstance();
   
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');  
    
   
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
    

    $csvData  = "Employee Name:,".parseCSVComments($REQUEST_DATA['employeeName']).",Employee Code:,".parseCSVComments($REQUEST_DATA['employeeCode']);
    $csvData .= "\n SearchBy:,".parseCSVComments($REQUEST_DATA['searchbox'])."\n";
    $csvData .= "Sr. No., Project Name, Sponsor, Start Date, End Date, Amount Funding, Remarks \n";
    
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
              
                                                                                                                                                                                                                                                                                    
        $csvData .= ($i+1).','.parseCSVComments($consultRecordArray[$i]['projectName']).','.parseCSVComments($consultRecordArray[$i]['sponsorName']).','.parseCSVComments($consultRecordArray[$i]['startDate']).','.parseCSVComments($consultRecordArray[$i]['endDate']).','.parseCSVComments($consultRecordArray[$i]['amountFunding']).','.parseCSVComments($consultRecordArray[$i]['remarks']);
        $csvData .= "\n";
    }   
   
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="EmployeeConsultingReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: consultingReportPrintCSV.php $
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