<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
		</td>	
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="contenttab_border" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title">Duty Leave Conflict Report : </td>
											<td class="content_title"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top" >
									<form name="conflictReportForm" id="conflictReportForm" method="post" action="" onsubmit="return false;" >
									<table border="0" cellpadding="2" cellspacing="2" width="100%">
										<tr>
											<td>
												<table align="left" border="0" cellpadding="5px" cellspacing="0"  >
													<tr>
														<td class="contenttab_internal_rows"><nobr><b>Time Table</b></nobr></td>
														<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
														<td class="contenttab_internal_rows"><nobr>
															<select size="1" class="selectField" name="labelId" id="labelId" style="width:220px;" onchange="getTimeTableClasses(this.value);getDutyEvent(this.value)">
															<option value="" >Select</option>
															<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();
															?>
															</select></nobr>
														</td>
														<td class="contenttab_internal_rows">
															<nobr><b>Class</b></nobr>
														</td>
														<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
														<td class="contenttab_internal_rows"><nobr>
															<select size="1" class="selectField" name="classId" id="classId" style="width:310px;" onChange="hideResults();getClassSubjects();">
															<option value="">Select</option>
															<?php
															//require_once(BL_PATH.'/HtmlFunctions.inc.php');
															//echo HtmlFunctions::getInstance()->getSelectedTimeTableClasses();
															?>
															</select></nobr>
														</td>

														<td class="contenttab_internal_rows">
															<nobr><strong>Event</strong></nobr>
														</td>
														<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
														<td class="padding"><nobr>
															<select name="eventId" id="eventId"  class="selectField"  onChange="hideResults();"  style="width:230px;" >
															<option value="-1">Select</option>
															</select></nobr>
														</td>
													</tr>
													<tr>
														<td class="contenttab_internal_rows">
															<nobr><strong>Subjects</strong></nobr>
														</td>
														<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
														<td class="contenttab_internal_rows"><nobr>
									<select name="subjectId" id="subjectId"  class="selectField"  onChange="hideResults();"  style="width:220px;" >
										<option value="-1">Select</option>
									</select></nobr>
														</td>
                                                        
														<td class="contenttab_internal_rows">
                                                            <nobr><strong>Show</strong></nobr>
                                                        </td>
														<td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
														<td class="contenttab_internal_rows"><nobr>
<input type="radio" name="showConflict" value="1" checked="checked" onclick="hideResults();" />Conflicted Data&nbsp;
<input type="radio" name="showConflict" value="0" onclick="hideResults();" />Non Conflicted Data&nbsp;
<input type="radio" name="showConflict" value="-1" onclick="hideResults();" />Both &nbsp;
<input type="checkbox" name="rejectedLeave" value="1" checked="checked"  onclick="hideResults();" style="display:none" />
														</td>  
                                                        <td class="contenttab_internal_rows" nowrap>
                                                           <nobr><strong>Leave For</strong></nobr>
                                                        </td>
                                                        <td class="contenttab_internal_rows" nowrap><b>&nbsp;:&nbsp;</b></td>
                                                        <td class="contenttab_internal_rows" nowrap>&nbsp;
                                                           <select name="leaveFor" id="leaveFor" class="selectField"  style="width:230px;" >
                                                               <option value="">All</option>
                                                               <option value="0">Rejected</option>
                                                               <option value="1">Approved</option>
                                                               <option value="2">Mark Absent</option>
                                                               <option value="3">Unresolved</option>
                                                           </select>  
                                                        </td>
                                                        
														<td style="display:none" colspan="2" class="contenttab_internal_rows" nowrap>
															<table align="left" border="0" cellpadding="0px" cellspacing="0" >
																<tr>
																	<!--<td class="contenttab_internal_rows"><nobr>
																		<input type="checkbox" name="isApprove" id="isApprove" ></td><td>Highlight rows where limit exceeds</nobr><br><nobr><b>allowed</b> Duty Leaves per subject</nobr>
																	</td>   --> 
																	
																</tr>
															</table>       
														</td>
													</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><strong>Include</strong></nobr></td>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
    <td class="contenttab_internal_rows" colspan="10"><nobr>
    <input type="radio" name="includeLimit" value="1" checked="checked"  onclick="hideResults();" />Don't Show Which Overshoot Duty Leave Limit&nbsp;&nbsp;  
    <input type="radio" name="includeLimit" value="2" onclick="hideResults();" />Show all Uploaded Duty Leaves&nbsp;&nbsp;
    <span style="display:none">
    <input type="radio" name="includeLimit" value="3" onclick="hideResults();" />Leaves Less Than or Equal to Duty Leave Limits &nbsp;&nbsp;
    </span>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows" valign="top"><nobr><strong>Roll No.</strong></nobr></td>
    <td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
    <td class="contenttab_internal_rows" colspan="6"  valign="top">
        <nobr><textarea style="width:630px" class="selectField" name="rollNo" id="rollNo" cols="30" rows="1" onkeyup="return ismaxlength(this);"></textarea></nobr><br>
        <span style="padding-left: 2px; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">
            * Use comma seperator( , ) for multipile Roll No. entered
       </span>
    </td>
    <td class="contenttab_internal_rows" style="padding-left:20px">
    <nobr>
        <input type="image" name="stu22" value="stu22" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateData(this.form);return false;" />
    </nobr>
    </td>
