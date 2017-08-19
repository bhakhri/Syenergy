<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used for group change
//
//
// Author :Ajinder Singh
// Created on : 07-Mar-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");    
?>
	<tr>
		<td valign="top" colspan=2>
			<form name="assignGroup" action="" method="post" onSubmit="return false;">
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
									<div id = 'resultsDiv'>
									</div>
									</td>
								</tr>
								<tr id='nameRow2' style='display:none;'>
								<td valign='' colspan='1' class='content_title' align="right"><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/update_student_details.gif" onClick="showTabs()" />&nbsp;</td>
								</tr>
                                
                                <tr id='resultRow1' style='display:none;'>
                                    <td colspan='1' class='contenttab_row' align='right' width='100%'>
                                    <div id = 'resultsDiv1'>
                                    </div>
                                    </td>
                                </tr>
                                <tr id='nameRow22' style='display:none;'>
                                <td valign='' colspan='1' class='content_title' align="right"><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/update_student_details.gif" onClick="showTabs2()" />&nbsp;</td>
                                </tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
                        
                        </td>
                    </tr>
                </table>
			<?php floatingDiv_Start('showDetailsDiv','','',''); ?>
			<div id="innerDiv" style="height:450px;overflow:auto;">
			</div>
			<?php floatingDiv_End(); ?>

<?php
//$History: updateStudentGroup.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Student
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Templates/Student
//worked on role to class
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/03/09    Time: 2:10p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 1393
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:33p
//Created in $/LeapCC/Templates/Student
//file added for group change
//
?>