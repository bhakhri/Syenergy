<?php
//This file creates Html Form output "listAssignRollNo" Module
//
// Author :Ajinder Singh
// Created on : 04-Dec-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
					<td valign="top" colspan="2">
					<?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<form name="updateMarksForm" action="" method="post" onSubmit="return false;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405" >
					<tr>
						<td valign="top" class="content">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" class="contenttab_border2">
										<table align="center" border="0" >
										<!-- new row -->
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Roll No. :</strong> &nbsp;
												</td>
												<td align="left">
													<input type='text' name='rollNo' value='' class='htmlElement' size='24'/>
												</td>
												<td align="center" colspan="1">
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
										</table>
										<!-- /new row -->

							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr id='holdResultRow' style='display:none;'>
									<td class="" height="20">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_row">
											<tr>
												<td colspan="1" class="" width="10%">&nbsp;<B>Student Name :</B></td>
												<td colspan="1" class="" align="left"><input type='hidden' name='studentName' value='' /></td>
											</tr>
										</table>
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
											<tr>
												<td colspan="1" class="content_title" width='50%'>Hold Unhold Result :</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr id='holdResultRow2' style='display:none;'>
									<td colspan='1' class='contenttab_row' align='left'><div id = 'holdResultDiv'></div>
									<input type="image" name="studentListSubmit" value="studentListSubmit" id="holdUnholdImage" src="<?php echo STORAGE_HTTP_PATH;?>/Images/save.gif" onClick="holdUnholdResult()" />&nbsp;</td>
								</tr>
								<tr id='nameRow' style='display:none;'>
									<td class="" height="20">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
											<tr>
												<td colspan="1" class="content_title" width='50%'>Mark Details : <span id='studentNameSpan' style="padding-left:20px; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 12px; color: white;"></span></td>
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
												<td height="20" colspan='2' class='content_title' align="right"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>


<?php floatingDiv_Start('updateMarks','Update Marks and Grades'); ?>
<div id='internalDiv'></div>
<?php floatingDiv_End(); ?>
<?php floatingDiv_Start('updateReappearMarks','Update Re-Exam Marks and Grades'); ?>
<div id='reappearDiv'></div>
<?php floatingDiv_End(); ?>
<?php floatingDiv_Start('updateMarksStatus','Select Final Marks'); ?>
<div id='finalMarksDiv'></div>
<?php floatingDiv_End(); ?>



<?php
//$History: scListUpdateTotalMarks.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 1/19/10    Time: 5:34p
//Updated in $/Leap/Source/Templates/ScStudent
//fixed bugs. FCNS No. 1099
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 1/12/10    Time: 11:20a
//Updated in $/Leap/Source/Templates/ScStudent
//fixed issues: 2576, 2577, 2578
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 9/01/09    Time: 5:19p
//Updated in $/Leap/Source/Templates/ScStudent
//added student name,
//corrected attendance bug
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 5/01/09    Time: 5:05p
//Updated in $/Leap/Source/Templates/ScStudent
//added code for hold/unhold result.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 3/18/09    Time: 3:25p
//Updated in $/Leap/Source/Templates/ScStudent
//changed issue of wrong breadcrumb.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 3/02/09    Time: 4:23p
//Updated in $/Leap/Source/Templates/ScStudent
//modified to make it working for last level.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/23/09    Time: 3:19p
//Updated in $/Leap/Source/Templates/ScStudent
//modified to fix issue for update total marks problem with IE
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 1/07/09    Time: 12:52p
//Updated in $/Leap/Source/Templates/ScStudent
//added value I in drop down
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/15/08   Time: 12:36p
//Updated in $/Leap/Source/Templates/ScStudent
//added code for "reason"
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/04/08   Time: 4:33p
//Created in $/Leap/Source/Templates/ScStudent
//file made for marks updation for single student
//




?>
