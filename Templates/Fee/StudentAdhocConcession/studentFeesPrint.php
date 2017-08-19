<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css" media="print">
@page port {size: portrait;}
@page land {size: landscape;}
.portrait {page: port;}
.landscape {page: land;}
</style>
</head>
<body>

<?php
//This file is used as printing version for student profile.
//
// Author :Nishu Bindal
// Created on : 14-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	global $sessionHandler;
	require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    	$studentFeeManager = StudentFeeManager::getInstance();
    	
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
	$userId = $sessionHandler->getSessionVariable('UserId');
	if($userId==''){
		echo 'Required Parameters Missing';
		die;
	}
	
	$feesClassArray = $studentFeeManager->getFeeClass(); 
	$classId = $feesClassArray[0]['feeClass'];	
	$condition = "AND stu.userId = '$userId'";	
	$studentFeesArray = $studentFeeManager->getStudentDetailClass($condition,$classId); 
	//print_r($studentFeesArray); die; 
        if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {
           if($studentFeesArray[0]['feeClassId']==-1) {
              echo FEE_HEAD_NOT_DEFINE;
              die;
           }
        }
        else{
           echo STUDENT_NOT_EXIST;  
           die; 
        }
	$studentId = $studentFeesArray[0]['studentId'];  
        $quotaName = $studentFeesArray[0]['quotaName'];  
        $isLeet = $studentFeesArray[0]['isLeet'];  
        $studentName = $studentFeesArray[0]['firstName']." ".$studentFeesArray[0]['lastName'];
        $feeStudyPeriodName = $studentFeesArray[0]['feeStudyPeriodName'];
        $adhocCondition =" AND acm.feeClassId = $classId AND acm.studentId = $studentId "; 
        $adhocArray=$studentFeeManager->getStudentAdhocConcession($adhocCondition);
        
         $feeArray = $studentFeeManager->getStudentFeeHeadDetail($classId,$quotaId,$isLeet,$studentId);
    
       	
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
  for($i = 0;$i<count($feeArray);$i++){
                        $feeContent .=" <tr>
                        	<td class='dataFont' align='center'  style='padding-top:4px'>".($i + 1)."</td>
                              <td class='dataFont'  style='padding-top:4px' >".$feeArray[$i]['headName']."</td> 
                              <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$feeArray[$i]['feeHeadAmt']."</td>
                           </tr>";
                           $totalAmount += $feeArray[$i]['feeHeadAmt'];
                           
                        }
                        
  $concession = "---";
  $payableAmount = $totalAmount;
 if($adhocArray[0]['adhocAmount'] != ''){
 	$concession = $adhocArray[0]['adhocAmount'];
 	$payableAmount = $payableAmount - $concession;
 	
 }
 $feeContent .=" <tr>
                	<td class='dataFont' style='padding-top:4px' align='center'>".(count($feeArray) + 1)."</td>
                      <td class='dataFont' style='padding-top:4px'>Concession</td>
                      <td class='dataFont'  align='right' nowrap style='padding-top:4px'>".number_format((float)$concession, 2, '.', '')."</td>
                   </tr>";
  	
               
  $feeContent .=" <tr>
                	<td class='dataFont' style='padding-top:4px'>&nbsp;</td>
                      <td class='dataFont' style='padding-top:4px'><strong>Payable Amount</strong></td>
                      <td class='dataFont'  align='right' nowrap style='padding-top:4px'>".number_format((float)$payableAmount, 2, '.', '')
