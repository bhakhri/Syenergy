<?php
//This file creates html form for student academic performance report
//
// Author :Jaineesh
// Created on : 29-Aug-2008
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
					<form name="studentPerformanceReportForm" action="" method="post" onSubmit="return false;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
										<table align="center" border="0px" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td class="contenttab_internal_rows" nowrap ><strong>Time Table</strong> </td>
                                                <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
												<td class="contenttab_internal_rows" nowrap><nobr>&nbsp;
                                                    <select size="1" class="inputbox1" name="timeTable" id="timeTable" style='width:145px' onchange="getLabelClass();">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
													</select></nobr>
												</td>
                                                <td class="contenttab_internal_rows" nowrap style="padding-left:3px;"><strong>Degree</strong> </td>
                                                <td class="contenttab_internal_rows1"><nobr><b>:</b></nobr></td>
												<td class="contenttab_internal_rows" nowrap>&nbsp;
													<select id="degree" name="degree[]" class="selectfield" style="width:260px" size="1">
														<option value="">Select</option>
													</select><br/>
												</td>
												<a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
												<td align="left" valign="top" style="padding-left:2px" height="100%" rowspan="5" >
                                                  <table border="0" cellpadding="0" cellspacing="0" height="100%">
                                                   <tr>  
                                                    <td align="left" class="contenttab_internal_rows" nowrap><b><span id='lblStatus'>Incl. Total</span></td>
                                                    <td class="contenttab_internal_rows" nowrap><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows" nowrap>
                                                     <input type="radio" name="incTotal" value="1" checked="checked">Yes&nbsp;
                                                     <input type="radio" name="incTotal" value="0" >No&nbsp;
                                                    </td>
                                                    <td class="contenttab_internal_rows" nowrap><nobr><b>Incl. Percentage</b></nobr></td>
                                                    <td class="contenttab_internal_rows" nowrap ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows" nowrap>
                                                      <input type="radio" name="showPercentage" value="1">Yes&nbsp;
                                                      <input type="radio" name="showPercentage" value="0" checked="checked">No&nbsp;
                                                    </td>  
                                                   </tr> 
                                                   <tr>
                                                   <td align="left" class="contenttab_internal_rows" nowrap ><b>Page No.</td>
                                                    <td class="contenttab_internal_rows" nowrap ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows">
                                                            <input type="radio" name="incPage" value="1"  checked="checked">Yes&nbsp;
                                                            <input type="radio" name="incPage" value="0">No&nbsp;
                                                    </td>
                                                    <td align="left" class="contenttab_internal_rows" nowrap ><b>Academic</b></td>
                                                    <td  class="contenttab_internal_rows" nowrap ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td  class="contenttab_internal_rows" nowrap>
                                                        <input type="radio" name="showTransfer" onclick="document.getElementById('lblStatus').innerHTML='Incl. Total';" value="0" checked="checked">Pre
                                                        <input type="radio" name="showTransfer" onclick="document.getElementById('lblStatus').innerHTML='Incl. Marks';" value="1">Post Transfer&nbsp;
                                                    </td>  
                                                   <tr>
                                                   <tr> 
                                                     <td class="contenttab_internal_rows" ><nobr><b>Signature</b></nobr></td>
                                                       <td class="contenttab_internal_rows" nowrap ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                       <td class="contenttab_internal_rows1" nowrap>
                                                          <input type="radio" name="signatureChk" onclick="setSignature();" checked="checked" value="0">No&nbsp;&nbsp;
                                                          <input type="radio" name="signatureChk" onclick="setSignature();"  value="1">Yes&nbsp;
                                                          <span style="display:none" id="signatureHide">
                                                             <input type="text" id="signatureContents" name="signatureContents" class="inputbox" style="width:220px" maxlength="100"/>
                                                          </span>
                                                       </td>
                                                       <td class="contenttab_internal_rows" ><nobr><b>Graph</b></nobr></td>
                                                       <td class="contenttab_internal_rows" nowrap ><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                       <td class="contenttab_internal_rows1" colspan="6" nowrap>
                                                          <input type="checkbox" name="graphAtt" id="graphAtt" value="1">&nbsp;Attendance&nbsp;&nbsp;
                                                          <!-- <input type="checkbox" name="graphMks" id="graphMks" value="1">&nbsp;Marks&nbsp;&nbsp; -->
                                                       </td>
                                                   </tr>
                                                   <tr>
                                                     <td colspan="6">&nbsp;</td>
                                                   </tr>
                                                   <tr>
                                                      <td class="contenttab_internal_rows1" colspan="6" align="right" nowrap> 
                                                         <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                      </td>
                                                   </tr>
                                                  </table>   
												</td>
											</tr>
                                            <tr>
                                             <td class="contenttab_internal_rows1" align="left" nowrap><b>Address</b></td>
                                             <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                             <td colspan="4" class="">
                                                 <table border="0" align="left" cellpadding="0" cellspacing="0" height="100%"> 
                                                   <tr>
                                                     <td class="" align="left"><nobr> 
                                                      <input type="radio" name="addressChk" value="1">Permanent&nbsp;
                                                      <input type="radio" name="addressChk" value="2">Correspondence&nbsp;
                                                      <input type="radio" name="addressChk" value="0" checked="checked" >None
                                                      </nobr>
                                                     </td> 
                                                     <td class="contenttab_internal_rows1" style="padding-left:10px" nowrap><b>HOD Info.</b></td>
                                                     <td class="contenttab_internal_rows1"><nobr><b>:</b></nobr></td>
                                                     <td class="contenttab_internal_rows1"><nobr>
                                                        <input type="radio" name="showHODInfo" value="1">Yes&nbsp;
                                                        <input type="radio" name="showHODInfo" value="0" checked="checked">No</nobr>   
                                                     </td>
                                                   </tr>  
                                                 </table> 
                                             </td> 
                                            </tr>
                                            <tr>
                                                 <td align="left" class="contenttab_internal_rows" nowrap><b>Detained Student</td>
                                                 <td class="contenttab_internal_rows" nowrap ><nobr><strong>:</strong></nobr></td>
                                                 <td class="" nowrap colspan="4">
                                                   <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                      <td class="" nowrap>  
                                                        <input type="radio" id="incDetained" name="incDetained" onclick="checkDetainedList();" value="1">Yes&nbsp;
                                                        <input type="radio" id="incDetained" name="incDetained" value="0" onclick="checkDetainedList();" checked="checked">No&nbsp;
                                                      </td> 
                                                      <td align="left" style="padding-left:15px" class="contenttab_internal_rows" colspan="3" nowrap id='filter1'>
                                                       <b>Theory&nbsp;:&nbsp;</b>
                                                        <input type="text" class="inputbox" id="incTheory" name="incTheory" maxlength="3" style="width:35px" value="<?php 
                                                         echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>" >&nbsp;
                                                         <b>Practical&nbsp;:&nbsp;</b>
                                                         <input type="text" class="inputbox" id="incPractical" name="incPractical" maxlength="3" style="width:35px" value="<?php 
                                                         echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>" >&nbsp;
                                                         <b>Training&nbsp;:&nbsp;</b>
                                                         <input type="text" class="inputbox" id="incTraining" name="incTraining" maxlength="3" style="width:35px" value="<?php 
                                                         echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>" >
                                                        </td>                                             
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
											<td colspan="1" class="content_title">Student Academic Performance Report :</td>
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
						</form>
						<!-- form table ends -->
					</td>
				</tr>
			</table>
		</table>

<?php

////$History: listStudentAcademicPerformanceReport.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 20/11/09   Time: 14:57
//Updated in $/LeapCC/Templates/StudentReports
//Modified "Student Academic Performance Report" and added "Detained
//Student" facility
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/19/09   Time: 5:31p
//Updated in $/LeapCC/Templates/StudentReports
//Detained Student checkbox added 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 23/10/09   Time: 11:23
//Updated in $/LeapCC/Templates/StudentReports
//Done bug fixing.
//Bug ids---
//00001864
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/10/09    Time: 12:22
//Updated in $/LeapCC/Templates/StudentReports
//Modified "Student Academic Performance Report"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 28/08/09   Time: 19:06
//Updated in $/LeapCC/Templates/StudentReports
//Created  "Student Academic Performance Report" module
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/24/09    Time: 10:36a
//Created in $/LeapCC/Templates/StudentReports
//new template file for student performance report
//
//
?>
