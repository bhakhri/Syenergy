<?php 
//This file is used as printing version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
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
	 
	/// Search filter /////  
	function parseName($value){
		
		$name=explode(' ',$value);
	    $genName="";
		$len= count($name);
		if($len > 0){
			
			for($i=0;$i<$len;$i++){
			
			if(trim($name[$i])!=""){
            
				if($genName!=""){
					
					$genName =$genName ." ".$name[$i];
				}
				else{

					$genName =$name[$i];
				} 
			}
		}
    }
    if($genName!=""){

		$genName=" OR CONCAT(TRIM(stu.firstName),' ',TRIM(stu.lastName)) LIKE '".$genName."%'";
	}  
  
	return $genName;
	}

	/// Search filter ///// 
	// Degree
 
	if(UtilityManager::notEmpty($REQUEST_DATA['degree'])){

	   $filter .= "	AND stu.classId = ".$REQUEST_DATA['degree'];         
	}
	$searchCrieria .= " <B>Degree:</B>".$REQUEST_DATA['degreeName'];

	// Batch
	if(UtilityManager::notEmpty($REQUEST_DATA['batch'])) {
	   $filter .= ' AND (cls.batchId = '.add_slashes($REQUEST_DATA['batch']).')';         
	}
	$searchCrieria .= " <B>Batch:</B>".$REQUEST_DATA['batchName'];

	// Study Period
	if(UtilityManager::notEmpty($REQUEST_DATA['studyperiod'])) {
	   $filter .= ' AND (fr.currentStudyPeriodId = '.add_slashes($REQUEST_DATA['studyperiod']).')';         
	}
	$searchCrieria .= " <B>Study Period:</B>".$REQUEST_DATA['studyperiodName'];

	// Student Name
	if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
		$studentName = $REQUEST_DATA['studentName'];
		$parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $filter .= " AND (
						  TRIM(stu.firstName) LIKE '".add_slashes(trim($studentName))."%' 
						  OR 
						  TRIM(stu.lastName) LIKE '".add_slashes(trim($studentName))."%'
						  $parsedName
					 )";
	  $searchCrieria .= " <B>Name:</B>".$REQUEST_DATA['studentName'];      
	}

	// Roll No
	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")'; 
	   $searchCrieria .= " <B>Roll No:</B>".$REQUEST_DATA['studentRoll'];      
	}

	// fee cycle
	if(UtilityManager::notEmpty($REQUEST_DATA['feeCycle'])) {
	   $filter .= ' AND (fr.feeCycleId = '.add_slashes($REQUEST_DATA['feeCycle']).')';         
	}
	$searchCrieria .= " <B>Fee Cycle:</B>".$REQUEST_DATA['feecycleName'];

	// from Date
	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	   $filter .= " AND (receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";
	   $searchCrieria .= " <B>From Date:</B>".$REQUEST_DATA['fromDate'];
	}

	// to date
	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	   $filter .= " AND (receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";  
	   $searchCrieria .= " <B>To Date:</B>".$REQUEST_DATA['toDate'];
	}

	// instrument status
	if(UtilityManager::notEmpty($REQUEST_DATA['paymentStatus'])) {
	   $filter .= ' AND (instrumentStatus ='.add_slashes($REQUEST_DATA['paymentStatus']).')';   
	   
	}
	$searchCrieria .= " <B>Payment Status:</B>".$REQUEST_DATA['paymentStatusName'];

	// receipt status
	if(UtilityManager::notEmpty($REQUEST_DATA['receiptStatus'])) {
	   $filter .= ' AND (receiptStatus ='.add_slashes($REQUEST_DATA['receiptStatus']).')';         
	}
	$searchCrieria .= " <B>Receipt Status:</B>".$REQUEST_DATA['receiptStatusName'];

	// from amount
	if(UtilityManager::notEmpty($REQUEST_DATA['fromAmount'])) {

	   $filter .= ' AND (totalAmountPaid >='.add_slashes($REQUEST_DATA['fromAmount']).')';  
	   $searchCrieria .= " <B>From Amount:</B>".$REQUEST_DATA['fromAmount'];
	}

	// to amount
	if(UtilityManager::notEmpty($REQUEST_DATA['toAmount'])) {

	   $filter .= ' AND (totalAmountPaid <='.add_slashes($REQUEST_DATA['toAmount']).')';         
	   $searchCrieria .= " <B>To Amount:</B>".$REQUEST_DATA['toAmount'];
	}
	////////////
	
	// payment type
	if(UtilityManager::notEmpty($REQUEST_DATA['paymentType'])) {
	   $filter .= ' AND (paymentInstrument ='.add_slashes($REQUEST_DATA['paymentType']).')';         
	}
	$searchCrieria .= " <B>Payment Type:</B>".$REQUEST_DATA['paymentTypeName'];
	
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feeReceiptId';
    
    $orderBy = " ORDER by $sortField $sortOrderBy";  

    $recordArray = $studentManager->getFeesHistoryList($filter,$limit='',$orderBy);
