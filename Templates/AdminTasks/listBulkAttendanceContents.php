<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
        <td valign="top" colspan="2">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <?php
               $recordCount = count($bulkAttLastTakenRecordArray);
               $dAttRecord="";
               $hStr="";
                if($recordCount >0 && is_array($bulkAttLastTakenRecordArray)){
                 for($i=0; $i<$recordCount; $i++ ) {    //if attendance taken for this subject
                   if($bulkAttLastTakenRecordArray[$i]['attendanceId']!=-1){
                     $hStr .= '<input  type="hidden" id="hi_'.strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectId']).'" value="Bulk Attendance Taken For '.strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectCode']).' On '.strip_slashes(UtilityManager::formatDate($bulkAttLastTakenRecordArray[$i]['dated'])).'" />';
                     if($dAttRecord==""){
                      $dAttRecord="Bulk Attendance Taken For ".strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectCode'])." On ".strip_slashes(UtilityManager::formatDate($bulkAttLastTakenRecordArray[$i]['dated']));
                     }
                    else{
                       $dAttRecord .="+~+"."Bulk Attendance Taken For ".strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectCode'])." On ".strip_slashes(UtilityManager::formatDate($bulkAttLastTakenRecordArray[$i]['dated']));
                     }
                   }
                  else{ //if attendance not taken for this subject
                        $hStr .= '<input  type="hidden" id="hi_'.strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectId']).'" value="Bulk Attendance Not Taken For '.strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectCode']).'" />';
                        if($dAttRecord==""){
                         $dAttRecord="Bulk Attendance Not Taken For ".strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectCode']);
                        }
                       else{
                           $dAttRecord .="+~+"."Bulk Attendance Not Taken For ".strip_slashes($bulkAttLastTakenRecordArray[$i]['subjectCode']);
                        }
                    }
                  }
                  echo $hStr;    //creates hidden elements
                }
            ?>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">

                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title" width="72%">Bulk Attendance : </td>
                        <td class="content_title_scroll" align="right" style="padding-right:3px" >
                         <!--<input type="image" name="imageField5" id="imageField5" onClick="getAttendanceHistory();return false" src="<?php echo IMG_HTTP_PATH;?>/attendance_history.gif" />-->
                         <input type="image" name="imageField5" id="imageField5" onClick="fetchAttendanceHistory(0);return false" src="<?php echo IMG_HTTP_PATH;?>/attendance_history.gif" />
                        </td>
                        <!--<td class="content_title"><norb>In Date Range</nobr></td>
                        <td><input type="checkbox" name="includeDateRange" id="includeDateRange" value="0" /></td>-->
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                 <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table </b></nobr></td>
                        <td class="padding" align="left" style="padding-right:10px;"><nobr>:
                        <select size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="autoPopulateEmployee(this.value);">
                         <option value="">Select</option>
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                         ?>
                        </select></nobr></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Teacher </b></nobr></td>
                        <td class="padding" align="left" colspan="4"> :
                        <!--<select size="1" name="employeeId" id="employeeId" class="selectfield" onChange="autoPopulateClass(this.value);" >-->
                        <select size="1" name="employeeId" id="employeeId" class="selectfield" onChange="getClassData();" >
                         <option value="">Select</option>
                        </select></td>
                    </tr>
                    <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>From Date</b></nobr></td>
                        <td width="20%" class="padding"  align="left">:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'),1);
                         ?>
                        </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>To Date</b></nobr></td>
                        <td width="15%" class="padding"  align="left" colspan="4">:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'),2);
                         ?>
                         <a class="contenttab_internal_rows" href="javaScript:void(0);" onclick="fetchAttendanceHistory(1);" title="Show Attendance History In Selected Date Range">Show Attendance History In Selected Date Range</a>
                        </td>
                    </tr>
                    <tr>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding" align="left">:&nbsp;<select size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                        </select></td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding" align="left" style="padding-right:10px;"><nobr>:&nbsp;<select size="1" class="selectfield" name="subject" id="subject" onchange="topicPopulate(this.value);populateGroups(document.searchForm.classId.value,this.value);">
                        <option value="">Select Subject</option>
                        </select></nobr>
                      </td>
                        <td width="7%" class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>:&nbsp;<select size="1" class="selectfield" name="group" id="group" onchange="clearData(5);" >
                        <option value="">Select Group</option>
                        </select></nobr>
                      </td>
                      <td align="left" style="padding-left:5px" align="left"><nobr>&nbsp;
                          <input type="image" name="imageField1" id="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></nobr>
                      </td>
                    </tr>
                     <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Lectures Delivered</b></nobr></td>
                     <td width="20%" class="padding"  align="left">:
                       <!--<input type="text" id="lectureDelivered" name="lectureDelivered" class="inputbox" style="width:50px" onkeyup="checkLectureDelivered(this.value);">-->
                       <input type="text" id="lectureDelivered" name="lectureDelivered" class="inputbox" style="width:50px">
                       &nbsp;
                       <input type="image" name="imageField1" style="margin-bottom: -5px;" id="imageField1" onClick="checkLectureDelivered(document.searchForm.lectureDelivered.value);return false" src="<?php echo IMG_HTTP_PATH;?>/update.gif" />
                     </td>
					 <td width="5%" class="contenttab_internal_rows"><nobr><b>Comments </b></nobr></td>
						 <td width="40%" class="padding"  align="left" colspan="4"><nobr>:
							 <input type="text" name="commentTxt" id="commentTxt" maxlength="255" style="width:89%;font-family: Arial, Helvetica, sans-serif;  font-size: 12px; border: 1px solid #c6c6c6; height:20px; border: 1px solid #c6c6c6;"/></nobr>
						 </td>
                    </tr>

					 <tr>
						 <td width="4%" class="contenttab_internal_rows"><nobr><b>Topics Taught</b></nobr></td>
						 <td width="20%" class="padding"  align="left" colspan="6">:
						  <select id="topicsId" class="inputbox" multiple="multiple" size="5" name="topicsId[]" style="vertical-align:middle;width:800px;" >
							<!--<option value="">Select</option>-->
						  </select>
						 </td>

                    </tr>
					<tr>
					<td colspan=7 align="center" name="attendanceStatus" id="attendanceStatus" class="attendanceMessage"></td>
					</tr>

                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="listFrm" >
                 <div id="results">
                </div>
                <!--Do Not Delete-->
                   <input type="hidden"  name="lcep" id="lcep" value="1">
                   <input type="hidden"  name="lcep" id="lcep" value="1">
				   <input type="hidden" name="taught" id="taught">
                  <!--Do Not Delete-->
                </form>
             </td>
          </tr>
          <tr><td   height="5px"></td></tr>
          <tr><td  align="center" >
            <div id="divButton" style="display:none">
                  <input type="image" name="imageField2" id="imageField2" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm(this.form);return false;" />
                  <input type="image" name="imageField3" id="imageField3" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onClick="resetForm();return false;" />
            </div>
         </td></tr>
          </table>
        </td>
    </tr>

    </table>
    </td>
    </tr>
    </table>

