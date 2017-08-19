<?php 
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 12-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
										<table width="15%" align="center" border="0" cellspacing="0px" cellpading="0px">
											<tr>
												<td class="contenttab_internal_rows" align="left" nowrap><strong>Time Table</strong> </td>
												<td class="contenttab_internal_rows" align="left"><strong>:</strong></td>
												<td class="contenttab_internal_rows">
                                    <select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:240px" onChange="getLabelClass()">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
													</select>
												</td>
												<td class="contenttab_internal_rows" align="left" style="padding-left:5px" ><strong>Class</strong></td>
												<td class="contenttab_internal_rows" align="left"><strong>:</strong></td>
												<td class="contenttab_internal_rows" align="left" nowrap>&nbsp;
                                                <select size="1" class="selectfield" name="degree" id="degree" style="width:320px" onFocus="getLabelClass();" onChange="getClassSubjects();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select>
												</td>
											</tr>
											<tr>
												<td  class="contenttab_internal_rows" align="right" valign="top"><strong>Subject</strong></td>
												<td class="contenttab_internal_rows" align="right" valign="top"><strong>:</strong></td>
												<td  align="left" class="contenttab_internal_rows">
                    <select size="5" multiple name="subjectId[]"  class="selectfield" id="subjectId" style="width:240px"></select>
<div align="left">
Select &nbsp;<a class="allReportLink" href="javascript:makeSelection('subjectId[]','All','testWiseMarksReportForm');">All</a> / 
<a class="allReportLink" href="javascript:makeSelection('subjectId[]','None','testWiseMarksReportForm');">None</a>
												</div>
												</td>
										    <td class="contenttab_internal_rows" align="left" style="padding-left:5px" valign="top"><strong>Show</strong></td>
										    <td class="contenttab_internal_rows" align="left" valign="top"><strong>:</strong></td>
                                            <td class="contenttab_internal_rows" align="left" width="15%" valign="top">
                                              <table width="10%" align="left" border="0" cellpadding="0px" cellspacing="0px">  
                                                 <tr>   
										           <td class="contenttab_internal_rows" align="left" valign="top" nowrap>
												        <input type='checkbox' name='internal' checked/>Internal Marks <br>
												        <input type='checkbox' name='attendance' checked/>Attendance<br>
                                                        <input type='checkbox' name='isSGPA' checked/>SGPA
                                                   </td>
                                                   <td style="padding-left:20px"></td>
                                                   <td class="contenttab_internal_rows" align="left" valign="top" nowrap>
                                                        <input type='checkbox' name='external' checked/>External Marks <br>
                                                        <input type='checkbox' name='total' checked/>Total Marks<br>
                                                        <input type='checkbox' name='isCGPA' checked/>CGPA
                                                   </td>
                                                   <td style="padding-left:20px"></td>
                                                   <td class="contenttab_internal_rows" align="left" valign="top" nowrap>
                                                      <input type='checkbox' name='gradeList' checked/>Grade<br>
                                                      <input type='checkbox' name='emptyList' />Empty List
                                                   </td>
                                                </tr>
                                               </table>
                                            </td>   
                                           <td class="contenttab_internal_rows" align="left" width="15%" valign="top" style="padding-left:5px" colspan="10">
                                              <table width="10%" align="left" border="0" cellpadding="0px" cellspacing="0px">  
                                              <tr>  
                                                  <td colspan="1" align="left" class="contenttab_internal_rows" valign="top">  
                                                    <nobr><strong>Sort By</strong>
                                                  </td>
										          <td class="contenttab_internal_rows" align="right" colspan='1' nowrap  valign="top"><b>:</b></td>
                                                  <td colspan="1" align="left" class="contenttab_internal_rows" valign="top">
                                                     <select name="sorting"  class="selectfield" id="sorting" style="width:100px" onBlur="hideResults();">
													  <option value="cRollNo">C.RollNo</option>
													  <option value="uRollNo">U.RollNo</option>
													  <option value="name">Name</option>
												    </select>
                                                    </nobr>
											      </td>
                                               </tr>   
                                               <tr>
								                    <td class="contenttab_internal_rows" valign="top">
                                                        <nobr><strong>Order</strong>
                                                    </td>
                                                    <td class="contenttab_internal_rows" valign="top" align="right"><b>:</b></td>
                                                     <td class="" align="left" valign="top" nowrap>
<input type="radio" name="ordering" id="ordering1" value = "asc" checked="checked" onclick="hideResults();" />Asc&nbsp;
<input type="radio" name="ordering" id="ordering2" value="desc" onclick="hideResults();" />Desc &nbsp;
</td>
                      </tr>
                      <tr>
                      <td class="contenttab_internal_rows" align="left" valign="top" nowrap><strong>Print Records</strong></td>
                        <td class="contenttab_internal_rows" align="left" valign="top"><strong>:</strong></td>
                        <td class="contenttab_internal_rows" align="left" width="15%" valign="top">
                          <input type="text" name="printRecord" id="printRecord" class="inputbox" style="width:100px" value="12" size="4" />
                        </td>
                      </tr>
                      <tr>  
                        <td colspan="12" class="contenttab_internal_rows"  valign="bottom" nowrap height="50px">   
                       <center>
						  <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateAddForm(this.form)" />
                          </center>
                       </td>
                                                     </tr>
                                                   </table>       
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
