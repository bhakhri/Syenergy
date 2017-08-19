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
									<form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/StudentDetailUpload/fileUpload1.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td valign='top' colspan='2' class=''>
													<B>Notes: <br>1.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadStudentDetailFormat.php?t=suf'>here</a> to download Student Detail Format. <br>2.&nbsp;Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadStudentDetailFormat.php?t=sui'>here</a> to download instructions.</B>
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
											</tr>
											<tr>
												<td colspan="1" align="right" valign="top" >
													<strong>Select File :&nbsp;</strong>
												</td>
												<td valign="top" rowspan='1'>&nbsp;
													<input type="file" id="studentDetailUploadFile" name="studentDetailUploadFile" class="inputbox1" <?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:1px solid #fff;"></iframe>
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
                                                <td class="contenttab_internal_rows" colspan="20" height="30px" valign="top">  
<img src="<?php echo IMG_HTTP_PATH;?>/reddot_icon_rdax.jpg"/><span style="font-weight:bold;font-size:10px;">&nbsp;Missing Values</span>&nbsp;&nbsp;&nbsp;
<img src="<?php echo IMG_HTTP_PATH;?>/blue-dot.png"/><span style="font-weight:bold;font-size:10px;">&nbsp;Duplicate Values</span>&nbsp;&nbsp;&nbsp;
<img src="<?php echo IMG_HTTP_PATH;?>/green_dot.gif"/><span style="font-weight:bold;font-size:10px;">&nbsp;Format Mismatch</span>
                                                </td>
                                               </tr>
                                                <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" >
                                                    <b><a href="" class="link" onClick="getShowDetail(); return false;" >
                                                       <Label id='idSubjects'>Expand Sample Format for .xls file and instructions</label></b></a>
                                                       <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                                  </td>
                                               <td>
<input value='1'   id="ignoreDuplicateRollUniv" name="ignoreDuplicateRollUniv" type="checkbox">Ignore Duplicate Roll No./Univ No.  
                           <input value='1'   id="addressValid" name="addressValid" type="checkbox">Ignore State, City, Country Validation
                           <input value='1'   id="domicileValid" name="domicileValid" type="checkbox">Ignore Domicile Validation
                           <input value='1'   id="quotaValid" name="quotaValid" type="checkbox">Ignore Quota  Validation
                                         </td>
                                                 </tr> 
                                                 <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" id='showSubjectEmployeeList11'>
                                                    <nobr><br><span id='subjectTeacherInfo'>
<div id="OverDiv12" style="overflow:auto; width:960px; height:300px">
 <div id="Over2Div">
