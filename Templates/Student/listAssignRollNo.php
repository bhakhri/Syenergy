<?php 
//This file creates Html Form output "listAssignRollNo" Module 
//
// Author :Ajinder Singh
// Created on : 24-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
			<form name="assignRoleNo" action="" method="post" onSubmit="return false;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405" >
					<tr>
						<td valign="top" class="content">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" class="contenttab_border2">
										<table align="center" border="0" >
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Degree</strong>
												</td>
												<td align="left" colspan="1" class='contenttab_internal_rows padding'>:&nbsp;
													<select size="1" class="htmlElement" name="degree" id="degree" onBlur="hideResults();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getSessionClasses();?>
													</select>
												</td>
												<td class="contenttab_internal_rows" colspan="1" align="left">
													<nobr><strong>Roll No. Length</strong></nobr>
												</td>
												<td class='contenttab_internal_rows padding'>:&nbsp;
													<select size="1" class="selectfield" name="rollNoLength" id="rollNoLength" style="width:100px" onBlur="hideResults();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getRollNoLength();?>
													</select>
												</td>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Sorting</strong>
												</td>
												<td colspan="1" class="contenttab_internal_rows padding">:&nbsp;
													<select size="1" class="selectfield" name="sorting" id="sorting" style="width:100px" onBlur="hideResults();">
														<option value="">Select</option>
														<option value="alphabetic">Alphabetic</option>
														<option value="registration">Registration</option>
													</select>
												</td>
											</tr>
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Include Leet</strong>
												</td>
												<td class='contenttab_internal_rows padding'>:&nbsp;<input type="checkbox" name="leet" id="leet" onClick="hideResults();" style='vertical-align:top;'>
												</td>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Prefix</strong>
												</td>
												<td class='contenttab_internal_rows padding'>:&nbsp;
													<input type="text"  class="selectfield" name="prefix" id="prefix" maxlength="10" style="width:100px" onBlur="hideResults();">
												</td>
												<td class="contenttab_internal_rows" colspan="1" align="right" onBlur="hideResults();">
													<strong>Suffix</strong>
												</td>
												<td class='contenttab_internal_rows padding'>:&nbsp;
													<input type="text" class="selectfield" name="suffix" id="suffix" maxlength="10" style="width:100px" onBlur="hideResults();">
												</td>
											</tr>
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Include already assigned</strong>
												</td>
												<td class='contenttab_internal_rows padding'>:&nbsp;<input type="checkbox" name="alreadyAssigned" id="alreadyAssigned" onClick="hideResults();" style='vertical-align:top;'></td>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Start Series From</strong>
												</td>
												<td class='contenttab_internal_rows padding'>:&nbsp;
													<input type="text"  class="selectfield" name="startSeriesFrom" id="startSeriesFrom" maxlength="10" style="width:100px" onBlur="hideResults();">
												</td>
												<td valign='top' colspan='1' class=''></td>
												<td align="center" colspan="1" >
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
											<tr>
											<td colspan='9' class="contenttab_internal_rows highlightPermissionBlue" style="text-align:center">
											<strong><?php echo ROLL_NO_MESSAGE;?></strong>
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
												<td colspan="1" class="content_title">Student Details :</td>
												<td valign='' colspan='1' class='content_title' align="right"><span id='showSaveButton1'><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="assignRollNo()" /></span>&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr id='resultRow' style='display:none;'>
									<td colspan='1' class='contenttab_row' align='right'><div id = 'resultsDiv'></div></td>
								</tr>
								<tr id='nameRow2' style='display:none;'>
									<td class="content_title">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
											<tr>
												<td height="20" colspan='2' class='content_title' align="right"><span id='showSaveButton2'><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="assignRollNo()" /></span>&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
<?php 
//$History: listAssignRollNo.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/04/09    Time: 1:22p
//Updated in $/LeapCC/Templates/Student
//fixed bug no.s 842, 841, 840, 839, 814, 813, 812
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/30/09    Time: 1:51p
//Updated in $/LeapCC/Templates/Student
//fixed bug no.0000755
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/18/08   Time: 12:28p
//Updated in $/LeapCC/Templates/Student
//added message
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/18/08   Time: 12:02p
//Updated in $/LeapCC/Templates/Student
//done the coding for making table of students.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/18/08    Time: 5:53p
//Updated in $/Leap/Source/Templates/Student
//fixed minor issue found during self-testing
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Student
//Removed Width and Height on cancel button
//
//*****************  Version 4  *****************
//User: Admin        Date: 8/05/08    Time: 12:03p
//Updated in $/Leap/Source/Templates/Student
//file changed to make it as per new format
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/25/08    Time: 4:58p
//Updated in $/Leap/Source/Templates/Student
//removed "suffix" from form
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/25/08    Time: 4:21p
//Updated in $/Leap/Source/Templates/Student
//done the changes as per "change in functionality"
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/24/08    Time: 4:24p
//Created in $/Leap/Source/Templates/Student
//file added for assigning roll no.s to students

?>
