<?php 
//This file creates Html Form output for attendance report
//
// Author :Rajeev Aggarwal
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
										<table width="80%" align="center" border="0">
											<tr>
												<td class="padding" align="left" nowrap><strong>Time Table</strong> </td>
												<td class="padding" align="right"><strong>:</strong></td>
												<td class="padding"><select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:150px" onChange="getLabelClass()">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
													</select>
												</td>
												<td class="padding" align="left" ><strong>Degree</strong></td>
												<td class="padding" align="right"><strong>:</strong></td>
												<td><select size="1" class="selectfield" name="degree" id="degree" style="width:250px" onChange="getClassSubjects();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select>
												</td>
												<td class="padding" nowrap align="left" ><strong>Subject Type</strong></td>
												<td class="padding" align="right"><strong>:</strong></td>
												<td align="left" class="padding"><select name="subjectTypeId"  class="selectfield" id="subjectTypeId" style="width:90px"  onChange="getTypeGroups();">
														<option value="">Select</option>
													</select></td>
											</tr>
											<tr>
												<td class="padding" align="left"><strong>Subject</strong></td>
												<td class="padding" align="right"><strong>:</strong></td>
												<td align="left" class="padding"><select name="subjectId"  class="selectfield" id="subjectId" style="width:150px"  onChange="getGroups();">
														<option value="">Select</option>
													</select>
												</td>
												<td class="padding" align="left" ><strong>Group</strong></td>
												<td class="padding" align="right"><strong>:</strong></td>
												<td align="left">
													<select name="groupId"  class="selectfield" id="groupId" style="width:175px" onBlur="hideResults();">
														<option value="">ALL</option>
													</select>
												</td>
												<td class="padding" align="left" nowrap><strong>Marks</strong> </td>
												<td class="padding" align="right"><strong>:</strong></td>
												<td class="padding">
													<select size="1" class="inputbox1" name="marksFor" id="marksFor" style="width:150px">
														<option value="0">Both</option>
														<option value="1">Internal</option>
														<option value="2">External</option>
														 
													</select>
												</td>
												<td align="left" valign="middle">
													 
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
											<td colspan="1" class="content_title">Testwise Marks Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							 
							<tr id='resultRow'>
								<td colspan='1' class='contenttab_row' align='center'>
									<div id = 'results'></div>
									 
								</td>
							</tr>
							<tr>
								<td colspan='1' class='contenttab_row' align='center'>
									<div id = 'results1'></div>
									 
								</td>
							</tr>
							 
							
								 
							 
							<tr id='nameRow2' style='display:none;'>
									<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" /></td>
								</tr>
		<form method="POST" name="addForm"  action="<?php echo UI_HTTP_PATH;?>/imageSave.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="imageDataFrame">
							<input type='hidden' name='imageData' value='' />
							<iframe id="imageDataFrame" name="imageDataFrame" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
							 
						</table>
						<!-- form table ends -->
						
					</td>
				</tr>
			</table>
		</table>

<?php 
//$History: listTeacherConsolidatedReport.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-09-03   Time: 1:07p
//Updated in $/LeapCC/Templates/StudentReports
//0001439: Teacher Consolidated Reports (Admin) > No action performed as
//clicked on Show List button. 
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/14/09    Time: 2:36p
//Updated in $/LeapCC/Templates/StudentReports
//Updated the query to generate print reports
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/01/09    Time: 11:17a
//Updated in $/LeapCC/Templates/StudentReports
//Updated print button hide/show feature
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/30/09    Time: 7:09p
//Created in $/LeapCC/Templates/StudentReports
//intial checkin
?>
