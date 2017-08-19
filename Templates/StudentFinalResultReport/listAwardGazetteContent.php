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
									<form name="testWiseMarksReportForm" action="" method="post" onSubmit="return false;">
										<table width="65%" align="center" border="0" cellspacing="0px" cellpading="0px">
											<tr>
												<td class="contenttab_internal_rows" align="right" nowrap><strong>Time Table</strong> </td>
												<td class="contenttab_internal_rows" align="right"><strong>:</strong></td>
												<td class="contenttab_internal_rows"><select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:150px" onChange="hideResults();getLabelClass()">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
													</select>
												</td>
												<td class="contenttab_internal_rows" align="right" style="padding-left:10px" ><strong>Class</strong></td>
												<td class="contenttab_internal_rows" align="right"><strong>:</strong></td>
												<td class="contenttab_internal_rows" align="right"><nobr>
                                                <select size="1" class="selectfield" name="degree" id="degree" style="width:250px" onFocus="hideResults();getLabelClass();" onChange="hideResults();getClassSubjects();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select></nobr>
												</td>
												<td class="contenttab_internal_rows" style="padding-left:10px" ><nobr><strong>Sort</strong></td>
												<td class="contenttab_internal_rows" align="right"><strong>:</strong></td>
												<td class="contenttab_internal_rows"><nobr>
                                                    <select name="sorting"  class="selectfield" id="sorting" style="width:100px" onBlur="hideResults();">
														<option value="cRollNo">C.RollNo</option>
														<option value="uRollNo">U.RollNo</option>
														<option value="name">Name</option>
													</select></nobr>
												</td>
												<td class="contenttab_internal_rows" style="padding-left:10px" ><nobr><strong>Order</strong></td>
                                                <td class="contenttab_internal_rows" align="right"><strong>:</strong></td>
												<td class="contenttab_internal_rows"  valign="top" colspan="10" nowrap>
                                                    <table width="10%" align="left" border="0" cellpadding="0px" cellspacing="0px">  
                                                     <tr>
                                                       <td class="" align="left" valign="top" nowrap>
                                                         <input type="radio" name="ordering" id="ordering1" value = "asc" checked="checked" onclick="hideResults();" />Asc&nbsp;
                                                       </td>
                                                       <td class="" align="left" valign="top" nowrap>
                                                          <input type="radio" name="ordering" id="ordering2" value="desc" onclick="hideResults();" />Desc &nbsp;
                                                       </td>
                                                       <td class="" style="padding-left:20px" align="left" valign="top" nowrap>   
													      <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateAddForm(this.form)" />
                                                       </td>
                                                     </tr>
                                                   </table>       
                                                </td>
											</tr>
                                            <tr style="display:none">
                                                <td class="padding" align="right" valign="top"><strong>Subject</strong></td>
                                                <td class="padding" align="right" valign="top"><strong>:</strong></td>
                                                <td align="left" class="padding"><select size="5" multiple name="subjectId[]"  class="selectfield" id="subjectId" style="width:150px"></select><div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('subjectId[]','All','testWiseMarksReportForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('subjectId[]','None','testWiseMarksReportForm');">None</a>
                                                </div>
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
											<td colspan="1" class="content_title">Total Number Of Students : <span id='totalStudents'></span></td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:1000px; height:410px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                    </div>
									<div style="padding-right:20px" id='pagingDiv' align='right'></div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;</td>



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
//$History: listStudentConsolidatedReport.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-04   Time: 3:44p
//Updated in $/LeapCC/Templates/StudentReports
//Changed default value of subject type to "Select" from "All" as this
//was mandatory field
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/03/09    Time: 10:46a
//Updated in $/LeapCC/Templates/StudentReports
//Gurkeerat: resolved issue 1438
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/29/09    Time: 6:21p
//Created in $/LeapCC/Templates/StudentReports
//Intial checkin
?>
