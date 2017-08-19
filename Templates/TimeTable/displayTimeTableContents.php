<?php 
//it contain the template of time table 
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
 <form name="allDetailsForm" action="" method="post" onSubmit="return false;">     
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
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border2">
                    <table width="10%" border="0" cellspacing="0px" cellpadding="10px" align="center">
                    <tr>
						<td class="contenttab_internal_rows" valign="middle">
                                <nobr><b>Time Table</b></nobr>
                         </td>
                         <td class="contenttab_internal_rows" valign="middle"><b>&nbsp;:&nbsp;<b></td>
                         <td class="contenttab_internal_rows" valign="middle">
                            <select size="1" class="htmlElement2" name="timeTableLabelId" id="timeTableLabelId"  style='width:150px' onChange="refreshTimeTableData();">
					        <option value="">Select</option>
					        <?php
					          require_once(BL_PATH.'/HtmlFunctions.inc.php');
					          echo HtmlFunctions::getInstance()->getTimeTableLabelDate();
					        ?>
				            </select>
                         </td>
                          <td class="contenttab_internal_rows" valign="middle">
                            <nobr><b>&nbsp;Report Result Type</b></nobr>
                          </td> 
                          <td class="contenttab_internal_rows" valign="middle"><b>&nbsp;:&nbsp;<b></td>
                          <td class="contenttab_internal_rows" valign="middle">
                            <select size="1" class="htmlElement2" name="reportResult" style='width:120px' id="reportResult" onChange="hideDetails();">
                                <option value="">Select</option>
                                    <option value="className">Class</option> 
                                    <option value="employeeName">Teacher</option>
                                    <option value="subjectName">Subject</option>
                                    <option value="roomName">Room</option>
                                    <option value="groupShort">Groups</option>
                                    <option value="weekWise">Weekwise</option>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows" valign="middle">
                               <span id="timeTableViewId">
                                   <table width="10%" border="0" cellspacing="0px" cellpadding="0px" align="left">    
                                      <tr>
                                        <td class="contenttab_internal_rows" valign="middle">  
                                            <nobr><b>&nbsp;View</b></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" valign="middle"><b>&nbsp;:&nbsp;<b></td>
                                        <td class="contenttab_internal_rows" valign="middle">
                                            <select size="1" class="htmlElement2" name="reportView" style='width:150px' id="reportView" onChange="hideDetails();">
                                            <?php
                                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                              echo HtmlFunctions::getInstance()->getTimeTableView();
                                            ?>
                                            </select>
                                        </td>
                                        </tr>
                                   </table>
                               </span>       
                           </td> 
                            <td class="contenttab_internal_rows" valign="middle">
                               <span id="timeTableTypeId">
                                   <table width="10%" border="0" cellspacing="0px" cellpadding="0px" align="left">    
                                      <tr>
                                        <td class="contenttab_internal_rows" valign="top" height="10px" ><nobr><b></nobr></b></td>
                                        <td class="contenttab_internal_rows" ><nobr>
                                            <strong>&nbsp;From Date</strong></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>    
                                        <td class="contenttab_internal_rows" align="left"><nobr>
                                            <?php 
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                               echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                            ?></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" align="left"><nobr>
                                           <strong>&nbsp;To Date</strong></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows" align="left" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                        <td class="contenttab_internal_rows" align="left"><nobr>
                                           <?php 
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                               echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                                            ?></nobr>
                                        </td>
                                      </tr>
                                   </table>
                               </span>       
                           </td>
                        </tr>
                   </table>
                   <table width="100%" border="0" cellspacing="5px" cellpadding="0px" align="center">
                        <tr>
                            <td class="contenttab_internal_rows" valign="top" height="10px" ><nobr><b></nobr></b></td>
                        </tr>
                        <tr>
                        <td width="1%" class="contenttab_internal_rows" valign="top"><nobr><b></nobr></b></td>
                        <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Class:</nobr></b></td>
                        <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Subject: </b></nobr></td>
                        <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Teacher: </b></nobr></td>
                        <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Room:</nobr></b></td>
                        <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Groups:</nobr></b></td>
                        <td width="16%" class="contenttab_internal_rows" valign="top"><nobr><b>Weeks:</nobr></b></td>
                        <td width="1%" class="contenttab_internal_rows" valign="top"><nobr><b></nobr></b></td>
                </tr>
                <tr>
                        <td width="1%" class="contenttab_internal_rows" valign="top"><nobr><b></nobr></b></td>
                        <td width="10%" class="contenttab_internal_rows"><nobr>
                            <select multiple name='classId[]' id='classId' size='5' class='inputbox1' style='width:160px' onChange="hideDetails();">
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getAllClassData();
                            ?>
                            </select><br>
                            <div align="left">
                            Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('classId[]','All','allDetailsForm');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('classId[]','None','allDetailsForm');">None</a>
                            </div></nobr>
                        </td>
                         
                        <td width="15%" class="contenttab_internal_rows" ><nobr> 
                        <select multiple name='subjectId[]' id='subjectId' size='5' class='htmlElement2' style='width:120px' onChange="hideDetails();">
                        </select><br>
                        <div align="left">
                        Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('subjectId[]','All','allDetailsForm');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('subjectId[]','None','allDetailsForm');">None</a>
                        </div></nobr>
                        </td>
                        <td  width="15%" class="contenttab_internal_rows"><nobr>
                        <select multiple name='employeeId[]' id='employeeId' size='5' class='htmlElement2' style='width:180px' onChange="hideDetails();">
                        </select><br>
                        <div align="left">
                        Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('employeeId[]','All','allDetailsForm');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('employeeId[]','None','allDetailsForm');">None</a>
                        </div></nobr>
                        </td>
                         
                        <td width="10%" class="contenttab_internal_rows"><nobr>
                            <select multiple name='roomId[]' id='roomId' size='5' class='htmlElement2' style='width:220px' onChange="hideDetails();">
                            </select><br>
                            <div align="left">                            
                            Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('roomId[]','All','allDetailsForm');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('roomId[]','None','allDetailsForm');">None</a>
                            </div></nobr>
                        </td>
                        
                        <td width="10%" class="contenttab_internal_rows"><nobr>
                            <select multiple name='groupId[]' id='groupId' size='5' class='htmlElement2' style='width:110px' onChange="hideDetails();">
                            </select><br>
                            <div align="left">
                            Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('groupId[]','All','allDetailsForm');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('groupId[]','None','allDetailsForm');">None</a>
                            </div></nobr>
                        </td>
                        
                        <td width="10%" class="contenttab_internal_rows" valign="top"><nobr>
                            <select multiple name='dayWeeks[]' id='dayWeeks' size='5' class='inputbox1' style='width:110px' onChange="hideDetails();">
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getDaysList();
                            ?>
                            </select><br>
                            <div align="left">
                            Select &nbsp;
                            <a class="allReportLink" href="javascript:makeSelection('dayWeeks[]','All','allDetailsForm');">All</a> / 
                            <a class="allReportLink" href="javascript:makeSelection('dayWeeks[]','None','allDetailsForm');">None</a></nobr>
                            </div>
                        </td>
                </tr>
                <tr>
                  <td  colspan="15" height="10px" align="center" valign="middle"></td>
                </tr>
                <tr>
                        <td  colspan="15" align="center" valign="middle">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return getTimeTableData();return false;"/>
                        </td>
                    </tr>
                    </table>                                                                  
                </td>
             </tr>
             <tr>
               <td height="10px"> </td>
            </tr>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr id='nameRow' style='display:none;'>
                        <td class="" height="20">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                        <tr>
                            <td class="content_title">Display Table Details :</td>
                            <td class="content_title" align="right">  
                                <nobr>
                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                <input type="image" id="generateCSV" id="generateCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV();" />&nbsp;
                                <!-- <input type="image" id="generateCSV1" id="generateCSV1" src="<?php echo IMG_HTTP_PATH;?>/export_as_document.gif" onClick="printReportDoc();" />&nbsp;-->
                                </nobr> 
                            </td>                                        

                        </tr>
                        </table>
                        </td>
                        </tr>
                        <tr id='resultRow' style='display:none;'>
                        <td colspan='1' class='contenttab_row'>
                        <div id = 'results'> </div>
                        </td>
                        </tr>
                        <tr id='nameRow2' style='display:none;'>
                        <td class="" height="20">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                        <tr>
                            <td colspan="2" class="content_title" align="right">
                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                <input type="image" id="generateCSV" id="generateCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV();" />&nbsp;
                                <!-- <input type="image" id="generateCSV1" id="generateCSV1" src="<?php echo IMG_HTTP_PATH;?>/export_as_document.gif" onClick="printReportDoc();" />&nbsp; -->
                            </td>
                       </tr>
              </table>
            </td>
          </tr>
         </table>
           </td>
        </tr> 
      </table>
    </td>
  </tr>
  </table>
