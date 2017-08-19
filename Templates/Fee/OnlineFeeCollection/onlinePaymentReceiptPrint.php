<?php
        global $FE;
        require_once($FE . "/Library/common.inc.php"); 
        require_once(BL_PATH . "/UtilityManager.inc.php");
        require_once(BL_PATH . '/NumToWord.class.php'); 
        
        require_once(MODEL_PATH . "/Fee/OnlineFeeManager.inc.php");
        $onlineFeeManager = OnlineFeeManager::getInstance();

        require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
        
        global $sessionHandler; 
        $roleId = $sessionHandler->getSessionVariable('RoleId');       
        require_once(MODEL_PATH . "/Fee/PaymentHistoryReportManager.inc.php");   
        $PaymentHistoryReportManager = PaymentHistoryReportManager::getInstance();

        require_once(BL_PATH . '/ReportManager.inc.php');
        $reportManager = ReportManager::getInstance();

        require_once(BL_PATH . '/NumToWord.class.php');  

        require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
        $studentFeeManager = StudentFeeManager::getInstance();
        
        $contentHead = "";
        $styleFont = 'style="font-size:13px;font-family: Verdana, Arial, Helvetica, sans-serif;"';        
        $styleFont2 = 'style="font-size:14px;font-family: Verdana, Arial, Helvetica, sans-serif;"';  
            
        $address = "All disputes subject to Chandigarh Jurisdiction.";
        $address = "<hr style='width:100%'>";
        $address .= "Chandigarh - Patiala National Highway (NH-64),<br>
                     Village Jhansla, Tehsil Rajpura, Distt.Patiala-140 401<br>
                     Tel: +91.1762.507084, 507085<br>";
        
        
        if($roleId=='4') {
           // Online Transcation Redirect  
           $onlineId = $sessionHandler->getSessionVariable('IdToOnlieFeeReceipt');
		   $isOnlineStudentId = $sessionHandler->getSessionVariable('StudentId'); 
          
           // Complete Info Tab
           $receiptId = htmlentities(add_slashes(trim($REQUEST_DATA['receiptId'])));
        }
        else {
           $receiptId = htmlentities(add_slashes(trim($REQUEST_DATA['receiptId'])));
           $isOnlineStudentId = htmlentities(add_slashes(trim($REQUEST_DATA['studentId'])));
        }
		
        if($onlineId=='') {
          $onlineId='0';  
        } 
       
		if($isOnlineStudentId=='') {
          $isOnlineStudentId='0';  
        } 

        $imageButton1 = '';
        $rupeeIcon = "<img height='11px' src='".IMG_HTTP_PATH."/rupee-icon.gif'>";
        $logoPath = IMG_HTTP_PATH."/logo.gif";
       
        $notes="<i>This is a computer generated receipt. No signature is required.</i>"; 
        
        
        $onlineFeeCondition='';   
		$condition='';    
        if($receiptId!=''){        	
		  $condition = " AND frd.feeReceiptId ='$receiptId'  ";				
        }
        else{
		   $condition = " AND ot.onlineId = '$onlineId' AND ot.isPrint=0 ";
		}                 
		$onlineFeeArray = $onlineFeeManager->getOnlineFeeDetails($condition);
		$isStatus=$onlineFeeArray[0]['isStatus'];
        
        if($isStatus=='1') {//transaction status 1- success 2-failed 
		  $transactionStatus ="Transaction Completed Succesfully";
		}
        else{
		  $transactionStatus ="Transaction Failed";
		}
		$receiptNo = $onlineFeeArray[0]['receiptNo'];
		$receiptDate = $onlineFeeArray[0]['receiptDate'];
		$amount = $onlineFeeArray[0]['totalAmount'];
        $taxAmount = $onlineFeeArray[0]['taxAmount'];   
        $totalFee = $onlineFeeArray[0]['totalFee'];   
		$isPrint =   $onlineFeeArray[0]['isPrint']; 
		$feeType = $onlineFeeArray[0]['feeType']; //fee history(classid~feeType,..)
    	if($feeType=='1'){
       	  $feeName="Academic Fee";
       	}
		if($feeType=='2'){
       	  $feeName="Transport Fee";
       	}
		if($feeType=='3'){
       	  $feeName="Hostel Fee";
       	}
        if($feeType=='4'){
          $feeName="All";
        }
       	$classIdArray = $onlineFeeArray[0]['className']."<br>(".$feeName.")";
			
		$num = new NumberToWord($totalFee);
        $num1 = trim(ucwords(strtolower($num->word)));
        if($num1!='') {
          $num1 .=" Only";  
        }
        
        
                                     
