<?php 
//This file is used as printing version for payment receipt.
//
// Author :Rajeev Aggarwal
// Created on : 29-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/FeeReportManager.inc.php");
    $feeReportManager = FeeReportManager::getInstance();
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    
	require_once(BL_PATH . '/NumToWord.class.php');
	 
    $feeReceiptId =0; 
	/// Search filter /////  
	if(UtilityManager::notEmpty($REQUEST_DATA['receiptId'])) {
       $feeReceiptId = $REQUEST_DATA['receiptId'];
	}
    $condition = " AND f.feeReceiptId = $feeReceiptId AND IFNULL(f.reasonOfCancellation,'') = '' ";          
    
	if($REQUEST_DATA['feeType']==2){
		$feeType = "Transport ";
	}
    
	if($REQUEST_DATA['feeType']==3){
		$feeType = "Hostel ";
	}
    
	global $sessionHandler;
	$feeFavourText = $sessionHandler->getSessionVariable('FEE_FAVOUR_TEXT');
    $recordArray = $feeReportManager->getStudentReceiptPrintDetail($condition);

    $condition = " f.feeReceiptId = $feeReceiptId AND IFNULL(f.reasonOfCancellation,'') = '' ";    
    $foundArray = $feeReportManager->getPreviousFeePaymentDetail($condition);    
    $totalAmountPaid=0;
    for($j=0;$j<count($foundArray);$j++) {
      $totalAmountPaid += ($foundArray[$j]['prevHostelPaid']+$foundArray[$j]['prevTransportPaid']+$foundArray[$j]['prevFeePaid']);
      $totalAmountPaid += ($foundArray[$j]['prevHostelFine']+$foundArray[$j]['prevTransportFine']+$foundArray[$j]['prevFeeFine']);
    }
    

	$bankArray = $studentManager->getFavBankDetail($recordArray[0]['favouringBankBranchId'])
?>
	<table border="0" cellspacing="0" cellpadding="0" width="650" align="center">
	<tr>
		<td align="left" colspan="1" width="25%"><?php echo $reportManager->showHeader();?></td> 
		<th align="left" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
	</tr>
	<tr><th colspan="2"  <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->getInstituteAddress()."<br>".$reportManager->getInstituteTelephone(); ?></th></tr>
	 
	</table> <br>
 
	<table cellspacing="0" cellpadding="0" border="0" width="650" align="center">
	<tr>
		<td <?php echo $reportManager->getReportDataStyle()?> align="right" width="2%"><nobr>No: </nobr></td>
		<td <?php echo $reportManager->getReportDataStyle()?>><B><?php echo $recordArray[0]['receiptNo']?></B></td>
		<td <?php echo $reportManager->getReportDataStyle()?> width="28%" align="right"><nobr>Dated: </nobr></td>
		<td <?php echo $reportManager->getReportDataStyle()?> width="7%"><B><?php echo (UtilityManager::formatDate($recordArray[0]['receiptDate']));?></B></td>
	</tr>
	<tr>
		<td valign="top" colspan="4" height="20"></td>
	</tr>
	<tr>
		<td colspan="4">
		<table cellspacing="0" cellpadding="0" border="0" width="650">
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width="20%" nowrap>Received with thanks from</td>
			<td valign="top" class="receiptClass" width="38%">&nbsp;<i><?php echo ucwords(strtolower($recordArray[0]['firstName'].' '.$recordArray[0]['lastName']))?></i></td>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width="6%"><?php
			if($recordArray[0]['studentGender']=="M") echo "S/o"; else echo "D/o";
			?> Sh.</td>
			<td valign="top" class="receiptClass"  width="38%">&nbsp;<i><?php echo ucwords(strtolower($titleResults[$recordArray[0]['fatherTitle']].' '.$recordArray[0]['fatherName']))?></i></td>
		</tr>
		<tr>
			<td valign="top" height="8"></td>
		</tr>
		<tr>
			<td valign="top" colspan="4">
			<table cellspacing="0" cellpadding="0" border="0" width="650">
				<tr>
					
					<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width="13%">a sum of Rupees</td>
					<td valign="top" class="receiptClass">&nbsp;<i><?php 
					$num = new NumberToWord($totalAmountPaid);
					echo ucwords(strtolower($num->word)).' Only';
					?></i></td>
					
				</tr>
		<tr>
			<td valign="top" height="8"></td>
		</tr>
				<tr>
					<td valign="top" <?php echo $reportManager->getReportDataStyle()?> colspan="2">on account of <B><?php echo $feeType?> Fee</td>
				</tr>
			</td>
			</table>
		</tr>
		<tr>
			<td valign="top" height="8"></td>
		</tr>
		<tr>
			<td valign="top" height="28"></td>
		</tr>
		<tr>
			<td valign="top" colspan="4">
			<table cellspacing="0" cellpadding="0" border="0" width="650">
				<tr>
					<td valign="middle" class="receiptBox" width="20%" height="30">&nbsp;<B>Rs.&nbsp;&nbsp;<?php echo $totalAmountPaid; ?>/-</B></td>
					<td valign="top" width="40%"></td>
					<td valign="top" width="40%" <?php echo $reportManager->getReportDataStyle()?> align="right"><B><?php echo $feeFavourText ?></B></td>
				</tr>
				<tr>
					<td valign="top" height="28"></td>
				</tr>
				<tr>
					<td valign="middle"></td>
					<td valign="top" width="40%"></td>
					<td valign="top" width="40%" <?php echo $reportManager->getReportDataStyle()?> align="center">Authorised Sign</td>
				</tr>
			</td>
			</table>
		</tr>
		
		</table>
		</td>
	</tr>
	</table>
<?php
// $History: paymentReceipt.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/Student
//updated with all the fees enhancements
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
//User: Rajeev       Date: 9/19/08    Time: 4:07p
//Updated in $/Leap/Source/Templates/Student
//changed "cauton money" to "fees"
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:07p
//Created in $/Leap/Source/Templates/Student
//intial checkin

?>    