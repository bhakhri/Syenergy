<?php 
//This file creates Html Form output for student Foxpro Marks Report
//
// Author :Parveen Sharma
// Created on : 28-04-09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="internalMarksFoxproFrm" action="" method="post" onSubmit="return false;">        
<select size="1" class="selectfield" name="subjectId" id="subjectId" style="display:none">
</select>                                                  
                                             
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
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
								<td valign="top" class="contenttab_row1" valign="top">
                                    <table width="35%" align="left" border="0" cellpadding="0px" cellspacing="0px">
                                        <tr>    
                                            <td class="contenttab_internal_rows" nowrap >
                                            <strong>College Code</strong>
                                            </td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>    
                                            <td class="contenttab_internal_rows" align="left" width="29%">
                                            <input type="text" maxlength="10" id="collegeCode" name="collegeCode" style="width:155px" class="inputbox" /> 
                                            </td>
                                            <td class="contenttab_internal_rows" align="left" nowrap>
                                               <strong>&nbsp;&nbsp;Stream Code</strong>
                                            </td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>
                                            <td class="contenttab_internal_rows" align="left" width="28%">
                                               <input type="text" maxlength="10" id="streamCode" name="streamCode"  style="width:160px" class="inputbox" /> 
                                            </td>
                                            <td class="contenttab_internal_rows" align="left" nowrap>
                                            <strong>&nbsp;&nbsp;Branch Code</strong>
                                            </td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>
                                            <td class="contenttab_internal_rows" align="left" >
                                            <input type="text" maxlength="10" id="branchCode" name="branchCode"  style="width:160px" class="inputbox" /> 
                                            </td>
                                        </tr>        
                                         <tr>
                                            <td class="contenttab_internal_rows" align='left' nowrap><strong>Time Table&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>  
                                            <td class="contenttab_internal_rows" align="left" nowrap>
                                            <select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:160px" onChange="getLabelClass()">
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTimeTableLabelData();?>
                                                </select></nobr>
                                            </td>
											<a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                                            <td class="contenttab_internal_rows" align="right"><strong>&nbsp;&nbsp;Degree&nbsp;<?php echo REQUIRED_FIELD ?></strong></td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>  
                                            <td class="contenttab_internal_rows" align="left" colspan="4" nowrap>
                                               <table align="left" border="0" cellpadding="0px" cellspacing="0px">
                                                 <tr>
                                                    <td class="contenttab_internal_rows" align="left">
                                                        <select size="1" class="selectfield" name="degree" id="degree" style="width:280px" onChange="getClassSubjects();">
                                                            <option value="">Select</option>
                                                         </select>
                                                        </nobr>
                                                    </td>
                                                    <td class="contenttab_internal_rows" align="left" style="padding-left:20px" nowrap>  
                                                        <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                    </td>
                                                 </tr>
                                               </table> 
                                            </td>    
                                        </tr>
                                    </table>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
										<tr>
											<td class="content_title" width="15%" valign="top" ><nobr>Student Details :</nobr></td>
                                            <td class="content_title" width="70%" valign="top" style="color:black;font-size:11px;"><nobr>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                   <tr>
                                                      <td align="left" width="20px"><b>Note&nbsp;:&nbsp;&nbsp;&nbsp;</b></td>
                                                      <td align="left"><b>a.&nbsp;&nbsp;Foxpro report should be generated only after all student of concerned class have been allotted Univ. Roll No.</b></td>
                                                   </tr>   
                                                      <td align="left" width="20px"></td>
                                                      <td align="left"><b>b.&nbsp;&nbsp;'Null' keyword will be displayed when student absent in Foxpro Report.</b></td>
                                                   </tr>   
                                                </table>
                                                </nobr>
                                            </td>
											<td width="15%" class="content_title" align="right" valign="top"><nobr>
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/generate_foxpro_report.gif" onClick="getGenerateFoxpro()" />&nbsp;
                                                </nobr>
                                            </td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv' style="overflow:auto; height:370px;" >
                                    </div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/generate_foxpro_report.gif" onClick="getGenerateFoxpro()" />&nbsp;</td>
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
</form>           
<?php 
//$History: internalMarksFoxproContent.php $
//
//*****************  Version 11  *****************
//User: Parveen      Date: 2/25/10    Time: 4:56p
//Updated in $/LeapCC/Templates/StudentReports
//button updated (generate_foxpro_report.gif)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 2/05/10    Time: 3:56p
//Updated in $/LeapCC/Templates/StudentReports
//time table label format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 2/05/10    Time: 1:03p
//Updated in $/LeapCC/Templates/StudentReports
//Time Table Label base format updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/17/09   Time: 1:26p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated 
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/10/09   Time: 11:15a
//Updated in $/LeapCC/Templates/StudentReports
// Improve the Look and feel
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/04/09   Time: 3:36p
//Updated in $/LeapCC/Templates/StudentReports
//college, stream, branch code columns added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/29/09   Time: 4:04p
//Updated in $/LeapCC/Templates/StudentReports
//studyCode, centerCode, branchCode added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/16/09    Time: 3:25p
//Updated in $/LeapCC/Templates/StudentReports
//div added in list view
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/01/09    Time: 1:06p
//Updated in $/LeapCC/Templates/StudentReports
//print button remove generate foxpro button added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/30/09    Time: 2:18p
//Created in $/LeapCC/Templates/StudentReports
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/29/09    Time: 11:29a
//Created in $/LeapCC/Templates/StudentReports
//file added
//

?>
