<?php 
//This file creates Html Form output for attendance report
//
// Author :Jaineesh
// Created on : 28-04-2009
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
									<form name="HanldeComplaintForm" action="" method="post" onSubmit="return false;">
										<table width="100%" align="center" border="0" >
											<tr>
												<td align="left">
													<strong>Hostel :</strong>&nbsp;
													<select name="hostel" id="hostel" class="selectfield1" onblur="getHostelRoom();">
														<option value="">ALL</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getHostelName();
														?>
													</select>
												</td>
												<td align="left">
													<strong>Room :</strong>&nbsp;
													<select  class="selectfield1" id="room" name="room" onblur="getStudentHostelData();">
														<option value="">ALL</option>
													</select>
												</td>
												<td class="padding" align="left">
													<strong>Reported By :</strong> &nbsp;
													<select  class="selectfield1" id="reportedBy" name="reportedBy">
														<option value="" selected="selected">ALL</option>
														<?php
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getStudentHostelData();
														?>
													</select>
												</td>
												
												<td valign="center" align="right" ><b>Complaint Date : </b></td>
												<td valign="center" align="left" >
													<?php
													   require_once(BL_PATH.'/HtmlFunctions.inc.php');
													   echo HtmlFunctions::getInstance()->datePicker('startDate',date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-30, date('Y'))));
													?>
													&nbsp;&nbsp;<b>Between </b>
													<?php
														   require_once(BL_PATH.'/HtmlFunctions.inc.php');
														   echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
													?>
												</td>
												<td align="center">
													<span style="padding-right:10px" >
													<input type="image" name="handleComplaintSubmit" value="handleComplaintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
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
											<td colspan="1" class="content_title">Handle Complaints :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="handleComplaintsPrintSubmit" value="handleComplaintsPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
											
											<td colspan="2" class="content_title" align="right"><input type="image" name="handleComplaintsPrintSubmit" value="handleComplaintsPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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

<?php floatingDiv_Start('divHandleComplaint','Handle Complaint Detail'); ?>
<form name="HandleComplaintForm" action="" method="post">
<input type="hidden" id="complaintId">

<table border="0" cellspacing="0" cellpadding="0" class="border" width="320px">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td class="contenttab_internal_rows" width="10%"><nobr>&nbsp;<strong>Subject</strong></nobr></td>
		<td width="35%" class="padding">:&nbsp;<input type="text" id="subject" name="subject" class="inputBox" disabled="disabled"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows" width="10%"><nobr>&nbsp;<strong>Complaint Date</strong></nobr></td>
		<td width="40%" class="padding">:&nbsp;<input type="text" id="complaintOn" class="inputBox" disabled="disabled"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows" width="10%"><nobr>&nbsp;<strong>Reported By</strong></nobr></td>
		<td width="40%" class="padding">:&nbsp;<input type="text" id="reportedBy" class="inputBox" disabled="disabled"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows" width="10%"><nobr>&nbsp;<strong>Room Name</strong></nobr></td>
		<td width="40%" class="padding">:&nbsp;<input type="text" id="roomName" class="inputBox" disabled="disabled"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Status</strong></nobr></td>
		<td class="padding">:&nbsp;
		<select  class="selectfield1" id="complaintStatus" name="complaintStatus">
			<option value="1">Pending</option>
			<!--<option value="2">Escalate</option>-->
			<option value="3">Complete</option>
		</select>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows" valign="top" >
		<b><span><nobr>&nbsp;<strong>Completion Date</strong></nobr></span></b></td>
		<td class="padding">:&nbsp;
		<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('endDate','');
		?>
		</td>
        </td>
    </tr>
	<tr>
		<td class="contenttab_internal_rows" valign="top" >
		<b><span><nobr>&nbsp;<strong>Remarks</strong></nobr></span></b></td>
		<td class="padding">:&nbsp;
			<textarea id="remarks" name="remarks" cols="22" rows="3" class="inputbox" style="valign:top"></textarea>
		</td>
	</tr>
	<tr>
		<td height="5px" colspan="6"></td>
    </tr>
	<tr>
	<td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddHandleComplaint(this.form,'Add');return false;" />
		<input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('divHandleComplaint');return false;" />
    </td>
		
	</tr>
</table>
</form> 
<?php floatingDiv_End(); ?>

<?php 
//$History: listHandleComplaintsContents.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/ReportComplaints
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/15/09    Time: 5:53p
//Updated in $/LeapCC/Templates/ReportComplaints
//fixed bug nos. 0001499, 0001550
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/02/09    Time: 6:22p
//Updated in $/LeapCC/Templates/ReportComplaints
//fixed bugs nos.0000193,0000194,0000359
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Templates/ReportComplaints
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/ReportComplaints
//make the changes as per discussion with pushpender sir
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:18p
//Created in $/LeapCC/Templates/ReportComplaints
//
?>