<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
//
// Author : Saurabh Thukral
// Created on : (13.09.2012 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineCancelledReportManager.inc.php");
    $fineCancelledReportManager = FineCancelledReportManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlManager = HtmlFunctions::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    $instituteId  = trim($REQUEST_DATA['instituteId']); 
    $degreeId  = trim($REQUEST_DATA['degreeId']); 
    $branchId  = trim($REQUEST_DATA['branchId']); 
    $batchId  = trim($REQUEST_DATA['batchId']); 
    $classId  = trim($REQUEST_DATA['classId']); 
    $fromDate  = trim($REQUEST_DATA['fromDate']); 
    $toDate  = trim($REQUEST_DATA['toDate']); 
    $receiptNo  = htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo']))); 
    $rollNo  = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo']))); 
    $studentName  = htmlentities(add_slashes(trim($REQUEST_DATA['studentName']))); 
    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName']))); 
    
    $condition = "";
    $whereCondition='';
    
     if($instituteId!='') {
      $condition .= " AND c.instituteId = '$instituteId' ";
    }
    if($degreeId!='') {
      $condition .= " AND c.degreeId = '$degreeId' ";
    }
    if($branchId!='') {
      $condition .= " AND c.branchId = '$branchId' ";
    }
    if($batchId!='') {
      $condition .= " AND c.batchId = '$batchId' ";
    }
    if($classId!='') {
      $condition .= " AND c.classId = '$classId' ";
    }
    if($rollNo!='') {
      $condition .= " AND (stu.rollNo LIKE '$rollNo%' OR stu.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND stu.fatherName LIKE '$fatherName%' ";
    }
    if($receiptNo!='') {
      $condition .= " AND frd.fineReceiptNo LIKE '$receiptNo%' ";
    }
    
    if($fromDate!='' && $toDate!='') {
      $condition .= " AND (frd.receiptDate BETWEEN '$fromDate' and '$toDate') ";
    }

    $startingRecord  = htmlentities(add_slashes(trim($REQUEST_DATA['startingRecord']))); 
    $totalRecords = htmlentities(add_slashes(trim($REQUEST_DATA['totalRecords']))); 
    
    if($startingRecord=='') {
      $startingRecord = 0; 
    }
    if($startingRecord>0) {
      $startingRecord=$startingRecord-1;  
    }
    else {
      $startingRecord=0;  
    }
    if($totalRecords=='') {
       $totalRecords = 500; 
    }
    $limit  = ' LIMIT '.$startingRecord.','.$totalRecords;

	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    $orderBy = " ORDER by $sortField $sortOrderBy";	
   
    $studentFineList  = $fineCancelledReportManager->studentFineList($condition,$limit,$orderBy);
    $cnt = count($studentFineList);

    for($i=0;$i<$cnt;$i++) {   		
   		
		$studentFineList[$i]['receiptDate'] = UtilityManager::formatDate($studentFineList[$i]['receiptDate']);
   	 	$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$studentFineList[$i]);
 	}
    
    $reportManager->setReportWidth('665');
    $reportManager->setReportHeading('Fine Payment History Report');
    $reportManager->setReportInformation("Search By: $searchCrieria");
    
    $reportTableHead                           =   array();
    $reportTableHead['srNo']                   =   array('#','width="2%"', "align='center' ");
   $reportTableHead['receiptDate']        =   array('Receipt Date','width="10%" align="center" ', 'align="center"');   
        $reportTableHead['fineReceiptNo']      =   array('Receipt No.','width=10% align="left"', 'align="left"');        
        $reportTableHead['fullName']           =   array('Name','width="12%" align="left" ', 'align="left"');
	$reportTableHead['rollNo']             =   array('Roll No.','width=12% align="left"', 'align="left"');
        $reportTableHead['className']        =   array('Fine Class','width="15%" align="center" ', 'align="center"');
        $reportTableHead['paidAt']        =   array('Paid At','width="10%" align="right" ', 'align="right"');        
        $reportTableHead['receiveCash']         =   array('Cash','width="10%" align="left" ', 'align="left"');    
        $reportTableHead['receiveDD']             =   array('DD','width=10% align="left"', 'align="left"');
        $reportTableHead['totalAmount']           =   array('Total Receipt','width="14%" align="left" ', 'align="left"');
	$reportTableHead['reasonDelete']        =   array('Cancellation Reason','width="10%" align="left" ', 'align="left"');
	$reportTableHead['employeeCodeName']        =   array('Deleted By','width="10%" align="left" ', 'align="left"');
   

    
    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();


// for VSS
// $History: finetHistoryPrint.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 12/15/09   Time: 4:38p
//Updated in $/LeapCC/Templates/Fine
//resolved issues0002238, 0002239, 0002241, 0002242, 0002243, 0002240,
//0002271
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 11/12/09   Time: 16:57
//Updated in $/LeapCC/Templates/Fine
//Modified Column names and added option of navigating to fine collect
//page from this module
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/10/09   Time: 6:07p
//Updated in $/LeapCC/Templates/Fine
//resolved issues 0002236, 0002235
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/09/09   Time: 5:48p
//Updated in $/LeapCC/Templates/Fine
//resolved issue 0002213,0002234
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 3:32p
//Updated in $/LeapCC/Templates/Fine
//resolved issue 0002216,0002211,0002214,0002215,0002217,0002220,0002221,
//0002222,0002223,0002224,0002225,0002226,0002227,0002218
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Templates/Fine
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/08/09    Time: 1:25p
//Updated in $/LeapCC/Templates/Fine
//Updated with approved by parameter in reports
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/09    Time: 6:45p
//Created in $/LeapCC/Templates/Fine
//intial checkin
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/18/09    Time: 1:51p
//Updated in $/LeapCC/Templates/Student
//Updated report formatting so that "outstanding" field stand Out
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Templates/Student
//updated as per CC functionality
?>
