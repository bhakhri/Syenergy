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
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
                	 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                <td valign="top" align="right">
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
              <!--  <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Feedback Teacher Final Report (Advanced) : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table> 
                </td> -->
             </tr>
          <tr>
	<td  valign="top" class="contenttab_border2">
    <!-- <td colspan="1" class="contenttab_row" style="border-top:none;" valign="top">  -->
           <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
               <table border="0"  cellspacing="0" cellpadding="0" width="100%">
                <tr>
                 <td colspan="7" nowrap="nowrap">
                     <table border="0"  cellspacing="0" cellpadding="0" width="70%">
                       <tr>
                        <td class="contenttab_internal_rows"><b>Time Table</b></td>
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
                         <select name="labelId" id="labelId" class="inputbox"  onchange="getCategory(document.allDetailsForm.timeTableLabelId.value,this.value);" >
                          <option value="">Select</option>
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
						<td class="padding" align="right">
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
//User: Dipanjan     Date: 23/03/10   Time: 16:46
//Created in $/LeapCC/Templates/FeedbackAdvanced/Teacher
//Created Feedback Teacher Final Report (Advanced) for Teacher login
?>