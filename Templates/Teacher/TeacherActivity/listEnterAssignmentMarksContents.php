<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR entering marks
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php
    require_once(BL_PATH.'/helpMessage.inc.php');
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">

            <tr>
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
</tr>

               <!-- <td valign="top">Marks & Attendance  &nbsp;&raquo;&nbsp;Test Marks</td> -->
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
                <td class="contenttab_border" height="20">

                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title" width="80%">Enter Marks :<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 12px; color: black;">&nbsp;To move the cursor up and down while entering marks use up and down arrow respectively.</td>
                        <td style="padding-right:10px" align="right" class="content_title">
                           <a href="#" onclick="getHelpImageDownLoad('teacher-test-marks.jpg','TeacherTestMarks'); return false;" name="">Help</a>
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <td class="contenttab_border1" >
             <form action="" method="" name="searchForm">
               <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" >
                    <tr>
                        <td class="contenttab_internal_rows" width="3%"><nobr><b>Class<?php echo REQUIRED_FIELD;
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Class',HELP_TEST_MARKS_CLASS);
                                     ?></b></nobr></td>
                        <td class="padding" width="5%"><nobr>:
                        <!--<select size="1" class="selectfield" name="class" id="class" onchange="blankValues(1);populateSubjects(this.value);groupPopulate(this.form.subject.value);"  >-->
                        <select size="1" class="selectfield" name="class" id="class" onchange="blankValues(1);populateSubjects(this.value);"  >
                        <option value="" selected="selected">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                       </select></nobr>
                      </td>
                        <td class="contenttab_internal_rows"  width="2%" style="padding-left:5px"><nobr><b>Subject<?php
                                        echo REQUIRED_FIELD; require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_TEST_MARKS_SUBJECT);
                                     ?></b></nobr></td>
                        <td class="padding" width="4%"><nobr>: <select size="1" class="selectfield" name="subject" id="subject" onchange="blankValues();groupPopulate(this.value);testTypePopulate(this.value);">
                        <option value="" selected="selected">Select Subject</option>
                          <?php
                          // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                        <td class="contenttab_internal_rows"  width="2%" style="padding-left:5px"><nobr><b>Group<?php
                                        echo REQUIRED_FIELD; require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Group',HELP_TEST_MARKS_GROUP);
                                     ?></b></nobr></td>
                        <td class="padding" width="1%" style="padding-right:0px"><nobr>: <select size="1" class="selectfield" name="group" id="group"  onchange="blankValues(1);" style="width:195px;">
                        <option value="" selected="selected">Select Group</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select></nobr>
                      </td>
                    </tr>
                    <tr>
                     <td class="contenttab_internal_rows"><nobr><b>Test Type<?php
                                        echo REQUIRED_FIELD; require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Test Type',HELP_TEST_MARKS_TYPES);
                                     ?></b></nobr></td>
                      <td class="padding"><nobr>:
                       <select size="1" class="selectfield" name="testType" id="testType" onchange="blankValues();populateTest(this.value,1);" >
                       <option value="" selected="selected">SELECT</option>
					   <?php
							//require_once(BL_PATH.'/HtmlFunctions.inc.php');
							//echo HtmlFunctions::getInstance()->getTestTypeCategory("WHERE examType='PC' AND showCategory=1",'');
					   ?>
                      </select></nobr>
                     </td>
                    <td class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Test<?php
                                       echo REQUIRED_FIELD; require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Test',HELP_TEST_MARKS_TEST);
                                     ?></b></nobr></td>
                    <td class="padding"> <nobr>:
                     <select size="1" class="selectfield" name="test" id="test" onchange="populateTestDetails(this.value);" >
                      <option value="" selected="selected">SELECT</option>
                      <option value="NT">Create New Test</option>
                     </select></nobr>
                   </td>
                   <td align="left" >
                     <table>
                       <tr>
                          <td align="left">
                     <img style="display:none;" id="deleteTestIcon" name="deleteTestIcon" onClick="deleteData(document.searchForm.test.value,document.searchForm.test.selectedIndex);return false" title="Delete Test Data" src="<?php echo IMG_HTTP_PATH;?>/delete.gif" />
                            </td>
                            <td align="left" style="display:none;" id="deleteTestIcon1">
                             <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getHelpLink('Delete Test Data',HELP_TEST_MARKS_DELETE);
                             ?>
                            </td>
                         </tr>
                       </table>
                    </td>
                    <td  align="left" style="padding-left:15px" width="2%" >
                        <input type="image" id="imageField1" name="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                     </td>
                  </tr>
                  <tr id="testRowId1" style="display:none;background-color:#E5E5E5">
                       <td class="contenttab_internal_rows"><nobr><b>Test Abbr.<?php echo REQUIRED_FIELD;
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Test Abbreviation',HELP_TEST_MARKS_ABBR);
                                     ?>&nbsp;</b></nobr></td>
                       <td class="padding" align="left"><nobr>
                        <b>:</b>&nbsp;<input type="text" id="testAbbr" name="testAbbr" class="inputbox" maxlength="10" style="width:200px;" />
                        </nobr>
                        </td>
                       <td class="contenttab_internal_rows" style="padding-left:5px">
                       <nobr><b>Max Mark<?php echo REQUIRED_FIELD;
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Max. Marks',HELP_TEST_MARKS_MAX);
                                     ?></b></nobr>
                        </td>
                        <td class="padding" align="left">:
                        <input type="text" id="maxMarks" name="maxMarks" class="inputbox" style="width:60px" onchange="checkMarks(this.id);" onkeyup="checkNumber(this.value,this.id);" maxlength="5" />
                      </td>
                      <td align="left" class="contenttab_internal_rows" style="padding-left:5px">
                       <nobr><b>Test Date<?php echo REQUIRED_FIELD;
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->getHelpLink('Test Date',HELP_TEST_MARKS_DATE);
                                     ?></b></nobr>
                        </td>
                        <td class="padding">:
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         $thisDate=date('Y')."-".date('m')."-".date('d');
                         echo HtmlFunctions::getInstance()->datePicker('testDate',$thisDate);
                        ?>
                       </td>
                      </tr>
                      <tr id="testRowId2" style="display:none;background-color:#E5E5E5">
                       <td class="contenttab_internal_rows"><b>Test Topic<?php
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Test Topic',HELP_TEST_MARKS_TOPIC);
                                     ?></b></nobr></td>
                       <td class="padding" align="left" colspan="3"><nobr>:
                        <input type="text" id="testTopic" name="testTopic" class="inputbox" maxlength="100" style="width:500px;" /></nobr>
                       </td>
                      <td align="left" class="contenttab_internal_rows" style="padding-left:5px">
                       <nobr><b>Test Index<?php
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getHelpLink('Test Index',HELP_TEST_MARKS_INDEX);
                                     ?></b>
                       </td>
                       <td class="padding">:
                       <input type="text" id="testIndex" name="testIndex" class="inputbox" maxlength="3" style="width:30px;" onkeyup="checkNumber(this.value,this.id);" disabled="true" />
                      </td>
                      </tr>
					  <tr id="testRowId3" style="display:none;background-color:#E5E5E5">
					  <td class="contenttab_internal_rows"><b>Comment</b></td>
					  <td class="padding" align="left" colspan="3"><nobr>:
                        <input type="text" id="comments" name="comments" class="inputbox" maxlength="100" style="width:500px;" /></nobr>
                       </td>
					  <td>&nbsp;<input type="image" style="margin-bottom:-5px" id="imageField4" name="imageField4" onClick="getData();checkData('maxMarks');return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></td>
					  <td></td>

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
                <table id="divButton1" border="0" cellpadding="0" cellspacing="0" width="100%" height="30px" style="display:none" >
                   <tr class="contenttab_border">
                     <td class="content_title" align="left">List of Students : </td>
                     <td align="right">
                     <input type="image" name="imageField44" id="imageField44" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateForm('listFrm');return false;" style="margin-bottom:-3px" />
                     <input type="image" name="imageField55" id="imageField55" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="hide_div('showList',2);resetForm(); return false;" style="margin-bottom:-3px" />
					 <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title = "Print" style="margin-bottom:-3px">
                   </td>
                 </tr>
                 <tr>
                  <td colspan="2">
                   <div id="results"></div>
                  </td>
                  </tr>
                 </table>
                </form>
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr>
                <td align="right">
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


