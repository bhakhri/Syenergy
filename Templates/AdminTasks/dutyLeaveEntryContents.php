<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Activities&nbsp;&raquo;&nbsp;Marks and Attendance&nbsp;&raquo;&nbsp;Student Duty Leaves</td>
                <td valign="top" align="right">
                 <!-- 
                <form action="" method="" name="searchForm">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                   --> 
                  </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Duty Leave Details : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm" id="searchForm" onsubmit="return false;"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table</b></nobr></td>
                        <td class="padding" align="left">: 
                        <select size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="autoPopulateEmployee(this.value);">
                         <option value="">Select</option>
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData('0');
                         ?>
                        </select></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Teacher</b></nobr></td>
                        <td class="padding" colspan="3" align="left">: 
                        <select size="1" name="employeeId" id="employeeId" class="selectfield" onChange="autoPopulateClass(this.value);" >
                         <option value="">Select</option>
                        </select></td>
                    </tr>
                    <tr>    
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td class="padding" align="left">: 
                        <select size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                      </select></td>
                      <td class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                      <td class="padding"><nobr>: 
                      <select size="1" class="selectfield" name="subject" id="subject" onchange="populateGroups(document.searchForm.classId.value,this.value);" >
                        <option value="">Select Subject</option>
                        </select></nobr>
                      </td>
                      <td  style="padding-left:10px" class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                      <td class="padding" align="left">: 
                      <select size="1" class="selectfield" name="group" id="group" onchange="clearData(5);" >
                        <option value="">Select Group</option>
                        </select>
                      </td>
                     </tr>
                     <tr> 
                      
                      <td  class="contenttab_internal_rows"><nobr><b>Roll No.</b></nobr></td>
                      <td class="padding" colspan="2" align="left"><nobr>: 
                       <input type="text" name="rollNo" id="rollNo" autocomplete='off' class="inputbox"  style="width:260px"/></nobr>
                      </td>
                       <td  align="left" style="padding-left:15px;padding-right:5px" colspan="3">
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
                    </tr>
                  
                    <tr><td colspan="5" height="5px"></td><td width="41%"></td>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top">&nbsp;
                 <div id="results">
                </div>
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<select name="inLeaveType" id="inLeaveType" style="display:none">
<option value="">Select</option>
<?php
//Get Leave Type Lists
  require_once(BL_PATH.'/HtmlFunctions.inc.php');
  echo HtmlFunctions::getInstance()->getAttendanceCodeData(' WHERE  showInLeaveType=1');
?>
</select>
<!--Start Add Div-->
<?php floatingDiv_Start('dutyLeaveDiv','Add/Edit Duty Leave Details'); ?>
    <form name="dutyLeaveForm" action="" method="post" onsubmit="return false;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="studentId" id="studentId" value="" />
   
    <tr>
      <td class="padding"><b>Name :</b><div id="studentNameDiv" style="display:inline"></div></td>
    </tr>
   
    <tr>
      <td class="padding"><a href="javascript:addOneRow();" title="Add One Record"><b>Add One Record</b></a></td>
    </tr> 
   
    <tr>
     <td>
     <div id="containerDiv" style="height:200px;overflow:auto">
       <table id="resourceDetailTable" border="0" cellpadding="0" cellspacing="2" style="width:100%;">
        <tbody id="resourceDetailTableBody">
         <tr  class="rowheading">
          <th align="right" width="3%">#</th>
          <th width="20%">Date</th>
          <th width="20%" align="left">Duty Leave Type</th>
          <th width="20%" align="left">Comments</th>
          <th align="right" width="2%" class="searchhead_text">Del.</th>
       </tr>
      </tbody>
    </table>
    </div>
     </td>    
   </tr>
   
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('dutyLeaveDiv');return false;" />        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->    
<?php
// $History: dutyLeaveEntryContents.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/AdminTasks
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/AdminTasks
//added code for autosuggest functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 26/06/09   Time: 12:28
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//bug ids---
//0000302 to 0000307
//
//*****************  Version 3  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Templates/AdminTasks
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Administrator Date: 27/05/09   Time: 12:32
//Updated in $/LeapCC/Templates/AdminTasks
//Added "comments" field in duty leave module in admin & teacher section
//
//*****************  Version 1  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Created in $/LeapCC/Templates/AdminTasks
//Created "Duty Leave" Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/05/09   Time: 18:58
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Duty Leave" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Grace Marks Master"
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified to show group by selecting subject
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:49p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/08/08    Time: 11:45a
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
?>