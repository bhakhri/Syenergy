<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR entering marks
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Marks & Attendance &nbsp;&raquo;&nbsp;External Marks</td>
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
                        <td class="content_title">Enter Marks : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <td class="contenttab_border1" >
             <form action="" method="" name="searchForm">
               <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                        <td width="20%" class="padding">
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="populateTestType(this.value,document.searchForm.classes.value,1);classPopulate();" >
                        <option value="" selected="selected">Select Subject</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Section: </b></nobr></td>
                        <td width="20%" class="padding">
                        <select size="1" class="selectfield" name="section" id="section" onchange="classPopulate();">
                        <option value="" selected="selected">Select Section</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSectionData();
                        ?>
                        </select>
                      </td>
                      <td width="10%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
                      <td width="20%" class="padding">
                       <select size="1" class="selectfield" name="classes" id="classes"  onchange="populateTestType(document.searchForm.subject.value,this.value,2);" >
                        <option value="">All</option>
                       </select>
                      </td>
                    </tr>
                    <tr>
                     <td width="10%" class="contenttab_internal_rows"><nobr><b>Test Type: </b></nobr></td>
                      <td width="20%" class="padding">
                       <select size="1" class="selectfield2" name="testType" id="testType" onchange="populateTest(this.value,1);" >
                       <option value="" selected="selected">SELECT</option>
                      </select>
                     </td>
                    <td width="10%" class="contenttab_internal_rows"><nobr><b>Test: </b></nobr></td>
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
                        <input type="image" id="imageField1" name="imageField1" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                     </td>
                  </tr>
                  <tr>
                   <td width="100%"  colspan="8" align="center" style="padding:5px;">
                    <div id="testDesc" style="display:none;width:80%;border:1px solid black;">
                     <table cellpadding="0" cellspacing="0" border="0" class="field3_heading" >
                      <tr>
                       <td ><b bgcolor="#708090">Test Abbr:</b></td>
                       <td class="padding" align="left">
                        <input type="text" id="testAbbr" name="testAbbr" class="inputbox" maxlength="10" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <b>Max Mark:</b>
                        &nbsp;&nbsp;
                        <input type="text" id="maxMarks" name="maxMarks" class="inputbox" style="width:60px" onkeyup="checkNumber(this.value,this.id);" maxlength="5" />
                      </td>
                      <td align="left" colspan="2">
                       <b>Test Date:</b>
                        &nbsp;&nbsp;
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         $thisDate=date('Y')."-".date('m')."-".date('d');
                         echo HtmlFunctions::getInstance()->datePicker('testDate',$thisDate);
                        ?>
                       </td>  
                      </tr>
                      <tr>
                       <td ><b>Test Topic:</b></td>
                       <td class="padding" align="left">
                        <input type="text" id="testTopic" name="testTopic" class="inputbox" maxlength="100" style="width:400px;" />
                       </td>
                      <td align="left">
                       <b>Test Index:</b>
                       &nbsp; <input type="text" id="testIndex" name="testIndex" class="inputbox" maxlength="3" style="width:30px;" onkeyup="checkNumber(this.value,this.id);" disabled="true" />
                      </td>
                      <td align="right"> 
                       <input type="image" id="imageField4" name="imageField4" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                       </td>
                      </tr> 
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
// $History: scListEnterExternalMarksContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/03/08   Time: 5:35p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Created this file for test and marks deletion functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/19/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//'Select Class' to 'All' according to Sachin Sir
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
?>