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
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                       <tr>
					   <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
              <!-- <td valign="top">Marks & Attendance&nbsp;&raquo;&nbsp;Grace Marks</td> -->
                <td valign="top" align="right">
                 <!-- 
                <form action="" method="" name="searchForm">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                   --> 
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
               <!-- <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Grace Marks : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td> -->
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" style="padding-left:10px;" >
             <form action="" method="" name="searchForm"> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                 <tr>
                     <td colspan="9" height="5px"></td>
                   </tr>
                    <tr>    
                       <td class="contenttab_internal_rows" align="left" colspan="9"><nobr><b>Note: Only those classes, subjects, groups will be shown for which the marks have been transferred.</b></nobr></td>
                    </tr>
                    <tr>
                     <td colspan="9" height="5px"></td>
                   </tr
                    <tr>    
                        <td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td  class="padding" align="left"><nobr>: 
                        <select size="1" class="selectfield" name="class" id="class" onchange="clearData();populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          require_once(MODEL_PATH.'/Teacher/TeacherManager.inc.php');
                          $activeTimeTable=TeacherManager::getInstance()->getActiveTimeTable();
                          $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
                          if($activeTimeTable[0]['timeTableLabelId']!='' and $employeeId!=''){
                           echo HtmlFunctions::getInstance()->getTransferredClasses($activeTimeTable[0]['timeTableLabelId'],$employeeId);
                          }
                        ?>
                      </select></td>
                        <td  class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td  class="padding" align="left"><nobr>: 
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="groupPopulate(this.value);" >
                        <option value="">Select Subject</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                        <td  class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                        <td  class="padding" align="left"><nobr>: 
                        <select size="1" class="selectfield" name="group" id="group" onchange="clearData();" >
                        <option value="">Select Group</option>
                        </select>
                      </td>
                    </tr>
                    <tr>    
                        <td  class="contenttab_internal_rows"><nobr><b>Roll No. </b>&nbsp;(Optional)</nobr></td>
                        <td  class="padding"  align="left"><nobr>: 
                        <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" style="width:185px" autocomplete='off' onchange="clearData();">
                        </td>
                        <td  align="left" style="padding-left:14px" >
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
                    </tr>
                    <tr>
                      <td  class="contenttab_internal_rows"><nobr><b>Grace Marks</b></nobr></td>
                      <td class="padding" width="1%"><b>:</b>&nbsp;<input type="text" id="graceMarksAll" name="graceMarksAll" class="inputbox" style="width:40px" onkeyup="setData(this.value);">
                       <span class="contenttab_internal_rows"><b>( for all )</b></span></td>
							  <td  class="contenttab_internal_rows" colspan="1"><nobr><b>Class Average (with Grace)</b><br><b>Class Average (without Grace)</b></nobr></td>
							  <td class="padding" width="1%">:&nbsp;<span id="classAverageSpan" class="contenttab_internal_rows">0.00</span><br>:&nbsp;<span id="classAverageSpan3" class="contenttab_internal_rows">0.00</span></td>
                      <td  class="contenttab_internal_rows"  align="left" colspan="1"></td>
                    </tr>    
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" style="padding-right:0px" style="width:100%" >&nbsp;
                <div id="headingDivId" class="contenttab_border content_title" style="text-align:left;display:none;">Student List</div>
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
          <tr style="display:none" id="buttonRow">
            <td align="center">
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
<?php
// $History: graceMarksContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/12/09    Time: 12:46
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//0002167,0002168,0002170 to 0002175
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Grace Marks Master"
?>