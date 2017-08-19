<?php 
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Examination Reports&nbsp;&raquo;&nbsp;Marks Distribution Report</td>
				</tr>
			</table>
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
									<form name="marksDistributionForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" width="75%" >
											<tr>
												<td class="contenttab_internal_rows1" width="10%" align="right"><nobr><b>Time Table</b></nobr></td>
												<td class="padding" width="30%"><b>:&nbsp;</b><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="cleanSubjectType();cleanSubjects();hideResults();getTimeTableClasses();">
												<option value="" >Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select></td>
												<td class="contenttab_internal_rows1" width="10%" align="right">
													<strong>Degree</strong>
												</td>
												<td class="padding"><b>:</b>
													<select size="1" class="selectfield" name="degree" id="degree" style="width:250px" onBlur="cleanSubjectType();cleanSubjects();hideResults();">
														<option value="">Select</option>
														<?php 
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select>
												</td>
											 </tr>
											 <tr>
												<td class="contenttab_internal_rows1" align="right">
													<nobr><strong>Subject Type </strong></nobr>
												</td>
												<td class="padding">:
													<select size="1" class="selectfield" name="subjectTypeId" id="subjectTypeId" style="width:120px" onBlur="getSubjects();hideResults();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getSubjectTypeData();?>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Subjects</strong>
												</td>
												<td class="padding">:
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:140px" onBlur="hideResults();">
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
											<td colspan="1" class="content_title">Marks Distribution Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
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
//$History: listMarksDistributionReport.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/25/09   Time: 10:40a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug: 2109, 2111, 2119
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/23/09   Time: 6:03p
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug no.2108
//
//*****************  Version 3  *****************
//User: Rahul.nagpal Date: 11/19/09   Time: 12:43p
//Updated in $/LeapCC/Templates/StudentReports
//issue #2053 resolved.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 9/10/08    Time: 2:56p
//Updated in $/Leap/Source/Templates/StudentReports
//fixed designing issue.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 9/04/08    Time: 11:55a
//Updated in $/Leap/Source/Templates/StudentReports
//made the dropdown box function call from onChange() to onBlur()
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/29/08    Time: 12:30p
//Updated in $/Leap/Source/Templates/StudentReports
//removed unnecessary space from dropdown boxes
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:16p
//Updated in $/Leap/Source/Templates/StudentReports
//remove word following "select" in dropdown boxes
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/StudentReports
//unnecessary server trip code removed
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/StudentReports
//improved page design, applied new buttons
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/12/08    Time: 3:59p
//Created in $/Leap/Source/Templates/StudentReports
//file added for making marks distribution report - view part
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:06p
//Updated in $/Leap/Source/Templates/StudentReports
//done minor changes
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/07/08    Time: 5:51p
//Updated in $/Leap/Source/Templates/StudentReports
//done minor changes for improving design
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/06/08    Time: 2:05p
//Updated in $/Leap/Source/Templates/StudentReports
//file changed for making it as per new format
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/02/08    Time: 5:19p
//Updated in $/Leap/Source/Templates/StudentReports
//done cosmetic changes
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/19/08    Time: 10:17a
//Updated in $/Leap/Source/Templates/StudentReports
//changed the comments and header label
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/17/08    Time: 7:27p
//Updated in $/Leap/Source/Templates/StudentReports
//done the coding for studentAttendanceReport completion
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/17/08    Time: 10:33a
//Created in $/Leap/Source/Templates/StudentReports
//File made for : StudentAttendanceReport
//
//*****************  Version 1  *****************
//User: Ajinder       Date: 7/15/08    Time: 12:45p
//Created in $/Leap/Source/Templates/StudentReports
//added a new file for StudentLablesReport  Module
?>
