<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(BL_PATH.'/helpMessage.inc.php');

require_once(BL_PATH.'/HtmlFunctions.inc.php');
$attendanceCodeDataString= HtmlFunctions::getInstance()->getAttendanceCodeData();
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">

            <?php
               $recordCount = count($dailyAttLastTakenRecordArray);
               $dAttRecord="";
               $hStr="";
                if($recordCount >0 && is_array($dailyAttLastTakenRecordArray)){
                   for($i=0; $i<$recordCount; $i++ ) {
                   if($dailyAttLastTakenRecordArray[$i]['attendanceId']!=-1){
                    $hStr .= '<input  type="hidden" id="hi_'.strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectId']).'" value="Daily Attendance Taken For '.strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectCode']).' On '.strip_slashes(UtilityManager::formatDate($dailyAttLastTakenRecordArray[$i]['dated'])).'" />';
                    if($dAttRecord==""){
                     $dAttRecord="Daily Attendance Taken For ".strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectCode'])." On ".strip_slashes(UtilityManager::formatDate($dailyAttLastTakenRecordArray[$i]['dated']));
                    }
                   else{
                       $dAttRecord .="+~+"."Daily Attendance Taken For ".strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectCode'])." On ".strip_slashes(UtilityManager::formatDate($dailyAttLastTakenRecordArray[$i]['dated']));
                    }
                 }
                else{
                    $hStr .= '<input  type="hidden" id="hi_'.strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectId']).'" value="Daily Attendance Not Taken For '.strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectCode']).'" />';
                    if($dAttRecord==""){
                     $dAttRecord="Daily Attendance Not Taken For ".strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectCode']);
                    }
                   else{
                       $dAttRecord .="+~+"."Daily Attendance Not Taken For ".strip_slashes($dailyAttLastTakenRecordArray[$i]['subjectCode']);
                    }
                }
              }
              echo $hStr;    //creates hidden elements
            }
            ?>
            <tr>

                <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>

               <!-- <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Daily Attendance</td>
                <td valign="top" align="right">&nbsp;

                </td>
