<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
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
                        <td class="content_title">Student Duty Leave Details : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm" id="searchForm" onsubmit="return false;"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table</b></nobr></td>
                        <td class="padding" align="left">: 
                        <select size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="autoPopulateEmployee(this.value);">
                         <option value="">Select</option>
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                         ?>
                        </select></td>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Teacher</b></nobr></td>
                        <td class="padding" colspan="3" align="left">: 
                        <select size="1" name="employeeId" id="employeeId" class="selectfield" onChange="autoPopulateClass(this.value);" >
                         <option value="">Select</option>
                        </select></td>
                    </tr>
                    <tr>    
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td class="padding" align="left">: 
                        <select size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                      </select></td>
                      <td class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                      <td class="padding" align="left"><nobr>: 
                      <select size="1" class="selectfield" name="subject" id="subject" onchange="populateGroups(document.searchForm.classId.value,this.value);" >
                        <option value="">Select Subject</option>
                        </select></nobr>
                      </td>
                      <td  style="padding-left:10px" class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                      <td class="padding" align="left">: 
                      <select size="1" class="selectfield" name="group" id="group" onchange="clearData(5);" >
                        <option value="">Select Group</option>
                        </select>
                      </td>
                     </tr>
                     <tr> 
                      
                      <td  class="contenttab_internal_rows"><nobr><b>Roll No.</b>
                      <br/>(Comma seperated)</nobr></td>
                      <td class="padding" colspan="4" align="left"><nobr>: 
                       <input type="text" name="rollNo" id="rollNo" autocomplete='off' class="inputbox"  style="width:560px"/></nobr>
                      </td>
                       <td  align="left" style="padding-left:15px;padding-right:5px" colspan="3">
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
                    </tr>
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
               <td class="contenttab_row" style="border-bottom-width:0px;">&nbsp;
                <div id="saveTr2" style="display:none;height:23px;" class="contenttab_border">
                <table border="0"  cellpadding="0" cellspacing="0" width="100%">
                <tr>
                 <td align="left" class="content_title">
                   Student Duty Leave List :
                 </td>
                 <td align="right">
                  <input type="image" name="imageField3" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return giveDutyLeaves();return false;" />
                  <input type="image" name="addCancel3" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="washOutData();;return false;" /></td>
                 </td>
                 </tr>
                 </table> 
                </div> 
               </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top">
                 <div id="results">
                </div>
             </td>
          </tr>
          <tr><td colspan="2" height="5px"></td></tr>
           <tr>
           <td align="right" style="padding-right:10px;display:none;" colspan="2" id="saveTr1">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return giveDutyLeaves();return false;" />
            <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="washOutData();;return false;" /></td>
           </tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: dutyLeaveEntryAdvancedContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/02/10   Time: 18:53
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//Bug ids---
//0002876
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 21/11/09   Time: 12:55
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//Bug ids :
//0002087 to 0002093
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 18/11/09   Time: 13:14
//Created in $/LeapCC/Templates/AdminTasks
//Modified Duty Leaves module in admin section
?>