<?php
//-------------------------------------------------------
// Purpose: to design add student.
//
// Author : Ajinder Singh
// Created on : (30-09-2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $sessionHandler;
$instituteId = $sessionHandler->getSessionVariable('InstituteId');

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td class="contenttab_border" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title" width="80%">Timetable Detail:</td>
                                  <td style="padding-right:10px" align="right" class="content_title">
												<a href="#" onclick="getHelpImageDownLoad('time-table-making-flow.jpg','CreateTimeTable'); return false;" name="">Help</a>
												</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top" >
									<form action="" method="POST" name="timeTableForm" id="timeTableForm" onsubmit="return false;">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
											<tr>
												<td valign="top" class="content" align="center">
													<table width="80%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="contenttab_border1" align='center'>
																<table border="0" cellspacing="0" cellpadding="0" width="80%">
																	<tr>
																		<td class="contenttab_internal_rows" align="right"><b>Period Slot: </b></td>
																		<td align="left" class="padding">
<select style="width:280px"  name="periodSlotId" id="periodSlotId" class="inputbox1" onchange='cleanUpTable();hideAddRow();getTimeTablePeriodSlotPopulate();'>
																			<?php
																			require_once(BL_PATH.'/HtmlFunctions.inc.php');
																			echo HtmlFunctions::getInstance()->getPeriodSlot();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows" align="right">
																			<nobr><b>Time Table: </b></nobr>
																		</td>
																		<td class="padding" align="left" colspan="4">
				<select size="1" style="width:170px" name="timeTableLabelId" id="timeTableLabelId" class="inputbox1" onBlur="hideAddRow();cleanUpTable();">
																			<option value="">Select</option>
									<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->getTimeTableLabelData(''," AND timeTableType = ".DAILY_TIMETABLE);
									?>
																			</select>
																		</td>
																		<td valign='top' colspan='1' class=''></td>
																	</tr>
																	<tr>
																		<td class="contenttab_internal_rows" align="right">
																			<nobr><b>Class: </b></nobr>
																		</td>
																		<td class="padding" align="left">
																			<select size="1" name="studentClass" id="studentClass" class="inputbox1" style="width:280px;" onfocus="getTimeTableClasses();" onblur='hideAddRow();cleanUpTable();'>
																			<option value="">Select</option>
																			<?php
																				//require_once(BL_PATH.'/HtmlFunctions.inc.php');
																				//echo HtmlFunctions::getInstance()->getClassData();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows" align="right">
																			<nobr><b>Date: </b></nobr>
																		</td>
																		<td class="padding" align="left">
																		<?php
																		require_once(BL_PATH.'/HtmlFunctions.inc.php');
																		echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
																		?>
																		</td>
																		<td  align="left" style="padding-right:5px" colspan="6" nowrap>
																			<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/showtimetable.gif" onclick="return validatetTimetableForm('validate');" />
																			<!--<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_adjustments.gif" onclick="return showAdjustments();" />-->
                                                           <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_subject_list.gif" onclick="showClassSubjects();" />
																		</td>
																	</tr>
																	
																	<tr id="periodRow">
																		<td valign='top' colspan='15' class="contenttab_internal_rows"  width="80%">
																			<div id="periodRowDiv" style="overflow:auto;width:1000px;">
																			
																				
																			</div>
																		</td>
																	</tr>
																	<tr>
																		<td height="5px" colspan="5">
																			<select name='teacherHidden' id='teacherHidden' style='display:none;'>
																				<option value="">Select</option>
																				<?php
																					require_once(BL_PATH.'/HtmlFunctions.inc.php');
																					echo HtmlFunctions::getInstance()->getTeachersHighlighted();
																				?>
																			</select>
																			<select name='subjectHidden' id='subjectHidden' style='display:none;'>
																				<option value="">Select</option>
																			</select>
																			<select name='groupHidden' id='groupHidden' style='display:none;'>
																				<option value="">Select</option>
																			</select>
																			<select name='roomHidden' id='roomHidden' style='display:none;'>
																				<option value="">Select</option>
																				<?php
																					require_once(BL_PATH.'/HtmlFunctions.inc.php');
																					echo HtmlFunctions::getInstance()->getInstituteRoomData2(""," AND r.roomId IN(select roomId from room_institute where instituteId = $instituteId)");
																				?>
																			</select>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td class="contenttab_row" valign="top" align="left">
																<table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
																	<tbody id="anyidBody">
																		<tr class="rowheading">
																			<td width="2%" class="searchhead_text"><b>Sr.</b></td>
																			<td width="12%" class="searchhead_text"><b>Subject</b></td>
																			<td width="12%" class="searchhead_text"><b>Group</b></td>
																			<td width="12%" class="searchhead_text"><b>Teacher</b></td>
																			<td width="12%" class="searchhead_text"><b>Room</b></td>
																			<td width="7%" class="searchhead_text"><b>Periods</b></td>
																			<td  class="searchhead_text"><b>Remove</b></td>
																		</tr>
																	</tbody>
																</table>
																<input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
																<div id='addRowDiv' style='display:none;'>
																<h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></h3>
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td  align="center" style="padding-right:5px" colspan="12">
																			<input type="hidden" name="listSubject" value="1"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_conflicts.gif" onclick="return validatetTimetableForm('conflicts');return false;" />
																			<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validatetTimetableForm('save');return false;" />
																		</td>
																	</tr>
																</table>
																</div>
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
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php floatingDiv_Start('Conflicts','Conflict Details','',' '); ?>
<div id='conflictMessage' style="width:800px;height:300px;overflow:auto;"></div>
<?php floatingDiv_End(); ?>
<?php floatingDiv_Start('Adjustments','Adjustment Details','',' '); ?>
<div id='adjustmentMessage' style="width:800px;height:300px;overflow:auto;"></div>
<?php floatingDiv_End(); ?>


