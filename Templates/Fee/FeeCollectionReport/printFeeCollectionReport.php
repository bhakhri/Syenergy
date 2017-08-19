 <?php 
//--------------------------------------------------------
//This file is used as printing version for Fee Collection Report
// Author :Nishu Bindal
// Created on : 7-May-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
  
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

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
    
	
	//$feeDataArray  = $FeeCollectionReportManager->getFeeDetailsPrint($classId,$fromDate,$toDate,$orderBy);
	$feeDataArray = $FeeCollectionReportManager->getFeeDetailsNew($condition,$whereCondition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
	$recordCount = count($feeDataArray);
	if($recordCount >0 && is_array($feeDataArray)){
		for($i=0; $i<$recordCount; $i++ ) {
			$totalReceipt = 0;
			$totalReceipt = ($feeDataArray[$i]['DDAmount'] + $feeDataArray[$i]['checkAmount'] + $feeDataArray[$i]['cashAmount']);
			$feeDataArray[$i]['DDAmount'] = number_format($feeDataArray[$i]['DDAmount'], 2, '.', '');
			$feeDataArray[$i]['checkAmount'] = number_format($feeDataArray[$i]['checkAmount'], 2, '.', '');
			$feeDataArray[$i]['cashAmount'] = number_format($feeDataArray[$i]['cashAmount'], 2, '.', '');
			$totalReceipt = number_format($totalReceipt, 2, '.', '');
			$valueArray[] = array_merge(array('srNo'=>($i+1),'totalReceipt'=>$totalReceipt),$feeDataArray[$i]);
		}
	}
    

    $dt = UtilityManager::formatDate(date('Y-m-d'));
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Collection Report');
    //$reportManager->setReportInformation("class : $className (From: $fromDate To : $toDate)");
    $reportManager->setReportInformation("As on $dt");
	

                   
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']		=    	array('#','width="2%" align="left"', "align='left'");
    $reportTableHead['receiptNo']	=    	array('Rcpt No.',' width=7% align="left" ','align="left" ');
    $reportTableHead['studentName']	=    	array('Student Name',' width="11%" align="left" ','align="left"');
    $reportTableHead['rollNo']		=    	array('Roll No.',' width="10%" align="left" ','align="left"');
    $reportTableHead['regNo']		=    	array('Reg No.',' width="10%" align="left" ','align="left"');
    $reportTableHead['branchName']	=    	array('Branch Name',' width="9%" align="left" ','align="left"');
    $reportTableHead['periodName']	=    	array('Semester',' width="7%" align="left" ','align="left"');
     $reportTableHead['feeTypeOf']	=    	array('Pay Fee Of',' width="11%" align="left" ','align="left"');
    $reportTableHead['cashAmount']	=    	array('Cash Amt.',' width="9%" align="right" ','align="right"');
    $reportTableHead['DDAmount']	=    	array('DD Amt.',' width="8%" align="right" ','align="right"');
    $reportTableHead['checkAmount']	=    	array('cheque Amt.',' width="10%" align="right" ','align="right"');
    $reportTableHead['totalReceipt']	=    	array('Total Rcpt',' width="10%" align="right" ','align="right"');
        
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
