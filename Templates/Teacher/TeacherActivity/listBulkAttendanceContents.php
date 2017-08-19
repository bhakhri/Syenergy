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
?>
<?php

    require_once(BL_PATH.'/helpMessage.inc.php');
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
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
            <tr>
                	<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>

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
                        <td class="content_title" width="72%">Bulk Attendance : </td>
                        <td style="padding-right:10px" align="right" class="content_title">
                           <a href="#" onclick="getHelpImageDownLoad('teacher-bulk-attendance.jpg','TeacherBulkAttendance'); return false;" name="">Help</a>
                        </td>
                        <td class="content_title_scroll" align="right" style="padding-right:3px" >
                        <input type="image" name="imageField10" id="imageField10" onClick="getAttendanceOptions();return false" src="<?php echo IMG_HTTP_PATH;?>/schedule.gif" />
                        <!--<input type="image" name="imageField5" id="imageField5" onClick="getAttendanceHistory();return false" src="<?php echo IMG_HTTP_PATH;?>/attendance_history.gif" />-->
                        <input type="image" name="imageField5" id="imageField5" onClick="fetchAttendanceHistory(0);return false" src="<?php echo IMG_HTTP_PATH;?>/attendance_history.gif" />
                          <script type="text/javascript">
                            window.onload=function(){
                                //document.getElementById('includeDateRange').checked=false;
                                document.getElementById('class').focus();
                                //scroll_init("<?php echo $dAttRecord; ?>");
                                document.onkeydown=stopEnterKey;
                                document.getElementById('calImg1').onblur=refreshDropDowns
                                document.getElementById('calImg2').onblur=refreshDropDowns
                            }
                          </script>
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
                      <td width="14%" valign="middle" class="contenttab_internal_rows"><nobr><b>From Date<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('From Date',HELP_BULK_ATTENDANCE_FROM);
                         ?></b></nobr></td>
                        <td width="20%" class="padding"  align="left">:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'),1);
                         ?>
                        </td>
                        <td width="6%" class="contenttab_internal_rows"><nobr><b>To Date<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('To Date',HELP_BULK_ATTENDANCE_TO);
                         ?></b></nobr></td>
                        <td width="20%" class="padding"  align="left" colspan="5">:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'),2);
                         ?>
                         <a class="contenttab_internal_rows" href="javaScript:void(0);" onclick="fetchAttendanceHistory(1);" title="Show Attendance History In Selected Date Range">Show Attendance History In Selected Date Range</a>
                        </td>

                    </tr>
                    <tr>
                        <td width="14%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding" align="left" style="padding-right:10px"><nobr>:
                        <select size="1" class="selectfield" name="class" id="class" onchange="populateSubjects(this.value);groupPopulate(this.form.subject.value);resetForm();" >
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr>
                      </td>
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding" align="left" style="padding-right:10px"><nobr>:
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="setScroller(this.value);topicPopulate(this.value);groupPopulate(this.value);resetForm();">
                        <option value="">Select Subject</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding" align="left" ><nobr>:&nbsp;
                        <select size="1" class="selectfield" name="group" id="group"  onchange="resetForm();">
                        <option value="">Select Group</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select></nobr>
                      </td>
                       <td>&nbsp;</td>
                         <td align="left" style="padding-left:15px" align="left" >
                          <input type="image" name="imageField1" id="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                         </td>
                    </tr>
                     <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Lecture Delivered<?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getHelpLink('Lecture Delivered',HELP_BULK_ATTENDANCE_LECTURE);
                         ?></b></nobr></td>
                     <td width="15%" class="padding"  align="left">:
                       <!--<input type="text" id="lectureDelivered" name="lectureDelivered" class="inputbox" style="width:50px" onblur="checkLectureDelivered(this.value);">-->
                       <input type="text" id="lectureDelivered" name="lectureDelivered" class="inputbox" style="width:50px">
                       &nbsp;
                       <input type="image" name="imageField1" style="margin-bottom: -5px;" id="imageField1" onClick="checkLectureDelivered(document.searchForm.lectureDelivered.value);return false" src="<?php echo IMG_HTTP_PATH;?>/update.gif" />
                     </td>
					 <td width="5%" class="contenttab_internal_rows"><nobr><b>Comments </b></nobr></td>
						 <td width="40%" class="padding"  align="left" colspan="5"><nobr>:
							 <input type="text" name="commentTxt" id="commentTxt" maxlength="255" onkeydown="return sendKeys('commentTxt',event);" style="width:78%;font-family: Arial, Helvetica, sans-serif;  font-size: 12px; border: 1px solid #c6c6c6; height:20px; border: 1px solid #c6c6c6;"/></nobr>
						 </td>
                    </tr>

					 <tr>
						 <td width="4%" class="contenttab_internal_rows"><nobr><b>Topics </b></nobr></td>
						 <td width="20%" class="padding"  align="left" colspan="7"><nobr>:
						  <select id="topicsId" class="inputbox" multiple="multiple" size="5" name="topicsId[]" style="vertical-align:middle;width:750px;" >
							<!--<option value="">Select</option>-->
						  </select></nobr>
						 </td>
                    </tr>
					<tr>
					<td colspan=8 align="center" name="attendanceStatus" id="attendanceStatus" class="attendanceMessage"></td>
					</tr>
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="listFrm" >
                <table id="divButton1" border="0" cellpadding="0" cellspacing="0" width="100%" height="30px"  style="display:none" >
                   <tr class="contenttab_border">
                     <td class="content_title" align="left">List of Students : </td>
                     <td align="right">
                     <input type="image" name="imageField44" id="imageField44" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm('listFrm');return false;" style="margin-bottom:-3px" />
                     <input type="image" name="imageField55" id="imageField55" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onClick="resetForm();return false;" style="margin-bottom:-3px" />
                   </td>
                  </tr>
                 <tr>
                  <td colspan="2">
                    <div id="results"></div>
                  </td>
                 </tr>
                 </table>
                <!--Do Not Delete-->
                   <input type="hidden"  name="lcep" id="lcep" value="1">
                   <input type="hidden"  name="lcep" id="lcep" value="1">
				   <input type="hidden" name="taught" id="taught">
                  <!--Do Not Delete-->
                </form>
             </td>
          </tr>
          <tr><td height="5px"></td></tr>
          <tr><td align="right" >
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
    <tr><td height="5px" colspan="2"></td></tr>
    <tr>
     <td colspan="2">
      <div id="historyResults" style="width:920px;height:300px;overflow:auto">
      </td>
    </tr>
