<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR feed back grades 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.1.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
///--------------------------------------------------------
?>
<?php
    global $sessionHandler;
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS'); 

    $titleApproval="Approval";
    if(trim($leaveAuthorizersId)=='') {
       $leaveAuthorizersId=1;  
    }
    $tableWidth='';
    if($leaveAuthorizersId==2) {  
      $titleApproval="First Approval";
      $tableWidth=" width='100%' ";
  
	}
	$roleId = $sessionHandler->getSessionVariable('RoleId');
 if($roleId==1) {
?>
    <input type="hidden" readonly="readonly" id="hiddenLeaveAuthorizersId" name="hiddenLeaveAuthorizersId" value="<?php echo $leaveAuthorizersId; ?>" >
<?php
	    require_once(TEMPLATES_PATH . "/breadCrumb.php");
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
	<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('EditLeaveAuthorization',355,250);blankValues();return false;" />&nbsp;
				</td>
			</tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <tr>
                                <td align="right" colspan="2">
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




    

<?php
 }
else {
?>
    <input type="hidden" readonly="readonly" id="hiddenLeaveAuthorizersId" name="hiddenLeaveAuthorizersId" value="<?php echo $leaveAuthorizersId; ?>" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php   require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
	<tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							 <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <!-- <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0"> -->
            <tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td></tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">
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
}
?>

<!--Start View Div-->
<?php floatingDiv_Start('EditLeaveAuthorization','Authorize Applied Leave '); ?>
<form name="EditLeaveAuthorization" action="" method="post">
<input type="hidden" name="authorizer" id="authorizer" value="" />
<input type="hidden" name="mappingId" id="mappingId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
     <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Code</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4" >&nbsp;
      <div id="empCodeDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Employee Name</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <div id="empNameDiv" style="display:inline;" />
     </td>
   </tr>
<tr>
     <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type</strong></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
        <div id="empLeaveTypeDiv" style="display:inline;" />
     </td>
 <tr>
     <td class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Records</strong></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" colspan="4">&nbsp;
      <div id="empLeaveRecordsDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave From</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" width="20%" >&nbsp;
      <div id="empLeaveFromDiv" style="display:inline;" />
     </td>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave To</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" >&nbsp;
      <div id="empLeaveToDiv" style="display:inline;" />
       </td>
	<td width="5%" class="contenttab_internal_rows" style="display:none;"><nobr>&nbsp;<b>Leave To</b></nobr></td>
       </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave Applied For</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" width="20%" >&nbsp;
      <div id="empLeaveAppliedDiv" style="display:inline;" />
     </td>
   </tr>  
	 <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Substitute Employee Code</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" width="20%" >&nbsp;
      <div id="empsubsDiv" style="display:inline;" />
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Leave Status</b></nobr></td>
     <td class="padding">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" width="20%" >&nbsp;
      <div id="empLeaveStatusDiv" style="display:inline;" />  
     </td>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Attachment</b></nobr></td>
     <td class="padding" width="1%">:</td>
     <td class="contenttab_internal_rows" nowrap="nowrap" >&nbsp;
      <div id="empLeaveAttachmentDiv" style="display:inline;" /> 
     </td>
   </tr>
   <tr>
     <td width="5%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>Reason</b></nobr></td>
     <td class="padding" width="1%" valign="top">:</td>
     <td class="contenttab_internal_rows" colspan="4" valign="top" style="border:1px solid #c6c6c6">
       <div id="scroll2" style="overflow:auto; height:70px; vertical-align:top;">
         <div id="empLeaveReasonDiv" style="width:98%; vertical-align:top;"></div>
       </div>
     </td>
   </tr>
   
    </table>
      <table border="0" cellpadding="0" cellspacing="0" >
        <tr>
	
         <td valign="top" >
          <fieldset>
           <legend><?php echo $titleApproval; ?></legend>
           <table border="0" cellpadding="0" cellspacing="0" >
            <tr>
             <td class="contenttab_internal_rows"><b>Status<?php echo REQUIRED_FIELD ?></b></td>
             <td class="padding">:</td>
             <td class="padding">
              <select name="firstAuthorizeStatus" id="firstAuthorizeStatus" class="inputbox">
               <option value="">Select</option>
               <?php
                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 echo HtmlFunctions::getInstance()->getFirstAuthorizationLeaveStatusData();
                ?>
              </select>
             </td>
            </tr>
            <tr>
             <td class="contenttab_internal_rows"><b>Comments<?php echo REQUIRED_FIELD ?></b></td>
             <td class="padding">:</td>
             <td class="padding">
              <textarea cols="20" rows="3" name="firstAuthorizeReason" id="firstAuthorizeReason" maxlength="2000" onkeyup="return ismaxlength(this)"></textarea>
             </td>
            </tr>
           </table>
          </fieldset>
		
         </td>
         <?php if($leaveAuthorizersId==2) { ?>
                 <td valign="top">
                 <fieldset>
                   <legend>Second Approval</legend>
                   <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                     <td class="contenttab_internal_rows"><b>Status<?php echo REQUIRED_FIELD ?></b></td>
                     <td class="padding">:</td>
                     <td class="padding">
                      <select name="secondAuthorizeStatus" id="secondAuthorizeStatus" class="inputbox">
                       <option value="">Select</option>
                       <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->getSecondAuthorizationLeaveStatusData();
                        ?>
                      </select>
                     </td>
                    </tr>
                    <tr>
                     <td class="contenttab_internal_rows"><b>Comments<?php echo REQUIRED_FIELD ?></b></td>
                     <td class="padding">:</td>
                     <td class="padding">
                      <textarea cols="20" rows="3" name="secondAuthorizeReason" id="secondAuthorizeReason" maxlength="2000" onkeyup="return ismaxlength(this)"></textarea>
                     </td>
                    </tr>
                   </table>
                  </fieldset>
                 </td>
               <?php } ?>                     
        </tr>
      

  
 <tr><td height="5px" colspan="6"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditLeaveAuthorization');return false;" />
     </td>
</tr>
<tr><td height="5px" colspan="6"></td></tr>

</table>
</form>   
<?php floatingDiv_End(); ?>
<!--End: Div To View The Table-->


<?php
// $History: listFeedbackAdvOptionsContents.php $
?>
