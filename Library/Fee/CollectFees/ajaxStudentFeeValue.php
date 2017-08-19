<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
// Author : Nishu Bindal
// Created on : (05.Mar.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CollectFeesNew');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    $errorMessage = '';
    
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/CollectFeesManager.inc.php");   
    $CollectFeesManager = CollectFeesManager::getInstance(); 
    
    require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
    
    global $sessionHandler;
    $paymentModeArray = array(1=>'Cash', 2=>'Check', 3=>'DD');
    
    $rollNoRegNo = trim($REQUEST_DATA['rollNoRegNo']);
    $classId = trim($REQUEST_DATA['classId']);
    $feeType = trim($REQUEST_DATA['feePaymentMode']); 
    $lblName = "Paid:&nbsp;";
    
    $academicFeePaid = trim($REQUEST_DATA['academicFeePaid']);
    $hostelFeePaid = trim($REQUEST_DATA['hostelFeePaid']);
    $transportFeePaid = trim($REQUEST_DATA['transportFeePaid']);
    $totalAlreadyPaid =0;
  
    $negativeLedgerAmount ='0';
    $negativeDebitLedgerAmount ='0';
    if($rollNoRegNo == ''){
        echo "Please Enter Roll No. / Reg No.";
        die;
    }
    if($classId == ''){
        echo "Please Select Class.";
        die;
    }
    if($feeType == ''){
        echo "Please Select Pay Fee Of.";
        die;
    }
    

    $rollCondition = " (rollNo = '$rollNoRegNo' OR regNo = '$rollNoRegNo') ";  
    $studentRollArray = $studentFeeManager->getStudentId($rollCondition);  
    if(is_array($studentRollArray) && count($studentRollArray)>0) {  
       $studentId = $studentRollArray[0]['studentId'];
    }
    
	 // Previous Class Balance (Start)
    $prevStudentId = $studentId;
    $prevClassId = $classId;
     
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
	
//Fine SetUp module linkingg...
    function dateDiff($start, $end) {
       $start_ts = strtotime($start);
       $end_ts = strtotime($end);
       
       $diff = $end_ts - $start_ts;
       return round($diff / 86400);
    }
    
    /*
    $condition = " AND frd.classId = '$classId' AND frd.feeType = '$feeType' AND frd.studentId = '$studentId' ";
    $prevFeeArray = $studentFeeManager->getCheckStudentFine($condition);
    if(is_array($prevFeeArray) && count($prevFeeArray)==0) {  
          // Ledger Check  
          $ledgerCondition = "frd.classId = '$classId' AND frd.studentId = '$studentId' AND frd.ledgerTypeId = '$feeType' ";  
          $ledgerSetUpArray = $studentFeeManager->getLedgerCheckFine($ledgerCondition);
          if($ledgerSetUpArray[0]['isFine']!='2') {  
              $fineSetUpArray = $studentFeeManager->getFineSetUpDetails($classId,$feeType);

              if(is_array($fineSetUpArray) && count($fineSetUpArray)>0) {  
                 $serverDate = date('Y-m-d'); 
                 $dif=-1;
                 $fineCharges='0';
                 for($i=0;$i<count($fineSetUpArray);$i++) {
                    if($fineSetUpArray[$i]['fromDate']<$serverDate) {
                      $dif = abs(dateDiff($fineSetUpArray[$i]['fromDate'],$serverDate));
                      if($dif==0) {
                        $dif=1;
                      }
                    }
                    if($dif > 0) {
                      if($fineSetUpArray[$i]['chargesFormat']=='1') { // daily
                        $fineCharges = $fineSetUpArray[$i]['charges']*$dif;
                      }
                      else if($fineSetUpArray[$i]['chargesFormat']=='2') { // fixed
                        $fineCharges = $fineSetUpArray[$i]['charges'];
                      }
                      break;
                    } 
                 }      
                
                 if($dif!=-1) {
                    // Academic Fee/  Transport / Hostel
                    $isApplyFine='0';
                    if($feeType=='1') {
                       $fineApplyArray = $studentFeeManager->applyFineAcd($studentId,$classId);
                       if($fineApplyArray===false){
                         echo FAILURE;
                         die;
                       } 
                       if(count($fineApplyArray)>0){
                          $isApplyFine='1';       
                       }     
                    } 
                    else if($feeType=='2') {
                       $isApplyFine='0';
                       $fineApplyArray = $studentFeeManager->applyFineTransport($studentId,$classId);
                       if($fineApplyArray===false){
                         echo FAILURE;
                         die;
                       }
                       if(count($fineApplyArray)>0){
                          $isApplyFine='1';       
                       }
                    }
                    else if($feeType=='3') {
                       $isApplyFine='0';
                       $fineApplyArray = $studentFeeManager->applyFineHostel($studentId,$classId);
                       if($fineApplyArray===false){
                         echo FAILURE;
                         die;
                       }
                       if(count($fineApplyArray)>0){
                          $isApplyFine='1';       
                       }       
                    }
            
                    if($isApplyFine=='1') { 
                        $returnArray = $studentFeeManager->deleteFineLedgerData($studentId,$classId,$feeType);
                        if($returnArray===false){
                          echo FAILURE;
                          die;
                        }
                        
                        $returnArray = $studentFeeManager->updateFineLedgerData($studentId,$classId,$feeType,$fineCharges,$feeCycleId);
                        if($returnArray===false){
                          echo FAILURE;
                          die;
                        }
                    }
                 }
               }
          }
    }
    */
