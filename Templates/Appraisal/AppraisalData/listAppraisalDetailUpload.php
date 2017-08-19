
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
									<form method="POST" name="addForm"  action="" onSubmit="return false;" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td valign='top' colspan='2' class=''>
													<B>Notes: <br>1.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadAppraisalGroupFormat.php?t=suf'>here</a> to download Employee Appraisal Detail Format.
													<br>2.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadAppraisalGroupFormat.php?t=sui'>here</a> to download instructions.</B>
												</td>
											</tr>
											<tr>
												<td colspan="1" align="right" valign="top" >
													<strong>Select File :&nbsp;</strong>
												</td>
												<td valign="top" rowspan='1'>&nbsp;
													<input type="file" id="appraisalDetailUploadFile" name="appraisalDetailUploadFile" class="inputbox1" <?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:1px solid #fff;"></iframe>
												</td>
												<td colspan="2" valign="top">
													<input type="image" name="appraisalListSubmit" value="appraisalListSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="submitForm('upload');" />
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
                           <td class="contenttab_internal_rows"><b>[Sr.No.]</b></td>
                           <td class="contenttab_internal_rows"><b>[employeeCode]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>[scoreGained]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>[dutiesWeekend]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>[extSupreintendent]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>[copyChecked]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>[dutiesExternal]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>[dutiesInternal]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">1</td>
                           <td class="contenttab_internal_rows">E08001001</td>
                           <td class="contenttab_internal_rows">10</td>
                           <td class="contenttab_internal_rows">5</td>
                           <td class="contenttab_internal_rows">3</td>
                           <td class="contenttab_internal_rows">1</td>
			<td class="contenttab_internal_rows">2</td>
			 <td class="contenttab_internal_rows">3</td>
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">E1306044</td>
                           <td class="contenttab_internal_rows">5</td>
                           <td class="contenttab_internal_rows">6</td>
                           <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">1</td>
			 <td class="contenttab_internal_rows">3</td>
			 <td class="contenttab_internal_rows">3</td>
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">3</td>
                           <td class="contenttab_internal_rows">E2907159</td>
                           <td class="contenttab_internal_rows">8</td>
                           <td class="contenttab_internal_rows">5</td>
                           <td class="contenttab_internal_rows">6</td>
                           <td class="contenttab_internal_rows">1</td>
			<td class="contenttab_internal_rows">3</td>
			 <td class="contenttab_internal_rows">2</td>
                         </tr>
                        </table>
			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 <b><font color="red">1. Columns marks with * are compulsory</font></b><br/>
                    	 <b><font color="red">2. Columns must be in the same order as in above mentioned format</b><br/>
			 <b><font color="red">3. Not even a single column should be removed or added</font></b><br/>
			 <b><font color="red">4. Don't Forget to put the [ ] brackets</font></b><br/>
										
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
