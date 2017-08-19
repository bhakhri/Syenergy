<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
	<tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<!--<tr>
					<td valign="top" class="content"> -->
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" height="430">
									<tr>
										<td class="content" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr height="30">
													<td class="contenttab_border" height="20" style="border-right:0px;">
													<?php //require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
													</td>
													<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" align="right" onClick="displayWindow('StudentTeacherActionDiv',770,1550);blankValues();return false;" />&nbsp;</td>
												</tr>
												<tr>
													<td  valign="top" class="contenttab_row" colspan=2>
														<table width="100%" border="0" cellspacing="2" cellpadding="2">
															<tr>
																<td ><div id="LeaveTypeResultDiv"></div> </td>
															</tr>
														</table>
													</td>
												</tr>
												<tr><td height="5px;"></td></tr>
												 <tr>
													 <td align="right" colspan=2>
														<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;
														<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
					</tr>
			</table>
 <form action="" method="" name="searchForm2" onsubmit="document.searchForm2.searchbox.value=document.searchForm2.searchbox_h.value ;refreshMessageData();return false;">
<input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" />
 </form>
<!--Start Add/Edit Div-->
<?php floatingDiv_Start('StudentTeacherActionDiv',''); ?>

<form name="searchForm" id="searchForm" method="post" action="<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/assignmentFileUpload.php" enctype="multipart/form-data" style="display:inline">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td valign="top">
				<fieldset class="fieldset">
					<legend>Search Student</legend>
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select style="width:150px;" size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);groupPopulate(this.form.subject.value);washoutData();" >
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr></td>
                        <td width="6%" class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select style="width:150px;" size="1" class="selectfield" name="subject" id="subject" onchange="groupPopulate(this.value);washoutData();">
                        <option value="">Select Subject</option>
                        </select></nobr>
                      </td>
                        <td width="6%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select style="width:150px;" size="1" class="selectfield" name="group" id="group" onchange="washoutData();">
                        <option value="">Select Group</option>
                        </select></nobr>
                      </td>
					  <td align="left" class="contenttab_internal_rows" nowrap><b>Roll No  </b></td>
					  <td align="left"  class="padding">
						<nobr>:&nbsp;
						<input type="text" name="studentRollNo" id="studentRollNo" class="inputbox1" style="width:100px;" maxlength="14"></nobr>
					  </td>
					  <td style="padding-right:10px" colspan="4">
						<div id="showListDisplay">
						 <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
						</div>
					  </td>
					</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<fieldset class="fieldset">
					<legend>Enter Task</legend>
                    <input type="hidden" name="assignmentId" id="assignmentId">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Task Title </b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding">:</td>
							<td class="padding">
								<nobr>
                                <input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:348px" maxlength="250"></nobr>
							</td>
							<td class="contenttab_internal_rows"><nobr><b>Assigned On</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding">:</td>
							<td class="padding">
								<nobr>
								<?php
									require_once(BL_PATH.'/HtmlFunctions.inc.php');
									echo HtmlFunctions::getInstance()->datePicker('assignedDate','');?>
								</nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows" rowspan="7" valign="top"><nobr><b>Description </b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding" rowspan="7" valign="top">:</td>
							<td valign="top" rowspan="7">
								<nobr>&nbsp;&nbsp;<textarea name="elm1" id="elm1" rows="7" cols="41"></textarea></nobr>
							</td>
							<td class="contenttab_internal_rows" height="20"><nobr><b>Due Date</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding">:</td>
							<td class="padding">
								<nobr>
								<?php
									require_once(BL_PATH.'/HtmlFunctions.inc.php');
									echo HtmlFunctions::getInstance()->datePicker('submissionDate','');
								?>
								</nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Upload File</b></nobr></td>
							<td class="padding">:</td>
							<td class="padding"><nobr><input type="file" id="msgLogo" name="msgLogo" class="inputbox"></nobr></td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><span id="editLogoPlace"></span><span id="deleteLogoDiv"></span></td>
							<td class="contenttab_internal_rows"></td>
							<td class="padding">(Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)</td>
						</tr>
                        <tr>
                         <td class="contenttab_internal_rows"><nobr><b>Visible to Students</b></nobr></td>
                         <td class="padding">:</td>
                         <td class="contenttab_internal_rows">
                            <nobr>
                             <input type="radio" name="isVisible" id="isVisible1" value="1" checked="checked" /> Yes
                             <input type="radio" name="isVisible" id="isVisible2" value="0" /> No
                            </nobr>
                         </td>
                        </tr>
						<tr>
							<td height="5px"><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe><input type="hidden" name="students" id="students"></td>
						</tr>
						<tr id="showSubmit1">
							<td class="padding" colspan="3" height="20">&nbsp;</td>
						</tr>
						<tr id="showSubmit" style="display:none">
							<td colspan="3" align="center" height="20">
								<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateForm();return false;"/>
                                &nbsp;&nbsp;<input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"   onclick="javascript:hiddenFloatingDiv('StudentTeacherActionDiv');{refreshMessageData();}return false;" />
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="9">
             <div id="results" style="overflow:auto;height:250px;vertical-align:top;width:100%"></div>
            </td>
		</tr>
	</table>

