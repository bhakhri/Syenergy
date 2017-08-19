<?php 
//--------------------------------------------------------
// Author :Parveen Sharma
// Created on : 03-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
									<form name="resourceForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0" width="15%">
											<tr>
												<td class="contenttab_internal_rows" align="right" nowrap>
													<strong>Course</strong>&nbsp;
												</td>
                                                <td class="contenttab_internal_rows" align="right" nowrap>
                                                    <strong>:</strong>&nbsp;
                                                </td>
												<td class="padding" align="right" nowrap>
													<select size="1" style="width:150px" class="htmlElement" name="subjectId" id="subjectId" onchange="hideResults()">
                                                        <option value="Select">Select</option>
														<option value="All">All</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getSubjectsWithCode();?>
													</select>
												</td>
												<td class="padding" align="right" nowrap>
													<span style="padding-right:10px" >
													<input type="image" name="resourceListSubmit" value="resourceListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form); return false;" />
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
											<td colspan="1" class="content_title">Coursewise Resources Report :</td>
											<td colspan="2" class="content_title" align="right"><input type="image" name="resourcePrintSubmit" value="resourcePrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="resourcePrintSubmit" value="resourcePrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
//$History: scCourseWiseResourceReport.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 5:00p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/17/09    Time: 12:10p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/01/09   Time: 15:39
//Created in $/SnS/Templates/StudentReports
//Added "Coursewise resource report" module
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/10/08   Time: 3:27p
//Updated in $/Leap/Source/Templates/ScStudentReports
//issue fix
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/04/08   Time: 11:33a
//Updated in $/Leap/Source/Templates/ScStudentReports
//sorting format setting
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/08   Time: 4:12p
//Created in $/Leap/Source/Templates/ScStudentReports
//coursewise resource file added 
//
//


?>
