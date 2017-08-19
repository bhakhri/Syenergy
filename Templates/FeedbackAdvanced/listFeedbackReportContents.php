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
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                    <form name="allDetailsForm" id="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        <table border="0"  cellspacing="0" cellpadding="0">
                                           <tr>
                                            <td class="contenttab_internal_rows"><b><nobr>Time Table<?php echo REQUIRED_FIELD ?></nobr></b></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>  
                                            <td class="contenttab_internal_rows"><nobr>
                                             <select name="timeTableLabelId" id="timeTableLabelId" class="inputbox" style="width:240px;" onchange="getSurveyLabel(this.value);">
                                              <option value="">Select</option>
                                              <?php
                                                 echo $htmlFunctions->getTimeTableLabelData('-1');
                                              ?>
                                             </select></nobr>
                                            </td>
                                            <td class="contenttab_internal_rows"><b><nobr>&nbsp;Label<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>  
                                            <td class="contenttab_internal_rows"><nobr>
                                             <select name="labelId" id="labelId" class="inputbox" style="width:220px;"  onchange="getClasss(document.allDetailsForm.timeTableLabelId.value,this.value);" >
                                              <option value="">Select</option>
                                             </select></nobr>
                                            </td>
                                            <td class="contenttab_internal_rows"><b><nobr>&nbsp;Class</nobr></b></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>  
                                            <td class="contenttab_internal_rows"><nobr>
                                             <select name="classId" id="classId" class="inputbox" style="width:320px;" onchange="getEmployees(document.allDetailsForm.timeTableLabelId.value,document.allDetailsForm.labelId.value,this.value);">
                                              <option value="">Select</option>
                                             </select></nobr>
                                            </td>                        
                                            </td>
                                           </tr>
                                           <tr> 
                                            <td class="contenttab_internal_rows"><nobr><b>Teacher</b></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>  
                                            <td class="contenttab_internal_rows"  colspan="3" ><nobr>
                                             <select name="employeeId" id="employeeId" class="inputbox" style="width:290px;" onchange="vanishData();">
                                              <option value="">Select</option>
                                             </select></nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" colspan="4">
                                                <table border="0"  cellspacing="0" cellpadding="0">       
                                                 <tr>
                                                    <td class="contenttab_internal_rows"><nobr><b>&nbsp;Category</b></nobr></td>
                                                    <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>  
                                                    <td class="contenttab_internal_rows"><nobr>
                                                      <select name="categoryId" id="categoryId" class="inputbox" style="width:260px;" onchange="vanishData();">
                                                      <option value="">Select</option>
                                                      </select></nobr>
                                                    </td>
                                                    <td class="padding" colspan="6" align="left" style="padding-left:20px"><nobr>
                                                        <!-- <input type="image" src="<?php echo IMG_HTTP_PATH ?>/show_list.gif" border="0" onClick="showReport()"> -->
                                                        <input type="image" src="<?php echo IMG_HTTP_PATH ?>/show_list.gif" border="0" onClick="showScoreReport()">
                                                     </nobr>
                                                    </td>
                                                 </tr>
                                               </table>
                                          </tr>
                                         </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Feedback Details:</td>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/>
                                         </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv'></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/>
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
   </td>
  </tr>  
</table>
<?php
// $History: listFeedbackClassFinalReportContents.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:55
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created "Class Final Report"  for advanced feedback modules.
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/16/10    Time: 12:13p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//created file under feedback teacher final report

?>