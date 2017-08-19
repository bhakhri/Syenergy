<?php 
//This file is used as printing version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineCategoryManager = FineManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();  
	 
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

	/// Search filter /////  
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
      
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    $orderBy = " ORDER by $sortField $sortOrderBy";	

    $studentFineList  = $fineCategoryManager->studentFineList($condition,$limit,$orderBy);
    $cnt = count($studentFineList);
   
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $studentFineList[$i]['receiptDate'] = UtilityManager::formatDate($studentFineList[$i]['receiptDate']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$studentFineList[$i]);
    }
    
    $reportManager->setReportWidth(665);
    $reportManager->setReportHeading('Fine Payment History Report');
//    $reportManager->setReportInformation("Search By: $searchCrieria");
    
    $reportTableHead                           =   array();
    $reportTableHead['srNo']                   =   array('#','width="2%"', "align='center' ");
   $reportTableHead['receiptDate']        =   array('Receipt Date','width="10%" align="center" ', 'align="center"');   
        $reportTableHead['fineReceiptNo']      =   array('Receipt No.','width=10% align="left"', 'align="left"');        
        $reportTableHead['fullName']           =   array('Name','width="12%" align="left" ', 'align="left"');
	$reportTableHead['rollNo']             =   array('Roll No.','width=12% align="left"', 'align="left"');
        $reportTableHead['className']        =   array('Fine Class','width="15%" align="center" ', 'align="center"');
        $reportTableHead['paidAt']        =   array('Paid At','width="10%" align="right" ', 'align="right"');
        $reportTableHead['employeeCodeName']        =   array('Collected By','width="10%" align="left" ', 'align="left"');
        $reportTableHead['receiveCash']         =   array('Cash','width="10%" align="left" ', 'align="left"');    
        $reportTableHead['receiveDD']             =   array('DD','width=10% align="left"', 'align="left"');
        $reportTableHead['totalAmount']           =   array('Total Receipt','width="14%" align="left" ', 'align="left"');
   

    
    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>
