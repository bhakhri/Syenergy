 <?php 
//--------------------------------------------------------
// This file is used as printing version for Pending Fee Report.
// Created on : 7-May-2012
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
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();  

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
	
	$recordCount = count($feeDataArray);
	
	if($recordCount >0 && is_array($feeDataArray)){
		for($i=0; $i<$recordCount; $i++ ) {
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
			$unPaidFees = number_format($unPaidFees, 2, '.', '');
			$valueArray[] = array_merge(array('srNo'=>($records+$i+1),
							'unPaidFees'=>$unPaidFees,
							'academicTotalFees'=>$academicTotalFees,
							'academicLedgerDebit'=>$academicLedgerDebit,
							'academicLedgerCredit'=>$academicLedgerCredit,
							'hostelTotalFees'=>$hostelTotalFees,
							'hostelLedgerDebit'=>$hostelLedgerDebit,
							'hostelLedgerCredit'=>$hostelLedgerCredit,
							'transportTotalFees'=>$transportTotalFees,
							'transportLedgerDebit'=>$transportLedgerDebit,
							'transportLedgerCredit'=>$transportLedgerCredit,
							'remarks'=> ''),$feeDataArray[$i]);
			
			
		}
	}

    $dt = UtilityManager::formatDate(date('Y-m-d'));               
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Pending Fee Report');
    //$reportManager->setReportInformation("class : $className");
    $reportManager->setReportInformation("As on $dt");


                   
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']		=    	array('#','width="2%" align="left"', "align='left'");
    $reportTableHead['studentName']	=    	array('Student Name','width="6%" align="left" ','align="left"');
    $reportTableHead['rollNo']		=    	array('Roll No.','width="5%" align="left" ','align="left"');
    $reportTableHead['className']		=    	array('Class',' width="12%" align="left" ','align="left"');   
     $reportTableHead['academicTotalFees']	=    	array('Academic',' width="7%" align="right" ','align="right"');
     $reportTableHead['academicLedgerDebit']	=    	array('Academic Debit',' width="5%" align="right" ','align="right"');
	  $reportTableHead['academicLedgerCredit']	=    	array('Academic Credit',' width="5%" align="right" ','align="right"');
   $reportTableHead['hostelTotalFees']	=    	array('Hostel',' width="7%" align="right" ','align="right"');
    $reportTableHead['hostelLedgerDebit']	=    	array('Hostel Debit',' width="5%" align="right" ','align="right"');
     $reportTableHead['hostelLedgerCredit']	=    	array('Hostel  Credit',' width="5%" align="right" ','align="right"');
    $reportTableHead['transportTotalFees']	=    	array('Transport',' width="7%" align="right" ','align="right"');
     $reportTableHead['transportLedgerDebit']	=    	array('Transport Debit',' width="5%" align="right" ','align="right"');
      $reportTableHead['transportLedgerCredit']	=    	array('Transport Credit',' width="5%" align="right" ','align="right"');
     $reportTableHead['concession']	=    	array('Concession',' width="7%" align="right" ','align="right"');
    $reportTableHead['totalFees']	=    	array('Total Fees',' width="7%" align="right" ','align="right"');
    $reportTableHead['paidAmount']	=    	array('Paid',' width="7%" align="right" ','align="right"');
    $reportTableHead['unPaidFees']	=    	array('Unpaid',' width="7%" align="right" ','align="right"');
        
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
