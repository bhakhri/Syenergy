<?php 
//--------------------------------------------------------
//This file creates Html Form output for marks not entered report
//
// Author :Ajinder Singh
// Created on : 23-oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<!-- form table starts -->
						<form name="marksNotEnteredForm" action="" method="post" onSubmit="return false;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
								<tr>
									<td valign="top" class="contenttab_row1">
											<table align="left" border="0" cellpadding="2px" cellspacing="2px">
												<tr>
													<td valign="top" nowrap class="contenttab_internal_rows"><nobr><b>Time Table</b></nobr></td>
                                                    <td valign="top" nowrap class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td valign="top" nowrap  class="">
													<select size="1" class="inputbox1" name="labelId" id="labelId" style="width:220px;"  onChange="getLabelClass();">
													<option value="">Select</option>
													<?php
													  require_once(BL_PATH.'/HtmlFunctions.inc.php');
													  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
													?>
													</select></td>
													<td valign="top" nowrap  class="contenttab_internal_rows"  align="left">
														<strong>Class</strong>
													</td>
                                                    <td valign="top" nowrap class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td valign="top"  nowrap class="">
														<select class="htmlElement2" name="degreeId" id="degreeId" style="width:280px;" onChange="getClassSubjects();">
														<option value="">Select</option>
														</select>
													</td>
													<td valign="top"  nowrap class="contenttab_internal_rows" align="left">
														<strong>Subject </strong> &nbsp;
													</td>
                                                    <td valign="top" nowrap class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                                                    <td valign="top" nowrap  align="left" class="">
														<select size="1" class="htmlElement" style="width:180px;" name="subjectId" id="subjectId">
															<option value="">Select</option>
														</select>
													</td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top"  nowrap align="left" class="contenttab_internal_rows">
							                                <strong>Grading</strong>
						                                </td>
                                                        <td valign="top" nowrap class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
						                                <td valign="top" nowrap class="contenttab_internal_rows">
							                                <select name="gadeLabelId"  class="htmlElement" id="gadeLabelId" style="width:220px" onChange="hideResults1();getGradeList();" style="width:60px;">
								                                <option value="">Select</option>
								                                <?php 
									                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
									                                echo HtmlFunctions::getInstance()->getGradingLabels();?>
							                                </select>
						                                </td>
						                                <td valign="top"  nowrap colspan="1" align="left" class="contenttab_internal_rows">
							                                <strong>Rounding</strong>
						                                </td>
                                                        <td valign="top" nowrap class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
						                                <td valign="top" class="" nowrap colspan="10">
                                                          <table align="left" border="0" cellpadding="0px" cellspacing="0px"> 
                                                            <tr>
                                                              <td valign="top" nowrap class="contenttab_internal_rows">
							                                        <select name="gradingFormula"  class="htmlElement" style="width:180px" id="gradingFormula"  onChange="hideResults();"  style="width:80px;">
								                                        <option value="">Select</option>
								                                        <option value="ceil">Round Up</option>
								                                        <option value="floor">Round Down</option>
								                                        <option value="round">Round Off</option>
								                                        <option value="round2">Round 2 Decimals</option>
							                                        </select>
						                                        </td>
												             </tr>
                                                        </table>
                                                 </td> 
                                               </tr>       
                                               <tr>
                                                 <td valign="top" class="" nowrap colspan="4">  
                                                    <table width="10%" border="0" cellspacing="5px" cellpadding="2px" id='msgRow' style='display:none;'>
                                                       <tr>
                                                          <td width="40%" class="contenttab_internal_rows"><span style='background-color:#4C6D9D;padding-left:40px'>&nbsp;</span></td>
                                                          <td width="60%" class="" >
                                                            <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: #4C6D9D;"> 
                                                             This color represents non editable fields. 
                                                             </span>
                                                           </td>
                                                       </td> 
                                                    </table>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr id='gradeRow' style='display:none;'>
                                                            <td colspan='1' class=''>
                                                                <div id = 'gradeDiv'></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                 </td>
                                                 <td align="left"  nowrap  style="padding-left:15px" class="" valign="bottom" colspan="10">
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
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
											<td colspan="1" class="content_title">Apply Grade :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/apply_grades.gif" onClick="saveGrades()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>


						<!-- form table ends -->
<?php 
//$History: scListApplyGrades.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 11/02/09   Time: 12:25p
//Updated in $/Leap/Source/Templates/ScStudent
//resolved issue 1916
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/04/09    Time: 1:59p
//Updated in $/Leap/Source/Templates/ScStudent
//added code to make histogram to show all marks even if no student has
//scored that marks.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/20/09    Time: 5:29p
//Created in $/Leap/Source/Templates/ScStudent
//file added for grading-advanced.
//



?>
