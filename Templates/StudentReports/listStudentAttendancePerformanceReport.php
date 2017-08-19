<?php
//This file creates html form for student academic performance report
//
// Author :Jaineesh
// Created on : 29-Aug-2008
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
					<form name="studentPerformanceReportForm" action="" method="post" onSubmit="return false;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr height=>
								<td valign="top" class="contenttab_row1" nowrap="nowrap">

										<table align="left" border="0" cellpadding="0" cellspacing="0" >
											<tr height="50">
												<td class="contenttab_internal_rows" nowrap ><strong>Time Table </strong> </td>
												<td class="padding">:&nbsp;<select size="1" class="inputbox1" name="timeTable" id="timeTable" onchange="getLabelClass();">
														<option value="">Select</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
													</select>
												</td>
                                                <td class="contenttab_internal_rows" nowrap><strong>Class</strong> </td>
												<td class="padding" >:&nbsp;
													<select id="degree" name="degree" class="inputbox" style="width:220px" size="1" onchange=
                                                    "getGroups(); getSubjects();"  >
														<option value="">Select</option>
													</select>
												</td>
                                                <td class="contenttab_internal_rows" nowrap><strong>Subject</strong> </td>
                                                <td class="padding" >:&nbsp;
                                                    <select id="subjectId" name="subjectId" class="inputbox" size="1" style="width:100px;" onchange="getGroups();"  >
                                                        <option value="-1">All</option>
                                                    </select>
                                                </td>
                                                <td class="contenttab_internal_rows" nowrap><strong>Group</strong> </td>
                                                <td colspan= 3 class="padding" >:&nbsp;
                                                    <select id="groupId" name="groupId" class="inputbox" size="1" style="width:80px;" onchange="hideResults();"  >
                                                        <option value="-1">All</option>
                                                    </select>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td class="contenttab_internal_rows"><b>Enter Range</b></td>
                                                <td class="padding" colspan="3">

                                                 :<input type="text" name="rangeText" id="rangeText" class="inputbox" style="width:98%;" value="0-25,26-35,36-50,51-65,66-75" onchange="hideResults();" />
                                                </td>
                                                <td colspan=4 class="contenttab_internal_rows">
                                                <table border=0 cellspacing="0" cellpadding="1">
                                                	<tr><td><b>&nbsp;Include Duty Leaves</b></td>
		                                            <td ><b>:</b>&nbsp;
		                                                <input type="radio" name="dutyLeave" value="1" checked="checked" />Yes &nbsp;
		                                                <input type="radio" name="dutyLeave" value="0" />No&nbsp;
		                                            </td></tr>
		                                            <tr><td><b>&nbsp;Include Medical Leaves</b></td>
		                                            <td ><b>:</b>&nbsp;
		                                                <input type="radio" name="medicalLeave" value="1" checked="checked" />Yes &nbsp;
		                                                <input type="radio" name="medicalLeave" value="0" />No&nbsp;
		                                            </td></tr>
	                                            </table>
	                                            </td>
                                                <td class="contenttab_internal_rows"><b>Report Format</b></td>
                                                <td class="padding" >:&nbsp;
                                                    <select id="reportFormat" name="reportFormat" class="inputbox" style="width:85px" size="1"  >
                                                        <option value="1">Roll No.</option>
                                                        <option value="2">Name</option>
                                                        <option value="3">Both</option>
														<option value="4">Summary</option>
                                                    </select>&nbsp;</td><td>
                                                    <input type="image" name="imageField" style="margin-bottom:-6px;" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateForm();return false;" />
                                                </td>
                                              </tr>
                                              <tr>
                                              	<td class="contenttab_internal_rows" align="left" colspan="6">
                                              		<font color="red"><b><u>Please Note</u>:&nbsp;N.A</b> means University Roll Number is not assigned.</font>
                                              	</td>
                                              </tr>
                                                  </table>
												</td>
											</tr>
										</table>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="10">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="10"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Student Attendance Performance Report :</td>
                                            <td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;</td>
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
                                            <td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
								</td>
							</tr>
						</table>
						</form>
						<!-- form table ends -->
					</td>
				</tr>
			</table>
		</table>

<?php
////$History: listStudentAcademicPerformanceReport.php $
?>
