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
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
												<td class="padding"><b>:&nbsp;</b><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getTimeTableClasses();">
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
													<select size="1" class="selectfield" name="degree" id="degree" style="width:220px" onBlur="getClassSubjects();clearClassGroups();">
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
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:100px"  onBlur="getGroups();">
														<option value="">Select</option>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Group</strong>&nbsp;
												</td>
												<td class="padding">:
													<select name="groupId"  class="selectfield" id="groupId" style="width:100px" onBlur="hideResults();">
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
											<td colspan="1" class="content_title">Change Category of Test :</td>
											<td colspan="1" class="content_title" align="right"></td>
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
											<td colspan="2" class="content_title" align="right"></td>
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

<?php floatingDiv_Start('editTestCategory','Change Test Category'); ?>
<div id='editTestCategoryDiv'>
<form name='editTestCategoryForm' method='post' action='' onsubmit='return false;'>
	<input type='hidden' name='testId' value='' />
	<table border='0' cellspacing='0' cellpadding='0' class="border">
		<tr>
			<td valign='top' colspan='1' class='contenttab_internal_rows1 padding'>Test Topic</td>
			<td valign='top' colspan='1' class='contenttab_internal_rows1'><b>:&nbsp;</b><span id='currentTestTopicSpan'></span></td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='contenttab_internal_rows1 padding'>Test Abbr</td>
			<td valign='top' colspan='1' class='contenttab_internal_rows1'><b>:&nbsp;</b><span id='currentTestAbbrSpan'></span></td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='contenttab_internal_rows1 padding'>Test Type</td>
			<td valign='top' colspan='1' class='contenttab_internal_rows1'><b>:&nbsp;</b><span id='currentTestTypeSpan'></span></td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='contenttab_internal_rows1 padding'>Test Index</td>
			<td valign='top' colspan='1' class='contenttab_internal_rows1'><b>:&nbsp;</b><span id='currentTestIndexSpan'></span></td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='contenttab_internal_rows1 padding'>Student Count</td>
			<td valign='top' colspan='1' class='contenttab_internal_rows1'><b>:&nbsp;</b><span id='currentStudentCountSpan'></span></td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='contenttab_internal_rows1 padding'>New Test Type</td>
			<td valign='top' colspan='1' class='contenttab_internal_rows1'><b>:&nbsp;</b><select name='newTestType' class="selectfield" onChange="getNewTestIndex();"></select></td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='contenttab_internal_rows1 padding'>New Test Index</td>
			<td valign='top' colspan='1' class='contenttab_internal_rows1'><b>:&nbsp;</b><span id='newTestIndexSpan'>0</span></td>
		</tr>
		<tr>
			<td valign='top' colspan='2' class='' align='center'><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return updateTestIndex();return false;" />&nbsp;<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onClick="hiddenFloatingDiv('editTestCategory');return false;" /></td>
		</tr>
	</table>
</form>
</div>
<?php floatingDiv_End(); ?>

<?php 

////$History: listChangeTestCategory.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/30/09   Time: 4:41p
//Created in $/LeapCC/Templates/ChangeTestCategory
//file added for change test category
//
//


?>
