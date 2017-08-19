<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
require_once(BL_PATH.'/helpMessage.inc.php');    
?>
<form name="allDetailsForm" id="allDetailsForm" action="<?php echo HTTP_LIB_PATH;?>/AdminMessage/parentFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;">
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
                        <td class="content_title">Send Message : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" class="contenttab_row" style="border-bottom:none" >
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >

              <tr>
               <td valign="bottom" width="50%" >
               <nobr>
                <div id="subjectDiv">
                 <b>Subject : </b><input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:435px" maxlength="100">
                </div> 
               </nobr> 
              </td>
              
              <td valign="top" width="45%" style="padding-left:5px;padding-top:5px;">
                  <b>Send Messages via :<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                        echo HtmlFunctions::getInstance()->getHelpLink('Send Messages Via',HELP_MESSAGE_STUDENT);?></b>
                   <input type="checkbox" id="smsCheck" name="smsCheck" value="1" onclick="smsDivShow();">SMS &nbsp;
                   <input type="checkbox" id="emailCheck" name="emailCheck" value="1" onclick="subjectDivShow();">E-Mail &nbsp;
                   <input type="checkbox" id="dashBoardCheck" name="dashBoardCheck" value="1" onclick="dateDivShow();">DashBoard &nbsp;
                                
                 </td> 
              </tr>
              
              <tr>
                <td>&nbsp;</td>
                <td style="padding-left:5px">
                   <div id="dateDiv" style="display:none"> 
                     <table border="0" cellpadding="0" cellspacing="0"> 
                     <tr> 
                     <td valign="top"><b>Visible From :</b>
                      <input type="text" id="startDate" name="startDate" class="inputBox" readonly="true" value="<?php echo date('Y-m-d'); ?>" size="8" /><input type="image" id="calImg" name="calImg" title="Select Date" src="<?php echo IMG_HTTP_PATH;?>/calendar.gif"  onClick="return showCalendar('startDate', '%Y-%m-%d', '24', true);">                    </td>  
                     <td valign="top" style="padding-left:5px"><b>Visible To :</b>
                      <input type="text" id="endDate" name="endDate" class="inputBox" readonly="true" value="<?php echo date('Y-m-d'); ?>" size="8" /><input type="image" id="calImg" name="calImg" title="Select Date" src="<?php echo IMG_HTTP_PATH;?>/calendar.gif"  onClick="return showCalendar('endDate', '%Y-%m-%d', '24', true);">                    </td> 
                    </tr> 
                    </table> 
                  </div> 
                </td>
              </tr>    
              <tr><td height="5px" colspan="2"></td></tr> 
              <?php  
                require_once(TEMPLATES_PATH.'/smsTemplate.php');  
              ?>
	       
              <tr id="nameTinyMCE">
                <td valign="top" width="100%" colspan="2" style="padding-left:5px;padding-right:5px">
                <!--keyup event is handled in init() function-->
                 <textarea id="elm1" name="elm1" rows="10" cols="50" style="width:100%" class="mceEditor"></textarea>
                </td>                         
              </tr>  
	      <tr>
              <td valign="top" width="100%" colspan="2">
               <div id="smsDiv" class="field3_heading"  style="width:50%;display:none" >
                 SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
                 &nbsp;&nbsp;&nbsp;SMS(s) :     <input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
                 &nbsp;&nbsp;&nbsp;<nobr><b><?php echo REQUIRED_FIELD ?></b></nobr>   
                 <nobr><b><FONT COLOR="#FF0000"> #col# will be replaced by #Val</FONT> 
               </div>
             </td>
             </tr>              
              <tr>
              <td valign="top" width="100%" colspan="2">
             </td>
             </tr>                  
             </table>  
             </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  >
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
                <tr><b>Use the below criteria's to select the number of students to whom the message is to be sent</b><td height="5px"></td></tr>
                        <tr><td>
              
                <tr><td>
               <!--Add Student Filter-->
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                            <tr>
                                <td valign="top" class="contenttab_row1" align="center">
					
                                        <table border='0' width='100%' cellspacing='0'>
                                            <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                                            <tr>
                                                <td valign='top' colspan='10' class='' align='center'>
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                                </td>
                                            </tr>
                                        </table>
                                </td>
                            </tr>
                        </table>
               <!--Add Student Filter-->
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
                  <b></b><label id="totalRecordsId"></label></b> 
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="checkbox" id="sendToAllChk" value="sendToAllChk" style="display:none" />
                  <label for="sendToAllChk" style="display:none" ><b>Send to All</b></b><label>
                 </td>
                </tr> 
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                <!--Do not delete-->
                 <input type="hidden" name="fathers" id="fathers" />
                 <input type="hidden" name="fathers" id="fathers" />  
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
                 <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);resetForm();return false;" />
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
    <iframe id="uploadTarget" name="uploadTarget" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>  
</form>
 <!-- Help Div Starts -->
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
 <!-- Help Div Ends -->
<?php
// $History: scListAdminParentMessageContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 13/10/09   Time: 13:46
//Updated in $/LeapCC/Templates/AdminMessage
//Done bug fixing.
//Bug ids---
//00001774,00001775,00001776
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/08/09   Time: 10:57
//Updated in $/LeapCC/Templates/AdminMessage
//Corrected CSS class to comply with different themes 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 19/06/09   Time: 12:05
//Updated in $/LeapCC/Templates/AdminMessage
//Corrected "Html" codes
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 18/06/09   Time: 15:24
//Updated in $/LeapCC/Templates/AdminMessage
//Done bug fixing.
//bug ids---00000113,00000114,00000115,00000141,00000142,
//00000143,00000144,00000146,00000147
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/05/09   Time: 13:28
//Created in $/LeapCC/Templates/AdminMessage
//Created module  "Send Message for Parents"
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 2/11/09    Time: 3:15p
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Updated tinymce editor 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/09    Time: 6:15p
//Created in $/Leap/Source/Templates/ScAdminMessage
//new template for message to parent
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/15/09    Time: 5:57p
//Updated in $/Leap/Source/Templates/ScAdminMessage
//use student, dashboard, sms, email icons
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 30/12/08   Time: 14:44
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Corrected Image path
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 12/05/08   Time: 5:46p
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Suspend "Send To All" functionality temporarily as Told by Puspender
//sir
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/29/08   Time: 4:17p
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Corrected javascript error for IE
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/29/08   Time: 11:18a
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Added BackGround Process Capability
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/29/08    Time: 3:37p
//Updated in $/Leap/Source/Templates/ScAdminMessage
//Added Student Search Filter
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Created in $/Leap/Source/Templates/ScAdminMessage
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/29/08    Time: 11:37a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/28/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/08    Time: 4:09p
//Updated in $/Leap/Source/Templates/AdminMessage
//Changed Image Name of submit button
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/16/08    Time: 5:30p
//Updated in $/Leap/Source/Templates/AdminMessage
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/AdminMessage
//corrected breadcrumb and reset button height and width
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
