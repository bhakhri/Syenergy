<?php 
//  This File calls addFunction used in adding FeeHead Records
// Author :Nishu Bindal
// Created on : 2-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Fee/FeeCollectionReportManager.inc.php");
$FeeCollectionReportManager = FeeCollectionReportManager::getInstance();
define('MODULE','FeeCollectionReport');     
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);           
UtilityManager::headerNoCache();


global $sessionHandler;
$queryDescription ='';
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
$userId = $sessionHandler->getSessionVariable('UserId');
    $errorMessage ='';

    
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
      $condition .= " AND frd.feeType IN(1,4)";
    }else if($paidFee=='2') {
      $condition .= " AND frd.feeType IN(2,4)";
    }else if($paidFee=='3') {
      $condition .= " AND frd.feeType IN(3,4) ";
    }
    
    
    if($fromDate!='' && $toDate!='') {
      $whereCondition = " WHERE (DATE_FORMAT(a.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
    }
    
 	
	// to limit records per page    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	if(trim($errorMessage) == ''){
		$totalFeeDataArray = $FeeCollectionReportManager->getFeeDetailsCountNew($condition,$whereCondition);
		$feeDataArray = $FeeCollectionReportManager->getFeeDetailsNew($condition,$whereCondition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
		$cnt1 = count($totalFeeDataArray);
		$cnt = count($feeDataArray);
		for($i=0;$i<$cnt;$i++) {
			$totalReceipt = 0;
			$totalReceipt = ($feeDataArray[$i]['DDAmount'] + $feeDataArray[$i]['checkAmount'] + $feeDataArray[$i]['cashAmount']);
			$feeDataArray[$i]['DDAmount'] = number_format($feeDataArray[$i]['DDAmount'], 2, '.', '');
			$feeDataArray[$i]['checkAmount'] = number_format($feeDataArray[$i]['checkAmount'], 2, '.', '');
			$feeDataArray[$i]['cashAmount'] = number_format($feeDataArray[$i]['cashAmount'], 2, '.', '');
			$totalReceipt = number_format($totalReceipt, 2, '.', '');
			$valueArray = array_merge(array('srNo'=>($records+$i+1),'totalReceipt'=>$totalReceipt),$feeDataArray[$i]);
			
			if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
			}
			else {
			$json_val .= ','.json_encode($valueArray);           
			}
		}
		echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
	}
	else{
		echo $errorMessage;
	}
	 
	 
	 
	
 
?>
