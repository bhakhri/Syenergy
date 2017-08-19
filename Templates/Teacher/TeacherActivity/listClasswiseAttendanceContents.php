<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
          
            <tr>
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
               <!-- <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Display Attendance</td>-->
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
                        <td class="content_title" width="80%">Display Attendance : </td>
                         <td style="padding-right:10px" align="right" class="content_title"> 
              <a href="#" onclick="getHelpImageDownLoad('teacher-diaplay-attendance.jpg','TeacherDisplayAttendance'); return false;" name="">Help</a> 
                          </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                  <tr>
                   <td width="5%" class="contenttab_internal_rows"><nobr><b>From Date</b></nobr></td>
                       <td width="20%" class="padding"  align="left"><nobr>:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            $toDate=date('Y')."-".date('m')."-".date('d');
                            echo HtmlFunctions::getInstance()->datePicker('fromDate',$toDate,1);
                         ?></nobr>
                        </td>
                       <td width="7%" class="contenttab_internal_rows" style="padding-left:15px;"><nobr><b>To Date</b></nobr></td>
                       <td width="20%" class="padding"  align="left" colspan="4"><nobr>:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            $toDate=date('Y')."-".date('m')."-".date('d');
                            echo HtmlFunctions::getInstance()->datePicker('toDate',$toDate,2);
                         ?></nobr>
                        </td>
                  </tr>
                    <tr>    
                        <td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding" align="left"><nobr>:
                        <select size="1" class="selectfield" name="class" id="class" onchange="deleteRollNo();populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                        <?php
                          //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          //echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr></td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px;"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding"><nobr>:
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="deleteRollNo();groupPopulate(this.value);" >
                        <option value="">Select Subject</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px;"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding" colspan="2" align="left"><nobr>:
                        <select size="1" class="selectfield" name="group" id="group" onchange="deleteRollNo();" style="width:237px;" >
                        <option value="">Select Group</option>
                        <option value="-1">All</option>
                        </select></nobr>
                      </td>
                    </tr>
              
                    <tr>
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
                        <td width="20%" class="padding"  align="left"><nobr>: 
                        <input type="text" id="studentName" name="studentName" onkeydown="return sendKeys('studentName',event);" class="inputbox" style="width:182px"></nobr>
                        </td>    
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Roll No. / Univ. Roll No.</b></nobr></td>
                        <td width="20%" class="padding"  align="left"><nobr>:
                        <input type="text" id="studentRollNo" name="studentRollNo" autocomplete='off' onkeydown="return sendKeys('studentRollNo',event);" class="inputbox" style="width:182px"></nobr>
                        </td>
                        <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Report Type</b></nobr></td>
                        <td width="20%" class="padding"  align="left"><nobr>:
                            <span class="contenttab_internal_rows">
                            <input type="radio" name="reportType" checked="checked" onclick="vanishData();" value="1" />My Only &nbsp;
                            <input type="radio" name="reportType" onclick="vanishData();" value="0" />All Teachers</span>&nbsp;
                            <input  type="image" name="imageField" onClick="getData();return false" style="margin-bottom:-6px;" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                         </nobr>
                       </td>
                    </tr>
                    <tr><td colspan="6" height="5px"></td><td width="41%"></td>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" style="padding-right:10px;" >&nbsp;
                 <div id="results">
                </div>
             </td>
          </tr>
          <tr>
				<td height="10" colspan="2"></td>
			 </tr>
			 <tr id = 'saveDiv1' style="display:none">
				 
				<td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCourseToClassCSV();return false;"/></td>
			</tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: listClasswiseAttendanceContents.php $
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made changes in Teacher module for DAILY_TIMETABLE issues 
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 12/04/10   Time: 18:58
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Updated "Display Attendance" report
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/06/09   Time: 12:20p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//help link added
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added code for autosuggest functionality
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added code for "Time table adjustment"
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 28/08/09   Time: 13:14
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//00001337,00001336,00001335,00001334,
//00001332,00001333,00001339,00001265,
//00001267,00001257,00001256,00001266,
//00001232,00001231
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 29/06/09   Time: 11:32
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added name & roll no wise search in display attendance and marks
//display in teacher login
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 4/08/09    Time: 1:04p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added print and export to csv functionality
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