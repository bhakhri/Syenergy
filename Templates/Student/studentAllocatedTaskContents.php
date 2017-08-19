<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
$queryString =  $_SERVER['QUERY_STRING'];

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="title">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td valign="middle">
                <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>  
            </td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="430">
		<tr>
		 <td class="content" valign="top">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
			<td class="contenttab_border" height="20">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
				<tr>
					<td class="contenttab_row1"><span class="content_title">Allocated Task Detail:</span></td>
				</tr>
				</table>
			</td>
		 </tr>
		 <tr>
			<td class="contenttab_row" valign="top">
				<table width="100%" border="0" cellspacing="2" cellpadding="2">
					<tr>
						<td><div id="LeaveTypeResultDiv"></div></td>
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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('StudentTeacherActionDiv',''); ?>

<form name="StudentTeacher" id="StudentTeacher" action="<?php echo HTTP_LIB_PATH;?>/Student/assignmentFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">
<input type="hidden" name="assignmentDetailId" id="assignmentDetailId" value="" />
<input type="hidden" name="assignmentId" id="assignmentId" value="" />
<input type="hidden" name="attachmentFile" id="attachmentFile" value="" />
<input type="hidden" name="readStatus" id="readStatus" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td colspan="3">
			<table width="100%" border="0" cellspacing="3" cellpadding="0" id="replyMessage">
			<tr>
				<td width="16%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Assigned By</strong></nobr></td>
				<td  valign="middle" class="contenttab_internal_rows"><nobr><strong>:</strong>&nbsp;</nobr></td>
				<td width="94%"><div id='senderName'></div></td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Assigned On</strong></nobr></td>
				<td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr>&nbsp;</td>
				<td><div id='senderDate'></div></td>
			</tr>
            <tr>
				<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Due Date</strong></nobr></td>
				<td class="contenttab_internal_rows"><nobr><strong>:</strong>&nbsp;</nobr></td>
				<td><div id='dueDate'></div></td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Topic</strong></nobr></td>
				<td class="contenttab_internal_rows"><nobr><strong>:</strong>&nbsp;</nobr></td>
				<td><div id='assignmentTopic'></div></td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Description</strong></nobr></td>
				<td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong>&nbsp;</nobr></td>
				<td><div id="noticeSubject" style="overflow:auto; width:550px; height:100px" ><div id='assignmentDescription'></div></div></td>
			</tr>
			<tr>
				<td valign="middle">&nbsp;<strong>Attachment</strong></nobr></td>
				<td valign="middle"><nobr><strong>:</strong></nobr></td>
				<td valign="middle"><div id='editLogoPlace'></div></td>
			</tr>
			</table>
			<fieldset>
			<legend>Student Remarks</legend>
			<table width="100%" border="0" cellspacing="3" cellpadding="0" id="replyMessage">
            <tr>
             <td width="16%" class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Remark</strong><?php echo REQUIRED_FIELD;?></nobr></td>
             <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
             <td width="94%" class="padding" valign="top"><textarea id="messageText" name="messageText"  style="width:500px" class="inputbox" rows="5"/></textarea></td>
            </tr>
            <tr>
             <td width="7%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><strong>Assignment</strong></nobr></td>
             <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
             <td width="94%" class="padding"><div id="editLogoPlace1" style="display:inline"><input type="file" id="noticeAttachment" name="noticeAttachment" class="inputbox" tabindex="15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)</div>
             <div id="editLogoPlace2" class="cl"></div></td>
            </tr>
		</td>
	</tr>

	<tr>
		<td height="5px"><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe></td>
	</tr>
	<tr id="repliedRow" style="display:none">
		<td width="7%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><strong>Submitted On</strong></nobr></td>
		<td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
		<td width="94%" class="padding"><div id='repliedOn'></div></td>
		
	</tr>
	<!---// (update assignment)------------------------>
	<tr id="updateRecord" >
		<td width="7%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><strong>Update Assignment</strong></nobr></td>
        <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
		<td width="94%" class="padding">
			<div id="update"></div>
		</td>
	</tr>
	<!--//---------------------------------------------------->
	</table>
	</legend>
	<tr id="editRecord">
		<td colspan="3" align="center">
		<table border="0" cellspacing="0" cellpadding="0"  width="100%" align="center">
		<tr>
			<td align="center" style="padding-right:10px" colspan="3" width="100%">
				<input type="hidden" value="" name="hiddenDueDate" id='hiddenDueDate' />
				<input type="hidden" value="" name="hiddenSubmittedDate" id='hiddenSubmittedDate' />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
				<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('StudentTeacherActionDiv');{refreshMessageData();}return false;" />
			</td>
		</tr>
		</table>
		</td>                                                                           
     </tr>
	 <tr>
		<td height="5"></td>
	 </tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<!--End Add Div-->  
<?php
// $History: studentAllocatedTaskContents.php $
?>