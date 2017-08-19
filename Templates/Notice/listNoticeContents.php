<?php
//This file creates Html Form output in Notice Module
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<style>
.imgLinkRemove{
    cursor: default;
}
</style>
<?php
    require_once(BL_PATH.'/helpMessage.inc.php');
	require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
                   <td colspan="2">  
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
                        <tr>
                        <td class="content_title" width="15%" valign="top" nowrap><nobr>
                         <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?> 
                        </nobr></td>
                        <td class="content_title" width="20%" valign="middle" style="color:black;font-size:11px;" nowrap>
                           <b>Note&nbsp;:&nbsp;&nbsp;&nbsp;</b>Show notices added by user of that institute.
                        </nobr>
                        </td>
                        <td width="14%" class="content_title" align="right" nowrap style="border-left:0px;margin-right:5px;">
                    <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddNoticeDiv','',600,400,screen.width/4.8, screen.height/10);blankValues();getAllClass('U','Add');return false;" />&nbsp;</td>
                        </tr>
                        </table>
                    </td>
                  </tr>  
             <tr>
								<td class="contenttab_row" colspan="3" valign="top" >
                                    <!-- <div id="scroll2" style="overflow:auto; height:410px; vertical-align:top;"> -->
                                       <div id="results"></div>
                                     <!-- </div>   -->
                                 </td>
          </tr>
           <tr>
								<td align="right" colspan="3">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                            </td>
                        </tr>
                    </table>
                </td>
           </tr>
          </table>
        </td>
    </tr>
    </table>
		</td>
	</tr>
</table>


<?php floatingDiv_Start('AddNoticeDiv','Add Notice'); ?>
<!-- <form name="addNotice" id="addNotice" action="<?php echo HTTP_LIB_PATH;?>/Notice/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline"> -->                                                     
<div id="personalScrol2" style="overflow:auto; width:850px; height:420px">
<form name="addNotice" id="addNotice" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
<table width="100%" border="0px" cellspacing="0" cellpadding="0" class="border">
<tr>
  <td class="contenttab_internal_rows"><b>Notice Subject<?php echo REQUIRED_FIELD;
  require_once(BL_PATH.'/HtmlFunctions.inc.php');
  echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_NOTICE_SUBJECT);
 ?></b></td>
  <td width="5%"><b>:</td>
  <td colspan="65%" class="contenttab_internal_rows">
  <input type="text"  class="inputbox" name="noticeSubject" id="noticeSubject" style="width:680px" maxlength="255" onkeydown="return sendKeys(1,'noticeSubject',event);"/>
  </td>
</tr>
<tr>
  <td class="contenttab_internal_rows" valign="top"><strong><br>Notice Text<?php echo REQUIRED_FIELD;
  require_once(BL_PATH.'/HtmlFunctions.inc.php');
  echo HtmlFunctions::getInstance()->getHelpLink('Notice Text',HELP_NOTICE_TEXT); ?></strong><br>
  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">
  Kindly Do not copy and<br>paste from any text<br> editor except NOTEPAD<br>
  otherwise the text may be<br>wrongly formatted.<br>
  To format the text use<br>the tools provided<br>at the top.
    </span>            
  </td>
  <td class="contenttab_internal_rows" width="5%" valign="top"><br><b>:</td>
  <td colspan="3" class="contenttab_internal_rows">
    <!-- <textarea cols="56" rows="8"  id="noticeText" name="noticeText" ></textarea> -->
    <textarea id="elm11" name="elm11" class="tiny1" style="width:100%" ></textarea>
   </td>