-->
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
                        <td class="content_title" width="80%">Extra Classes Conducted By Faculty : </td>
                        <td style="padding-right:10px" align="right" class="content_title">
                           <a href="#" onclick="getHelpImageDownLoad('teacher-daily-attendance.jpg','TeacherDailyAttendance'); return false;" name="">Help</a>
                        </td>
                        <td class="content_title_scroll" align="right" style="padding-right:3px" >
                        <input type="image" name="imageField5" id="imageField5" onClick="showWaitDialog(true);window.setTimeout(getAttendanceHistory, 1);return false" src="<?php echo IMG_HTTP_PATH;?>/attendance_history.gif" />
                          <script type="text/javascript">
                            window.onload=function(){
                                //scroll_init("<?php echo $dAttRecord; ?>");
                                init();
                            }
                          </script>
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                 <?php
                  if($roleId!=2){
                  ?>
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
                        <select size="1" name="employeeId" id="employeeId" class="selectfield" onChange="clearData(2);getClassData();" >
                         <option value="">Select</option>
                        </select></td>
                    </tr>
                  <?php
                   }
                  ?>
                    <tr>
                       <td width="10%" class="contenttab_internal_rows"><nobr><b>Attendance Date</b></nobr>
                       <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Schedule',HELP_DAILY_ATTENDANCE_SCHEDULE);     ?>
                       </td>
                       <td width="20%" class="padding"  align="left" valign="top">:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            $toDate=date('Y')."-".date('m')."-".date('d');
                            echo HtmlFunctions::getInstance()->datePicker('forDate',$toDate);
                         ?> &nbsp;<input type="image" name="imageField5" id="imageField5" onClick="getAttendanceOptions();return false" src="<?php echo IMG_HTTP_PATH;?>/schedule.gif" style="margin-bottom: -5px;" />&nbsp;<?php

                         ?>
                        </td>
                        <td width="5%" class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Class<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Class',HELP_DAILY_ATTENDANCE_CLASS);
                         ?></b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select size="1" class="selectfield" name="class" id="class" onchange="populateSubjects(this.value);getPeriodNames();groupPopulate(this.form.subject.value);" >
                        <option value="">Select Class</option>
                        <?php
                        if($roleId==2){
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        }
                        ?>
                      </select></nobr></td>
                        <td width="5%" class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Subject<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_DAILY_ATTENDANCE_SUBJECT);
                         ?></b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select size="1" class="selectfield" name="subject" id="subject" onchange="getPeriodNames();setScroller(this.value);topicPopulate(this.value);groupPopulate(this.value);" >
                        <option value="">Select Subject</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                    </tr>
                    <tr>
                     <td width="10%" class="contenttab_internal_rows" ><nobr><b>Group<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Group',HELP_DAILY_ATTENDANCE_GROUP);
                         ?></b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>: <select size="1" class="selectfield" name="group" id="group"  onchange="getPeriodNames();">
                        <option value="">Select Group</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select></nobr>
                      </td>
                     <td width="5%" class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Period<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Period',HELP_DAILY_ATTENDANCE_PERIOD);
                         ?></b></nobr></td>
                     <td width="20%" class="padding" align="left"><nobr>:
                     <select size="1" class="selectfield" name="period" id="period" onchange="document.getElementById('commentTxt').value='';" >
                        <option value="">Select Period</option>
                          <?php
                          // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          // $daysofWeek=date('w'); //gievs day of week
                           //if($daysofWeek==0){ $dayofWeek=7;} //we consider sunday as 7
                           //echo HtmlFunctions::getInstance()->getTeacherPeriodData(" AND daysOfWeek=".$daysofWeek);
                        ?>
                        </select></nobr>
                      </td>
                        <td>&nbsp;</td>
                        <td style="padding-left:15px"  align="left" >
                         <input type="image" name="imageField1" id="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/list_students.gif" />
                        </td>
                    </tr>
                    <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Default Att. Code<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Default Attendance Code',HELP_DAILY_ATTENDANCE_DEFAULTATTCODE);
                         ?></b></nobr></td>
                     <td width="20%" class="padding" align="left"><nobr>:
                     <select size="1" class="selectfield" name="defaultAttCode" id="defaultAttCode" onchange="makeDefaultAttCode();"  onblur="makeDefaultAttCode();">
                        <option value="">Select Att.Code</option>
                          <?php
                            //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            //echo HtmlFunctions::getInstance()->getAttendanceCodeData();
                            echo $attendanceCodeDataString;
                        ?>
                      </select></nobr>
                      </td>
					  <td  class="contenttab_internal_rows" style="vertical-align:middle;padding-left:5px"><nobr><b>Comments<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Comments',HELP_DAILY_ATTENDANCE_COMMENTS);
                         ?> </b></nobr></td>
					  <td width="40%" class="padding" align="left" colspan="3" ><nobr>:&nbsp;<input type="text" name="commentTxt" id="commentTxt" maxlength="255" onkeydown="return sendKeys('commentTxt',event);" style="width:97%;font-family: Arial, Helvetica, sans-serif;  font-size: 12px; border: 1px solid #c6c6c6; height:20px; border: 1px solid #c6c6c6; vertical-align:middle;" /></nobr>
					  </td>

					</tr>
					 <tr>
						 <td width="4%" class="contenttab_internal_rows" ><nobr><b>Topics<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Topics',HELP_DAILY_ATTENDANCE_TOPICS);
                         ?> </b></nobr></td>
						 <td width="20%" class="padding"  align="left" colspan="6"><nobr>:
						  <select id="topicsId" class="inputbox" multiple="multiple" size="7" name="topicsId[]" style="vertical-align:middle; width:860px;" >
							<!--<option value="">Select</option>-->
						  </select></nobr>
						 </td>

                    </tr>
					<tr>
					<td colspan=6 align="center" name="attendanceStatus" id="attendanceStatus" class="attendanceMessage"></td>
					</tr>
    <tr id='warningRow' style='display:none;'>
    <td class="contenttab_internal_rows" colspan="10"><nobr>
    <fieldset>
    <b><u>Warning</u></b>&nbsp;
    <font color="red"><span id='warningMsg'></span> </font><br>
    </fieldset></nobr>
    </td>
    </tr>
                    <tr>
                     <td class="contenttab_internal_rows" colspan="2"><nobr><b>To mark attendance for specific roll numbers only,&nbsp;<a onclick="openAttendanceShortCutDiv();" href="#" class="allReportLink" ><font color="blue" > Click Here </a></font></td>
                     <td class="padding" align="left" colspan="4" id="attendanceSummeryTdId"></td>
                    </tr>
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top">
                 <form name="listFrm" id="listFrm" style="display:inline">
                  <!--Do Not Delete-->
                  <input type="hidden"  name="mem" id="mem" value="1">
                  <input type="hidden"  name="mem" id="mem" value="1">
                  <input type="hidden" name="taught" id="taught">
                  <!--Do Not Delete-->
                <table id="divButton1" border="0" cellpadding="0" cellspacing="0" width="100%" height="30px" style="display:none">
                   <tr class="contenttab_border">
                     <td class="content_title" align="left">List of Students : </td>
                     <td align="right">
                     <input type="image" name="imageField44" id="imageField44" src="<?php echo IMG_HTTP_PATH;?>/mark_attendance.gif" onClick="return validateForm('listFrm');return false;" style="margin-bottom:-3px" />
                     <input type="image" name="imageField55" id="imageField55" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onClick="resetForm();return false;" style="margin-bottom:-3px" />
                   </td>
                  </tr>
                 <tr><td colspan="2">
                 <div id="results" style="vertical-align:top">
                </div>
                </td></tr>
               </table>
                </form>
             </td>
          </tr>
          <tr><td   height="5px"></td></tr>
          <tr><td  align="right" >
               <div id="divButton" style="display:none">
                  <input type="image" name="imageField2" id="imageField2" src="<?php echo IMG_HTTP_PATH;?>/mark_attendance.gif" onClick="return validateForm(this.form);return false;" />
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
<?php floatingDiv_Start('AttendanceHistoryDiv','Attendance History',3,' ','<a href="#" onclick="getHelpImageDownLoad(\'teacher-attendance-history.jpg\',\'DisplayDailyAttendanceHistory\'); return false;" >Help</a>'); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px"></td></tr>
    <!--<tr>
      <td style="padding-right:10px" align="right" class="content_title">
         <a href="#" onclick="getHelpImageDownLoad('teacher-attendance-history.jpg','DisplayDailyAttendanceHistory'); return false;" name="">Help</a>
      </td>
    </tr>
    -->

    <tr>
     <td>
      <div id="historyResults" style="vertical-align:top;width:920px;height:300px;overflow:auto">
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

