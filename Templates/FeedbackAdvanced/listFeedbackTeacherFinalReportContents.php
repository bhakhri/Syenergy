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
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
       <?php   require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Feedback Teacher Final Report (Advanced) : </td>
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
                 <td colspan="7" nowrap="nowrap">
                     <table border="0"  cellspacing="0" cellpadding="0">
                       <tr>
                        <td class="contenttab_internal_rows"><nobr><b>Time Table</b></nobr></td>
                        <td class="padding">:</td>
                        <td class="padding">
                         <select name="timeTableLabelId" id="timeTableLabelId" class="inputbox" style="width:150px;" onchange="getSurveyLabel(this.value);">
                          <option value="">Select</option>
                          <?php
                             echo $htmlFunctions->getTimeTableLabelData('-1');
                          ?>
                         </select>
                        </td>
                        <td class="contenttab_internal_rows"><b>Label</b></td>
                        <td class="padding">:</td>
                        <td class="padding">
                         <select name="labelId" id="labelId" class="inputbox"  onchange="getEmployees(document.allDetailsForm.timeTableLabelId.value,this.value);getCategory(document.allDetailsForm.timeTableLabelId.value,this.value,'');" >
                          <option value="">Select</option>
                         </select>
                        </td>
                        
                        <td class="contenttab_internal_rows"><b>Teacher</b></td>
                        <td class="padding">:</td>
                        <td class="padding">
                         <select name="employeeId" id="employeeId" class="inputbox" style="width:200px;" onchange="getCategory(document.allDetailsForm.timeTableLabelId.value,document.allDetailsForm.labelId.value,this.value);">
                         
                          <option value="">All</option>
                         </select>
                        </td>
                        <td class="contenttab_internal_rows" width="3%" style="padding-left:5px;"><b>Category</b></td>
                        <td class="padding" width="1%">:</td>
                        <td class="padding" width="5%"><nobr>
                        <select style="width:160px;" name="catId" id="catId" class="inputbox" onchange="vanishData();">
                        <option value="">All</option>
                       <?php
                        // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        // echo HtmlFunctions::getInstance()->getCategoryData();        
                        ?>
                        </select>
                        </td>
                       </tr>
					  <!-- <tr>
					   <td class="padding" colspan="12"  align="right"><a href="" onClick ="displayWindow()">Rating Legend</a>
					   </td>
					   </tr> -->
                       <tr> 
                        <td class="padding" colspan="12" align="right"> 
                             <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/show_list.gif" border="0" onClick="showReport()">
                        </td>
                      </tr>
                     </table>
                 </td>
                 </tr> 
                   <tr>
                    <td colspan="7">
                       <div id="reportDiv"></div>   
                    </td>
                   </tr>
                   <tr><td colspan="7" height="5px"></td></tr>
                   <tr>
                    <td colspan="7" id="printRowId" style="display:none" align="right">
                        <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
                    </td>
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
<?php
// $History: listFeedbackTeacherFinalReportContents.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/16/10    Time: 12:13p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//created file under feedback teacher final report

?>