<table border="1" cellpadding="0" cellspacing="0" width="100%">
                         <tr>
                           <td class="contenttab_internal_rows"><b>[Sr.No.]</b></td>
                           <td class="contenttab_internal_rows"><b>[Roll No]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           
                        <td class="contenttab_internal_rows"><b>[Univ Roll No]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                        <td class="contenttab_internal_rows"><b>[Is Leet]</b></td>
                        <td class="contenttab_internal_rows"><b>[First Name]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>[Last Name]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Father Name]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
	 		<td class="contenttab_internal_rows"><b>[Father Occupation]</b></td>
 			<td class="contenttab_internal_rows"><b>[Father Mobile]</b></td>
			<td class="contenttab_internal_rows"><b>[Father Address1]</b></td>
 			<td class="contenttab_internal_rows"><b>[Father Address2]</b></td>
 			<td class="contenttab_internal_rows"><b>[Father Country]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Father State]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Father City]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Mother Name]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>[Date of Birth]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Corr. Address1]</b></td>
 			<td class="contenttab_internal_rows"><b>[Corr. Address2]</b></td>
 			<td class="contenttab_internal_rows"><b>[Corr. Pin Code]</b></td>
 			<td class="contenttab_internal_rows"><b>[Corr. Country]</b></td>
 			<td class="contenttab_internal_rows"><b>[Corr. State]</b></td>
			<td class="contenttab_internal_rows"><b>[Corr. City]</b></td>
 			<td class="contenttab_internal_rows"><b>[Perm. Address1]</b></td>
 			<td class="contenttab_internal_rows"><b>[Perm. Address2]</b></td>
			<td class="contenttab_internal_rows"><b>[Perm. Pin Code]</b></td>
 			<td class="contenttab_internal_rows"><b>[Perm. Country]</b></td>
 			<td class="contenttab_internal_rows"><b>[Perm. State]</b></td>
 			<td class="contenttab_internal_rows"><b>[Perm. City]</b></td>
 			<td class="contenttab_internal_rows"><b>[Student Mobile]</b></td>
 			<td class="contenttab_internal_rows"><b>[Domicile]</b></td>
			<td class="contenttab_internal_rows"><b>[Hostel Facility]</b></td>
 			<td class="contenttab_internal_rows"><b>[Bus Facility]</b></td>
 			<td class="contenttab_internal_rows"><b>[Correspondence Phone]</b></td>
 			<td class="contenttab_internal_rows"><b>[Permanent Phone]</b></td>
 			<td class="contenttab_internal_rows"><b>[Student Status]</b></td>
			<td class="contenttab_internal_rows"><b>[Gender]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Nationality]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Quota]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Contact No.]</b></td>
			<td class="contenttab_internal_rows"><b>[Email]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			<td class="contenttab_internal_rows"><b>[Alternate Email]</b></td>
			<td class="contenttab_internal_rows"><b>[Date of Admission]</b></td>
 			<td class="contenttab_internal_rows"><b>[Registration No.]&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
 			
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">1</td>
                           
                        <td class="contenttab_internal_rows">U10100</td>
                        <td class="contenttab_internal_rows">Un3333</td>
                        <td class="contenttab_internal_rows">yes</td>
                        <td class="contenttab_internal_rows">RAM</td>
			<td class="contenttab_internal_rows">SHARMA</td>
 			<td class="contenttab_internal_rows">ANIL SHARMA</td>
	 		<td class="contenttab_internal_rows">SERVICE</td>
 			<td class="contenttab_internal_rows">98473534</td>
			<td class="contenttab_internal_rows">SC0-12,CHANDIGARH</td>
 			<td class="contenttab_internal_rows">SC0-133,CHANDIGARH</td>
 			<td class="contenttab_internal_rows">INDIA</td>
 			<td class="contenttab_internal_rows">PUNJAB</td>
 			<td class="contenttab_internal_rows">CHANDIGARH</td>
 			<td class="contenttab_internal_rows">SEEMA SHARMA</td>
			<td class="contenttab_internal_rows">1994.5.7</td>
 			<td class="contenttab_internal_rows">SC0-12,CHANDIGARH</td>
 			<td class="contenttab_internal_rows">SC0-98,CHANDIGARH</td>
 			<td class="contenttab_internal_rows">17267</td>
 			<td class="contenttab_internal_rows">INDIA</td>
 			<td class="contenttab_internal_rows">PUNJAB</td>
			<td class="contenttab_internal_rows">CHANDIGARH</td>
 			<td class="contenttab_internal_rows">SC0-123 CHANDIGARH</td>
 			<td class="contenttab_internal_rows">SC0-123 CHANDIGARH</td>
			<td class="contenttab_internal_rows">17254</td>
 			<td class="contenttab_internal_rows">INDIA</td>
 			<td class="contenttab_internal_rows">PUNJAB</td>
 			<td class="contenttab_internal_rows">CHANDIGARH</td>
 			<td class="contenttab_internal_rows">9843455434</td>
 			<td class="contenttab_internal_rows">U.T.</td>
			<td class="contenttab_internal_rows">YES</td>
 			<td class="contenttab_internal_rows">NO</td>
 			<td class="contenttab_internal_rows">5556456</td>
 			<td class="contenttab_internal_rows">65667687</td>
 			<td class="contenttab_internal_rows">YES</td>
			<td class="contenttab_internal_rows">MALE</td>
 			<td class="contenttab_internal_rows">INDIAN</td>
 			<td class="contenttab_internal_rows">GENERAL</td>
 			<td class="contenttab_internal_rows">456346657</td>
			<td class="contenttab_internal_rows">ram_sharma@gmail.com</td>
 			<td class="contenttab_internal_rows">sharma.ram@yahoo.com</td>
			<td class="contenttab_internal_rows">2009.3.15</td>
 			<td class="contenttab_internal_rows">A119</td>
                         </tr>
                         <tr>
                               <td class="contenttab_internal_rows">2</td>
                           
                        <td class="contenttab_internal_rows">U4567</td>
                        <td class="contenttab_internal_rows">Un878</td>
                        <td class="contenttab_internal_rows">yes</td>
                        <td class="contenttab_internal_rows">KALAM</td>
			<td class="contenttab_internal_rows">NEGI</td>
 			<td class="contenttab_internal_rows">F.R NEGI</td>
	 		<td class="contenttab_internal_rows">SERVICE</td>
 			<td class="contenttab_internal_rows">99684645</td>
			 <td class="contenttab_internal_rows">11/78 PREET VIHAR,DELHI</td>
 			<td class="contenttab_internal_rows">566 RAJENDRA NAGAR DELHI</td>
 			<td class="contenttab_internal_rows">INDIA</td>
 			<td class="contenttab_internal_rows">DELHI</td>
 			<td class="contenttab_internal_rows">DELHI</td>
 			<td class="contenttab_internal_rows">SURBHI NEGI</td>
			 <td class="contenttab_internal_rows">1994.5.15</td>
 			<td class="contenttab_internal_rows">11/78 PREET VIHAR,DELHI</td>
 			<td class="contenttab_internal_rows">11/78 PREET VIHAR,DELHI</td>
 			<td class="contenttab_internal_rows">11244</td>
 			<td class="contenttab_internal_rows">INDIA</td>
 			<td class="contenttab_internal_rows">DELHI</td>
			<td class="contenttab_internal_rows">DELHI</td>
 			<td class="contenttab_internal_rows">566 RAJENDRA NAGAR DELHI</td>
 			<td class="contenttab_internal_rows">566 RAJENDRA NAGAR DELHI</td>
			 <td class="contenttab_internal_rows">1122</td>
 			<td class="contenttab_internal_rows">INDIA</td>
 			<td class="contenttab_internal_rows">DELHI</td>
 			<td class="contenttab_internal_rows">DELHI</td>
 			<td class="contenttab_internal_rows">9855554</td>
 			<td class="contenttab_internal_rows">U.T.</td>
			<td class="contenttab_internal_rows">YES</td>
 			<td class="contenttab_internal_rows">NO</td>
 			<td class="contenttab_internal_rows">64565678</td>
 			<td class="contenttab_internal_rows">76878788</td>
 			<td class="contenttab_internal_rows">YES</td>
			<td class="contenttab_internal_rows">MALE</td>
 			<td class="contenttab_internal_rows">INDIAN</td>
 			<td class="contenttab_internal_rows">GENERAL</td>
 			<td class="contenttab_internal_rows">4945635</td>
			<td class="contenttab_internal_rows">K_negi.com</td>
 			<td class="contenttab_internal_rows">negi.kalam@yahoo.com</td>
			<td class="contenttab_internal_rows">2009.3.15</td>
 			<td class="contenttab_internal_rows">A566</td>
			
                         </tr>
                        
                        </table>


			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 <b><font color="red">1. Columns marks with * are compulsory</font></b><br/>
			<b><font color="red">2.The format of Date  should be YYYY.MM.DD</font></b><br/>
			<b><font color="red">3.Don't forget to put the [ ](square) brackets </font></b><br/>
			
			
                    	 </div>
</div>
		</span></nobr>
                                                  </td>
                                                 </tr> 
                                              </table>
                                          </td>
                                     </tr>
</table>
<div style="overflow:auto; width:960px; height:400px;vertical-align:top;">
						<div id="result" style="width:98%; vertical-align:top;"></div>
					    </div>									</form>

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
<!--Confirmation Box   Start-->
<?php floatingDiv_Start('confirmDiv','Brief Description'); ?>
    <div style="overflow:auto; WIDTH:490px; HEIGHT:410px; vertical-align:top;"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
            <tr>
                <td height="5px"></td></tr>
            <tr>
            <tr>    
                <td width="89%">
                     <div id="confirmResultDiv" style="width:98%; vertical-align:top;"></div>
                </td>
            </tr>
        </table>
    </div>   
<?php floatingDiv_End(); ?> 
</div>            
<!-- Confirmation Box   End-->     
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