<!--Start Attendance Help  Div-->
<?php floatingDiv_Start('AttendanceHelpDiv','Attendance Schedule',4); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px"></td></tr>
    <tr>
     <td>
      <div id="attendanceOptionsResults" style="width:345px;height:145px;overflow:auto">
      </td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="1">
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif"  onclick="javascript:hiddenFloatingDiv('AttendanceHelpDiv');return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
<?php floatingDiv_End(); ?>
<!--End history  Div-->


<!--Start Attendance Shortcut  Div-->
<?php floatingDiv_Start('AttendanceShortCutDiv','Mark Attendance for Roll Numbers',5); ?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
       <td class="contenttab_internal_rows" width="10%"><nobr><b>Enter Roll Numbers</b><br/>(Comma seperated)<br/>Eg. B090001,B090010,...</td>
       <td class="padding">:</td>
       <td class="padding" align="left" colspan="2"><nobr>
          <textarea name="rollNoTxt" id="rollNoTxt" cols="40" rows="5"></textarea>
          </nobr>
       </td>
   </tr>
   <tr>
       <td class="contenttab_internal_rows"><nobr><b>Attendance Code</b></td>
       <td class="padding">:</td>
       <td class="padding">
         <select size="1" class="selectfield" name="shortAttCode" id="shortAttCode">

              <?php
                echo $attendanceCodeDataString;
            ?>
          </select>

          <input type="image" name="imageField66" id="imageField66" style="margin-bottom: -5px;" onClick="makeAttendanceHelp();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
          </nobr>
         </td>
  </tr>
  <tr>
    <td align="center" style="padding-right:10px" colspan="4">
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AttendanceShortCutDiv');return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="4"></td></tr>
</table>
<?php floatingDiv_End(); ?>
<!--End history  Div-->