</form>
<?php floatingDiv_End(); ?>


<!--Start Add/Edit Div-->
<?php floatingDiv_Start('StudentAssignmentActionDiv','Assignment Detail'); ?>
<form name="searchForm1" id="searchForm1" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td valign="top">
		<fieldset class="fieldset">
		<legend>Assignment Detail</legend>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
         <tr>
             <td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
             <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
             <td width="20%" class="contenttab_internal_rows" align="left"><nobr>
              <div style="display:inline" name="classId1" id="classId1"></div>
             </nobr></td>
            <td width="6%" class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Subject</b></nobr></td>
            <td width="20%" class="contenttab_internal_rows" align="left"><nobr>&nbsp;<b>:</b>
              <div style="display:inline" name="subject1" id="subject1"></div>
            </nobr>
           </td>
            <td width="6%" class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Group</b></nobr></td>
            <td width="20%" class="contenttab_internal_rows" align="left"><nobr>&nbsp;<b>:</b>
              <div style="display:inline" name="group1" id="group1"></div>
            </nobr>
          </td>
         </tr>
         <tr>
			<td class="contenttab_internal_rows"><nobr><b>Assigned On</b></nobr></td>
			<td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
			<td class="contenttab_internal_rows"><nobr>
            <div id="DateAssignedOn" style="display:inline"></div></nobr></td>
			<td class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Due Date</b></nobr></td>
			<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
            <div id="DateDueOn" style="display:inline"></div></nobr></td>
			<td class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Added On </b></nobr></td>
			<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
            <div id="DateAddedOn" style="display:inline"></div></nobr></td>
		</tr>
        <tr>
            <td class="contenttab_internal_rows"><nobr><b>Visble to Students</b></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
            <td class="contenttab_internal_rows" colspan="5"><nobr>
            <div id="isVisibleDiv" style="display:inline"></div></nobr></td>
        </tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>Topic</b></nobr></td>
			<td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
			<td class="contenttab_internal_rows" valign="top" colspan="5"><nobr>
            <div id="AssignmentTopic" style="width:670px;overflow:auto;height:40px;display:inline"></div></nobr></td>
		</tr>
		<tr>
			<td valign="top" height="5"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>Attachment</b></nobr></td>
			<td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
			<td class="contenttab_internal_rows" colspan="5"><nobr>
            <div id="editLogoPlaceDetail" style="display:inline"></div></nobr></td>
		</tr>
		<tr>
			<td valign="top" height="5"></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows" valign="top"><nobr><b>Description</b></nobr></td>
			<td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
			<td class="contenttab_internal_rows" valign="top" colspan="5"><nobr>
            	<div id="noticeSubject" style="overflow:auto; width:690px; height:70px;" >
            	<div id="AssignmentDescription" style="display:inline"></div></div>
            </td>
		</tr>
		<tr>
			<td valign="top" height="5"></td>
		</tr>
		</table>
		</fieldset>
		</td>
	</tr>

	<tr>
		<td valign="top">
		<fieldset class="fieldset">
		<legend>Student Status</legend>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td height="5px" colspan="8"><div style="OVERFLOW: auto; HEIGHT:200px; width:800px; TEXT-ALIGN: justify;"><div id="results12"></div></div></td></tr>
		</tr>
		</table>
		</fieldset>
		</td>


	<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<?php
// $History: allocateTaskContents.php $
?>
