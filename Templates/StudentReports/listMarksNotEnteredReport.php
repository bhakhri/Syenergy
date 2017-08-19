<?php 
//--------------------------------------------------------
//This file creates Html Form output for marks not entered report
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
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
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Examination Reports&nbsp;&raquo;&nbsp;Marks Entered Report</td>
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
									<form name="marksNotEnteredForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td class="contenttab_internal_rows1"><nobr><b>Time Table</b></nobr></td>
												<td class="padding" ><b>:&nbsp;</b><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getTimeTableClasses();">
												<option value="" >Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select></td>
												<td class="contenttab_internal_rows1" align="right">
													&nbsp;&nbsp;<strong>Degree </strong>
												</td>
												<td class="padding"><b>:</b>
													<select size="1" name="degree" id="degree" class="selectfield" onBlur="resetSubject();hideDetails();" style="width:200px">
														<option value="all">All</option>
														<?php 
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['class']==''?$REQUEST_DATA['class'] : $REQUEST_DATA['class']);?>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													&nbsp;&nbsp;<strong>Subjects</strong>
												</td>
												<td class="padding"><b>:</b>
													<select name="subjectId" class="selectfield" id="subjectId" style="width:100px" onBlur="hideDetails();">
														<option value="all">All</option>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													&nbsp;&nbsp;<strong>Groups</strong>
												</td>
												<td class="padding"><b>:</b>
													<select name="groupId" class="selectfield" id="groupId" style="width:100px" onBlur="hideDetails();">
														<option value="">Select</option>
														<option value="all">All</option>
													</select>
												</td>
												<td align="center" colspan="4" >
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
											<td colspan="1" class="content_title">Marks Entered Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
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
//$History: listMarksNotEnteredReport.php $
//
//*****************  Version 7  *****************
//User: Rahul.nagpal Date: 11/19/09   Time: 11:13a
//Updated in $/LeapCC/Templates/StudentReports
//issue #2047 resolved.
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 11/14/09   Time: 5:48p
//Updated in $/LeapCC/Templates/StudentReports
//Test Wise marks consolidated report is renamed as Test Type category
//wise detailed report
//marks not entered report is renamed as marks entered report
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/01/09    Time: 2:07p
//Updated in $/LeapCC/Templates/StudentReports
//report testing and changing view part.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/01/09    Time: 6:47p
//Updated in $/LeapCC/Templates/StudentReports
//corrected from class1 to degree as part of checking/fixing all reports.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:54p
//Updated in $/Leap/Source/Templates/StudentReports
//improved design and made it working for IE
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/05/08    Time: 1:25p
//Updated in $/Leap/Source/Templates/StudentReports
//done minor modifications
//
?>
