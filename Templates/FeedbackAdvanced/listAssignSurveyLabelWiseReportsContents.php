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
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
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
                        <td class="content_title">Feedback Label Wise Survey Report (Advanced) : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
          
          <!--student part(Common part)-->
          <tr id="studentSearchFilterRowId">
           <td colspan="1" class="contenttab_row" style="border-top:none;">
               <table border='0' width='100%' cellspacing='0' cellpadding="0">
                  <tr>
                    <td>
                    <table border="0" cellpadding="0" cellspacing="0">
                   <tr>
                     <td class="contenttab_internal_rows"><b>Time Table</b></td>
                     <td class="contenttab_internal_rows"><b> :</b></td>
                     <td class="padding">
                       <select name="timeTableLabelId" id="timeTableLabelId" class="htmlElement" style='width:200px' onchange="fetchMappedSurveyLabels(this.value);" >
                       <option value="">Select</option>
                       <?php
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->getTimeTableLabelData(-1);
                       ?>
                      </select>
                     </td>
                     <td class="contenttab_internal_rows" style="padding-left:3px;"><b>Label</b></td>
                     <td class="contenttab_internal_rows"><b>:</b></td>
                     <td class="padding">
                       <select class="htmlElement" style='width:200px' name="labelId" id="labelId" onchange="fetchMappedCategories(document.getElementById('timeTableLabelId').value,this.value);">
                       <option value="">Select</option>
                      </select>
                      &nbsp;Applicable To&nbsp;:&nbsp;<div id="applicableToTDId" style="display:inline"><?php echo $blank_string; ?>
                     </td>
                   </tr>
                   </table>
                  </td>
                  </tr> 
                 
                 <tr><td height="5px"></td></tr>
                 
                  <!--Student Part-->
                  <tr id="studentTRId" style="display:none;">
                   <td>
                   <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
                    <table border="0" cellpadding="0" cellspacing="0">
                       <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                       <tr height='5'></tr>
                       <?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
                       <tr height='5'></tr>
                       <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                       <tr height='5'></tr>
                       <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                        <td valign='top' colspan='10' class='' align='center'>
                         <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_students.gif" onClick="return validateCommonStudentList();return false;" />
                        </td>
                       </tr> 
                       <tr>
                        <td id="blockAllTdId" colspan="10" align="right">
                         <img src="<?php echo IMG_HTTP_PATH; ?>/blink.gif" onclick="return unBlockStudent();" title="Unblock All Students" />
                         <a href="javascript:void(0);" onclick="return unBlockStudent();" title="Unblock All Students"><b>Unblock All Students</b></a>
                         &nbsp;
                         |
                         &nbsp;
                         <img src="<?php echo IMG_HTTP_PATH; ?>/blink.gif" onclick="return blockAllStudents();" title="Block All Students" />
                         <a href="javascript:void(0);" onclick="return blockAllStudents();" title="Block All Students"><b>Block All Students</b></a>
                       </td>
                       </tr>
                       <tr>
                        <td colspan="10">
                           <div id="studentResultsDiv"></div>   
                        </td>
                       </tr>
                       <tr>
                        <td colspan="10" id="printRowId1" style="display:none" align="right">
                            <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
                        </td>
                       </tr>
                  </table>
                  </form>
                 </td>
                 </tr>
                 <!--Student Part Ends-->
                 
                 <!--Student Part-->
                  <tr id="employeeTRId" style="display:none;">
                   <td>
                   <form name="employeeDetailsForm" action="" method="post" onSubmit="return false;">
                    <table border="0" cellpadding="0" cellspacing="0">
                       <tr height='10'></tr> 
                       <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                       <tr height='5'></tr>
                       <?php echo $htmlFunctions->makeEmployeeAcademicSearch_feedback(false,'emp_','employeeDetailsForm'); ?>
                       <tr height='5'></tr>
                       <?php echo $htmlFunctions->makeEmployeeAddressSearch_feedback('emp_','employeeDetailsForm'); ?>
                       <tr height='5'></tr>
                       <?php echo $htmlFunctions->makeEmployeeMiscSearch_feedback('emp_'); ?>
                        <td valign='top' colspan='10' class='' align='center'>
                         <input type="image" name="employeeListSubmit" value="employeeListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_employees.gif" onClick="return validateEmployeeForm();return false;" />
                        </td>
                       </tr>
                       <tr>
                        <td colspan="10">
                           <div id="employeeResultsDiv"></div>   
                        </td>
                       </tr>
                       <tr>
                        <td colspan="10" id="printRowId2" style="display:none" align="right">
                            <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
                        </td>
                       </tr>
                  </table>
                  </form>
                 </td>
                 </tr>
                 <!--Student Part Ends-->
                 
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
    </table>


<?php floatingDiv_Start('studentUnBlockDiv','Unblock Students',1); ?>
    <form name="unblockFrm" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="userId" id="userId" value="" />
    <input type="hidden" name="mode" id="mode" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Enter Reason for Unblocking<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
   </tr>
   <tr>
        <td width="100%" class="contenttab_internal_rows">
         <textarea name="reason" id="reason" cols="40" rows="5" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
        </td>
   </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="1">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateUnblockForm();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('studentUnBlockDiv');return false;" />
    </td>
</tr>
<tr><td height="5px"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<?php floatingDiv_Start('studentAllUnBlockDiv','Unblock Students',2); ?>
    <form name="allUnblockFrm" action="" method="post" onsubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Enter Reason for Unblocking<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
   </tr>
   <tr>
        <td width="100%" class="contenttab_internal_rows">
         <textarea name="reason" id="reason" cols="40" rows="5" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
        </td>
   </tr>
   <tr>
    <td align="center" style="padding-right:10px" colspan="1">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return unBlockAllStudents();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('studentAllUnBlockDiv');return false;" />
    </td>
</tr>
<tr><td height="5px"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>