<!--Start history  Div-->
<?php floatingDiv_Start('AttendanceHistoryDiv','Attendance History',4,' '); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px"></td></tr>
    <tr>
     <td valign="top">
 	  <div id="scroll2" style="overflow:auto; width:1000px; height:510px; vertical-align:top;">
         <div id="historyResults" style="width:98%; vertical-align:top;"></div>
      </div>
      </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="1">
      <!--<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif"  onclick="javascript:hiddenFloatingDiv('AttendanceHistoryDiv');return false;" />-->
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/print.gif"  onclick="printReport();return false;" />
      &nbsp;<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/excel.gif"  onclick="printCSV();return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
<?php floatingDiv_End(); ?>
<!--End history  Div-->

<?php
// $History: listBulkAttendanceContents.php $
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 12  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/AdminTasks
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 26/11/09   Time: 11:30
//Updated in $/LeapCC/Templates/AdminTasks
//Made enhancements in Attendance History : Teacher can now view other
//teachers attendance and also edit & delete them,if they have the same
//time table allocation.
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 17/11/09   Time: 17:37
//Updated in $/LeapCC/Templates/AdminTasks
//Updated image for auto calculating lecture delivered and attended
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 16/11/09   Time: 18:11
//Updated in $/LeapCC/Templates/AdminTasks
//Modified UI of bulk attendance module
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:08
//Updated in $/LeapCC/Templates/AdminTasks
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 12/11/09   Time: 17:21
//Updated in $/LeapCC/Templates/AdminTasks
//Modified logic in bulk attendance module and corrected flaws in coding
//and removed check which prevents taking attendance across months or
//years.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/11/09    Time: 16:48
//Updated in $/LeapCC/Templates/AdminTasks
//Added "Attendance History" option in bulk attendance from admin section
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Templates/AdminTasks
//Added code for "Time table adjustment"
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/09/09   Time: 5:34p
//Updated in $/LeapCC/Templates/AdminTasks
//fixed bug nos.0001748, 0001749, 0001747, 0001746, 0001745, 0001744,
//0001742, 0001731
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/06/09   Time: 10:37
//Updated in $/LeapCC/Templates/AdminTasks
//Done GNIMT enhancements as on 26.06.2009
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 14:13
//Updated in $/LeapCC/Templates/AdminTasks
//Modified  field heading (lecture delivered to lectures delivered &
//topic to topics taught)
//
//*****************  Version 1  *****************
//User: Administrator Date: 11/06/09   Time: 16:01
//Created in $/LeapCC/Templates/AdminTasks
//Created "Bulk Attendance" modules in admin section in leapcc
//
//*****************  Version 6  *****************
//User: Administrator Date: 8/06/09    Time: 17:58
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "lecture delivered" column in attendance summary div in teacher
//login
//
//*****************  Version 5  *****************
//User: Administrator Date: 5/06/09    Time: 17:07
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "Attendance History" option in teacher module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/04/09    Time: 16:09
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added class check during group populate
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified to show group by selecting subject
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/12/09    Time: 11:49a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified the files for topics taught
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 9/16/08    Time: 12:58p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/03/08    Time: 5:05p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/26/08    Time: 12:35p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/25/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/18/08    Time: 5:39p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/14/08    Time: 2:59p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/05/08    Time: 4:51p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/25/08    Time: 11:52a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/18/08    Time: 11:30a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/16/08    Time: 7:12p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>