<!--Assignments Marks Help  Details  Div-->
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
<!--Assignments Marks Help  Details  End -->


<?php
// $History: listEnterAssignmentMarksContents.php $
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 18/02/10   Time: 14:39
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected html coding to stop duplicate group fetching
//
//*****************  Version 17  *****************
//User: Gurkeerat    Date: 12/04/09   Time: 5:46p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//updated look n feel of help dialog box
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 2/12/09    Time: 11:24
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look & feel
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 1/12/09    Time: 17:09
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made UI changes in test marks module in teacher module
//
//*****************  Version 14  *****************
//User: Parveen      Date: 11/06/09   Time: 3:37p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//alignment formatting updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 11/06/09   Time: 12:20p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//help link added
//
//*****************  Version 12  *****************
//User: Parveen      Date: 11/02/09   Time: 3:29p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Help Div function added (showHelpDetails)
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 31/07/09   Time: 11:29
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Woked on client issues.
//Issues taken care of ---4,5,7,10
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/04/09    Time: 16:09
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added class check during group populate
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 4/04/09    Time: 3:28p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//show test type as per theory or practical subject
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 3/27/09    Time: 2:34p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified in test type category
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified to show group by selecting subject
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified for test type & put test type category
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/09/08   Time: 3:10p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected Marks modules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/08   Time: 4:41p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "SC" enhancements
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/30/08    Time: 11:26a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:36p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//corrected breadcrumb and reset button height and width
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 1:32p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/08    Time: 7:59p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/25/08    Time: 2:54p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/24/08    Time: 11:58a
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:57p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>