<?php 
//This file creates Html Form output "listAssignOptionalGroup" Module 
//
// Author :Ajinder Singh
// Created on : 11-June-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>  
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<form name="assignGroup" action="" method="post" onSubmit="return false;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" class="contenttab_border2">
										<table align="center" border="0" >
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Degree :</strong>
												</td>
												<td>
													<select size="1" class="selectfield" name="degree" id="degree" style="width:220px"  onBlur="getOptionalSubjects();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH."/HtmlFunctions.inc.php");
															echo HtmlFunctions::getInstance()->getSessionClasses();?>
													</select>
												</td>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Subject :</strong>
												</td>
												<td colspan='1' class="contenttab_internal_rows">
													<select size="1" class="selectfield" name="subjectId" id="subjectId" style="width:100px" onChange="checkCategory();" onBlur="hideResults();checkCategory();">
													<option value="">Select</option>
													</select>
														<span id='categorySubjectsSpan' style='display:none;'><strong>Choose :</strong>
															<select name='categorySubjects' class="selectfield">
																<option value=''>Select</option>
																
															</select>
														</span>
												</td>
											</tr>
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Sort Data By :</strong>
												</td>
												<td colspan='1' class='contenttab_internal_rows'>
													<input type='radio' name='sort' value='1' style='vertical-align:middle;' checked>Roll No.
													<input type='radio' name='sort' value='2' style='vertical-align:middle;'>Univ. Roll No.
													<input type='radio' name='sort' value='3' style='vertical-align:middle;'>Name
												</td>
												<td align="center" valign="top" colspan="1" >
													<table border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td valign='top' colspan='1' class="contenttab_internal_rows" >
														<input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" name="listBtn" value="Show List" onClick="return validateAddForm(this.form);return false;"/></td>
													</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr id='nameRow' style='display:none;'>
									<td class="" height="20">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
											<tr>
												<td colspan="1" class="content_title">Assign Groups :</td>
												<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/assign_groups.gif" onClick="saveSelectedStudents()" />&nbsp;</td>
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
												<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/assign_groups.gif" onClick="saveSelectedStudents()" />&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>

<?php 
//$History: listAssignOptionalGroup.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 2/11/10    Time: 4:49p
//Updated in $/LeapCC/Templates/Student
//done changes as per FCNS No.1292
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Rahul.nagpal Date: 11/18/09   Time: 3:12p
//Updated in $/LeapCC/Templates/Student
//issue #2042 resolved.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/07/09    Time: 11:22a
//Updated in $/LeapCC/Templates/Student
//added code for showing category subjects for MBA
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/11/09    Time: 12:42p
//Created in $/LeapCC/Templates/Student
//file added for assigning optional subject to students
//



?>