<tr><td height="5px" colspan="2"></td></tr>
<tr>
    <!--<td align="center" style="padding-right:10px" colspan="1" width="80%">
      &nbsp;<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif"  onclick="javascript:hiddenFloatingDiv('AttendanceHistoryDiv');return false;" />
    </td>-->
    <td align="center" style="padding-right:5px">
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/print.gif"  onclick="printReport();return false;" />
      &nbsp;<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/excel.gif"  onclick="printCSV();return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
<?php floatingDiv_End(); ?>
<!--End history  Div-->

<!--Start Attendance Help  Div-->
<?php floatingDiv_Start('AttendanceHelpDiv','Attendance Schedule'); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px"></td></tr>
    <tr>
     <td>
      <div id="attendanceOptionsResults" style="width:345px;height:145px;overflow:auto">
      </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
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

<!--Bulk Attendance Help  Details  Div-->
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
<!--Bulk Attendance Help  Details  End -->

<?php
// $History: listBulkAttendanceContents.php $
//
//*****************  Version 22  *****************
//User: Gurkeerat    Date: 12/04/09   Time: 5:46p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//updated look n feel of help dialog box
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 25/11/09   Time: 18:40
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made enhancements : Teacher can view other teachers attendance and also
//edit & delete them,if they have the same time table allocation
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 17/11/09   Time: 17:37
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Updated image for auto calculating lecture delivered and attended
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 16/11/09   Time: 18:11
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified UI of bulk attendance module
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:08
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 12/11/09   Time: 17:21
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified logic in bulk attendance module and corrected flaws in coding
//and removed check which prevents taking attendance across months or
//years.
//
//*****************  Version 16  *****************
//User: Parveen      Date: 11/06/09   Time: 12:20p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//help link added
//
//*****************  Version 15  *****************
//User: Parveen      Date: 11/02/09   Time: 2:50p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Help Div added
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added code for "Time table adjustment"
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