//End of fIne setup Module      
    
    
    // Fee Payment Mode 1=>Academic, 4=>All
    if($feeType=='1' || $feeType =='4') {
       $studentSearchArray = $CollectFeesManager->getSearchStudent($rollNoRegNo);
       if(count($studentSearchArray) == 0){
          echo "Fees Not Found for this class.";
          die;
       }
       $studentId = $studentSearchArray[0]['studentId'];
       $feeClassId = $classId;
       
       /*
       $feeGenSearch='0';
       $feeCycleCondition = " studentId = '$studentId' AND classId = '$feeClassId' ";
       $feeCycleArray = $generateFeeManager->checkStudentFeeGenerate($feeCycleCondition);
       if(count($feeCycleArray)> 0){
         $feeGenSearch='1';
       }
       */
       
       /*
           $feeAcdSearch='0';
           $feeCycleCondition = " AND studentId = '$studentId' AND classId = '$feeClassId' ";
           $feeCycleArray = $generateFeeManager->checkStudentFeeDetail($feeCycleCondition);
           if(count($feeCycleArray)> 0){
             $feeAcdSearch='1';
           }
       */
       $feeAcdSearch='0';
       if($feeAcdSearch=='0') {
         $feeResultMessage = getGenerateFee($feeClassId,$studentId);
         if($feeResultMessage!=SUCCESS) {
           echo $feeResultMessage;
           die;  
         }
       }
    }
 
    
    $busStopId ='';
    $busRouteId ='';
    $bankScrollNo = '';
    $receiptDate = '';
    $acdmicFeePaid = 0;
    $hostelFeePaid = 0;
    $hostelSecurityStatus = 0;
    $transportFeePaid = 0;
    
    $index = array();
    $academicFeeArray = array();
    $applicableHeadId = array();
    if($errorMessage == ''){
        $currentDate = date("d-m-Y");
        $count =1;
        $concessionAmount =0;
        $totalFees =0;
        $studentFeeArray = $CollectFeesManager->getStudentFeeDetail($classId,$rollNoRegNo);
        if(count($studentFeeArray) == 0){
            echo "Fees Not Found for this class.";
            die;
        }
        
        $json_student =  json_encode($studentFeeArray[0]);
        $feeReceiptId = $studentFeeArray[0]['feeReceiptId'];
        $studentId = $studentFeeArray[0]['studentId'];
        $feeClassId = $studentFeeArray[0]['feeClassId'];
        $hostelId = $studentFeeArray[0]['hostelId'];
        $hostelRoomId = $studentFeeArray[0]['hostelRoomId'];
        $busRouteId = $studentFeeArray[0]['busRouteId'];
        $busStopId = $studentFeeArray[0]['busStopId'];
        $feeCycleName = $studentFeeArray[0]['cycleName'];
        $feeCycleId = $studentFeeArray[0]['feeCycleId'];
        $rollNo = $studentFeeArray[0]['rollNo'];
        $instituteId = $studentFeeArray[0]['instituteId'];

        $studentFeePaidArray = $CollectFeesManager->getStudentAlreadyPaidFeeDetail($classId,$studentId,$feeReceiptId);
        
        // Is Online Payment Receive Start
            $studentFeeOnlinePaidArray = $CollectFeesManager->getStudentOnlineAlreadyPaidFeeDetail($classId,$studentId,$feeReceiptId);
            $isOnlineReceivePayment = 0;
            if(count($studentFeeOnlinePaidArray)>0) {
               $isOnlineReceivePayment = $studentFeeOnlinePaidArray[0]['paidAmount'];
               $isOnlineFeeType = $studentFeeOnlinePaidArray[0]['feeType'];  
            }
        // Is online payment Receive End
        
        // to get Ledger Details Debit/Credit
    
        $feeHeadIdArr = array();
        $miscellaneousHeadArr = array();
        if($feeType == 4 || $feeType == 1){
            for($i=0;$i<count($studentFeeArray);$i++){
                $paidAmount = 0;
                $remaningAmount = $studentFeeArray[$i]['amount'];
                $applAmt ='';
                foreach($studentFeePaidArray as $key =>$value){
                    if($studentFeeArray[$i]['feeHeadId'] == $value['feeHeadId']){
                        $paidAmount = number_format((float)doubleval($value['paidAmount']), 2, '.', '');
                        $remaningAmount = doubleval($studentFeeArray[$i]['amount']) - $paidAmount;
                    }
                }
                if($remaningAmount < 0){
                  $remaningAmount =0;
                }
                $feeHeadIdArr[] = $studentFeeArray[$i]['feeHeadId'];
                $totalAlreadyPaid += (float) $paidAmount;
                
                $ttPrevPaid = "<br/><span align='right' style='display:none;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'>$lblName$paidAmount</span>";
                $amount = $studentFeeArray[$i]['amount'];
                $totalFees += $amount;
                $applAmt="<input type='text'    name='academicFee[]'  value='".$remaningAmount."' id='academicFee$i' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);'><input type='hidden' name='feeHeadId[]' value='".$studentFeeArray[$i]['feeHeadId']."'>$ttPrevPaid";
                
                if($studentFeeArray[$i]['isSpecial'] == 1){ 
                    // in case of Misc Head storing it in array & displaying in last
                    $headName = "<font color='blue'>".$studentFeeArray[$i]['feeHeadName']."</font>"; 
                    $miscellaneousHeadArr[] = array('headName' => "$headName",'amount' => "$amount",'applAmt' => "$applAmt");
                }
                else{
                    $headName = $studentFeeArray[$i]['feeHeadName'];
                    feeTypeValueFormat($count,$headName,$amount,$applAmt);
                    $count++;
                }
            }
            if($isOnlineFeeType == 1) {
              $totalAlreadyPaid += (float) $isOnlineReceivePayment;    
            }
            if($studentFeeArray[0]['concession'] > 0 ){
              $concessionAmount = $studentFeeArray[0]['concession'];
            }
        }
        
       
        
         // function to get Already paid hostel Fees/transport Fees/ Hostel Security
         $FeePaidArray = $CollectFeesManager->getAlreadyPaidFee($classId,$studentId,$feeReceiptId);
         $installmentNo = doubleval($FeePaidArray[0]['installmentNo']) + 1;
            
        /*  BUS FEE CODE BEGAINS */
        if($feeType == 3 || $feeType == 4){
            $paidAmount = 0;
            $hostelSecurity = 0;
            $toBePaid =0;
            if($isOnlineFeeType == 3) {
               $totalAlreadyPaid += (float) $isOnlineReceivePayment;    
            }
             if($studentFeeArray[0]['hostelSecurity'] > 0){
                $paidAmount = 0;
                $headName ="Hostel Security";/*
                if($studentFeeArray[0]['hostelFeeStatus'] == 1){
                    $headName ="<font color='green'>Hostel Security</font>";
                }
                else{
                    
                }*/
                
                $paidAmount = number_format((float)doubleval($FeePaidArray[0]['hostelSecurity']), 2, '.', '');
                    $hostelSecurity = $studentFeeArray[0]['hostelSecurity'];
                    $totalFees += $hostelSecurity;
                    $ttPrevPaid = "<br/><span align='right' style='display:none;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'>$lblName$paidAmount</span>";
                    $toBePaid = doubleval($hostelSecurity) - doubleval($paidAmount);
                    if($toBePaid < 0){
                    $toBePaid =0;
                }
                    $totalAlreadyPaid +=(float)$paidAmount;
                    
                   
                    
                    $applAmt="<input type='text'  name='hostelSeurity' id='hostelSecurity' value='".$toBePaid."' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);'><input type='hidden' name='hostelSecurityInstrument' value='$toBePaid'>$ttPrevPaid";
                    feeTypeValueFormat($count,$headName,$hostelSecurity,$applAmt);
                    $count++;
                    $hostelSecurityStatus =1; // flag to know if user is paying hostel Security 
            }
            if($studentFeeArray[0]['hostelFees'] > 0){
                $paidAmount = 0;
                $paidAmount=number_format((float)doubleval($FeePaidArray[0]['hostelFeePaid']), 2, '.', '');
                $remaningAmt = doubleval($studentFeeArray[0]['hostelFees']) - doubleval($FeePaidArray[0]['hostelFeePaid']);
                number_format((float)$remaningAmt, 2, '.', '');
                number_format((float)$paidAmount, 2, '.', '');
                if($remaningAmt < 0){
                    $remaningAmt =0;
                }
                $headName ="Hostel Fee";
                /*if($studentFeeArray[0]['hostelFeeStatus'] == 1){
                    $headName ="<font color='green'>Hostel Fee</font>";
                    $hostelFeePaid =1;
                }
                */
                $totalAlreadyPaid +=(float) $paidAmount;
                    $amount = $studentFeeArray[0]['hostelFees'];
                    $totalFees += $amount;
                    $ttPrevPaid = "<br/><span align='right' style='display:none;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'>$lblName$paidAmount</span>";
                    $applAmt="<input type='text'  name='hostelFees' value='".$remaningAmt."' id='hostelFees' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);'><input type='hidden' name='hostelFeeInstrument' value='$remaningAmt'>$ttPrevPaid";
                    feeTypeValueFormat($count,$headName,$amount,$applAmt);
                    $count++;                
            }
        }
        // END
        
        if($feeType == 2 || $feeType == 4){
            $paidAmount = 0;
            if($isOnlineFeeType == 2) {
               $totalAlreadyPaid += (float) $isOnlineReceivePayment;    
            }
            if($studentFeeArray[0]['transportFees'] > 0){
                $toBePaid = doubleval($studentFeeArray[0]['transportFees']) - doubleval($FeePaidArray[0]['transportFeePaid']);
                if($toBePaid < 0){
                    $toBePaid =0;
                }
                $paidAmount = number_format((float)doubleval($FeePaidArray[0]['transportFeePaid']), 2, '.', '');
                $headName ="Transport Fee";/*
                if($studentFeeArray[0]['transportFeeStatus'] == 1){
                    $headName ="<font color='green'>Transport Fee</font>";
                    $transportFeePaid =1;
                }
            
                    */
                    $totalAlreadyPaid +=(float) $paidAmount;
                    
                    $amount = $studentFeeArray[0]['transportFees'];
                    $totalFees += $amount;
                    $ttPrevPaid = "<br/><span align='right' style='display:none; font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>$lblName$paidAmount</span>";
                    $applAmt="<input type='text'  name='transportFees' id='transportFees' value='".$toBePaid."' maxlength='8' style='width:70px' class='inputbox2'  onblur='checkValue(this.value,this.id);'><input type='hidden' name='transportFeeInstrument' value='$toBePaid' >$ttPrevPaid";
                    feeTypeValueFormat($count,$headName,$amount,$applAmt);
                    $count++;
            }
        }
        
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
        
           // ledger Data 
           for($j=0;$j<count($ledgerDataArray);$j++){
                $comments = '';
                $amount='';
                $alreadyPaid = 0;
                $comments = substr(trim(chunk_split(ucwords($ledgerDataArray[$j]['comments']),50,"<br/>")),0,-5);
                if($ledgerDataArray[$j]['debit'] > 0){
                    
                        $amount = number_format((float)$ledgerDataArray[$j]['debit'], 2, '.', '');
                    $negativeDebitLedgerAmount +=$ledgerDataArray[$j]['debit'];
                            
                    //$totalFees += $negativeDebitLedgerAmount;
                    $ttPrevPaid = "<br/><span align='right' style='display:none;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>$lblName$amount</span>";
                     $toBePaid = $amount - $alreadyPaid ;
                    $applAmt="<input type='text'  name='ledger' id='ledger' value='".$toBePaid."' maxlength='8' style='width:70px' class='inputbox2'  onblur='checkValue(this.value,this.id);'><input type='hidden' name='ledger' value='' >$ttPrevPaid";
                }
                else{
                    $amount = "-".number_format((float)$ledgerDataArray[$j]['credit'], 2, '.', '');
                    //$applAmt= "---";
                
                            $negativeLedgerAmount += $ledgerArray[$j]['credit'];
                            $applAmt = $amount;
                
                }
                
                $headName = $comments;
                /*if($ledgerDataArray[$j]['status']== 1){
                    $headName = "<font color='green'>".$comments."</font>"; 
                    $acdmicFeePaid =1;
                }
                */

            
                 $totalFees += $amount;
                feeTypeValueFormat($count,$headName,$amount,$applAmt);
                $count++;
            }
            
                
       
    
        if($feeType == 4 || $feeType == 1){
            $headName = "Fine";
            $remaingAmt =0;
            $prevTotalPaid = "<br/><span align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>Total Fine:&nbsp;".number_format((float)$FeePaidArray[0]['academicFine'], 2, '.', '')."</span>";
                $amount = "<input type='text'  name='appliedFine'  id='appliedFine' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);' onchange='claculatePayableAmount();'>$prevTotalPaid";
                $ttPrevPaid = "<br/><span align='right' style='display:none; font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>$lblName".number_format((float)$FeePaidArray[0]['academicFinePaid'], 2, '.', '')."</span>";
                $remaingAmt = $FeePaidArray[0]['academicFine'] - $FeePaidArray[0]['academicFinePaid'];
                if($remaingAmt== 0){
                $remaingAmt = '';
                }
                $applAmt = "<input type='text'  name='finePaid' value='$remaingAmt' id='finePaid' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);'>$ttPrevPaid";
                feeTypeValueFormat($count,$headName,$amount,$applAmt);
                $count++;
        }
        
        // for Transport Fine
        if((($busRouteId !='' && $busStopId != '') && ($busRouteId !=0 && $busStopId != 0)) && ($feeType == 2 || $feeType == 4)){
            $headName = "Transport Fine";
            $remaingAmt = 0;
            $prevTotalPaid = "<br/><span align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>Total Transport Fine:&nbsp;".number_format((float)$FeePaidArray[0]['tranportFine'], 2, '.', '')."</span>";
                $amount = "<input type='text'  name='appliedTransportFine'  id='appliedTransportFine' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);' onchange='claculatePayableAmount();'>$prevTotalPaid";
                $ttPrevPaid = "<br/><span align='right' style='display:none; font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>$lblName".number_format((float)$FeePaidArray[0]['transportFinePaid'], 2, '.', '')."</span>";
                $remaingAmt = $FeePaidArray[0]['tranportFine'] - $FeePaidArray[0]['transportFinePaid'];
                if($remaingAmt== 0){
                    $remaingAmt = '';
                }
                $applAmt = "<input type='text'  name='transportFinePaid'  value='$remaingAmt' id='transportFinePaid' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);'> $ttPrevPaid";
                feeTypeValueFormat($count,$headName,$amount,$applAmt);
                $count++;
            
        }
        
        // for Hostel Fine
        if((($hostelId !='' && $hostelRoomId != '') && ($hostelId !='' && $hostelRoomId != '')) && (($feeType == 3 || $feeType == 4) && ($studentFeeArray[0]['hostelFees'] > 0))){
            $headName = "Hostel Fine";
            $remaingAmt = 0;
            $prevTotalPaid = "<br/><span align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>Total Hostel Fine:&nbsp;".number_format((float)$FeePaidArray[0]['hostelFine'], 2, '.', '')."</span>";
                $amount = "<input type='text'  name='appliedHostelFine'  id='appliedHostelFine' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);' onchange='claculatePayableAmount();'>$prevTotalPaid";
                $ttPrevPaid = "<br/><span align='right' style='display:none; font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>$lblName".number_format((float)$FeePaidArray[0]['hostelFinePaid'], 2, '.', '')."</span>";
                $remaingAmt = $FeePaidArray[0]['hostelFine'] - $FeePaidArray[0]['hostelFinePaid'];
                if($remaingAmt== 0){
                    $remaingAmt = '';
                }
                $applAmt = "<input type='text'  name='hostelFinePaid' value='$remaingAmt' id='hostelFinePaid' maxlength='8' style='width:70px' class='inputbox2' onblur='checkValue(this.value,this.id);'>$ttPrevPaid";
                feeTypeValueFormat($count,$headName,$amount,$applAmt);
                $count++;
        }
        
       
        if($feeType == 4 || $feeType == 1){
            // Mis Prev Data
               foreach($miscellaneousHeadArr as $key => $value){
                   if($value['headName'] != ''){
                       feeTypeValueFormat($count,$value['headName'],$value['amount'],$value['applAmt']);
                        $count++;
                    }
               }
         
        
           // Misc Data New Heads
            $miscHeadArr = array();
           //to avoid miscelleneous head 
           //$miscHeadArr = $CollectFeesManager->getMiscFeeHead($instituteId);
           foreach($miscHeadArr as $key => $value){
               // if not in fee head array 
               if(!array_search($value['feeHeadId'], $feeHeadIdArr)){ 
                   $headName = "<font color='blue'>".$value['headName']."</font><input type='hidden' name=miscHeadData[] id='miscData$count' value='".$feeReceiptId."-".$feeClassId."-".$studentId."-".$value['feeHeadId']."-".$value['headName']."'>";
                   $amount ="<input type='text'  name='miscHeadAmt[]' id='miscAppl$count'  maxlength='8' style='width:70px' class='inputbox2'  onblur='checkValue(this.value,this.id);' onchange='claculatePayableAmount();'>";
                   $ttPrevPaid = "<br/><span align='right' style='display:none; font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:red;'>$lblName".number_format((float)0, 2, '.', '')."</span>";
                   $applAmt="<input type='text'  name='academicFee[]' id='academicFee$count'  maxlength='8' style='width:70px' class='inputbox2'  onblur='checkValue(this.value,this.id);'><input type='hidden' name='feeHeadId[]' value='".$value['feeHeadId']."'>$ttPrevPaid";
                   feeTypeValueFormat($count,$headName,$amount,$applAmt);
                    $count++;
               }
           }
        }

        // to get Details of cash/check/DD payments 
        // Not Needed Now may be used later on 
        /*$paymentData = '';
        $hideAddRow = 0;
        $cashCheckDetailArr = $CollectFeesManager->getPaymentDetails($feeReceiptId,$studentId,$feeClassId,$feeType);
        
        if(count($cashCheckDetailArr) > 0){
            $paymentData .="<table border=0 cellspacing=1 width='100%' cellpadding=0 >";    
                $j=1;
            foreach($cashCheckDetailArr as $subArray){
                if($subArray['number'] == ''){
                    $subArray['number'] = "---";
                }
                if($subArray['bankAbbr'] == ''){
                    $subArray['bankAbbr'] = "---";
                }

                $class = 'row0';
                if($j%2 == 0){
                    $class = 'row1';
                }
                $paymentData .="<tr class=$class ><td style='padding-top:5px' width='3%'>$j</td>";
                $paymentData .="<td class='padding_top' width='15%' style='padding-left:10px;'>".$paymentModeArray[$subArray['paymentMode']]."</td>";
                $paymentData .="<td  align='right' class='padding_top' width='10%' style='padding-right:10px;'>".$subArray['number']."</td>";
                $paymentData .="<td  style='padding: 0px 10px 0px 0px;' align='right' class='padding_top' width='10%' >".$subArray['amount']."</td>";
                $paymentData .="<td class='padding_top' width='15%' style='padding-left:10px;'>".$subArray['bankAbbr']."</td>";
                if($subArray['paymentMode'] == 1){
                    $paymentData .="<td class='padding_top' width='13%' align='center'>---</td>";
                }
                else{
                    $paymentData .="<td class='padding_top' width='13%' align='center'>".date('d-m-Y',strtotime($subArray['dated']))."</td>";
                }
                $paymentData .="<td align='right' class='padding_top' width='10%' style='padding-right:10px;'>".$subArray['receiptNo']."</td>";
                $paymentData .="<td align='center' class='padding_top' width='13%'>".date('d-m-Y',strtotime($subArray['receiptDate']))."</td>";
                $paymentData .="<td align='right' class='padding_top' width='15%'>".$subArray['installmentNo']."</td></tr>";
                ++$j;
            }
            $paymentData .="</table>";
            $hideAddRow =1; // if payment is already done hide add row button
        }
        */
        $caption = '';
        $fineData = '';
		
		$totalAmount = $totalFees;
        if($feeType == 4){
            if($isOnlineFeeType == 4) {
               $totalAlreadyPaid += (float) $isOnlineReceivePayment;    
            }
            
            $caption = "Total Fees";
            if($FeePaidArray[0]['academicFine'] > 0){
                $fineData = "<tr><td valign='middle' class='padding_top' style='padding-left:7px;'>Fine</td><td valign='middle' width='1%' class='padding_top'><b>:</b></td><td valign='middle'  class='padding_top' align='right'>".number_format(doubleval($FeePaidArray[0]['academicFine']), 2, '.', '')."</td></tr>";
                $totalFees += $FeePaidArray[0]['academicFine'];
                $totalAlreadyPaid +=$FeePaidArray[0]['academicFinePaid'];
            }
            if($FeePaidArray[0]['hostelFine'] > 0){
                $fineData .= "<tr><td valign='middle' class='padding_top' style='padding-left:7px;'>Hostel Fine</td><td valign='middle' width='1%' class='padding_top'><b>:</b></td><td valign='middle'  class='padding_top' align='right'>".number_format(doubleval($FeePaidArray[0]['hostelFine']), 2, '.', '')."</td></tr>";
                $totalFees += $FeePaidArray[0]['hostelFine'];
                $totalAlreadyPaid +=$FeePaidArray[0]['hostelFinePaid'];
            }
            if($FeePaidArray[0]['tranportFine'] > 0){
                $fineData .= "<tr><td valign='middle' class='padding_top' style='padding-left:7px;'>Transport Fine</td><td valign='middle' width='1%' class='padding_top'><b>:</b></td><td valign='middle'  class='padding_top' align='right'>".number_format(doubleval($FeePaidArray[0]['tranportFine']), 2, '.', '')."</td></tr>";
                $totalFees += $FeePaidArray[0]['tranportFine'];
                $totalAlreadyPaid +=$FeePaidArray[0]['transportFinePaid'];
            }
        }
        else if($feeType == 1){
            $caption = "Total Academic Fees";
            if($FeePaidArray[0]['academicFine'] > 0){
                $fineData = "<tr><td valign='middle' class='padding_top' style='padding-left:7px;'>Fine</td><td valign='middle' width='1%' class='padding_top'><b>:</b></td><td valign='middle'  class='padding_top' align='right'>".number_format(doubleval($FeePaidArray[0]['academicFine']), 2, '.', '')."</td></tr>";
                $totalFees += $FeePaidArray[0]['academicFine'];
                $totalAlreadyPaid +=$FeePaidArray[0]['academicFinePaid'];
            }
        }
        else if($feeType == 2 ){
            $caption = "Total Transport Fees";
            if($FeePaidArray[0]['tranportFine'] > 0){
                $fineData = "<tr><td valign='middle' class='padding_top' style='padding-left:7px;'>Transport Fine</td><td valign='middle' width='1%' class='padding_top'><b>:</b></td><td valign='middle'  class='padding_top' align='right'>".number_format(doubleval($FeePaidArray[0]['tranportFine']), 2, '.', '')."</td></tr>";
                $totalFees += $FeePaidArray[0]['tranportFine'];
                $totalAlreadyPaid +=$FeePaidArray[0]['transportFinePaid'];
            }
        }
        else if($feeType == 3){
            $caption = "Total Hostel Fees";
            if($FeePaidArray[0]['hostelFine'] > 0){
                $fineData = "<tr><td valign='middle' class='padding_top' style='padding-left:7px;'>Hostel Fine</td><td valign='middle' width='1%' class='padding_top'><b>:</b></td><td valign='middle'  class='padding_top' align='right'>".number_format(doubleval($FeePaidArray[0]['hostelFine']), 2, '.', '')."</td></tr>";
                $totalFees += $FeePaidArray[0]['hostelFine'];
                $totalAlreadyPaid +=$FeePaidArray[0]['hostelFinePaid'];
            }
        }
        $payableAmount =0;
        

      

        if(count($studentFeeArray) > 0){    
            $amountAfterConcession = $totalFees - $concessionAmount;
            if($totalAlreadyPaid>0 && $payableAmount==0){
            $totalAlreadyPaid -= $negativeLedgerAmount;            
            $totalAlreadyPaid +=$negativeDebitLedgerAmount;
			$totalAlreadyPaid +=$prevAllClassAmount;
            }
            $payableAmount = $totalFees - ($totalAlreadyPaid + $concessionAmount);
            $payableAmount = number_format($payableAmount, 2, '.', '');
        $prevClassDetails ='';	
		 if($feeType == 1 || $feeType == 4){
		 	if($isPrevPrint==$classId){    
			if($prevAllClassAmount==''){
				
				$prevClassDetails .= '';	
			} else{				
			$prevClassDetails.="<td valign='middle' class='padding_top' style='padding-left:7px;'><font color='red'>Prev.Class Balance</font></td><td valign='middle' width='1%' class='padding_top'><b><font color='red'>:</font></b></td><td valign='middle'  class='padding_top' align='right'><font color='red'>".number_format($prevAllClassAmount, 2, '.', '')."</font></td></tr><tr><td valign='middle' colspan='3' class='padding_top' style='padding-left:7px;'><font color='red'>Prev. Details For (".$prevAllClassComment.")</font></td>";
				//$totalAmount +=$prevAllClassAmount;
				$amountAfterConcession +=$prevAllClassAmount;
				$payableAmount +=$prevAllClassAmount;
			}
		 }
	 } 
            $payFeeTotalData = "<table border='0' cellspacing='0' cellpadding='0'><tr>$prevClassDetails</tr><tr><td valign='middle' class='padding_top' style='padding-left:7px;'>$caption</td><td valign='middle' width='1%' class='padding_top'><b>:</b></td><td valign='middle'  class='padding_top' align='right'>".number_format($totalAmount, 2, '.', '')."</td></tr>$fineData<tr><td valign='middle' class='padding_top' style='padding-left:7px;'>Concession</td><td valign='middle' class='padding_top'><b>:</b></td><td valign='middle' align='right' class='padding_top'><nobr>-".number_format($concessionAmount, 2, '.', '')."</td></tr><tr><td valign='middle' class='padding_top' style='padding-left:7px;'><font color='green'>Payable Amount</font></td><td valign='middle' width='1%' class='padding_top'><b><font color='green'>:</font></b></td><td valign='middle' align='right' class='padding_top'><font color='green'> ".number_format($amountAfterConcession, 2, '.', '')."</font></td></tr><tr><td class='padding_top' style='padding-left:7px;'><font color='blue'>Already Paid</font></td><td class='padding_top'><b><font color='blue'>:</font></b></td><td class='padding_top' align='right'><font color='blue'>".number_format($totalAlreadyPaid, 2, '.', '')."</font></td><tr><td class='padding_top' style='padding-left:7px;'> <font color='red'>Balance</font></td><td class='padding_top'><b><font color='red'>:</font></b></td><td class='padding_top' align='right'><font color='red'>".$payableAmount."</font></td></tr></table>";
      }
        
        if($busRouteId == ''){
            $busStopId = 0;
            $busRouteId =0;
        }
    
      echo '{"payFeeTotalData" :"'.$payFeeTotalData.'","busStopId":"'.$busStopId.'",
             "busRouteId":"'.$busRouteId.'","studentinfo" : ['.$json_student.'],"info" : ['.$json_val.'],
             "feeInfo" :['.$feePaid_val.'],"paymentDetails" :"'.$paymentData.'","bankScrollNo":"'.$bankScrollNo.'",
             "receiptDate":"'.$receiptDate.'","hideAddRow":"'.$hideAddRow.'","feeCycleName":"'.$feeCycleName.'",
             "feeCycleId" : "'.$feeCycleId.'","rollNo":"'.$rollNo.'","hostelSecurityStatus":"'.$hostelSecurityStatus.'",
             "installmentNo":"'.$installmentNo.'","payableAmount":"'.$payableAmount.'","negativeLedgerAmount":"'.$negativeLedgerAmount.'",
             "negativeDebitLedgerAmount":"'.$negativeDebitLedgerAmount.'","concessionAmount":"'.$concessionAmount.'",
             "prevAllClassAmount":"'.$prevAllClassAmount.'"}';
      die; 
    }
    else{
        echo $errorMessage; die;
    }
  
    function feeTypeValueFormat($srNo,$head,$amount,$applAmt) {
        global $feePaid_val; 
        $head = strtoupper($head);    
        $valueArray1 = array_merge(array('srNo' => $srNo,
                            'headName' =>$head,
                            'amount' =>$amount,
                            'applAmt'=>$applAmt));  
        
        if(trim($feePaid_val)=='') {
            $feePaid_val = json_encode($valueArray1);
        }
        else {
            $feePaid_val .= ','.json_encode($valueArray1);           
        }                                    
    } 
    
    
    function getGenerateFee($feeClassId,$studentId) {
         
         global $generateFeeManager;
         global $commonQueryManager;
         global $feeHeadManager;
         global $sessionHandler;
         
         if($feeClassId=='') {
           $feeClassId='0';
         }
         
         if($studentId=='') {
           $studentId='0';
         }
         
         $ttFeeClassId=  $feeClassId;
         
         $userId = $sessionHandler->getSessionVariable('UserId');
         $errorMessage =''; 
         
         
         $feeCycleCondition = " classId = '$feeClassId' ";
         $feeCycleArray = $generateFeeManager->checkStudentFeeCycle($feeCycleCondition);
         if(count($feeCycleArray) > 0){
            $feeCycleId = $feeCycleArray[0]['feeCycleId'];
         }
    
         // to fetch Current class of student
         $classArray = $generateFeeManager->getClass($feeClassId);
         if(count($classArray) == 0){
           return  "Class Not Found";
        }
        
        // Fetch the all Classes 
        $classes = '';
        foreach($classArray as $key => $value){
          if($classes == ''){
            $classes = $value['classId'];
          }
          else{
            $classes .= ",".$value['classId'];
          }
        } 
        $feeStudyPeriodId = $classArray[0]['feeStudyPeriodId'];
    
    
        if(SystemDatabaseManager::getInstance()->startTransaction()){
            
                // To Delete old fee heads
                $oldFeeHeadDelete = $generateFeeManager->checkStudentFeeHeadDelete($studentId,$ttFeeClassId);
                if($oldFeeHeadDelete===false) {
                   echo FAILURE;
                   die;
                }
            
     // Fetch Migration Fee  Start
                $migrationArray = $generateFeeManager->getCheckStudentMigration($studentId);
                if(count($migrationArray) > 0 && is_array($migrationArray)) {
                  $ttIsMigrationId=$migrationArray[0]['migrationStudyPeriod'];
                }
                if($ttIsMigrationId=='') {
                  $ttIsMigrationId='0';  
                }
                
                $ttPeriodValue='-1';  
                if($ttIsMigrationId>0) {
                   $migrationPeriodArray = $generateFeeManager->getMigrationStudyPeriod($feeClassId);
                   $ttPeriodValue = $migrationPeriodArray[0]['periodValue'];
                   if($ttPeriodValue=='') {
                     $ttPeriodValue='-1';  
                   }
                }
                if($ttIsMigrationId==$ttPeriodValue) {
                  $ttIsMigrationId=1; 
                }
                else {
                  $ttIsMigrationId=0;   
                }
            // Migration Fee END  

            // to Get Student Details
            $condition1 = " AND studentId = '$studentId' ";
            $condition2 = " AND stu.studentId = '$studentId' ";
            $studentDataArray = $generateFeeManager->getStudentDetailsNew($classes,$condition1,$condition2);
            if(count($studentDataArray) == 0 || !is_array($studentDataArray)) {
               return "Students Not Found";  
            }

            $j=1;
            foreach($studentDataArray as $key =>$studentArr) {
                $currentClass = $studentArr['classId'];  
                $instituteId =  $studentArr['instituteId'];  
                $instituteAbbr =  $studentArr['instituteAbbr'];  
                
                $concession = '';
                $hostelFees='';
                $transportFees='';
                $busRouteId   = '';
                $busStopId = '';
                $feeReceiptId = '';
                $totalAcademicFee =0;
                $hostelSecurity = 0;
             // to get Student Concession                   
                $adhocCondition =" acm.feeClassId = '".$feeClassId."' AND acm.studentId = '".$studentArr['studentId']."'"; 
                $adhocArray=$generateFeeManager->getStudentAdhocConcessionNew($adhocCondition);
                $concession = $adhocArray[0]['adhocAmount']; // concession Amount
                $isMigration ='-1';
                if($studentArr['isMigration'] == 1  && $ttIsMigrationId == 1){
                  $isMigration = 3;
                }
                 // to get Student Fee Heads
                 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
                 if(count($foundArray) == 0){
                    return FEE_HEAD_NOT_DEFINE;
                 }
                 
                 $feeArray = array();
                 $applicableHeadId = array();
                 $index = array();
                
                 // code to find only applicable Head Value 
                 foreach($foundArray as $key =>$subArray) {
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                           $flag1 = true; // used for filtering purpose
                       }  
                    $flag= true;
                    foreach($foundArray as $key1 =>$subArray1){
                           if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 3)&& (($subArray['quotaId'] == $studentArr['quotaId']) && $subArray['isLeet'] == $isMigration)){
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 1) && (($subArray['quotaId'] == $studentArr['quotaId']) && ($subArray['isLeet'] == $studentArr['isLeet']))){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $flag1 == true) && (($subArray['quotaId'] == 0) && $subArray['isLeet'] == $isMigration)){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && !in_array($subArray['feeHeadId'],$applicableHeadId)) && (($subArray['quotaId'] == $studentArr['quotaId']) || (($subArray['isLeet'] == $studentArr['isLeet']) || $subArray['isLeet'] == $isMigration))){ 
                               $applicableHeadId[] = $subArray['feeHeadId'];
                               $index[] = $key;
                               break;
                           
                           }
                    }              
                  }
          
                  $applicableHeadId = array_unique($applicableHeadId); 

                  // to put other heads 
                  foreach($foundArray as $key =>$subArray){
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                        $feeArray[$key] = $foundArray[$key];
                    }
                  }
                  // to insert aplicable head at there place
                  $index = array_unique($index);
                  foreach($index as $key =>$value){
                    $feeArray[$value] = $foundArray[$value];
                  }
                  // this is done to mantain the order of fee it stores the key
                  $indexArr = array();
                  foreach($feeArray as $key =>$value){
                    $indexArr[] = $key;
                  }
                  sort($indexArr); // to sort the index
            
                   $studentId = $studentArr['studentId'];
                $feeReceiptId = '';
                $feeReceiptArray= $generateFeeManager->getFeeMasterId($studentId,$feeClassId);
                if(count($feeReceiptArray) > 0 ) {
                  $feeReceiptId = $feeReceiptArray[0]['feeReceiptId'];
                }
                $status = $generateFeeManager->insertIntoFeeMaster($studentId,$currentClass,$feeClassId,$feeCycleId,$concession);
                if($status === FALSE){
                    return FALIURE;
                }
                if($feeReceiptId=='') {
                  $feeReceiptId=SystemDatabaseManager::getInstance()->lastInsertId();
                }
                
                $cnt = count($indexArr);
                $instrumentValues = '';
                for($i=0;$i<$cnt; $i++){
                    //feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus
                    if($feeArray[$indexArr[$i]]['feeHeadAmt'] > 0) {
                        if($instrumentValues != ''){
                            $instrumentValues .=", ";
                        }
                        $instrumentValues .="('','$feeReceiptId','$studentId','$feeClassId','".$feeArray[$indexArr[$i]]['feeHeadId']."','".ucwords($feeArray[$indexArr[$i]]['headName'])."','".$feeArray[$indexArr[$i]]['feeHeadAmt']."',0)";
                        $totalAcademicFee += floatval($feeArray[$indexArr[$i]]['feeHeadAmt']);
                        $totalAcademicFee = " ".$totalAcademicFee;
                    }
                }
        
                $status1 = $generateFeeManager->insertIntoReceiptInstrument($instrumentValues);
                if($status1 === FALSE){
                    return FALIURE;
                }
                $j++;
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                $msg = SUCCESS; 
            }
            else {
               $msg = FAILURE;
            }
        }
        else {
          $msg = FAILURE;
        }
        return $msg; 
    }
    
?>