<!--Start Class->Subjects Div-->
<?php floatingDiv_Start('ClassSubjectDiv','Subjects',4,' '); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 <tr><td height="5px" colspan="2"></td></tr>
 <tr>
    <td colspan="2">
     <div id="classSubjectResults" style="width:920px;height:300px;overflow:auto">
    </td>
  </tr>
 <tr><td height="5px" colspan="2"></td></tr>
</table>
<?php floatingDiv_End(); ?>
<!--End Class->Subjects Div-->


<?php
// $History: timetableAdvancedDayWiseContents.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/17/10    Time: 4:30p
//Created in $/LeapCC/Templates/TimeTable
//initial checkin
//
//*****************  Version 18  *****************
//User: Ajinder      Date: 4/09/10    Time: 4:14p
//Updated in $/LeapCC/Templates/TimeTable
//added check for fetching institute wise rooms
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 3/17/10    Time: 11:36a
//Updated in $/LeapCC/Templates/TimeTable
//Created Class->Subject display popup in "Create Time Table" module
//
//*****************  Version 16  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/TimeTable
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 12/14/09   Time: 5:33p
//Updated in $/LeapCC/Templates/TimeTable
//changed position of "Show Conflicts" AND "Save" button to center.
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 11/17/09   Time: 11:50a
//Updated in $/LeapCC/Templates/TimeTable
//improved formatting
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 11/12/09   Time: 5:34p
//Updated in $/LeapCC/Templates/TimeTable
//changed image to show time table.gif
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 11/12/09   Time: 1:11p
//Updated in $/LeapCC/Templates/TimeTable
//fixed "conflict" table not coming in IE
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 11/09/09   Time: 12:28p
//Updated in $/LeapCC/Templates/TimeTable
//Modified in manage table table according to HOD role
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/06/09   Time: 12:26p
//Updated in $/LeapCC/Templates/TimeTable
//help link format updated
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/06/09   Time: 11:52a
//Updated in $/LeapCC/Templates/TimeTable
//increased popup width
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/02/09   Time: 12:29p
//Updated in $/LeapCC/Templates/TimeTable
//files changes to fix bug no. 1909
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 10/26/09   Time: 11:38a
//Updated in $/LeapCC/Templates/TimeTable
//done changes for taking care of adjustment.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 10/12/09   Time: 10:35a
//Updated in $/LeapCC/Templates/TimeTable
//done changes to fix bugs:
//0001740, 0001738, 0001737, 0001736, 0001735, 0001728
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 10/06/09   Time: 11:09a
//Updated in $/LeapCC/Templates/TimeTable
//applied changes for multi-slot time table.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/01/09   Time: 10:57a
//Updated in $/LeapCC/Templates/TimeTable
//changed orientation
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/30/09    Time: 5:14p
//Created in $/LeapCC/Templates/TimeTable
//file added for class based time table
//



?>
