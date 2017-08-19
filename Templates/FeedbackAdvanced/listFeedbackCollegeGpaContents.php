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
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
require_once(BL_PATH.'/helpMessage.inc.php');  
?>
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
                        <td class="content_title">Feedback College GPA Report (Advanced) : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
          <tr>
           <td colspan="1" class="contenttab_row" style="border-top:none;">
           <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
               <table border="0"  cellspacing="0" cellpadding="0" width="100%">
                <tr>
                 <td colspan="7">
                     <table border="0"  cellspacing="0" cellpadding="0" width="100%">
                       <tr><td><table border="0"  cellspacing="0" cellpadding="0" width="72%">
                       <tr>
                        <td class="contenttab_internal_rows"><b>Time Table</b></td>
                        <td class="padding">:</td>
                        <td class="padding">
                         <select name="timeTableLabelId" id="timeTableLabelId" class="inputbox" onchange="getSurveyLabel(this.value);">
                          <option value="">Select</option>
                          <?php
                             echo $htmlFunctions->getTimeTableLabelData('-1');
                          ?>
                         </select>                        </td>
                        <td class="contenttab_internal_rows"><b>Label</b></td>
                        <td class="padding">:</td>
                        <td class="padding">
                         <select name="labelId" id="labelId" class="inputbox" style="width:300px;" onchange="vanishData();">
                          <option value="">Select</option>
                         </select>                        </td>
                        <td class="padding">
                             <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/show_list.gif" border="0" onClick="showReport()">                        </td></tr></td></table> 
                      </tr>
                       <tr bgcolor="#FFFFC6" width="100%" >
                        <td  colspan="8" class="contenttab_internal_rows"><b>GPA</b>
                       <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                        echo HtmlFunctions::getInstance()->getHelpLink('GPA Help',HELP_GPA); ?>                       </td>  
                        </tr>
                     </table>                 </td>
                 </tr> 
                   <tr>
                    <td colspan="7">
                       <div id="reportDiv"></div>                    </td>
                   </tr>
                   <tr><td colspan="7" height="5px"></td></tr>
                   <tr>
                    <td colspan="7" id="printRowId" style="display:none" align="right">
                        <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">                    </td>
                   </tr>  
              </table>
             </form>
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
    </table> 
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