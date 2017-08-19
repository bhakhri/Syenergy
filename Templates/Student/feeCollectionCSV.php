<?php 
//This file is used as CSV version for fee collection for subject centric.
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	if(UtilityManager::notEmpty($REQUEST_DATA['univId'])) {
	    
	   $filter .= ' AND (cls.universityId IN ('.$REQUEST_DATA['univId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['instsId'])) {
	    
	   $filter .= ' AND (cls.instituteId IN ('.$REQUEST_DATA['instsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['degsId'])) {
	    
	   $filter .= ' AND (cls.degreeId IN ('.$REQUEST_DATA['degsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['bransId'])) {
	    
	   $filter .= ' AND (cls.branchId IN ('.$REQUEST_DATA['bransId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['semsId'])) {
	    
	   $filter .= ' AND (cls.studyPeriodId IN ('.$REQUEST_DATA['semsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['feesId'])) {
	   
	   $feeCycleArr = explode(",", $REQUEST_DATA['feesId']);
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	   $filter .= " AND (fr.receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	   $filter .= " AND (fr.receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";         
	}
	
    $cnt = count($feeCycleArr); 
	$j=0;
	$k=0;
	$filterPrev = $filter; 
	if($cnt >0 && is_array($feeCycleArr) ) { 
		$csvData = '';
		$csvData .= "Sr,Fee Cycle,Cash(Rs),Cheque(Rs),Draft(Rs),Total(Rs) \n";
	for($i=0;$i<$cnt;$i++){
	  
	   $filter1 = '';	
	   //$filter1 = " AND fr.feeCycleId =".$feeCycleArr[$i];
       $filter1 = " AND fr.feeCycleId =".$feeCycleArr[$i]." AND fr.isNew = 1 AND fr.receiptStatus NOT IN (3,4) "; 
	   $filter  = $filterPrev.' '.$filter1;
       $studentRecordArray = $studentManager->getFeeCollectionList($filter,$limit,$orderBy);
       $cont = count($studentRecordArray);
	   $filter1 = '';
	   $totalCash = '';
	   $totalCheque = '';
	   $totalDraft = '';
	   $feeCycle = '';
	   for($j=0;$j<$cont;$j++) {
		  $feeReceiptId = $studentRecordArray[$j]['feeReceiptId'];
          $totalCash +=$studentRecordArray[$j]['cashAmount'];    
              
          $conditions = " WHERE feeReceiptId = $feeReceiptId AND paymentInstrument =2";
          $resultArray = $studentManager->getSingleField('fee_payment_detail',' SUM(instrumentAmount) AS totalAmountPaid',$conditions);  
          $totalCheque +=$resultArray[0]['totalAmountPaid'];  
          
          $conditions = " WHERE feeReceiptId = $feeReceiptId AND paymentInstrument =3";
          $resultArray = $studentManager->getSingleField('fee_payment_detail',' SUM(instrumentAmount) AS totalAmountPaid',$conditions); 
          $totalDraft +=$resultArray[0]['totalAmountPaid'];   
           
          if($studentRecordArray[$j]['paymentInstrument']=="1")
          {
             $totalCash +=$studentRecordArray[$j]['totalAmountPaid'];
          }
          if($studentRecordArray[$j]['paymentInstrument']=="2")
          {
             $totalCheque +=$studentRecordArray[$j]['totalAmountPaid'];
          }
          if($studentRecordArray[$j]['paymentInstrument']=="3")
          {
             $totalDraft +=$studentRecordArray[$j]['totalAmountPaid'];
          }
		   $feeCycle = $studentRecordArray[$j]['feeCycleId'];
	   }

	   $feeArr = $studentManager->getFieldValue("fee_cycle","cycleName",$feeCycleArr[$i],"feeCycleId");
	   $feeCycle=	$feeArr[0]['cycleName'];
	   
	   $totalCash = ($totalCash!='') ? $totalCash : "-- ";
	   $totalCash =  number_format($totalCash,'2','.','');
	   
	   $totalCheque = ($totalCheque!='') ? $totalCheque : "-- ";
	   $totalCheque =  number_format($totalCheque,'2','.','');

	   $totalDraft = ($totalDraft!='') ? $totalDraft : "-- ";
	   $totalDraft =  number_format($totalDraft,'2','.','');

	   $totalAmount = $totalCash+$totalCheque+$totalDraft;
	   $totalAmount =  number_format($totalAmount,'2','.','');

	   $grandAmount = $grandAmount+$totalAmount;
	   $grandAmount =  number_format($grandAmount,'2','.','');
		
       $csvData .= ($i+1).','.$feeCycle.','.$totalCash.','.$totalCheque.','.$totalDraft.','.$totalAmount;
	   $csvData .= "\n";
 
		}

		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		// We'll be outputting a PDF
		header('Content-type: application/octet-stream');
		header("Content-Length: " .strlen($csvData) );
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment;  filename="feeCollection-'.date("d-M-y").'.csv"');
		// The PDF source is in original.pdf
		header("Content-Transfer-Encoding: binary\n");
		echo $csvData;
		die;   
 
		}
// for VSS
// $History: feeCollectionCSV.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/23/08   Time: 1:00p
//Created in $/LeapCC/Templates/Student
//Intial Checkin
?>