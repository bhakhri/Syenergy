<?php 
//This file is used as printing version for testwise marks report.
//
// Author :Rajeev Aggarwal
// Created on : 14-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$imageName = $REQUEST_DATA['imageName'];
	$degreeName = $REQUEST_DATA['className'];
	$typeName = $REQUEST_DATA['typeName'];
	$subjectName = $REQUEST_DATA['subjectName'];
	$categoryName = $REQUEST_DATA['categoryName']; 
	$groupName = $REQUEST_DATA['groupName'];  
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
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Test Type Consolidated Report</th></tr>
	 
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>>For <B>Class:</B><?php echo $degreeName?> <B>Subject Type:</B> <?php echo $typeName?> <B>Subject :</B> <?php echo $subjectName?>  <B>Group :</B> <?php echo $groupName?> <B>Test Type :</B> <?php echo $categoryName?></th></tr>
	 
	</table> <br>
 

	<table border='0' cellspacing='0' cellpadding='0' width='70%' align='center'>
	<tr>
		<td valign='' align='center'><img src = "<?php echo IMG_HTTP_PATH ."/".$imageName;?>"/>
		
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td valign='' align='left' <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter();?></td>
	</tr>

	</table>
	
<?php
	 
//$History : listTestWiseMarksReportPrint.php $
//
?>