</tr>
<tr>
  <td class="contenttab_internal_rows"><strong>Attachment</strong>
  <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
  echo HtmlFunctions::getInstance()->getHelpLink('Attachment',HELP_NOTICE_ATTACHMENT); ?></td>
  <td><b>:</td>
  <td class="contenttab_internal_rows" nowrap>
    <nobr>
    <table border="0" cellpadding="0" cellspacing="0"> 
      <tr >
        <td class="contenttab_internal_rows"  width="250px" nowrap><nobr>
            <input type="file" id="noticeAttachment" name="noticeAttachment" class="inputbox" tabindex="15" >
          </nobr>
        </td>
        <td class="contenttab_internal_rows"  ><nobr> 
	      (Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)</nobr>
        </td>
        <td class="contenttab_internal_rows" style='padding-left:35px;'><nobr> 
  	        <strong>Allow User to Download</strong></nobr>
        </td>    
        <td class="contenttab_internal_rows"><nobr> 
            <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getHelpLink('Allow User to Download',HELP_NOTICE_DOWNLOAD); ?>
        </td>
        <td class="contenttab_internal_rows"><nobr> 
              <strong>:&nbsp;</strong></nobr>
        </td> 
        <td class="contenttab_internal_rows"><nobr>
            <input name="downloadOption" id="downloadOption1" value="1"  type="radio" checked="checked">Yes&nbsp;
            <nobr>
        </td>
        <td class="contenttab_internal_rows"><nobr>                   
            <input name="downloadOption" id="downloadOption2" value="2"  type="radio">No
           <nobr>
        </td>
     </tr>
   </table>
   </nobr>         
  </td>	
</tr>

<tr>
      <td  class="contenttab_internal_rows"><nobr><b>Notice by Department<?php echo REQUIRED_FIELD;
      require_once(BL_PATH.'/HtmlFunctions.inc.php');
      echo HtmlFunctions::getInstance()->getHelpLink('Notice By Department',HELP_NOTICE_BY_DEPARTMENT);
      ?></b></nobr></th>
      <td class="contenttab_internal_rows" nowrap><b>:&nbsp;</b></td>
      <td class="contenttab_internal_rows" nowrap colspan="10">
        <table border="0" cellpadding="0" cellspacing="0"> 
          <tr>
            <td class="contenttab_internal_rows" nowrap>
                <select name="departmentId" id="departmentId" size="1" class="selectfield" style="width:150px" >
                    <option value="">Select</option>
                    <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      echo HtmlFunctions::getInstance()->getDepartmentData();
                    ?>
                </select>
            </td>
            <td class="contenttab_internal_rows" style="padding-left:20px" nowrap><strong>Visible</strong></td>
            <td class="contenttab_internal_rows" nowrap>
                <?php 
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getHelpLink('Visible From/To',HELP_NOTICE_VISIBLE); 
                ?>
            </td>
            <td class="contenttab_internal_rows" nowrap><b>:&nbsp;</b></td>
            <td class="contenttab_internal_rows" nowrap>&nbsp;From&nbsp;</td>
            <td class="contenttab_internal_rows" nowrap>
                    <?php
                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                     echo HtmlFunctions::getInstance()->datePicker('visibleFromDate',date('Y')."-".date('m')."-".date('d'));
                    ?>      
            </td>
            <td class="contenttab_internal_rows" nowrap>&nbsp;To&nbsp;</td>
            <td class="contenttab_internal_rows" nowrap>
                    <?php
                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                     echo HtmlFunctions::getInstance()->datePicker('visibleToDate',date('Y')."-".date('m')."-".date('d'));
                    ?>      
            </td>
            <td class="contenttab_internal_rows" style="padding-left:20px" nowrap><strong>Visible Mode</strong></td>
            <td class="contenttab_internal_rows" nowrap><b>:&nbsp;</b></td>
            <td class="contenttab_internal_rows" nowrap>
                <select name="visibleMode" id="visibleMode" size="1" class="selectfield" style="width:100px" >
                    <option value="1">New</option>
                    <option value="2">Important</option>
                    <option value="3">Urgent</option>
                </select>
            </td>
          </tr>
        </table>
        </nobr>
      </td>    
 </tr>
 <tr><td height="5px"></td></tr>
<tr>
  <td class="contenttab_internal_rows" align="left" valign="top"><nobr>
      <table border="0" width="100%" align="left" cellspacing="0px" cellpadding="0px">
        <tr>
           <td class="contenttab_internal_rows" align="left" valign="top">
             <b>Notice Visible To</b>
           </td>
        </tr>
        <tr> 
           <td class="contenttab_internal_rows" align="left" valign="top">    
