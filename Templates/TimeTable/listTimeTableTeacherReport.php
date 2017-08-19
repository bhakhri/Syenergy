<?php 
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 15-July-2008
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
									<form name="timeTableForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" >
											<tr>
												<td class="contenttab_internal_rows1" align="right"><nobr><b>Time Table</b></nobr></td>
												<td class="padding" ><b>:&nbsp;</b><select size="1" class="inputbox1" name="labelId" id="labelId" onChange="getTimeTableClasses();hideResults();">
												<option value="" >Select</option>
												<option value="0" >ALL</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getAllTimeTableLabelData();
												?>
												</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Degree </strong>
												</td>
												<td class="padding"><b>:</b>
													<select size="1" class="selectfield" name="degree" id="degree" style="width:200px" onChange="getClassSubjects();getClassTeacher();hideResults();">
														<option value="">Select</option>
														<?php 
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Subject</strong>
												</td>
												<td class="padding"><b>:</b>
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:140px" onChange="hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Teacher</strong>
												</td>
												<td class="padding"><b>:</b>
													<select name="employeeId"  class="selectfield" id="employeeId" style="width:140px" onChange="hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												<td align="center" >
													<span >
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
											<td colspan="1" class="content_title">Subject Taught By Teacher Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
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
//$History: listStudentAttendanceReport.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 2/12/10    Time: 12:25p
//Updated in $/LeapCC/Templates/StudentReports
//done changes FCNS No. 1280
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/28/09    Time: 5:15p
//Updated in $/LeapCC/Templates/StudentReports
//changed form layout.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 9/04/08    Time: 11:55a
//Updated in $/Leap/Source/Templates/StudentReports
//made the dropdown box function call from onChange() to onBlur()
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 8/29/08    Time: 12:30p
//Updated in $/Leap/Source/Templates/StudentReports
//removed unnecessary space from dropdown boxes
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/26/08    Time: 1:23p
//Updated in $/Leap/Source/Templates/StudentReports
//removed word following "select" from dropdown boxes
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:40p
//Updated in $/Leap/Source/Templates/StudentReports
//applied check for reducing unnecessary server trip
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/18/08    Time: 5:55p
//Updated in $/Leap/Source/Templates/StudentReports
//file modified for setting print button and improving design.
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
