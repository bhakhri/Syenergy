<?php 
//it contain the template of time table 
//
// Author :Parveen Sharma
// Created on : 01-06-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
             <!--   <td valign="middle">Marks & Attendance&nbsp;&raquo;&nbsp;Display Subject Wise Topic Taught Report</td> -->
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <form action="" method="POST" name="listForm" id="listForm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border2">
                    <table width="85%" border="0" cellspacing="0" cellpadding="0" align="center">
                         <tr>
                            <td class="contenttab_internal_rows" valign="middle"><nobr><b>Time Table<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td class="padding"><nobr>
                               <select size="1" class="inputbox1" name="labelId" style="width:125px" id="labelId" onchange="populateClass();">
                                   <option value="">Select</option>
                                    <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                    ?>
                                </select></nobr>
                            </td>
                            <td class="contenttab_internal_rows" valign="middle"><nobr><b>Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td class="padding">
                                <select size="1" class="inputbox1" name="classId" id="classId" style="width:220px" onChange="populateSubjects()">
                                   <option value="">Select</option>                                             
                                </select>
                            </td>
                            <td class="contenttab_internal_rows" valign="middle"><nobr><b>Subject<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td class="padding">
                                <select size="1" class="inputbox1" name="subject" id="subject" style="width:170px" onChange="populateGroups()">
                                    <option value="">Select</option>
                                    <?php
                                      // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      // echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                                    ?>
                                </select>
                            </td>
                            <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;"><nobr><b>Group</b></nobr></td>
                            <td class="padding" width="1%" valign="top">:</td>
                            <td class="padding" valign="top">
                                <select size="1" class="inputbox1" name="group" id="group" style="width:125px" >
                                <option value="">Select</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;"><b>Topic</b></td>
                            <td class="padding" width="1%" valign="top">:</td>
                            <td style="padding-left:7px">
                            <div id="containerDiv" >
                                <select size="5" name="subjectTopic[]" id="subjectTopic" multiple style="width:178px;" class="inputbox1">
                                </select>
                                <?php
                                $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
                                if($isIE6==1){
                                ?>    
                                 <br><span class="contenttab_internal_rows">Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("subjectTopic","All","listForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("subjectTopic","None","listForm");'>None</a></span>
                                 <?php    
                                 }
                                ?>
                             </div>   
                            <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d1"></div>
                            <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d2" >
                              <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                                   <tr>
                                      <td id="d3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                                      <td width="5%">
                                      <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('subjectTopic','d1','containerDiv','d3');" />
                                      </td>
                                    </tr>
                                 </table>
                              </div>    
                            </td>
                            <td valign="top" style="padding-top:5px;" class="contenttab_internal_rows"><nobr><b>Date From</b></nobr></td>
                            <td class="padding" width="1%" valign="top">:</td>
                            <td class="padding" valign="top">
                            <?php
                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                               echo HtmlFunctions::getInstance()->datePicker('startDate','');
                            ?></td>
                            <td valign="top" style="padding-top:5px;" class="contenttab_internal_rows"><b>Date To</b></td>
                            <td class="padding" width="1%" valign="top">:</td>
                            <td class="padding" valign="top" ><nobr> 
                            <?php
                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                               echo HtmlFunctions::getInstance()->datePicker('endDate','');
                            ?>&nbsp;&nbsp;
                             <input style="margin-bottom:-4px;" type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();document.getElementById('saveDiv').style.display='';document.getElementById('showTitle').style.display='';document.getElementById('showData').style.display='';" />
                             </nobr>
                           </td>  
                        </tr>
                    </table>
                </td>
            </tr> 
           <tr id="showTitle" style="display:none">
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Subject Wise Topic Taught: </td>
                        <td colspan='1' align='right'>
                           <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport(); return false;" />&nbsp;
                           <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV(); return false;" />&nbsp;&nbsp;
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr id="showData" style="display:none">
                <td class="contenttab_row" valign="top" ><div id="results"></div>          
             </td>
          </tr>
          <tr>
            <td height="10"></td>
          </tr>
         <tr>
            <td colspan='1' align='right'  id="saveDiv" style="display:none">
               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport(); return false;" />&nbsp;
               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV(); return false;" />&nbsp;&nbsp;
            </td>
          </tr>
         </table>
        </td>
    </tr>
    </table>
    </form>
    </td>
    </tr>
    </table>
<?php
//$History: listTopicCoveredContents.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/23/10    Time: 12:45p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//timeTableLabel Id check updated 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 15/02/10   Time: 17:43
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified javascript and html coding for "New Multiple Selected
//Dropdowns" as these are not working in IE6.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 4/01/10    Time: 13:17
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made UI Changes
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/20/09   Time: 1:15p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//alignment format (bug no 1753)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/06/09   Time: 2:51p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//class added, look & feel formating 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/05/09    Time: 4:44p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//table heading change 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/03/09    Time: 12:24p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//

?>