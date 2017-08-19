<?php 
//-------------------------------------------------------
//  This File contains html code for Import fee
//
//
// Author :Rajeev Aggarwal	
// Created on : 08-July-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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
                            <td class="contenttab_row1"><b>The Import Fees module lets you enter the fees of multiple students in one go into the application. The fees that is imported needs to be in a specific format which can be
                            seen by downloading the "Fee uploading format" by clicking on the link in note 1 below. The basic instructions to follow when importing the fees from the excel file can be seen by clicking the link in notice 2 below.
                            Not following these instructions will result in errors in the fees data. If the fees cannot be arranged in this format to be uploaded, it is better to enter the fees of students one at a time.<br/><br/>The fees can be imported one fees cycle at a time using the 
                            "Fee cycle" and "Select File" options below.<br/><br/></b></td></tr><tr>
								<td valign="top" class="contenttab_row1">
									<form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/Student/feeFileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td valign='top' colspan='7' class=''>
                                            <!--    Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadFeeFormat.php?t=suf'>here</a> to download Fee Uploading Format.      -->
												<B>Notes: <br>1.&nbsp; 
                                                <span id="linkInstructions"><strong>
                                                Click 
                                                <a class='redLink' href='#' onClick='return showInstructions(); return false;'>here</a>
                                                 to view Fee Uploading Format.</strong>
                                                 <br>2.&nbsp;Please read these <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadFeeFormat.php?t=sui'>instructions</a> before importing fees data.</B>
                                                </span>
												</td>
											</tr>
											<tr>
												<td height="10"></td>
											</tr>
											<tr>
												<td nowrap align="left" valign="top"><strong>Fee Cycle<?php echo REQUIRED_FIELD; ?></strong></td>
												<td nowrap align="left" valign="top"><strong>:</strong></td>
												<td nowrap align="left" valign="top"  >&nbsp;<select size="1" class="selectfield" name="feeCycleId" id="feeCycleId">
												<option value="">Select </option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getFeeCycleData($REQUEST_DATA['feeCycleId']==''? $stateRecordArray[0]['feeCycleId'] : $REQUEST_DATA['feeCycleId'] );
												?>
												</select>
												</td>
												<td nowrap align="left" valign="top"><strong>Select File</strong></td>
												<td nowrap align="left" valign="top"><strong>:</strong></td>
												<td nowrap align="left" valign="top"  >&nbsp;<input type="file" id="employeeInfoUploadFile" name="employeeInfoUploadFile" class="inputbox1" <?php echo $disableClass?>/>
												<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
												</td>
												<td align="right"  valign="top"><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload.gif"/></td>
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
                           <td class="contenttab_internal_rows"><b> Sno</b></td>
                           <td class="contenttab_internal_rows"><b>Roll No&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b> Receipt no</b></td>
                           <td class="contenttab_internal_rows"><b>Paid Date&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Paid Fee&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Trans_id</b></td>
			<td class="contenttab_internal_rows"><b>Student Name</b></td>
			<td class="contenttab_internal_rows"><b> Class</b></td>
			<td class="contenttab_internal_rows"><b> Semester</b></td>
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">1</td>
                           <td class="contenttab_internal_rows">B08001001</td>
                           <td class="contenttab_internal_rows">10</td>
                           <td class="contenttab_internal_rows">2011.08.15</td>
                           <td class="contenttab_internal_rows">77,000</td>
                           <td class="contenttab_internal_rows"></td>
			<td class="contenttab_internal_rows">ROHIT</td>
			 <td class="contenttab_internal_rows">11</td>
			<td class="contenttab_internal_rows">6</td>
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">B1306044</td>
                           <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">2011.08.15</td>
                           <td class="contenttab_internal_rows">77,000</td>
                           <td class="contenttab_internal_rows">2</td>
			 <td class="contenttab_internal_rows">MEGHA</td>
			 <td class="contenttab_internal_rows">1</td>
			<td class="contenttab_internal_rows">6</td>
                         </tr>
                         <tr>
                           <td class="contenttab_internal_rows">3</td>
                           <td class="contenttab_internal_rows">B5356044</td>
                           <td class="contenttab_internal_rows">7</td>
                           <td class="contenttab_internal_rows">2011.08.15</td>
                           <td class="contenttab_internal_rows">77,000</td>
                           <td class="contenttab_internal_rows">10</td>
			 <td class="contenttab_internal_rows">MEENA</td>
			 <td class="contenttab_internal_rows">2</td>
			<td class="contenttab_internal_rows">6</td>
                         </tr>
                        </table>
			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 <b><font color="red">1. Columns marks with * are compulsory</font></b><br/>
                    	 <b><font color="red">2. Columns must be in the same order as in above mentioned format</b><br/>
			 <b><font color="red">3. The format of Date should be YYYY.MM.DD</b><br/>
			 
			
										
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
        
   <!-- Instructions Div Starts -->
<?php  floatingDiv_Start('divInstructionsInfo','Fee Uploading Instructions','12','','','1'); ?>  
<div id="instructionsDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;" name="divInstructionsInfo"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>  
        <tr>    
            <td width="89%">     
                <div id="instruction" style="vertical-align:top;" >
                <table><tr><td>
               <b>Kindly follow these instructions <font color="red">strictly </font>before uploading the file: </b> </td></tr><tr> <td>
A. Import Student Fee    </td></tr><tr> <td> 
   ===================== </td></tr><tr> <td> 
   
    1. The following columns need to be present in the excel file to be uploaded. The order of the columns in the excel is left </td></tr><tr> <td>
       to right and this order needs to be maintained. All these are required fields. If any field has a blank value it may lead to erroneous data.</td></tr><tr> <td>
       Sno</td></tr><tr> <td>    
       Roll No (this is the student college roll number. This should be unique for every student)</td></tr><tr> <td>    
       Receipt date (this is the receipt number for the fees paid)</td></tr><tr> <td>
       Paid Date (this is the date on which the fees was paid)</td></tr><tr> <td>    
       Paid Fee (this is amount of fees paid in Rupees)</td></tr><tr> <td>
       Trans_id (this is an optional field which denotes a unique transaction id )</td></tr><tr> <td>
       Student Name(this is the name of the student)</td></tr><tr> <td>    
       Class (this is the class of the student for which the fees is being paid)</td></tr><tr> <td>    
       Semester (this is the semester for which the fees is being paid. This should match with semester mentioned in the class above )</td></tr><tr> <td>
       <br/>
    2. Not even a single column should be removed or added.</td></tr><tr> <td>
        <br/> 
    3. File extension should be .xls  </td></tr><tr> <td>
         <br/> 
    4. Date field should be in format MM.DD.YYYY . 
         </td></tr> </table> 
                </div> 
            </td>
        </tr>     
    </table>
</div>       
<?php floatingDiv_End(); ?>          
<?php 

?>
