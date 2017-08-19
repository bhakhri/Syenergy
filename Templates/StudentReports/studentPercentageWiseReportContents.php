<?php 
//-------------------------------------------------------
//  This File outputs test time period Form
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="studentAttendanceForm" action="" method="post" onSubmit="return false;">
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
                                <td valign="top" class="contenttab_row1">
                                          <table width="100%" border="0" align="left" cellspacing="2px" cellpadding="0px" >
                                          <tr>
                                            <td style='display:none' valign="top" width="20%" align="right" class="contenttab_internal_rows">
                                              <table border="0" align="left" cellspacing="0px" cellpadding="0px"> 
                                                <tr style='display:none'>
                                                  <td class="contenttab_internal_rows" colspan="3"><nobr><b>
                                                    <span id="consolidatedDiv" title="Consolidated View" style="text-decoration:underline;cursor:pointer;" onclick="toggleAttendanceDataFormat(''); return false;">
                                                         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/consolidated.gif" />
                                                    </span>
                                                    </nobr>  
                                                  </td>
                                                </tr>       
                                                <tr style='display:none'>
                                                 <td class="contenttab_internal_rows" colspan="3" ><nobr>
                                                   <input type="checkbox" id="consolidatedId" checked="checked" name="consolidatedId" value="1">&nbsp;<b>Consolidated (Lec. + Tut.)</b>
                                                   </nobr>
                                                 </td>
                                               </tr>
                                              </table> 
                                            </td>
											<a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                                            <td width="80%" colspan="2">
                                                <table width="100%" cellspacing="2px" cellpadding="0px" border="0" align="left" >
                                                    <td class="contenttab_internal_rows"><nobr><strong>Report Type</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>
                                                       <select name="reportType" id="reportType" class="inputbox1" style="width:170px">
                                                         <option value="1">Percentagewise</option>
                                                         <option value="2">Attended & Delivered</option>
                                                       </select></nobr>
                                                    </td>    
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;Time Table</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>
                                                      <nobr><select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:220px" onChange="getLabelClass(); return false;">
                                                                <option value="">Select</option> 
                                                                <?php 
                                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                    echo HtmlFunctions::getInstance()->getTimeTableLabelDate('','');?>
                                                      </select></nobr>
                                                   </td>    
                                                   <td class="contenttab_internal_rows"><nobr><strong>&nbsp;Degree</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows" colspan="2"><nobr><strong>
        <select size="1" class="htmlElement" name="degreeId" id="degreeId" style="width:320px;" onchange="hideResults(); getSubjectClasses(); ">
                                                   <option value="">Select</option>      
                                                </select></nobr>
                                                    </td>
                                                  </tr>  
                                                  <tr style="display:''" id='filter1'> 
                                                    <td class="contenttab_internal_rows"><nobr><strong>Subject</strong><nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>
          <select size="1" class="htmlElement" style="width:170px;" name="subjectId" id="subjectId" onchange="hideResults(); getSubjectGroups(); return false;" >
                                                           <option value="">Select</option>      
                                                </select>
                                                </td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;Group</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><strong>
                      <select name="groupId"  class="htmlElement" id="groupId" style="width:220px;" onchange="hideResults();" >
                                                          <option value="">Select</option>      
                                                        </select>
                                                    </td>
                                                    <td class="contenttab_internal_rows" id='sortingFormat1' align="right"><nobr><strong>&nbsp;Sort</strong></nobr></td>
                                                    <td class="contenttab_internal_rows" id='sortingFormat2' align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                                    <td class="contenttab_internal_rows" id='sortingFormat3' align="left" colspan="5">
                                                      <nobr>
                                                        <table width="10%" cellspacing="0px" cellpadding="0px" border="0" align="left">      
                                                         <tr>
                                                             <td class="contenttab_internal_rows" align="left"><nobr>
                                                                <select size="1" class="inputbox1" name="sortField1" id="sortField1" style="width:150px">
                                                                    <option value="rollNo">Roll No.</option> 
                                                                    <option value="universityRollNo">Univ. Roll No.</option>
                                                                    <option value='studentName'>Student Name</option> 
                                                                </select>
                                                                </nobr>
                                                              </td>  
                                                              <td class="contenttab_internal_rows" style="padding-left:15px;" align="left"><nobr><strong>Order</strong></nobr></td>
                                                              <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                              <td class="contenttab_internal_rows" align="left"><nobr>  
                                                                <input type="radio" name="sortOrderBy1" id="sortOrderBy1" value="ASC"  checked="checked" onclick="hideResults();" />Asc.&nbsp;
                                                                <input type="radio" name="sortOrderBy1" id="sortOrderBy2" value="DESC" onclick="hideResults();" />Desc.
                                                                </nobr>
                                                              </td>
                                                              
                                                         </tr> 
                                                        </table>
                                                      </nobr>
                                                    </td>      
                                                  </tr> 
                                                  <tr><td  height="5px"></td>
                                                  </tr>
                                                  <tr style="display:'';" id='reportTypeFilter'> 
                                                    <td class="" colspan="10" align='left'><nobr><nobr>  
                                                       <table width="90%" cellspacing="0px" cellpadding="0px" border="0" > 
                                                         <tr>
                                                            <td class="contenttab_internal_rows" ><nobr><strong>Show Attendance From</strong></nobr></td> 
                                                            <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><strong>
                                                                <?php 
                                                                     require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                                     echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                                                                ?>
                                                            </td>
                                                            <td class="contenttab_internal_rows" ><nobr><strong>&nbsp;To</strong></nobr></td> 
                                                            <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><strong>
                                                                <?php 
                                                                     require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                                     echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                                                                ?>
                                                            </td>
                                                            <td class="contenttab_internal_rows" align="right" style="padding-left:10px"><nobr><strong>&nbsp;%age</strong></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><strong>
                                                                <select class="inputbox1" name="average" id="average" size="1" style="width:70px">
                                                                         <option value="2">Below</option>
                                                                         <option value="1">Above</option>
                                                                         <option value="3">Equal</option>
                                                                    </select>
                                                                    &nbsp;&nbsp;
                                                                    <input type="text" name="percentage" id="percentage" style="width:30px" value="<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD'); ?>" maxlength="3"/> %
                                                            </td>
                                                       <td class="padding" align="right" style="padding-left:10px"></td>    
                                                      <td class="" colspan="10" align='left'><nobr><nobr>  
                                                       <table width="10%" cellspacing="0px" cellpadding="0px" border="0" > 
                                                         <tr>
                                                            <td  colspan="2" class="contenttab_internal_rows" ><nobr><strong>Include:</strong></nobr></td>
                                                         </tr>
                                                         <tr>
                                                            <td class="contenttab_internal_rows" ><nobr>
                                                               <input type="checkbox" id="incDutyLeave" name="incDutyLeave" checked="checked" value="1" ></nobr>
                                                            </td>
                                                            <td class="contenttab_internal_rows" align="left"><nobr>&nbsp;Duty Leave(DL)</nobr></td>
                                                         
                                                            <td class="contenttab_internal_rows" ><nobr>
                                                               <input type="checkbox" id="incMedicalLeave" name="incMedicalLeave" checked="checked" value="1" ></nobr>
                                                            </td>
                                                            <td class="contenttab_internal_rows" align="left"><nobr>&nbsp;Medical Leave(ML)</nobr></td>
                                                         
                                                        
                                                            <td class="contenttab_internal_rows" ><nobr>
                                                               <input type="checkbox" id="incAll" name="incAll" checked="checked" value="1" >
                                                               </nobr>
                                                            </td>
                                                            <td class="contenttab_internal_rows" align="left"><nobr>&nbsp;All Subject Attendance</nobr></td>
                                                         </tr>
                                                       </table>
                                                      </td>
                                                            <td class="padding" align="right" style="padding-left:5px"><nobr><strong>
                                                                &nbsp;&nbsp;<input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form); return false;" />
                                                            </td>
                                                         </tr>  
                                                       </table>   
                                                    </td> 
                                                  </tr>
                                                </table>  
                                             </td>   
                                           </tr>  
                                           <tr>
												<td class="contenttab_internal_rows" align="left">
													<font color="red"><b><u>Please Note:</u>&nbsp;</b></font><br>
													<font color="red">1. Medical Leaves are ONLY applicable in the Consolidated Reports i.e. when Group : "All".</font><br/>
													<font color="red">2. Medical Leaves are counted in the Aggregate ONLY if (Total Attendance + Duty Leaves) lie between <?php echo $sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT'); ?>% and <?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>% </font>
												</td>
											</tr>
                                           <tr id='showSubjectEmployeeList' style='display:none;'>  
                                             <td class="contenttab_internal_rows" colspan="20"><nobr>
                                              <table width="100%">
                                                <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" >
                                                     <b><a href="" class="link" onClick="getShowDetail(); return false;" >
                                                       <Label id='idSubjects'>Show Subject & Teacher Details</label></b></a>
                                                       <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                                  </td>
                                                 </tr> 
                                                 <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" id='showSubjectEmployeeList11'>
                                                    <nobr><span id='subjectTeacherInfo'></span></nobr>
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
                                <td class="" >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
                                        <tr>
                                            <td colspan="1" nowrap width="30%" class="content_title">Percentage wise Attendance Details : </td>
                                            <td colspan="1" nowrap class="" valign="top" width="45%" style="font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px; font-weight:bold;" >
                                               <div id="note" style='display:""'>
                                                 <table width="80%" border="0" cellspacing="0" cellpadding="0"  class="">
                                                   <tr>                                                                     
                                                      <td nowrap align="left" valign="top">&nbsp;&nbsp;&nbsp;</td>
                                                      <td nowrap align="left" valign="top">Attended/Delivered</td>
                                                      <td nowrap align="left" valign="top">&nbsp;:&nbsp;</td>
                                                      <td nowrap align="left" valign="top">13/20</td>
                                                   </tr>
                                                   <tr>   
                                                      <td nowrap align="left" valign="top">&nbsp;</td>                            
                                                      <td nowrap align="left"  valign="top">Duty Leaves(DL)&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                      <td nowrap align="left" valign="top">&nbsp;:&nbsp;</td>
                                                      <td nowrap align="left" valign="top">2</td>
                                                   </tr>
                                                   <tr>   
                                                      <td nowrap align="left" valign="top">&nbsp;</td>                            
                                                      <td nowrap align="left"  valign="top">Medical Leaves(ML)&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                      <td nowrap align="left" valign="top">&nbsp;:&nbsp;</td>
                                                      <td nowrap align="left" valign="top">1</td>
                                                   </tr>
                                                   <tr>      
                                                      <td nowrap valign="top">&nbsp;</td>                            
                                                      <td nowrap align="left"  valign="top">Percentage</td>
                                                      <td nowrap align="left" valign="top">&nbsp;:&nbsp;</td>
                                                      <td nowrap align="left" valign="top">80%&nbsp;&nbsp;i.e.&nbsp;((13+2+1)/20)*100</td>
                                                    </tr>
                                                 </table>
                                                 </span>
                                              </div>
                                            </td>
                                            <td colspan="1" nowrap class="content_title" align="right"  width="25%" >
                                                <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                     <div id="scroll2" style="overflow:auto; width:1100px; height:510px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:100%; vertical-align:top;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                               <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                               <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
