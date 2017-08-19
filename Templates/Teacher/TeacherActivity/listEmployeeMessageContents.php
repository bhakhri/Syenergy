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
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
         
            <tr>
         	 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
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
              <tr><td valign="bottom" colspan="1">
              <nobr>
               <div id="subjectDiv">
                <b>Subject : </b><input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:392px" maxlength="100">
               </div> 
              </nobr> 
              </td>
              <td valign="top" style="padding-left:5px;padding-top:5px;">
                  <span><b>Message Medium :</b></span>
                   <input type="checkbox" id="dashBoardCheck" name="dashBoardCheck" value="1" onclick="dateDivShow();">DashBoard &nbsp;
                   <input type="checkbox" id="emailCheck" name="emailCheck" value="1" onclick="subjectDivShow();">E-Mail &nbsp;
                   <?php
                    if($sessionHandler->getSessionVariable('TEACHER_SMS_COLLEAGUES')==1){
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
                <td>&nbsp;</td>
                <td style="padding-left:5px">
                   <div id="dateDiv" style="visibility:hidden"> 
                     <table border="0" cellpadding="0" cellspacing="0"> 
                     <tr> 
                     <td valign="top"><b>Visible From :</b>
                       <?php
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                        ?>
                      </td>  
                     <td valign="top" style="padding-left:5px"><b>Visible To :</b>
                      <?php
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                        ?>
                      </td> 
                    </tr> 
                    </table> 
                  </div> 
                </td>
              </tr>    
              <tr><td height="5px" colspan="2"></td></tr>  
               <tr>
                <td valign="top" width="100%" colspan="2">
                <!--keyup event is handled in init() function-->
                 <textarea id="elm1" name="elm1" rows="10" cols="120" ></textarea>
                 
                 <div id="smsDiv" class="field3_heading"  style="width:50%;display:none" >
                 SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
                 &nbsp;&nbsp;&nbsp;SMS(s) :     <input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
                 </div>
                 
                </td>
               
               </tr>
             <tr>
              <td valign="top" width="100%" colspan="2">
              <!--
               <div id="smsDiv" class="field3_heading"  style="width:50%;display:none" >
                 SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
                 &nbsp;&nbsp;&nbsp;SMS(s) :     <input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
                 </div>
              -->   
             </td>
             </tr>  
              </table>  
             </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                <form name="employeeDetailsForm" id="employeeDetailsForm" action="" method="post" onSubmit="return false;">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center">
                 <?php
                   $htmlFunctions=HtmlFunctions::getInstance();
                 ?>
                  <table border='0' width='100%' cellspacing='0'>
                   <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAcademicSearch(false,'emp_','employeeDetailsForm');
				   ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAddressSearch('emp_','employeeDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeMiscSearch('emp_'); ?>
                   <tr>
                    <td valign='top' colspan='8' class='' align='center'>
                     <input type="image" name="employeeListSubmit" value="employeeListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateEmployeeList();return false;" />
                     </td>
                    </tr>
                   </table>
                </form>   
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                <!--Do not delete-->
                 <input type="hidden" name="emps" id="emps" />
                 <input type="hidden" name="emps" id="emps" />  
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
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);resetForm();;return false;" />
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
// $History: listEmployeeMessageContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/08/09   Time: 10:57
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected CSS class to comply with different themes 
//
//*****************  Version 3  *****************
//User: Administrator Date: 29/05/09   Time: 18:30
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added "SMS" restriction codes
//
//*****************  Version 2  *****************
//User: Administrator Date: 14/05/09   Time: 16:36
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected html layout
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:49
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Added the functioanality "Send Message to Colleagues" In Teacher
//Section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:14
//Created in $/SnS/Templates/Teacher/TeacherActivity
//Added the functionaility of send message from teacher end
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:12
//Created in $/SnS/Templates/AdminMessage
//Added Sns System to VSS(Leap for Chitkara International School)
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Templates/AdminMessage
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/AdminMessage
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 9/08/08    Time: 4:47p
//Updated in $/Leap/Source/Templates/AdminMessage
//Modified so that "Student" and "Parent" role does not visible
//to the user.
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/08/08    Time: 4:06p
//Updated in $/Leap/Source/Templates/AdminMessage
//Updated according to Kabir Sir's suggestion
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/05/08    Time: 12:11p
//Updated in $/Leap/Source/Templates/AdminMessage
//Added employee search filter
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/29/08    Time: 11:37a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/28/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/21/08    Time: 4:09p
//Updated in $/Leap/Source/Templates/AdminMessage
//Changed Image Name of submit button
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/16/08    Time: 5:30p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/AdminMessage
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/11/08    Time: 4:24p
//Updated in $/Leap/Source/Templates/AdminMessage
?>