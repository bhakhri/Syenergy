<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
$queryString =  $_SERVER['QUERY_STRING'];
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
               <!-- <td valign="middle">Mail Box</td> -->
               <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="430">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Mail Box:</span></td>
                        <td class="content_title" title="Compose Message"><img src="<?php echo IMG_HTTP_PATH;?>/mail.gif" 
                        align="right" onClick="displayWindow('ParentTeacherActionDiv',680,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top"><div id="dhtmlgoodies_tabView1">
            
                   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                            <td><div id="LeaveTypeResultDiv"></div></td>
                        </tr>
                         
                      </table>                 
                   </div>
                   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                           <table width="100%" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                               <td><div id="sentItemResultsDiv"></div></td>
                           </tr>
                           </table>                 
                       </div>    
                   </div>
                    
                   <script type="text/javascript">
                    initTabs('dhtmlgoodies_tabView1',Array('Inbox','Sent Items'),0,985,365,Array(false,false));
                   </script>        
                   </td>
                   </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
 
<!--Start Add/Edit Div-->
<?php floatingDiv_Start('ParentTeacherActionDiv','',1); ?>
<!-- <form name="ParentTeacher" id="ParentTeacher" action="<?php echo HTTP_LIB_PATH;?>/Parent/messageFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline" target='fileUpload'> -->
<form name="ParentTeacher" id="ParentTeacher" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" > 
<iframe id='fileUpload' name='fileUpload' style="width:0px;height:0px;border:0px solid #fff;"></iframe>      
<input type="hidden" name="messageId" id="messageId" value="" />
<input type="hidden" name="receiverId" id="receiverId" value="" />
<input type="hidden" name="senderId" id="senderId" value="" />
<input type="hidden" name="troleId" id="troleId" value="" />    
<input type="hidden" name="attachmentFile" id="attachmentFile" value="" />
<input type="hidden" name="readStatus" id="readStatus" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr  id="replyMessage">
        <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Sent By</strong></nobr></td>
        <td  valign="middle" class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td  class="padding"><div id='senderName'></div></td>
    </tr>
    <tr  id="replyMessage_1">
        <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Sent On</strong></nobr></td>
        <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td class="padding"><div id='senderDate'></div></td>
    </tr>
    <tr  id="replyMessage_2">
        <td valign="middle">&nbsp;<strong>Attachment</strong></nobr></td>
        <td valign="middle"><nobr><strong>:</strong></nobr></td>
        <td valign="middle">
            <div id='editLogoPlace'></div>
        </td>
    </tr>
  
    <!--
    <tr>
        <td colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" id="replyMessage1">
            <tr>
                <td class="contenttab_internal_rows" width="25%" ><nobr><b>Select Teacher</b><?php echo REQUIRED_FIELD;?></nobr>&nbsp;&nbsp;</td>
                <td class="contenttab_internal_rows" width="2%" align="left" ><nobr>&nbsp;&nbsp;<b>:</b></nobr></td>
                <td class="contenttab_internal_rows" width="73%" >
                <select size="1" class="inputbox" name="teacherId" id="teacherId" style="width:200px">
                <?php
                      //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      //echo HtmlFunctions::getInstance()->getStudentTeacherData();
                ?>
                </select></td>
            </tr>
            </table>
        </td>
    </tr>
    -->
    <tr><td height="2px"></td></tr>
    <tr id="replyMessage4">  
        <td class="contenttab_internal_rows" width="25%"><nobr>&nbsp;<b>Role</b><?php echo REQUIRED_FIELD;?></nobr>&nbsp;&nbsp;</td>
        <td class="contenttab_internal_rows" width="2%" align="left" ><nobr><b>:</b></nobr></td>
        <td class="contenttab_internal_rows" width="73%" >
        <select size="1" class="inputbox" name="roleId" id="roleId" style="width:200px" onchange="getTeacher(); return false;">
        <option value="">Select</option>
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getRoleData(''," WHERE roleId in(select roleId from user u,employee e where u.userId=e.userId and e.visibleToParent=1) AND   	 								roleId NOT IN (1,3,4) ");
        ?>
        </select></td>    
    	</tr>

    <tr id="replyMessage1">
        <td class="contenttab_internal_rows" width="25%"><nobr>&nbsp;<b>User</b><?php echo REQUIRED_FIELD;?></nobr>&nbsp;&nbsp;</td>
        <td class="contenttab_internal_rows" width="2%" align="left" ><nobr><b>:</b></nobr></td>
        <td class="contenttab_internal_rows" width="73%" >
        <select size="1" class="inputbox" name="teacherId" id="teacherId" style="width:200px">
        <option value="">Select</option>
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getStudentTeacherData();
        ?>
        </select></td>
    </tr>

    <tr>
        <td class="contenttab_internal_rows" width="18%" >&nbsp;<nobr><b>Message Subject</b><?php echo REQUIRED_FIELD;?></nobr></td>
        <td class="contenttab_internal_rows" width="2%" ><nobr><strong>:</strong></nobr></td>
        <td class="contenttab_internal_rows" width="80%" >
        <input type="text" id="messageSubject" name="messageSubject"  style="width:270px" class="inputbox" maxlength="255"/></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Message</strong><?php echo REQUIRED_FIELD;?></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
        <td  class="contenttab_internal_rows" valign="top"><textarea id="messageText" name="messageText"  style="width:270px" class="inputbox" rows="5"/></textarea></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows">&nbsp;<nobr><strong>Attachment</strong></nobr></td>
        <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td  class="contenttab_internal_rows"><div id="editLogoPlace1" style="display:inline"><nobr>
            <span id="editLogoPlace" class="cl" onClick="this.style.display='none';"></span>
                <input type="file" id="noticeAttachment" name="noticeAttachment" class="inputbox" tabindex="15">
                <span style="padding-left:25px">(Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)</span>
            </div>
          <nobr>  
        </td>
    </tr>
    <tr>
        <td height="5px"></td>
    </tr>
    <tr>
        <td colspan="3" align="center">
        <table border="0" cellspacing="0" cellpadding="0"  width="100%" align="center">
        <tr>
            <td align="center" style="padding-right:10px" colspan="3" width="100%">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateAddForm(this.form,'Add');return false;"/>
            <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ParentTeacherActionDiv');{refreshMessageData();}return false;" /></td>
        </tr>
        </table>
        </td>
     </tr>
     <tr>
        <td height="5"></td>
     </tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ParentTeacherActionDiv1','',2); ?>

