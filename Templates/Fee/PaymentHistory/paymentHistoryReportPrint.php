<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
// Author : Nishu Bindal
// Created on : (08.April.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeePaymentHistory');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
 
    require_once(MODEL_PATH . "/Fee/PaymentHistoryReportManager.inc.php");   
    $PaymentHistoryReportManager = PaymentHistoryReportManager::getInstance();
     
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();   
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////  
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
                        
    //$filter .= ' AND (paymentInstrument !=1)';   
    //$totalArray = $studentManager->getFeesHistoryListNew($filter);
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
    
    
    // Findout Student Fee Receipt (New Format)
    //$filter .= " AND fr.studentId = 1 ";
    $totalArray = $PaymentHistoryReportManager->getPaymentHistoryCount($filter);
     $studentRecordArray = $PaymentHistoryReportManager->getPaymentHistoryDetailsNew($condition,$limit,$sortOrderBy,$sortField);
    $cnt = count($studentRecordArray);
    
	 $cashTotal = 0;
	 $ddTotal = 0;
	 $receiptTotal = 0;
    for($i=0;$i<$cnt;$i++) { 
        $feeReceiptId = $studentRecordArray[$i]['feeReceiptId'];
        $conessionFormatId  = $studentRecordArray[0]['isConessionFormat']; 
        
        $chkInstallment = $studentRecordArray[$i]['installmentCount'];
         
        $studentRecordArray[$i]['installmentNo'] = "Installment-".$studentRecordArray[$i]['installmentNo'];
  
        $printAction = "<a href='javascript:void(0)' onClick='printDetailReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\")' title='Print'><img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>&nbsp;|&nbsp;<a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";

        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);  
        
		  $cashTotal += $studentRecordArray[$i]['receiveCash'];
	    $ddTotal += $studentRecordArray[$i]['receiveDD'];
	    $receiptTotal += $studentRecordArray[$i]['amount'];
	    
        if($studentRecordArray[$i]['receiveDD']=='0.00') {
          $studentRecordArray[$i]['receiveDD']='';  
        }
        
        if($studentRecordArray[$i]['receiveCash']=='0.00') {
          $studentRecordArray[$i]['receiveCash']='';  
        }
        
        $valueArray[] = array_merge(array('printAction'=> $printAction,
                                        'srNos' => ($records+$i+1) ),
                                        $studentRecordArray[$i]);
}
	$valueArray[] = array_merge(array('srNos'=>'',
									'receiptDate'=>'',
									'receiptNo'=>'',
									'studentName'=>'',
									'rollNo'=>'',
									'className'=>'',
									'cycleName'=>'',
									'installmentNo'=>'',																											
									'feeTypeOf'=>"<b>Total</b>",
									'receiveCash'=> "<b>".number_format($cashTotal,2,'.','')."</b>",
									'receiveDD'=>   "<b>".number_format($ddTotal,2,'.','')."</b>",
									 'ddDetail'=>'',
									'amount'=>  "<b>".number_format($receiptTotal,2,'.','')."</b>",                                       
									'employeeCodeName'=>''
                                        )); 
	$formattedDate = date('d-M-y');
        $reportManager->setReportWidth(800);
	$reportManager->setReportHeading("Payment History Report");
    $reportManager->setReportInformation("As on $formattedDate");
	 
	$reportTableHead = array();
	//associated key col.label, col.width, data align	
	$reportTableHead['srNos'] = array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['receiptDate'] = array('Receipt Date','width=10% align="left"', 'align="left"');
	$reportTableHead['receiptNo'] = array('Receipt No.','width=10% align="center"', 'align="center"');
	$reportTableHead['studentName'] = array('Name','width="10%" align="center" ', 'align="center"');
    $reportTableHead['rollNo'] = array('Roll No.','width="10%" align="center" ', 'align="center"');
    $reportTableHead['className'] = array('Fee Class','width=10% align="center"', 'align="center"');
    $reportTableHead['cycleName'] = array('Fee Cycle','width=10% align="center"', 'align="center"'); 
    $reportTableHead['installmentNo'] = array('Installment','width=10% align="center"', 'align="center"'); 
      $reportTableHead['feeTypeOf'] = array('Pay Fee Of','width=10% align="center"', 'align="center"'); 
    $reportTableHead['receiveCash'] = array('Cash(Rs.)','width=10% align="center"', 'align="center"');
    $reportTableHead['receiveDD'] = array('DD(Rs.)','width=10% align="center"', 'align="center"'); 
    $reportTableHead['ddDetail'] = array('DD Detail','width=10% align="center"', 'align="center"'); 
    $reportTableHead['amount'] = array('Total Receipt','width=8% align="center"', 'align="center"');
    $reportTableHead['employeeCodeName'] = array('Collected By','width=10% align="center"', 'align="center"');


    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
         
?>
