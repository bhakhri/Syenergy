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
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr>
        <td>
          <table border="0" cellspacing="0" cellpadding="0" width="100%"> 
          <tr>
        
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
               <!-- <td valign="top">Messaging &nbsp;&raquo;&nbsp;Send Message to Students</td> --->
                   <!--  <td valign="top" align="right">
             
                <form action="" method="" name="searchForm">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                
                  </td> --></tr>
         </table>
          
        </td>
    </tr> 
    <tr>
  <td valign="top"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
              <td valign="top" > 
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
            <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Send Message : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" class="contenttab_row" style="border-bottom-style:none;">
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >
              <tr><td valign="bottom" width="50%" colspan="1">
              <nobr>
               <div id="subjectDiv">
                <b>Subject : </b><input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:435px" maxlength="100">
               </div> 
              </nobr> 
              </td>
              <td valign="top" width="100%" style="padding-left:5px;padding-top:5px;">
                  <span><b>Message Medium :</b></span>
                   <input type="checkbox" id="dashBoardCheck" name="dashBoardCheck" value="1" onclick="dateDivShow();">DashBoard &nbsp;
                   <input type="checkbox" id="emailCheck" name="emailCheck" value="1" onclick="subjectDivShow();">E-Mail &nbsp;
                   <?php
                    if($sessionHandler->getSessionVariable('TEACHER_SMS_STUDENTS')==1){
                   ?>     
                    <input type="checkbox" id="smsCheck" name="smsCheck" value="1" onclick="smsDivShow();">SMS &nbsp;
                   <?php
                    }
                   else{ 
                   ?>
                   <input type="checkbox" id="smsCheck" name="smsCheck" value="1" onclick="smsDivShow();" style="display:none">
                   <?php
                   }
                   ?>
                 </td> 
              </tr>
              <tr><td height="5px" colspan="2"></td></tr> 
               <tr>
                <td valign="top" width="50%">
                <!--keyup event is handled in init() function-->
                 <textarea id="elm1" name="elm1" rows="10" cols="60" style="width: 100%" ></textarea>
                </td>
               <td valign="top" height="100%" style="padding-left:5px">
               <table cellpadding="0" cellspacing="0" border="0" height="200">
               <tr>
                <td valign="top">
                  <div id="dateDiv" style="display:none">
                     <table border="0" cellpadding="0" cellspacing="0"> 
                     <?php $thisDate=date('Y')."-".date('m')."-".date('d'); ?> 
                     <tr>
                     <td valign="top"><b>Visible From :</b>
                      <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->datePicker('startDate',$thisDate);
                      ?>
                    </td>  
                     <td valign="top" style="padding-left:5px"><b>Visible To :</b>
                      <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->datePicker('endDate',$thisDate);
                      ?>
                    </td>
                    </tr>
                    </table>
                  </div>
                </td>
               </tr> 
                <tr>
                 <td valign="top">
                   <form action="" method="" name="searchForm"> 
                   <table width="90%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
                        <td width="20%" class="padding"><nobr>: <select size="1" class="selectfield" name="class" id="class" onchange="deleteRollNo();populateSubjects(this.value);" >
                        <option value="">Select Class</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject</b></nobr></td>
                        <td width="20%" class="padding"><nobr>: <select size="1" class="selectfield" name="subject" id="subject" onchange="deleteRollNo();groupPopulate(this.value);" >
                        <option value="">Select Subject</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select></nobr>
                      </td>
                    </tr>
                    <tr>  
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                        <td width="20%" class="padding"><nobr>: <select size="1" class="selectfield" name="group" id="group" onchange="deleteRollNo();" >
                        <option value="">Select Group</option>
                          <?php
                           //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           //echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select></nobr>
                      </td>
                    <td align="left" class="contenttab_internal_rows"><b>Roll No</b></td>
                    <td align="left"  class="padding" colspan="3"><nobr>: 
                     <input type="text" name="studentRollNo" id="studentRollNo" autocomplete='off' class="inputbox" value="<?php echo $REQUEST_DATA['studentRollNo'];?>"></nobr>
                    </td>
                  </tr>
                  <tr>   
                    <td colspan="4" align="right" style="padding-right:5px" >
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                        </td>
                    </tr>
                    <tr><td colspan="4" height="5px"></td></td>    
                    </table>
                    </form>
                 </td>
                </tr>
               </table>    
               </td> 
               </tr>
              <tr>
              <td valign="top" width="100%" colspan="2">
               <div id="smsDiv" class="field3_heading"  style="width:50%;display:none" >
                 SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
                 &nbsp;&nbsp;&nbsp;SMS(s) :     <input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
                 </div>
             </td>
             </tr>                  
              </table>  
             </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp; 
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                <!--Do not delete-->
                 <input type="hidden" name="students" id="students" />
                 <input type="hidden" name="students" id="students" />  
                 <!--Do not delete-->
                 
                 <div id="results">
                </div>
                </form>           
                </td>
               </tr>
               <tr><td height="5px"></td></tr>
               <tr> 
                <td align="center">
                <div id="divButton" style="display:none">
                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateForm();return false;" />
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);return false;" />
                </div> 
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
// $History: listStudentMessageContents.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 29/01/10   Time: 15:56
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added checks---Subjects will be fetched based on selected class and
//groups will be fetched based on selected class and subject in 
//Send Message to Student and Parent modules
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/15/09   Time: 11:44a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//updated funtionality for 'send message to students' icon in footer
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added code for autosuggest functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/08/09   Time: 10:57
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected CSS class to comply with different themes 
//
//*****************  Version 2  *****************
//User: Administrator Date: 29/05/09   Time: 18:30
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "SMS" restriction codes
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/28/08    Time: 7:49p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/18/08    Time: 12:06p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:36p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//corrected breadcrumb and reset button height and width
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/12/08    Time: 2:51p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Done modifications as discussed in the demo session
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:53p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>