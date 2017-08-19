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
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Test Marks :<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 12px; color: black;">&nbsp;To move the cursor up and down while entering marks use up and down arrow respectively. </td>
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
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table<?php echo REQUIRED_FIELD;?></b></nobr></td>
                        <td><b>: </b></td>
						<td class="padding" align="left">
                        <select size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="autoPopulateEmployee(this.value);">
                         <option value="">Select</option>
                         <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                         ?>
                        </select></td>
                        <td class="contenttab_internal_rows" align="left" style="padding-left:10px"><nobr><b>Teacher<?php echo REQUIRED_FIELD;?></b></nobr></td>
                       <td><b>: </b></td>
						<td class="padding" colspan="4">
                        <select size="1" style="width:467px" name="employeeId" id="employeeId" class="selectfield" onChange="autoPopulateClass(this.value);" >
                         <option value="">Select</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td class="contenttab_internal_rows" align="left"><nobr><b>Class<?php echo REQUIRED_FIELD;?></b></nobr></td>
                        <td><b>: </b></td>
						<td class="padding" align="left">
                        <select size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                      </select></td>
                      <td  style="padding-left:10px" class="contenttab_internal_rows" style="padding-left:10px"><nobr><b>Subject<?php echo REQUIRED_FIELD;?> </b></nobr></td>
                      <td><b>: </b></td>
					  <td class="padding">
                      <select size="1" class="selectfield" name="subject" id="subject" onchange="populateGroups(document.searchForm.classId.value,this.value);testTypePopulate(this.value);" >
                        <option value="">Select Subject</option>
                        </select>
                      </td>
                      <td  style="padding-left:10px" class="contenttab_internal_rows"><nobr><b>Group<?php echo REQUIRED_FIELD;?> :</b></nobr></td>
					  <td class="padding" align="left">
                      <select size="1" class="selectfield" name="group" id="group" onchange="clearData(5);" >
                        <option value="">Select Group</option>
                        </select>
                      </td>
                     </tr>
                    <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Test Type<?php echo REQUIRED_FIELD;?></b></nobr></td>
                      <td><b>: </b></td>
					  <td width="20%" class="padding">
                       <select size="1" class="selectfield" name="testType" id="testType" onchange="populateTest(this.value,1);" >
                       <option value="" selected="selected">Select</option>
                       <?php
                            //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            //echo HtmlFunctions::getInstance()->getTestTypeCategory("WHERE examType='PC' AND showCategory=1",'');
                       ?>
                      </select>
                     </td>
                    <td width="10%" class="contenttab_internal_rows" style="padding-left:10px"><nobr><b>Test<?php echo REQUIRED_FIELD;?></b></nobr></td>
                    <td><b>: </b></td>
					<td width="20%" class="padding" >
                     <select size="1" class="selectfield" name="test" id="test" onchange="populateTestDetails(this.value);" >
                      <option value="" selected="selected">SELECT</option>
                      <option value="NT">Create New Test</option>
                     </select>
                   </td>
                   <td align="left" >
                     <input type="image" style="display:none;" id="deleteTestIcon" name="deleteTestIcon" onClick="deleteData(document.searchForm.test.value,document.searchForm.test.selectedIndex);return false" title="Delete Test Data" src="<?php echo IMG_HTTP_PATH;?>/delete.gif" />
                   </td>
                    <td  align="left" style="padding-left:5px" >
                        <input type="image" id="imageField1" name="imageField1" onClick="getData();checkData('maxMarks');return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                     </td>
                  </tr>
                  <tr>
                   <td width="100%"  colspan="8" align="center" style="padding:5px;">
                    <div id="testDesc" style="display:none;width:80%;border:1px solid black;">
                     <table cellpadding="0" cellspacing="0" border="0" class="field3_heading" >
                      <tr>
                       <td ><b bgcolor="#708090">Test Abbr<?php echo REQUIRED_FIELD;?>:</b></td>
					   <td class="padding" align="left">
                        <input type="text" id="testAbbr" name="testAbbr" class="inputbox" maxlength="10" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <b>Max Mark<?php echo REQUIRED_FIELD;?>:</b>
                        &nbsp;&nbsp;
                        <input type="text" id="maxMarks" name="maxMarks" class="inputbox" style="width:60px" onchange="checkMarks(this.id);"onkeyup="checkNumber(this.value,this.id);" maxlength="5" />
                      </td>
                      <td align="left" colspan="2">
                       <b>Test Date<?php echo REQUIRED_FIELD;?>:</b>
                        &nbsp;&nbsp;
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         $thisDate=date('Y')."-".date('m')."-".date('d');
                         echo HtmlFunctions::getInstance()->datePicker('testDate',$thisDate);
                        ?>
                       </td>
                      </tr>
                      <tr>
                       <td ><b>Test Topic :</b></td>
                       <td class="padding" align="left">
                        <input type="text" id="testTopic" name="testTopic" class="inputbox" maxlength="100" style="width:400px;" />
                       </td>
                      <td align="left">
                       <b>Test Index  :</b>
                       &nbsp; <input type="text" id="testIndex" name="testIndex" class="inputbox" maxlength="3" style="width:30px;" onkeyup="checkNumber(this.value,this.id);" disabled="true" />
                      </td>
                       </td>
                      </tr>
					  <tr id="testRowId3">
					  <td ><b>Comment  :</b></td>
					  <td class="padding" align="left" colspan="3"><nobr>
                        <input type="text" id="comments" name="comments" class="inputbox" maxlength="100" style="width:500px;" /></nobr>
                       </td>
					  <td>&nbsp;<input type="image" id="imageField4" name="imageField4" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></td>
					  <td></td>

                   <tr>
                     </table>
                    </div>
                   </td>
                  </tr>
                   <tr>
                   <!--
                    <td colspan="5">&nbsp;</td>
                    <td  align="left" style="padding-left:5px" >
                        <input type="image" id="imageField1" name="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                    </td>
                   -->
                    </tr>
                    </table>
                    </form>
                </td>
             </tr>
              <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                <!--Do Not Delete-->
                 <input type="hidden" name="mem">
                 <input type="hidden" name="mem">
                <!--Do Not Delete-->
                 <div id="results">
                </div>
                </form>
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr>
                <td align="center">
                  <input type="image" id="imageField2"  name="imageField2" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm();return false;" />
                  <input  type="image" id="imageField3"  name="imageField3" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);resetForm(); return false;" />
				  <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title = "Print">
                 </td>
               </tr>
               <tr><td height="5px"></td></tr>
              </table>
              </div>
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
// $History: listEnterAssignmentMarksContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Templates/AdminTasks
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/30/09   Time: 10:14a
//Updated in $/LeapCC/Templates/AdminTasks
//Updated Breadcrumb
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/AdminTasks
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 11:18
//Created in $/LeapCC/Templates/AdminTasks
//Created "Test Marks" module in admin section
//
//*****************  Version 2  *****************
//User: Administrator Date: 27/05/09   Time: 12:32
//Updated in $/LeapCC/Templates/AdminTasks
//Added "comments" field in duty leave module in admin & teacher section
//
//*****************  Version 1  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Created in $/LeapCC/Templates/AdminTasks
//Created "Duty Leave" Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/05/09   Time: 18:58
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Duty Leave" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Grace Marks Master"
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified to show group by selecting subject
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:49p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/08/08    Time: 11:45a
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
?>