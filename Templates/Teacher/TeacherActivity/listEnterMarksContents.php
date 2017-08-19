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
                <td valign="top">Class Related Activities  &nbsp;&raquo;&nbsp;Enter Marks</td>
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
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
                        <td width="20%" class="padding"><select size="1" class="selectfield" name="class" id="class"  >
                        <option value="" selected="selected">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                       </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                        <td width="20%" class="padding"><select size="1" class="selectfield" name="subject" id="subject" onchange="populateTestType(this.value);" >
                        <option value="" selected="selected">Select Subject</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Group: </b></nobr></td>
                        <td width="20%" class="padding"><select size="1" class="selectfield" name="group" id="group" >
                        <option value="" selected="selected">Select Group</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select>
                      </td>
                      <td width="10%" class="contenttab_internal_rows"><nobr><b>Test Type: </b></nobr></td>
                      <td width="20%" class="padding">
                       <select size="1" class="selectfield2" name="testType" id="testType" onchange="populateTest(this.value,1);" >
                       <option value="" selected="selected">SELECT</option>
                      </select>
                     </td>
                    </tr>
                    <tr>
                    <td width="10%" class="contenttab_internal_rows"><nobr><b>Test: </b></nobr></td>
                    <td width="20%" class="padding" colspan="7">
                     <select size="1" class="selectfield" name="test" id="test" onchange="populateTestDetails(this.value);" >
                      <option value="" selected="selected">SELECT</option>
                      <option value="NT">Create New Test</option>
                     </select>
                   </td>
                  </tr>
                  <tr>
                   <td width="100%"  colspan="8" align="center" style="padding:5px;">
                    <div id="testDesc" style="display:none" >
                     <table cellpadding="0" cellspacing="0" border="0" style="border:1px solid black;">
                      <tr>
                       <td class="contenttab_internal_rows"><b>Test Abbr:</b></td>
                       <td class="padding" align="left">
                        <input type="text" id="testAbbr" name="testAbbr" class="inputbox" maxlength="10" />
                       </td>  
                       <td class="contenttab_internal_rows"><b>Max Mark:</b></td>
                       <td class="padding">
                        <input type="text" id="maxMarks" name="maxMarks" class="inputbox" style="width:60px" onkeyup="checkNumber(this.value,this.id);" maxlength="5" />
                       </td>  
                       <td class="contenttab_internal_rows"><b>Test Date:</b></td>
                       <td class="padding">
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         $thisDate=date('Y')."-".date('m')."-".date('d');
                         echo HtmlFunctions::getInstance()->datePicker('testDate',$thisDate);
                        ?>
                       </td>  
                      </tr>
                      <tr>
                       <td class="contenttab_internal_rows"><b>Test Topic:</b></td>
                       <td class="padding" align="left">
                        <input type="text" id="testTopic" name="testTopic" class="inputbox" maxlength="100" style="width:485px;" />
                       </td>
                       <td class="contenttab_internal_rows"><b>Test Index:</b></td>
                        <td class="padding" colspan="3" align="left">
                        <input type="text" id="testIndex" name="testIndex" class="inputbox" maxlength="3" style="width:30px;" onkeyup="checkNumber(this.value,this.id);" />
                       </td>
                      </tr> 
                     </table>
                    </div>
                   </td>
                  </tr>
                    
                     <tr>
                        <td height="10"></td>
                    </tr>
                     <tr>
                        <td  align="right" style="padding-right:5px" colspan="8">
                        <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/save.gif" /></td>
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
                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateForm();return false;" />
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);return false;" />
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

<?php floatingDiv_Start('CommentDiv','Your Comments'); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <form name="frmComment" action="" method="post">  
    <tr><td height="3px"></td></tr> 
    <tr><td colspan="2"  align="left" style="padding-left:3px"><b>Comments:</b></td></tr>
    <tr><td height="3px"></td></tr>
    <tr>
      <td colspan="2" align="center" style="padding-left:3px"> 
       <textarea name="teacherComment" id="teacherComment"  cols="25" rows="5"></textarea> 
    </td>
 </tr>
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('CommentDiv');return false;" />
    </td>
</tr>
<tr><td height="5px"></td></tr>

</form>
</table>
<?php floatingDiv_End(); ?>
<!--End Add Div-->    

<?php
// $History: listEnterMarksContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:36p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//corrected breadcrumb and reset button height and width
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