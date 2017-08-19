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
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Messaging &nbsp;&raquo;&nbsp;Send Message to Students</td>
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
                  <span class="fontTitle">Message Medium :</span>
                   <input type="checkbox" id="smsCheck" name="smsCheck" value="1" onclick="smsDivShow();">SMS &nbsp;
                   <input type="checkbox" id="emailCheck" name="emailCheck" value="1" onclick="subjectDivShow();">E-Mail &nbsp;
                   <input type="checkbox" id="dashBoardCheck" name="dashBoardCheck" value="1" onclick="dateDivShow();">DashBoard &nbsp;
                                
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
                     <?php $thisDate=date('Y-m-d');?> 
                     <tr>
                     <td valign="top" class="fontTitle" valign="middle" >From :
                      <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->datePicker('startDate',$thisDate);
                      ?>
                    </td>  
                     <td valign="top" class="fontTitle" style="padding-left:5px">To :
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
                   <form name="searchForm" id="searchForm" action="" method=""> 
                   <table width="90%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>    
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                        <td width="20%" class="padding">
                        <select size="1" class="selectfield" name="subject" id="subject" onchange="deleteRollNo();classPopulate();" >
                        <option value="">Select Subject</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
                        ?>
                        </select>
                      </td>
                        <td width="10%" class="contenttab_internal_rows"><nobr><b>Section: </b></nobr></td>
                        <td width="20%" class="padding">
                        <select size="1" class="selectfield" name="section" id="section" onchange="deleteRollNo();classPopulate();" >
                        <option value="">Select Section</option>
                          <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getTeacherSectionData();
                        ?>
                        </select>
                      </td>
                    </tr>
                    <tr>  
                    <td width="10%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
                        <td width="20%" class="padding">
                         <select size="1" class="selectfield" name="classes" id="classes" onchange="deleteRollNo();" >
                         <option value="">All</option>
                         </select>
                        </td>  
                    <td align="left" class="contenttab_internal_rows"><b>Roll No : </b></td>
                    <td align="left"  class="padding" colspan="3">
                     <input type="text" name="studentRollNo" id="studentRollNo" class="inputbox">
                    </td>
                  </tr>
                  <tr>   
                    <td colspan="4" align="right" style="padding-right:5px" >
                         <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                        </td>
                    </tr>
                    <tr><td colspan="4" height="5px"></td></tr>    
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
                <form name="listFrm" id="listFrm"  action="<?php echo HTTP_LIB_PATH;?>/Teacher/ScTeacherActivity/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">  
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                   <div id="uploadFileDiv" style="display:none;">
                       <b>Upload Attachment :</b>&nbsp;
                       <input type="file" id="msgLogo" name="msgLogo" class="inputbox">
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       &nbsp;&nbsp;&nbsp;&nbsp; Maximum File Size : <?php echo round(MAXIMUM_FILE_SIZE/(1024*1024),2); ?> MB
                    </div>
                 </td>
                </tr>
                <tr><td height="5px"</td></tr>
                <tr><td>
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <!--Do not delete-->
                 <input type="hidden" name="students" id="students" />
                 <input type="hidden" name="students" id="students" />  
                 <!--Do not delete-->
                  <div id="results">
                  </div>
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
              </td></tr>
              </table>
              
              <iframe id="uploadTarget" name="uploadTarget" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
             </form>            
             
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
// $History: scListStudentMessageContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/04/08   Time: 11:31a
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Added functionality for sending attachment with messages from teacher
//to students and parents
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
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
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