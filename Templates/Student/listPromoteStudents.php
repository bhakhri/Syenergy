<?php
//-------------------------------------------------------
//  This File contains html code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 31-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
									<form name="promoteStudentsForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td colspan="1" align="right" valign="top" >
													<strong>Degree :</strong> &nbsp;
												</td>
												<td valign="top" rowspan='1'>
													<select name="class1" id="class1" class="htmlElement2"   onBlur="hideDetails();">
														<option value=''>Select</option>
													</select>
												</td>
												<td align="left" colspan="2" valign="top">
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/promote.gif" onClick="return validateAddForm(this.form);return false;" />
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
											<td colspan="1" class="content_title">List of Students which could not be promoted :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
<?php floatingDiv_Start('promotionDiv','Student Promotion','',''); ?>
<div style="width:800px;height:400px;">
<form name="promotionFormMain" action="" method="post">
	<div id="promotionDetailDiv" style="width:800px;overflow:auto;display:none;">
		<h2><font color='red'>Promotion is a critical activity and can not be reverted back. <br />Once students are promoted, their previous class will become inactive and no attendance / tests can be taken. Are you sure you still want to promote students?</font></h2><input type='hidden' id='class1' name='class1' value='' />
		<table border='0' width='100%'><tr height='25' align='center'><td style='width:400px;background-color:#FF712D;'><a href='javascript:confirmDetails();' class='whiteLink'>Yes, I am sure and want to promote students. Please proceed.</a></td><td align='center' style='width:400px;background-color:#FFFFBF;'><a href='javascript:hiddenFloatingDiv("promotionDiv");' class='redLink'>No, I do not want to promote students. Please close this window.</a></td></tr></table>
	</div>
	<div id="promotionDetailDiv2" style="width:800px;overflow:auto;"></div>
	<div id="promotionDetailDiv3" style="width:800px;overflow:auto;display:none;">
	<table width='100%' border='0'>
		<tr><td width='50%'>Attendance entry for this class has been done : </td><td><b><input type='radio' name='attendance' value='no'>No&nbsp;<input type='radio' name='attendance' value='yes'>Yes</b></td></tr>
		<tr><td>Test entry for this class has been done : </td><td><b><input type='radio' name='test' value='no'>No&nbsp;<input type='radio' name='test' value='yes'>Yes</b></td></tr>
		<tr><td>Marks have been transferred for this class : </td><td><b><input type='radio' name='marks' value='no'>No&nbsp;<input type='radio' name='marks' value='yes'>Yes</b></td></tr>
		<tr height='25' align='center'><td width='100%' colspan='2'><input name='sub' type='button' value='I am sure, I want to Promote students' onClick='promoteStudents();'></td></tr>
	</table>
	</div>
</form>
</div>
<?php floatingDiv_End(); ?>
<?php
//$History: listPromoteStudents.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/06/10    Time: 5:23p
//Updated in $/LeapCC/Templates/Student
//updated breadcrumb
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/29/09    Time: 11:36a
//Created in $/LeapCC/Templates/Student
//file added for student promotion.
//

?>
