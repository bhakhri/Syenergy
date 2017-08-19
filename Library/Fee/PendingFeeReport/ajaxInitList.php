<?php 
// This File calls addFunction used in adding FeeHead Records
// Created on : 2-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Fee/PendingFeeReportManager.inc.php");
    $PendingFeeReportManager = PendingFeeReportManager::getInstance();
    define('MODULE','PendingFeeReport');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);           
    UtilityManager::headerNoCache(); 
   
    require_once(MODEL_PATH . "/Fee/PendingFeeReportManager.inc.php");
    $PendingFeeReportManager = PendingFeeReportManager::getInstance();
    
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
    
	
	// to limit records per page    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	if(trim($errorMessage) == ''){
		//$totalFeeDataArray = $PendingFeeReportManager->getPendingFeeCount($feeClassId);
		//$feeDataArray = $PendingFeeReportManager->getPendingFeeDetails($feeClassId,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
    	$totalFeeDataArray = $PendingFeeReportManager->getPendingFeeCountNew($condition,$classId);
    	$feeDataArray = $PendingFeeReportManager->getPendingFeeDetailsLedgerNew($condition,$classId,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
        
		$cnt1 = count($totalFeeDataArray);
		$cnt = count($feeDataArray);
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
		 	
            //$concession =$feeDataArray[$i]['concession'];
			$unPaidFees = 0;
			$remarks = '';
			$unPaidFees = ($feeDataArray[$i]['totalFees'] - $feeDataArray[$i]['paidAmount']);
			$unPaidFees = number_format($unPaidFees, 2, '.', '');
			$valueArray = array_merge(array('srNo'=>($records+$i+1),
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
							                'remarks'=> ''),
                                      $feeDataArray[$i]);
			
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
