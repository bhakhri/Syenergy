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
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td valign="top" colspan="2">
					<?php
					if ($sessionHandler->getSessionVariable('RoleId') != 2) {
						?>
						  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
					<!--	Reports&nbsp;&raquo;&nbsp; Examination Reports&nbsp;&raquo;&nbsp;Final Internal Marks Report -->
					<?php
					}
					else {
					?>
					  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 

				<!--	Marks & Attendance&nbsp;&raquo;&nbsp; Display Final Internal Marks Report -->
					<?php
					}
					?>


					</td>
				</tr>
			</table>
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
								<td valign="top" class="contenttab_row1" nowrap="nowrap">
									<form name="testWiseMarksReportForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="1px" cellspacing="2px" width="80%" >
											<tr>
												<td valign='top' colspan='10' class='contenttab_internal_rows'>
                                                  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: red;">
													<b>Note: Only those degrees and students will be shown for which marks have been transferred.</b>
                                                  </span>  
												</td>
											</tr>
                                            <tr><td height="10px"></td></tr>
											<tr>
												
                                                <?php
                                                if ($sessionHandler->getSessionVariable('RoleId') != 2) {
                                                ?>
                                                <td class="contenttab_internal_rows" colspan='1' align='right' nowrap><strong>Time Table</td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                                                <td class="contenttab_internal_rows" align="left" nowrap>
                            <select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:290px" onChange="getLabelEmployee();getLabelClass(); ">                                  <option value="">Select</option>
                                                    <?php
                                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                      echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                                    ?>
                                                    </select></nobr>
                                                </td>
                                                <td class="contenttab_internal_rows" colspan='1' align='right' nowrap><strong>Teacher</td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                                                <td class="contenttab_internal_rows" align="left" colspan='8' nowrap>
                                                <select size="1" class="inputbox1" name="employeeId" id="employeeId" style="width:255px" onChange="getLabelClass();">
                                                    </select></nobr>
                                                </td>
                                                <?php } 
                                                else { 
                                                ?>
                                                <td class="contenttab_internal_rows" colspan='1' align='right' nowrap><strong>Time Table</td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                                                <td class="contenttab_internal_rows" align="left" nowrap>
                                <select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:290px" onChange="getLabelClass();">                                          <option value="">Select</option>
                                                    <?php
                                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                      echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                                    ?>
                                                    </select></nobr>
                                                </td>
                                                <?php }  ?>
											</tr>
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right"><strong>Degree</strong></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
												<td class="contenttab_internal_rows" align="left" colspan='1' nowrap>
													<select size="1" class="selectfield" name="degree" id="degree" style="width:290px" onChange="getClassSubjects();">
														<option value="">Select</option>
														<?php
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
													</select></nobr>
												</td>
												<td colspan="1" align="left" class="contenttab_internal_rows"><nobr><strong>Subject </strong></td>
												<td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr>
													<select name="subjectId"  class="selectfield" id="subjectId" style="width:100px" onChange="getGroups();" >
														<option value="">Select</option>
													</select></nobr>
												</td>
												<td colspan="1" align="left" class="contenttab_internal_rows"><nobr><strong>Group </strong></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
												<td class="contenttab_internal_rows" align="left" colspan='2' nowrap>
												<select name="groupId"  class="selectfield" id="groupId" style="width:100px" onChange="hideResults();">
														<option value="">Select</option>
													</select></nobr>
												</td>
											</tr>
											<tr>
												<td colspan='1' class="contenttab_internal_rows" align="right"><nobr><b>Show Grace Marks</b></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
												<td colspan='1' class="contenttab_internal_rows" align='left'>
                                                   <table align="left" border="0" cellpadding="0" cellspacing="0" width="20%" >  
                                                     <tr>
                                                       <td class="contenttab_internal_rows">
													      <input type="radio" name="showGraceMarks" id="showGraceMarks1" checked="checked" onclick="hideResults();" />No&nbsp;
                                                          <input type="radio" name="showGraceMarks" id="showGraceMarks2" onclick="hideResults();" />Yes
                                                       </td>
                                                       <td class="contenttab_internal_rows" style="padding-left:20px" align="right"><nobr><b>External Marks</nobr></b></td>
                                                       <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                                                       <td class="contenttab_internal_rows">
                                                          <input type="radio" name="showExternalMarks" id="showExternalMarks1" checked="checked" onclick="hideResults();" />No&nbsp;
                                                          <input type="radio" name="showExternalMarks" id="showExternalMarks2" onclick="hideResults();" />Yes
                                                       </td>
                                                     </tr>
                                                   </table>       
												</td>
                                                
                                                
												<td colspan="1" align="left" class="contenttab_internal_rows"><nobr><strong>Sort</strong></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
												<td class="contenttab_internal_rows" align="left" colspan='1' nowrap>
												<select name="sorting"  class="selectfield" id="sorting" style="width:100px" onBlur="hideResults();">
														<option value="cRollNo">C.RollNo</option>
														<option value="uRollNo">U.RollNo</option>
														<option value="name">Name</option>
													</select></nobr>
												</td>
													<td colspan="1" align="left" class="contenttab_internal_rows"><nobr><strong>Order</strong></td>
                                                    <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
												    <td colspan="1" align="left" class="contenttab_internal_rows">
													 <input type="radio" name="ordering" id="ordering1" checked="checked" onclick="hideResults();" />Asc&nbsp;
													 <input type="radio" name="ordering" id="ordering2"  onclick="hideResults();" />Desc
												</td>
											</tr>
											<tr>
												<td valign='top' class="contenttab_internal_rows" align="left"><nobr><b>Show Marks</b></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
												<td valign='middle' colspan='5' class="contenttab_internal_rows" align='left'>
													<!--<select name='showGraceMarks' class="selectfield" style="width:200px" onBlur="hideResults();">
													<option value='no'>No</option>
													<option value='yes'>Yes</option>
													</select>-->
                                                    <input type="radio" name="showMarks" id="showMarks1" checked="checked" onclick="hideResults();" />Weighted Marks [Marks propotionate to Test-type marks]&nbsp;
                                                    <input type="radio" name="showMarks" id="showMarks2" onclick="hideResults();" />Actual Marks
												</td>
												<td colspan='1' align='left'></td>
											</tr>
											<tr>
												<td  colspan='1' class="contenttab_internal_rows" align="left"><nobr><b>Show University Roll No.</b></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
												<td  colspan='10' class="contenttab_internal_rows" align='left'>
                                                    <table border="0" cellspacing="0" cellpadding="0" align="left" >
                                                        <tr>
                                                            <td>
                                    <input type="radio" name="showUnivRollNo" id="showUnivRollNo1" onclick="hideResults();" />No&nbsp;
                                    <input type="radio" name="showUnivRollNo" id="showUnivRollNo2" checked="checked" onclick="hideResults();" />Yes
												</td>
                                                          <td class="contenttab_internal_rows" style="padding-left:20px"> 
                                                                <b>Rounding Method for Internal Marks</b>
                                                          </td>
                                                          <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td> 
                                                          <td class="contenttab_internal_rows" valign="top">
                                                            <select name="roundMethod" id="roundMethod" class="inputbox1" style="width:120px;">
                                                                <option value="ceil">Ceil</option>
                                                                <option value="round">Rounding</option>
                                                                <option value="">No Rounding</option>
                                                             </select>
                                                          </td>
                                                     <td>
													<span  style="padding-left:20px;" >&nbsp;
													<input style="margin-bottom:-2px;" type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" /></span>
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
											<td colspan="1" class="content_title">Final Marks Report&nbsp;|&nbsp;Avg: <span id='courseAverage'></span> &nbsp;|&nbsp;Teachers: <span id='teachers'></span>&nbsp;|&nbsp;Subject: <span id='subjectName'></span></td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick='printCSV()'/></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
                                  <div id="scroll2" style="overflow:auto; width:970px; height:370px; vertical-align:top;">     
									  <div  style="width:98%" id = 'resultsDiv'></div>
                                   </div>   
                                   <br>
								   <div id = 'pagingDiv' align='right'></div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick='printCSV()'/></td>
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

