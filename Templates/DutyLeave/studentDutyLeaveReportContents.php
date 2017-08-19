<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form name="dutyLeaveReportForm" id="dutyLeaveReportForm" action="" method="post" onSubmit="return false;">
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

                                        <table width="70%" align="center" border="0" cellpadding="0px" cellspacing="0" >
										<tr>
											<td class="contenttab_internal_rows"><nobr><b>Reg. No./ Uni. No./ Roll No.</b></nobr></td>
											<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
											 <td class="padding"><nobr>
											 <input type="text" name="rollNo" id="rollNo" class="inputbox" style="width:246px;" onblur="getClassList();"></nobr>
											 </td>
											 <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;&nbsp;Class</b></nobr></td>
											<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
											 <td class="padding"><nobr>
											   <select name="classId" id="classId"  class="inputbox" style="width:250px;" >
												<option value="-1">Select</option>
											   </select></nobr>
											 </td>
										</tr>
										<tr>
											<td class="contenttab_internal_rows">
												<nobr><strong>Event</strong></nobr>
											</td>
											<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
											<td class="padding"><nobr>
											   <select name="eventId" id="eventId"  class="inputbox"  onChange="hideResults();" style="width:250px;" >
												<option value="-1">All</option>
													<?php
													   require_once(BL_PATH.'/HtmlFunctions.inc.php');
													   echo HtmlFunctions::getInstance()->getDutyLeaveEventData('-1');
													?>
											   </select></nobr>
											</td>
											<td class="contenttab_internal_rows">
												<nobr><strong>&nbsp;&nbsp;&nbsp;&nbsp;Status</strong></nobr>
											</td>
											<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
											<td class="padding"><nobr>
											   <select name="statusId" id="statusId"  class="inputbox"  onChange="hideResults();" style="width:250px;" >
												<option value="-1">All</option>
													<?php
													   require_once(BL_PATH.'/HtmlFunctions.inc.php');
													   echo HtmlFunctions::getInstance()->makeDutyLeaveSelect("-1");
													?>
											   </select></nobr>
											</td>

											 <td colspan="10" style="padding-left:15px;">
												<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateData(this.form);return false;" />
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
											<td colspan="1" class="content_title">Student Duty Leave Report :</td>
											<td class="content_title" align="right">
								  <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
								  <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                                </td>
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
											<td colspan="2" class="content_title" align="right">
              <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
              <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
								</td>
							</tr>
						</table>
				</td>
			</tr>
	 	</table>
	</td>
  </tr>
</table>
</form>