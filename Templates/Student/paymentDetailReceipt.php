<?php 
//This file is used as printing version for payment receipt.
//
// Author :Rajeev Aggarwal
// Created on : 29-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	require_once(BL_PATH . '/NumToWord.class.php');
	 
	/// Search filter /////  
	if(UtilityManager::notEmpty($REQUEST_DATA['receiptId'])) {

	   $condition .= ' fr.feeReceiptId ='.$REQUEST_DATA['receiptId'];         
	   $condition1 = ' WHERE feeReceiptId ='.$REQUEST_DATA['receiptId'];         
	}
    //$recordArray = $studentManager->getFeesReceiptDetail($condition);
     $recordArray = $studentManager->getFeesReceiptDetailNew($condition);
	//echo $recordArray[0]['feeStudyPeriodId'];
	$studentFeesArray = $studentManager->getStudentHeadDetailClass($recordArray[0]['studentId'],$recordArray[0]['feeCycleId'],$REQUEST_DATA['feeType'],$recordArray[0]['feeStudyPeriodId']);
	$bankArray = $studentManager->getIssueBankDetail($recordArray[0]['issuingBankId']);
	$payableBankArray = $studentManager->getFavBankDetail($recordArray[0]['favouringBankBranchId']);
	$studentFeesDetailArray = $studentManager->getFeeDetailClass($condition1);
	$studentFeesConcessionArray = $studentManager->getFeeConcessionClass($recordArray[0]['studentId'],$recordArray[0]['feeCycleId']);
	$recordConcessionCount = count($studentFeesConcessionArray);
	if($recordConcessionCount >0 && is_array($studentFeesConcessionArray) ) { 
					
		for($i=0; $i<$recordConcessionCount; $i++ ) {

			$querySeprator = '';
			if($insertValue!=''){

				$querySeprator = ",";
			}
			$insertValue .= "$querySeprator ".$studentFeesConcessionArray[$i]['reason'];

			//$concessionReason .= $studentFeesConcessionArray[$i]['reason'].",";
		}
	}
	//echo $insertValue;
	//echo "<pre>";
	//print_r($studentFeesConcessionArray);
	$studentPrevFeesArray = $studentManager->getPreviousPayment($recordArray[0]['studentId'],$recordArray[0]['feeCycleId'],$REQUEST_DATA['receiptId']);
	//echo "-------------".$studentPrevFeesArray[0]['previousPayment'];
	//echo '<pre>';
	//print_r($studentFeesDetailArray);
	//echo count($feeReceiptPrintArr);
	// print_r($bankArray);
	$cnt=1;
	/*if($recordArray[0]['paymentInstrument']==1){
	
		$feeReceiptPrintArr= $feeReceiptCashPrintArr;
	}else{
		
		$feeReceiptPrintArr=	$feeReceiptChequePrintArr;
	}*/
?>
	<table border="0" cellspacing="0" cellpadding="0" width="730px">
	<tr>

	