<input name="noticePublishedTo" id="noticePublishedTo1" value="1" checked="checked" onclick="getNoticePublish(this.value,'A');" type="radio">Role&nbsp;     <input name="noticePublishedTo" id="noticePublishedTo2" value="2" onclick="getNoticePublish(this.value,'A');" type="radio">Institute
           </td>
        </tr>
      </table>        
  </nobr>
  </td>
  <td  class="contenttab_internal_rows" align="left" valign="top"><b><nobr>:</nobr></b></td>
  <td  class="contenttab_internal_rows" colspan="3" align="left" rowspan="1" valign="top">  
      <table border="0" width="100%" align="left" class="contenttab_border" id="showRoleInstituteAdd" style="display:none">
         <tr>
            <td width="2%" class="contenttab_internal_rows" style="padding-left:5px" nowrap align="left"><b>Institute</b></td>  
            <td width="98%" class="contenttab_internal_rows"  style="padding-left:15px"  align="left" rowspan="2" valign="top">  
              
            </td>
         </tr>   
         <tr>
            <td class="contenttab_internal_rows" nowrap align="left" valign="top">
               <select size="6" multiple="multiple" class="selectfield" name="noticeInstituteId" id="noticeInstituteId" style="width:250px">
                 <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getInstituteData();
                  ?>
                </select>
               <div>
               Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("noticeInstituteId","All","addNotice");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("noticeInstituteId","None","addNotice");'>None</a></div>
            </td>
         </tr> 
      </table>   
      
      <table border="0" width="10%" align="left" class="contenttab_border" id="showRoleNoticeAdd">
        <tr>
            <td class="contenttab_internal_rows"    style="padding-left:5px" align="left"><b>Role</b></td>
            <td class="contenttab_internal_rows"  align="left" rowspan="2" valign="top">
                 <table border="0" width="100%" align="left" class="contenttab_border"> 
                    <tr> 
                       <td class="contenttab_internal_rows"  align="left"  valign="top"><b>University</b></td> 
                       <td class="contenttab_internal_rows" style="padding-left:8px" align="left" rowspan="6" valign="top">
                         <b>Class</b><br>
                         <select multiple="multiple" class="selectfield" name="classId" id="classId" size='6' style='width:280px'>
                            <?php
                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getAllClassDataNew();
                            ?>
                            </select>
                           <div>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("classId","All","addNotice");'>All</a> /
                           <a class="allReportLink" href='javascript:makeSelection("classId","None","addNotice");'>None</a></div>
                       </td>
                       
                    </tr>   
                    <tr>
                       <td class="contenttab_internal_rows" align="left" >
                          <select name="universityId" id="universityId" size="1" class="selectfield" style="width:120px" onChange="getAllClass('U','Add');">
                            <option value="NULL">ALL</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getUniversityAbbr();
                            ?>
                          </select>
                       </td>
                    </tr>
                    <tr>  
                       <td class="contenttab_internal_rows"  align="left"><b>Degree</b></td>
                    </tr>
                    <tr>   
                       <td class="contenttab_internal_rows" align="left">
                           <select name="degreeId" id="degreeId" size="1" class="selectfield" style="width:120px" onChange="getAllClass('D','Add');">
                                <option value="NULL">ALL</option>
                                <?php
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getInstituteDegreeAbbr();
                                ?>
                             </select>  
                       </td>
                     </tr>
                     <tr>  
                       <td class="contenttab_internal_rows"  align="left"><nobr><strong>Branch</strong></nobr></td>
                     </tr>  
                     <tr>
                        <td class="contenttab_internal_rows" align="left">
                          <select name="branchId" id="branchId" size="1" class="selectfield" style="width:120px"  onChange="getAllClass('B','Add');">
                             <option value="NULL">ALL</option>
                          </select>
                       </td> 
                    </tr>
                 </table>
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows" align="left" valign="top" style="padding-left:5px">  
                <select size="6" multiple="multiple" class="selectfield" name="roleId" id="roleId" style="width:250px">
                 <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['roleId']==''? $eventRecordArray[0]['roleId'] : $REQUEST_DATA['roleId'] );
                  ?>
                </select>
               <div>
               Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("roleId","All","addNotice");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("roleId","None","addNotice");'>None</a></div>
          </td>
        </tr>
      </table>
 </td>
