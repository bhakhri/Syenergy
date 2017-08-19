<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <td valign="top" align="right">
            <tr>
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>
			
                 <input type="hidden" name="startDate" id="startDate" value="<?php echo date('Y-m-d'); ?>" />
                 <input type="hidden" name="endDate" id="endDate" value="<?php echo date('Y-m-d'); ?>" />
               </td>
            </tr><tr><td height=2></tr>
            </table>
        </td>
    </tr>

         
       
          <td  class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
               
				
      <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
    
             <tr>
             <td  align="center" height=0 valign="top">
             <form action="" method="" name="searchForm" id="searchForm" onsubmit="return false;"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
					<td class="contenttab_border1" valign="top">
					 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
					 <tr>
                        <td width="14%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding" align="left" style="padding-right:10px"><nobr>:
                        <select size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);groupPopulate(this.form.subject.value);resetForm();" >
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr>
                      </td>
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding" align="left" style="padding-right:10px"><nobr>:
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="groupPopulate(this.value);resetForm();">
                        <option value="">Select Subject</option>
                        </select></nobr>
                      </td>
                        <td width="5%" class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding" align="left" ><nobr>:&nbsp;
                        <select size="1" class="selectfield" name="group" id="group"  onchange="resetForm();">
                        <option value="">Select Group</option>
                        </select></nobr>
                      </td>
                     </tr>
					 </tr>
                     <tr> 
                      
                      <td class="contenttab_internal_rows"><nobr><b>Roll No.</b>
                      <?php 
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('Roll No',HELP_DUTY_LEAVES_ROLL_NO);
                      ?>
                      <br/>(Comma seperated)</nobr>
                      </td>
                      <td class="padding" colspan="4" align="left"><nobr>: 
                       <input type="text" name="rollNo" id="rollNo" autocomplete='off' class="inputbox"  style="width:560px"/></nobr>
                      </td>
                       <td  align="left" style="padding-left:15px;padding-right:5px" colspan="3">
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
                    </tr>
                  
                    <tr><td colspan="5" height="5px"></td><td width="41%"></td>    
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
           <td align="center" style="padding-right:10px;display:none;" colspan="2" id="saveTr1">
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
    
<!--Duty Leave Help Details Div(Roll No:)-->
<?php  floatingDiv_Start('divHelpInfo','Help','1','','','1'); ?>
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
// $History: dutyLeaveEntryAdvancedContents.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/04/09   Time: 5:46p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//updated look n feel of help dialog box
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 23/11/09   Time: 10:24
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//0002107,0002106
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 21/11/09   Time: 15:02
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "Help" option in "Duty Leaves" module in teacher section.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/11/09   Time: 15:27
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Completed/Modified duty leaves module in teacher end
?>