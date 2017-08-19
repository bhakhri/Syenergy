<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(BL_PATH.'/helpMessage.inc.php');

require_once(BL_PATH.'/HtmlFunctions.inc.php');
$attendanceCodeDataString= HtmlFunctions::getInstance()->getAttendanceCodeData();
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
	<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
        <td valign="top" class="">
            <!--<table border="1" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>-->
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
            <!--<tr>
                <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Copy Attendance</td>
                <td valign="top" align="right">
                  &nbsp;
                </td>
            </tr>
            </table>-->
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
                        <td class="content_title" width="80%">Copy Attendance : </td>
                        <td style="padding-right:10px" align="right" class="content_title"> 
                        
                        </td>
                        <td class="content_title_scroll" align="right" style="padding-right:3px" >
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
                       <td width="10%" class="contenttab_internal_rows"><nobr><b>Attendance Date</b>
                       <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('Schedule',HELP_DAILY_ATTENDANCE_SCHEDULE);
                       ?>
                       </nobr>
                       </td> 
                       <td width="20%" class="padding"  align="left" valign="top">:
                        <?php
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            $toDate=date('Y')."-".date('m')."-".date('d');
                            echo HtmlFunctions::getInstance()->datePicker('forDate',$toDate);
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
                        <td width="20%" class="padding" align="left"><nobr>: <select size="1" class="selectfield" name="subject" id="subject" onchange="getPeriodNames();setScroller(this.value);groupPopulate(this.value);" >
                        <option value="">Select Subject</option>
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
                        </select></nobr>
                      </td>                     
                     <td width="5%" class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Source Period<?php 
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('Period',HELP_DAILY_ATTENDANCE_PERIOD);
                         ?></b></nobr></td>
                     <td width="20%" class="padding" align="left"><nobr>: 
                     <select size="1" class="selectfield" name="period" id="period" onchange="getTargetPeriods(this.value);">
                        <option value="">Select Period</option>
                        </select></nobr>
                      </td> 
                        <td width="5%" class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Target Period<?php 
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('Period',HELP_DAILY_ATTENDANCE_PERIOD);
                         ?></b></nobr></td>
                     <td width="20%" class="padding" align="left"><nobr>: 
                     <select size="1" class="selectfield" name="targetPeriodId" id="targetPeriodId">
                        <option value="">Select Period</option>
                        </select></nobr>
                      </td> 
                    </tr>
                    <tr>
                     <td class="padding" align="center" colspan="6">
                       <input type="image" name="imageField1" id="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/list_students.gif" />
                     </td> 
                    </tr>
                    <tr>
                     <td class="padding" align="center" colspan="6" id="attendanceSummeryTdId"></td> 
                    </tr> 
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top">
                 <form name="listFrm" id="listFrm" style="display:inline" onsubmit="return false;">
                  <!--Do Not Delete-->
                  <input type="hidden"  name="mem" id="mem" value="1">
                  <input type="hidden"  name="mem" id="mem" value="1">
                  <input type="hidden" name="taught" id="taught">
                  <!--Do Not Delete-->  
                <table id="divButton1" border="0" cellpadding="0" cellspacing="0" width="100%" height="30px" style="display:none">
                   <tr class="contenttab_border">
                     <td class="content_title" align="left">List of Students : </td>
                     <td align="right">
                     <input type="image" name="imageField44" id="imageField44" src="<?php echo IMG_HTTP_PATH;?>/copy_attendance.gif" onClick="return validateForm('listFrm');return false;" style="margin-bottom:-3px" />
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
                  <input type="image" name="imageField2" id="imageField2" src="<?php echo IMG_HTTP_PATH;?>/copy_attendance.gif" onClick="return validateForm(this.form);return false;" />
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