//$History: studentPercentageWiseReportContents.php $
//
//*****************  Version 15  *****************
//User: Parveen      Date: 4/01/10    Time: 4:57p
//Updated in $/LeapCC/Templates/StudentReports
//heading updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 3/24/10    Time: 3:53p
//Updated in $/LeapCC/Templates/StudentReports
//condition format updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 3/22/10    Time: 2:22p
//Updated in $/LeapCC/Templates/StudentReports
//time table Label Id base check updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 2/16/10    Time: 10:42a
//Updated in $/LeapCC/Templates/StudentReports
//name updated (Show All Subject Marks)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 2/15/10    Time: 5:43p
//Updated in $/LeapCC/Templates/StudentReports
//added check box (Include All Students) and validation format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/26/09   Time: 5:06p
//Updated in $/LeapCC/Templates/StudentReports
//Duty Leave Base format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 11/16/09   Time: 3:20p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/13/09   Time: 9:54a
//Updated in $/LeapCC/Templates/StudentReports
//format updated all subjects view 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/06/09   Time: 5:50p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/06/09   Time: 10:37a
//Updated in $/LeapCC/Templates/StudentReports
//new column added report type base conditions updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/14/09   Time: 12:14p
//Updated in $/LeapCC/Templates/StudentReports
//CSV & Query Format updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/13/09   Time: 4:55p
//Updated in $/LeapCC/Templates/StudentReports
//link button updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/13/09   Time: 2:44p
//Updated in $/LeapCC/Templates/StudentReports
//consolidated & details report print
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Templates/StudentReports
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/08/08   Time: 11:48a
//Created in $/LeapCC/Templates/StudentReports
//student percentagewise report file added
//


?>
