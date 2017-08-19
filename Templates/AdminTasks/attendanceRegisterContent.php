<?php 
//This file creates Html Form output for Attendance Register  
//
// Author :Parveen Sharma
// Created on : 28-04-09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="attendanceForm" action="" method="post" onSubmit="return false;">        
<input type="hidden" readonly id="storeDays" name="storeDays" class="inputbox" /> 
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
                                    <table width="90%" align="left" border="0" cellpadding="0px" cellspacing="0px">
                                        <tr>   
                                            <td class="contenttab_internal_rows" align='left' nowrap><strong>Time Table&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>  
                                            <td class="contenttab_internal_rows" align="left" >
                                                <nobr>
                                                <select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:200px" onChange="getLabelClass(); return false;">
                                                    <option value="">Select</option> 
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTimeTableLabelDate('',"");?>
                                                </select>
                                                </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" align="right"><strong>&nbsp;Degree&nbsp;<?php echo REQUIRED_FIELD ?></strong></td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>  
                                            <td class="contenttab_internal_rows" align="left" nowrap>
                                                <select size="1" class="selectfield" name="degree" id="degree" style="width:320px" onChange="getSubject(); return false;">
                                                    <option value="">Select</option>
                                                 </select>
                                            </td>
                                            <td class="contenttab_internal_rows" ><nobr>
                                            <strong>&nbsp;Show Attendance Upto&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr>
                                            </td>
											<a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                                            <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>    
                                            <td class="contenttab_internal_rows" align="left" colspan="7"><nobr>
                                                <?php 
                                                   require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                   echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'),'',"onBlur=\"getDeliverLecture();\""); 
                                                ?></nobr>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="contenttab_internal_rows" align="right"><nobr><strong>Subject<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                            <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                            <td class="contenttab_internal_rows" align="left" >
                                            <nobr>
                                                <select size="1" class="inputbox1" name="subjectId" id="subjectId" style="width:200px" onChange="getGroup(); return false;">
                                                    <option value="">Select</option>    
                                                </select>
                                            </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" align="right"><nobr><strong>&nbsp;Group<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                            <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                            <td class="contenttab_internal_rows" align="left" colspan="10">
                                             <table width="10%" align="left" border="0" cellpadding="0px" cellspacing="0px"> 
                                                <tr>
                                                   <td class="contenttab_internal_rows" align="left" >
                                                        <nobr>
                                                        <select size="1" class="inputbox1" name="groupId" id="groupId" style="width:110px" onChange="getDeliverLecture();">
                                                            <option value="">Select</option> 
                                                        </select>
                                                        </nobr>     
                                                    </td>
                                                    <td class="contenttab_internal_rows" align="left" width="2%"><nobr>
                                                    &nbsp;<input type="checkbox" id="consolidatedId" name="consolidatedId" onClick="getDeliverLecture();"></nobr>   
                                                    </td>    
                                                    <td class="contenttab_internal_rows" align="left"  width="98%"><nobr>
                                                      Consolidated</nobr> 
                                                    </td>
                                                    <td class="contenttab_internal_rows" align="right" width="2%" style="padding-left:15px;"><b><nobr>&nbsp;Lectures Delivered Till Date</nobr></b></td>
                                                    <td class="contenttab_internal_rows" align="left"  width="2%"><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                                    <td class="contenttab_internal_rows" align="left"  width="96%">
                                                    <nobr>
                                                        <label id="deliverAttendance" class="contenttab_internal_rows">0 </label>
                                                        <span id='deliverAttendance11' style='display:none;'>
                                                           &nbsp;<label id="deliverAttendanceL" class="contenttab_internal_rows"><b>L-:&nbsp;</b>0</label> 
                                                           &nbsp;<label id="deliverAttendanceT" class="contenttab_internal_rows"><b>T-:&nbsp;</b>0</label> 
                                                        </span>               
                                                    </nobr>     
                                                    </td>
                                                 </tr>
                                               </table>
                                             </td>        
                                          </tr>
                                          <tr>  
                                            <td class="contenttab_internal_rows" align="left" >
                                                <nobr><strong>View of Lectures</strong></nobr> 
                                            </td>                           
                                            <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows" align="left" valign="middle">
                                                <table width="10%" align="left" border="0" cellpadding="0px" cellspacing="0px"> 
                                                    <tr>
                                                        <td class="contenttab_internal_rows" align="left" ><nobr>
                                                            <nobr><input type="text" maxlength="3" style="width:35px" id="nosCol" name="nosCol" onkeydown="return sendKeys('nosCol',event);" value="20" class="inputbox" /></nobr>    
                                                        </td>    
                                                        <td class="contenttab_internal_rows" align="left" style="padding-left:10px;">
                                                        	<table width="10%" align="left" border="0" cellpadding="0px" cellspacing="0px">
                                                        		<tr>
				                                                	<td>
				                                                	<nobr>&nbsp;<input type="checkbox" id="dutyLeave" name="dutyLeave"></nobr>   
				                                                	</td>    
				                                                	<td class="contenttab_internal_rows" align="left"><nobr>Show Duty Leave</nobr> 
				                                                	</td>
				                                                </tr>
				                                                <tr>
				       											    <td>
				                                                <nobr>&nbsp;<input type="checkbox" id="medicalLeave" name="medicalLeave"></nobr>   
				                                                	</td>    
				                                                <td class="contenttab_internal_rows" align="left"><nobr>Show Medical Leave</nobr> 
				                                                	</td>
				                                                </tr>
                                                        	</table>
                                                        </td>
                                                        
                                                    </tr>
                                                </table>    
                                            </td>                           
                                            <td class="contenttab_internal_rows" align="left"><nobr><strong>&nbsp;Heading</strong></nobr></td>
                                            <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                            <td class="contenttab_internal_rows" align="left" colspan="10">
                                              <table width="100%" align="left" border="0" cellpadding="0px" cellspacing="0px"> 
                                                <tr>
                                                    <td class="contenttab_internal_rows" align="left" ><nobr>
                                                       <input type="text" maxlength="100" style="width:220px" id="heading" name="heading" onkeydown="return sendKeys('heading',event);" class="inputbox" /> </nobr>  
                                                    </td>
                                                    <td class="contenttab_internal_rows" align="right"><nobr><strong>&nbsp;Sort </strong></nobr></td>
                                                    <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                                    <td class="contenttab_internal_rows" align="left">
                                                      <nobr>
                                                        <select size="1" class="inputbox1" name="sortField1" id="sortField1" style="width:105px">
                                                            <option value="universityRollNo">Univ. Roll No.</option>
                                                            <option value="rollNo">Roll No.</option>
                                                            <option value='studentName'>Namewise</option> 
                                                        </select>
                                                    </td>  
                                                    <td class="contenttab_internal_rows" align="left" colspan="15">
                                                      <nobr>  
                                                        <table width='100%'>
                                                           <tr>
                                                              <td class="contenttab_internal_rows" align="left"><nobr><strong>Order</strong></nobr></td>
                                                              <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                              <td class="contenttab_internal_rows" align="left"><nobr>  
                                                                <input type="radio" name="sortOrderBy1" id="sortOrderBy1" value="ASC"  checked="checked" onclick="hideResults();" />Asc&nbsp;
                                                                <input type="radio" name="sortOrderBy1" id="sortOrderBy2" value="DESC" onclick="hideResults();" />Desc
                                                                </nobr>
                                                              </td>
                                                            </tr>
                                                         </table>     
                                                       </nobr> 
                                                    </td>
                                                   
                                                 </tr>
                                               </table>         
                                            </td>               
                                          </tr>
                                          <tr>
                                            <td class="contenttab_internal_rows" align="right" width="2%" ><nobr><strong>Show Prefix</strong></nobr></td>
                                            <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>
                                            <td class="contenttab_internal_rows" align="left" colspan="10" width="2%"><nobr>
                                              <table width="10%" align="left" border="0" cellpadding="0px" cellspacing="0px"> 
                                                <tr>
                                                    <td class="contenttab_internal_rows" align="right" width="2%" ><nobr>Not Member of Class</nobr></td>
                                                    <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>
                                                    <td class="contenttab_internal_rows" align="left">
                                                        <nobr><input type="text" maxlength="2" style="width:35px" id="notMemberPrefix" name="notMemberPrefix" value="N" class="inputbox" /></nobr>
                                                    </td>
                                                    <td class="contenttab_internal_rows" align="right" width="2%" ><nobr>&nbsp;Absent</nobr></td>
                                                    <td class="contenttab_internal_rows" align="left"  width="2%"><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                                    <td class="contenttab_internal_rows" align="left">
                                                        <nobr><input type="text" maxlength="2" style="width:35px" id="absentPrefix" name="absentPrefix" value="X" class="inputbox" /></nobr>
                                                    </td>
                                                    <td class="contenttab_internal_rows" align="right" width="2%" ><nobr>&nbsp;Duty Leave</nobr></td>
                                                    <td class="contenttab_internal_rows" align="left"  width="2%"><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                                    <td class="contenttab_internal_rows" align="left">
                                                        <nobr><input type="text" maxlength="2" style="width:35px" id="dutyLeavePrefix" name="dutyLeavePrefix" value="DL" class="inputbox" /></nobr>
                                                    </td>
                                                    
                                                    <td class="contenttab_internal_rows" align="right" width="2%" ><nobr>&nbsp;Medical Leave</nobr></td>
                                                    <td class="contenttab_internal_rows" align="left"  width="2%"><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                                    <td class="contenttab_internal_rows" align="left">
                                                        <nobr><input type="text" maxlength="2" style="width:35px" id="medicalLeavePrefix" name="medicalLeavePrefix" value="ML" class="inputbox" /></nobr>
                                                    </td>
                                                    
                                                    <td colspan='10' class="contenttab_internal_rows" align="left" style="padding-left:20px;"><nobr>   
                                                          <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                    </td>
                                                  </tr>
                                              </table></nobr>  
                                             </td>
                                          </tr>                                                 
                                          <tr><td height="5"></tr>
                                          <tr id='subjectInfo' style='display:none'>
                                            <td class="contenttab_internal_rows" align="left"><nobr><strong>Subject</strong></nobr></td>
                                            <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                            <td class="contenttab_internal_rows" align="left" colspan="2"><nobr>  
                                               <nobr><label id='subjectName' class="contenttab_internal_rows"></label><nobr> 
                                            </td>
                                            <td class="contenttab_internal_rows" align="left" colspan="15"><nobr><strong>Teacher&nbsp;:&nbsp;</strong>
                                               <nobr><label id='employeeName' class="contenttab_internal_rows"></label><nobr> 
                                            </td>
                                         </tr>  
                                    </table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_internal_rows" align="left">
									<font color="red"><b><u>Please Note:</u>&nbsp;</b></font><br>
									<font color="red">1. Medical Leaves are ONLY applicable when Consolidated is <b>checked</b>.</font><br/>
									<font color="red">2. Medical Leaves are counted in the Aggregate ONLY if (Total Attendance + Duty Leaves) lie between <?php echo $sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT'); ?>% and <?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>% </font>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
										<tr>
											<td class="content_title" width="15%" valign="top" ><nobr>Attendance Register Details :</nobr></td>
                                            <td colspan="1" nowrap class="" valign="top" width="45%" style="font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px; font-weight:bold;" >
                                               </nobr>
                                               <!--
                                                 <table width="70%" border="0" cellspacing="0" cellpadding="0"  class="">
                                                   <tr>                                                                     
                                                      <td nowrap align="left" valign="top">i.e.&nbsp;&nbsp;&nbsp;</td>
                                                      <td nowrap align="left" valign="top">Serial No.</td>
                                                   </tr>
                                                   <tr>   
                                                      <td nowrap align="left" valign="top">&nbsp;</td>                            
                                                      <td nowrap align="left" valign="top">Show Attendance Date (Daily/Bulk) (DD/MM)</td>
                                                   </tr>
                                                   <tr>      
                                                      <td nowrap valign="top">&nbsp;</td>                            
                                                      <td nowrap align="left" valign="top">Period No.</td>
                                                    </tr>
                                                 </table>
                                               -->
                                               </nobr>  
                                            </td>
											<td width="15%" class="content_title" align="right" valign="top">
                                                <nobr>
                                                   <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                    <input type="image" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick='printCSV()'/>&nbsp; 
                                                </nobr>
                                            </td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row' style="width:490px" >
                                    <div id="scroll2" style="overflow:auto; width:1000px; height:510px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                    </div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
								  	      <td width="15%" class="content_title" align="right" valign="top">  
                                            <nobr>
                                                <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick='printCSV()'/>&nbsp;    
                                            </nobr>
                                          </td>
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
//$History: attendanceRegisterContent.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 4/10/10    Time: 5:20p
//Updated in $/LeapCC/Templates/AdminTasks
//validation & format updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 3/26/10    Time: 7:10p
//Updated in $/LeapCC/Templates/AdminTasks
//keyPress check updated
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/25/10    Time: 1:44p
//Updated in $/LeapCC/Templates/AdminTasks
//updated breadcrumb
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/19/10    Time: 1:51p
//Updated in $/LeapCC/Templates/AdminTasks
//label name update (no. of lectures)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/19/10    Time: 1:18p
//Updated in $/LeapCC/Templates/AdminTasks
//format updated (no. of columns check) 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/18/10    Time: 6:19p
//Updated in $/LeapCC/Templates/AdminTasks
//format & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 4:28p
//Created in $/LeapCC/Templates/AdminTasks
//initial checkin
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
