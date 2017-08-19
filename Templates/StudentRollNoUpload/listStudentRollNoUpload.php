<?php 
//-------------------------------------------------------
//  This File contains html code for Student Roll No. Upload
//
//
// Author :Jaineesh
// Created on : 26-Oct-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">

								<form method="POST" name="editForm"  action="<?php echo HTTP_LIB_PATH;?>/StudentRollNoUpload/exportStudentRollNoCSV.php" id="editForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetEdit">
										<table align="center" border="0" cellpadding="0" width="42%">
											<tr>
												<td colspan="3">
													<input type="radio" value="1" name="studentListRollNo" id="studentListRollNo">
													<strong>Student List &nbsp;</strong>
												</td>
											</tr>
											<tr>
												<td colspan="3">
													<input type="radio" value="2" name="studentListRollNo" checked="checked">
													<strong>Student List without Roll no. or University Roll No.</strong>
												</td>
												
											</tr>

											<tr>
												<td colspan="1" align="right" >
													<strong>Select Class :&nbsp;</strong>
												</td>
												<td class="padding" ><select size="1" class="selectfield" name="degree" id="degree" onchange="document.addForm.classId.value=this.value">
												<option value="">Select Class</option>
												<?php
													require_once(BL_PATH.'/HtmlFunctions.inc.php');
													echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);?>
												?>
												</select></td>
												<td align="right" colspan="2" valign="top">
													<input type="image" name="studentRollNoSubmit" value="studentRollNoSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif"/>
												</td>
											</tr>
										</table>
									</form>

									<form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/StudentRollNoUpload/fileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
										<table align="center" border="0" cellpadding="0" width="42%">
											<!--<tr>
												<td valign='top' colspan='2' class=''>
													<B>Notes: <br>1.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadStudentRollNoFormat.php?t=suf'>here</a> to download Student Roll No. Uploading Format. <br>2.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadStudentRollNoFormat.php?t=sui'>here</a> to download instructions.</B>
												</td>
											</tr>
											<tr>
												<td colspan="1" align="right" >
													<strong>Select Class :&nbsp;</strong>
												</td>
												<td class="padding"><select size="1" class="selectfield" name="degree" id="degree">
												<option value="">Select Class</option>
												<?php
													require_once(BL_PATH.'/HtmlFunctions.inc.php');
													echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);?>
												?>
												</select></td>
											</tr>-->
											<input type="hidden" id="classId" name="classId" value="" />
											<tr>
												<td colspan="1" align="right" valign="top" width="21%" >
													<nobr><strong>Select File :&nbsp;</strong><nobr>
												
												
<input type="file" id="studentRollNoUploadFile" name="studentRollNoUploadFile" class="inputbox1" <?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:1px solid #fff;"></iframe>
												</td>
												<td align="right" colspan="2" valign="top">
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload.gif"  />
												</td>
											</tr>
											<!--<tr>
												<td align="left" valign="top" colspan="3">&nbsp;&nbsp;
													<b>Notes : </b>&nbsp;&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadStudentRollNoFormat.php?t=sui'>here</a> to download instructions.
												</td>
											</tr>-->

										</table>

<table align="center" border="0" cellpadding="0" width="100%">
<tr id='showSubjectEmployeeList' > 
                                          <td class="contenttab_internal_rows" align="left" colspan="20">

                                              <table width="100%" border="0px" cellpadding="0" cellspacing="0">
<tr>
												<td class="contenttab_internal_rows" colspan="20">
													&nbsp;&nbsp;<b>Click </b><a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadStudentRollNoFormat.php?t=sui'>here</a> <b>to download instructions.</b><br>
												</td></tr>
<tr><td class="contenttab_internal_rows" colspan="20"><b><font color="red">* Note :- The data present here should match with the data in database otherwise error will occur during updation</font></b></td></tr>
                                                <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" >
                                                    <b><a href="" class="link" onClick="getShowDetail(); return false;" >
                                                       <Label id='idSubjects'>Expand Sample Format for .xls file and instructions</label></b></a>
                                                       <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                                  </td>
                                         
                                                 </tr> 
                                                 <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" id='showSubjectEmployeeList11'>
                                                    <nobr><br><span id='subjectTeacherInfo'>
<table border="1" cellpadding="0" cellspacing="0" width="100%">
                         <tr>
                           <td class="contenttab_internal_rows"><b>Sr.No.</b></td>
                           <td class="contenttab_internal_rows"><b>Roll No.&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>University Roll No.&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Student Name&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Father Name&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Date of Birth&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>Upload Roll No. Status&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">1</td>
                           <td class="contenttab_internal_rows">80011</td>
                           <td class="contenttab_internal_rows">B0800334</td>
                           <td class="contenttab_internal_rows">TARUN NEGI</td>
                           <td class="contenttab_internal_rows">ANIL NEGI</td>
                           <td class="contenttab_internal_rows">1988.07.13</td>
			<td class="contenttab_internal_rows">YES</td>
			 
                         </tr>
                         <tr>
                          <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">430011</td>
                           <td class="contenttab_internal_rows">B0800044</td>
                           <td class="contenttab_internal_rows">TANYA MAKKAR</td>
                           <td class="contenttab_internal_rows">TATA MAKKAR</td>
                           <td class="contenttab_internal_rows">1988.04.09</td>
			<td class="contenttab_internal_rows">YES</td>
                         </tr>
                         <tr>
                          <td class="contenttab_internal_rows">3</td>
                           <td class="contenttab_internal_rows">34311</td>
                           <td class="contenttab_internal_rows">B0800789</td>
                           <td class="contenttab_internal_rows">UPMA RANI</td>
                           <td class="contenttab_internal_rows">SUNIL KUMAR</td>
                           <td class="contenttab_internal_rows">1988.01.07</td>
			<td class="contenttab_internal_rows">YES</td>
                         </tr>
                        </table>
			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 <b><font color="red">1. Columns marks with * are compulsory</font></b><br/>
                    	 <b><font color="red">2. Columns must be in the same order as in above mentioned format</b><br/>
			 <b><font color="red">3. Not even a single column should be removed or added</font></b><br/>
			 <b><font color="red">4. The format of Date should be YYYY.MM.DD</font></b><br/>
			
		</span></nobr>
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
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Student Roll No. Incorrect Entries Report :</td>
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
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
//$History: listStudentRollNoUpload.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/26/10    Time: 10:40a
//Updated in $/LeapCC/Templates/StudentRollNoUpload
//fixed bug not showing upload button in IE browser
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/18/10    Time: 11:28a
//Updated in $/LeapCC/Templates/StudentRollNoUpload
//put new field university Roll No.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/24/09   Time: 4:51p
//Updated in $/LeapCC/Templates/StudentRollNoUpload
//fixed bug nos. 0002118, 0002117, 0002116, 0002115
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/04/09   Time: 12:08p
//Updated in $/LeapCC/Templates/StudentRollNoUpload
//modification in bread crumb
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/04/09   Time: 11:44a
//Created in $/LeapCC/Templates/StudentRollNoUpload
//new template file for student roll no. uploading
//
//
?>
