 <?php 
//This file is used as printing CSV OF Fee collection Report 
// Created on : 7-May-12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
   require_once(MODEL_PATH . "/Fee/FeeCollectionReportManager.inc.php");
   $FeeCollectionReportManager = FeeCollectionReportManager::getInstance();
	
   $valueArray = array();
    $instituteId  = trim($REQUEST_DATA['instituteId']); 
    $degreeId  = trim($REQUEST_DATA['degreeId']); 
    $branchId  = trim($REQUEST_DATA['branchId']); 
    $batchId  = trim($REQUEST_DATA['batchId']); 
    $classId  = trim($REQUEST_DATA['classId']); 
    $fromDate  = trim($REQUEST_DATA['fromDate']); 
    $toDate  = trim($REQUEST_DATA['toDate']); 
	$paidFee = trim($REQUEST_DATA['paidFee']); //used to print fee-academic wise-1,hostel,wise-2,transport wise-3,all-4.
      
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
    if($receiptNo!='') {
      $condition .= " AND frd.receiptNo LIKE '$receiptNo%' ";
    }
      if($paidFee=='1') {//academic
      $condition .= " AND frd.feeType IN(1,4)";
    }else if($paidFee=='2') {//transport
      $condition .= " AND frd.feeType IN(2,4)";
    }else if($paidFee=='3') {//hostel
      $condition .= " AND frd.feeType IN(3,4) ";
	}
	
    if($fromDate!='' && $toDate!='') {
      $whereCondition = " WHERE (DATE_FORMAT(a.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = " $sortField $sortOrderBy";

	$feeDataArray = $FeeCollectionReportManager->getFeeDetailsNew($condition,$whereCondition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);

    $cnt = count($feeDataArray);

  
    $formattedDate = date('d-M-y');
    $csvData  ='';
    $csvData .= ",,,,,Fee Collection Report\n";
    //$csvData .= ",,,,,,Class Name : $className\n";
    $csvData .= ",,,,,As On $formattedDate";
    $csvData .="\n";
	        
    $csvData .="#,Rcpt No.,Student Name,Roll No.,Reg No.,Branch Name,Semester,Pay Fee Of,Cash Amt.,DD Amt.,cheque Amt.,Total Rcpt";
    $csvData .="\n";

	

    for($i=0;$i<$cnt;$i++) {
    		$totalReceipt = 0;
		$totalReceipt = ($feeDataArray[$i]['DDAmount'] + $feeDataArray[$i]['checkAmount'] + $feeDataArray[$i]['cashAmount']);
		$csvData .= ($i+1).",";
		$csvData .= $feeDataArray[$i]['receiptNo'].",";
		$csvData .= $feeDataArray[$i]['studentName'].",";
		$csvData .= $feeDataArray[$i]['rollNo'].",";
		$csvData .= $feeDataArray[$i]['regNo'].",";
		$csvData .= $feeDataArray[$i]['branchName'].",";
		$csvData .= $feeDataArray[$i]['periodName'].",";
		$csvData .= $feeDataArray[$i]['feeTypeOf'].",";
		$csvData .= number_format($feeDataArray[$i]['cashAmount'], 2, '.', '').",";
		$csvData .= number_format($feeDataArray[$i]['DDAmount'], 2, '.', '').",";
		$csvData .=  number_format($feeDataArray[$i]['checkAmount'], 2, '.', '').",";
		$csvData .= number_format($totalReceipt, 2, '.', '').",";
		
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
header('Content-Disposition: attachment;  filename="'.'listFeeCollectionReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
