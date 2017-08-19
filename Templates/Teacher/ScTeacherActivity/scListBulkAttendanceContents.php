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
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
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
                <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Bulk Attendance</td>
                <td valign="top" align="right"> &nbsp;
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
                        <td class="content_title" width="50%">Bulk Attendance : </td>
                        <td class="content_title_scroll" align="left" style="padding-right:3px" >
                        <div id='headertextbox'>
                          <script type="text/javascript">
                            window.onload=function(){
                                document.getElementById('subject').focus();
                                scroll_init("<?php echo $dAttRecord; ?>");
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
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="setScroller(this.value);classPopulate();">
                        <option value="">Select Subject</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Section: </b></nobr></td>
                        <td width="20%" class="padding" align="left" >
                        <select size="1" class="selectfield" name="section" id="section" onchange="classPopulate();" >
                         <option value="">Select Section</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSectionData();
                         ?>
                        </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows" align="left"><nobr><b>Class: </b></nobr></td>
                        <td width="20%" class="padding" align="left">
                        <select size="1" class="selectfield" name="classes" id="classes" >
                         <option value="">All</option>
                         </select>
                      </td>
                    </tr>
                    <tr>    
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>From Date: </b></nobr></td>
                        <td width="20%" class="padding"  align="left">
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('startDate','');
                         ?>
                        </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>To Date: </b></nobr></td>
                        <td width="20%" class="padding"  align="left">
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->datePicker('endDate','');
                         ?>
                        </td>
                         <td>&nbsp;</td>
                         <td align="left" style="padding-left:5px" align="left" >
                          <input type="image" name="imageField1" id="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                         </td>
                    </tr>  
                     <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Lecture Delivered: </b></nobr></td>
                     <td width="20%" class="padding"  align="left" colspan="5">
                       <input type="text" id="lectureDelivered" name="lectureDelivered" class="inputbox" style="width:50px" onkeyup="checkLectureDelivered(this.value);">                       
                     </td>
                    </tr>
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="listFrm" >
                 <div id="results">
                 <!--
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 <tr class="rowheading">
                    <td width="5%" class="unsortable">&nbsp;&nbsp;<b>#</b></td>
                    <td width="20%" class="searchhead_text"><b><nobr>Name</nobr></b></td>
                    <td width="10%" class="searchhead_text"><b><nobr>Roll No</nobr></b></td>
                    <td width="15%" class="searchhead_text"><b><nobr>Delivered</nobr></b>
                    <td width="15%" class="searchhead_text"><b><nobr>Attended</nobr></b></td>
                    <td width="15%" class="searchhead_text"><b><nobr>Percentage</nobr></b></td>
                    <td width="15%" class="searchhead_text"><b><nobr>MemberOfClass</nobr></b></td>
                    <td></td>
                 </tr>
                <?php
                    echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                ?>                 
                 </table>
                --> 
                </div>
                <!--Do Not Delete-->
                   <input type="hidden"  name="lcep" id="lcep" value="1">
                   <input type="hidden"  name="lcep" id="lcep" value="1"> 
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
<?php
// $History: scListBulkAttendanceContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/19/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//'Select Class' to 'All' according to Sachin Sir
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/19/08    Time: 1:39p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/19/08    Time: 12:21p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/18/08    Time: 3:13p
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