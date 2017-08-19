<?php 
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
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
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp;Examination Reports&nbsp;&raquo;&nbsp;Subject Wise Graph</td>
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
									<form name="subjectWiseConsolidatedReportForm" action="" method="post" onSubmit="return false;">
										<table width="80%" align="center" border="0" >
											<tr>
											<td class="contenttab_internal_rows1" width="10%" align="right"><nobr><b>Time Table</b></nobr></td>
												<td class="padding" width="18%"><b>:&nbsp;</b><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getTimeTableClasses();">
												<option value="" >Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select>
												</td>
												<td class="contenttab_internal_rows1" width="8%" align="right">
													<strong>Degree</strong>
												</td>
												<td class="padding" width="25%"><b>:</b>
													<select size="1" class="selectfield" name="degree" id="degree" style="width:140px" onBlur="resetStudyPeriod();hideResults();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getConcatenateClassData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);?>
													</select>
												</td>
												<td class="contenttab_internal_rows1" width="5%" align="right">
													<strong>Subject</strong>
												</td>
												<td class="padding" width="22%"><b>:</b>
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:140px" onBlur="hideResults();">
														<option value="">Select</option>
													</select>
												</td>
												<td align="center" width="20%">
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
											<td colspan="1" class="content_title">Subjectwise Consolidated Report:</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printGraph()" /></td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printGraph()" /></td>
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
//$History: listSubjectWiseConsolidatedReport.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 9/04/08    Time: 11:55a
//Updated in $/Leap/Source/Templates/StudentReports
//made the dropdown box function call from onChange() to onBlur()
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/29/08    Time: 12:30p
//Updated in $/Leap/Source/Templates/StudentReports
//removed unnecessary space from dropdown boxes
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:26p
//Updated in $/Leap/Source/Templates/StudentReports
//removed word following "select" in dropdown boxes
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/25/08    Time: 7:14p
//Updated in $/Leap/Source/Templates/StudentReports
//removed code which was making unnecessary server trip
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 6:39p
//Created in $/Leap/Source/Templates/StudentReports
//file added for subject wise consolidated report
//


?>