////$History: listFinalInternalReport.php $
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 4/01/10    Time: 13:17
//Updated in $/LeapCC/Templates/StudentReports
//Made UI Changes
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 12/06/09   Time: 2:36p
//Updated in $/LeapCC/Templates/StudentReports
//done changes in files for marks transfer
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 11/12/09   Time: 11:14a
//Updated in $/LeapCC/Templates/StudentReports
//done changes to fix following bug no.s:
//0001987
//0001986
//0001985
//0001984
//0001983
//0001777
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 10/28/09   Time: 1:49p
//Updated in $/LeapCC/Templates/StudentReports
//done changes for making on/off for grace marks.
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 10/08/09   Time: 5:05p
//Updated in $/LeapCC/Templates/StudentReports
//resolved issue 0001717
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 10/06/09   Time: 4:36p
//Updated in $/LeapCC/Templates/StudentReports
//fixed bug no.1707
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/StudentReports
//Corrected look and feel of teacher module logins
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 5/21/09    Time: 7:02p
//Updated in $/LeapCC/Templates/StudentReports
//added code for sorting and ordering.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/12/09    Time: 7:30p
//Updated in $/LeapCC/Templates/StudentReports
//commented print button
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/12/09    Time: 6:34p
//Updated in $/LeapCC/Templates/StudentReports
//corrected breadcrumb
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/07/09    Time: 8:13p
//Created in $/LeapCC/Templates/StudentReports
//file added for internal report.
//


?>
