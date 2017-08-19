 <?php 
//This file is used as printing CSV OF Fee collection Report 
// Created on : 7-May-12
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
	require_once(MODEL_PATH . "/Fee/ConsolidateFeeReportManager.inc.php");
	$ConsolidateFeeReportManager = ConsolidateFeeReportManager::getInstance();
	
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
	$paidFee = trim($REQUEST_DATA['paidFee']); //used to print fee-academic wise-1,hostel,wise-2,transport wise-3,all-4.
    
    
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
    if($receiptNo!='') {
      $condition .= " AND frd.receiptNo LIKE '$receiptNo%' ";
    }
    
	  if($paidFee=='1') {
      $condition .= " AND frd.feeType IN(1)";
   		 }else if($paidFee=='2') {
      $condition .= " AND frd.feeType IN(2)";
   		 }else if($paidFee=='3') {
      $condition .= " AND frd.feeType IN(3) ";
   		 }else if($paidFee=='4') {
      $condition .= " AND frd.feeType IN(4) ";
   		 }
    
    if($fromDate!='' && $toDate!='') {
      $condition .= " AND (DATE_FORMAT(frd.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
      $whereCondition = " WHERE (DATE_FORMAT(a.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'instituteId';
    $orderBy = " $sortField $sortOrderBy";
 
  $feeDataArray = $ConsolidateFeeReportManager->getFeeDetailsNew($condition,$whereCondition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);

    $cnt = count($feeDataArray);

  
    $dt = UtilityManager::formatDate(date('Y-m-d'));
    $csvData  ='';
    $csvData .= ",,Consolidated Fee Collection Report\n";
    $csvData .= ",,As on: $dt\n";
    $csvData .="\n";
	
	
    $csvData .="#,Institute Abbr.,Concession,Fine,Cash Amount,DD Amount,cheque Amount,Total Amount";
    $csvData .="\n";

	
    $netTotal1 =0;
    $netTotal2 =0;
    $netTotal3 =0;
    $netTotal4 =0;

    for($i=0;$i<$cnt;$i++) {
    		$total = 0;
		$total = ($feeDataArray[$i]['DDAmount'] + $feeDataArray[$i]['checkAmount'] + $feeDataArray[$i]['cashAmount']);
		$csvData .= ($i+1).",";
		$csvData .= $feeDataArray[$i]['instituteAbbr'].",";
		$csvData .= number_format($feeDataArray[$i]['concession'], 2, '.', '').",";
		$csvData .= number_format($feeDataArray[$i]['fine'], 2, '.', '').",";
		$csvData .= number_format($feeDataArray[$i]['cashAmount'], 2, '.', '').",";
		$csvData .= number_format($feeDataArray[$i]['DDAmount'], 2, '.', '').",";
		$csvData .=  number_format($feeDataArray[$i]['checkAmount'], 2, '.', '').",";
		$csvData .= number_format($total, 2, '.', '').",";
		
		$netTotal1 += $feeDataArray[$i]['cashAmount'];
		$netTotal2 += $feeDataArray[$i]['DDAmount'];
		$netTotal3 += $feeDataArray[$i]['checkAmount'];
		$concession += $feeDataArray[$i]['concession'];
		$fine += $feeDataArray[$i]['fine'];
		$netTotal4 += $total;
		$csvData .= "\n";
  }
		$csvData .= ",";
		$csvData .= "Grand Total,";
		$csvData .= number_format($concession , 2, '.', '').",";
		$csvData .= number_format($fine , 2, '.', '').",";
		$csvData .= number_format($netTotal1 , 2, '.', '').",";
		$csvData .= number_format($netTotal2 , 2, '.', '').",";
		$csvData .= number_format($netTotal3 , 2, '.', '').",";
		$csvData .= number_format($netTotal4 , 2, '.', '').",";

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
header('Content-Disposition: attachment;  filename="'.'consolidatedFeeCollectionReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
