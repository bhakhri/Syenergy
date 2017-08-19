<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR MOVE/COPY TIME TABLE 
//
//
// Author :Jaineesh 
// Created on : (27.10.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


require_once(BL_PATH.'/HtmlFunctions.inc.php');

$timeTableLabelDataString=HtmlFunctions::getInstance()->getTimeTableLabelData();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top">Time Table&nbsp;&raquo;&nbsp;Move/Copy Time Table</td>
					<td valign="top" align="right"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="750px">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" height="730px">
							<tr>
								<td class="contenttab_border" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title">Move/Copy Time Table Detail : </td>
											<td class="content_title"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top">
								 <!--Allocation Tab Starts-->
								<div id="dhtmlgoodies_tabView1">
								<div class="dhtmlgoodies_aTab" style="overflow:auto;">
								<form name="searchForm" action="" method="post" onsubmit="return false;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="contenttab_internal_rows" ><nobr><b>Move/Copy for Time Table</b></nobr></td>
											<td class="padding" width="1%">:</td>
											<td class="padding" width="10%">
												<select size="1" class="inputbox" name="labelId" id="labelId" >
												<option value="">Select</option>
												<?php
												echo $timeTableLabelDataString;
												?>
												</select>
											</td>
											<td class="contenttab_internal_rows" ><nobr><b>Adjustment Type</b></nobr></td>
											<td class="padding" width="1%">:</td>
											<td colspan="7">
												<select size="1" class="inputbox" name="typeId" id="typeId" >
												<option value="">Select</option>
												<?php
												require_once(BL_PATH.'/HtmlFunctions.inc.php');
												echo HtmlFunctions::getInstance()->getTimeTableAdjustmentType('','Copy,Move');
												?>
												</select>
											</td>
										</tr>

										<tr>
											<td class="contenttab_internal_rows" width="7%"><b>From Date</td>
											<td class="padding" width="1%">:</td>
											<td class="padding"  width="15%" ><nobr>
											<?php
											require_once(BL_PATH.'/HtmlFunctions.inc.php');
											echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'),1);
											?>&nbsp;&nbsp;&nbsp;&nbsp;<span class="contenttab_internal_rows">To Date&nbsp;&nbsp;</span>:
											<?php
											require_once(BL_PATH.'/HtmlFunctions.inc.php');
											echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'),2); 
											?></nobr>
											</td>
										</tr>
										<tr> 
											<td class="contenttab_internal_rows"><b>Class</b></td>
											<td class="padding">:</td>
											<td class="padding"><nobr> 
												<select name="classId" id="classId" class="inputbox" onChange="getSubject(this.value,document.getElementById('fromDate').value);">
												<option value="">ALL</option>
												</select>&nbsp;&nbsp;
											</td>
											<td class="contenttab_internal_rows"><b>Subject</b></td>
											<td class="padding">:</td>
											<td class="padding"><nobr> 
												<select name="subjectId" id="subjectId" class="inputbox" onChange="getGroups(document.getElementById('classId').value,this.value,document.getElementById('fromDate').value);">
												<option value="">ALL</option>
												</select>&nbsp;&nbsp;
											</td>
										</tr>
										<tr>
											<td class="contenttab_internal_rows"><b>Group</b></td>
											<td class="padding">:</td>
											<td class="padding"><nobr> 
												<select name="groupId" id="groupId" class="inputbox" onChange="getemployee(document.getElementById('classId').value,this.value,document.getElementById('fromDate').value);">
												<option value="">ALL</option>
												</select>&nbsp;&nbsp;
											</td>
											<td class="contenttab_internal_rows"><b>Employee</b></td>
											<td class="padding">:</td>
											<td class="padding"><nobr> 
												<select name="employeeId" id="employeeId" class="inputbox">
												<option value="">ALL</option>
												</select>&nbsp;&nbsp;
											</td>
											<td class="contenttab_internal_rows" width="30%" >
												<input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onclick="getTimeTableData();return false;">
											</td>
										</tr>

										<tr><td colspan="12" height="5px"></td></tr>
										<tr><td colspan="12">
											<div id="placeHolderDiv" style="height:250px;overflow:auto">
											<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
											<td valign="top" >
											<div id="empDiv1"></div>
											</td>
											<!--<td valign="top" width="50%">
											<div id="empDiv2"></div>
											</td>-->
											</tr>
											</table>
											</div>
											</td>
										</tr> 
										<tr>
											<td colspan="12">
											<div id="resultDiv" style="width:100%;height:180px;overflow:auto;display:none;"></div>
											</td>
										</tr>
										<tr id="saveTr1" style="display:none" >
											<td colspan="12" align="center">
											<input type="image" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="validateData();return false;">&nbsp;
											<input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="cleanData();return false;">
											</td>
										</tr>
									</table>
								</form>
							</div>
