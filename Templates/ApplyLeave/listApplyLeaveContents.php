<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Apply Leave
// Author :Dipanjan Bhattacharjee 
// Created on : (12.1.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
    global $sessionHandler;
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    $leaveTypeString=HtmlFunctions::getInstance()->getLeaveTypeAdvData(' AND isActive=1');
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS'); 
    if(trim($leaveAuthorizersId)=='') {
      $leaveAuthorizersId=1;  
    }
    
    $commentHeading = "Reason";
    if($leaveAuthorizersId==2) {
       $commentHeading = "Reason(1st Appr.)";  
    }
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if ($roleId == 1) {
?>
    <input type="hidden" readonly="readonly" id="hiddenLeaveAuthorizersId" name="hiddenLeaveAuthorizersId" value="<?php echo $leaveAuthorizersId; ?>" >

   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
         <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");?>
            <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddApplyLeave',355,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
							<tr>
							<td align="right" colspan="2">
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
	}
	else {
?>
    <input type="hidden" readonly="readonly" id="hiddenLeaveAuthorizersId" name="hiddenLeaveAuthorizersId" value="<?php echo $leaveAuthorizersId; ?>" >
  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
	<tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddApplyLeave',355,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
 
      </td>
    </tr>

    
    
    </table>
    </td>
    </tr>
    </table>    

<?php
	}
?>

<!--Start Add Div-->
<?php floatingDiv_Start('AddApplyLeave','Apply for Leave'); ?>
<form name="addApplyLeave1" id="addApplyLeave1"  method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
 <input type="hidden" name="employeeId" id="employeeId" value="" />
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="padding" colspan="4" >&nbsp;
      <input type="text" name="employeeCode" id="employeeCode" class="inputbox" style="width:260px;" onblur="getEmployeeCode(this.value);" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="padding" colspan="4">&nbsp;
      <div id="employeeName" class="contenttab_internal_rows" style="display:inline" />
     </td>
   </tr>
   <tr>
     <td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <!--<td class="padding" width="1%">:</td>
     <td width="24%" class="padding" colspan="4">&nbsp;
      <select name="leaveType" id="leaveType" class="selectfield" style="width:260px;" onchange="getLeaveRecord('Add');">
        <option value="">SELECT</option>
      </select>
     </td>-->
	<td class="padding" width="1%">:</td>
	<td width="24%" class="padding" colspan="4">&nbsp;
      <select name="leaveType" id="leaveType" class="inputbox" style="width:260px;">
        <option value="">SELECT</option>
           <?php
            echo $leaveTypeString;
           ?>
      </select>
     </td>
   </tr>
<tr>    
        <td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Format</strong></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="79%" class="contenttab_internal_rows">&nbsp;
          <input onclick="getLeaveStatus(this.value,'A'); " type="radio" name="leaveFormat" value="1" maxlength="20" checked="checked"/>Full Day
          <input onclick="getLeaveStatus(this.value,'A'); " type="radio" value="2" name="leaveFormat" >Half Day</td>
    </tr>
   <tr style='display:none' id='leaveRecordAdd'>
     <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Records</strong></nobr></td>
     <td class="padding" width="1%">:</td>
     <td width="24%" class="contenttab_internal_rows" colspan="4">
        &nbsp;<div id="emplLeaveRecordAddDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave From</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan='4'>&nbsp;
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">  
        <tr>
           <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->datePicker('fromDate1',date('Y-m-d')); 
            ?>
           </td>
             <td id='hiddenLeaveFormatA1' width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave To</b></nobr></td>
             <td id='hiddenLeaveFormatA2' class="padding" width="1%">:</td>
             <td id='hiddenLeaveFormatA3' class="contenttab_internal_rows" >&nbsp;
              <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->datePicker('toDate1',date('Y-m-d')); 
              ?>
             </td>
         </tr>
       </table></nobr>
    </td>         
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Substitute Employee Code</b>
     <br>
       <span style="padding-left:2px;font-family:Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">
            * Use comma seperator( , )<br>&nbsp;&nbsp;&nbsp;&nbsp;for multiple employees<br>&nbsp;* Unique employee code shown<br>&nbsp;&nbsp;&nbsp;&nbsp;for multiple employees
       </span>
     </nobr>
     </td>
      <td class="padding" width="1%" valign="top">:</td>
     <td class="padding" colspan="4">
          <textarea name="substituteEmployee" id="substituteEmployee" cols="30" rows="2" maxlength="100" onkeyup="return ismaxlength(this);"></textarea>
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Reason<?php echo REQUIRED_FIELD ?></b><br>
        <span style="padding-left:2px;font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
          * Maximum characters:1000
        </span>
     </nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="padding" colspan="4">
      <textarea name="leaveReason" id="leaveReason" cols="30" rows="2" maxlength="2000" onkeyup="return ismaxlength(this);"></textarea>
     </td>
   </tr>
   
    <tr>
     <td width="5%" valign="top"  class="contenttab_internal_rows"><nobr>&nbsp;<b>Application Date</b></nobr></td>
     <td class="padding" valign="top" width="1%">:</td>
     <td class="padding" valign="top" colspan="4">
      <?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->datePicker('applyDate1',date('Y-m-d')); 
      ?>
     </td>
   </tr> 
