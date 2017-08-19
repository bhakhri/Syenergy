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
										<table width="100%" border="0" cellspacing="2px" cellpadding="0px">
											<tr>
                                                <td class="contenttab_internal_rows" nowrap ><strong>Time Table</strong> </td>
                                                <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
												<td class="contenttab_internal_rows" nowrap >
                                                    <select size="1" class="inputbox1" name="timeTable" id="timeTable" onChange="getLabelClass()" style="width:150px">
														<option value="">Select</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
													</select>
												</td>


                                               <td class="contenttab_internal_rows" nowrap ><strong>Degree</strong> </td>
                                                <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" nowrap >
												    <select size="1" class="selectfield" name="degree" id="degree" onChange="getClassSubjects();"  style="width:272px">
														<option value="">Select</option>
														<?php
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getTimeTableClasses();?>
													</select>
												</td>
                                                <td class="contenttab_internal_rows" nowrap ><strong>Subject</strong> </td>
                                                <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" nowrap >
												<select name="subjectId[]"  class="selectfield" id="subjectId"  onChange="getGroups();" style="width:170px">
														<option value="">ALL</option>
													</select>
												</td>
                                                <td class="contenttab_internal_rows" nowrap style="display:none"><strong>Subject Type</strong> </td>
                                                <td class="contenttab_internal_rows" nowrap style="display:none"><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" nowrap style="display:none" >
                                                    <select name="subjectTypeId"  class="selectfield" id="subjectTypeId" style="width:90px"  onChange="getTypeGroups();">
                                                        <option value="">ALL</option>
                                                    </select>
                                                </td>
											</tr>
											<tr>
											    <td class="contenttab_internal_rows" nowrap ><strong>Group</strong> </td>
                                                <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" nowrap >
													<select name="groupId"  class="selectfield" id="groupId" style="width:150px" onBlur="hideResults();">
														<option value="">ALL</option>
													</select>
												</td>
                                                <td class="contenttab_internal_rows" nowrap ><strong>Marks</strong> </td>
                                                <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" nowrap>
                                                    <table width="60%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="contenttab_internal_rows" nowrap >
													        <select size="1" class="inputbox1" name="marksFor" id="marksFor"  style="width:90px" onChange="disableOption(this.value)"  >
														        <option value="0">Both</option>
														        <option value="1">Internal</option>
														        <option value="2">External</option>

													        </select>
												        </td>
                                                        <td class="contenttab_internal_rows" nowrap  style="padding-left:10px"><strong>Report For</strong> </td>
                                                        <td class="contenttab_internal_rows" nowrap ><nobr><strong>:&nbsp;</strong></nobr></td>
                                                        <td class="contenttab_internal_rows" nowrap align="right" >
                                                            <select size="1" class="inputbox1" name="reportFor" id="reportFor" style="width:110px" onChange="changeParameter(this.value)">
                                                                <option value="2">Marks</option>
                                                                <option value="1">Attendance</option>
                                                                <!--option value="0">Both</option-->
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </table>
                                                </td>
												<td class="contenttab_internal_rows" nowrap  align="left">
                                                <strong>Criteria</strong></td>
												<td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                                <td nowrap colspan='5' >
                                                    <select name="average" id="average" size="1" style="width:125px" class="inputbox1">
                                                          <option value="">Select</option>
                                                         <option value="1">Above Marks</option>
                                                         <option value="2">Below Marks</option>
                                                         <option value="3">Above Attendance</option>
                                                         <option value="4">Below Attendance</option>
                                                    </select>
                                                    &nbsp;
                                                <input type="text" name="percentage" id="percentage" style="width:30px" value="" maxlength="3" class="inputbox1" /> %
                                                </td>
											</tr>
											<tr>
                                               <td class="contenttab_internal_rows" nowrap ><strong>Sort By</strong> </td>
                                               <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                               <td class="contenttab_internal_rows" nowrap >
                                                   <select size="1" class="inputbox1" name="sortField1" id="sortField1" style="width:150px">
														<option value="universityRollNo">Univ. Roll No.</option>
														<option value="rollNo">Roll No.</option>
														<option value='firstName'>Namewise</option>
													</select>
                                                </td>
                                               <td class="contenttab_internal_rows" nowrap ><strong>Order</strong> </td>
                                               <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                               <td class="contenttab_internal_rows" colspan="20">
                                                    <table width="40%" border="0" cellspacing="0" cellpadding="0" align="left">
                                                      <tr>
                                                        <td class="contenttab_internal_rows" nowrap >
												            <input type="radio" name="sortOrderBy1" id="sortOrderBy1" value="ASC"  checked="checked" onclick="hideResults();" />Asc&nbsp;
													        <input type="radio" name="sortOrderBy1" id="sortOrderBy2" value="DESC" onclick="hideResults();" />Desc
											            </td>
                                                        <td class="contenttab_internal_rows" nowrap style="padding-left:15px"><strong>With Grace Marks</strong> </td>
                                                        <td class="contenttab_internal_rows" nowrap width="1px"><nobr><strong>:</strong></nobr></td>
                                                        <td class="contenttab_internal_rows" nowrap >
                                                            <input type="radio" name="showGraceMarks" id="showGraceMarks1" checked="checked" onclick="hideResults();" />No&nbsp;
                                                            <input type="radio" name="showGraceMarks" id="showGraceMarks2" onclick="hideResults();" />Yes
												        </td>
                                                        <td class="contenttab_internal_rows" nowrap style="padding-left:15px"><strong>With Grades</strong> </td>
                                                        <td class="contenttab_internal_rows" nowrap width="1px"><nobr><strong>:</strong></nobr></td>
                                                        <td class="contenttab_internal_rows"><nobr>
                                                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                              <td class="contenttab_internal_rows"><nobr>
												                <input type="radio" name="showGrades" id="showGrades1" checked="checked" onclick="hideResults();" />No&nbsp;
                                                                <input type="radio" name="showGrades" id="showGrades2" onclick="hideResults();" />Yes
                                                                </nobr>
												              </td>
                                                              <td class="contenttab_internal_rows"  style="padding-left:25px" nowrap>
                                                                <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                               </td>
                                                            </tr>

                                                           </table></nobr>
                                                      </td>
                                                        </tr>
                                                    </table>
                                                </td>
											   </tr>
											   	<tr>
												<td class="contenttab_internal_rows" nowrap colspan="10" >
                                                    <table width="40%" border="0" cellspacing="0" cellpadding="0" align="left">
                                                      <tr>
                                                        <td class="contenttab_internal_rows" nowrap valign="top"  >
                                                            <strong>Define Range in % (0-10, 11-20, 21-30........)</strong> 
                                                        </td> 
                                                        <td class="contenttab_internal_rows" nowrap valign="top"  >
                                                            <strong>:&nbsp;</strong> 
                                                        </td> 
												        <td class="contenttab_internal_rows" nowrap colspan="7"><strong></strong>&nbsp;&nbsp;<input type="text" name="range" style="width:450px" id="range"value="<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
															    echo HtmlFunctions::getInstance()->getMarksRange();?>"  class="inputbox1" />
                                                                <br>&nbsp;&nbsp;<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> Define Range in % which will include starting and ending value</strong> </td> </span>
                                                        </td>
                                                      </tr>
                                                    </table>         
                                                 </td>   
											</tr>
                                            <tr>
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
											<td colspan="1" class="content_title">Testwise Marks Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='showSubjectEmployeeList' style='display:none;'>
							 <td class="contenttab_internal_rows" colspan="20"><nobr>
							  <table width="100%">
								<tr>
								  <td class="contenttab_internal_rows" colspan="20" >
									<b><a href="javascript:getShowDetail();" class="link">Show Subject & Teacher Details</b></a>
									   <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
								  </td>
								 </tr>
								 <tr>
								  <td class="contenttab_internal_rows" colspan="20" id='showSubjectEmployeeList11'>
									<nobr><span id='subjectTeacherInfo'></span></nobr>
								  </td>
								 </tr>
							  </table>
							  </td>
						   </tr>
							<tr id='resultRow'>
								<td colspan='1' class='contenttab_row' align="center">
								<div id="scroll" style="OVERFLOW: auto; HEIGHT:300px;width:1000px; TEXT-ALIGN: justify;">
									<div id = 'results'></div>
								</div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
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
