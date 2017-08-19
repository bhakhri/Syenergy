<?php
//--------------------------------------------------------
//This file is used as printing version for student profile.
// Author :Nishu Bindal
// Created on : 14-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
            
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    global $sessionHandler;
    require_once(MODEL_PATH . "/Fee/PaymentHistoryReportManager.inc.php");   
    $PaymentHistoryReportManager = PaymentHistoryReportManager::getInstance();
        
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(BL_PATH . '/NumToWord.class.php');  
    
    
    $address  = "All disputes subject to Chandigarh Jurisdiction.";
    $address .= "<hr>";
    //$address .= "Chandigarh - Patiala National Highway (NH-64),<br>Village Jhansla, Tehsil Rajpura, Distt. Patiala-140 401<br>Tel: +91.1762.507084, 507085<br>";
    $address .= "Pinjore - Barotiwala Highway (NH-21A), Atal Siksha Kunj,<br>Atal Nagar Vill. Kalujhanda Distt. Solan, Himachal Pradesh 174 103<br>Tel: +91.1795.661011 Fax: +91.1795.661013<br>";
      
    $rupeeIcon = "<img height='11px' src='".IMG_HTTP_PATH."/rupee-icon.gif'>";    
    
    
    $receiptId = $REQUEST_DATA['receiptId'];
    $receiptNo = $REQUEST_DATA['receiptNo'];
    
    if($receiptNo == '') {
        echo 'Required Parameters Missing';
        die;
    }
    $condition = '';
    $condition1 = '';
    if($receiptId != ''){
      $condition = " AND frm.feeReceiptId = '$receiptId' ";
    }
    
    // to fetch student Info & heads amount
          $feeDataArray = $PaymentHistoryReportManager->getReceiptDetails($receiptNo,$condition);
          if(count($feeDataArray) == 0){
              echo "No Data Found.";
              die;
          }
        
        $ttStudentId = $feeDataArray[0]['studentId'];
        $ttClassId = $feeDataArray[0]['feeClassId'];
        $ttFeeReceiptId = $feeDataArray[0]['ttFeeReceiptId'];
        $showDataArray = $PaymentHistoryReportManager->getStudentFeeDetails($ttStudentId, $ttClassId, $ttFeeReceiptId);
        
        $hostelDescId = '';
        $transportDescId='';               
      
        $hostelDesc = "<tr>
                            <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                              Hostel:</b>&nbsp;</b>
                            </td>
                            <td class='dataFont' style='padding-top:4px' colspan='2' nowrap><b>".$showDataArray[0]['hostelName']." (".$showDataArray[0]['roomName'].")</b></td>
                         </tr>";
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
                        
      if($showDataArray[0]['hostelName']!='') {
        $hostelDescId='1';  
      }               
     
     $transportDesc = "<tr>
                            <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                              <b>Route<b>:</b>&nbsp;
                            </td>
                            <td class='dataFont' style='padding-top:4px' colspan='2' nowrap>".
                            $showDataArray[0]['routeName']." (".$showDataArray[0]['cityName'].")</td>
                         </tr>";
        
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
    
        
        if($studentGender=='M') {
          $studentGender='S/o';  
        }
        else {
          $studentGender='D/o'; 
        }
        
          $condition1 = " AND    frd.feeReceiptId = '$feeReceiptId1' ";
          // to fetch payment details
          $paymentDataArray = $PaymentHistoryReportManager->getPaymentDetails($receiptNo,$condition1);
          
   // print_r($feeDataArray); die;
          // to get Already paid fees 
          $prevPaidAmountArr = $PaymentHistoryReportManager->getTotalAlreadyPaid($feeReceiptId1,$installmentNo);
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
    
    if($feeDataArray[0]['hostelFees'] > 0 && ($feeDataArray[0]['hostelId'] != '' && $feeDataArray[0]['hostelRoomId'] != '')){
        
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

    
    if($feeDataArray[0]['transportFees'] > 0 && ($feeDataArray[0]['busRouteId'] != '' && $feeDataArray[0]['busStopId'] != '')){
        $transportFee = $feeDataArray[0]['transportFees'];
        $feeContent .=" <tr>
        <td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
        <td class='dataFont'  style='padding-top:4px' >Transport Fees/Caution Money</td> 
        <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$transportFee, 2, '.', '')."</td>
        </tr>";
        //<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$paidTransportFee, 2, '.', '')."</td>
        $totalAmount += $transportFee;
        $paidAmount += $paidTransportFee;
        $cnt++;
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
    

  
   $balance = 0;
   $balance= $totalAmount - $paidAmount;
   $concessionData = "";
   
   if($feeDataArray[0]['paidAmount'] > 0 && $feeDataArray[0]['concession'] > 0){
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
   if($previousPaid > 0){
       $concessionData .= "<tr><td class='dataFont' style='padding-top:4px'><b>Prev. Paid Amount</b></td>
                   <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                   <td class='dataFont' align='right' style='padding-top:4px'>".number_format((float)$previousPaid, 2, '.', '')."</td></tr>";
       $balance = $balance - $previousPaid;
   }
   
   $totalFeeContents ='';
   $totalFeeContents = "<table width='55%' border=0 cellspacing=0 cellpadding=0>
                   <tr>    <td class='dataFont'><b>Total Fees</b></td>
                       <td class='dataFont' width='1%'><strong>:</strong></td>
                       <td class ='dataFont' align='right'>".number_format((float)$totalAmount, 2, '.', '')."</td>
                   </tr>
                   <tr style='display:none'>    
                    <td class='dataFont'><b>Installment No.</b></td>
                       <td class='dataFont' width='1%'><strong>:</strong></td>
                       <td class ='dataFont' align='right'>".$installmentNo."</td>
                   </tr>
                   $concessionData
                   <tr>
                       <td class='dataFont' style='padding-top:4px'><b>Curr. Paid Amount</b></td>
                       <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                       <td class ='dataFont' align='right' style='padding-top:4px'>".number_format((float)$paidAmount, 2, '.', '')."</td>
                   </tr>";
    
    if($balance!='0') {            
           $totalFeeContents .= "<tr>
                           <td class='dataFont' style='padding-top:4px'><b>Balance</b></td>
                           <td class='dataFont' width='1%' style='padding-top:4px'><strong>:</strong></td>
                           <td class ='dataFont' align='right' style='padding-top:4px'>".number_format((float)$balance, 2, '.', '')."</td>
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
 $modeArray = array(1=>'Cash',2=>'Cheque',3=>'Draft');
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
     if($value['paymentMode'] != 1) {
        $paymentMode .=    "<tr><td class='dataFont'  style='padding-top:4px'>".$modeArray[$value['paymentMode']]."</td>
                   <td class='dataFont' style='padding-left:2px;padding-top:4px'>".$value['number']."</td>
                   <td class='dataFont' align='right' style='padding-right:2px;padding-top:4px'>".$value['amount']."</td>
                   <td class='dataFont' style='padding-left:2px;padding-top:4px'>".$value['bankName']."</td>
                   <td class='dataFont' align='center' style='padding-top:4px'>".$value['dated1']."</td></tr>";
       if($ddNo!='') {
         $ddNo .=',';                
         $ddAmt .=',';
         $ddDate .=',';
         $ddBankName .=',';  
       }           
       $ddNo .= $value['number'];                
       $ddAmt .= $value['amount'];
       $ddDate .=$value['dated1'];
       $ddBankName .=$value['bankName'];
     }
     else{
         $cashPaid = number_format((float)$value['amount'], 2, '.', '');
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
                        <b>$receiptScrollFormat&nbsp;:&nbsp;</b>".$feeDataArray[0]['receiptNo']."
                        <span style='float:right'>
                         <b>Date&nbsp;:</b>&nbsp;".date('d-m-y',strtotime($paymentDataArray[0]['receiptDate']))."</span> 
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
                             <b>".$feeStudyPeriodName."</b>
                           </td>                 
                         </tr>
                         <tr>
                           <td class='dataFont' width='2%' nowrap>
                              Id Number&nbsp;:&nbsp;
                           </td>
                           <td class='dataFont' width='2%' nowrap>
                             <b>".$rollNo."</b>
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
                            ".$feeContent." 
                         
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
            
    echo $paymentReceiptPrint = "<table width='98%' border='0px' cellpadding='0px' cellspacing='0px' align='center'>
               <tr>
                 <td width='45%'>".$receiptData."</td>
                 <td width='10%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt= ''></td>
                 <td width='45%'>".$receiptData."</td>
               </tr>
               <tr><td height=20></td></tr> 
               <tr>
               <td colspan=5 align='right'><span id='hidePrint'><input type='image'  src=".IMG_HTTP_PATH.'/print.gif'." onClick=printout(); title=Print></span></td>
               </tr>
               </table>";
             
        ?>  
        