<tr>
  <td class="contenttab_internal_rows" valign="top" >
    <nobr>&nbsp;<strong>Leave Document</strong><br>
    <span style="padding-left:2px;font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
       * Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB
    </span>
    </nobr>    
  </td>
  <td class="padding" valign="top" width="1%">:</td>   
  <td class="padding" valign="top"  nowrap>
    <nobr>
    <input type="file" id="documentAttachment" name="documentAttachment" class="inputbox" tabindex="15" >
	<span style='padding-left:65px;'></span>
  </td>
</tr>
 <tr><td height="10px" colspan="6"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="6">
       <iframe id="uploadTargetAdd" name="uploadTargetAdd" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddApplyLeave');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
 </tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditApplyLeave','Edit Applied Leave '); ?>
<form name="editApplyLeave1" id="editApplyLeave1"  method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
<input type="hidden" name="mappingId" id="mappingId" value="" />  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
     <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
     <td class="padding" colspan="4" >&nbsp;
      <input type="text" name="employeeCode" id="employeeCode" class="inputbox" style="width:260px;" disabled="disabled" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
     <td class="padding" colspan="4">&nbsp;
      <div id="employeeName2" class="contenttab_internal_rows" style="display:inline" />
     </td>
   </tr>
   <tr>
     <td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
     <td width="24%" class="padding" colspan="4">&nbsp;
      <select name="leaveType" id="leaveType" class="selectfield" style="width:260px;" onchange="getLeaveRecord('Edit');" >
        <option value="">SELECT</option>
      </select>
     </td>
   </tr>
   <tr>    
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Leave Format</b></nobr></td>
        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
        <td width="79%" class="contenttab_internal_rows">&nbsp;
          <input onclick="getLeaveStatus(this.value,'E');" type="radio" name="leaveFormat" value="1" maxlength="20" checked="checked"/>Full Day
          <input onclick="getLeaveStatus(this.value,'E');" type="radio" value="2" name="leaveFormat" >Half Day</td>
         </td>
   </tr>
<tr><td height="5px"></td></tr>
	    
   <!--<tr style='display:none' id='leaveRecordEdit'>-->
     <tr id='leaveRecordEdit'>
     <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Leave Records</strong></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b></td>&nbsp;
     <td width="24%" class="contenttab_internal_rows" colspan="4">&nbsp;
       &nbsp;&nbsp;&nbsp;<div id="emplLeaveRecordDiv" style="display:inline;" />
	
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave From</b></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
     <td class="contenttab_internal_rows" colspan='4'>&nbsp;
       <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">  
        <tr>
           <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->datePicker('fromDate2',date('Y-m-d')); 
            ?>
           </td>
             <td id='hiddenLeaveFormatE1' width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave To</b></nobr></td>
             <td id='hiddenLeaveFormatE2' class="contenttab_internal_rows" width="1%"><b>:</b></td>
             <td id='hiddenLeaveFormatE3' class="contenttab_internal_rows" >&nbsp;
              <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->datePicker('toDate2',date('Y-m-d')); 
              ?>
             </td>
         </tr>
       </table></nobr>
    </td>         
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Substitute Employee Code</b><br>
        <span style="padding-left:2px;font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
          * Use comma seperator( , )<br>&nbsp;&nbsp;&nbsp;for multiple employees<br>
          * Unique employee code shown<br>&nbsp;&nbsp;&nbsp;for multiple employees
        </span>
      </nobr>
     </td>
     <td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
       <textarea name="substituteEmployee" id="substituteEmployee" cols="30" rows="2" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea> 
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Reason<?php echo REQUIRED_FIELD ?></b><br>
        <span style="padding-left:2px;font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
          * Maximum characters:1000
        </span>
     </nobr></td>
     <td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <textarea name="leaveReason" id="leaveReason" cols="30" rows="2" maxlength="2000" onkeyup="return ismaxlength(this);"></textarea>
     </td>
   </tr>
    <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Application Date</b></nobr></td>
     <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->datePicker('applyDate2',date('Y-m-d')); 
      ?>
     </td>
   </tr>
