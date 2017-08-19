 <?php 
//--------------------------------------------------------
//This file is used as printing CSV OF Fee Pending Report 
// Created on : 7-May-12
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PendingFeeReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Fee/PendingFeeReportManager.inc.php");
	$PendingFeeReportManager = PendingFeeReportManager::getInstance();
    
   $valueArray = array();
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
      $condition .= " AND frm.feeClassId = '$classId' ";
    }
    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND s.fatherName LIKE '$fatherName%' ";
    }

   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = " $sortField $sortOrderBy";
    
	
   $feeDataArray = $PendingFeeReportManager->getPendingFeeDetailsLedgerNew($condition,$classId,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
    $cnt = count($feeDataArray);

  

    
    $dt = UtilityManager::formatDate(date('Y-m-d'));
    $csvData  ='';
    $csvData .= ",,,,Pending Fee Report\n";
    $csvData .= ",,,,As on $dt\n";
    $csvData .="\n";
	        
    $csvData .="#,Student Name,Roll No.,Class,Academic,Academic Debit,Academic Credit,Hostel,Hostel Debit,hostel Credit,Transport,Transport Debit,Transport Credit,Total Fees, Paid,Unpaid";
    $csvData .="\n";


    for($i=0;$i<$cnt;$i++) {
			$academicTotalFees = $feeDataArray[$i]['academicFees'] ;
			$academicLedgerDebit= $feeDataArray[$i]['ledgerAcademicDebit'];
			$academicLedgerCredit= $feeDataArray[$i]['ledgerAcademicCredit'];
			$hostelTotalFees = $feeDataArray[$i]['hostelFees'] ;
			$hostelLedgerDebit= $feeDataArray[$i]['ledgerHostelDebit'];
			$hostelLedgerCredit=$feeDataArray[$i]['ledgerHostelCredit'];
			$transportTotalFees = $feeDataArray[$i]['transportFees'];
			$transportLedgerDebit= $feeDataArray[$i]['ledgerTransportDebit'];
			$transportLedgerCredit= $feeDataArray[$i]['ledgerTransportCredit'];
    		$unPaidFees = 0;
		$remarks = '';
		$unPaidFees = ($feeDataArray[$i]['totalFees'] - $feeDataArray[$i]['paidAmount']);
		$csvData .= ($i+1).",";
		$csvData .= $feeDataArray[$i]['studentName'].",";
		$csvData .= $feeDataArray[$i]['rollNo'].",";
		$csvData .= $feeDataArray[$i]['className'].",";
		
		$csvData .= number_format($academicTotalFees, 2, '.', '').",";
		$csvData .= number_format($academicLedgerDebit, 2, '.', '').",";
		$csvData .= number_format($academicLedgerCredit, 2, '.', '').",";
		$csvData .= number_format($hostelTotalFees, 2, '.', '').",";
		$csvData .= number_format($hostelLedgerDebit, 2, '.', '').",";
		$csvData .= number_format($hostelLedgerCredit, 2, '.', '').",";
		$csvData .= number_format($transportTotalFees, 2, '.', '').",";
		$csvData .= number_format($transportLedgerDebit, 2, '.', '').",";
		$csvData .= number_format($transportLedgerCredit, 2, '.', '').",";
		$csvData .= number_format($feeDataArray[$i]['concession'], 2, '.', '').",";
		$csvData .= number_format($feeDataArray[$i]['totalFees'], 2, '.', '').",";
		$csvData .= number_format($feeDataArray[$i]['paidAmount'], 2, '.', '').",";
		$csvData .=  number_format($unPaidFees, 2, '.', '').",";
		$csvData .= "\n";
  }
    if($cnt == 0){
		$csvData .=" No Data Found ";
	}
	
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'pendingFeeReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