<form name="ParentTeacher1" id="ParentTeacher1" method="post" enctype="multipart/form-data" style="display:inline"> 
 
<input type="hidden" name="attachmentFile" id="attachmentFile" value="" />
<input type="hidden" name="readStatus" id="readStatus" value="" />

<input type="text" name="messageId1" id="messageId1" value="" />
<input type="text" name="receiverId1" id="receiverId1" value="" />
<input type="text" name="senderId1" id="senderId1" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5"></td>
    </tr>
    <tr>
        <td  valign="middle">&nbsp;<nobr><strong>Sent By</strong></nobr></td>
        <td  valign="middle"><nobr><strong>:</strong></nobr></td>
        <td  valign="middle" style="padding-left:64x"><div id='senderName'></div></td>
    </tr>
    <tr>
        <td height="5"></td>
    </tr>
    <tr>
        <td valign="middle">&nbsp;<nobr><strong>Sent On</strong></nobr></td>
        <td valign="middle"><nobr><strong>:</strong></nobr></td>
        <td valign="middle" style="padding-left:64x"><div id='senderDate'></div></td>
    </tr>
    <tr>
        <td height="5"></td>
    </tr>
     
     
    <tr>
        <td valign="middle">&nbsp;<strong>Attachment</strong></nobr></td>
        <td valign="middle"><nobr><strong>:</strong></nobr></td>
        <td valign="middle"><div id='editLogoPlace2'></div></td>
    </tr>
    <tr>
        <td height="5"></td>
    </tr>
     
    <tr>
        <td  class="contenttab_internal_rows">&nbsp;<nobr><strong>Message Subject</strong><?php echo REQUIRED_FIELD;?></nobr></td>
        <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td  class="padding"><input type="text" id="messageSubject" name="messageSubject"  style="width:510px" class="inputbox" maxlength="255"/></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Message</strong><?php echo REQUIRED_FIELD;?></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
        <td  class="padding" valign="top"><textarea id="messageText" name="messageText"  style="width:510px" class="inputbox" rows="5"/></textarea></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Attachment</strong></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
        <td  class="padding" valign="top"><input type="file" id="noticeAttachment" name="noticeAttachment" class="inputbox" tabindex="15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>(Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)<div id="editLogoPlace1" class="cl" onClick="this.style.display='none';"></div></td>
    </tr>
    <tr>
        <td height="5px"><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe></td>
    </tr>
    <tr>
        <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
            <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ParentTeacherActionDiv');{refreshMessageData();}return false;" /></td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('SentDataActionDiv','',3,''); ?>
