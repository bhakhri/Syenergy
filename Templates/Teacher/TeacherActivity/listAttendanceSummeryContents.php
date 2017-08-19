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
               <!-- <td valign="top">Marks & Attendance &nbsp;&raquo;&nbsp; Display Attendance Summary </td> -->
			    <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
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
                        <td class="content_title">Display Attendance Summary : </td>
                        <td align="right" valign="middle"><div id="printDiv1" style="display:none"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printAttendanceReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="csvAttendanceReport();return false;"/></div></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm" id="searchForm" onsubmit="return false;"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table: </b></nobr></td>
                        <td class="padding" align="left">
                        <select size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="autoPopulateClass(this.value);">
                         <option value="">Select</option>
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData('0');
                         ?>
                        </select></td>
                    </tr>
                    <tr>    
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Class: </b></nobr></td>
                        <td class="padding" align="left">
                        <select size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                      </select></td>
                      <td  style="padding-left:10px" class="contenttab_internal_rows" style="padding-left:10px"><nobr><b>Subject: </b></nobr></td>
                      <td class="padding">
                      <select size="1" class="selectfield" name="subject" id="subject" onchange="populateGroups(document.searchForm.classId.value,this.value);" >
                        <option value="">Select Subject</option>
                        </select>
                      </td>
                      <td  style="padding-left:10px" class="contenttab_internal_rows"><nobr><b>Group: </b></nobr></td>
                      <td class="padding" align="left">
                      <select size="1" class="selectfield" name="group" id="group" onchange="clearData(5);" >
                        <option value="">Select Group</option>
                        </select>
                      </td>
                      <td  align="left" style="padding-left:5px" >
                        <input type="image" id="imageField1" name="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                     </td>
                     </tr>
                    </table>
                    </form>
                </td>
             </tr>
               <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <div id="totalDelivered"></div>
                 <div id="finalMarksResultDiv">
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
<?php
// $History: listAttendanceSummeryContents.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 19:24
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Attendance Summary" module in teacher login
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 16:14
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Test Summary" module in teacher login
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 11:18
//Created in $/LeapCC/Templates/AdminTasks
//Created "Test Marks" module in admin section
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