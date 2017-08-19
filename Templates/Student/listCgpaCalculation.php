<?php 
//This file creates Html Form for cgpa calculation
//
// Author :Ajinder Singh
// Created on : 16-feb-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
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
									<form name="totalMarksReportForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0px" cellspacing="0px" >
											<tr>
												<td class="contenttab_internal_rows"><nobr><b>Time Table: </b></nobr></td>
												<td class="padding">
												<select size="1" class="htmlElement" name="labelId" id="labelId" style="width:200px">
												<option value="">Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select></td>
												<td colspan="1" align="right" valign="" style="padding-left:10px">
													<strong>Degree :</strong> &nbsp;
												</td>
												<td valign="" rowspan='1'>
								<select name="class1" id="class1" class="htmlElement"  style="width:290px" onfocus="getMarksTotalClasses();">
														<option value=''>Select</option>
													</select>
												</td>
                                                <td valign="" style="display:none" rowspan='1'>&nbsp;
                                                    <input name="chkExternal" id="chkExternal" value="1" type="checkbox">
                                                    Include External Marks<br> 
                                                </td>
                                             </tr>
                                             <tr>
                                             <td class="contenttab_internal_rows" align="left" colspan="10"><nobr>
                                               <table align="left" border="0" cellpadding="0px" cellspacing="0px" >
                                               <tr>
                     <td class="contenttab_internal_rows" align="left"><nobr><strong>
                     Wheather to include fail grade (CGPA/SGPA)
                     </strong></nobr></td>
                      <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                      <td class="contenttab_internal_rows" align="left"><nobr>  
                        <input name="includeFail" id="includeFailYes" value="1" checked="checked" type="radio">Yes&nbsp;
                        <input name="includeFail" id="includeFailNo" value="0" type="radio">No
                        </nobr>
                      </td>
                    <td class="contenttab_internal_rows" align="left" style="padding-left:20px" >
	                <input type="image" src="<?php echo IMG_HTTP_PATH;?>/calculate_cgpa.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
                            </tr>
                         </table></nobr>
                         </td>                       
											</tr>
										</table>
									</form>
								</td>
							</tr>
                            <tr><td height="10px"></td></tr>  
                            <tr>
                                   <td style="padding-left:10px">
                                     <!-- <span class='redLink'> -->
                                     <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: red;"> 
                                     <strong>Note:&nbsp;</strong><br>
                                     -- Precision is used to define how many places after decimal one wants to keep. To change check Config Master (<u>Other</u> tab)<br>
                                     </h4>
                                     </span>
                                      </td>
                            </tr>
                            <tr><td height="10px"></td></tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Total Marks Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<a id='generateCSV' href='javascript:printCSV();'><img src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></a></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
									<div id = 'pagingDiv' align='right'></div>
								</td>
							</tr>
							<tr>
								<td colspan="7" align="right">
									
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<a id='generateCSV2' href='javascript:printCSV();'><img src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></a></td>
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

////$History: scListCgpaCalculation.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 2/16/09    Time: 4:45p
//Created in $/Leap/Source/Templates/ScStudent
//file added for cgpa calculation
//
//







?>
