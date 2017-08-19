<?php            
		global $FE;
		require_once($FE . "/Library/common.inc.php");
		require_once(BL_PATH . "/UtilityManager.inc.php");
		define('MODULE','COMMON');
		define('ACCESS','view');
		define("MANAGEMENT_ACCESS",1);
		UtilityManager::ifNotLoggedIn();
		
		global $sessionHandler;
		

		require_once(MODEL_PATH . '/FineManager.inc.php');
		$fineManager = FineManager::getInstance();

		require_once(BL_PATH . '/ReportManager.inc.php');
		$reportManager = ReportManager::getInstance();
		
		require_once(BL_PATH . '/NumToWord.class.php');  

		$address  = "All disputes subject to Chandigarh Jurisdiction.";
		$address .= "<hr>";
		$address .= "Chandigarh - Patiala National Highway (NH-64),<br>Village Jhansla, Tehsil Rajpura, Distt. Patiala-140 401<br>Tel: +91.1762.507084, 507085<br>";
		  
		$rupeeIcon = "<img height='11px' src='".IMG_HTTP_PATH."/rupee-icon.gif'>";    
		

		$content = "";
		$receiptId = htmlentities(add_slashes(trim($REQUEST_DATA['receiptId'])));
		$receiptNo = htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo'])));
		
		if($receiptNo == '') {
		  echo 'Required Parameters Missing';
		  die;
		}
		$condition = '';
		$condition1 = '';
		if($receiptId != ''){
		  $condition = " AND frm.feeReceiptId = '$receiptId' ";
		}
    
       // to fetch student Info 
	  	$feeDataArray = $fineManager->getReceiptDetails($receiptNo,$condition);
		if(count($feeDataArray) == 0){
		  echo "No Data Found.";
		  die;
		}
	 
		$feeContent = '';
		$totalAmount =0;
		$paidAmount = 0;
		$cnt = 1;

		$receiptDate = $feeDataArray[0]['receiptDate'];
        $studentId = $feeDataArray[0]['studentId'];
		$classId = $feeDataArray[0]['classId'];
        $feeReceiptId1 = $feeDataArray[0]['fineReceiptDetailId'];
        $paidAt = $feeDataArray[0]['paidAt'];       
        $regNo = $feeDataArray[0]['regNo'];
        $rollNo = $feeDataArray[0]['rollNo'];
        $className = $feeDataArray[0]['className'];  
        $studentGender = $feeDataArray[0]['studentGender'];
        $instituteAbbr = $feeDataArray[0]['instituteAbbr'];
        $instituteId = $feeDataArray[0]['instituteId'];
		$paidAmount = $feeDataArray[0]['amount'];
    
        
        if($studentGender=='M') {
          $studentGender='S/o';  
        }
        else {
          $studentGender='D/o'; 
        }


        // to fetch payment details
		$condition1 = " AND  frd.fineReceiptDetailId = '$feeReceiptId1' ";
	    $paymentDataArray = $fineManager->getPaymentDetails($receiptNo,$condition1);
		  
            
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

   // Total Fine
   $conditionFine = " AND fs.studentId = '$studentId' AND fs.fineDate <= '$receiptDate' ";
   $fineAddArray = $fineManager->getStudentFineHead($conditionFine);

   // Previous Paid Fine
   $conditionFine = " AND fsd.studentId = '$studentId' AND fsd.receiptDate <= '$receiptDate' AND fsd.fineReceiptDetailId != '$feeReceiptId1' ";
   $prevFinePaidArray = $fineManager->getStudentFineHeadPay($conditionFine);
   
   $prevPaidFine = "0";	
   if(is_array($prevFinePaidArray) && count($prevFinePaidArray)>0) {
	 $prevPaidFine = $prevFinePaidArray[0]['paidAmount'];   
   }  

   for($i = 0;$i<count($fineAddArray);$i++) {
		 if($fineAddArray[$i]['fineAmount'] > $prevPaidFine) {
		   $fineAddArray[$i]['fineAmount'] = $fineAddArray[$i]['fineAmount'] - $prevPaidFine;
		   $prevPaidFine = 0; 
		 }
		 else {
		   $prevPaidFine =  $prevPaidFine - $fineAddArray[$i]['fineAmount'];
		   $fineAddArray[$i]['fineAmount'] = 0; 
		 }
	}


   $totalAmount = '0';
   $srNo='1';
   for($i = 0;$i<count($fineAddArray);$i++){
	   if($fineAddArray[$i]['fineAmount'] > 0) {
         $content .=" <tr>
						<td class='dataFont' align='center'  style='padding-top:4px'>".($srNo)."</td>
			            <td class='dataFont'  style='padding-top:4px' >".$fineAddArray[$i]['fineType']."</td> 
						<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$fineAddArray[$i]['fineAmount']."</td>
		            </tr>";
         $totalAmount += $fineAddArray[$i]['fineAmount'];
		 $totalAmount = number_format((float)$totalAmount, 2, '.', '');
		 $srNo = $srNo + 1;
	   }
    }
    
  
   $balance = 0;
   $balance= $totalAmount - $paidAmount;
   

   $totalFeeContents ='';
   $totalFeeContents = "<table width='55%' border=0 cellspacing=0 cellpadding=0>
                   <tr>    <td class='dataFont'><b>Total Fine Amount</b></td>
                       <td class='dataFont' width='1%'><strong>:</strong></td>
                       <td class ='dataFont' align='right'>".number_format((float)$totalAmount, 2, '.', '')."</td>
                   </tr>
                   <tr>
                       <td class='dataFont' style='padding-top:4px'><b>Paid Fine Amount</b></td>
                       <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                       <td class ='dataFont' align='right' style='padding-top:4px'>".number_format((float)$paidAmount, 2, '.', '')."</td>
                   </tr>";
    
    if($balance!='0') {            
           $totalFeeContents .= "<tr>
                           <td class='dataFont' style='padding-top:4px'><b>Balance Fine Amount</b></td>
                           <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                           <td class ='dataFont' align='right' style='padding-top:4px'>".number_format((float)$balance, 2, '.', '')."</td>
                       </tr>";
    }
                
    $totalFeeContents .= "</table>";
 

    $logoPath = '';
   
	$logoPath = IMG_HTTP_PATH."/logo.gif";
    if($feeDataArray[0]['bankAddress'] ==''){
      $feeDataArray[0]['bankAddress'] ='---';
    }
	
	$paymentHeader= '';
	$paymentMode ='';
	$cashPaid = $paidAmount;
	$modeArray = array(1=>'Cash',2=>'Cheque',3=>'Draft');
	$paymentData = "<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
 
    $paymentHeader = "<table border=1 cellspacing=0 cellpadding=0 width='100%'>
						 <tr>
							<td class='dataFont' width='20%'><b>Instrument</b></td>
							<td class='dataFont' width='20%' style='padding-left:2px;'><b>Number</b></td>
							<td class='dataFont' width='18%' align='right' style='padding-right:2px;'><b>Amount</b></td>
							<td class='dataFont' width='22%' style='padding-left:2px;'><b>Bank Name</b></td>
							<td class='dataFont' width='20%' align='center'><b>Dated</b></td></tr>";
	 $ddNo='';                
	 $ddAmt ='';
	 $ddDate='';
	 $ddBankName='';
  	  
     $condition = " AND fri.fineReceiptDetailId = '".$feeReceiptId1."'";
	 $ddChequeArray = $fineManager->getFineReceiptInstrument($condition); 
	 foreach($ddChequeArray as $key =>$value){
		 if($value['ddType'] == 2 || $value['ddType'] == 3 ) {
			$paymentMode .=    "<tr><td class='dataFont'  style='padding-top:4px'>".$modeArray[$value['ddType']]."</td>
					   <td class='dataFont' style='padding-left:2px;padding-top:4px'>".$value['ddNo']."</td>
					   <td class='dataFont' align='right' style='padding-right:2px;padding-top:4px'>".$value['ddAmount']."</td>
					   <td class='dataFont' style='padding-left:2px;padding-top:4px'>".$value['bankName']."</td>
					   <td class='dataFont' align='center' style='padding-top:4px'>".$value['dated1']."</td></tr>";
		   if($ddNo!='') {
			 $ddNo .=',';                
			 $ddAmt .=',';
			 $ddDate .=',';
			 $ddBankName .=',';  
		   }           
		   $ddNo .= $value['ddNo'];                
		   $ddAmt .= $value['ddAmount'];
		   $ddDate .=$value['dated1'];
		   $ddBankName .=$value['bankName'];
		   $cashPaid = $cashPaid - $value['ddAmount'];
		 }
		 else{
		    $cashPaid = number_format((float)$value['ddAmount'], 2, '.', '');
		 }
	 }
	$paymentHeader .="$paymentMode</table>";
 
	$paymentMode1 = "<b>DD No.&nbsp;:&nbsp;</b> $ddNo <b>Amount&nbsp;:&nbsp;</b> $ddAmt
					  <b>Bank&nbsp;:&nbsp;</b> $ddBankName <b>Dated&nbsp;:&nbsp;</b> $ddDate";          
	  
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
	 
	 if($paidAt=='1') {    
	   $receiptScrollFormat = "Scroll No.";  
	 }
	 else {
	   $receiptScrollFormat = "Receipt No.";    
	 }
 
	$receiptData="<table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >";
                 
	if($paidAt=='1') {               
		 $receiptData .="<tr class='dataFont'>
						<td class='dataFont' colspan=2 style='padding-top:4px'><b>Bank Name&nbsp;:&nbsp;</b>".$feeDataArray[0]['bankAbbr']."<span style='float:right'><b>A/C No.</b>&nbsp;:&nbsp;".$feeDataArray[0]['instituteBankAccountNo']."</span></td>
					  </tr>";
	}  
            
    $num = new NumberToWord($paidAmount);
    $num1 = trim(ucwords(strtolower($num->word)));
    if($num1!='') {
      $num1 .=" Only";  
    }            
               
	$receiptData .="<tr class='dataFont' style='display:none'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'><table width='100%' border=0 cellspacing=0 cellpading=0><tr><td valign='top' width='70px'><b>Bank Addr.&nbsp;:&nbsp;</b></td><td valign='top' >".$feeDataArray[0]['bankAddress']."</td></tr></table></td> </tr>
                   

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
                 <tr class='dataFont'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'>
                        <b>$receiptScrollFormat&nbsp;:&nbsp;</b>".$feeDataArray[0]['fineReceiptNo']."
                        <span style='float:right'>
                         <b>Date&nbsp;:</b>&nbsp;".date('d-m-y',strtotime($feeDataArray[0]['receiptDate']))."</span> 
                    </td>  
                 </tr>
                 <tr class='dataFont'>
                     <td colspan='2' align='center' style='padding-top:10px'><b><br></b></td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px'  valign='top' colspan = 2> 
                      Received with thanks from &nbsp;<b>".$feeDataArray[0]['studentName']."</b> &nbsp;$studentGender <b>Sh.&nbsp;".$feeDataArray[0]['fatherName']."</b></td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px' align='left' colspan=2>
                      <table width='100%'>
                         <tr>
                           <td class='dataFont' width='2%' nowrap>
                             Course&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='48%' nowrap>
                             <b>".$feeDataArray[0]['degreeAbbr']."</b>
                           </td> 
                           <td class='dataFont' width='2%' nowrap>
                             Branch&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='48%' nowrap>
                             <b>".$feeDataArray[0]['branchCode']."</b>
                           </td>
                         </tr>
                          <tr>
                          <td class='dataFont' width='2%' nowrap>
                              Semester&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='2%' nowrap>
                             <b>".$feeDataArray[0]['feeStudyPeriodName']."</b>
                           </td>                 
                           <td class='dataFont' width='2%' nowrap>
                              Reg No.&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='2%' nowrap>
                             <b>".$regNo."</b>
                           </td>
                         </tr>
                         <tr>
                           <td class='dataFont' width='2%' nowrap>
                              Roll No.&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='2%' nowrap>
                             <b>".$rollNo."</b>
                           </td>
                         </tr>
					   </table>       
                    </td>
                 </tr>  
                 <tr>
                    <td class='dataFont' style='padding-top:4px' align='left' colspan=2 colspan=2>
                      a sum of $rupeeIcon&nbsp;<b><u>".number_format((float)$paidAmount, 2, '.', '')."/- (Rs. $num1)</u></b>&nbsp;towards the following :</td>
                 </tr>
                 <tr>
                     <td colspan='2' style='padding-top:8px'>   
                        <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
                           <tr>
                               <td class='dataFont' align='center' width='5%'><strong>#</strong></td>
                               <td class='dataFont'  width='43%'><strong>Particulars</strong></td>
                               <td class='dataFont' align='right' width='25%'><strong>Amount $rupeeIcon</strong></td>
                           </tr> 
                            ".$content." 
                        </table>
                     </td>
                 </tr> 
                     <tr>
                   <td colspan = 3 align='right' style='padding-top:10px;'>
                        $totalFeeContents 
                   </td>
                   </tr> 
               
                 <tr><td class='dataFont' colspan='2' height='6px' style='padding-top:10px;'><strong>Payment Details</strong></td></tr>
                 <tr><td class='dataFont' colspan='2' align='left'><b><u></u></b></td></tr>
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>   
                 <tr>
                 <td colspan = 3>
                        $paymentData
                   </td>
                </tr>
                   <tr><td height=10></td></tr>";
                    

                $receiptData .="<tr><td height=10></td></tr>
                                <tr>
                                   <td height='40px' valign='bottom' class='dataFont' align='left'><b>".strtoupper($sessionHandler->getSessionVariable('UserName'))."</b></td> 
                                   <td height='40px' valign='bottom' class='dataFont' align='right'><b>Authorised Signatory</b></td> 
                                </tr>               
                                <tr><td height=20></td></tr> 
                                <tr>
                                   <td class='dataFont' colspan='2' align='center'>
                                      $address 
                                   </td>
                               </tr>      
                              </table>";
            
    $paymentReceiptPrint = "<table width='98%' border='0px' cellpadding='0px' cellspacing='0px' align='center'>
               <tr>
				   <td width='45%'>".$receiptData."</td>
				   <td width='10%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt= ''></td>
				   <td width='45%'>".$receiptData."</td>
               </tr>
               <tr>
				   <td height=20></td>
			   </tr> 
               <tr>
				   <td colspan=5 align='right'>
					  <span id='hidePrint'>
						<input type='image'  src=".IMG_HTTP_PATH.'/print.gif'." onClick=printout(); title=Print>
					  </span>
				   </td>
               </tr>
            </table>";

    echo $paymentReceiptPrint;

?>  
