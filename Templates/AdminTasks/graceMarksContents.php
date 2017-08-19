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
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
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
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Grace Marks : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" style="padding-left:10px;" >
             <form action="" method="" name="searchForm"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                   <tr>
                     <td colspan="9" height="5px"></td>
                   </tr>
                    <tr>    
                       <td class="contenttab_internal_rows" align="left" colspan="9"><nobr><b>Note: Only those classes, subjects, groups will be shown which are mapped to active time table and for which marks has been transferred. </b></nobr></td>
                    </tr>
                    <tr>
                     <td colspan="9" height="5px"></td>
                   </tr>
                    <tr>
						<td class="contenttab_internal_rows" align="left"><nobr><b>Time Table</b></nobr></td>
						<td class="contenttab_internal_rows" width="1%"><b>:</b></td>
					    <td  class="padding" align="left">
                        <select size="1" class="selectfield" name="labelId" id="labelId">
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getTimeTableLabelData();
						?>
						<td class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                        <td  class="padding" align="left">
                        <select size="1" class="selectfield" name="class1" id="class1" style="width:250px" onFocus= "getClasses();" onchange="clearData();subjectPopulate(this.value);" >
                        <option value="">Select Class</option>
                        <!--<?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          require_once(MODEL_PATH.'/Teacher/TeacherManager.inc.php');
                          $activeTimeTable=TeacherManager::getInstance()->getActiveTimeTable();
                          if($activeTimeTable[0]['timeTableLabelId']!=''){
                           echo HtmlFunctions::getInstance()->getTransferredClasses($activeTimeTable[0]['timeTableLabelId']);
                          }
                        ?>-->
                      </select></td>
                        <td  class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                        <td  class="padding" align="left">
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="groupPopulate(this.value);" >
                        <option value="">Select Subject</option>
                          <?php
                          //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          //echo HtmlFunctions::getInstance()->getAllTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                    </tr>
                    <tr> 
					    <td  class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                        <td  class="padding" align="left">
                        <select size="1" class="selectfield" name="group" id="group" onchange="clearData();" >
                        <option value="">Select Group</option>
                        </select>
                        </td>
                        <td  class="contenttab_internal_rows"><nobr><b>Roll No.</b>&nbsp;(Optional)</nobr></td>
                        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                        <td  class="padding"  align="left">
                        <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" style="width:246px" autocomplete='off' onchange="clearData();">
                        </td>
                        <!--<td colspan="2"></td>-->
                        <td  align="left" style="padding-left:0px" colspan="3" >
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
                    </tr>
                    <tr>
                      <td  class="contenttab_internal_rows"><nobr><b>Grace Marks</b></nobr></td>
                      <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                      <td  class="padding"  align="left" >
                        <input type="text" id="graceMarksAll" name="graceMarksAll" class="inputbox" style="width:40px" onkeyup="setData(this.value);">
                       <span class="contenttab_internal_rows"><b>( for all )</b></span></td>
							  <td  class="contenttab_internal_rows" colspan="1"><nobr><b>Class Average (with Grace)</b><br><b>Class Average (without Grace)</b></nobr></td>
							  <td class="contenttab_internal_rows" width="1%"><b>: <br>:</b></td>
                      <td  class="contenttab_internal_rows"  align="left" colspan="1"><b><span id="classAverageSpan">0.00</span><br><span id="classAverageSpan3">0.00</span></b></td>
                    </tr>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" style="padding-right:0px" style="width:100%" >&nbsp;
                <div id="headingDivId" class="contenttab_border content_title" style="text-align:left;display:none;">
                 <table border="0" cellpadding="0" cellspacing="0" width="100%">
                 <tr>
                  <td align="left">Student List</td>
                  <td align="right">
                   <input type="image" name="imageField2" id="imageField2" onClick="saveData();return false" src="<?php echo IMG_HTTP_PATH;?>/save.gif" />
                   <input type="image" name="imageField3" id="imageField3" onClick="clearData();return false" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" />&nbsp;
                  </td>
                 </tr>
                 </table> 
                  </div>
                <form name="listFrm" id="listFrm">
                 <div id="results"></div>
                <!--Do Not Delete-->
                   <input type="hidden"  name="student" id="student" value="1">
                   <input type="hidden"  name="student" id="student" value="1">
                  <!--Do Not Delete-->
               </form> 
             </td>
          </tr>
          <tr><td height="5px"></td></tr>
          <tr style="display:none" id="buttonRow" valign="top">
            <td align="center" class="contenttab_internal_rows" width="100%">
					<table border='0' cellspacing='0' cellpadding='0' width="100%">
						<tr>
							<td valign='top' colspan='1' class='' align="right" width="50%">
							  <input type="image" name="imageField2" id="imageField2" onClick="saveData();return false" src="<?php echo IMG_HTTP_PATH;?>/save.gif" />
							  <input type="image" name="imageField3" id="imageField3" onClick="clearData();return false" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" />
							</td>
						</tr>
					</table>
				</td>
          </tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: graceMarksContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/12/09   Time: 10:26
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//Bug ids---
//0002244,0002245,0002246,0002248,0002249
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/12/09    Time: 16:58
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//Bug ids---
//0002170
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/12/09    Time: 12:46
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//Bug ids---
//0002167,0002168,0002170 to 0002175
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/AdminTasks
//added code for autosuggest functionality
//
//*****************  Version 1  *****************
//User: Administrator Date: 4/06/09    Time: 10:47
//Created in $/LeapCC/Templates/AdminTasks
//Created grace marks module in admin end
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Grace Marks Master"
?>