."</td>
                   </tr>";
 
 $receiptData="<table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                <tr class='dataFont'>
                     <td align='left' colspan='2' style='padding-top:20px'>
                        <table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                          <tr>
                             <td align='left'>
                               <InstituteLogo>       
                             </td>
                             <td align='center'>
                                <b><InstituteName><InstituteAddress></b>
                             </td>   
                          </tr>  
                        </table>
                     </td>
                 </tr> 
                 <tr class='dataFont'>
                     <td colspan='2' align='center'><b>FEE RECEIPT</b></td>   
                 </tr> 
                 
                 <tr class='dataFont'>
                     <td width = 29% align='left' style='padding-top:4px'><b>Receipt No.</b><ReceiptNo></td>   
                     <td align='right' style='padding-top:4px'><b>Date:&nbsp;</b>".date('d-m-y')."&nbsp;</td>   
                 </tr> 
                 <tr>
                    <td class='dataFont' style='padding-top:4px'> 
                       <b>Student Name</b>
                    </td>
                    <td class='dataFont' style='padding-top:4px'><b>:</b>&nbsp;$studentName</td>
                 </tr>
                 <tr>
                 	<td class='dataFont' style='padding-top:4px'> 
                 		<b>Father's Name</b>
                 	</td>
                 	<td class='dataFont' style='padding-top:4px'><b>:&nbsp;</b>".$studentFeesArray[0]['fatherName']."</td>
                 </tr>
                
                   <tr>
                    <td class='dataFont' align='left' style='padding-top:4px'>
                        <b>Degree</b></td>
                     <td class='dataFont' style='padding-top:4px'><b>:</b>&nbsp;".$studentFeesArray[0]['degreeName']."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px' align='left'>
                        <b>Branch</b></td>
                     <td class='dataFont' style='padding-top:4px'><b>:</b>&nbsp;".$studentFeesArray[0]['branchName']."</td>
                 </tr>
                  <tr>
                    <td class='dataFont' style='padding-top:4px' align='left'>
                        <b>Semester</b></td>
                     <td class='dataFont' style='padding-top:4px'><b>:</b>&nbsp;$feeStudyPeriodName <span  style='float:right'><b>Roll No.&nbsp;:&nbsp;</b>".$studentFeesArray[0]['rollNo']."</span></td>
                 </tr>
                 <tr>
                     <td colspan='2' style='padding-top:8px'>   
                        <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
                           <tr>
                               <td class='dataFont' align='center' width='5%'><strong>#</strong></td>
                               <td class='dataFont'  width='40%'><strong>Particulars</strong></td>
                               <td class='dataFont' align='right' width='50%'><strong>Amount</strong></td>
                           </tr> 
                            ".$feeContent." 
                         
                        </table>
                     </td>
                 </tr> 
               
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>
                 <tr><td class='dataFont' colspan='2' align='left'><b><u></u></b></td></tr>
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>   
                  
                 <tr>
                    <td class='dataFont' colspan='3'><b>Cash&nbsp;/&nbsp;DD No.&nbsp;</b>............................................................</td>
                 </tr>
                  <tr>
                    <td class='dataFont' colspan='3' style='padding-top:5px'>..................................................<b>&nbsp;Dated&nbsp;</b>.......................</td>
                 </tr>
                  <tr>
                    <td class='dataFont' colspan='3' style='padding-top:5px'><b>Bank Name&nbsp;</b>................................................................</td>
                 </tr>
                 <tr>
                    <td class='dataFont' colspan='3' style='padding-top:5px'>......................................................................................</td>
                 </tr>     
                
                 <tr>
                   <td  valign='bottom' class='dataFont' colspan=3 style='padding-top:50px'><b>Depositor's Singnature</b> <span  style='float:right'>  <b>Authorised Signatory</b></span></td> 
             
                 </tr>                       
              </table>";

  
              
	echo $paymentReceiptPrint = "<table width='98%' border='0px' cellpadding='0px' cellspacing='0px' align='center'>
               <tr>
                 <td width='30%'>".$receiptData."</td>
                 <td width='5%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt= ''></td>
                 <td width='30%'>".$receiptData."</td>
                <td width='5%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt ='' ></td>
                 <td width='30%'>".$receiptData."</td>
               </tr>
               <tr>
               	<td height=20></td>
               </tr>
               <tr>
               <td colspan=5 align='right'><span id='hidePrint'><input type='image'  src=".IMG_HTTP_PATH.'/print.gif'." onClick=printout(); title=Print></span></td>
               </tr>
               </table>";
        ?>  
        
        </body>
        </html>
