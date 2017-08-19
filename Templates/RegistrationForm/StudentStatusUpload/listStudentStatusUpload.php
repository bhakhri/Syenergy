<?php 
//-------------------------------------------------------
//  This File contains html code for Student Detail Upload
//
// Author :Jaineesh
// Created on : 14-Nov-2009
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
									<form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/RegistrationForm/StudentDetailUpload/statusFileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td valign='top' colspan='2' class=''>
													<B>Notes: <br>1.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/RegistrationForm/downloadStudentStatusFormat.php?t=suf'>here</a> to download Student Detail Format. <br>
														2.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/RegistrationForm/downloadStudentStatusFormat.php?t=sui'>here</a> to download instructions.</B>
												</td>
											</tr>
											<tr style="display:none">
												<td colspan="1" align="right" >
													<strong>Select Class :&nbsp;</strong>
												</td>
												<td class="padding"><select size="1" class="selectfield" name="degree" id="degree">
												<option value="">Select Class</option>
												<?php
													require_once(BL_PATH.'/HtmlFunctions.inc.php');
													echo HtmlFunctions::getInstance()->getClassWithStudyPeriod($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
												?>
												</select></td>
											</tr>
											<tr>
												<td colspan="1" align="right" valign="top" >
													<strong>Select File :&nbsp;</strong>
												</td>
												<td valign="top" rowspan='1'>&nbsp;
													<input type="file" id="studentStatusUploadFile" name="studentStatusUploadFile" class="inputbox1" <?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:1px solid #fff;"></iframe>
												</td>
												<td colspan="2" valign="top">
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload.gif"  />
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
<div id="OverDiv12" style="overflow:auto; width:960px; height:300px">
 <div id="Over2Div">
<table border="1" cellpadding="2px" cellspacing="0px" width="30%">
                         <tr>
                           <td class="contenttab_internal_rows"><b>[Sr.No.]</b></td>                      
                           <td class="contenttab_internal_rows"><b>[Univ Roll No]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>[Scholar Type (D/H)]&nbsp;<?php echo REQUIRED_FIELD;?></b></td> 			
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">1</td>
                           <td class="contenttab_internal_rows">B08001</td>
                           <td class="contenttab_internal_rows">D</td>
                         </tr>
                         <tr>
                               <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">B08002</td>
                           <td class="contenttab_internal_rows">H</td>		
                         </tr>
                        
                        </table>


			<br/>
			<b><u >***Please Note***</u><b><br/>
			
                    	 <b><font color="red" style="padding-top:10px;">1. Columns marks with * are compulsory</font></b><br/>
			<b><font color="red" >2. Don't forget to put the [ ](square) brackets </font></b><br/>
			<b><font color="red" style="padding-left:12px;"> D : Day Scholar </font></b><br/>
			<b><font color="red" style="padding-left:12px;"> H : Hostler </font></b><br/>
			
			
                    	 </div>
</div>
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

								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">

								</td>
							</tr>
						</table>
						<!-- form table ends -->
					</td>
				</tr>
			</table>
		</table>
