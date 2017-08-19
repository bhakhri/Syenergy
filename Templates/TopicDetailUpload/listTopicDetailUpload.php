	<?php 
	//-------------------------------------------------------
	//  This File contains html code for Student Detail Upload
	//
	// Author :Jaineesh
	// Created on : 14-Nov-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	?>

				<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" class="title">
				  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
			</td>
		</tr>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" class="title">
				<table  border="0" cellspacing="0" cellpadding="5" class="contenttab_border2" width="100%" bgcolor="lightblue">
					<tr>
						<td height="6"></td>
					</tr>
					<tr>
						<td valign="center" colspan="2"><b><font color="">This page lets you select an csv file in a specific format through which you can upload the list of topics that are to be taught for a particular subject.These topics would then appear in teacher's list when they are marking attendance so that they can choose the particular they taught in that class.</b></td>
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
										<form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/TopicDetailUpload/fileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
											<table align="center" border="0" cellpadding="0">
												<tr>
													<td valign='top' colspan='2' class=''>
														<B>Notes: 	<br>1.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadTopicDetailFormat.php?t=suf'>here</a> to download Topic Detail Format. <br>2.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadTopicDetailFormat.php?t=sui'>here</a> to download instructions.</B>
													</td>
												</tr>
												
												<tr>
													<td colspan="1" align="right" valign="top" >
														<strong>Select File:&nbsp;</strong>
													</td>
													<td valign="top" rowspan='1'>&nbsp;
													<input type="file" id="topicDetailUploadFile"name="topicDetailUploadFile"class="inputbox1"<?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style=" width:00px;height:000px;border:0px solid #000;"></iframe>
													</td>
													<td align="right" colspan="2" valign="top">
													<input type="image" name="topicListSubmit" value="topicListSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload.gif"  />
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
                           <td class="contenttab_internal_rows"><b>Sr.No.</b></td>
                           <td class="contenttab_internal_rows"><b>University Roll No.&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Student Name&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b> [Max. Marks]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           
                         </tr>
                         <tr>
                          <td class="contenttab_internal_rows">1.</td>
                           <td class="contenttab_internal_rows">B0824852</td>
                           <td class="contenttab_internal_rows">MANISH GUPTA</td>
                           <td class="contenttab_internal_rows">90</td>
                           
                         </tr>
                         <tr>
                          <td class="contenttab_internal_rows">2.</td>
                           <td class="contenttab_internal_rows">B086534</td>
                           <td class="contenttab_internal_rows">ROHIT MALAN</td>
                           <td class="contenttab_internal_rows">90</td>
                           
                         </tr>
                         
                        </table>
			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 <b><font color="red">1. Columns marks with * are compulsory</font></b><br/>
                    	 <b><font color="red">2. Columns must be in the same order as in above mentioned format</b><br/>
			 <b><font color="red">3. Not even a single column should be removed or added</font></b><br/>
			<b><font color="red">4. Don't Forget to put the [ ] brackets where needed</font></b><br/>
										
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
												<td colspan="1" class="content_title">Student Group Incorrect Entries Report :</td>
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
	//$History: listStudentDetailUpload.php $
	//
	//*****************  Version 1  *****************
	//User: Jaineesh     Date: 11/18/09   Time: 6:40p
	//Created in $/LeapCC/Templates/StudentDetailUpload
	//new template for student upload
	//
	//
	?>