<!--Daily Attendance Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div>
            </td>
        </tr>
    </table>
</div>
<?php floatingDiv_End(); ?>
<!--Daily Attendance Help  Details  End -->

<?php
// $History: listDailyAttendanceContents.php $
//
//*****************  Version 27  *****************
//User: Dipanjan     Date: 19/04/10   Time: 16:35
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified javascript code to make comments text box empty upon changes
//of class,subject,group etc dropdowns
//
//*****************  Version 26  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "Daily Attenance" module in admin end
//
//*****************  Version 25  *****************
//User: Dipanjan     Date: 13/04/10   Time: 17:03
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done llrit enhancements
//
//*****************  Version 24  *****************
//User: Gurkeerat    Date: 12/04/09   Time: 5:46p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//updated look n feel of help dialog box
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 3/12/09    Time: 15:42
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made UI related changes in Attendance History
//
//*****************  Version 22  *****************
//User: Dipanjan     Date: 2/12/09    Time: 12:22
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made UI changes
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 26/11/09   Time: 11:30
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made enhancements in Attendance History : Teacher can now view other
//teachers attendance and also edit & delete them,if they have the same
//time table allocation.
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 25/11/09   Time: 18:40
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made enhancements : Teacher can view other teachers attendance and also
//edit & delete them,if they have the same time table allocation
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:08
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
//
//*****************  Version 18  *****************
//User: Parveen      Date: 11/10/09   Time: 11:26a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Help functionality implemented (Help Option On/Off)
//
//*****************  Version 17  *****************
//User: Parveen      Date: 11/06/09   Time: 1:20p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//div DisplayDailyAttendanceHistory help link added
//
//*****************  Version 16  *****************
//User: Parveen      Date: 11/06/09   Time: 12:20p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//help link added
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 5/11/09    Time: 10:06
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug id---00001943
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 26/10/09   Time: 12:30
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//checked in for the time being.
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 28/08/09   Time: 13:14
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//00001337,00001336,00001335,00001334,
//00001332,00001333,00001339,00001265,
//00001267,00001257,00001256,00001266,
//00001232,00001231
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 17/08/09   Time: 15:51
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel issues
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 31/07/09   Time: 11:29
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Woked on client issues.
//Issues taken care of ---4,5,7,10
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 26/06/09   Time: 15:44
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified as required by GNIMT as on 26.06.2009
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 26/06/09   Time: 10:37
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done GNIMT enhancements as on 26.06.2009
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
//*****************  Version 14  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/26/08    Time: 3:36p
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
//User: Dipanjan     Date: 8/29/08    Time: 3:18p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/26/08    Time: 12:35p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/25/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/25/08    Time: 4:34p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/14/08    Time: 2:59p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/05/08    Time: 3:01p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/25/08    Time: 11:52a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/19/08    Time: 10:34a
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial checkin
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
