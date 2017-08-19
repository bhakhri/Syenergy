<?php
//-------------------------------------------------------
// Purpose: To store the records of fees receipt
// Author : Nishu Bindal
// Created on : (19.April.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	define('MODULE','CollectFeesNew');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	global $sessionHandler;
	
    $userId = $sessionHandler->getSessionVariable('UserId');
        
	require_once(MODEL_PATH . "/Fee/CollectFeesManager.inc.php");   
	$CollectFeesManager = CollectFeesManager::getInstance(); 

	  
    require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
    	
	$errorMessage = '';
    $rollNoRegNo = trim($REQUEST_DATA['rollNoRegNo']);
    $feeClassId = trim($REQUEST_DATA['classId']);
    $feeType = trim($REQUEST_DATA['feePaymentMode']); 
    $paidAt = trim($REQUEST_DATA['paidAt']);
    $installmentNo = trim($REQUEST_DATA['installmentNo']);
    	
	if($rollNoRegNo == ''){
		echo "Please Enter Roll No. / Reg No.";
		die;
	}
	if($feeClassId == ''){
		echo "Please Select Class.";
		die;
	}
	if($feeType == ''){
		echo "Please Select Pay Fee Of.";
		die;
	}
	if($paidAt == ''){
		echo "Please Select Paid At.";
		die;
	} 

	
	$studentId = $REQUEST_DATA['studentId'];
	$feeReceiptId = $REQUEST_DATA['receiptId'];
	$feeClassId = $REQUEST_DATA['classId'];
	$roomRent = $REQUEST_DATA['hostelRent'];
	$hostelId = $REQUEST_DATA['hostelId'];
	$hostelRoomId = $REQUEST_DATA['roomId'];
	$busRouteId = $REQUEST_DATA['busRouteId'];
	$busStopId = $REQUEST_DATA['busStopId'];
	$receiptNo = $REQUEST_DATA['bankScrollNo']; // it is a receipt No
	$alreadyPaid = $REQUEST_DATA['alreadyPaid'];
	$feeDate =  date("Y-m-d",strtotime($REQUEST_DATA['receiptDate']))." ".date('H:i:s'); // appending current time just for ordering purposes
	$hostelSecurityStatus = $REQUEST_DATA['hostelSecurityStatus'];
	$hostelSecurityUpdate = '';
	$paymentAmount = 0;
	$headTotal = 0;
    
    $negativeLedgerAmount ='0';     
	$negativeDebitLedgerAmount ='0'; 
    if($feeType == 1 || $feeType == 4) {
		$concessionAmount =0;		
		$studentFeeArray = $CollectFeesManager->getStudentFeeDetail($feeClassId,$rollNoRegNo);
		  if($studentFeeArray[0]['concession'] > 0 ){
		    	$concessionAmount = $studentFeeArray[0]['concession'];
		    }   
    }
	 // Previous Class Balance (Start)
    $prevStudentId = $studentId;
    $prevClassId = $feeClassId;
    
    $isCheckPrviousBalance='0';        // Check the previous class balance  1=>On, 0=>Off
    
   $isPreviousPaid =0;
    
		
		 $prevAllClassComment = '';	
		  $prevAllClassAmount = 0;
    
    if($isCheckPrviousBalance=='1') {
        // Fetch Prev. Period Value
       	$prevFeeClass = $studentFeeManager->getFeeClassPeriodValue($prevClassId);
		
		$feeClassPeriodValue =$prevFeeClass[0]['periodValue'];
        $prevSemArray = $studentFeeManager->getPreviousPeriodValue($prevStudentId,$feeClassPeriodValue);		
		
      
        $prevAcademicClassComment='';
	    $prevAcademicClassAmount=0;  
   
		$prevHostelClassAmount =0;
		$prevHostelClassComment ='';
			
		$prevTransportClassComment='';
		$prevTransportClassAmount =0;
		
       for($i=0;$i<count($prevSemArray);$i++) {
         $academicBalance=0;
		 $hostelBalance=0;
		 $transportBalance=0;
		 
		 $prevAcademicAmount = 0;
		 $prevHostelAmount = 0;
		 $prevTransportAmount = 0;
			
		 $prevAcademicPaid = 0;  
		 $prevHostelPaid = 0; 
		 $prevTransportPaid = 0;
	         // Calculation Total Amount
	         $ttPrevClassId =$prevSemArray[$i]['classId'];
	         $prevSemFeeArray = $studentFeeManager->getPreviousTotalAmount($prevStudentId,$ttPrevClassId);			 
			 $prevAcademicAmount = $prevSemFeeArray[0]['acdemicFees'];
			 $prevHostelAmount = $prevSemFeeArray[0]['hostelFees'];
			 $prevTransportAmount = $prevSemFeeArray[0]['transportFees'];
			
			 // Calculation Paid AMOUNT 
			
			  $prevSemAcademicPaidArray = $studentFeeManager->getTotalAcademicPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevAcademicPaid = $prevSemAcademicPaidArray[0]['paidAcademicAmount'];
			   
			   $prevSemHostelPaidArray = $studentFeeManager->getTotalHostelPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevHostelPaid = $prevSemHostelPaidArray[0]['paidHostelAmount'];
			   
			   $prevSemTransportPaidArray = $studentFeeManager->getTotalTransportPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevTransportPaid = $prevSemTransportPaidArray[0]['paidTransportAmount'];	
			   
			    $prevSemAllPaidArray = $studentFeeManager->getTotalAllPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevAllPaid = $prevSemTransportPaidArray[0]['paidAmount'];			  
			  
			  if($prevAllPaid =='' || $prevAllPaid=='0'){
			  	   	$academicBalance = $prevAcademicAmount - $prevAcademicPaid;
					$hostelBalance = $prevHostelAmount - $prevHostelPaid;
					$transportBalance = $prevTransportAmount - $prevTransportPaid;
					
					 if($academicBalance!=0) {
			           if($prevAcademicClassComment!='') {
			             $prevAcademicClassComment .=",";  
			           }  
			           $prevAcademicClassComment .= $prevSemArray[$i]['periodName']."(A)";     
			         }
				  if($hostelBalance!=0) {
			           if($prevHostelClassComment!='') {
			             $prevHostelClassComment .=",";  
			           }  
			           $prevHostelClassComment .= $prevSemArray[$i]['periodName']."(H)";     
			         }
				  if($transportBalance!=0) {
			           if($prevTransportClassComment!='') {
			             $prevTransportClassComment .=",";  
			           }  
			           $prevTransportClassComment .= $prevSemArray[$i]['periodName']."(T)";     
			         }				
				
			   } else{
			  		if($prevAcademicAmount > 0){
					    $academicBalance = $prevAcademicAmount - $prevAllPaid;
					  } 
	                  if($prevHostelAmount > 0){
	                    $hostelBalance = $prevHostelAmount - $academicBalance; 
	                  }
	                  if($prevTransportAmount > 0){
	                    $transportBalance = $prevTransportAmount - $hostelBalance; 
	                  }
					 if($academicBalance!=0) {
			           if($prevAcademicClassComment!='') {
			             $prevAcademicClassComment .=",";  
			           }  
			           $prevAcademicClassComment .= $prevSemArray[$i]['periodName']."(A)";     
			         }
				  if($hostelBalance!=0) {
			           if($prevHostelClassComment!='') {
			             $prevHostelClassComment .=",";  
			           }  
			           $prevHostelClassComment .= $prevSemArray[$i]['periodName']."(H)";     
			         }
				  if($transportBalance!=0) {
			           if($prevTransportClassComment!='') {
			             $prevTransportClassComment .=",";  
			           }  
			           $prevTransportClassComment .= $prevSemArray[$i]['periodName']."(T)";     
			         }
			   } 		  
				
			  if($academicBalance !='0' || $hostelBalance !='0' || $transportBalance !='0'){
					if($isPreviousPaid=='0'){        
                    $prevAllClassAmount = $academicBalance + $hostelBalance + $transportBalance; 
					 $prevAllClassComment = $prevAcademicClassComment.",".$prevHostelClassComment.",".$prevTransportClassComment;                                                                  
                     $isPreviousPaid = '1'; 
						 $isPrevPrint=$ttPrevClassId +1;                           
					}
				 }  
                           
         }
    }
    // Diference
        
   
    // Previous Class Balance (End)
      if($feeType == 1) {
               $ledgerTypeId = '1';
		
               $ledgerDataArray = $CollectFeesManager->getStudentFeeLedger($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
            else if($feeType == 2) {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '2';
               $ledgerDataArray = $CollectFeesManager->getStudentFeeLedger($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
            else if($feeType == 3) {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '3';
               $ledgerDataArray = $CollectFeesManager->getStudentFeeLedger($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
            else if($feeType ==4) {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '1,2,3';
          $ledgerDataArray = $CollectFeesManager->getStudentFeeLedger($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }

       for($j=0;$j<count($ledgerDataArray);$j++) {
            if($ledgerDataArray[$j]['debit'] > 0){
		$negativeDebitLedgerAmount +=$ledgerDataArray[$j]['debit'];
		
            }
            else{
               $negativeLedgerAmount += $ledgerDataArray[$j]['credit'];
            }
       }
	 	
    	
    if($paidAt=='2') {            
        $feeMaxStatus = $CollectFeesManager->getReceiptMax();
        if($feeMaxStatus === false){
           echo FALIURE;
           die;
        }     
        $idNos ="00001";                     
        if($feeMaxStatus[0]['receiptNo']!='') {
           $ttId = intval(trim($feeMaxStatus[0]['receiptNo']))+1; 
           if(strlen($ttId)==1) {
             $idNos = "0000".$ttId;  
           } 
           else if(strlen($ttId)==2) {
             $idNos = "000".$ttId;  
           } 
           else if(strlen($ttId)==3) {
             $idNos = "00".$ttId;  
           } 
           else if(strlen($ttId)==4) {
             $idNos = "0".$ttId;  
           } 
           else {
             $idNos = $ttId;  
           } 
        }
        $receiptNo = "CUPB/12-13/".$idNos;
    }                                                                                                                                           
    
	
	if($studentId == '' || $feeReceiptId ==''){
		$errorMessage = "Required Parameter is Missing.!!!\n";
	}

	if($receiptNo == ''){
		$errorMessage .="Please Enter Bank Scroll No. /  Receipt No\n";
	}
	
	// checks for mode of payment (check,draft)
	$countOfPaymentModes = count($REQUEST_DATA['paymentTypeId']);
	if($countOfPaymentModes == 0  && $REQUEST_DATA['cashAmount'] == ''){
		$errorMessage .="Please Enter Payment Details.\n";
	}
	
	// to check fine amount & fine paid should be equal
	if($REQUEST_DATA['appliedFine'] != '' && $REQUEST_DATA['finePaid']){
		if($REQUEST_DATA['appliedFine'] !=  $REQUEST_DATA['finePaid']){
			$errorMessage .="Fine Amount & Fine Amount Paid can't be Different.\n";
		}
	}
	
	// to check Transport fine amount & Transport fine paid should be equal
	if($REQUEST_DATA['appliedTransportFine'] != '' && $REQUEST_DATA['transportFinePaid']){
		if($REQUEST_DATA['appliedTransportFine'] !=  $REQUEST_DATA['transportFinePaid']){
			$errorMessage .="Transport Fine Amount & Transport Fine Paid can't be different.\n";
		}
	}
	
	// to check Hostel fine amount & Hostelfine paid should be equal
	if($REQUEST_DATA['appliedHostelFine'] != '' && $REQUEST_DATA['hostelFinePaid']){
		if($REQUEST_DATA['appliedHostelFine'] !=  $REQUEST_DATA['hostelFinePaid']){
			$errorMessage .="Hostel Fine Amount & Hostel Fine Paid can't be different.\n";
		}
	}
	
	if($REQUEST_DATA['cashAmount'] !=''){
		if(((float)$REQUEST_DATA['cashAmount']) < 0){
			$errorMessage .="Cash Amount Should be Greater than 0.\n";	
		}
		$paymentAmount = (float) $REQUEST_DATA['cashAmount'];
	}

	for($i=0;$i<$countOfPaymentModes;$i++){
		if($REQUEST_DATA['paymentTypeId'][$i] == ''){
			$errorMessage .= "Please Select Type at row ".($i +1)."\n";
		}
		if($REQUEST_DATA['number'][$i] == ''){
			$errorMessage .= "Please Enter Number at row ".($i +1)."\n";
		}
		if($REQUEST_DATA['amount'][$i] == ''){
			$errorMessage .= "Please Enter Amount at row ".($i +1)."\n";
		}
		else{
			if(!is_numeric($REQUEST_DATA['amount'][$i])){
				$errorMessage .= "Amount Can't be Alphanumeric at row ".($i +1)."\n";
			}
			else if($REQUEST_DATA['amount'][$i] <= 0){
				$errorMessage .= "Amount Should be Greater than 0 at row ".($i +1)."\n";
			}
			else{ 
				$paymentAmount += (float) $REQUEST_DATA['amount'][$i];
			}						
		}
		if($REQUEST_DATA['issuingBankId'][$i] == ''){
			$errorMessage .= "Please Enter Bank at row ".($i +1)."\n";
		}
		if($REQUEST_DATA['dated'][$i] == ''){
			$errorMessage .= "Please Enter Date at row ".($i +1)."\n";
		}
		
		for($j=$i+1; $j<$countOfPaymentModes;$j++){
			if(($REQUEST_DATA['paymentTypeId'][$j] == $REQUEST_DATA['paymentTypeId'][$i]) && ($REQUEST_DATA['number'][$i] == $REQUEST_DATA['number'][$j])){
				$errorMessage .="Number Can't be Same at row ".($i + 1)." and ".($j+1);
			}
		}
	}
	
	 if($busRouteId != 0  && $busStopId != 0){ 
	 	if($REQUEST_DATA['transportFees'] !=''){
			if(((float) $REQUEST_DATA['transportFees']) < 0){
				$errorMessage .= "Trasport Paid Amount can't be smaller than 0.\n";
			}
			else if(!is_numeric($REQUEST_DATA['transportFees'])){
				$errorMessage .= "Transport Paid Amount can't be Alphanumeric.\n";
			}
			else{
				$headTotal += (float) $REQUEST_DATA['transportFees'];
			}
		}	
		
		if($REQUEST_DATA['transportFinePaid'] !=''){
			if($REQUEST_DATA['transportFinePaid'] < 0){
				$errorMessage .= "Transport Fine can't be smaller than 0.\n";
			}
			else if(!is_numeric($REQUEST_DATA['transportFinePaid'])){
				$errorMessage .= "Transport Fine Amount can't be Alphanumeric.\n";
			}
			else{
				$headTotal += (float) $REQUEST_DATA['transportFinePaid'];
			}
		}	
	}

	if($hostelId != 0 && $hostelRoomId != 0){
		if($REQUEST_DATA['hostelFees'] !=''){
			if($REQUEST_DATA['hostelFees'] < 0){
				$errorMessage .= "Hostel Amount Paid can't be smaller than 0.\n";
			}
			else if(!is_numeric($REQUEST_DATA['hostelFees'])){
				$errorMessage .= "Hostel Paid Amount can't be Alphanumeric.\n";
			}
			else{
				$headTotal += (float) $REQUEST_DATA['hostelFees'];
			}
		}
		if($REQUEST_DATA['hostelFinePaid'] !=''){
			if($REQUEST_DATA['hostelFinePaid'] < 0){
				$errorMessage .= "Hostel Fine can't be smaller than 0.\n";
			}
			else if(!is_numeric($REQUEST_DATA['hostelFinePaid'])){
				$errorMessage .= "Hostel Fine Amount can't be Alphanumeric.\n";
			}
			else{
				$headTotal += (float) $REQUEST_DATA['hostelFinePaid'];
			}
		}
	}
	
	
	
	if($REQUEST_DATA['hostelSecurityStatus'] == 1){
		if($REQUEST_DATA['hostelSeurity'] !=''){
			if(((float) $REQUEST_DATA['hostelSeurity']) < 0){
				$errorMessage .= "Hostel Security Amount Paid can't be smaller than 0.\n";
			}
			else if(!is_numeric(trim($REQUEST_DATA['hostelSeurity']))){
				$errorMessage .= "Hostel Security Paid can't be Alphanumeric.\n";	
			}
			else{
				$headTotal += (float) $REQUEST_DATA['hostelSeurity'];
	
			}
		}
	}
	
	$count = count($REQUEST_DATA['feeHeadId']);		  
	for($k=0;$k<$count;$k++){
		if($REQUEST_DATA['academicFee'][$k] !=''){
			if(((float) $REQUEST_DATA['academicFee'][$k]) < 0){
				$errorMessage .= "Amount Paid can't be smaller than 0.\n";
			}
			else if(!is_numeric($REQUEST_DATA['academicFee'][$k])){
				$errorMessage .= "Amount Paid can't be Alphanumeric.\n";
			}
			else{
				$headTotal += (float) $REQUEST_DATA['academicFee'][$k];
			}
		}
	}
	
	if($REQUEST_DATA['finePaid'] !=''){
		if($REQUEST_DATA['finePaid'] < 0){
			$errorMessage .= "Fine can't be smaller than 0.\n";
		}
		else if(!is_numeric($REQUEST_DATA['finePaid'])){
			$errorMessage .= "Fine Amount can't be Alphanumeric.\n";
		}
		else{
			$headTotal += (float) $REQUEST_DATA['finePaid'];
		}
	}
	
    	//$headTotal -=  $negativeLedgerAmount;

  	$headTotal +=  $negativeDebitLedgerAmount;
	//Previous class Balance Added to Total Amount
	 if($feeType == 1 || $feeType==4) {
	   if($isPrevPrint==$feeClassId){
	   	if($prevAllClassAmount!='' || $prevAllClassAmount!='0'){
	   	$headTotal +=$prevAllClassAmount;
	   	}	
	}
   }
   
	
	//$headTotal -=	$concessionAmount;
	if($headTotal != $paymentAmount){
      $errorMessage .="Fee Head Wise Amount (".$headTotal.") and Payment Detail (".$paymentAmount.") mismatch.\n Difference (Head Wise Amount - Payment Detail) is of ".($headTotal - $paymentAmount);
	}
			  	
	 // check if receipt No Already exists
	$checkReceiptNo = $CollectFeesManager->checkReceiptNo($receiptNo);
	if($checkReceiptNo[0]['cnt'] > 0){
		$errorMessage .= "Fee Receipt No Already exists.";
	}
  	
  	 // check if Instalment No Already exists
	$checkInstallmentNo = $CollectFeesManager->checkInstallmentNo($feeReceiptId,$studentId,$feeClassId,$installmentNo);
	if($checkInstallmentNo[0]['cnt'] > 0){
		$errorMessage .= "Installment No. Already exists.";
	}
	
  	if($errorMessage == ''){
  		if(SystemDatabaseManager::getInstance()->startTransaction()){
  			if($feeType == 4){ // All

		  		$instrumentStatus = $CollectFeesManager->updateFeeReceiptInstrument($feeReceiptId,$studentId,$feeClassId);
		  		if($instrumentStatus == false){
		  			echo FALIURE;
		  			die;
		  		}
			  	
			  	// logic for entering applicable fee bus/hostel
			  	if($busRouteId != 0 && $busStopId != 0){
				  	$updateData = " transportFeeStatus = 1";
			   	}
			   	
			   	if($hostelId != 0 && $hostelRoomId != 0){
			   		if($updateData !=''){
			   			$updateData .=", ";
			   		}
			   		$updateData .= " hostelFeeStatus = 1";		
			    		// to mark hostel security as paid
			    		if($hostelSecurityStatus == 1){
			    			$hostelSecurityUpdate = ", securityStatus = 1";
			    			$securityStatus = $CollectFeesManager->updateHostelSecurityStatus($studentId,$hostelRoomId,$hostelSecurityUpdate);
					  	    if($securityStatus === false){
					  		    echo FALIURE;
					  		    die;
					  	    }
			    		}
			   	}
			   			  
			  	$status = $CollectFeesManager->updateFeeReceiptMaster($feeReceiptId,$updateData);
			  	if($status === false){
			  		echo FALIURE;
			  		die;
			  	}
			  	
			  	$status = $CollectFeesManager->updateFeeLedger($studentId,$feeClassId,$feeCycleId);
			  	if($status === false){
			  		echo FALIURE;
			  		die;
			  	}
			  	
			  	
			 }
			 else if($feeType == 1){ // only Academic
			 	
			 	$instrumentStatus = $CollectFeesManager->updateFeeReceiptInstrument($feeReceiptId,$studentId,$feeClassId);
		  		if($instrumentStatus == false){
		  			echo FALIURE;
		  			die;
		  		}
		  		
			  	$status = $CollectFeesManager->updateFeeReceiptMaster($feeReceiptId,'');
			  	if($status === false){
			  		echo FALIURE;
			  		die;
			  	}
			  	
			  	$status = $CollectFeesManager->updateFeeLedger($studentId,$feeClassId,$feeCycleId);
			  	if($status === false){
			  		echo FALIURE;
			  		die;
			  	}
			 }
			 else if($feeType == 2){// only Transport
			  	$transportStatus = $CollectFeesManager->updateTransportFees($feeReceiptId);
			  	if($transportStatus === false){
			  		echo FALIURE;
			  		die;
			  	}
			 }
			 else if($feeType == 3){ // only Hostel
			 	if($hostelId != 0 && $hostelRoomId != 0){
				  	$hostelStatus = $CollectFeesManager->updateHostelFees($feeReceiptId);
				  	if($hostelStatus === false){
				  		echo FALIURE;
				  		die;
				  	}
				  	// to mark hostel security as paid
			    		if($hostelSecurityStatus == 1){
			    			$hostelSecurityUpdate = ", securityStatus = 1";
			    			$securityStatus = $CollectFeesManager->updateHostelSecurityStatus($studentId,$hostelRoomId,$hostelSecurityUpdate);
					  	    if($securityStatus === false){
					  		    echo FALIURE;
					  		    die;
					  	    }
			    		}
			    	}
			 }
			 
			 // INSERTING HEAD WISE DATA OF FEE
			   	$count = count($REQUEST_DATA['feeHeadId']);
			  	//feeHeadCollectionId,dateOfEntry,feeReceiptId,installmentNo,receiptNo,receiptDate,studentId,classId,feeHeadId,feeHeadAmount,receiptCancellation,userId
			  	$feeHeadValues = "";
			  	for($k=0;$k<$count;$k++){
			  		if($REQUEST_DATA['academicFee'][$k] > 0){
			  			if($feeHeadValues != ''){
			  				$feeHeadValues .=", ";
			  			}
			  			$feeHeadValues .="('',now('Y-m-d'),'$feeReceiptId','$installmentNo','$receiptNo','$feeDate','$studentId','$feeClassId','".$REQUEST_DATA['feeHeadId'][$k]."','".$REQUEST_DATA['academicFee'][$k]."',0,'$userId')";
			  		}
			  	}
			  	
			  	if($feeHeadValues !=''){
			  		$status1 = $CollectFeesManager->insertIntoFeeHeadCollection($feeHeadValues);
			  		if($status1 === false){
			  			echo FALIURE;
			  			die;
			  		}
			  	}
			  	// In case of Fee Receipt Instrument Add Instrument in fee receipt instrument table
			  	$countMiscHead = count($REQUEST_DATA['miscHeadAmt']);
			  	$headValues = '';
			  	
			  	for($k= 0;$k<$countMiscHead;$k++){
			  		$dataArr = array();
			  		if($REQUEST_DATA['miscHeadAmt'][$k] !=''){
			  			$data = $REQUEST_DATA['miscHeadData'][$k];
			  			$dataArr = explode('-',$data);
			  			
			  			if($headValues != ''){
			  				$headValues .=",";
			  			}
			  			
			  			$headValues .="('','".$dataArr[0]."','".$dataArr[2]."','".$dataArr[1]."','".$dataArr[3]."','".$dataArr[4]."','".$REQUEST_DATA['miscHeadAmt'][$k] ."')";
			  		}
			  	}
				
				if($headValues !=''){
					$status2 = $CollectFeesManager->insertIntoFeeReceiptInstrument($headValues);
			  		if($status2 === false){
			  			echo FALIURE;
			  			die;
			  		}	
				}
					  	
			  $hostelFeePaid = trim($REQUEST_DATA['hostelFees']);
			  $transportFeePaid = trim($REQUEST_DATA['transportFees']);
			  $hostelSecurityPaid = trim($REQUEST_DATA['hostelSeurity']);
			  
			 // for inserting details of payment 
			 $countOfPaymentModes = count($REQUEST_DATA['paymentTypeId']);
			 $values ='';
			 //feeReceiptDetailId,feeReceiptId,studentId,classId,paymentMode,bankId,dated,amount,number,feeType,receiptNo,receiptDate,paidAt,installmentNo,academicFeePaid,hostelFeePaid,transportFeePaid,hostelSecurity,isDelete,tranportFine,hostelFine,academicFine,academicFinePaid,hostelFinePaid,transportFinePaid
			 if($REQUEST_DATA['cashAmount'] !=''){
			 	$values .="('','$feeReceiptId','$studentId','$feeClassId','1',''
				,'NULL','".$REQUEST_DATA['cashAmount']."','','".$feeType."','".$receiptNo."','".$feeDate."','".$paidAt."','".$installmentNo."','".$academicFeePaid."','".$hostelFeePaid."','".$transportFeePaid."','".$hostelSecurityPaid."',0,'$userId','".$REQUEST_DATA['appliedTransportFine']."','".$REQUEST_DATA['appliedHostelFine']."','".$REQUEST_DATA['appliedFine']."','".$REQUEST_DATA['finePaid']."','".$REQUEST_DATA['hostelFinePaid']."','".$REQUEST_DATA['transportFinePaid']."')";
			}
			for($i=0;$i<$countOfPaymentModes;$i++){
				if($values != ''){
					$values .=", ";
				}

				$values .="('','$feeReceiptId','$studentId','$feeClassId','".$REQUEST_DATA['paymentTypeId'][$i]."','".$REQUEST_DATA['issuingBankId'][$i]."'
				,'".$REQUEST_DATA['dated'][$i]."','".$REQUEST_DATA['amount'][$i]."','".$REQUEST_DATA['number'][$i]."','".$feeType."','".$receiptNo."','".$feeDate."','".$paidAt."','".$installmentNo."','".$academicFeePaid."','".$hostelFeePaid."','".$transportFeePaid."','".$hostelSecurityPaid."',0,'$userId','".$REQUEST_DATA['appliedTransportFine']."','".$REQUEST_DATA['appliedHostelFine']."','".$REQUEST_DATA['appliedFine']."','".$REQUEST_DATA['finePaid']."','".$REQUEST_DATA['hostelFinePaid']."','".$REQUEST_DATA['transportFinePaid']."')";
			}
			
			if($values != ''){
				$feeDetailStatus = $CollectFeesManager->insertIntoFeeReceiptDetails($values);
				if($feeDetailStatus === false){
			  		echo FALIURE;
			  		die;
			  	}
			}
		
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo SUCCESS;
			}
			else {
				echo FAILURE;
				die; 
			}
		  }
		  else {
			echo FAILURE;
			die;
		}
  		
  	}
  	else{
  		echo $errorMessage;
  		die;
  	}
  	
  	
?>
