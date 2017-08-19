<?php 
//This file is used as printing version for payment history.
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

	$studentId    = $REQUEST_DATA['studentId'];
	$studentName  = $REQUEST_DATA['studentName'];
	$studentLName = $REQUEST_DATA['studentLName'];
	$classId	  = $REQUEST_DATA['classId'];
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
	$className	  = $REQUEST_DATA['className'];

    $orderBy = " $sortField $sortOrderBy";

	$studentSubjectArray = $studentManager->getStudentFinalResultList($studentId,$classId,$orderBy,'');
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	 
	$reportManager->setReportInformation("For ".$studentName.' '.$studentLName." of ".$className." As On $formattedDate ");
	$reportManager->setReportHeading("Final Marks Detail Report");
?>
	<table border='0' cellspacing='0' width="90%" align="center">
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
 	 
	<table border='1' cellspacing='0' class="reportTableBorder" width="90%" align="center">
	<tr>
		<td valign="middle" height="25" width="4%" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;SrNo</b></td>
		<td valign="middle" height="25" <?php echo $reportManager->getReportDataStyle()?> width="11%" ><b>&nbsp;Study Period</b></td>
		<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> width="35%" >&nbsp;<b>Course</b></td>
		<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align='right'  width="12%">&nbsp;<b>Attendance</b></td>
		<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align='right' width="12%">&nbsp;<b>Pre Compre</b></td>
		<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align='right' width="12%">&nbsp;<b>Comprehensive</b></td>
		 
		 
	</tr>
	 <?php
	$recordCount = count($studentSubjectArray);
	$j=0;
	$k=0;
	if($recordCount >0 && is_array($studentSubjectArray) ) { 
		$subjectName = "";     
		for($i=0; $i<$recordCount; $i++ ) {
			
			 
		echo '<tr>
			<td valign="top"'.$reportManager->getReportDataStyle().'>'.($i+1).'</td>
			<td valign="top"'.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['periodName'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['subjectCode'].'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' nowrap align="right">'.$studentSubjectArray[$i]['attendance'].'</td>
			
			<td align="right" valign="top" '.$reportManager->getReportDataStyle().' nowrap>'.$studentSubjectArray[$i]['preComprehensive'].'</td>
			<td align="right" valign="top" '.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['Comprehensive'].'</td>
			 
			 
			</tr>';
			 
		}
		 
	}
	else {
		echo '<tr><td colspan="8" align="center" '.$reportManager->getReportDataStyle().'>No record found</td></tr>';
	}
	 ?> 
	 
	 

	</table>
	<br>

	<table border='0' cellspacing='0' width="90%" align="center">
		<tr>
			<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
		</tr>
	</table>
<?php 
// $History: studentFinalMarksPrint.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/09    Time: 10:21a
//Updated in $/LeapCC/Templates/Student
//Updated with quartine student registration number increment
?>