<?php 
//-------------------------------------------------------
//  This File contains html code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 02-May-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Activities&nbsp;&raquo;&nbsp;Exam Activities&nbsp;&raquo;&nbsp;Upload External Marks</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
		<form name="marksNotEnteredForm" action="<?php echo HTTP_LIB_PATH;?>/Student/uploadFinalMarksFile.php" enctype="multipart/form-data" method="post" target = "uploadTargetAdd">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									<table align="center" border="0" cellpadding="0">
										<tr>
											<td valign="top" class="contenttab_internal_rows"><nobr><b>Time Table: </b></nobr></td>
											<td valign="top" ><select size="1" class="inputbox1" name="labelId" id="labelId" onBlur="getClassesForTransfer()">
											<option value="">Select</option>
											<?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
											?>
											</select></td>
											<td colspan="1" align="right" valign="top" >
												&nbsp;&nbsp;<strong>Degree :</strong> &nbsp;
											</td>
											<td valign="top" rowspan='1'>
												<select size="5" name="class1" id="class1" class="htmlElement2" style="width:300px;">
													<?php 
														require_once(BL_PATH.'/HtmlFunctions.inc.php');
														//echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['class']==''?$REQUEST_DATA['class'] : $REQUEST_DATA['class']);?>
												</select>
											</td>
											<td align="left" colspan="2" valign="top">
												<span style="padding-right:10px" >
												<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
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
											<td colspan="1" class="content_title">Marks Not Entered Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
									<tr class="">
										<td colspan="2" align="left" width="60%"><a id="generateCSV" href="javascript:downloadSample();"><input type="image" src="<?php echo IMG_HTTP_PATH;?>/download_sample_file.gif" /></a></td>
										<td colspan="1" align="right" width="25%">
										<input type='hidden' name='isFileUploaded' value='0' />
										<input type="file" class="htmlElement" name="uploadFile">
										</td>
										<td colspan="1" align="right">
										<input type="image" src="<?php echo IMG_HTTP_PATH;?>/upload_marks_file.gif" onClick="uploadSampleFile();"/></td></tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- form table ends -->
					</td>
				</tr>
			</table>
			<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:400px;height:500px;border:1px solid;display:none;"></iframe>
		</form>

		</table>
<?php 
//$History: listUploadExternalMarks.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/19/09    Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//changed breadcrumb to fix bug no. 0000149
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/02/09    Time: 6:41p
//Created in $/LeapCC/Templates/Student
//file uploaded for marks upload.
//


?>