?>
	<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
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
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Installment Details Report</th></tr>
	<?php 
	 
	if($searchCrieria)
	{
	?>
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>>For <?php echo $searchCrieria?></th></tr>
	<?php
	}	
	?>
	</table> <br>
	 	 	 	 	 	 	 
	<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
	<tr>
		<td width="5%" align="left" class = 'headingFont'><b>&nbsp;#</b>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Receipt</b></td>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Date</b></td>
		<td valign="middle" align="left" width="16%" <?php echo $reportManager->getReportDataStyle()?>><b>Name</b></td>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Roll No</b></td>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?> nowrap><b>Fee Cycle</b></td>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?> nowrap><b>Installment</b></td>
		<td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Payable(Rs)</b></td>
		<td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Paid(Rs)</b></td>
		<td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Outstanding(Rs)</b></td>
		<td valign="middle" align="left" width="6%" <?php echo $reportManager->getReportDataStyle()?>><b>Status</b></td>
	</tr>
   <?php
	$recordCount = count($recordArray);
	$j=0;
	$k=0;
	if($recordCount >0 && is_array($recordArray) ) { 
		$subjectName = "";     
		for($i=0; $i<$recordCount; $i++ ) {
			
		echo '<tr>
			<td valign="top"'.$reportManager->getReportDataStyle().' align="left">'.($i+1).'</td>
			<td valign="top"'.$reportManager->getReportDataStyle().' nowrap>'.$recordArray[$i]['receiptNo'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().'>'.$recordArray[$i]['receiptDate'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="left" nowrap>'.$recordArray[$i]['fullName'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$recordArray[$i]['rollNo'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$recordArray[$i]['cycleName'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$recordArray[$i]['installmentCount'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$recordArray[$i]['discountedFeePayable'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$recordArray[$i]['totalAmountPaid'].'</td>';

			if($recordArray[$i]['outstanding']){

				echo '<td valign="top" align="right" '.$reportManager->getReportDataStyle().' bgcolor="#ECECEC"><b>'.$recordArray[$i]['outstanding'].'</b></td>';
			}
			else{
			
				echo '<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$recordArray[$i]['outstanding'].'</td>';
			}
		echo '<td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$receiptArr[$recordArray[$i]['receiptStatus']].'</td>
			 
			</tr>';
			 
		}
	}
	else {
		echo '<tr><td colspan="10" align="center" '.$reportManager->getReportDataStyle().'>No record found</td></tr>';
	}
	 ?>        
	</table> <br>
	<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
	<tr>
		<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
	</tr>
	</table>
<?php 
// $History: installmentPrint.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/03/09    Time: 11:13a
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 1425
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/18/09    Time: 1:51p
//Updated in $/LeapCC/Templates/Student
//Updated report formatting so that "outstanding" field stand Out
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Templates/Student
//updated as per CC functionality
?>