<tr>
  <td valign="top" class="contenttab_internal_rows"><nobr>
    <strong>&nbsp;Attachment</strong><br>
    <span style="padding-left:2px;font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
     * Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB</nobr></span>  
    </nobr>
  </td>
  <td valign="top" class="contenttab_internal_rows" width="1%"><b>:</b></td>
  <td valign="top"  class="contenttab_internal_rows" nowrap>
    <nobr>
     <table border="0" cellspacing="0" cellpadding="0" width="100%" >
      <tr>
        <td class="contenttab_internal_rows">&nbsp;
        <nobr>
            <input type="file" id="documentAttachment" name="documentAttachment" class="inputbox" tabindex="15">
	</nobr>
        </td>
        <td valign="top" width="100%" class="padding" align="right">
          <nobr>
            <!-- <span id="editLogoPlace" class="cl" onClick="this.style.display='none';"> -->
            <span id="editLogoPlace" class="cl" style="display:none;" >
             <input readonly type="hidden" id="downloadFileName" name="downloadFileName" class="inputbox">
              <label id="uploadIconLabel" style="padding-left:3px;"></label>
             <img src="<?php echo IMG_HTTP_PATH;?>/download.gif" class="imgLinkRemove1" alt="Download File" title="Download File" onClick="download1();" />

             <!--<img src="<?php //echo IMG_HTTP_PATH;?>/delete.gif" class="imgLinkRemove" onClick="deatach();" />-->

            </span>
          </nobr>
        </td>
      </tr>
    </table>
    
	<span style='padding-left:65px;'></span>
  </td>
</tr>
 <tr><td height="5px" colspan="6"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditApplyLeave');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
<iframe id="uploadTargetEdit" name="uploadTargetEdit" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>   
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->


<!--Start View Div-->
<?php floatingDiv_Start('ViewApplyLeave','View Applied Leave '); ?>
<form name="ViewApplyLeave" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
     <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="1" >
      <div id="emplCodeDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="1">
        <div id="emplNameDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type</strong></nobr></td>
     <td class="padding" width="1%">:</td>
     <td width="24%" class="contenttab_internal_rows" colspan="1">
        <div id="emplLeaveTypeDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>

  <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Leave Records</strong></nobr></td>
     <td class="padding" width="1%"><b>:</b></td>
     <td width="24%" class="contenttab_internal_rows" colspan="1">
       <div id="emplLeaveRecordDiv1" style="display:inline;" />
	
     </td>
     <!--<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Records</strong></nobr></td>
     <td class="padding" width="1%">:</td>
     <td width="24%" class="contenttab_internal_rows" colspan="4">&nbsp;
        <div id="emplLeaveRecordDiv" style="display:inline;" />
     </td>-->
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave From</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" >
        <div id="emplLeaveFromDiv" style="display:inline;" />
     </td>
	
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave To</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" >&nbsp;
        <div id="emplLeaveToDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Attachment</b></nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="contenttab_internal_rows" valign="top" colspan="1">
       <div id="emplLeaveAttachmentDiv" style="display:inline;" />
   </td>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Reason</b></nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="contenttab_internal_rows" valign="top" colspan="7" width="100%">
	    <div style="overflow:auto; width:98%; height:35px; vertical-align:top;">
           <div id="emplLeaveReasonDiv" style="width:98%; vertical-align:top;"></div>
        </div>
   </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Application Date</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows">
        <div id="emplLeaveApplicationDateDiv" style="display:inline;" />
     </td>      
     
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave Status</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows">
      <div id="emplLeaveStatusDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b><?php echo $commentHeading; ?> </b></nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="contenttab_internal_rows" colspan="8"  valign="top">
	    <div style="overflow:auto; width:98%; height:35px; vertical-align:top;">
          <div id="firstCommentsDiv" style="width:98%; vertical-align:top;"></div>
        </div>
     </td>
   </tr>
   <?php if($leaveAuthorizersId==2) { ?>
       <tr>
         <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Reason(2nd Appr.)</b></nobr></td>
         <td class="padding" width="1%" valign="top">:</td>
         <td class="contenttab_internal_rows" colspan="8" valign="top">
 	         <div style="overflow:auto; width:98%; height:35px; vertical-align:top;">
               <div id="secondCommentsDiv" style="width:98%; vertical-align:top;"></div>
             </div>
         </td>
       </tr>
   <?php } ?>    
 <tr><td height="5px" colspan="6"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ViewApplyLeave');return false;" />
     </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>   
<?php floatingDiv_End(); ?>
<!--End: Div To View The Table-->


<?php
// $History: listFeedbackAdvOptionsContents.php $
?>
