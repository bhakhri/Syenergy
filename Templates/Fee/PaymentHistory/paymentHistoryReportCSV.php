<?php 
//This file is used as CSV version for payment status for subject centric.
// Author :Nishu Bindal
// Created on : 11-05-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

     require_once(MODEL_PATH . "/Fee/PaymentHistoryReportManager.inc.php");   
    $PaymentHistoryReportManager = PaymentHistoryReportManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
       //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
       } 
       else {
         return $comments.chr(160); 
       }
    }   
    
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
    }
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $csvData .= "As On, ".parseCSVComments($formattedDate);
    $csvData .= "\n";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    
    if($sortField=='undefined') {
      $sortField='receiptDate';  
    }
    
    if($sortOrderBy=='undefined') {
      $sortOrderBy='ASC';  
    }
    
    $sortField1 = $sortField;
    if($sortField=='receiptDate') {
      $sortField1 = 'fr.receiptDate';  
    }
    else if($sortField=='receiptNo') {
      $sortField1 = 'LENGTH(fr.receiptNo)+0,fr.receiptNo';  
    }
    $orderBy = "$sortField1 $sortOrderBy";    
    
    
    $studentRecordArray = $PaymentHistoryReportManager->getPaymentHistoryDetailsNew($condition,$limit,$sortOrderBy,$sortField);
    $cnt = count($studentRecordArray);
 
    $csvData .= "#,Receipt Date,Receipt No.,Name,Roll No.,Fee Class,Fee Cycle,Installment,Pay Fee Of,Cash(Rs.),DD(Rs.),DD Detail,Total Receipt (Rs.),Collected By";
    $csvData .= "\n";
    
	 $cashTotal = 0;
	 $ddTotal = 0;
	 $receiptTotal = 0;
    for($i=0;$i<$cnt;$i++) { 
          $cashTotal += $studentRecordArray[$i]['receiveCash'];
	    $ddTotal += $studentRecordArray[$i]['receiveDD'];
	    $receiptTotal += $studentRecordArray[$i]['amount'];
	    
        if($studentRecordArray[$i]['receiveDD']=='0.00') {
          $studentRecordArray[$i]['receiveDD']='';  
        }
        
        if($studentRecordArray[$i]['receiveCash']=='0.00') {
          $studentRecordArray[$i]['receiveCash']='';  
        }
        
        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);  
        $studentRecordArray[$i]['installmentNo'] = "Installment-".$studentRecordArray[$i]['installmentNo'];
        $csvData .= ($i+1).",".parseCSVComments($studentRecordArray[$i]['receiptDate']).",".parseCSVComments($studentRecordArray[$i]['receiptNo']); 
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['studentName']).",".parseCSVComments($studentRecordArray[$i]['rollNo']);
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['className']).",".parseCSVComments($studentRecordArray[$i]['cycleName']); 
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['installmentNo']).",".parseCSVComments($studentRecordArray[$i]['feeTypeOf']).",".$studentRecordArray[$i]['receiveCash']; 
        $csvData .= ",".$studentRecordArray[$i]['receiveDD']; 
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['ddDetail']).",".$studentRecordArray[$i]['amount']; 
	$csvData .= ",".parseCSVComments($studentRecordArray[$i]['employeeCodeName']);
        $csvData .= "\n";
    }
    $csvData .= ",";
		 $csvData .= ",";
		  $csvData .= ",";
		   $csvData .= ",";
		    $csvData .= ",";
			$csvData .= ",";
		   $csvData .= ",";
		    $csvData .= ",";
		$csvData .= "Total,";
		$csvData .= number_format($cashTotal , 2, '.', '').",";
		$csvData .= number_format($ddTotal , 2, '.', '').",";
		 $csvData .= ",";
		$csvData .= number_format($receiptTotal , 2, '.', '').",";
    if($i==0) {
      $csvData .= ",,,No Data Found \n";   
    }
    
    UtilityManager::makeCSV($csvData,'FeeReceiptReport.csv');
    die;   
?>