//$address  = "All disputes subject to Chandigarh Jurisdiction.";
$address = "<hr>";
$address .= "Chandigarh - Patiala National Highway (NH-64),<br>Village Jhansla, Tehsil Rajpura, Distt. Patiala-140 401<br>Tel: +91.1762.507084, 507085<br>";

$rupeeIcon = "<img height='11px' src='".IMG_HTTP_PATH."/rupee-icon.gif'>";    


//$receiptId = $REQUEST_DATA['receiptId'];


//$receiptNo = $REQUEST_DATA['receiptNo'];


$condition = '';          
$condition1 = '';
if($receiptId != ''){
  $condition = " AND frm.feeReceiptId = '$receiptId' AND frd.isDelete=0";
}

// to fetch student Info & heads amount
$feeDataArray = $PaymentHistoryReportManager->getReceiptDetails($receiptNo,$condition);
if(count($feeDataArray) == 0){
  echo "";
}
else {

        $ttFeeType =  $feeDataArray[0]['feeType'];
        $ttStudentId = $feeDataArray[0]['studentId'];
        $ttClassId = $feeDataArray[0]['feeClassId'];
        $ttFeeReceiptId = $feeDataArray[0]['ttFeeReceiptId'];
       
         $imageButton1 = '<input type="image"  src="'.IMG_HTTP_PATH.'/print.gif" onClick="printOnlineSlip(\''.$ttFeeReceiptId.'\'); return false;" title="Print">';
       
        // Previous Class Balance (Start)
               $prevStudentId = $ttStudentId;
               $prevClassId = $ttClassId;       
                $isPreviousPaid =0;
            
                
                 $prevAllClassComment = '';    
                  $prevAllClassAmount = 0;
            $isCheckPrviousBalance='0';        // Check the previous class balance  1=>On, 0=>Off
            
            $prevAcademicClassAmount =0;
            $prevHostelClassAmount =0;
            $prevTransportClassAmount =0; 
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

        $showDataArray = $PaymentHistoryReportManager->getStudentFeeDetails($ttStudentId, $ttClassId, $ttFeeReceiptId);

        $hostelDescId = '';
        $transportDescId='';               
        $transportDesc = "";
        $hostelDesc = "";

        if($ttFeeType == 3 || $ttFeeType == 4) {  
            $hostelDesc = "<tr>
                            <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                              Hostel
                            </td>
                             <td class='dataFont' style='padding-top:4px' nowrap align='left'>&nbsp;:&nbsp;</td>
                            <td class='dataFont' style='padding-top:4px' colspan='2' nowrap><b>".$showDataArray[0]['hostelName']." (".$showDataArray[0]['roomName'].")</b></td>
                         </tr>";
        }                 
                       /*
                        <u><b>Hostel Detail</b></u>
                       <table width='100%' border='1px' cellpadding='0px' cellspacing='0px' >
                        <tr>
                           <td class='dataFont' align='left' ><b>Hostel</b></td>
                           <td class='dataFont' align='center' ><b>Check In</b></td>
                           <td class='dataFont' align='center' ><b>Check Out</b></td>
                        </tr>   
                        <tr>
                           <td class='dataFont' align='left' >".$showDataArray[0]['hostelName']." (".$showDataArray[0]['roomName'].")</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($showDataArray[0]['dateOfCheckIn'])."</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($showDataArray[0]['dateOfCheckOut'])."</td>
                        </tr>
                       </table>";
                        */
                        
        if($showDataArray[0]['hostelName']!='' ) {
        $hostelDescId='1';  
        }               

        if($ttFeeType == 2 || $ttFeeType == 4) {   
        $transportDesc = "<tr>
                            <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                              Route</td>
                             <td class='dataFont' style='padding-top:4px' nowrap align='left'>&nbsp;:&nbsp;</td>   
                            <td class='dataFont' style='padding-top:4px' colspan='2' nowrap><b>".
                            $showDataArray[0]['routeName']." (".$showDataArray[0]['cityName'].")</b></td>
                         </tr>";
        }
                         
        /*  
        $transportDesc = " <u><b>Transport Detail</b></u>
                       <table width='100%' border='1px' cellpadding='0px' cellspacing='0px' >
                        <tr>
                           <td class='dataFont' align='left' ><b>Route</b></td>
                           <td class='dataFont' align='center' ><b>Valid From</b></td>
                           <td class='dataFont' align='center' ><b>Valid To</b></td>
                        </tr>   
                        <tr>
                           <td class='dataFont' align='left' >".$showDataArray[0]['routeName']." (".$showDataArray[0]['cityName'].")</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($showDataArray[0]['validFrom'])."</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($showDataArray[0]['validTo'])."</td>
                        </tr>
                       </table>";
        */
        if($showDataArray[0]['routeName']!='') {
        $transportDescId='1';  
        }               


        $installmentNo = $feeDataArray[0]['installmentNo'];
        $feeReceiptId1 = $feeDataArray[0]['feeReceiptId'];
        $paidAt = $feeDataArray[0]['paidAt'];       
        $regNo = $feeDataArray[0]['regNo'];
        $rollNo = $feeDataArray[0]['rollNo'];
        $className = $feeDataArray[0]['className'];  
        $studentGender = $feeDataArray[0]['studentGender'];
        $instituteAbbr = $feeDataArray[0]['instituteAbbr'];
        $instituteId = $feeDataArray[0]['instituteId'];
           $ttFeeType =  $feeDataArray[0]['feeType'];


        if($studentGender=='M') {
          $studentGender='S/o';  
        }
        else {
          $studentGender='D/o'; 
        }

        $condition1 = " AND    frd.feeReceiptId = '$feeReceiptId1' ";
        // to fetch payment details
        $paymentDataArray = $PaymentHistoryReportManager->getPaymentDetails($receiptNo,$condition1);


        // to get Already paid fees 
        $prevPaidAmountArr = $PaymentHistoryReportManager->getTotalAlreadyPaid($feeReceiptId1,$installmentNo,$ttFeeType);

        // function is used to fetch Total previous fine amount
        $prevFineAmountArr = $PaymentHistoryReportManager->getTotalPrevFine($feeReceiptId1,$installmentNo);
        $previousPaid = $prevPaidAmountArr[0]['paidAmount'];
        $previousFine = $prevFineAmountArr[0]['previousFine'];
        $paidTransportFee = $paymentDataArray[0]['transportFeePaid'];
        $paidHostelFee = $paymentDataArray[0]['hostelFeePaid'];
        $paidHostelSecurity = $paymentDataArray[0]['hostelSecurity'];
        $fine = $paymentDataArray[0]['academicFine'];
        $finePaid = $paymentDataArray[0]['academicFinePaid'];
        $hostelFine = $paymentDataArray[0]['hostelFine'];
        $hostelFinePaid = $paymentDataArray[0]['hostelFinePaid'];
        $tranportFine = $paymentDataArray[0]['tranportFine'];
        $transportFinePaid = $paymentDataArray[0]['transportFinePaid'];


        $feeCycleId = $feeDataArray[0]['feeCycleId'];
        $rollNo = $feeDataArray[0]['rollNo'];
        $feeStudyPeriodName = $feeDataArray[0]['feeStudyPeriodName'];
        $feeReceiptId = $feeDataArray[0]['feeReceiptId'];
        $degreeAbbr = $feeDataArray[0]['degreeAbbr'];
        $batchYear = $feeDataArray[0]['batchYear'];
            
        $paddingLeft = "style='padding-left:45px'";

        $showPrevPayment = "<tr>
                        <td class='dataFont' $paddingLeft ><b>Prev. Payment</b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><PrevPayment></td>
                     </tr>";
                     
        $showBalanceAdvance = "<tr>
                        <td class='dataFont' $paddingLeft ><b><BalanceAdvanceText></b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><BalanceAdvance></td>
                     </tr>";                     

        $showPrevFine = "<tr>
                        <td class='dataFont' $paddingLeft ><b>Prev. Fine</b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><PrevFine></td>
                     </tr>";



        $feeContent = '';
        $totalAmount =0;
        $paidAmount = 0;
        $cnt = 1;

        if($ttFeeType == 1 || $ttFeeType == 4) {  
        for($i = 0;$i<count($feeDataArray);$i++){
        if($feeDataArray[$i]['feeHeadName']!='') { 
         if($feeDataArray[$i]['isSpecial'] == 0){ 
            $feeContent .=" <tr>
            <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
            <td class='dataFont'  style='padding-top:4px' >".$feeDataArray[$i]['feeHeadName']."</td> 
            <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$feeDataArray[$i]['amount']."</td>
            </tr>";
            //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$feeDataArray[$i]['paidAmount']."</td>
            $totalAmount += $feeDataArray[$i]['amount'];
            $paidAmount += $feeDataArray[$i]['paidAmount'];
            $cnt++;
         }
        }
        }
        }   
        if($feeDataArray[0]['hostelFees'] > 0 && ($feeDataArray[0]['hostelId'] != '' && $feeDataArray[0]['hostelRoomId'] != '')){
        if($ttFeeType == 3 || $ttFeeType == 4) {      
        if($feeDataArray[0]['hostelSecurity'] > 0){
            $feeContent .=" <tr>
            <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
            <td class='dataFont'  style='padding-top:4px' >Hostel Security</td> 
            <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$feeDataArray[0]['hostelSecurity'], 2, '.', '')."</td>
            </tr>";
            //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$paidHostelSecurity, 2, '.', '')."</td>
            $totalAmount += $feeDataArray[0]['hostelSecurity'];
            $paidAmount += $paidHostelSecurity;
            $cnt++;
        }

        $feeContent .=" <tr>
        <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
        <td class='dataFont'  style='padding-top:4px' >Hostel Fees</td> 
        <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$feeDataArray[0]['hostelFees'], 2, '.', '')."</td>
        </tr>";
        //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$paidHostelFee, 2, '.', '')."</td>
        $totalAmount += $feeDataArray[0]['hostelFees'];
        $paidAmount += $paidHostelFee;
        $cnt++;
        }
        }

        if($feeDataArray[0]['transportFees'] > 0 && ($feeDataArray[0]['busRouteId'] != '' && $feeDataArray[0]['busStopId'] != '')){
        if($ttFeeType == 2 || $ttFeeType == 4) {  
        $transportFee = $feeDataArray[0]['transportFees'];
        $feeContent .=" <tr>
        <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
        <td class='dataFont'  style='padding-top:4px' >Transport Fees</td> 
        <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$transportFee, 2, '.', '')."</td>
        </tr>";
        //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$paidTransportFee, 2, '.', '')."</td>
        $totalAmount += $transportFee;
        $paidAmount += $paidTransportFee;
        $cnt++;
        }
        }    
        if($fine > 0 || $finePaid>0){
        $feeContent .=" <tr>
        <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
        <td class='dataFont'  style='padding-top:4px' >Fine</td> 
        <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$fine, 2, '.', '')."</td>
        </tr>";
        //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$finePaid, 2, '.', '')."</td>
        $totalAmount += $fine;
        $paidAmount += $finePaid;
        $cnt++;

        }

        if($hostelFine > 0 || $hostelFinePaid > 0){
        $feeContent .=" <tr>
        <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
        <td class='dataFont'  style='padding-top:4px' >Hostel Fine</td> 
        <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$hostelFine, 2, '.', '')."</td>
        </tr>";
        //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$hostelFinePaid, 2, '.', '')."</td>
        $totalAmount += $hostelFine;
        $paidAmount += $hostelFinePaid;
        $cnt++;    
        }

        if($tranportFine > 0 || $transportFinePaid >0){
        $feeContent .=" <tr>
        <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
        <td class='dataFont'  style='padding-top:4px' >Transport Fine</td> 
        <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$tranportFine, 2, '.', '')."</td>
        </tr>";
        //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$transportFinePaid, 2, '.', '')."</td>
        $totalAmount += $tranportFine;
        $paidAmount += $transportFinePaid;
        $cnt++;    
        }

        if($ttFeeType == 1 || $ttFeeType == 4) {      
        for($i= 0;$i<count($feeDataArray);$i++){ 
        if($feeDataArray[$i]['isSpecial'] == 1){
            $feeContent .=" <tr>
            <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
            <td class='dataFont'  style='padding-top:4px' >".$feeDataArray[$i]['feeHeadName']."</td> 
            <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$feeDataArray[$i]['amount']."</td>
            </tr>";
            //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$feeDataArray[$i]['paidAmount']."</td>
            $totalAmount += $feeDataArray[$i]['amount'];
            $paidAmount += $feeDataArray[$i]['paidAmount'];
            $cnt++;
        }
        }
        }    
        $negativeLedgerAmount ='0';
        $negativeDebitLedgerAmount ='0';
         if($ttFeeType == 1) {
               $ledgerTypeId = '1';
           $ledgerArray = $PaymentHistoryReportManager->getStudentFeeLedger($ttStudentId,'',$ttClassId,$ledgerTypeId);
            }
            else if($ttFeeType == 2) {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '2';
                $ledgerArray = $PaymentHistoryReportManager->getStudentFeeLedger($ttStudentId,'',$ttClassId,$ledgerTypeId);
            }
            else if($ttFeeType == 3) {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '3';
                  $ledgerArray = $PaymentHistoryReportManager->getStudentFeeLedger($ttStudentId,'',$ttClassId,$ledgerTypeId);
            }
            else if($ttFeeType ==4) {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '1,2,3';
             $ledgerArray = $PaymentHistoryReportManager->getStudentFeeLedger($ttStudentId,'',$ttClassId,$ledgerTypeId);
            }


        for($j=0;$j<count($ledgerArray);$j++) {

            $comments = '';
            $amount='';
            $alreadyPaid = 0;
            $comments = substr(trim(chunk_split(ucwords($ledgerArray[$j]['comments']),50,"<br/>")),0,-5);
            if($ledgerArray[$j]['debit'] > 0){
                $amount = number_format((float)$ledgerArray[$j]['debit'], 2, '.', '');
        $negativeDebitLedgerAmount +=$ledgerArray[$j]['debit'];

                $toBePaid = $amount - $alreadyPaid ;

            }
            else{
                $amount = "-".number_format((float)$ledgerArray[$j]['credit'], 2, '.', '');
         $negativeLedgerAmount += $ledgerArray[$j]['credit'];
                $applAmt = $amount ;

            }
            $headName = $comments;
            $feeContent .="<tr>
                             <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
                             <td class='dataFont'  style='padding-top:4px' >".$headName."</td> 
                             <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$amount."</td>
                           </tr>";
        //$paidAmount -= $negativeLedgerAmount;

            $totalAmount += $amount;
               

            $cnt++;   
        }   

        if($ttFeeType == 1 || $ttFeeType==4) {
        if($isPrevPrint == 
        $ttClassId){
            if($prevAllClassAmount!='' || $prevAllClassAmount!='0'){
          $feeContent .="<tr>
                             <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
                             <td class='dataFont'  style='padding-top:4px' >Prev. Class Balance (".$prevAllClassComment.")</td> 
                             <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$prevAllClassAmount."</td>
                           </tr>";
                            $totalAmount += $prevAllClassAmount;
             $cnt++;
            } 
         }
        }

            if($paidAmount>0){
            //$paidAmount -= $negativeLedgerAmount;
            }

            if($paidAmount>0){
              $paidAmount += $negativeDebitLedgerAmount;
              if($ttFeeType == 1 || $ttFeeType==4) {
               $paidAmount += $prevAllClassAmount;
               }
               
            }

        $paidAmount = $paymentDataArray[0]['amount'];

        $balance = 0;
        $balance= $totalAmount - $paidAmount ;
        $concessionData = "";

          if($ttFeeType == 1 || $ttFeeType==4) {
            if($paidAmount > 0 && $feeDataArray[0]['concession'] > 0) {

            if($balance==0){
            $paidAmount = $paidAmount - $feeDataArray[0]['concession'];
            }  
            $concessionData = "<tr><td class='dataFont' style='padding-top:4px'><b>Adjustment</b></td>
                                     <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                                    <td class='dataFont' align='right' style='padding-top:4px'>".number_format((float)$feeDataArray[0]['concession'], 2, '.', '')."</td></tr>";
            $balance = $totalAmount - ($paidAmount + $feeDataArray[0]['concession']);

            }

            if($previousFine > 0){
            $concessionData .= "<tr><td class='dataFont' style='padding-top:4px'><b>Prev. Total Fine </b></td>
                <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                <td class='dataFont' align='right' style='padding-top:4px'>".number_format((float)$previousFine, 2, '.', '')."</td></tr>";
            $balance = ($totalAmount + $previousFine) - ($paidAmount + $feeDataArray[0]['concession']);
            }


         }

        if($previousFine > 0){
        $concessionData .= "<tr><td class='dataFont' style='padding-top:4px'><b>Prev. Total Fine </b></td>
                   <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                   <td class='dataFont' align='right' style='padding-top:4px'>".number_format((float)$previousFine, 2, '.', '')."</td></tr>";
        $balance = ($totalAmount + $previousFine) - ($paidAmount );
        }
          if($previousPaid > 0){

           $concessionData .= "<tr><td class='dataFont' style='padding-top:4px'><b>Prev. Paid Amount</b></td>
                   <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                   <td class='dataFont' align='right' style='padding-top:4px'>".number_format((float)$previousPaid, 2, '.', '')."</td></tr>";
           $balance = $balance - $previousPaid;
            
            
            
          }

          $num = new NumberToWord($paidAmount);
                $num1 = trim(ucwords(strtolower($num->word)));
                if($num1!='') {
                  $num1 .=" Only";  
                }
                
        $totalFeeContents ='';
        $totalFeeContents = "<table width='55%' border='0' cellspacing='0' cellpadding='0'>
                   <tr>    
                        <td class='dataFont' align='right' width='35%'><b>Total Fees&nbsp;&nbsp;</b></td>
                       <td class='dataFont'  align='right' width='2%'><strong>&nbsp;:&nbsp;</strong></td>
                       <td class ='dataFont' align='right' width='18%'><b>&nbsp;$rupeeIcon&nbsp;".number_format((float)$totalAmount, 2, '.', '')."</b></td>
                   </tr>
                   <tr style='display:none'>    
                    <td class='dataFont'><b>Installment No.</b></td>
                       <td class='dataFont' width='1%'><strong>:</strong></td>
                       <td class ='dataFont' align='right'>".$installmentNo."</td>
                   </tr>
                   $concessionData
                   <tr>
                       <td class='dataFont' style='padding-top:4px' align='right' ><b>Paid Amount&nbsp;&nbsp;</b></td>
                       <td class='dataFont' width='2%' style='padding-top:4px' align='right'><strong>&nbsp;:&nbsp;</strong></td>
                       <td class ='dataFont' align='right' style='padding-top:4px'><b>&nbsp;$rupeeIcon&nbsp;".number_format((float)$paidAmount, 2, '.', '')."</b></td>
                   </tr>
                   ";  

        if($balance!='0') {            
        $totalFeeContents .= "<tr>
                           <td class='dataFont' style='padding-top:4px' align='right'><b>Balance&nbsp;&nbsp;</b></td>
                           <td class='dataFont' width='1%' style='padding-top:4px;' ><strong>&nbsp;:&nbsp;</strong></td>
                           <td class ='dataFont' align='right' style='padding-top:4px'><b>&nbsp;$rupeeIcon&nbsp;".number_format((float)$balance, 2, '.', '')."</b></td>
                       </tr>";
        }
                
        $totalFeeContents .= "</table>";


        $logoPath = '';
        $logo = $feeDataArray[0]['instituteLogo'];

        if($logo != ''){
        $logoPath =IMG_HTTP_PATH."/Institutes/".$logo."?xx=".rand(0,500);
        }
        else{
        $logoPath = IMG_HTTP_PATH."/logo.gif";
        }
        $logoPath = IMG_HTTP_PATH."/logo.gif";
        if($feeDataArray[0]['bankAddress'] ==''){
        $feeDataArray[0]['bankAddress'] ='---';
        }
        $paymentHeader= '';
        $paymentMode ='';
        $cashPaid = 0;
        $modeArray = array(1=>'Cash',2=>'Cheque',3=>'Draft',4=>'Online');
        $paymentData = "<table border=0 cellspacing=0 cellpadding=0 width='100%'>";

        $paymentHeader = "<table border=1 cellspacing=0 cellpadding=0 width='100%'>
                 <tr><td class='dataFont' width='20%'><b>Instrument</b></td>
                 <td class='dataFont' width='20%' style='padding-left:2px;'><b>Number</b></td>
                 <td class='dataFont' width='18%' align='right' style='padding-right:2px;'><b>Amount</b></td>
                 <td class='dataFont' width='22%' style='padding-left:2px;'><b>Bank Name</b></td>
                 <td class='dataFont' width='20%' align='center'><b>Dated</b></td></tr>";
        $ddNo='';                
        $ddAmt ='';
        $ddDate='';
        $ddBankName='';
        foreach($paymentDataArray as $key =>$value){
          if( $value['paymentMode'] == '4') {
            $cashPaid = number_format((float)$value['amount'], 2, '.', '');
          }
        }
        $paymentHeader .="$paymentMode</table>";

        $paymentData .="<tr>
                    <td class='dataFont' width='40%' colspan='2'>
                        <span style='float:justify;' nowrap>      
                        <b>By Cash&nbsp;:&nbsp;</b>$rupeeIcon&nbsp;$cashPaid/-&nbsp;</span>
                    </td>
                  </tr>
                  <tr><td width='60%'></td></tr>";
        if($paymentMode !=''){
        $paymentData .= "<tr><td colspan='5' style='padding-top:7px;'>".$paymentHeader."</td></tr>";
        }
        $paymentData .="</table>";


        $receiptScrollFormat = "Receipt No.";    


        $receiptData="<table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >";
         
            
        $num = new NumberToWord($paidAmount);
        $num1 = trim(ucwords(strtolower($num->word)));
        if($num1!='') {
        $num1 .=" Only";  
        }            
               
        $receiptData .="<tr class='dataFont' style='display:none'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'>
                    <table width='100%' border='0' cellspacing='0' cellpading='0'><tr><td valign='top' width='70px'><b>Bank Addr.&nbsp;:&nbsp;</b>
                    </td><td valign='top' >".$feeDataArray[0]['bankAddress']."</td></tr></table></td> </tr>
                   

                <tr class='dataFont'>
                     <td align='left' colspan='2' style='padding-top:10px'>
                        <table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                          <tr>
                             <td align='left'>
                               <img src='$logoPath' width='200' height='60' border=0>       
                             </td> 
                          </tr>  
                        </table>
                     </td>
                 </tr> 
                 <tr><td height='30px'></td></tr>
                 <tr class='dataFont'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'>
                        <b>$receiptScrollFormat&nbsp;:&nbsp;".$feeDataArray[0]['receiptNo']."</b>
                        <span style='float:right'>
                         <b>Date&nbsp;:&nbsp;".date('d-m-y',strtotime($paymentDataArray[0]['receiptDate']))."</b></span> 
                    </td>  
                 </tr>
                 <tr class='dataFont'>
                     <td colspan='2' align='center' style='padding-top:10px'><b><br></b></td>
                 </tr>          
                 <tr>
                    <td class='dataFont' style='padding-top:4px' align='left' colspan='2'>
                      <table width='100%' border='0' cellspacing='0' cellpading='0'>
                      <tr>
                           <td class='dataFont' width='2%' nowrap>
                             Student Name
                           </td>
                           <td class='dataFont' width='1%' nowrap>&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='47%' nowrap colspan='3'>
                             <b>".$feeDataArray[0]['studentName']."</b>
                           </td>
                         </tr>  
                         <tr> 
                          <td class='dataFont' width='2%' nowrap>
                             Father's Name
                           </td>
                           <td class='dataFont' width='1%' nowrap>&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='47%' nowrap colspan='3'>
                             <b>".$feeDataArray[0]['fatherName']."</b>
                           </td>
                          
                         </tr>
                         <tr>
                          <td class='dataFont' width='2%' nowrap>
                           Roll No</td>
                           <td class='dataFont' width='1%' nowrap>&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='47%' nowrap colspan='3'>
                             <b>".$rollNo."</b>
                           </td>
                          
                           <td class='dataFont' width='5%' style='padding-left:8px;' rowspan='3' nowrap>
                              <table border='0' cellspacing='0' cellpading='0' align='right'> 
                                <tr>
                                  <td class='dataFont' nowrap>Reg No.</td>
                                   <td class='dataFont' nowrap>&nbsp;:&nbsp;</td>  
                                  <td class='dataFont' align='left' nowrap>
                                    <b>".$regNo."</b>
                                  </td>
                               </tr>   
                               <tr>
                                  <td class='dataFont'>Branch</td>
                                    <td class='dataFont' nowrap>&nbsp;:&nbsp;</td> 
                                  <td class='dataFont' align='left' nowrap>
                                    <b>".$feeDataArray[0]['branchCode']."</b>
                                  </td>
                               </tr>
                                <tr>
                                  <td class='dataFont'>Mode</td>
                                    <td class='dataFont' nowrap>&nbsp;:&nbsp;</td> 
                                  <td class='dataFont' align='left' nowrap>
                                    <b>Online Payment</b>
                                  </td>
                               </tr>
                              </table>
                           </td>     
                          </tr>                          
                         <tr>
                           <td class='dataFont' width='2%' nowrap>
                             Course</td>
                             <td class='dataFont' nowrap>&nbsp;:&nbsp;</td> 
                           <td class='dataFont' width='48%' nowrap>
                             <b>".$feeDataArray[0]['degreeAbbr']."</b>
                           </td> 
                            
                          </tr>
                          <tr>
                          <td class='dataFont' width='2%' nowrap>
                              Semester</td>
                             <td class='dataFont' nowrap>&nbsp;:&nbsp;</td> 
                           <td class='dataFont' width='48%' nowrap>
                             <b>".$feeStudyPeriodName."</b>
                           </td>                 
                            
                         </tr>";
                         
                 if($hostelDescId=='1') {
                    $receiptData .="$hostelDesc";
                 }
                 
                 if($transportDescId=='1') {
                     $receiptData .="$transportDesc";
                 }

                         
                    $receiptData .="</table>       
                    </td>
                 </tr>  
                
                 <tr>
                     <td colspan='2' style='padding-top:8px'>   
                        <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
                           <tr>
                               <td class='dataFont' align='center' width='5%'><strong>#</strong></td>
                               <td class='dataFont'  width='50%'><strong>Particulars</strong></td>
                               <td class='dataFont' align='right' width='12%'><strong>Amount ($rupeeIcon)</strong></td>
                           </tr> 
                            ".$feeContent." 
                         
                        </table>
                     </td>
                 </tr> 
                    <tr>
                <td colspan = 3 align='right' style='padding-top:10px;'>
                     $totalFeeContents 
                </td>
                </tr>
                  <tr><td height='10px'></td></tr>           
                <tr> 
                 <td colspan='3' style='padding-top:5px'>   
                        <table width='100%' border='0px' cellpadding='1px' cellspacing='0px'> 
                           <tr>                    
                            <td  class='dataFont' align='left'><b>Amount in words&nbsp;:&nbsp;Rupees&nbsp;".$num1."<b></td>               
                            </tr> 
                          </table>
                         </td>
                 </tr>        
                    <tr><td height='50'></td></tr>  
                    <tr><td colspan ='3' align='center' style='font-size:11px;font-family: Verdana, Arial, Helvetica, sans-serif;'>$notes</td></tr> ";
                    

                $receiptData .="<tr><td height=10></td></tr>
                              
                                <tr>
                                   <td class='dataFont' colspan='2' align='center'>
                                      $address 
                                   </td>
                               </tr>      
                              </table>";
     
     
     
        echo $paymentReceiptPrint = "<table width='600px;' border='0px' cellpadding='0px' cellspacing='0px' align='center'>
               <tr>
                 <td align='center'>".$receiptData."</td> 
               </tr>
               <tr><td><br></td></tr>
               <tr>
                 <td>$imageButton1</td>
               </tr>
               </table>";
        $onlineUpdateFeeArray = $onlineFeeManager->getUpdateFeePrint(" onlineId = '$onlineId'");        
}
               
?>        