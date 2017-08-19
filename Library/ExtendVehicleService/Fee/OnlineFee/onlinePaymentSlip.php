<?php
       
    set_time_limit(0);  
  
	require_once(BL_PATH . '/NumToWord.class.php'); 
        
    require_once(MODEL_PATH . "/Fee/OnlineFeeManager.inc.php");
    $onlineFeeManager = OnlineFeeManager::getInstance();
    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
	$collectStudentFeeManager = CollectStudentFeeManager::getInstance(); 
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();   
    global $sessionHandler; 
    $sessionHandler->setSessionVariable('IdToOnlieFeeReceipt',''); 

	$imageButton2 = '<input type="image"  src="'.IMG_HTTP_PATH.'/home.gif" onClick=" getHome(); return false;" title="HOME">';

    $order_id = htmlentities(trim($responseData['orderId']));

    $isOnlineStatus = '0';
    $isPrintSlip='0';
		
        if($responseData['Status']=='2'){
				
				if(SystemDatabaseManager::getInstance()->startTransaction()) { 
					$queryMerchantDetails = " ('".$responseData['TxnReferenceNo']."','".$responseData['response']."','2') ";
										   
					$returnMerchantArray = $onlineFeeManager->insertMerchantFeeDetail($queryMerchantDetails);
					
					if($returnMerchantArray===false) {
						$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
						   
						header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die; 
					}
					if(!SystemDatabaseManager::getInstance()->commitTransaction()) {
							$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
							header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
							
					}
				}
				else{
					$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
					header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
				$updateCode='TxnReferenceNo = "'.$responseData['TxnReferenceNo'].'" , isStatus = "2"';
				$orderId=$studentTransactionArray[0]['orderId'];
				$studentId=$studentTransactionArray[0]['studentId'];
				if(SystemDatabaseManager::getInstance()->startTransaction()) { 
					$update=$onlineFeeManager->updateOnlineTransaction($updateCode,$orderId,$studentId);
					if($update===false) {
						$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM); 
						header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
					}
					if(!SystemDatabaseManager::getInstance()->commitTransaction()) {
						$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
						header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
							
					}
						
    			}
				else{
					$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
					header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
			 
        }
		else{
			// Student transaction detail Fetch 
			$condition = " CONCAT(of.orderId,LPAD(of.studentId,6,'0')) = '$order_id' ";
            
			$studentTransactionArray = $onlineFeeManager->getTransactionList($condition);
			
			
            if(count($studentTransactionArray) == 0 || !is_array($studentTransactionArray)) {
               
				$sessionHandler->setSessionVariable('onlineTransaction',Invalid_Transaction);
				header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
				die;    
            }
			else{
				
				if($studentTransactionArray[0]['isStatus']==0&&$studentTransactionArray[0]['paidAmount']==$responseData['TxnAmount']){
					$updateCode='TxnReferenceNo = "'.$responseData['TxnReferenceNo'].'" , isStatus = "1"';
					$orderId=$studentTransactionArray[0]['orderId'];
					$studentId=$studentTransactionArray[0]['studentId'];
					if(SystemDatabaseManager::getInstance()->startTransaction()) { 
						$update=$onlineFeeManager->updateOnlineTransaction($updateCode,$orderId,$studentId);
						if($update===false) {
							$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);   
							header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
							die; 
						}
						if(!SystemDatabaseManager::getInstance()->commitTransaction()) {
							$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
							header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
							die;
							
						}
						
    				}
					else{
						$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
						header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
						die;
					}
				}	
				else{
						$sessionHandler->setSessionVariable('onlineTransaction',Invalid_Transaction);
						
						header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
			}
			
			if(SystemDatabaseManager::getInstance()->startTransaction()) { 
				$queryMerchantDetails = " ('".$responseData['TxnReferenceNo']."','".$responseData['response']."','1') ";
										   
				$returnMerchantArray = $onlineFeeManager->insertMerchantFeeDetail($queryMerchantDetails);
				
				if($returnMerchantArray===false) {
					$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);     
					header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
				if(!SystemDatabaseManager::getInstance()->commitTransaction()) {
							$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
							header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
							
				}
    		}
			else{
				$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
				header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
			}
			
				
				
		}	
		
			
		$rollNo=$sessionHandler->getSessionVariable('RollNo');
		$feeClassId=$studentTransactionArray[0]['feeClassId'];
		$feeTypeId=$studentTransactionArray[0]['feeType'];
		$studentId=$studentTransactionArray[0]['studentId'];
		$onlinePaidAmt=$studentTransactionArray[0]['paidAmount'];
		$totalPaidAmt=$onlinePaidAmt;
		// Findout Student Details
		$condition = " AND (stu.rollNo='".$rollNo."' OR stu.regNo='".$rollNo."' OR stu.universityRollNo='".$rollNo."') ";
		$studentFeesArray = $collectStudentFeeManager->getStudentDetailClass($condition,$feeClassId);     
		
		if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {
			if($studentFeesArray[0]['feeClassId']==-1) {
				$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
				header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
			}
		}  
		else {
			$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
			header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
		}
    
		$studentCurrentStatus = "(Active)"; 
		$isDelete = $studentFeesArray[0]['isDelete']; 
		if($isDelete=='0') {
			if($studentFeesArray[0]['studentStatus']==0) {
				$studentCurrentStatus = "(Inactive)";  
			}
		}
		else {
			$studentCurrentStatus = "(Deleted)";  
		}
    
		$studentId = $studentFeesArray[0]['studentId'];  
		$quotaId = $studentFeesArray[0]['quotaId'];  
		$isLeet = $studentFeesArray[0]['isLeet'];  
    
		$isMigration = $studentFeesArray[0]['isMigration'];  
		$migrationClassId = $studentFeesArray[0]['migrationClassId'];  
     
		$universityId = $studentFeesArray[0]['universityId'];
		$batchId = $studentFeesArray[0]['batchId'];
		$degreeId = $studentFeesArray[0]['degreeId'];
		$branchId = $studentFeesArray[0]['branchId'];
		$studyPeriodId = $studentFeesArray[0]['studyPeriodId'];
		$feeStudyPeriodId = $studentFeesArray[0]['feeStudyPeriodId'];
    
		$tIsLeet=2; 
		if($isLeet==1) {
			$tIsLeet=1;  
		}
    
		// Check Adhoc Concession 
		$adhocConcession=0; 
		$adhocCondition = " feeClassId = '$feeClassId' AND studentId = '$studentId' "; 
		$adhocConcessionArray = $collectStudentFeeManager->getCheckStudentConcession($adhocCondition); 
		if(is_array($adhocConcessionArray) && count($adhocConcessionArray)>0) {
			$adhocConcession = 1; 
			$conessionFormatId = 4;
		}  
    
		$ttPaidReceiptId = -1; 
		$applFinalArray = $collectStudentFeeManager->getStudentPaidReceipt($feeClassId,$studentId); 
		for($i=0;$i<count($applFinalArray);$i++) {
			$ttPaidReceiptId .=",". $applFinalArray[$i]['feeReceiptId'];
		}
    
// ============ Fetch student Current Class Transport and Hostel Facility Check  (START) ==========================
        $studentFeesArray[0]['transportFacility'] =0; 
        $studentFeesArray[0]['hostelFacility'] = 0; 
        if($feeTypeId == '4' || $feeTypeId == '2') {     // Transport Check
            $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND IFNULL(fsf.facilityType,'') = 1 ";    
            $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
            if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                $studentFeesArray[0]['transportFacility']=1;
                $transportFacility = 1; 
                $trCharges = $facilityArrayCheck[0]['charges'];
                $trConcession = $facilityArrayCheck[0]['concession'];
                 
                $prevApplTransport =0;
                $condition  = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";    
                $previousTransportArray = $collectStudentFeeManager->getPreviousTransportPaymentDetail($condition); 
                if(is_array($previousTransportArray) && count($previousTransportArray)>0 ) { 
					$prevTransportCharges=$trCharges-$trConcession;
                    $prevTransportFine=$previousTransportArray[0]['transportPrevFine'];
                    $prevTransportPaid=$previousTransportArray[0]['transportPrevPaid'];
                    $prevTransportDues=($prevTransportCharges+$prevTransportFine)-$prevTransportPaid; 
                    $prevApplTransport= $previousTransportArray[0]['transportApplPaid'];
                }
            }
        }
     
        if($feeTypeId == '4' || $feeTypeId == '3') {    // Hostel Check  
            $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND IFNULL(fsf.facilityType,'') = 2 ";                    
            $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
            if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                $hostelFacility = 1;
                $studentFeesArray[0]['hostelFacility']=1;
                $hrCharges = $facilityArrayCheck[0]['charges'];
                $hrConcession = $facilityArrayCheck[0]['concession'];
                 
                $prevApplHostel =0;
                $condition = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";   
                $previousHostelArray = $collectStudentFeeManager->getPreviousHostelPaymentDetail($condition); 
                if(is_array($previousHostelArray) && count($previousHostelArray)>0 ) { 
                    $prevHostelCharges=$hrCharges-$hrConcession;
                    $prevHostelFine=$previousHostelArray[0]['hostelPrevFine'];-
                    $prevHostelPaid=$previousHostelArray[0]['hostelPrevPaid'];
                    $prevHostelDues=($prevHostelCharges+$prevHostelFine)-$prevHostelPaid;
                    $prevApplHostel= $previousHostelArray[0]['hostelApplPaid'];   
                }   
            }
        }
     // ============ Fetch student Current Class Transport and Hostel Facility Check  (END) ==========================    
     
  
      // ============= Fetch Student Fee Class Installment and payment Detail  (START) =======================    
        $condition = " f.studentId = '$studentId' AND f.feeClassId = '$feeClassId'";
        $cond = $condition." AND f.receiptStatus NOT IN (4) ";
        $foundArray = $collectStudentFeeManager->getCountInstallment($cond);   
        $studentInstallmentCount = $foundArray[0]['cnt']+1;
         
        $conditionPrev =  $condition." AND f.receiptStatus NOT IN (3,4) ";
        $foundArray = $collectStudentFeeManager->getPreviousFeePaymentDetail($conditionPrev);  
        if(is_array($foundArray) && count($foundArray)>0 ) {
            for($i=0; $i <count($foundArray); $i++) {
              $previousPaymentCurr += $foundArray[$i]['prevFeePaid'];  
              $previousFineCurr += $foundArray[$i]['prevFeeFine'];  
            }
        }
     // ============= Fetch Student Fee Class Installment and payment Detail  (END) =======================
     
     
     // =========== Fetch Student Fee Head Wise Detail (START) ==============================
             $condition='';
             $feeHeadDetailFind=0;                   
             $feeAmtPaidTotal=0;
             
             $showTFeeAmtPaid = 0;
             $showTTransportAmtPaid = 0;
             $showTHostelAmtPaid = 0;
             $showTDuesAmtPaid = 0;
                   
        
         // ======== Prev Dues START ===========
        $prevCondition = " AND fsf.studentId = '$studentId' AND fsf.classId <= '$feeClassId' ";  
        $prevClassFeeArray = $collectStudentFeeManager->getPendingDuesList($prevCondition);  
            
           
        $prevClassArray = array();
        $prevPaidArray = array();
        $prevDueArray =  array();
		
		
        for($i=0; $i<count($prevClassFeeArray); $i++) {
            if($prevClassFeeArray[$i]['dues'] != $prevClassFeeArray[$i]['paid']) {
                   
                $duesClassId = $prevClassFeeArray[$i]['classId'];
                  
                $duesAmt = $prevClassFeeArray[$i]['dues']; 
                $paidAmt = $prevClassFeeArray[$i]['paid']; 
                  
                if($duesAmt=='') {
					$duesAmt=0;  
                }
                if($paidAmt=='') {
					$paidAmt=0;  
                }
                
				
				
                $dif = doubleval($duesAmt) - doubleval($paidAmt); 
                if($onlinePaidAmt!=0){
					if($onlinePaidAmt>=$dif){
						$onlinePaidAmt=$onlinePaidAmt-$dif;
						$prevPaidArray[$i]=$dif;
						$prevClassArray[$i] = $duesClassId;
						$prevDueArray[$i] =  0;
					}
					else{
						$prevDueArray [$i]=$dif-$onlinePaidAmt; 
						$prevPaidArray[$i]=$onlinePaidAmt;
						$prevClassArray[$i] = $duesClassId;
						$onlinePaidAmt=0;
						
					}
					
				}
				else{
						$prevDueArray [$i]=$dif-$onlinePaidAmt; 
						$prevClassArray[$i] = $duesClassId;
						$prevPaidArray[$i] =0;
												
				}
                $showTDuesAmtPaid += doubleval($duesAmt) - doubleval($paidAmt); 
                
                $concession="";
                  
            }
        }
         // ======== Prev Dues END ===========
     
        $feePaidArray = array();
		$feeDueArray = array();
		$feeHeadArray = array();

                   
        if($feeTypeId == '4' || $feeTypeId == '1') {           // Only Academic
            
            $feeId = "-1";
            $havingConditon = " COUNT(fhv.feeHeadId) = 1 "; 
            $foundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,$havingConditon);
            for($i=0; $i<count($foundArray); $i++) {
				$feeId .=",".$foundArray[$i]['feeId'];  
            }        
            
            $havingConditon = " COUNT(fhv.feeHeadId) >= 2"; 
            $isLeetCheck = "1,2,3";
            $foundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,$havingConditon,'',0,$isLeetCheck); 
            for($i=0; $i<count($foundArray); $i++) {
				$tFeeHeadId = $foundArray[$i]['feeHeadId']; 
				if($quotaId!='') {
					$feeHeadCondition = " AND fhv.quotaId = $quotaId AND fhv.feeHeadId = $tFeeHeadId";  
					$quotaFoundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
					if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
						$feeId .=",".$quotaFoundArray[0]['feeId'];  
					}
					else {
						$feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
						$quotaFoundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
						if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
							$feeId .=",".$quotaFoundArray[0]['feeId'];  
						}
						else {
							$feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
							$quotaFoundArray = $collectStudentFeeManager->getCountFeeHeadNew($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
							if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
								$feeId .=",".$quotaFoundArray[0]['feeId'];  
							}
						}
					}
				}
				else {
					$feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
					$quotaFoundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
					if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
						$feeId .=",".$quotaFoundArray[0]['feeId'];  
					} 
				}
            }        
            if($feeId=='') {
              $feeId = "-1"; 
            }

 //================================FEE HEAD DETAILS (Start)============================================================
            $foundArray = $collectStudentFeeManager->getStudentFeeHeadCollectionDetail($feeClassId,$feeId,$studentId);
            $feeHeadIds = "-1";
            for($i=0;$i<count($foundArray);$i++) {
				if($foundArray[$i]['feeHeadId']!='') {  
					$feeHeadIds .= ",".$foundArray[$i]['feeHeadId'];   
				}
            }
            
            // Student Concession Findout is Leet & Non Leet Base 
            $concessionArray = $collectStudentFeeManager->getStudentConcession($feeClassId,$studentId,$feeHeadIds,$tIsLeet,$condition='');
            $concessionFeeHeadIds = "-1"; 
            for($i=0;$i<count($concessionArray);$i++) {
				$concessionFeeHeadIds .= ",".$concessionArray[$i]['feeHeadId'];   
            }
            
            $concessionCondition = " AND fcv.feeHeadId NOT IN ($concessionFeeHeadIds)";
            $concessionFinalArray = $collectStudentFeeManager->getStudentFinalConcession($feeClassId,$studentId,$feeHeadIds,$tIsLeet,$concessionCondition);
             
            $applCondition = " AND fhc.feeHeadId IN ($feeHeadIds) AND fhc.feeReceiptId IN ($ttPaidReceiptId) "; 
            $applFinalArray = $collectStudentFeeManager->getStudentFeeHeadCollection($feeClassId,$studentId,$applCondition); 
            
            $i=0;
            $concession=0;    
            for($i=0; $i<count($foundArray); $i++) {
				if($foundArray[$i]['feeId']=='') {
					continue;  
				} 
				$feeHeadDetailFind=1;
				$foundArray[$i]['concession']=0;
				$feeId = $foundArray[$i]['isVariable'].'_'.$foundArray[$i]['feeId'];
				$totalFees +=$foundArray[$i]['feeHeadAmt'];
				$salFeeHeadId =  $foundArray[$i]['feeHeadId'];
               
				$concession =0;     
				 
				if($adhocConcession==1) {
					for($jj=0;$jj<count($adhocConcessionArray);$jj++) {
						if($adhocConcessionArray[$jj]['feeHeadId']==$salFeeHeadId) {  
							$concession = $adhocConcessionArray[$jj]['concessionAmount']; 
						}
					}
				}
				else if($adhocConcession==0) {
					$maxConcession = 0;
					$minConcession = 0;
					$reducingConcession = 0;   
					$chk=0;               
					for($jj=0;$jj<count($concessionFinalArray);$jj++) {
						if($concessionFinalArray[$jj]['feeHeadId']==$salFeeHeadId) {
							if($concessionFinalArray[$jj]['concessionType']=='2') {
								$concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
								if($chk==1) {
									$reducingConcession = doubleval($reducingConcession) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
								}
							}
							if($concessionFinalArray[$jj]['concessionType']=='1') {
								$concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - (doubleval($foundArray[$i]['feeHeadAmt']) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
								if($chk==1) {
									$reducingConcession = doubleval($reducingConcession) - (doubleval($reducingConcession) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
								}
							}
                         
							if($chk==0) {
								$maxConcession = $concessionAmt;
								$minConcession = $concessionAmt; 
								$reducingConcession = $concessionAmt;
							}
							if($concessionAmt < $maxConcession) {
								$maxConcession = $concessionAmt;  
							}
							if($concessionAmt > $minConcession) {
								$minConcession = $concessionAmt;  
							}
							$chk=1;        
						}
					}
                   
					if($conessionFormatId==1) {
						$concession = $maxConcession; 
					}
					if($conessionFormatId==2) {
						$concession = $minConcession; 
					}
					if($conessionFormatId==3) {
						$concession = $reducingConcession; 
					}
				}
               
				if($concession==0 || $concession=='') {
					$conn = 0;    
				}
				else {
					if($adhocConcession==0) { 
						$conn = doubleval($foundArray[$i]['feeHeadAmt'])-doubleval($concession);
					}
					else {
						$conn = doubleval($concession);  
					}
				}
				$foundArray[$i]['concession'] = $conn;
               
				$totalConcession += doubleval($foundArray[$i]['concession']);  
               
				$srNo = ($rSrNo)."<input type='hidden'  readonly='readonly'  id='feeId$feeId' name='feeId[]' value='".$feeId."'>";
                
				$feesAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($foundArray[$i]['concession']);
				$ids = "applAmt".$i;
				$feeHeadId = $foundArray[$i]['feeHeadId'];  
               
				// Applicable Amount Remaining Part Calculated 
				$ttApplTotal=$feesAmt;   
				$ttPrevPaid='';
				$dif=0;
				for($jj=0;$jj<count($applFinalArray);$jj++) {  
					if($applFinalArray[$jj]['feeHeadId']==$salFeeHeadId) { 
						$ttApplTotal= doubleval($ttApplTotal) - doubleval($applFinalArray[$jj]['feeHeadAmt']);
						$dif = doubleval($feesAmt)-doubleval($ttApplTotal);
						break;  
					} 
				} 
				
				if($onlinePaidAmt!=0){
					if($onlinePaidAmt>=$ttApplTotal){
						$onlinePaidAmt=$onlinePaidAmt-$ttApplTotal;
						$feePaidArray[$i]=$ttApplTotal;
						$feeHeadArray[$i]=$feeHeadId;
						$feeDueArray[$i]=0; 
					}
					else{
						$feeDueArray[$i]=$ttApplTotal-$onlinePaidAmt;
						$feePaidArray[$i]=$onlinePaidAmt;
						$feeHeadArray[$i]=$feeHeadId;
						$onlinePaidAmt=0;
					}
					
				}
				else{
						$feeDueArray[$i]=$ttApplTotal; 
						$feeHeadArray[$i]=$feeHeadId;
						$feePaidArray[$i]=0;
				}
				
                 
               
				$showTFeeAmtPaid += $ttApplTotal;
               
				$feeAmtPaidTotal += ($feesAmt-$dif);
               
               
            }
		}
            
 //================================FEE HEAD DETAILS (End)==================================================
		
        $transpPaid = 0;
		$transDue = 0;
		$transHeadArray = 0;
         
		if($transportFacility==1 && ($feeTypeId == '4' || $feeTypeId == '2')) {    // Only Transport
            $trApplAmt = $trCharges - $trConcession;            
               
            // Applicable Amount Remaining Part Calculated 
            $ttApplTotal = doubleval($trApplAmt)-doubleval($prevApplTransport);
            
            $showTTransportAmtPaid = $ttApplTotal;
               
            $dif = doubleval($trApplAmt)-doubleval($ttApplTotal);
			
            if($onlinePaidAmt!=0){
				if($onlinePaidAmt>=$ttApplTotal){
					$onlinePaidAmt=$onlinePaidAmt-$ttApplTotal;
					$transpPaid += $ttApplTotal;
					$transDue += 0;
					$transHeadArray = "transport"; 
				}
				else{
					$transpPaid += $onlinePaidAmt ;
					$transDue +=$ttApplTotal-$onlinePaidAmt;
					$transHeadArray = "transport"; 
					$onlinePaidAmt=0;
				}
					
			}
			else{
				$transpPaid += 0;
				$transDue += $ttApplTotal;
				$transHeadArray = "transport"; 
			} 
				
                       
        }
		
        $hstlPaid = 0;
		$hstlDue = 0;
		$hstlHead = 0;   
		if($hostelFacility==1 && ($feeTypeId == '4' || $feeTypeId == '3')) {   // Only Hostel
            $hrApplAmt =  $hrCharges - $hrConcession;   
			$ttApplTotal = doubleval($hrApplAmt)-doubleval($prevApplHostel);
			$showTHostelAmtPaid = $ttApplTotal;
			$dif = doubleval($hrApplAmt)-doubleval($ttApplTotal);
			 if($onlinePaidAmt!=0){
				if($onlinePaidAmt>=$ttApplTotal){
					$onlinePaidAmt=$onlinePaidAmt-$ttApplTotal;
					$hstlPaid += $ttApplTotal;
					$hstlDue += 0;
					$hstlHead  = "transport"; 
				}
				else{
					$hstlPaid += $onlinePaidAmt ;
					$hstlDue +=$ttApplTotal-$onlinePaidAmt;
					$hstlHead  = "transport"; 
					$onlinePaidAmt=0;
				}
					
			}
			else{
				$hstlPaid += 0;
				$hstlDue += $ttApplTotal;
				  
				$hstlHead  = "transport"; 
			} 
			
			
		}
     // =========== Fetch Student Fee Head Wise Detail (START) ==============================
     
		$netAmount = 0;
		// Check Net Payable Amount 
		if($feeTypeId == '4' || $feeTypeId == '1') {           // Only Academic  
			//$totalFees = $totalFees + $previousFineCurr;
			$netAmount = (($totalFees-$totalConcession)+$previousFineCurr)-$previousPaymentCurr; 
		}
     
    
     
		$transportTotalFees=0;
		$hostelTotalFees=0;
		if($transportFacility==1 && ($feeTypeId == '4' || $feeTypeId == '2')) {    // Only Transport
			$prevTransportCharges =  $trApplAmt;
			$netAmount += ($trApplAmt+$prevTransportFine)-$prevTransportPaid;  
			$prevTransportDues = ($trApplAmt+$prevTransportFine)-$prevTransportPaid;  
			$transportTotalFees = ($trApplAmt+$prevTransportFine)-$prevTransportPaid;
		}
     
		if($hostelFacility==1 && ($feeTypeId == '4' || $feeTypeId == '3')) {    // Only Hostel      
			$prevHostelCharges =  $hrApplAmt;
			$netAmount += ($hrApplAmt+$prevHostelFine)-$prevHostelPaid;  
			$prevHostelDues = ($hrApplAmt+$prevHostelFine)-$prevHostelPaid;  
			$hostelTotalFees =  ($hrApplAmt+$prevHostelFine)-$prevHostelPaid;
		}
     //===================add data to database=================================================================================================
			$feeCycle=$collectStudentFeeManager->getActiveFeeCycle();
			if(!isset($feeCycle[0])){
				$feeCycle=$collectStudentFeeManager->getLastEntry();
		 
			}
			$totalFee=0;
			for($a=0;$a<count($feePaidArray);$a++){
			
			$totalFee+=$feePaidArray[$a];
			
			}
			for($a=0;$a<count($prevPaidArray);$a++){
			
			$totalPrevFee+=$prevPaidArray[$a];
			
			}
			
			
			
			$studentId=$studentTransactionArray[0]['studentId'];
			$classId = $sessionHandler->getSessionVariable('ClassId');
			$feeType=$studentTransactionArray[0]['feeType'];
			$feeCycleId=$feeCycle[0]['feeCycleId'];
			$feeClassId=$studentTransactionArray[0]['feeClassId'];
			$receiptNumber = $responseData['TxnReferenceNo'];
			$receiptDate = date('Y-m-d');
			$paidAmount = $totalPaidAmt;
			$userId = $sessionHandler->getSessionVariable('UserId'); 
			$installmentCount = $studentInstallmentCount;
			$feeAmtPaid = $totalFee;
			$hostelDues = $hrCharges;
			$hostelConcession = $hrConcession;
			$hostelPaid = $hstlPaid;
			$transportDues = $trCharges;
			$transportConcession = $trConcession;
			$transportPaid = $transpPaid;
			$duesAmtPaid = $totalPrevFee;
			$applHostel = $hstlPaid;
			$applTransport = $transpPaid;
			$isConessionFormat = $conessionFormatId;
			$str = "('$studentId', '$classId', '$feeType', '$feeCycleId', '$feeClassId', '$receiptNumber', '$receiptDate','1', '', 
                   '', '$paidAmount', '$cashAmount', '0', '$userId','$installmentCount','0',
                   '$feeAmtPaid','$hostelDues', '0', '$hostelConcession', '$hostelPaid', 
                   '$transportDues', '0', '$transportConcession', '$transportPaid', '$duesAmtPaid',
                   '$applHostel', '0', '$applTransport', '0','$isConessionFormat','1' )";
			$returnStatus = $collectStudentFeeManager->addFeeReceiptCollection($str);
			if($returnStatus === false) {
				$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
				header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
			}
			$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
          
			$receiptId = $sessionHandler->getSessionVariable('IdToFeeReceipt'); 
          
			$str ='';
          
			
			$str .= "('$receiptId','$studentId', '$classId', '$feeCycleId', '$feeClassId', '4',
                      '$receiptNumber', '$paidAmount', '0', '$receiptDate','2')";
        
			if($str!='') {
				$returnStatus = $collectStudentFeeManager->addFeePaymentDetailCollection($str);
				if($returnStatus === false) {
					$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM); 
					header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
				$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			}
			
			
          // Fee Headwise Collection Add
			$str ='';
			for($i=0;$i<count($feeHeadArray);$i++) {   
				$amt =  $feePaidArray[$i];
				if($amt!='') {
					if($str!='') {
						$str .=",";  
					} 
					$str .= "('$receiptDate','$receiptId','$studentId', '$classId', '$feeCycleId', '$feeClassId', '".$feeHeadArray[$i]."','1','$amt')";
				}
			}
          

			          
   
          
          
			if($str!='') {
				$returnStatus = $collectStudentFeeManager->addFeeHeadCollection($str);
				if($returnStatus === false) {
					$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM); 
					header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
				$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			}
			
			// Fee Dues Collection Add
			$str ='';
			for($i=0;$i<count($prevClassArray);$i++) {   
				$amt =  $prevPaidArray[$i];
				$classId = $prevClassArray[$i];
				if($amt!='') {
					if($str!='') {
						$str .=",";  
					} 
					$str .= "('$classId','$studentId','$amt','$receiptId')";
				}
			}
          
			if($str!='') {
				$returnStatus = $collectStudentFeeManager->addFeeDuesCollection($str);
				if($returnStatus === false) {
					$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM); 
					header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
				$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			}
          
	      //*****************************COMMIT TRANSACTION************************* 
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		    ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
				$auditTrialDescription = "Fees has been collected from :$studentName($rollNo) ,";
				$auditTrialDescription.=$className;
				$type = FEES_IS_COLLECTED; //Fee is collected
				$queryDescription='';
				$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
				if($returnStatus == false) {
					$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
					header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
				}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
				$sessionHandler->setSessionVariable('onlineTransaction',Success_Transaction." Recipt No. :- ".$receiptNumber); 
				header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;
			}
			else {
				$sessionHandler->setSessionVariable('onlineTransaction',TECHNICAL_PROBLEM);
				header("Location:".UI_HTTP_PATH.'/Student/studentOnlineFeePayment.php');
					die;				
			}    	
     
     
     
     
     
     
    
die;  
     
  
  

?>
