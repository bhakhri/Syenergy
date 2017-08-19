<?php 
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 12-Aug-2008
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
									<form name="testWiseMarksReportForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" >
											<tr>
												<td class="contenttab_internal_rows1" ><nobr><b>Time Table&nbsp;:&nbsp;</b></nobr></td>
												<td class="padding" colspan="5"><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getTimeTableClasses();" style="width:220px;">
												<option value="" >Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select></td>
											</tr>
											<tr>
												<td class="contenttab_internal_rows1" align="left">
													<strong>Degree&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</strong>
												</td>
												<td class="padding">
													<select size="1" class="selectfield" name="degree" id="degree" style="width:220px" onBlur="getClassSubjects();clearClassGroups();" onfocus = "getTimeTableClasses();">
														<option value="">Select</option>
														<?php 
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Subject&nbsp;:&nbsp;</strong>
												</td>
												<td class="padding">
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:100px"  onBlur="getGroups();">
														<option value="">Select</option>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Group&nbsp;:&nbsp;</strong>&nbsp;
												</td>
												<td class="padding">
													<select name="groupId"  class="selectfield" id="groupId" style="width:100px" onBlur="hideResults();">
														<option value="">Select</option>
													</select>
												</td>
											</tr>
											<tr>
												<td colspan="1" align="left" class="contenttab_internal_rows1"><nobr><strong>Sort&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</strong></td>
												<td class="padding" align="left" colspan='1' nowrap>
												<select name="sorting"  class="selectfield" id="sorting" style="width:220px" onBlur="hideResults();">
														<option value="cRollNo">C.Roll No.</option>
														<option value="uRollNo">U.Roll No.</option>
														<option value="name">Student Name</option>
													</select></nobr>
												</td>
													<td colspan="1" align="right" class="contenttab_internal_rows1"><nobr><strong>Order&nbsp;:&nbsp;</strong></td>
												    <td colspan="1" align="left" class="">
														<input type="radio" name="ordering" id="ordering1" checked="checked" onclick="hideResults();" />Asc.&nbsp;
                                          <input type="radio" name="ordering" id="ordering2"  onclick="hideResults();" />Desc.
												</td>
												<td colspan="2" align="left">
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
											<td colspan="1" class="content_title">Test wise Marks Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printCSV()" />&nbsp;</td>
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
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printCSV()" /></td>
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

////$History: listTestWiseMarksReport.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 3/24/10    Time: 12:52p
//Updated in $/LeapCC/Templates/StudentReports
//done changes, FCNS No.1459
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 11/23/09   Time: 6:54p
//Updated in $/LeapCC/Templates/StudentReports
//fixed bugs: 2112, 2113
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/20/09   Time: 10:45a
//Updated in $/LeapCC/Templates/StudentReports
//modified for bug fixing: FCNS ref. no. 822
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/22/09    Time: 5:47p
//Updated in $/LeapCC/Templates/StudentReports
//changed page formatting.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/17/09    Time: 5:46p
//Updated in $/LeapCC/Templates/StudentReports
//added code for pagination
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
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
//User: Ajinder      Date: 8/26/08    Time: 2:21p
//Updated in $/Leap/Source/Templates/StudentReports
//removed word following "select" in dropdown boxes
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/25/08    Time: 7:14p
//Updated in $/Leap/Source/Templates/StudentReports
//removed code which was making unnecessary server trip
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/18/08    Time: 6:15p
//Updated in $/Leap/Source/Templates/StudentReports
//file modified for showing print button and improving page design
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/14/08    Time: 4:04p
//Created in $/Leap/Source/Templates/StudentReports
//File added for Testwise Marks Report
//
?>