</tr>
</table>
</td>
</tr>
<tr>
											<td class="contenttab_internal_rows" align="left" valign="top">
												<fieldset>
													<b><u>Please Note:</u><br></b>
													<font color="red">1. This Conflict and Non Conflict Report is ONLY applicable for <u>Daily Attendance</u> and NOT for Bulk Attendance.</font><br/>
												   	<font color="red">2. The maximum number of Duty Leaves PER SUBJECT PER STUDENT cannot be more than: <b><u><?php echo $dutyLeaveLimit=$sessionHandler->getSessionVariable('DUTY_LEAVE_LIMIT'); ?></b></u> </font><br/>
												   	<font color="red">3. If in any case Duty Leaves more than the above mentioned scenario have to be granted, then this can be done ONLY by the Super admin and he would be alerted. </font><br>
												   	<font color="red">4. If a CONFLICTED Duty Leave is APPROVED then benefit will <b><u>NOT</u></b> be given in the Attendance. </font><br>
<font color="red">5. This Conflict and Non Conflict Report is ONLY <b>Applicable for those Days for which Attendance has been Taken.</b> </font><br>
<font color="red">6. <b>Sorting</b> is Done on the basis of <b>Roll No., Date, Period, Event.</b> </font><br>
<font color="blue">7. <b>Blue Color</b> indicates for <b>Duplicate Entry of Roll No., Date, Period and Subject but Event is Different.</b></font><br>
												</fieldset>
											</td>
										</tr>
										<tr> 
											<td class="contenttab_internal_rows" align="left" valign="top">
												<table width="100%" border="0px" cellpadding="0" cellspacing="0">
													<tr>
														<td class="contenttab_internal_rows" colspan="20" >
															<b><a href="" class="link" onClick="getShowDetail(); return false;" >
															<Label id='sampleFormat'>Click here for Details on Conflict and Non-Conflict Report</label></b></a>
														</td>
													</tr> 
												</table> 
											</td>
										</tr>
									</table>                      
									<div id="scrollDiv" style="overflow:auto; HEIGHT:500px; vertical-align:top;">  
										<div id='results'></div>
									</div>  
								</td>
							</tr>
						</table>  
					</form> 
				</td>
			</tr>
			<tr id="savePrintRowId" style="display:none;">
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
						<tr>
							<td class="content_title" valign="middle" align="center" width="20%">
								<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/save.gif" onClick="doDutyLeave();" >&nbsp;
								<span style="display:none" id="printSpanId">
								<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
								<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
								</span>  
							</td>  
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
</td>
</tr>
</table>


<!--Start Notice  Div-->
<?php floatingDiv_Start('divMessage','Duty Leave Limit Exceeding List','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll12" style="overflow:auto; width:550px; height:350px; vertical-align:top;">
                <div id="divLimitExceed" style="width:98%; vertical-align:top;"></div>

            </div>
        </td>
    </tr>
</table>
<?php floatingDiv_End(); ?>    

<?php floatingDiv_Start('showHelpMessage','Conflict Details','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll" style="overflow:auto; width:500px; height:225px; vertical-align:top;">
                <div id="divshowHelp" style="width:98%; vertical-align:top;">
					<table width="100%" border="0px" cellpadding="0" cellspacing="0">
						<tr>
							<td class="contenttab_internal_rows">
								<?php 
									global $FE;
									$conflictUrlInstruction = $FE . "/Templates/Conflict_NonConflictInstructions.php";
									include "$conflictUrlInstruction";
								?>
							</td>
						</tr> 
					</table>										
                </div>
            </div>
        </td>
    </tr>
</table>
<?php floatingDiv_End(); ?>    
    
