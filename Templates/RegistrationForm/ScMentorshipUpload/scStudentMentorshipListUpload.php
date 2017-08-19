<?php 
//-------------------------------------------------------
//  This File contains html code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 17-Oct-2008
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
					<td valign="top" colspan="2"><?php require_once(TEMPLATES_PATH."/breadCrumb.php");?></td>
				</tr>
			</table>
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
									<form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ScMentorshipUpload/scStudentFileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td valign='top' colspan='2' class=''>
													<B>Notes: <br>1.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/RegistrationForm/downloadFormat.php?t=meui'>here</a> to download Mentorship Uploading Format. 
														<br>2.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/RegistrationForm/downloadFormat.php?t=meuf'>here</a> to download instructions.</B>
												</td>
											</tr>
											<tr height='10'>
												
											</tr>
											<tr>
												<td colspan="1" align="right" valign="top" >
													<strong>Select File :</strong> &nbsp;
												</td>
												<td valign="top" rowspan='1'>
													<input type="file" id="mentorshipUploadFile" name="mentorshipUploadFile" class="inputbox1" <?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:1px;height:1px;border:0px solid #FFF;"></iframe>
												</td>
												<td align="left" colspan="2" valign="top">
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload_mentorship_file.gif"  />
												</td>
											</tr>
										</table>

<table align="center" border="0" cellpadding="0" width="100%">
<tr id='showSubjectEmployeeList' > 
                                          <td class="contenttab_internal_rows" align="left" colspan="20">

                                              <table width="100%" border="0px" cellpadding="0" cellspacing="0">
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
                          <td class="contenttab_internal_rows">1</td>
                          <td class="contenttab_internal_rows">B0800283</td>
                           <td class="contenttab_internal_rows">AMAN DHABA</td>
                           
                           
                         
                         </tr>
                         <tr>
                          <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">B0806555</td>                          
                           <td class="contenttab_internal_rows">ARTI DHAMA</td>
                          
                          
                         </tr>
                         <tr>
                          <td class="contenttab_internal_rows">3</td>
                          <td class="contenttab_internal_rows">B0455459</td>                          
                           <td class="contenttab_internal_rows">RAJ MALHOTRA</td>
                           
                          
                         </tr>
                       
                        </table>
                         <font color="red"><b><?php echo REQUIRED_FIELD; ?> Please give the <font color="blue"><u>name of sheet</u></font> exactly as <font color="blue"><u>user name of employee</u></font></b>
			</font>
                       			 
			<br/><br/>
			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 
                    	 <b><font color="red"><?php echo REQUIRED_FIELD; ?> Columns must be in the same order as in above mentioned format</b><br/>
			 <b><font color="red"><?php echo REQUIRED_FIELD; ?> Not even a single column should be removed or added</font></b><br/>
 			 <b><font color="red"><?php echo REQUIRED_FIELD; ?> First column is for Sr.No.</font></b><br/>
			 <b><font color="red"><?php echo REQUIRED_FIELD; ?> Second column is for Student Roll No.</font></b><br/>
			 <b><font color="red"><?php echo REQUIRED_FIELD; ?> Third column is for Student Name</font></b><br/>
             <b><?php echo REQUIRED_FIELD; ?> Please give the <font color="blue"><u>name of sheet</u></font> exactly as <font color="blue"><u>user name of employee</u></font></b>
			
										
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
											<td colspan="1" class="content_title">Mentorship Incorrect Entries Report :</td>
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
