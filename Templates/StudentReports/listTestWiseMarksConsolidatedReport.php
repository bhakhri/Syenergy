<?php
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 12-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									<form name="testWiseMarksReportForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" >
											<tr>
												<td class="contenttab_internal_rows1" ><nobr><b>Time Table</b></nobr></td>
												<td class="padding"><b>:&nbsp;</b><select size="1" class="inputbox1" name="labelId" id="labelId" onchange="getTimeTableClasses();">
												<option value="" >Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select></td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Degree</strong> &nbsp;
												</td>
												<td class="padding"><b>:</b>
													<select size="1" class="selectfield" name="degree" id="degree" style="width:220px" onchange="getClassSubjects();">
														<option value="">Select</option>
														<?php
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Subject</strong>
												</td>
												<td class="padding">:
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:100px" onChange="hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												<td align="center">
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
										</table>
									</form>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Test Type Category wise Detailed Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
									<table border='0' cellspacing='0' cellpadding='0' width='100%'>
										<tr>
											<td valign='top' colspan='1' class='' width='20%'>
												<b>TNH: Test Not Held</b>
											</td>
											<td valign='top' colspan='1' class='' width='50%'>
												<b>GNA: Group Not Assigned</b>
											</td>
											<td valign='top' colspan='1' class='' align='right'><div id = 'pagingDiv' align='right'></div></td>
										</tr>
									</table>

								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- form table ends -->

					</td>
				</tr>
			</table>
		</table>

<?php

////$History: listTestWiseMarksConsolidatedReport.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 11/25/09   Time: 4:27p
//Updated in $/LeapCC/Templates/StudentReports
//RESOLVED ISSUE 0002125
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 11/14/09   Time: 5:48p
//Updated in $/LeapCC/Templates/StudentReports
//Test Wise marks consolidated report is renamed as Test Type category
//wise detailed report
//marks not entered report is renamed as marks entered report
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/22/09   Time: 10:08a
//Updated in $/LeapCC/Templates/StudentReports
//added code for showing abbreviation meaning.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/23/09    Time: 3:54p
//Created in $/LeapCC/Templates/StudentReports
//added file for test wise consolidated marks report.
//
//
?>
