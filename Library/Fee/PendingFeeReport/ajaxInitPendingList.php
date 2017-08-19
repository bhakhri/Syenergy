<?php 
// This File calls addFunction used in Fee Paymnt Report
//author: harpreet
// Created on : 2-Feb-2013
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    define('MODULE','FeeDetailHistoryReport');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);           
    UtilityManager::headerNoCache(); 
   
    require_once(MODEL_PATH . "/Fee/FeeDetailHistoryReportManager.inc.php");
    $feeDetailHistoryReportManager = FeeDetailHistoryReportManager::getInstance();
    
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
	 $paidReport=trim($REQUEST_DATA['paidReport']); 
    $feeReport=trim($REQUEST_DATA['feeReport']); 
  
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

	//$totalFeeDataArray = $feeDetailHistoryReportManager->getTotalFee($classIdss);
	$feeTypeCondition='';
	if(trim($errorMessage) == ''){
		
    	if($paidReport==0){//report for all paid fee student list
    	 if($classId!='') {
		      $condition .= " AND frd.classId = '$classId' ";
		    }
    		if($feeReport==1){//academic
    		$condition .="AND frd.feeType IN(1,4)";
			$feeTypeCondition ="AND fl.ledgerTypeId = 1" ;
    		 }
    		if($feeReport==2){//transport
    			$condition .="AND frd.feeType IN(2,4)";
				$feeTypeCondition ="AND fl.ledgerTypeId = 2" ;
    			}
    		if($feeReport==3){//hostel
    		$condition .="AND frd.feeType IN(3,4)";
			$feeTypeCondition ="AND fl.ledgerTypeId = 3";
    			} 
			$totalPaidFeeArray =$feeDetailHistoryReportManager->getTotalPaidFee($condition,$feeTypeCondition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
			 $cnt1 = count($totalPaidFeeArray);
			$cnt = count($totalPaidFeeArray);
			for($i=0;$i<$cnt;$i++) {
				$academicTotalFees = $totalPaidFeeArray[$i]['academicFees'] ;
			$academicLedgerDebit= $totalPaidFeeArray[$i]['ledgerAcademicDebit'];
			$academicLedgerCredit= $totalPaidFeeArray[$i]['ledgerAcademicCredit'];
			$hostelTotalFees = $totalPaidFeeArray[$i]['hostelFees'] ;
			$hostelLedgerDebit= $totalPaidFeeArray[$i]['ledgerHostelDebit'];
			$hostelLedgerCredit=$totalPaidFeeArray[$i]['ledgerHostelCredit'];
			$transportTotalFees = $totalPaidFeeArray[$i]['transportFees'];
			$transportLedgerDebit= $totalPaidFeeArray[$i]['ledgerTransportDebit'];
			$transportLedgerCredit= $totalPaidFeeArray[$i]['ledgerTransportCredit'];
			 $unPaidFees = 0;
			$remarks = '';
			$unPaidFees = ($totalPaidFeeArray[$i]['totalFees'] - $totalPaidFeeArray[$i]['paidAmount']);
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
                                      $totalPaidFeeArray[$i]);
			
			if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
			}
			else {
			$json_val .= ','.json_encode($valueArray);           
			}
		}
		echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
			      			
    	 } else if($paidReport==1) {//report for all un-paid fee student list
    	   if($classId!='') {
		      $condition .= " AND frm.feeClassId = '$classId' ";
		    }
    	
    	 	if($feeReport==1){//academic
    		$totalUnPaidFeeArray =$feeDetailHistoryReportManager->getPendingAcademicFee($condition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
			
    			}
    		if($feeReport==2){//transport
    			$totalUnPaidFeeArray =$feeDetailHistoryReportManager->getPendingTransportFee($condition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
			
    			}
    		if($feeReport==3){//hostel
    			$totalUnPaidFeeArray =$feeDetailHistoryReportManager->getPendingHostelFee($condition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
			
    			} else if($feeReport==0){    				
				$totalUnPaidFeeArray =$feeDetailHistoryReportManager->getUnPaidFee($condition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
    			}
			  
			   $cnt1 = count($totalUnPaidFeeArray);
			$cnt = count($totalUnPaidFeeArray);
			for($i=0;$i<$cnt;$i++) {
				$academicTotalFees = $totalUnPaidFeeArray[$i]['academicFees'] ;
			$academicLedgerDebit= $totalUnPaidFeeArray[$i]['ledgerAcademicDebit'];
			$academicLedgerCredit= $totalUnPaidFeeArray[$i]['ledgerAcademicCredit'];
			$hostelTotalFees = $totalUnPaidFeeArray[$i]['hostelFees'] ;
			$hostelLedgerDebit= $totalUnPaidFeeArray[$i]['ledgerHostelDebit'];
			$hostelLedgerCredit=$totalUnPaidFeeArray[$i]['ledgerHostelCredit'];
			$transportTotalFees = $totalUnPaidFeeArray[$i]['transportFees'];
			$transportLedgerDebit= $totalUnPaidFeeArray[$i]['ledgerTransportDebit'];
			$transportLedgerCredit= $totalUnPaidFeeArray[$i]['ledgerTransportCredit'];
			
			 $unPaidFees = 0;
			$remarks = '';
			$unPaidFees = ($totalUnPaidFeeArray[$i]['totalFees'] - $totalUnPaidFeeArray[$i]['paidAmount']);
			
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
                                      $totalUnPaidFeeArray[$i]);
			
			if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
			}
			else {
			$json_val .= ','.json_encode($valueArray);           
			}
		}
		echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
			      			
		
    	 } 
	}
			
	else{
		echo $errorMessage;
	}
		 
		
?>