</tr>  
<tr><td colspan="3" height="5px"></td></tr>
<?php  if($sessionHandler->getSessionVariable('SMS_ALERT_FOR_NOTICE_UPLOAD') == 1) {?>
<tr>
  <td width="30%" class="contenttab_internal_rows"><b>Send SMS</b> <input type="checkbox" id="smsStatus" onclick="enableAddTextBox();"></td>
  <td width="5%"><b>:</td>
  <td colspan="65%" class="contenttab_internal_rows">
  <input type="text"  class="inputbox" name="smsText" disabled="disabled" id="smsText" style="width:480px" onclick="clearTextBox();" maxlength="140"/>
    <span style='padding-left:20px;'>(Max.140 chars)<span>
  </td>
</tr>
<?php }
 else{ ?>
<tr>
  <td width="30%" nowrap class="contenttab_internal_rows" valign="top">
    <span style="float:left;position:relative;top:8px"><b>Send SMS</b>
     <input type="checkbox" id="smsStatus" disabled="disabled" onclick="enableAddTextBox();">
    </span>
  </td>
  <td width="5%" class="contenttab_internal_rows"  nowrap valign="top"><b><span style="float:right;position:relative;top:8px">:</span></td>
  <td colspan="65%" height="50px"  class="contenttab_internal_rows">
  <input type="text"  class="inputbox" name="smsText" disabled="disabled" id="smsText" style="width:480px" onclick="clearTextBox();" maxlength="140"/>
    <span style='padding-left:15px;'>(Max.140 chars)<span>
  <div style="margin-top:5px" class='redLink'><table border='1' cellspacing='0' cellpadding='0' rules="all" style="border-collapse:collapse;" align="center" bgcolor="#FFFF99" width="100%">
            <tr>
                <td valign='top' colspan='1'>
                    <strong>&nbsp;Note:&nbsp;</strong>SMS functionality is currently disabled. To enable kindly check the Config Settings 
 (SMS Alerts tab)</td></tr></table></div>
  </td>
</tr>
<?php } ?>
<tr><td colspan="3" height="5px"></td></tr> 
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/publish_notice.gif" onClick="return validateAddForm(this.form,'Add');" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddNoticeDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />

    </td>
</tr>
</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
</div>       
<?php floatingDiv_End(); ?>




<?php floatingDiv_Start('EditNoticeDiv','Edit Notice'); ?>
<div id="personalScroll12" style="overflow:auto; width:850px; height:420px">
<!-- <form name="editNotice" id="editNotice"  action="<?php echo HTTP_LIB_PATH;?>/Notice/fileUpload.php" enctype="multipart/form-data" method="post" style="display:inline"> -->
<form name="editNotice" id="editNotice" method="post" enctype="multipart/form-data" style="display:inline width:400px;" onSubmit="return false;" >
<input type="hidden"  name="noticeId" id="noticeId" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
  <td width="30%" class="contenttab_internal_rows"><b>Notice Subject<?php echo REQUIRED_FIELD ?></b></td>
  <td width="5%"><b>:</td>
  <td colspan="65%" class="contenttab_internal_rows">
  <input type="text"  class="inputbox" name="noticeSubject" id="noticeSubject" style="width:680px" maxlength="255" onkeydown="return sendKeys(1,'noticeSubject',event);"/>
  </td>
</tr>
<tr>
<td class="contenttab_internal_rows" valign="top"><strong><br>Notice Text<?php echo REQUIRED_FIELD;
  require_once(BL_PATH.'/HtmlFunctions.inc.php');
  echo HtmlFunctions::getInstance()->getHelpLink('Notice Text',HELP_NOTICE_TEXT); ?></strong><br>
  <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">
  Kindly Do not copy and<br>paste from any text<br> editor except NOTEPAD<br>
  otherwise the text may be<br>wrongly formatted.<br>
  To format the text use<br>the tools provided<br>at the top.
    </span>            
  </td>
  <td class="contenttab_internal_rows" width="5%" valign="top"><br><b>:</td>
  <td colspan="3" class="contenttab_internal_rows">
    <!-- <textarea cols="56" rows="8"  id="noticeText" name="noticeText" ></textarea> -->
    <textarea id="elm12" name="elm12" class="tiny2" style="width:100%" ></textarea>
  </td>
</tr>
<?php  if($sessionHandler->getSessionVariable('SMS_ALERT_FOR_NOTICE_UPLOAD') == 1) {?>
<tr>
  <td width="30%" class="contenttab_internal_rows"><b>Send SMS</b> <input type="checkbox" id="editSmsStatus" name="editSmsStatus" onclick="enableEditTextBox();" ></td>
  <td width="5%"><b>:</td>
  <td colspan="65%" class="contenttab_internal_rows">
  <input type="text"  class="inputbox" name="editSms" id="editSms" style="width:480px" maxlength="255" disabled="disabled" onclick="clearEditTextBox();" maxlength="140"/>
  <span style='padding-left:20px;'>(Max.140 chars)<span>
</td>
</tr>
<?php }
 else{ ?>

<?php } ?>

