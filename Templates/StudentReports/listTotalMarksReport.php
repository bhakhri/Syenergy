<?php
//This file creates Html Form output for total marks report
//
// Author :Ajinder Singh
// Created on : 28-nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
									<form name="totalMarksReportForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" >
											<tr>
												<td class="contenttab_internal_rows"><nobr><b>Time Table: </b></nobr></td>
												<td class="padding">
												<select size="1" class="htmlElement" name="labelId" id="labelId" onBlur="getMarksTotalClasses();">
												<option value="">Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select></td>
												<td colspan="1" align="right" valign="" >
													<strong>Degree :</strong>
												</td>
												<td valign="" rowspan='1'>
													<select name="class1" id="class1" class="htmlElement"  style="width:250px">
														<option value=''>Select</option>
													</select>
												</td>
												<td valign='' colspan='1' class="padding" align="right"><nobr><b>Include Grace Marks :</b></nobr></td>
												<td valign='' colspan='1' class="contenttab_internal_rows" align='right'>
                                                    <input type="radio" name="showGraceMarks" id="showGraceMarks1" checked="checked" onclick="hideResults();" />No&nbsp;
                                                    <input type="radio" name="showGraceMarks" id="showGraceMarks2" onclick="hideResults();" />Yes&nbsp&nbsp;
												</td>
												</tr>
												<tr>
												<td colspan="1" style="text-align:right" valign="" class="contenttab_internal_rows"><strong>Sort :</strong></td>
												<td class="padding">
													<select name="sorting"  class="htmlElement" id="sorting" onBlur="hideResults();">
														<option value="RollNo">RollNo</option>
														<option value="name">Student Name</option>
													</select></nobr>
												</td>
												<td colspan="1" style="text-align:right" valign=""><nobr><strong>Order :</strong></nobr></td>
												<td>
													<input type="radio" name="ordering" id="ordering1" checked="checked" onclick="hideResults();" />Asc&nbsp;
													 <input type="radio" name="ordering" id="ordering2"  onclick="hideResults();" />Desc
												</td>
												<td></td>
												<td align="right">
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
											<td colspan="1" class="content_title">Total Marks Report :&nbsp;&nbsp;A: Attendance, I: Internal, E: External, G: Grade</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
									<div id = 'pagingDiv' align='right'></div>
								</td>
							</tr>
							<tr>
								<td colspan="7" align="right">

								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/></td>
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

////$History: scListTotalMarksReport.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 6/03/09    Time: 1:15p
//Updated in $/Leap/Source/Templates/ScStudentReports
//increased degree drop down width
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 3/27/09    Time: 11:25a
//Updated in $/Leap/Source/Templates/ScStudentReports
//changed image source to input type
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/28/08   Time: 1:43p
//Created in $/Leap/Source/Templates/ScStudentReports
//file added for total marks report
//
//
?>
