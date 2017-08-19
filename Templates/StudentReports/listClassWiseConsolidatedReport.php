<?php 
//This file creates Html Form output for class perfromance graph
//
// Author :Ajinder Singh
// Created on : 27-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Examination Reports&nbsp;&raquo;&nbsp;Class Performance Graph</td>
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
									<form name="classWiseConsolidatedReportForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" width="80%">
											<tr>
												<td class="contenttab_internal_rows1" width="10%" align="right"><nobr><b>Time Table</b></nobr></td>
												<td class="padding" width="20%"><b>:&nbsp;</b><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getTimeTableClasses();">
												<option value="" >Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select>
												</td>
												<td  class="contenttab_internal_rows1" width="8%" align="right">
													<strong>Degree</strong> 
												</td>
												<td class="padding" width="20%"><b>:</b>
													<select size="1" class="selectfield" name="degree" id="degree" style="width:180px" onBlur="getSubjects();hideResults();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select>
												</td>
												<td  class="contenttab_internal_rows1" width="8%" align="right">
													<strong>Subject</strong>
												</td>
												<td class="padding" width="10%"><b>:</b>
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:100px" onBlur="getTeachers();hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												</tr>
												<tr>
												<td  class="contenttab_internal_rows1" align="right">
													<strong>Teacher</strong> 
												</td>
												<td class="padding"><b>:</b>
													<select size="1" class="selectfield" name="teacherId" id="teacherId" style="width:120px" onBlur="getGroups();hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												<td  class="contenttab_internal_rows1" width="8%" align="right">
													<strong>Group</strong>
												</td>
												<td class="padding" width="30%"><b>:</b>
													<select name="groupId"  class="selectfield" id="groupId" style="width:100px" onBlur="getTests();hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												<td  class="contenttab_internal_rows1" width="8%" align="right">
													<strong>Test</strong>
												</td>
												<td class="padding" width="30%"><b>:</b>
													<select name="testId"  class="selectfield" id="testId" style="width:100px" onBlur="hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												<td align="center" valign="bottom">
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
								<td align="center" height="20">
									<table width="40%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr   class="contenttab_border">
											<td colspan="1" class="content_title">Class Performance Graph:</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" /></td>
										</tr>
										<tr id='resultRow' style='display:none;'>
											<td colspan='2' class='contenttab_row'>
												<div id = 'resultsDiv'></div>
												<div id = 'resultsDiv2'></div>
											</td>
										</tr>
										<tr id='nameRow2' style='display:none;'>
											<td class="" colspan="2" height="20">
												<table width="40%" border="0" cellspacing="0" cellpadding="0" height="20"  class="" align="right">
													<tr>
														<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" /></td>
													</tr>
												</table>
											</td>
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
//$History: listClassWiseConsolidatedReport.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Templates/StudentReports
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/06/08    Time: 3:41p
//Updated in $/Leap/Source/Templates/StudentReports
//done minor text symmetry modification
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/04/08    Time: 11:55a
//Updated in $/Leap/Source/Templates/StudentReports
//made the dropdown box function call from onChange() to onBlur()
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/29/08    Time: 12:30p
//Updated in $/Leap/Source/Templates/StudentReports
//removed unnecessary space from dropdown boxes
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/28/08    Time: 12:26p
//Created in $/Leap/Source/Templates/StudentReports
//File added for "class performance graph"
//


?>