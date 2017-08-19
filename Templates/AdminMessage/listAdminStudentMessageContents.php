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
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
require_once(TEMPLATES_PATH . "/breadCrumb.php");   
?>
    <tr>
        <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
             <td valign="top" class="contenttab_border2" style="border-bottom-style:none;">
              <table cellpadding="0" cellspacing="0" border="0" width="100%" class="contenttab_internal_rows">

              <tr>
               <td valign="bottom" width="50%" >
               <nobr>
                <div id="subjectDiv">
                 <b>Subject : </b><input type="text" name="msgSubject" id="msgSubject" class="inputbox" style="width:435px" maxlength="100">
                </div> 
               </nobr> 
              </td>
              
              <td valign="top" width="45%" style="padding-left:5px;padding-top:5px;">
                  <b>Message Medium :</b>
                   <input type="checkbox" id="smsCheck" name="smsCheck" value="1" onclick="smsDivShow();">SMS &nbsp;
                   <input type="checkbox" id="emailCheck" name="emailCheck" value="1" onclick="subjectDivShow();">E-Mail &nbsp;
                   <input type="checkbox" id="dashBoardCheck" name="dashBoardCheck" value="1" onclick="dateDivShow();">DashBoard &nbsp;
                                
                 </td> 
              </tr>
               <tr><td height="5px" colspan="2"></td></tr> 
              <tr>
                <td>&nbsp;</td>
                <td style="padding-left:5px">
                   <div id="dateDiv" style="display:none"> 
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
                    </tr> 
                    </table> 
                  </div> 
                </td>
              </tr> 
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
				<td style="padding-left:5px"><b>
				Note: <br>1. Text effect (such as italics / bold), smileys will not be displayed in SMS.
					  <br>2. Subject will not be displayed in SMS.
				</b>
				</td>
			 </tr>
   
             <tr>
              <td colspan="2" style="padding:5px" valign="top" >
               <!--Add Student Filter-->
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                            <tr>
                                <td valign="top" class="contenttab_row1" align="center">
                                    <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
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
                                    </form>
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
                  <input type="checkbox" id="sendToAllChk" value="sendToAllChk" />
                  <label for="sendToAllChk"><b>Send to All</b></b><label>
                 </td>
                </tr> 
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

<?php
// $History: listAdminStudentMessageContents.php $
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 13/10/09   Time: 13:46
//Updated in $/LeapCC/Templates/AdminMessage
//Done bug fixing.
//Bug ids---
//00001774,00001775,00001776
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 17/08/09   Time: 10:57
//Updated in $/LeapCC/Templates/AdminMessage
//Corrected CSS class to comply with different themes 
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 7/16/09    Time: 10:13a
//Updated in $/LeapCC/Templates/AdminMessage
//Updated Editor with class="mceEditor" in send message modules
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 19/06/09   Time: 12:05
//Updated in $/LeapCC/Templates/AdminMessage
//Corrected "Html" codes
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 18/06/09   Time: 15:24
//Updated in $/LeapCC/Templates/AdminMessage
//Done bug fixing.
//bug ids---00000113,00000114,00000115,00000141,00000142,
//00000143,00000144,00000146,00000147
//
//*****************  Version 6  *****************
//User: Administrator Date: 30/05/09   Time: 10:40
//Updated in $/LeapCC/Templates/AdminMessage
//Added BloodGroup wise search in messaging and delete/restore student
//modules
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 2/04/09    Time: 15:09
//Updated in $/LeapCC/Templates/AdminMessage
//Added datePicker
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Templates/AdminMessage
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/03/08   Time: 5:44p
//Updated in $/LeapCC/Templates/AdminMessage
//Added Common Student Filter
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/03/08   Time: 5:18p
//Updated in $/LeapCC/Templates/AdminMessage
//Create Send Message to All
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/AdminMessage
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
