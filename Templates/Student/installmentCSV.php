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
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	 
	/// Search filter /////  
	function parseName($value){
		
		$name=explode(' ',$value);
	    $genName="";
		$len= count($name);
		if($len > 0){
			
			for($i=0;$i<$len;$i++){
			
			if(trim($name[$i])!=""){
            
				if($genName!=""){
					
					$genName =$genName ." ".$name[$i];
				}
				else{

					$genName =$name[$i];
				} 
			}
		}
    }
	if($genName!=""){

		$genName=" OR CONCAT(TRIM(stu.firstName),' ',TRIM(stu.lastName)) LIKE '".$genName."%'";
	}  

	return $genName;
	}

	/// Search filter ///// 
	// Degree
 
	if(UtilityManager::notEmpty($REQUEST_DATA['degree'])){

	   $filter .= "	AND stu.classId = ".$REQUEST_DATA['degree'];         
	}

	// Batch
	if(UtilityManager::notEmpty($REQUEST_DATA['batch'])) {
	   $filter .= ' AND (cls.batchId = '.add_slashes($REQUEST_DATA['batch']).')';         
	}

	// Study Period
	if(UtilityManager::notEmpty($REQUEST_DATA['studyperiod'])) {
	   $filter .= ' AND (fr.currentStudyPeriodId = '.add_slashes($REQUEST_DATA['studyperiod']).')';         
	}

	// Student Name
	if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
		$studentName = $REQUEST_DATA['studentName'];
		$parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $filter .= " AND (
						  TRIM(stu.firstName) LIKE '".add_slashes(trim($studentName))."%' 
						  OR 
						  TRIM(stu.lastName) LIKE '".add_slashes(trim($studentName))."%'
						  $parsedName
					 )";
	  // $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
	}

	// Roll No
	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")';         
	}

	// fee cycle
	if(UtilityManager::notEmpty($REQUEST_DATA['feeCycle'])) {
	   $filter .= ' AND (fr.feeCycleId = '.add_slashes($REQUEST_DATA['feeCycle']).')';         
	}

	// from Date
	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	   $filter .= " AND (receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
	}

	// to date
	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	   $filter .= " AND (receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";         
	}

	// instrument status
	if(UtilityManager::notEmpty($REQUEST_DATA['paymentStatus'])) {
	   $filter .= ' AND (instrumentStatus ='.add_slashes($REQUEST_DATA['paymentStatus']).')';         
	}

	// receipt status
	if(UtilityManager::notEmpty($REQUEST_DATA['receiptStatus'])) {
	   $filter .= ' AND (receiptStatus ='.add_slashes($REQUEST_DATA['receiptStatus']).')';         
	}

	// from amount
	if(UtilityManager::notEmpty($REQUEST_DATA['fromAmount'])) {
	   $filter .= ' AND (totalAmountPaid >='.add_slashes($REQUEST_DATA['fromAmount']).')';         
	}

	// to amount
	if(UtilityManager::notEmpty($REQUEST_DATA['toAmount'])) {
	   $filter .= ' AND (totalAmountPaid <='.add_slashes($REQUEST_DATA['toAmount']).')';         
	}
	////////////
	
	// payment type
	if(UtilityManager::notEmpty($REQUEST_DATA['paymentType'])) {
	   $filter .= ' AND (paymentInstrument ='.add_slashes($REQUEST_DATA['paymentType']).')';         
	}
	
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feeReceiptId';
    
    $orderBy = " ORDER by $sortField $sortOrderBy";  

	//$totalArray = $studentManager->getTotalFeesStudent($filter);
    $recordArray = $studentManager->getFeesHistoryList($filter,$limit='',$orderBy);
	
	$cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => $i+1),$recordArray[$i]);
    }

	$csvData = '';
	$csvData .= "Sr,Receipt,Date,Name,Roll No,Fee Cycle,Installment,Payable(Rs),Paid(Rs),Outstanding(Rs),Status \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.$record['receiptNo'].','.$record['receiptDate'].','.$record['fullName'].','.$record['rollNo'].','.$record['cycleName'].','.$record['installmentCount'].','.$record['discountedFeePayable'].','.$record['totalAmountPaid'].','.$record['outstanding'].','.$receiptArr[$record['receiptStatus']];
		$csvData .= "\n";
	}

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="feeInstallment-'.date("d-M-y").'.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;   
 

// for VSS
// $History: installmentCSV.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/23/08   Time: 1:00p
//Created in $/LeapCC/Templates/Student
//Intial Checkin
?>