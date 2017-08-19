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
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
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
                <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Daily Attendance</td>
                <td valign="top" align="right">
                  &nbsp;
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
                        <td class="content_title" width="50%">Daily Attendance : </td>
                        <td class="content_title_scroll" align="left" style="padding-right:3px" >
                        <div id='headertextbox'>
                          <script type="text/javascript">
                            window.onload=function(){
                                document.getElementById('subject').focus();
                                scroll_init("<?php echo $dAttRecord; ?>");
                                init();
                            } 
                          </script>
                        </div>    
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
                       <td width="10%" class="contenttab_internal_rows"><nobr><b>Attendance Date: </b></nobr></td>
                       <td width="20%" class="padding"  align="left">
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            $toDate=date('Y')."-".date('m')."-".date('d');
                            echo HtmlFunctions::getInstance()->datePicker('forDate',$toDate);
                         ?>
                        </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="setScroller(this.value);classPopulate();getPeriodNames();" >
                        <option value="">Select Subject</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                      
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Section: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="section" id="section" onchange="classPopulate();getPeriodNames();" >
                        <option value="">Select Section</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSectionData();
                        ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="classes" id="classes"  onchange="getPeriodNames();">
                        <option value="">All</option>
                        </select>
                      </td>                     
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Period: </b></nobr></td>
                     <td width="20%" class="padding" align="left">
                     <select size="1" class="selectfield2" name="period" id="period" >
                        <option value="">Select Period</option>
                        </select>
                      </td> 
                        <td>&nbsp;</td>
                        <td style="padding-left:5px"  align="left" >
                         <input type="image" name="imageField1" id="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                        </td>
                    </tr> 
                    <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Default Att. Code: </b></nobr></td>
                     <td width="20%" class="padding" align="left">
                     <select size="1" class="selectfield2" name="defaultAttCode" id="defaultAttCode" onblur="makeDefaultAttCode();" onchange="makeDefaultAttCode();">
                        <option value="">Select Att.Code</option>
                          <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getAttendanceCodeData();
                        ?>
                      </select>
                      </td> 
                    </tr> 
                     <tr>
                        <td height="5"></td>
                    </tr>
                    <tr><td colspan="8" height="5px"></td><td width="41%"></td>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <form name="listFrm" id="listFrm">
                  <!--Do Not Delete-->
                   <input type="hidden"  name="mem" id="mem" value="1">
                   <input type="hidden"  name="mem" id="mem" value="1"> 
                  <!--Do Not Delete-->  
                  
                 <div id="results">
                 <!--
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 <tr class="rowheading">
                    <td width="5%" class="unsortable">&nbsp;&nbsp;<b>#</b></td>
                    <td width="30%" class="searchhead_text"><b><nobr>Name</nobr></b></td>
                    <td width="20%" class="searchhead_text"><b><nobr>Roll No</nobr></b></td>
                    <td width="20%" class="searchhead_text"><b><nobr>AttendanceCode</nobr></b>
                    <td width="15%" class="searchhead_text"><b><nobr>MemberOfClass</nobr></b></td>
                    <td></td>
                 </tr>
                <?php
                    echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                ?>                 
                 </table>
                --> 
                </div>
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
<?php
// $History: scListDailyAttendanceContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/20/08    Time: 12:59p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/19/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//'Select Class' to 'All' according to Sachin Sir
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/19/08    Time: 1:39p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/16/08    Time: 12:58p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/11/08    Time: 11:01a
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
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
