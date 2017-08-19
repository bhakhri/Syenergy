<?php 
//-------------------------------------------------------
//  This File contains html code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 18-Mar-2009
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
		<td valign="top" width='100%'>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content" width="100%">
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									<form name="marksNotEnteredForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td rowspan="3" valign="top" class="contenttab_internal_rows"><nobr><b>Time Table: </b></nobr></td>
												<td rowspan="3" valign="top" ><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getClassesForTransfer()">
												<option value="">Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select></td>
												<td colspan="1" rowspan="3" align="right" valign="top" >
													&nbsp;&nbsp;<strong>Degree :</strong> &nbsp;
												</td>
												<td valign="top" rowspan='3'>
													<div id="containerDiv">
                                                    <select multiple size="5" name="class1[]" id="class1" style="width:300px;overflow:none;" >
                                                        <?php 
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            //echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['class']==''?$REQUEST_DATA['class'] : $REQUEST_DATA['class']);?>
                                                    </select>
                                                    <?php
                                                        $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
                                                        if($isIE6==1){
                                                        ?>    
                                                         <br>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("class1","All","marksNotEnteredForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("class1","None","marksNotEnteredForm");'>None</a>
                                                         <?php    
                                                         }
                                                         ?>
                                                    </div>
                                                    <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF"    id="d1"></div>
                                                    <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d2" >
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                                     <tr>
                                                      <td id="d3" width="95%" valign="middle" style="padding-left:3px;"></td>
                                                      <td width="5%">
                                                       <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('class1','d1','containerDiv','d3');" />
                                                      </td>
                                                      </tr>
                                                    </table>
                                                    </div>
												</td>
												<td colspan="1" align="right" valign="top" >
													&nbsp;&nbsp;<strong>Rounding :</strong> &nbsp;
												</td>
												<td valign="top" rowspan='1'>
													<select name="rounding" class="htmlElement2">
														<option value="ceilTotal">Round Up on Grand Total</option>
														<option value="ceilTestType">Round Up on Test Types</option>
														<option value="roundTotal">Normal Round on Grand Total</option>
														<option value="roundTestType">Normal Round on Test Types</option>
														<option value="noRound">No Rounding</option>
													</select>
												</td>
												<td align="left" colspan="2" rowspan="1" valign="bottom">
												</td>
											</tr>
											<!--
                                            <tr>
												<td colspan="1" align="right" valign="top" >
													&nbsp;&nbsp;<strong>Errors :</strong> &nbsp;
												</td>
												<td valign="top" rowspan='1'>
													<select name="errors" class="htmlElement2">
														<option value="screen">Show on screen</option>
														<option value="docFile">Show in downloadable .doc file</option>
													</select>
												</td>
                                               
												<td align="left" colspan="2" rowspan="1" valign="top">
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/transfer_marks.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
                                            -->
                                            <tr>
                                                <td colspan="1" align="right" valign="top" >
                                                    &nbsp;&nbsp;<strong>Errors :</strong> &nbsp;
                                                </td>
                                                <td valign="top" rowspan='1'>
                                                        <input type="radio" name="errors" value="1" checked="checked"  />Show on screen<br/>
                                                        <input type="radio" name="errors" value="0"/>Show in downloadable .doc file
                                                </td>
                                               
                                                <td align="left" colspan="2" rowspan="1" valign="top">
                                                    <span style="padding-right:10px" >
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/transfer_marks.gif" onClick="return validateAddForm(this.form);return false;" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" align="right" valign="top" >
                                                    
                                                </td>
                                                <td valign="top" rowspan='1'>
                                                    <!--<input type="radio" name="errors" value="0"/>Show in downloadable .doc file-->
                                                </td>
                                            </tr>
										</table>
									</form>
								</td>
							</tr>
							<tr>
								<td valign='top' colspan='1' class=''>
									<div id="marksTransferMessage"></div>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign='top' colspan='1' class='contenttab_row1'>
									<table border='0' cellspacing='10' cellpadding='0' width='100%'>
										<tr>
											<td valign='top' colspan='1' class='contenttab_row1'>
												<u><b>Checklist for Marks Transfer:</b></u>
											</td>
										</tr>
										<tr>
											<td valign='top' colspan='1' class='lightGrey' width='100%'>
												<b>1. Check whether Internal Marks are entered or not.</b><br>
												&bull;&nbsp;&nbsp;<b>Go to</b> Setup -> Institute Setup -> <a href='<?php echo UI_HTTP_PATH;?>/assignSubjectToClass.php' target="_blank" class="redLink">Assign Subjects to Class</a><br>
												&bull;&nbsp;&nbsp;Select the class for which marks are to be transferred. [<a href='<?php echo IMG_HTTP_PATH;?>/Help/Assign Subjects to Class.png' target='_blank' class="redLink">see snapshot</a>]<br>
												&bull;&nbsp;&nbsp;Click on Show List button. [<a href='<?php echo IMG_HTTP_PATH;?>/Help/Assign Subjects to Class Click.png' target='_blank' class="redLink">see snapshot</a>]<br>
												&bull;&nbsp;&nbsp;List of subjects mapped to class will be populated.<br>
												&bull;&nbsp;&nbsp;Verify the Internal Marks are correctly filled or not. Internal Marks are the total marks out of which college will give marks to students.[<a href='<?php echo IMG_HTTP_PATH;?>/Help/Assign Subjects to Class Marks.png' target='_blank' class="redLink">see snapshot</a>]<br>
											</td>
										</tr>
										<tr>
											<td valign='top' colspan='1' class='lightGrey' width='100%'>
												<b>2. Check whether Test types are created or not.</b><br>
												&bull;&nbsp;&nbsp;<b>Go to</b> Setup -> Exam Masters -> <a href='<?php echo UI_HTTP_PATH;?>/listTestType.php' target="_blank" class="redLink">Test Type Master</a><br>
											</td>
										</tr>
										<tr>
											<td valign='top' colspan='1' class='lightGrey' width='100%'>
												<b>3. Check whether Attendance Marks Percentages & Slabs are entered or not.</b><br>
												&bull;&nbsp;&nbsp;<b>Go to</b> Setup -> Institute Setup -> <a href='<?php echo UI_HTTP_PATH;?>/listAttendanceMarksPercent.php' target="_blank" class="redLink">Attendance Marks Percent</a><br>
												&bull;&nbsp;&nbsp;Create attendance marks for percentages. [<a href='<?php echo IMG_HTTP_PATH;?>/Help/Attendance Marks Percent.png' target='_blank' class="redLink">see snapshot</a>]<br>
												&bull;&nbsp;&nbsp;<b>Go to</b> Setup -> Institute Setup -> <a href='<?php echo UI_HTTP_PATH;?>/listLecturePercent.php' target="_blank" class="redLink">Attendance Marks Slabs</a><br>
												&bull;&nbsp;&nbsp;Create Slabs for attendance marks. [<a href='<?php echo IMG_HTTP_PATH;?>/Help/Attendance Marks Slabs.png' target='_blank' class="redLink">see snapshot</a>]<br>
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
<?php //floatingDiv_Start('marksTransfer','Marks Transfer','',' '); ?>

<?php //floatingDiv_End(); ?>
<?php 
//$History: listTransferInternalMarks.php $
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 15/02/10   Time: 17:43
//Updated in $/LeapCC/Templates/Student
//Modified javascript and html coding for "New Multiple Selected
//Dropdowns" as these are not working in IE6.
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 29/12/09   Time: 18:42
//Updated in $/LeapCC/Templates/Student
//Corrected "Multiple dropdowns" look 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 18/12/09   Time: 17:42
//Updated in $/LeapCC/Templates/Student
//Made UI changes in transfer internal marks module
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/25/09   Time: 6:42p
//Updated in $/LeapCC/Templates/Student
//improved marks transfer page designing, done changes in final internal
//report as per requirement from sachin sir
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 11/24/09   Time: 4:06p
//Updated in $/LeapCC/Templates/Student
//done changes for help on marks transfer
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/20/09   Time: 4:15p
//Updated in $/LeapCC/Templates/Student
//done changes related to transfer marks - initial checkin
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/17/09   Time: 6:53p
//Updated in $/LeapCC/Templates/Student
//done changes for marks transfer
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/07/09    Time: 8:20p
//Updated in $/LeapCC/Templates/Student
//added code for rounding.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:55p
//Updated in $/LeapCC/Templates/Student
//done cosmetic changes
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:45p
//Created in $/LeapCC/Templates/Student
//file added for transfer of internal marks
//
//


?>
