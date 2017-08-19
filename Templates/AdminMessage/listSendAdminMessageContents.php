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
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Send Message :</td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" class="contenttab_row" style="border-bottom-style:none;">
              <table cellpadding="0" cellspacing="0" border="0" width="100%" >
               <tr>
                <td valign="top" width="100%" colspan="2" style="padding-left:5px;padding-right:5px">
                <!--keyup event is handled in init() function-->
                 <textarea id="elm1" name="elm1" rows="10" cols="50" style="width:100%" class="mceEditor"></textarea>
                </td>                         
              </tr>
              <tr><td height="5px"></td></tr>
              <tr>
              <td valign="top" width="100%" colspan="2" style="padding-left:2px;">
               <div id="smsDiv" class="field3_heading"  style="width:50%;" >
                 No Of Characters :&nbsp;<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />
                 &nbsp;&nbsp;&nbsp;No Of SMS(s) required to send this message:&nbsp;<input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" />
                 </div>
             </td>
             </tr>                  
             
             <tr>
              <td colspan="2" style="padding:5px" valign="top" >
              <table border="0" cellpadding="0" cellspacing="0">
               <tr>
                 <td class="contenttab_internal_rows"><b>Enter comma separated mobile numbers of persons you want to send message to </b></td>
               </tr>
               <tr>
                <td class="padding">
                 <textarea name="mobileNoTxt" id="mobileNoTxt" cols="115" rows="5"></textarea>
                </td>
              </table>
              </td>
             </tr>
              </table>  
             </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" align="center" >
                 <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/send_sms.gif"  onclick="sendMessage();return false;" />
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
// $History: listSendAdminMessageContents.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/01/10   Time: 14:14
//Created in $/LeapCC/Templates/AdminMessage
//Created "Send SMS" modules for sending SMSs to numbers entered by the
//end user
?>