<?php
	foreach($feeReceiptPrintArr as $feeReceiptPrint){

		$amtPayable = 0;
		$amountTotalPending=0;	
		$amountTotalConcession=0;
?>

<td align="center" width="50%">	
	<table border="0" cellspacing="3" cellpadding="3" width="335" align="center">
	<tr>
		<td <?php echo $reportManager->getReportDataStyle()?> align="right" colspan="5"><nobr><?php echo $feeReceiptPrint?></nobr></td>
	</tr>
	<tr>
		<td align="left" colspan="1"><?php echo $reportManager->showHeader();?></td> 
		<th align="center" <?php echo $reportManager->getReportTitleStyle();?> width="100%"  style="font-size:22px" nowrap><?php echo $reportManager->getInstituteName(); ?><br><span  <?php echo $reportManager->getReportHeadingStyle(); ?>  style="font-size:14px"><?php echo $reportManager->getInstituteAddress()."<br>Ph.:&nbsp;".$reportManager->getInstituteTelephone(); ?></span><br><span  style="font-size:13px">RECEIPT FOR FEE & CHARGES</span></th>
	</tr>
	</table> <br>
	<table cellspacing="0" cellpadding="3" border="0" width="225" align="center">
	<?php
		if($payableBankArray[0]['accountNumber']){
	?>
	<tr>
		<td <?php echo $reportManager->getReportDataStyle()?> align="right" colspan="5"style="font-size:14px"><nobr><B>Bank A/C No: <?php echo $payableBankArray[0]['accountNumber'];?></B></nobr></td>
	</tr>
	 
	<?php
		}	
	?>
	<tr>
		<td colspan="4">
		<table cellspacing="0" cellpadding="3" border="0" width="335">
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> align="left" colspan="2"style="font-size:14px"><nobr>Date: <B><?php echo (UtilityManager::formatDate($recordArray[0]['receiptDate']));?></B></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> align="right" colspan="2"style="font-size:14px"><nobr>Receipt No.: <B><?php echo $recordArray[0]['receiptNo']?></B></nobr></td>
		</tr>
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?>style="font-size:14px" nowrap align="left">Received from <?php
			if($recordArray[0]['studentGender']=="M") echo "Mr."; else if($recordArray[0]['studentGender']=="F") echo "Miss"; else echo "Mr/Miss";
			?></td>
			<td valign="top" class="receiptClass" align="left" style="font-size:14px" nowrap>&nbsp;<b><?php echo ucwords(strtolower($recordArray[0]['firstName'].' '.$recordArray[0]['lastName']))?></b></td>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> align="right" style="font-size:14px"><?php
			if($recordArray[0]['studentGender']=="M") echo "S/o"; else echo "D/o";
			?> </td>
			<td valign="top" class="receiptClass" nowrap style="font-size:14px">&nbsp;<B><?php echo ucwords(strtolower($recordArray[0]['fatherName']))?></B></td>
		</tr>
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?>  colspan="6" nowrap  align="left"style="font-size:14px">Semester:<i class="receiptClass"  style="font-size:16px;">&nbsp;<?php echo $recordArray[0]['periodName']?>&nbsp;&nbsp;</i>&nbsp;&nbsp;Branch:<i class="receiptClass"style="font-size:14px">&nbsp;<?php $branchArr = explode('-',$recordArray[0]['className']);
			echo $branchArr[3];
			?></i></td>
		</tr>
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?>  colspan="6" nowrap  align="left"style="font-size:14px">Roll No.(Reg No.)&nbsp;<i class="receiptClass"style="font-size:14px"><?php echo $recordArray[0]['rollNo'].'('.$recordArray[0]['regNo'].')';?></i></td>
		</tr> 
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> nowrap  colspan="3" align="left"style="font-size:14px">a sum of Rs&nbsp;<span  class="receiptClass" style="font-size:14px"><?php echo number_format($recordArray[0]['totalAmountPaid'],'0','.','')?>/-</span>&nbsp;towards the following:</td>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> colspan="3" ></td>
		</tr>
		 
		<tr>
			<td valign="top" colspan="4">
			<table border='1' cellspacing='0' cellpadding='4' class="reportTableBorder" width="400" align="center">
			<tr>
				<td valign="middle" width="8%" height="25" align="center" <?php echo $reportManager->getReportDataStyle()?> style="font-size:14px"><B>Sr.No</B></td>
				<td valign="middle" width="78%" <?php echo $reportManager->getReportDataStyle()?> style="font-size:14px"><B>Particulars</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right" style="font-size:14px"><B>Amount</B></td>
			</tr>
			<?php
				$j=1;
				 $recordCount = count($studentFeesArray);
				if($recordCount >0 && is_array($studentFeesArray) ) { 
					
					for($i=0; $i<$recordCount; $i++ ) {
						
						$bg = $bg =='row0' ? 'row1' : 'row0';
						
						$totalAmount = number_format($studentFeesArray[$i]['feeHeadAmount'],'2','.','');
						$amountConcession = number_format($studentFeesArray[$i]['discountedAmount'],'2','.','')."<br>";
						$amountTotalPending  += $totalAmount;
						$amountTotalConcession  += $amountConcession;
						  
					echo '<tr>
							<td valign="middle" align="center" '.$reportManager->getReportDataStyle().' style="font-size:14px">'.($j).'</td>
							<td valign="top" '.$reportManager->getReportDataStyle().' align="left" style="font-size:14px">'.$studentFeesArray[$i]['headName'].'</td>
							<td valign="top" align="right" '.$reportManager->getReportDataStyle().' style="font-size:14px">'.$totalAmount.'</td>
						</tr>';
						$j++;
					}
					 
				}
				else {
					echo '<tr><td colspan="4" align="center">No record found</td></tr>';
				}
				$amtPayable = $amountTotalPending+$recordArray[0]['fine'];
				if(($REQUEST_DATA['feeType']==1) || ($REQUEST_DATA['feeType']==4)){
			?>
			<tr>
				<td valign="middle" width="8%" align="center" <?php echo $reportManager->getReportDataStyle()?> style="font-size:14px"><?php echo $j?></td>
				<td valign="middle" width="78%" <?php echo $reportManager->getReportDataStyle()?>  align="left"style="font-size:14px">Fine</td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right" style="font-size:14px"><?php echo number_format($recordArray[0]['fine'],'2','.','')?></td>
			</tr>
			 
			<tr>
				<td valign="middle" width="8%" align="center" <?php echo $reportManager->getReportDataStyle()?> height="25"></td>
				<td valign="middle" width="78%" <?php echo $reportManager->getReportDataStyle()?> align="right"style="font-size:14px"><B>Total Amount Payable</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right"style="font-size:14px"><?php echo number_format($amtPayable,'2','.',''); ?></td>
			</tr>
			<?php
				}	
			?>
			</table>
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="4">
			<table border='0' cellspacing='0' cellpadding='3' width="400" align="center">
			
			<?php
				 
				if($amountTotalConcession!=""){
			?>
			<tr>
				<td valign="middle" colspan="2" <?php echo $reportManager->getReportDataStyle()?>><?php echo $insertValue;?></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right" style="font-size:14px"><B>Concession</B>&nbsp;</td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right"style="font-size:14px"><?php echo number_format($amountTotalConcession,'2','.','');?></td>
			</tr>
			<?php
			}	

			//$prePaid = $recordArray[0]['totalFeePayable']-$recordArray[0]['discountedFeePayable'];
			if($studentPrevFeesArray[0]['previousPayment']){
			?>
			<tr>
				<td valign="middle" colspan="2"></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right"  nowrapstyle="font-size:14px"><B>Previous Paid</B>&nbsp;</td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right" width="14%"style="font-size:14px"><?php echo number_format($studentPrevFeesArray[0]['previousPayment'],'2','.','')?></td>
			</tr>
			<?php
			}
			
			?>
			<tr>
				<td valign="middle" colspan="2"></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right"  nowrapstyle="font-size:14px"><B>Amount Paid</B>&nbsp;</td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right" width="14%"style="font-size:14px"><?php echo number_format($recordArray[0]['totalAmountPaid'],'2','.','')?></td>
			</tr>
			<?php
				if($recordArray[0]['previousDues']!="0.00"){
			?>
			<tr>
				<td valign="middle" colspan="2"></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right"  nowrapstyle="font-size:14px"><B>Pending Amount</B>&nbsp;</td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align="right" style="font-size:14px"><?php echo number_format($recordArray[0]['previousDues'],'2','.','')?></td>
			</tr>
			<?php
				}	
			?>
		</table>
		</td>
		</tr>
		 
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> align="right"style="font-size:14px" colspan="4">Rupees:&nbsp;<i><?php 
					$num = new NumberToWord($recordArray[0]['totalAmountPaid']);
					echo ucwords(strtolower($num->word)).' Only';?></i></td>
		</tr>
		<?php
			if($recordArray[0]['printRemarks']!=""){
		?>
		<tr>
			<td height="10"></td>
		</tr> 
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> align="left" style="font-size:12px" colspan="4">&nbsp;<i><?php 
					 
					echo $recordArray[0]['printRemarks'];?></i></td>
		</tr>
		<?php
					}				
		?>
		 
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> colspan='6' align="left">
			<table border='1' cellspacing='0' cellpadding='3' width='400px' class="reportTableBorder">
			<tr>
				<td colspan='4' align="left" style="font-size:12px"><B>Payment Mode:</B></td>
			</tr>
			<tr>
				<?php
				if($recordArray[0]['cashAmount']!='0.00'){		
				?>
				<td><B>By Cash:</B></td>
				<td <?php echo $reportManager->getReportDataStyle()?>><?php echo $recordArray[0]['cashAmount']?></td>
				<?php
				}
				$recordCount = count($studentFeesDetailArray);
				if($recordCount >0 && is_array($studentFeesDetailArray) ) { 
					
					echo "<tr><td><B>Instrument</B></td><td><B>Bank</B></td><td><B>Instrument No.</B></td><td><B>Issuing Date</B></td><td><B>Amount</B></td></tr>";
					for($i=0; $i<$recordCount; $i++ ) {

						$instrumentDate=UtilityManager::formatDate($studentFeesDetailArray[$i]['instrumentDate']);
						echo "<tr><td><b>".$modeArr[$studentFeesDetailArray[$i]['paymentInstrument']].":</b></td>";
						echo "<td align='left'>".$studentFeesDetailArray[$i]['bankName']."</td>";
						echo "<td align='left'>".$studentFeesDetailArray[$i]['instrumentNo']."</td>";
						echo "<td align='left'>".$instrumentDate."</td>";
						echo "<td align='left'>".$studentFeesDetailArray[$i]['totalAmount']."</td></tr>";
					}
				}
			?>
			</tr>
			</table></td>
			
		</tr>
		<tr>
			<td height="40"></td>
		</tr>  
		<tr>
			<td align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> colspan="2" nowrap><?php echo $reportManager->showFooter(); ?></td>
			<td valign="top"<?php echo $reportManager->getReportDataStyle()?> colspan="3" align="right" nowrap>&nbsp;Authorised Signatory</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	 </td>
	 <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
	 
<?php
	/*if($cnt!=count($feeReceiptPrintArr)){

		echo '<br class="page" />';
		$cnt++;
	}*/
	
}
?>
	<tr>
	</table>

<?php
// $History: paymentDetailReceipt.php $
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/Student
//updated with all the fees enhancements
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10-03-08   Time: 11:21a
//Updated in $/LeapCC/Templates/Student
//issue received during CIET implementation
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-11-21   Time: 3:52p
//Updated in $/LeapCC/Templates/Student
//Added Student search,receipt no manual and fee type functionality in
//collect fees
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-02   Time: 3:03p
//Updated in $/LeapCC/Templates/Student
//Updated with config parameter which has been removed from
//common.inc.php
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/04/08    Time: 2:48p
//Updated in $/Leap/Source/Templates/Student
//updated with concession and other parameters in list
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:07p
//Created in $/Leap/Source/Templates/Student
//intial checkin

?>    