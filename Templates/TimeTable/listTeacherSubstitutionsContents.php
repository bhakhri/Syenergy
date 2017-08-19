<?php 
//it contain the template of time table for class
//
// Author :Parveen Sharma
// Created on : 16-02-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
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
                       <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                       <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                                         <tr>  
                                            <td class="contenttab_internal_rows" valign="middle" align="right"><nobr><b>Time Table</b>&nbsp;<?php echo REQUIRED_FIELD ?></b>&nbsp;</nobr></td>  
                                            <td class="contenttab_internal_rows" valign="middle" align="right"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>  
                                            <td class="contenttab_internal_rows" valign="middle" align="right">
                                            <nobr>
                                               <select size="1" class="inputbox1" name="labelId" style="width:190px" id="labelId" onchange="populateTeachers();">
                                                    <?php
                                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                      $results = HtmlFunctions::getInstance()->getTimeTableLabelDate();
                                                      if($results=='') {
                                                        echo "<option value=''>Select</option>";                                                        
                                                      }
                                                      else {
                                                        echo $results;  
                                                      }
                                                    ?>
                                                </select>
                                            </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="top" rowspan="4"> 
                                                <nobr>&nbsp;&nbsp;</nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="top" rowspan="4"> 
                                                 <table border="0" cellspacing="0" cellpadding="0"> 
                                                    <tr>
                                                        <td class="contenttab_internal_rows" valign="top"><nobr>
                                                          <b>Periods&nbsp;<span style="font-size:9px;color:red">(Period No. ~ Period Slot ~ Room)</span>
                                                          <?php echo REQUIRED_FIELD ?>&nbsp;:</b>
                                                          </nobr>
                                                        </td>
                                                     </tr>
                                                     <tr>
                                                        <td class="contenttab_internal_rows" valign="top"><nobr>
                                                            <select multiple name='periodId[]' id='periodId' size='5' onChange="hideDetails();" class='htmlElement2' style='width:240px'>
                                                            </select>
                                                          </nobr>
                                                        </td>
                                                     </tr>
                                                     <tr>
                                                        <td class="contenttab_internal_rows" valign="top"><nobr>
                                                          Select
                                                            <a class="allReportLink" href="javascript:makeSelection('periodId[]','All','allDetailsForm');">All</a> / 
                                                            <a class="allReportLink" href="javascript:makeSelection('periodId[]','None','allDetailsForm');">None</a>
                                                            </nobr>
                                                        </td>
                                                     </tr>   
                                                </table>        
                                            </td>
                                            <td class="contenttab_internal_rows" valign="top" rowspan="4"> 
                                                <nobr>&nbsp;&nbsp;</nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="top" rowspan="4"> 
                                                <table border="0" cellspacing="0" cellpadding="0">  
                                                    <tr>
                                                        <td class="contenttab_internal_rows" valign="top"><nobr>
                                                          <b>Subjects&nbsp;:</b>
                                                          </nobr>
                                                        </td>
                                                     </tr>
                                                     <tr>
                                                        <td class="contenttab_internal_rows" valign="top"><nobr>
                                                            <select multiple name='subjectId[]' id='subjectId' size='5' onChange="hideDetails();" class='htmlElement2' style='width:340px'>
                                                            </select>
                                                          </nobr>
                                                        </td>
                                                     </tr>
                                                     <tr>
                                                        <td class="contenttab_internal_rows" valign="top"><nobr>
                                                         Select
                                                            <a class="allReportLink" href="javascript:makeSelection('subjectId[]','All','allDetailsForm');">All</a> / 
                                                            <a class="allReportLink" href="javascript:makeSelection('subjectId[]','None','allDetailsForm');">None</a>
                                                            </nobr>
                                                        </td>
                                                     </tr>   
                                                </table>        
                                            </td>
                                            <td class="padding" valign="bottom" rowspan="4" style="padding-left:15px;">
                                             <nobr><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" /></nobr> 
                                            </td>
                                          </tr>
                                          <tr id='dailyFormat' style="display:none"> 
                                            <td class="contenttab_internal_rows" valign="middle">
                                                <nobr><b>Date&nbsp;<?php echo REQUIRED_FIELD ?></nobr></b>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="middle" align="right"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>  
                                            <td class="contenttab_internal_rows" valign="middle" align="right">
                                             <?php 
                                                   require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                   echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'),'',"onBlur=\"populateTeachers();\"");
                                                ?></nobr>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td class="contenttab_internal_rows" valign="middle">
                                                <nobr><b>Teacher Name&nbsp;<?php echo REQUIRED_FIELD ?></nobr></b>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="middle" align="right"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>  
                                            <td class="contenttab_internal_rows" valign="middle" align="right" id='dailyEmployee'><nobr>
                                               <select name='employeeId1' id='employeeId1' class='htmlElement2' onChange="getPeriods();getSubjects();" style='width:190px'>
                                                 <option value="">Select</option>
                                               </select><nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="middle" align="right" id='weeklyEmployee'><nobr>
                                               <select name='employeeId' id='employeeId' class='htmlElement2' onChange="getDaysofWeekP();" style='width:190px'>
                                                  <option value="">Select</option>
                                               </select><nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" valign="middle" align="right" id='dailyWeeklyTeacher'><nobr>
                                               <select style='width:190px'>
                                                  <option value="">Select</option>
                                               </select><nobr>
                                            </td>
                                          </tr>
                                          <tr id='weeklyFormat' style="display:none">  
                                            <td class="contenttab_internal_rows" valign="middle" align="right"><nobr><b>Days</b>&nbsp;<?php echo REQUIRED_FIELD ?></nobr></td>
                                            <td class="contenttab_internal_rows" valign="middle" align="right"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>  
                                            <td class="contenttab_internal_rows" align="left"  valign="middle"><nobr>
                                              <select  name='daysOfWeek' id='daysOfWeek' onChange="getPeriods();getSubjects();" class='htmlElement2' style='width:190px'>     
                                              <option value="">Select</option>   
                                              </select> </nobr>  
                                            </td>
                                          </tr>
                                          <tr id='ttFormat1' style="display:none">
                                             <td class="padding" valign="middle" height="40px" align="right" colspan="3">&nbsp;</td>    
                                          </tr>                                             
                                          <tr id='ttFormat2' style="display:none">
                                             <td class="padding" valign="middle" height="40px" align="right" colspan="3">&nbsp;</td>    
                                          </tr>
                                    </table>
                                </td>
                            </tr>
                       </table>
                   </form>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Teacher Substitutions Report Details :</td>
                                            <td colspan="1" class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image" id="generateCSV" id="generateCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'results'>
                                    
                                    </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                               <input type="image" id="generateCSV" id="generateCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
        </table>