<tr>
  <td class="contenttab_internal_rows"><strong>Attachment</strong></td>
  <td><b>:</td>
  <td class="contenttab_internal_rows">
    <nobr>
    <table border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td class="contenttab_internal_rows"  width="250px" nowrap><nobr> 
	       <input type="file" id="noticeAttachment" name="noticeAttachment" class="inputbox" tabindex="15">
		</td>
        	<td class="contenttab_internal_rows"  ><nobr> 
	      (Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)</nobr>
        </td>
	 
        <td class="contenttab_internal_rows">
          <nobr>
            <!-- <span id="editLogoPlace" class="cl" onClick="this.style.display='none';"> -->
            <span id="editLogoPlace" class="cl" style="display:none;">
             <input readonly type="hidden" id="downloadFileName" name="downloadFileName" class="inputbox">
              <label id="uploadIconLabel" style="padding-left:5px;"></label>
             <img src="<?php echo IMG_HTTP_PATH;?>/download.gif" class="imgLinkRemove1" alt="Download File" title="Download File" onClick="download1();" />

             <!--<img src="<?php //echo IMG_HTTP_PATH;?>/delete.gif" class="imgLinkRemove" onClick="deatach();" />-->

            </span>
          </nobr>
        </td>
	
	<td class="contenttab_internal_rows" style='padding-left:35px;'><nobr> 
  	        <strong>Allow User to Download</strong></nobr>
        </td>
        <td class="contenttab_internal_rows"><nobr> 
              <strong>:&nbsp;</strong></nobr>
        </td> 
        <td class="contenttab_internal_rows"><nobr>
            <input name="downloadOption" id="downloadOption1" value="1"  type="radio" checked="checked">Yes&nbsp;
            <nobr>
        </td>
        <td class="contenttab_internal_rows"><nobr>                   
            <input name="downloadOption" id="downloadOption2" value="2"  type="radio">No
           <nobr>
        </td>
      </tr>
    </table>
    </nobr>
  </td>
</tr>
<tr>
      <td  class="contenttab_internal_rows"><nobr><b>Notice by Department<?php echo REQUIRED_FIELD?></b></nobr></th>
      <td class="contenttab_internal_rows" nowrap><b>:&nbsp;</b></td>
      <td class="contenttab_internal_rows" nowrap colspan="10">
        <table border="0" cellpadding="0" cellspacing="0"> 
          <tr>
            <td class="contenttab_internal_rows" nowrap>
                <select name="departmentId" id="departmentId" size="1" class="selectfield" style="width:150px" >
		<option value="">SELECT</option>
		 <?php
		          require_once(BL_PATH.'/HtmlFunctions.inc.php');
		          echo HtmlFunctions::getInstance()->getDepartmentData();
		      ?>
		</select>
            </td>
            <td class="contenttab_internal_rows" style="padding-left:20px" nowrap><strong>Visible</strong></td>
            <td class="contenttab_internal_rows" nowrap></td>
            <td class="contenttab_internal_rows" nowrap><b>:&nbsp;</b></td>
            <td class="contenttab_internal_rows" nowrap>&nbsp;From&nbsp;</td>
            <td class="contenttab_internal_rows" nowrap>
                     <?php
		       require_once(BL_PATH.'/HtmlFunctions.inc.php');
		       echo HtmlFunctions::getInstance()->datePicker('visibleFromDate1',date('Y')."-".date('m')."-".date('d'));
		     ?>    
            </td>
            <td class="contenttab_internal_rows" nowrap>&nbsp;To&nbsp;</td>
            <td class="contenttab_internal_rows" nowrap>
                    <?php
		     require_once(BL_PATH.'/HtmlFunctions.inc.php');
		     echo HtmlFunctions::getInstance()->datePicker('visibleToDate1',date('Y')."-".date('m')."-".date('d'));
		    ?>    
            </td>
            <td class="contenttab_internal_rows" style="padding-left:20px" nowrap><strong>Visible Mode</strong></td>
            <td class="contenttab_internal_rows" nowrap><b>:&nbsp;</b></td>
            <td class="contenttab_internal_rows" nowrap>
                <select name="visibleMode" id="visibleMode" size="1" class="selectfield" style="width:100px" >
                    <option value="1">New</option>
                    <option value="2">Important</option>
                    <option value="3">Urgent</option>
                </select>
            </td>
          </tr>
        </table>
        </nobr>
      </td>    
  </tr>
 <tr><td height="5px"></td></tr>
 <tr>
  <td  class="contenttab_internal_rows" align="left" valign="top" >
      <table border="0" width="100%" align="left" cellspacing="0px" cellpadding="0px">
        <tr>
           <td  class="contenttab_internal_rows" align="left" valign="top">
             <b>Notice Visible To</b>
           </td>
        </tr>
        <tr> 
           <td  class="contenttab_internal_rows" align="left" valign="top">    
