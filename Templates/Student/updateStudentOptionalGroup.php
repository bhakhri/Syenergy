<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used for group change
//
//
// Author :Jaineesh
// Created on : 07-Mar-2009
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
					<td valign="top" colspan="2">Setup&nbsp;&raquo;&nbsp; Student Setup &nbsp;&raquo;&nbsp;Update Student Optional Groups</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<form name="assignOptionalGroup" action="" method="post" onSubmit="return false;">
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
													<strong>Roll No :</strong> &nbsp;
												</td>
												<td align="left">
													<input type='text' name='rollNo' id='rollNo' autocomplete='off' class='htmlElement' size='24' value=""/>
												</td>
												<td align="center" colspan="1">
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
										<!-- /new row -->

							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr id='nameRow' style='display:none;'>
									<td class="" height="20">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
											<tr>
												<td colspan="1" class="content_title">Group Allocation:</td>
												
											</tr>
										</table>
									</td>
								</tr>
								<tr id='resultRow' style='display:none;'>
									<td colspan='1' class='contenttab_row' align='right' width='100%'>
									<div id = 'resultsDiv'></div>
									</td>
								</tr>
								<tr id='nameRow2' style='display:none;'>
								<td valign='' colspan='1' class='content_title' align="right"><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/update_student_details.gif" onClick="showTabs()" />&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
                        
                        </td>
                    </tr>
                </table>
			<?php floatingDiv_Start('showDetailsDiv','Details','',''); ?>
			<div id="innerDiv" style="height:450px;overflow:auto;">
			</div>
			<?php floatingDiv_End(); ?>

<?php
//$History: updateStudentOptionalGroup.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/10    Time: 12:56p
//Created in $/LeapCC/Templates/Student
//new ajax file for student optional change
//
?>