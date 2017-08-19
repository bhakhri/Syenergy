<?php 
//This file creates Html Form output "listAssignGroup" Module 
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
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Setup&nbsp;&raquo;&nbsp; Student Setup &nbsp;&raquo;&nbsp;Assign Groups</td>
				</tr>
			</table>
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
										<table border='0' cellspacing='0' cellpadding='0'>
											<tr>
												<td valign='top' colspan='1' class='contenttab_internal_rows'>
													<input type='checkbox' name='pendingStudents' style='vertical-align:middle;'/>
													<B>Show Pending Students only</B>
												</td>
											</tr>
										</table>
										<table width="100%" align="center" border="0" >
											<tr>
												<td valign='top' colspan='8' class=''>
													<hr size='1'>
												</td>
											</tr>
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Select Degree :</strong>
												</td>
												<td colspan="3">
													<select size="1" class="selectfield" name="degree" id="degree" style="width:250px"  onBlur="countDegreeStudents();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH."/HtmlFunctions.inc.php");
															echo HtmlFunctions::getInstance()->getSessionClasses();?>
													</select>
												</td>
												<td class="contenttab_internal_rows"><strong>Total Students :</strong></td>
												<td colspan='3'><span id='totalDegreeStudentsSpan' class="contenttab_internal_rows">0</span></td>
											</tr>
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<nobr><strong>Group Type :</strong></nobr>
												</td>
												<td>
													<select size="1" class="selectfield" name="groupType" id="groupType" style="width:120px" onBlur="countGroupTypeStudents();">
														<option value="">Select</option>
														<?php
															echo HtmlFunctions::getInstance()->getGroupTypes();?>
														?>
													</select>
												</td>
												<td class="contenttab_internal_rows"><strong>Group Type Assigned Students :</strong> </td>
												<td class="contenttab_internal_rows" width='50' style='text-align:left;'><span id='groupTypeAssignedToStudentsSpan'  class="contenttab_internal_rows">0</span></td>
												<td class="contenttab_internal_rows" ><strong>Group Type Pending Students :</strong></td>
												<td colspan='3' style='text-align:left;'><span id='groupTypePendingStudentsSpan' class="contenttab_internal_rows">0</span></td>
											</tr>
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Group :</strong>
												</td>
												<td>
													<select size="1" class="selectfield" name="groupId" id="groupId" style="width:120px" onBlur="countPendingStudents();">
														<option value="">Select</option>
													</select>
												</td>
												<td class="contenttab_internal_rows"><strong>Group Assigned Students :</strong> </td>
												<td class="contenttab_internal_rows" width='50' style='text-align:left;'><span id='groupAssignedSpan' class="contenttab_internal_rows">0</span></td>
												<td class="contenttab_internal_rows"><strong>Sibling Group Assigned To :</strong> </td>
												<td width='50'  style='text-align:left;'><span id='siblingGroupAssignedSpan' class="contenttab_internal_rows">0</span></td>
												<td class="contenttab_internal_rows"><strong>Available for Assignment :</strong></td>
												<td width='50'><span id='groupPendingStudentsSpan' class="contenttab_internal_rows">0</span></td>
											</tr>
											<tr>
												<td  colspan="1" class="contenttab_internal_rows" align="right">
													<strong>No. of Students :</strong>
												</td>
												<td>
													<input type="text"  class="selectfield" name="assignNo" id="assignNo" style="width:80px">
												</td>
												<td  colspan="1" class="contenttab_internal_rows" align="right">
													<strong>Assign Groups By :</strong>
												</td>
												<td colspan='5'>
													<select size="1" class="selectfield" name="groupAssignment" id="groupAssignment" style="width:100px" onChange="hideResults();">
														<option value="rollNo">RollNo</option>
														<option value="alphabetic">Alphabetic</option>
													</select>
												</td>
											</tr>
											<tr>
												<td align="center" valign="top" colspan="8" >
													<table border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td valign='top' colspan='1' class=''><input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" name="listBtn" value="Show List" onClick="return validateAddForm(this.form);return false;"/></td>
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
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="saveSelectedStudents()" />&nbsp;</td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="saveSelectedStudents()" />&nbsp;</td>
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
//$History: listAssignGroup.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Templates/Student
//added code for assigning groups to pending students only.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/14/09    Time: 6:00p
//Updated in $/LeapCC/Templates/Student
//contained resultdiv in form
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/13/08   Time: 4:31p
//Updated in $/LeapCC/Templates/Student
//updated code for student group assignment.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:34p
//Updated in $/LeapCC/Templates/Student
//working on student group allocation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 9/08/08    Time: 3:15p
//Updated in $/Leap/Source/Templates/Student
//removed unnecessary code
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/18/08    Time: 6:52p
//Updated in $/Leap/Source/Templates/Student
//changed buttons, improved page design and fixed bug found during
//self-testing.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Student
//Removed Width and Height on cancel button
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/09/08    Time: 4:50p
//Updated in $/Leap/Source/Templates/Student
//improved design
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:38p
//Updated in $/Leap/Source/Templates/Student
//file modified for improving functionality
//
//*****************  Version 3  *****************
//User: Admin        Date: 8/05/08    Time: 11:36a
//Updated in $/Leap/Source/Templates/Student
//changed as per new format and shown the list which are assigned group
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/28/08    Time: 5:36p
//Updated in $/Leap/Source/Templates/Student
//fixed minor designing issues

?>
