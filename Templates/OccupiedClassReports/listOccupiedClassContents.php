<?php 
//This file creates Html Form output for Occupied Class Report
//
// Author :Jaineesh
// Created on : 30.03.10
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
									<form name="classOccupiedForm" action="" method="post" onSubmit="return false;">
										<table width="100%" align="center" border="0" >
											<tr>
												<td style="valign:top" class="contenttab_internal_rows">
													<strong>Time Table Label </strong>&nbsp;
												</td>
												<td class="padding">:</td>
												<td>
													<select id="labelId" name="labelId" class="selectfield" onchange="clearDiv();">
														<option value="" selected="selected">Select</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();
														?>
													</select>
												</td>

												<td class="contenttab_internal_rows">
													<strong>Period Slot</strong>&nbsp;
												</td>
												<td class="padding" >:</td>
												<td>
													<select id="periodSlot" name="periodSlot" class="selectfield" onchange="getPeriods();clearDiv();">
														<option value="" selected="selected">Select</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getWithoutPeriodSlot();
														?>
													</select>
												</td>
												<td class="contenttab_internal_rows">
													<strong>Period</strong>&nbsp;
												</td>
												<td class="padding" >:</td>
												<td>
													<select id="periods" name="periods" class="selectfield" onchange="clearDiv();">
														<option value="">Select</option>
														<?php
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getPeriodSlot();
														?>
													</select>
												</td>
											 </tr>
											 <tr>
												<td class="contenttab_internal_rows">
													<strong>Show Report</strong>&nbsp;
												</td>
												<td class="padding" >:</td>
												<td>
													<select id="showReport" name="showReport" class="selectfield" onchange="clearDiv();">
														<option value="classwise" selected="selected">ClassWise</option>
														<option value="roomwise" selected="selected">RoomWise</option>
													</select>
												</td>
												<td class="contenttab_internal_rows">
													<strong>Show</strong>&nbsp;
												</td>
												<td class="padding" >:</td>
												<td>
													<select id="show" name="show" class="selectfield" onchange="clearDiv();">
														<option value="occupied" selected="selected">Occupied</option>
														<option value="free" selected="selected">Free</option>
													</select>
												</td>
												<td class="contenttab_internal_rows" >
													<B><span id='daySpan' style='display:none;'>Day</span> <span id='dateSpan' style='display:none;'>Date</span></B>&nbsp;
												</td>
												<td class="padding" >:</td>
												<td>
													<span id='makeDaySpan' style='display:none;'>
													<select id="day" name="day" style="vertical-align:middle" class="selectfield" onchange="clearDiv();">
														<!--<option value="" selected="selected"></option>-->
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getDaysList();
														?>
													</select>
													</span>
													<span id='makeDateSpan' style='display:none;'>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
														?>
													</span>
												</td>
												<td>
													<span style="padding-right:10px" >
													<input type="image" name="offenseReport" value="offenseReport" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getSelectedList();"/>
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
											<td colspan="1" class="content_title">Occupied/Free Class(s)/Room(s) Wise List :</td>
											<!--<td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printOffenseReport();" title="Print" />&nbsp;<input type="image" name="printOffenseSubmit" id='generateCSV' onClick="printCSV();return false;" value="printOffenseCSVSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>-->
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
											<!--
											<td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printOffenseReport();" title="Print" />&nbsp;<input type="image" name="printOffenseSubmit" id='generateCSV' onClick="printCSV();return false;" value="printOffenseCSVSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>-->
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
//$History: listOccupiedClassContents.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/17/10    Time: 5:29p
//Updated in $/LeapCC/Templates/OccupiedClassReports
//fixed bug nos.0003287, 0003286, 0003290
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/06/10    Time: 1:27p
//Updated in $/LeapCC/Templates/OccupiedClassReports
//make interface changes
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/06/10    Time: 12:22p
//Created in $/LeapCC/Templates/OccupiedClassReports
//templates to show occupied/free classes
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/23/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Offense
//fixed bug nos. 0002338, 0002341, 0002336, 0002337
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/02/09    Time: 5:36p
//Created in $/LeapCC/Templates/Offense
//copy from sc for offense report
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/16/09    Time: 11:24a
//Updated in $/Leap/Source/Templates/Offense
//modification in code to show list of student of all classes with
//offenses
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/15/09    Time: 4:48p
//Created in $/Leap/Source/Templates/Offense
//new template & print file to show offense against students
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/05/09    Time: 5:09p
//Updated in $/Leap/Source/Templates/CleaningHistory
//add new functions in html function, changes in templates hostel
//cleaning 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 2:36p
//Created in $/Leap/Source/Templates/CleaningHistory
//copy from cc, file containing design template & print template
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 4:34p
//Updated in $/LeapCC/Templates/CleaningHistory
//show some fields on list
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:29p
//Created in $/LeapCC/Templates/CleaningHistory
//
?>