<input name="noticePublishedTo" id="noticePublishedTo1" value="1" checked="checked" onclick="getNoticePublish(this.value,'E');" type="radio">Role&nbsp;
<input name="noticePublishedTo" id="noticePublishedTo2" value="2" onclick="getNoticePublish(this.value,'E');" type="radio">Institute
           </td>
        </tr>
      </table>        
  </td>
  <td  class="contenttab_internal_rows" align="left" valign="top"><b><nobr>:</nobr></b></td>
  <td  class="contenttab_internal_rows" colspan="3" align="left" rowspan="1" valign="top">
       <table border="0" width="100%" align="left" class="contenttab_border" id="showRoleInstituteEdit" style="display:none">
         <tr>
            <td width="2%" class="contenttab_internal_rows" style="padding-left:5px" nowrap align="left"><b>Institute</b></td>  
            <td width="98%" class="contenttab_internal_rows"  style="padding-left:15px"  align="left" rowspan="2" valign="top">  
              
            </td>
         </tr>   
         <tr>
            <td class="contenttab_internal_rows" nowrap align="left" valign="top">
               <select size="6" multiple="multiple" class="selectfield" name="noticeInstituteId" id="noticeInstituteId" style="width:250px">
                 <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getInstituteData();
                  ?>
                </select>
               <div>
               Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("noticeInstituteId","All","editNotice");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("noticeInstituteId","None","editNotice");'>None</a></div>
            </td>
         </tr> 
      </table>   
      
      <table border="0" width="100%" align="left" class="contenttab_border" id="showRoleNoticeEdit">
        <tr>
            <td class="contenttab_internal_rows"  style="padding-left:5px" align="left"><b>Role</b></td>
            <td class="contenttab_internal_rows"  align="left" rowspan="2" valign="top">
                 <table border="0" width="100%" align="left" class="contenttab_border"> 
                    <tr> 
                       <td class="contenttab_internal_rows"  align="left"  valign="top"><b>University</b></td> 
                       <td class="contenttab_internal_rows" style="padding-left:8px" align="left" rowspan="6" valign="top">
                         <b>Class</b><br>
                         <select multiple="multiple" name="classId" id="classId" size='6' class='inputbox1' style='width:280px'>
			<?php
			   require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getAllClassDataNew($REQUEST_DATA['classId']==''? $eventRecordArray[0]['classId'] : $REQUEST_DATA['classId'] );
			?>
			</select>
			<div>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("classId","All","editNotice");'>All</a> /
               		<a class="allReportLink" href='javascript:makeSelection("classId","None","editNotice");'>None</a></div>
                       </td>
                    </tr>   
                    <tr>
                       <td class="contenttab_internal_rows" align="left" >
                          <select name="universityId" id="universityId" size="1" class="selectfield" style="width:120px" onChange="getAllClass('U','Edit');">
			    <option value="NULL">ALL</option>
			<?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->getUniversityAbbr($REQUEST_DATA['universityId']==''? $universityRecordArray[0]['universityId'] : $REQUEST_DATA['universityId'] );
			      ?>
			 </select>
                       </td>
                    </tr>
                    <tr>  
                       <td class="contenttab_internal_rows"  align="left"><b>Degree</b></td>
                    </tr>
                    <tr>   
                       <td class="contenttab_internal_rows" align="left">
                           <select name="degreeId" id="degreeId" size="1" class="selectfield" style="width:120px" onChange="getAllClass('D','Edit');getBranchData();">
				<option value="NULL">ALL</option>
				<?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getInstituteDegreeAbbr($REQUEST_DATA['degreeId']==''? $degreeRecordArray[0]['universityId'] : $REQUEST_DATA['degreeId'] );
				      ?>
			  </select>
                       </td>
                     </tr>
                     <tr>  
                       <td class="contenttab_internal_rows"  align="left"><nobr><strong>Branch</strong></nobr></td>
                     </tr>  
                     <tr>
                        <td class="contenttab_internal_rows" align="left">
                          <select name="branchId" id="branchId" size="1" class="selectfield" style="width:120px" onChange="getAllClass('B','Edit');">
			      <option value="NULL">ALL</option>
			  </select>
                       </td> 
                    </tr>
                 </table>
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows" align="left" valign="top" style="padding-left:5px">  
                <select class='inputbox1' size="6" multiple="multiple"  name="roleId" id="roleId" style="width:250px">
                 <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['roleId']==''? $eventRecordArray[0]['roleId'] : $REQUEST_DATA['roleId'] );
                  ?>
                </select>
               <div>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("roleId","All","editNotice");'>All</a> /
               <a class="allReportLink" href='javascript:makeSelection("roleId","None","editNotice");'>None</a></div>
          </td>
        </tr>
      </table>
 </td>