<!--Allocation Tab Ends-->
<!--De-Allocation Tab Starts-->
					<div class="dhtmlgoodies_aTab" style="overflow:auto">
						<form name="searchForm2" action="" method="post" onsubmit="return false;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td class="contenttab_internal_rows" width="6%"><nobr><b>Time Table</b></nobr></td>
									<td class="padding" width="1%">:</td>
									<td class="padding" width="10%" style="padding-right:15px">
										<select size="1" class="inputbox" name="labelId2" id="labelId2" onBlur="getAdjustedTeachersForThisTimeTable(this.value);">
										<option value="">Select</option>
										<?php
										echo $timeTableLabelDataString;
										?>
										</select>
									</td>
									<td class="contenttab_internal_rows" width="6%"><b>Teacher</b></td>
									<td class="padding" width="1%">:</td>
									<td class="padding" width="10%" style="padding-right:15px">
										<select size="1" class="inputbox" name="teacherId" id="teacherId" onChange="cleanUpData(1);">
										<option value="">Select</option>
										</select>
									</td>
									<td class="contenttab_internal_rows"><b>From</b></td>
									<td class="padding">:</td>
									<td class="padding">
										<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->datePicker('fromDate2',date('Y-m-d'),3);
										?>
									</td>
									<td class="contenttab_internal_rows"><b>To</b></td>
									<td class="padding">:</td>
									<td class="padding">
										<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->datePicker('toDate2',date('Y-m-d'),4);
										?>
									</td>
									<td>
										<input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" style="margin-bottom: -5px;" onclick="getAdjustedTimeTableData();return false;">
									</td>
								</tr>
								<tr>
									<td colspan="13">
									<div id="cancelResultDiv" style="width:99%;height:580px;overflow:auto;"></div>
									</td>
								</tr>
								<tr>
									<td id="cancelOptionTd" colspan="13" align="center" style="display:none" >
									<input type="image" src="<?php echo IMG_HTTP_PATH;?>/delete_big.gif" onclick="validateData2();return false;">&nbsp;
									<input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="cleanUpData(2);return false;">
									</td>
								</tr>
							</table>
						</form>  
					</div>
				</div>
		</td>
	</tr>
</table>
	<script type="text/javascript">
	initTabs('dhtmlgoodies_tabView1',Array('Adjust Time Table','Delete Adjustment'),0,985,650,Array(false,false));
	</script>   
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>

<?php floatingDiv_Start('conflictsDivId','Conflicts','',' '); ?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<div id='conflictMessageDiv' style="width:800px;height:300px;overflow:auto;"></div>
</table>
<?php floatingDiv_End(); ?>
<?php
// $History: moveTimeTableContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/13/09   Time: 6:25p
//Updated in $/LeapCC/Templates/TimeTable
//Modification in code for move/copy timetable
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/05/09   Time: 5:18p
//Updated in $/LeapCC/Templates/TimeTable
//replace and with to date
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/04/09   Time: 4:28p
//Updated in $/LeapCC/Templates/TimeTable
//give link move/copy teacher time table and add new field adjustment
//type in time_table_adjustment table
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/02/09   Time: 10:33a
//Created in $/LeapCC/Templates/TimeTable
//new file for move/copy time table
//
?>