<?php
//-------------------------------------------------------
// Purpose: To store the records of Fee Ledger  functionality
// Author : Nishu Bindal
// Created on : (28.Mar.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','FeeLedger');
	define('ACCESS','edit');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	$errorMessage = '';
	require_once(MODEL_PATH . "/Fee/FeeLedgerManager.inc.php");   
	$feeLedgerManager = FeeLedgerManager::getInstance(); 
	global $sessionHandler;
	$particulars = trim($REQUEST_DATA['particulars']);
  	 $ledgerTypeId = trim($REQUEST_DATA['ledgerTypeId']);
	$debit = trim($REQUEST_DATA['debit']);
	$credit = trim($REQUEST_DATA['credit']);
	$classId = trim($REQUEST_DATA['classId']);
	$studentId = trim($REQUEST_DATA['studentId']);
	$feeCycleId = trim($REQUEST_DATA['feeCycleId']);
	$feeLedgerDebitCreditId = trim($REQUEST_DATA['feeLedgerDebitCreditId']);
	$isFine = trim($REQUEST_DATA['isFine']);
	
	if($particulars == ''){
		echo "Please Enter Particulars";
		die;
	}

	if($debit == '' && $credit == ''){
		echo "Please Enter Either Debit Or Credit";
		die;
	}

	if($classId == '' && ($studentId == '' && $feeCycleId =='')){
		echo "Required Parameter is Missing";
		die;
	}
   
	$status = $feeLedgerManager->editDebitCredit($classId,$studentId,$particulars,$debit,$credit,$feeCycleId,$feeLedgerDebitCreditId,$ledgerTypeId,$isFine);
	
	if($status === false){
		echo FAILURE;
		die;
	}
	else{		
		
	 if($sessionHandler->getSessionVariable('STUDENT_GENERATE_FEE')=='1'){
		 	$debitAmount =0;
			$creditAmount =0;
			$ledgerAcademicDebit = 0;
			$ledgerAcademicCredit = 0;
			$ledgerTransportDebit = 0;
			$ledgerTransportCredit = 0;
			$ledgerHostelDebit = 0;
			$ledgerHostelCredit = 0;
					
		 	$statusFee = $feeLedgerManager->checkFeeLedger($studentId,$classId,$ledgerTypeId);
		
		
			for($xx=0;$xx<=count($statusFee);$xx++){
				$debitAmount += $statusFee[$xx]['debit'];
				$creditAmount += $statusFee[$xx]['credit'];					
			}
				
			if($ledgerTypeId=='1'){
				$ledgerAcademicDebit =  $debitAmount;
				$ledgerAcademicCredit =  $creditAmount;
				
			}
			if($ledgerTypeId=='2'){
				$ledgerTransportDebit = $debitAmount;
				$ledgerTransportCredit = $creditAmount;
			}
			if($ledgerTypeId=='3'){
				$ledgerHostelDebit = $debitAmount;
				$ledgerHostelCredit = $creditAmount;
			}
			
			
			  $checkGenerateStudent = $feeLedgerManager->getGenerateStudentFeeValue($studentId,$classId);
 				$strQuery =" ledgerAcademicDebit ='$ledgerAcademicDebit',
					   ledgerAcademicCredit ='$ledgerAcademicCredit',											  
					   ledgerHostelDebit ='$ledgerHostelDebit',
					   ledgerHostelCredit ='$ledgerHostelCredit', 					 
					   ledgerTransportDebit ='$ledgerTransportDebit',
					  ledgerTransportCredit ='$ledgerTransportCredit'";
					 				
			 if(count($checkGenerateStudent) >0){											
				
				 $updateGenerateStudent = $feeLedgerManager->updateGenerateStudentFeeValue($studentId,$classId,$strQuery);	
				 if($updateGenerateStudent===false){		  		
					echo FAILURE;
			  	}
			
			 }else{
				 	$strQuery .=",studentId ='$studentId',
								classId ='$classId'";
				$insertGenerateStudent = $feeLedgerManager->insertGenerateStudentFeeValue($strQuery);	
				 if($insertGenerateStudent===false){		  		
					echo FAILURE;
			  	}
			 }
		 }
		 //Check for Generate Fee Student Table		END
		echo SUCCESS;
		die;
	}
	
	
?>