</tr> 
  <tr>
      <td width="30%" nowrap class="contenttab_internal_rows" valign="top">
	<span style="float:left;position:relative;top:8px"><b>Send SMS</b>
 	<input type="checkbox" id="smsStatus" disabled="disabled" onclick="enableEditTextBox();">
	</span>
     </td>
     <td width="5%" class="contenttab_internal_rows"  nowrap valign="top"><b>
	<span style="float:right;position:relative;top:8px">:
        </span>
     </td>
     <td colspan="65%" height="50px"  class="contenttab_internal_rows">
	<input type="text"  class="inputbox" name="smsText" disabled="disabled" id="smsText" style="width:480px" onclick="clearEditTextBox();" maxlength="140"/>
	<span style='padding-left:15px;'>(Max.140 chars)<span>
        <div style="margin-top:5px" class='redLink'>
        <table border='1' cellspacing='0' cellpadding='0' rules="all" style="border-collapse:collapse;" align="center" bgcolor="#FFFF99" width="100%">
	<tr>
	   <td valign='top' colspan='1'>
	      <strong>&nbsp;Note:&nbsp;</strong>
	      SMS functionality is currently disabled. To enable kindly check the Config Settings 
              (SMS Alerts tab)
           </td>
       </tr>
       </table>
       </div>
    </td>
  </tr>
  <tr>
      <td colspan="3" height="5px">
      </td>
  </tr>
  <tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/publish_notice.gif" onClick="return validateAddForm(this.form,'Edit');" />
        <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditNoticeDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
</table>
<iframe id="uploadTargetEdit" name="uploadTargetEdit" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
</div>
<?php floatingDiv_End(); ?>



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

 
<!-- Notice Detail Starts -->
<?php  
floatingDiv_Start('divNoticeMessage','Notice Detail','','',''); 
// floatingDiv_Start('divNoticeMessage','Notice Detail','','','','122'); 
?>  
<div style="overflow:auto; width:600px; vertical-align:top;">
   <table width="600" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Subject: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'><div id="viewNoticeSubject"></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Department: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="viewNoticeDepartment" style="width:430px; height:20px"></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Date: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" height='20'>&nbsp;<B>From</B>: <span id="viewVisibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="viewVisibleToDate" style="height:20px"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Description: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="viewNoticeText" style="overflow:auto; width:530px; height:200px" ></div></td>
</tr> 
<tr>
<td height="5px"></td>
</tr>
</table>
</div>
<?php floatingDiv_End(); ?>
<!-- Notice Detail End -->
 
 
 
 
<!--Divs for multiple selected dds-->
<!--
<div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d11"></div>
<div style="display:none;position:fixed;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d22" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
       <tr>
          <td id="d33" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
          <td width="5%">
          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('roleId1','d11','containerDiv1','d33',true,true);" />
          </td>
        </tr>
     </table>
 </div>

<div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d111"></div>
<div style="display:none;position:fixed;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d222" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
       <tr>
          <td id="d333" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
          <td width="5%">
          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('roleId2','d111','containerDiv2','d333',true,true);" />
          </td>
        </tr>
     </table>
 </div>
 -->

