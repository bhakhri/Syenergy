<?php 
//This file is used as printing version for student fee details.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
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
	 
	$studentId     = $REQUEST_DATA['studentId'];
	$sortOrderBy   = $REQUEST_DATA['sortOrderBy'];
	$sortField     = $REQUEST_DATA['sortField'];

	$studentName   = $REQUEST_DATA['studentName'];
	$studentLName  = $REQUEST_DATA['studentLName'];
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$feesClassArr  = array();
	$feesClassArr  = $studentManager->getStudentFeesClass($studentId);

	$cnt = count($recordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$reportManager->setReportInformation("For ".$studentName.' '.$studentLName." As On $formattedDate ");
	$reportManager->setReportHeading("Fee Details Report");
?>
	<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr>
		<td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
		<th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
		<td align="right" colspan="1" width="25%" class="">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
				</tr>
				<tr>
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
	</table> <br>
		 <table border='1' cellspacing='0' class="reportTableBorder" width="100%" align="center">
		<tr>
			<td valign="middle" height="25" <?php echo $reportManager->getReportDataStyle()?>><b>Sr No</b></td>
			<td valign="middle" height="25" <?php echo $reportManager->getReportDataStyle()?>><b>Receipt No</b></td>
			<td valign="middle" <?php echo $reportManager->getReportDataStyle()?>><b>Date</b></td>
			<td valign="middle" align="right" <?php echo $reportManager->getReportDataStyle()?>><b>Total fees</b></td>
			<td valign="middle" align="right" <?php echo $reportManager->getReportDataStyle()?>><b>Fees Payable</b></td>
			<td valign="middle" align="right" <?php echo $reportManager->getReportDataStyle()?>><b>Amount Paid</b></td>
			<td valign="middle" align="right" <?php echo $reportManager->getReportDataStyle()?>><b>Receipt Status</b></td>
			<td valign="middle" align="right" <?php echo $reportManager->getReportDataStyle()?>><b>Payment Instrument</b></td>
			<td valign="middle" align="right" <?php echo $reportManager->getReportDataStyle()?>><b>Instrument Status&nbsp;&nbsp;</b></td>
		</tr>
        <?php  
			$recordCount = count($feesClassArr);
			if($recordCount >0 && is_array($feesClassArr) ) { 
				
			for($i=0; $i<$recordCount; $i++ ) {
			echo '<tr class="'.$bg.'">
					<td valign="top" '.$reportManager->getReportDataStyle().' >'.($records+$i+1).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($feesClassArr[$i]['receiptNo']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($feesClassArr[$i]['receiptDate']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right">Rs '.number_format(strip_slashes($feesClassArr[$i]['totalFeePayable']),'2','.','').'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right">Rs '.number_format(strip_slashes($feesClassArr[$i]['discountedFeePayable']),'2','.','').'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right">Rs '.number_format(strip_slashes($feesClassArr[$i]['totalAmountPaid']),'2','.','').'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.$receiptArr[strip_slashes($feesClassArr[$i]['receiptStatus'])].'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.$modeArr[strip_slashes($feesClassArr[$i]['paymentInstrument'])].'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.$receiptPaymentArr[strip_slashes($feesClassArr[$i]['instrumentStatus'])].'</td>
				  </tr>';
				}  
			}
			else {
				echo '<tr><td colspan="8" align="center">No record found</td></tr>';
			}
			echo  '</tr>';
			?>          
		</table> <br>
		<table border='0' cellspacing='0' cellpadding='0' width="100%" align="center">
		<tr>
			<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
		</tr>
		</table>
<?php 
// $History: studentFeesPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and print reports
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/16/08    Time: 2:55p
//Created in $/Leap/Source/Templates/Student
//intial checkin
?>