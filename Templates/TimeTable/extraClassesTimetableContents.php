<?php
//-------------------------------------------------------
// Purpose: to design add student.
//
// Author : Ajinder Singh
// Created on : (11-Nov-2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                   <!--<a href="#" onclick="getHelpImageDownLoad('time-table-making-flow.jpg','Create Time Table'); return false;" name="">Help</a>-->
				    <a href="#" onclick="getHelpImageDownLoad('time-table-making-flow.jpg'); return false;" name="">Help</a>

                 </td>
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
																<table border="0" cellspacing="0" cellpadding="0" >
																	<tr><td height="5"></td></tr>
																	<tr>
																		<td class="contenttab_internal_rows" align="left" ><b>Period Slot: </b></td>
																		<td align="left" class="padding" >
																			<select name="periodSlotId" id="periodSlotId" class="inputbox1" onblur='cleanUpTable();hideAddRow();'><option value="">Select</option>
																			<?php
																			require_once(BL_PATH.'/HtmlFunctions.inc.php');
																			echo HtmlFunctions::getInstance()->getPeriodSlot();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows" align="left">
																			<nobr><b>Time Table: </b></nobr>
																		</td>
																		<td class="padding">
																			<select size="1" name="timeTableLabelId" id="timeTableLabelId" class="inputbox1" onBlur="hideAddRow();cleanUpTable();">
																			<option value="">Select</option>
																			<?php
																				require_once(BL_PATH.'/HtmlFunctions.inc.php');
																				echo HtmlFunctions::getInstance()->getTimeTableLabelData();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows">
																			<nobr><b>Class: </b></nobr>
																		</td>
																		<td class="padding">
																			<select size="1" name="studentClass" id="studentClass" class="inputbox1" style="width:300px;" onfocus="getTimeTableClasses();" onblur='hideAddRow();cleanUpTable();'>
																			<option value="">Select</option>
																			<?php
																				//require_once(BL_PATH.'/HtmlFunctions.inc.php');
																				//echo HtmlFunctions::getInstance()->getClassData();
																			?>
																			</select>
																		</td>
																		<td class="contenttab_internal_rows">
																			<nobr><b>Date: </b></nobr>
																		</td>
																		<td class="contenttab_internal_rows" align="left">
																		<?php  
																			require_once(BL_PATH.'/HtmlFunctions.inc.php');
																			echo HtmlFunctions::getInstance()->datePicker3('toDate',date('Y-m-d')); 
																		?>
																		</td>
																		<td  align="left" style="padding-right:5px" colspan="6">
																			<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif" onclick="return validatetTimetableForm('validate');" />
																		</td>
																	</tr>
																	<tr>
																		<td height="5px">
																			<select name='teacherHidden' id='teacherHidden' style='display:none;'>
																				<option value="">Select</option>
																				<?php
																					require_once(BL_PATH.'/HtmlFunctions.inc.php');
																					echo HtmlFunctions::getInstance()->getTeacher();
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
																			<td width="12%" class="searchhead_text"><b>Subject</b></td>
																			<td width="12%" class="searchhead_text"><b>Group</b></td>
																			<td width="12%" class="searchhead_text"><b>Teacher</b></td>
																			<td width="12%" class="searchhead_text"><b>Room</b></td>
																			<td width="7%" class="searchhead_text"><b>Periods</b></td>
																			
<!-- 																			<td width="7%" class="searchhead_text"><b>Tue</b></td>
																			<td width="7%" class="searchhead_text"><b>Wed</b></td>
																			<td width="7%" class="searchhead_text"><b>Thu</b></td>
																			<td width="7%" class="searchhead_text"><b>Fri</b></td>
																			<td width="7%" class="searchhead_text"><b>Sat</b></td>
																			<td width="7%" class="searchhead_text"><b>Sun</b></td>
 -->																			<td  class="searchhead_text"><b>Remove</b></td>
																		</tr>
																	</tbody>
																</table>
																<input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
																<div id='addRowDiv' style='display:none;'>
																<h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></h3>
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td  align="left" style="padding-right:5px" colspan="6">
																			<input type="hidden" name="listSubject" value="1"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_conflicts.gif" onclick="return validatetTimetableForm('conflicts');return false;" />
																		</td>
																		<td  align="right" colspan="6">
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
<table border="0" cellspacing="0" cellpadding="0" class="border">
<div id='conflictMessage' style="width:800px;height:300px;overflow:auto;"></div>
</table>
<?php floatingDiv_End(); ?>
<?php 
// $History: extraClassesTimetableContents.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/11/09   Time: 11:56a
//Created in $/LeapCC/Templates/TimeTable
//file added for extra classes
//

?>