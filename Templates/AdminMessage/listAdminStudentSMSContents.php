<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Institute Notices &nbsp;&raquo;&nbsp;Send  SMS to Students</td>
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
                        <td class="content_title">Send SMS : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" class="contenttab_row" style="border-bottom-width:0px">
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >
               <tr>
               <td valign="top" width="50%">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" >
                 <tr><td valign="top">
                  <div id="subjectDiv" >
                    <b>Subject : </b><input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:430px" maxlength="100">
                   </div> 
                  </td>
                 </tr>
                 <tr><td height="5px"></td></tr> 
                 <tr>
                    <td valign="top" width="40%" style="padding-left:5px">
                  <textarea id="elm1" name="elm1" style="height:100px;" rows="5" cols="57" onkeyup="smsCalculation(this.value,SMSML,'sms_no')" ></textarea>
                 </td>
               </td>
               </tr>
               <tr>
               <td valign="top">
               <div id="smsDiv" class="field3_heading"  style="width:50%" >
                SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
               &nbsp;&nbsp;&nbsp;SMS(s) :     <input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
              </div>
             </td>
            </tr> 
           </table>
          </td>
            <td align="left" valign="top">
            <form action="" method="" name="searchForm"> 
             <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr>    
                <td width="7%" class="contenttab_internal_rows1"><nobr><b>Class: </b></nobr></td>
                <td width="93%" class="padding">
                <select size="1" class="selectfield" name="class" id="class" onchange="deleteRollNo();" >
                <option value="" selected="selected">Select Class</option>
                <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFormattedClassData();
                ?>
                </select>
              </td>
            </tr>
             <tr>   
                <td width="10%"  align="left" class="contenttab_internal_rows1"><b>Roll No : </b></td>
                <td width="90%"  align="left"  class="padding">
                 <input type="text" name="studentRollNo" id="studentRollNo" class="inputbox">
           </tr>
              <tr>
                <td>&nbsp;</td>   
                <td  style="padding-left:5px" align="left"  >
                <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" /></td>
            </tr>
            </table>
            </form>
           </td>
          </tr> 
         </table> 
             </td>
             </tr>
             <tr>
                <td class="contenttab_row"  valign="top">&nbsp;  
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
// $History: listAdminStudentSMSContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/AdminMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/01/08    Time: 6:42p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/28/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/08    Time: 4:09p
//Updated in $/Leap/Source/Templates/AdminMessage
//Changed Image Name of submit button
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/AdminMessage
//corrected breadcrumb and reset button height and width
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 4:25p
//Created in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 3:04p
//Created in $/Leap/Source/Templates/AdminMessage
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