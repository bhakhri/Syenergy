<?php 
//-------------------------------------------------------
//  This File contains html code for Student External Marks Upload
//
//
// Author :Jaineesh
// Created on : 26-Oct-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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

								<form name="addForm" action="" id="addForm" method="post" enctype="multipart/form-data">
										<table align="center" border="0" cellpadding="0" width="90%">
											<tr>
												<td class="contenttab_internal_rows" nowrap><strong>Time Table:</strong> </td>
												<td><select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:150px" onChange="getLabelClass()">
														<option value="">Select</option>
														<?php 
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
													</select>
												</td>
												<td align="left" class="contenttab_internal_rows" nowrap>
													<strong>Class:</strong>
												</td>
												<td ><select size="1" class="selectfield" name="degree" id="degree" onChange="getClass();">
												<option value="">Select Class</option>
												<?php
													require_once(BL_PATH.'/HtmlFunctions.inc.php');
													echo HtmlFunctions::getInstance()->getCurrentSessionClasses();?>
												?>
												</select></td>
												<td align="left" valign="middle">
													<input type="image" name="studentMarksSubmit" value="studentMarksSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="doSubmitAction();return false;" />  
												</td>
												<td class="contenttab_internal_rows" nowrap><b>Display Result:</b></td>
												<td>
													<select name='showError' id='showError' onChange="getClass();">
														<option value='screen'>On Screen</option>
														<option value='file'>In downloadable Text File</option>
													</select>
                                               </td>
											   <td> <input type="image" name="marksStudentListSubmit" value="marksStudentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getClassSubjects();return false;" /></td>
	<tr>	</tr>

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
                           <td class="contenttab_internal_rows"><b>Max. Marks&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           
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
			<b><font color="red">4. Note that here Max. Marks is integer value like 45 out of 100 so Max. 				Marks=100 </font></b><br/>
			<b><font color="red">5. For students absent in test,please enter alphabet "A". </font></b><br/>
										
		</span></nobr>
                                                  </td>
                                                 </tr> 
                                              </table>
                                          </td>
                                     </tr>
</table>
										<table align="center" border="0" cellpadding="0" width="80%">
											<tr>
												<td valign='top' colspan='2' class='' align="center">
										<div id='marksDiv'></div> 
													<div id='statusDiv'></div>
													<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:500px;height:500px;border:2px solid #000;display:none;"></iframe>
												</td>
											</tr>
										</table>
									</form>
									
								</td>
							</tr>
						</table><!-- form table ends -->
					</td>
				</tr>
			</table>
		</table>
<?php 
//$History: listStudentMarksUpload.php $
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 2/22/10    Time: 11:07a
//Updated in $/LeapCC/Templates/StudentMarksUpload
//modified bread crum
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 2/22/10    Time: 11:02a
//Updated in $/LeapCC/Templates/StudentMarksUpload
//change bread crum
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 2/19/10    Time: 10:37a
//Updated in $/LeapCC/Templates/StudentMarksUpload
//put time table label onBlur() function
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 2/17/10    Time: 7:24p
//Updated in $/LeapCC/Templates/StudentMarksUpload
//fixed bug nos. 0002885, 0002887, 0002886, 0002888, 0002889 and add time
//table filter also
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/16/10    Time: 3:11p
//Updated in $/LeapCC/Templates/StudentMarksUpload
//display none of iframe
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 2/15/10    Time: 7:19p
//Updated in $/LeapCC/Templates/StudentMarksUpload
//fixed bug nos. 0002869, 0002870, 0002868, 0002867, 0002865, 0002864,
//0002866, 0002871
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 2/12/10    Time: 4:23p
//Updated in $/LeapCC/Templates/StudentMarksUpload
//fixed bug nos.  0002857, 0002861, 0002860, 0002863, 0002862
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/11/10    Time: 10:20a
//Updated in $/LeapCC/Templates/StudentMarksUpload
//fixed bug nos. 0002843, 0002844, 0002845, 0002847, 0002848
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/09/10    Time: 5:58p
//Created in $/LeapCC/Templates/StudentMarksUpload
//new files for upload/download student external marks
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
