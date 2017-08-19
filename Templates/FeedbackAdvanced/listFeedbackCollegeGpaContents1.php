<?php
//-------------------------------------------------------
// Author :Abhay Kant
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

require_once(BL_PATH.'/helpMessage.inc.php');  
?>
<form name="allDetailsForm" id="allDetailsForm" method="post" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
        <?php    require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Feedback College GPA Report: </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
          <tr>
           <td colspan="1" class="contenttab_row" style="border-top:none;">
               <table border="0"  cellspacing="0" cellpadding="0" width="100%">
                <tr>
                 <td colspan="7">
                     <table border="0"  cellspacing="0" cellpadding="0" width="100%">
                       <tr>
                       <td>
                         <table border="0"  cellspacing="0" cellpadding="0" width="10%">
                          <tr>
                            <td class="contenttab_internal_rows"><b><nobr>Time Table</nobr></b></td>
                            <td class="padding"><b></nobr>:&nbsp;</nobr></b></td>  
                            <td nowrap class="padding"><nobr>
                               <select name="timeTableLabelId" id="timeTableLabelId" class="inputbox" style='width:320px' onchange="getSurveyLabel(this.value);">
                                  <option value="">Select</option>
                                  <?php
                                     require_once(BL_PATH.'/HtmlFunctions.inc.php');     
                                     echo $htmlFunctions->getTimeTableLabelData();
                                  ?>
                                </select><span style='padding-left:5px'>
                                <b>Survey Name </b>&nbsp;:&nbsp;</nobr></b>
                                <select name="labelId" id="labelId" class="inputbox" style="width:320px;" onchange="vanishData();">
                                   <option value="">Select</option>
                                </select></span></nobr>   
                             </td>
                          </tr>   
                          <tr style="display:none">
                                <td class="contenttab_internal_rows" valign="top"><b>Teacher</b></td>
                                <td class="padding" valign="top">:</td>
                                <td class="contenttab_internal_rows" colspan="20"><nbor>
                                    <table border="0"  cellspacing="0" cellpadding="0" width="10%"> 
                                    <tr>
                                    <td class="padding">
                                        <select name="employeeId[]" id="employeeId" class="selectfield" size="9" style='width:320px' multiple="multiple">
                                            <?php
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getTeacher('-1');
                                            ?>
                                         </select><br>
                                        <div align="left">
                                        Select &nbsp;
                                        <a class="allReportLink" href="javascript:makeSelection('employeeId[]','All','allDetailsForm');">All</a> / 
                                        <a class="allReportLink" href="javascript:makeSelection('employeeId[]','None','allDetailsForm');">None</a>
                                        </div></nobr>
                                        </td>
                                  <td class="contenttab_internal_rows" valign="top"><b>Group</b></td>
                                <td class="padding" valign="top">:</td>
                                <td class="padding"><nobr>
                                   <select name="groupId[]" id="groupId" class="selectfield" size="9" style='width:150px' multiple="multiple">
                                       <?php
                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                            echo HtmlFunctions::getInstance()->getGroupTypeData('-1');
                                       ?>
                                    </select><br>
                                    <div align="left">
                                    Select &nbsp;
                                    <a class="allReportLink" href="javascript:makeSelection('groupId[]','All','allDetailsForm');">All</a> / 
                                    <a class="allReportLink" href="javascript:makeSelection('groupId[]','None','allDetailsForm');">None</a>
                                    </div></nobr>
                                 </td>
                                 <td class="contenttab_internal_rows" valign="top"><b>Subject</b></td>
                                 <td class="padding" valign="top">:</td>
                                 <td class="padding"><nobr>
                                    <select name="subjectId[]" id="subjectId" class="selectfield" style='width:220px' size="9" multiple="multiple">
                                    <?php
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getSubjectData('-1');
                                    ?>
                                    </select><br>
                                    <div align="left">
                                    Select &nbsp;
                                    <a class="allReportLink" href="javascript:makeSelection('subjectId[]','All','allDetailsForm');">All</a> / 
                                    <a class="allReportLink" href="javascript:makeSelection('subjectId[]','None','allDetailsForm');">None</a>
                                    </div></nobr>
                                  </td>
                           </tr>
                         </table> 
                         </nobr>                                     
                               </td>
                          </tr>
                          <tr>
                        <td class="padding" colspan="10" align='center'>
                             <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/show_feedback_results.gif" border="0" onClick="showReport()" >                        </td></tr></td></table> 
                      </tr>
                      <tr bgcolor="#FFFFC6" width="100%" >
                        <td  colspan="8" class="contenttab_internal_rows"><b>How GPA Is Calculated ?</b>
                           <?php 
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                               echo HtmlFunctions::getInstance()->getHelpLink('GPA Help',HELP_GPA); 
                            ?>                       
                        </td>  
                      </tr>
                     </table>
                   </td>
                 </tr> 
                   <tr>
                    <td colspan="7">
                       <div id="resultsDiv"></div>                    
                    </td>
                   </tr>
                   <tr><td colspan="7" height="5px"></td></tr>
                   <tr>
                    <td colspan="7" id="printRowId" style="display:none" align="right">
                        <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;
                        <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">                    </td>
                   </tr>  
              </table>
            </td>
           </tr>
          <!--Student List Showing-->
          
          <!--Student List Showing (Ends)-->
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    
    <tr id='nameRow2' style='display:none'>
                            <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                    <tr>
                                        <td colspan="2" class="content_title" align="right">
                                       
                                            <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                            <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    
    
    </table> 
</form>
    
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
<?php
// $History: listFeedbackCollegeGpaContents.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/10   Time: 17:17
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created college gpa report for feedback modules
?>