<table width="100%" border="0" cellspacing="5px" cellpadding="5px" class="border">
    <tr>
        <td width="20%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Receiver</strong></nobr></td>
        <td width="2%" class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td width="78%" class="contenttab_internal_rows"><div id="senderNameEmployee"></div></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Sent On</strong></nobr></td>
        <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td class="contenttab_internal_rows"><div id="senderDateEmployee"></div></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows">&nbsp;<nobr><strong>Message Subject</strong></nobr></td>
        <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td  class="contenttab_internal_rows">
           <div id="messageDiv11" style="overflow:auto; width:350px; vertical-align:top; "> 
              <div id="senderSubjectEmployee"></div>
           </div>  
        </td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Message</strong></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
        <td  class="contenttab_internal_rows" valign="top">
            <div id="noticeSubject" style="overflow:auto; height:150px; width:350px; vertical-align:top; " >
            <div id="senderMessageEmployee"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Attachment</strong></nobr></td>
        <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
        <td class="contenttab_internal_rows"><div id="editLogoPlaceEmployee" class="cl"></div></td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
</table>
<?php floatingDiv_End(); ?>
<!--End Add Div-->


<?php floatingDiv_Start('InboxDataActionDiv','',4,''); ?>
    <table width="100%" border="0" cellspacing="5px" cellpadding="5px" class="border">
        <tr>
            <td width="20%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Receiver</strong></nobr></td>
            <td width="2%" class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
            <td width="78%" class="contenttab_internal_rows"><div id="senderName1"></div></td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Sent On</strong></nobr></td>
            <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
            <td class="contenttab_internal_rows"><div id="senderDate1"></div></td>
        </tr>
        <tr>
            <td  class="contenttab_internal_rows">&nbsp;<nobr><strong>Message Subject</strong></nobr></td>
            <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
            <td  class="contenttab_internal_rows">
              <div id="messageDiv12" style="overflow:auto; width:350px; vertical-align:top; " > 
                <div id="senderText1"></div>
              </div>  
            </td>
        </tr>
        <tr>
            <td  class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Message</strong></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
            <td  class="contenttab_internal_rows" valign="top">
                <div id="noticeSubject1" style="overflow:auto; height:150px; width:350px; vertical-align:top; " >
                <div id="senderSubject1"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Attachment</strong></nobr></td>
            <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
            <td class="contenttab_internal_rows"><div id="editLogoPlace22"></div></td>
        </tr>
        <tr>
            <td height="5px"></td></tr>
        <tr>
    </table>
<?php floatingDiv_End(); ?>
<!--End Add Div-->
