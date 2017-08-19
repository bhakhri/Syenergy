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
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	 
	/// Search filter /////  
	if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
	   $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")';         
	}
	////////////

	$concatDegreeId = $REQUEST_DATA['degree'];
	$concatArr		= explode("-", $concatDegreeId);
	$universityID	= $concatArr[0];
	$degreeID		= $concatArr[1];
	$branchID		= $concatArr[2];
	$batchId		= $REQUEST_DATA['batch'];
	$studyperiodId	= $REQUEST_DATA['studyperiod'];
	$studentName	= $REQUEST_DATA['studentName'];
	$studentRoll	= $REQUEST_DATA['studentRoll'];
	
	if($universityID!='' && $batchId!='' && $studyperiodId!='')
	{
		$classIDArr		= $studentManager->getClass($universityID,$degreeID,$branchID,$batchId,$studyperiodId);

		$classId		= $classIDArr[0][classId];
		$className		= $classIDArr[0][className];
		$classNameArr = explode(" - ", $className);

		if($classId=='')
			$classId = "-1";
	}
	$searchCrieria = '';

	if(UtilityManager::notEmpty($REQUEST_DATA['degree'])) {
       
	   $universityID	= $concatArr[0];
	   $degreeID		= $concatArr[1];
	   $branchID		= $concatArr[2];
		
	   $uniArr     = $studentManager->getFieldValue("university", "universityAbbr", $universityID,"universityId");
	   $univAbbr   = $uniArr[0]['universityAbbr'];

	   $degreeArr  = $studentManager->getFieldValue("degree", "degreeAbbr", $degreeID,"degreeId");
	   $degreeAbbr = $degreeArr[0]['degreeAbbr'];

	   $branchArr  = $studentManager->getFieldValue("branch", "branchCode", $branchID,"branchId");
	   $branchAbbr = $branchArr[0]['branchCode'];

	   $searchCrieria .= "<B>University:</B> $univAbbr <B>Degree:</B> $degreeAbbr <B>Branch:</B> $branchAbbr";   
	}
	else{
	    $searchCrieria .= "<B>University:</B> ALL <B>Degree:</B> ALL <B>Branch:</B> ALL";
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['batch'])) {
       
	   $batchArr  = $studentManager->getFieldValue("batch", "batchName", $REQUEST_DATA['batch'],"batchId");
	   $batchAbbr = $batchArr[0]['batchName'];
	   $searchCrieria .= " <B>Batch:</B>".$batchAbbr;      
	}
	
	$searchCrieria .= " <B>StudyPeriod:</B> ALL";   
	if(UtilityManager::notEmpty($REQUEST_DATA['studyperiod'])) {
       
	   $periodArr  = $studentManager->getFieldValue("study_period", "periodName", $REQUEST_DATA['studyperiod'],"studyPeriodId");
	   $periodAbbr = $periodArr[0]['periodName'];

	   $searchCrieria .= " <B>StudyPeriod:</B>".$periodAbbr;         
	}
	else{
	 
	   $searchCrieria .= " <B>Batch:</B> ALL";
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
	   $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")'; 
	   if($searchCrieria)
		   $searchCrieria .="<br>";
	   $searchCrieria .= "<B>Name:</B>".$REQUEST_DATA['studentName'];
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")';
	   $searchCrieria .= " <B>Roll No:</B>".$REQUEST_DATA['studentRoll'];
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['feeCycle'])) {
	   $filter .= ' AND (fr.feeCycleId = '.add_slashes($REQUEST_DATA['feeCycle']).')';   
	   
	   $feeArr  = $studentManager->getFieldValue("fee_cycle", "cycleAbbr", $REQUEST_DATA['feeCycle'],"feeCycleId");
	   $feeAbbr = $feeArr[0]['cycleAbbr'];

	   $searchCrieria .= " <B>Fee Cycle:</B>".$feeAbbr;
	}
	else{
	 
	   $searchCrieria .= "<br><B>Fee Cycle:</B> ALL";
	}

	 
	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	    $filter .= " AND (receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";        
	    if($searchCrieria)
		   $searchCrieria .="<br>";
	   $searchCrieria .= " <B>From Date:</B>".UtilityManager::formatDate($REQUEST_DATA['fromDate']);
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	    $filter .= " AND (receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";        
	   $searchCrieria .= " <B>To Date:</B>".UtilityManager::formatDate($REQUEST_DATA['toDate']);
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['fromAmount'])) {
	   $filter .= ' AND (totalAmountPaid >='.add_slashes($REQUEST_DATA['fromAmount']).')';
	    $searchCrieria .= " <B>From Amount:</B>".number_format($REQUEST_DATA['fromAmount'],'2','.','');
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['toAmount'])) {
	   $filter .= ' AND (totalAmountPaid <='.add_slashes($REQUEST_DATA['toAmount']).')';         
	   $searchCrieria .= " <B>To Amount:</B>".number_format($REQUEST_DATA['toAmount'],'2','.','');
	}
	
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feeReceiptId';
    
    $orderBy = " $sortField $sortOrderBy";  

	$totalArray = $studentManager->getTotalFeesStudent($filter);
    //$recordArray = $studentManager->getFeesHistoryList($filter,$limit='',$orderBy,$classId);
	$recordArray = $dashboardManager->getAllFeesDue($filter,$limit,$orderBy,$classId);
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
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Payment History Report</th></tr>
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
		<td width="5%" align="center" class = 'headingFont'><b>&nbsp;#</b>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Receipt</b></td>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Date</b></td>
		<td valign="middle" align="left" width="16%" <?php echo $reportManager->getReportDataStyle()?>><b>Name</b></td>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Roll No</b></td>
		<td valign="middle" align="left" width="10%" <?php echo $reportManager->getReportDataStyle()?> nowrap><b>Fee Cycle</b></td>
		<td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Payable(Rs)</b></td>
		 
		<td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Outstanding(Rs)</b></td>
		 
	</tr>
   <?php
	$recordCount = count($recordArray);
	$j=0;
	$k=0;
	if($recordCount >0 && is_array($recordArray) ) { 
		$subjectName = "";     
		for($i=0; $i<$recordCount; $i++ ) {
			
		echo '<tr>
			<td valign="top"'.$reportManager->getReportDataStyle().' align="center">'.($i+1).'</td>
			<td valign="top"'.$reportManager->getReportDataStyle().' nowrap>'.$recordArray[$i]['receiptNo'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().'>'.$recordArray[$i]['receiptDate'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$recordArray[$i]['fullName'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$recordArray[$i]['rollNo'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$recordArray[$i]['cycleName'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$recordArray[$i]['discountedFeePayable'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$recordArray[$i]['previousDues'].'</td>
			 
			 
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
