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
											<td class="content_title">Time Table Detail:</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top" >
									<form action="" method="POST" name="timeTableForm" id="timeTableForm">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
											<tr>
												<td valign="top" class="content">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="contenttab_border1" align='center'>
																<table border="0" cellspacing="2" cellpadding="0" >
																	<tr>
																		<td class="contenttab_internal_rows" align="left" ><b>Period Slot </b></td>
																		<td align="left" class="contenttab_internal_rows" >&nbsp;<b>:</b>
																			<select name="periodSlotId" id="periodSlotId" class="inputbox1" onblur='cleanUpTable();hideAddRow();' style='width:280px;' onchange="getTimeTablePeriodSlotPopulate();"><option value="">Select</option>
																			<?php
																			require_once(BL_PATH.'/HtmlFunctions.inc.php');
																			echo HtmlFunctions::getInstance()->getPeriodSlot();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows" align="left">
																			<nobr><b>&nbsp;&nbsp;&nbsp;Time Table</b></nobr>
																		</td>
																		<td class="contenttab_internal_rows">&nbsp;<b>:</b>
		<select size="1" name="timeTableLabelId" id="timeTableLabelId" class="inputbox1" onBlur="hideAddRow();cleanUpTable();" style='width:150px;'>
																			<option value="">Select</option>
																			<?php
																				require_once(BL_PATH.'/HtmlFunctions.inc.php');
																				echo HtmlFunctions::getInstance()->getTimeTableLabelData(''," AND timeTableType = ".WEEKLY_TIMETABLE);
																			?>
																			</select>
																		</td>
																		<td valign='top' colspan='3' class=''>

																		</td>
																	</tr>
																	<tr>
																		<td class="contenttab_internal_rows">
																			<nobr><b>Class </b></nobr>
																		</td>
																		<td class="contenttab_internal_rows">&nbsp;<b>:</b>
																			<select size="1" name="studentClass" id="studentClass" onfocus="getTimeTableClasses();" onblur='hideAddRow();cleanUpTable();' class="inputbox1" style='width:280px;'>
																			<option value="">Select</option>
																			<?php
																				//require_once(BL_PATH.'/HtmlFunctions.inc.php');
																				//echo HtmlFunctions::getInstance()->getClassData();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows">
																			<nobr><b>&nbsp;&nbsp;&nbsp;Day</b></nobr>
																		</td>
																		<td class="contenttab_internal_rows">&nbsp;<b>:</b>
																			<select size="1" name="day" id="day" onBlur="hideAddRow();cleanUpTable();" class="inputbox1" style='width:150px;'>

																			<?php
																				require_once(BL_PATH.'/HtmlFunctions.inc.php');
																				echo HtmlFunctions::getInstance()->getDaysList();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows">
																			<nobr><b>&nbsp;&nbsp;&nbsp;Room </b></nobr>
																		</td>
																		<td class="contenttab_internal_rows">&nbsp;<b>:</b>
																			<select size="1" name="roomId" id="roomId" onBlur="hideAddRow();cleanUpTable();" class="inputbox1">
																				<?php
																					require_once(BL_PATH.'/HtmlFunctions.inc.php');
																					echo HtmlFunctions::getInstance()->getInstituteRoomData2(" AND r.roomId IN(select roomId from room_institute where instituteId = $instituteId)");
																				?>
																			</select>
																		</td>
																		<td  align="left">
																			&nbsp;&nbsp;&nbsp;<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/showtimetable.gif" onclick="return validatetTimetableForm('validate');" />
																		</td>

<tr id="periodRow">
<td valign='top' colspan='15' class="contenttab_internal_rows"  width="80%">

<div id="periodRowDiv" style="overflow:auto;width:1000px;">

</div>
</td>
</tr>



																	<tr>
																		<td height="5px">
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
																					echo HtmlFunctions::getInstance()->getInstituteRoomData2(" AND r.roomId IN(select roomId from room_institute where instituteId = $instituteId)");
																				?>
																			</select>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td class="contenttab_row" valign="top" >
																<table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
																	<tbody id="anyidBody">
																		<tr class="rowheading">
																			<td width="2%" class="searchhead_text"><b>Sr.</b></td>
																			<td width="15%" class="searchhead_text"><b>Subject</b></td>
																			<td width="15%" class="searchhead_text"><b>Group</b></td>
																			<td width="30%" class="searchhead_text"><b>Teacher</b></td>
																			<!-- <td width="15%" class="searchhead_text"><b>Room</b></td> -->
																			<td width="20%" class="searchhead_text"><b>Periods</b></td>
																			<td  class="searchhead_text"><b>Remove</b></td>
																		</tr>
																	</tbody>
																</table>
																<input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
																<div id='addRowDiv' style='display:none;'>
																<h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></h3>
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td  align="left" style="padding-right:5px">
																			<input type="hidden" name="listSubject" value="1"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_conflicts.gif" onclick="return validatetTimetableForm('conflicts');return false;" />
																		</td>
																		<td  align="right">
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
<?php floatingDiv_Start('Conflicts','Conflicts'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<div id='conflictMessage' style="width:600px;height:300px;overflow:auto;"></div>
</table>
<?php floatingDiv_End(); ?>
<?php
// $History: timetableClassWiseDayWiseRoomWiseContents.php $
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 1/20/10    Time: 6:10p
//Updated in $/LeapCC/Templates/TimeTable
//fixed minor issue of filling class drop-down on select
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/TimeTable
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 11/12/09   Time: 5:34p
//Updated in $/LeapCC/Templates/TimeTable
//changed image to show time table.gif
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/09/09   Time: 12:28p
//Updated in $/LeapCC/Templates/TimeTable
//Modified in manage table table according to HOD role
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/02/09   Time: 12:29p
//Updated in $/LeapCC/Templates/TimeTable
//files changes to fix bug no. 1909
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/12/09   Time: 10:35a
//Updated in $/LeapCC/Templates/TimeTable
//done changes to fix bugs:
//0001740, 0001738, 0001737, 0001736, 0001735, 0001728
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/06/09   Time: 11:09a
//Updated in $/LeapCC/Templates/TimeTable
//applied changes for multi-slot time table.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/03/09   Time: 11:26a
//Created in $/LeapCC/Templates/TimeTable
//file added for class wise day wise room wise time table.
//
//



?>