<?php
//$History: listTeacherSubstitutionsContents.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 3/12/10    Time: 10:43a
//Updated in $/LeapCC/Templates/TimeTable
//required pareamter remove (subjects)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/22/10    Time: 5:30p
//Updated in $/LeapCC/Templates/TimeTable
//period slot format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/12/10    Time: 2:17p
//Updated in $/LeapCC/Templates/TimeTable
//sortin order updated (employeeName1)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/12/10    Time: 2:11p
//Updated in $/LeapCC/Templates/TimeTable
//time Table label added (validation format updated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/12/10    Time: 1:39p
//Updated in $/LeapCC/Templates/TimeTable
//timeTable Label Id base code updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/18/09    Time: 5:34p
//Updated in $/LeapCC/Templates/TimeTable
//instituteId check added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 4:01p
//Created in $/LeapCC/Templates/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 3:31p
//Created in $/SnS/Templates/TimeTable
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/20/09    Time: 11:52a
//Updated in $/Leap/Source/Templates/ScTimeTable
//tag message update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 11:43a
//Created in $/Leap/Source/Templates/ScTimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/18/09    Time: 3:14p
//Created in $/SnS/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/17/09    Time: 6:26p
//Updated in $/Leap/Source/Templates/ScTimeTable
// time table label id update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/17/09    Time: 12:00p
//Created in $/Leap/Source/Templates/ScTimeTable
//initial checkin
//

?>