</form> 
<?php
//$History: displayTimeTableContents.php $
//
//*****************  Version 15  *****************
//User: Parveen      Date: 4/21/10    Time: 12:22p
//Updated in $/LeapCC/Templates/TimeTable
//document button commetout
//
//*****************  Version 14  *****************
//User: Parveen      Date: 4/20/10    Time: 6:30p
//Updated in $/LeapCC/Templates/TimeTable
//document button hide
//
//*****************  Version 13  *****************
//User: Parveen      Date: 4/20/10    Time: 5:03p
//Updated in $/LeapCC/Templates/TimeTable
//showTimeTable function (daily & weekly base format udpated)
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/19/10    Time: 4:47p
//Updated in $/LeapCC/Templates/TimeTable
//reportType base code updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/19/10    Time: 2:43p
//Updated in $/LeapCC/Templates/TimeTable
//daily/weekly base look & feel updated (timeTableDate function added)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/22/10    Time: 3:35p
//Updated in $/LeapCC/Templates/TimeTable
//look & feel updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/21/09   Time: 12:49p
//Updated in $/LeapCC/Templates/TimeTable
//csv & document button added
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/19/09   Time: 2:19p
//Updated in $/LeapCC/Templates/TimeTable
//csv format updated
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/10/09   Time: 11:26a
//Updated in $/LeapCC/Templates/TimeTable
//modification in function getEmployeesFromTimeTable() for HOD role 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/25/09    Time: 11:03a
//Updated in $/LeapCC/Templates/TimeTable
//excel button replace export_as_document button
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/25/09    Time: 10:52a
//Updated in $/LeapCC/Templates/TimeTable
//formatting & alignment updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/19/09    Time: 4:14p
//Updated in $/LeapCC/Templates/TimeTable
//dayswise, classwise  time table show & Print & CSV file checks
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/18/09    Time: 5:35p
//Updated in $/LeapCC/Templates/TimeTable
//classwise time table show
//
//*****************  Version 2  *****************
//User: Administrator Date: 26/05/09   Time: 11:28
//Updated in $/LeapCC/Templates/TimeTable
//Changed showlist button to show
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/15/09    Time: 3:53p
//Created in $/LeapCC/Templates/TimeTable
//file added
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/13/09    Time: 4:35p
//Updated in $/Leap/Source/Templates/ScTimeTable
//function base time table update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/08/09    Time: 6:46p
//Updated in $/Leap/Source/Templates/ScTimeTable
//report format change 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/06/09    Time: 12:53p
//Updated in $/Leap/Source/Templates/ScTimeTable
//Spelling Update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/06/09    Time: 12:11p
//Updated in $/Leap/Source/Templates/ScTimeTable
//Display Message Settings (Display Multiple Utility Time Table)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/06/09    Time: 11:48a
//Updated in $/Leap/Source/Templates/ScTimeTable
//subject dropdown settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/04/09    Time: 7:11p
//Created in $/Leap/Source/Templates/ScTimeTable
//file added
//

?>