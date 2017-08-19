<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR UNIVERSITY LISTING
// Author :Dipanjan Bhattacharjee
// Created on : (14.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------




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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddFollowUpDiv','',650,250,200,100);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
    <!--Start Add Div-->

<?php floatingDiv_Start('AddFollowUpDiv','Add Follow Ups'); ?>
<form name="AddFollowUp" id="AddFollowUp" action="" method="post" style="display:inline" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Company<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding" colspan="4">
        <select name="companyId" id="companyId" class="inputbox" style="width:100%">
         <option value="">Select</option>
         <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->getPlacementCompanies();
         ?>
        </select>
        </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows"><nobr><b>Contacted On</b></nobr></td>
       <td class="padding" >:</td>
       <td class="padding" >
       <?php
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->datePicker('contactedOn1',date('Y-m-d'));
       ?>
      </td>
      <td class="contenttab_internal_rows"><nobr><b>Type of call</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
        <input type="radio" name="newCall" id="newCall1" value="1" checked="checked" />New Call&nbsp;
        <input type="radio" name="newCall" id="newCall2" value="0"  />Follow Up
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Contacted Via</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding" colspan="4">
         <input type="radio" name="contactedVia" id="contactedVia1" value="1" checked="checked" />Email&nbsp;
         <input type="radio" name="contactedVia" id="contactedVi2" value="2"  />Landline&nbsp;
         <input type="radio" name="contactedVia" id="contactedVia3" value="3" />Mobile&nbsp;
         <input type="radio" name="contactedVia" id="contactedVi4" value="4"  />SMS
        </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" valign="top"><nobr><b>Contacted Person<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding">
        <input type="text" name="contactedPerson" id="contactedPerson" class="inputbox" maxlength="50" />
      </td>
      <td class="contenttab_internal_rows" valign="top"><nobr><b>Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding">
        <input type="text" name="designation" id="designation" class="inputbox" maxlength="50" />
      </td>
    </tr>

    <tr>
     <td class="contenttab_internal_rows" valign="top"><nobr><b>Comments</b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" colspan="4">
        <textarea id="comments" name="comments" class="inputbox" style="width:99%" cols="16" rows="5"  maxlength="2500" onkeyup="return ismaxlength(this)" /></textarea>
      </td>
    </tr>

    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Follow Up</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="followUp" id="followUp1" value="1" checked="checked" onclick="toggleFollowUp(1,1);" />Yes &nbsp;
         <input type="radio" name="followUp" id="followUp2" value="0" onclick="toggleFollowUp(1,0);" />No
        </td>
     <td class="contenttab_internal_rows"><nobr><b><div id="f1">Follow Up date</b></div></nobr></td>
      <td class="padding"><div id="f2">:</div></td>
     <td class="padding"><div id='f3'>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('followUpDate1',date('Y-m-d'));
          ?></div>
        </td>



    </tr>

    <tr id="fTRId1">

        <td class="contenttab_internal_rows"><nobr><b>Follow Up By</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="followUpBy" id="followUpBy1" value="1" checked="checked" onclick="toggleEmailSms(1,1);" />Email &nbsp;
         <input type="radio" name="followUpBy" id="followUpBy2" value="0" onclick="toggleEmailSms(1,0);" />SMS
        </td>
        <td class="contenttab_internal_rows"><nobr><b>
          <div id="fupD1" style="display:inline">Email Id</div><?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
          <input type="text" name="followUpMethod" id="followUpMethod" class="inputbox" maxlength="250" />
        </td>
    </tr>

    <tr>
     <td align="center" style="padding-right:10px" colspan="6">
      <input type="image" name="imageAdd" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');" tabindex="16"/>
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFollowUpDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="17" />
    </td>
   </tr>
   <tr><td colspan="6" height="5px"></td></tr>
</table>
</form>
 <?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditFollowUpDiv','Edit Follow Ups'); ?>
<form name="EditFollowUp" id="EditFollowUp" action="" method="post" style="display:inline" onsubmit="return false;">
<input type="hidden" name="followUpId" id="followUpId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Company<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding" colspan="4">
        <select name="companyId" id="companyId" class="inputbox" style="width:100%">
         <option value="">Select</option>
         <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->getPlacementCompanies();
         ?>
        </select>
        </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows"><nobr><b>Contacted On</b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" >
       <?php
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->datePicker('contactedOn2','');
       ?>
      </td>
      <td class="contenttab_internal_rows"><nobr><b>Type of call</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
        <input type="radio" name="newCall" id="newCall3" value="1"  />New Call&nbsp;
        <input type="radio" name="newCall" id="newCall4" value="0"  />Follow Up
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Contacted Via</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding" colspan="4">
         <input type="radio" name="contactedVia" id="contactedVia5" value="1" />Email&nbsp;
         <input type="radio" name="contactedVia" id="contactedVi6" value="2"  />Landline&nbsp;
         <input type="radio" name="contactedVia" id="contactedVia7" value="3" />Mobile&nbsp;
         <input type="radio" name="contactedVia" id="contactedVi8" value="4"  />SMS
        </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" valign="top"><nobr><b>Contacted Person<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding">
        <input type="text" name="contactedPerson" id="contactedPerson" class="inputbox" maxlength="50" />
      </td>
      <td class="contenttab_internal_rows" valign="top"><nobr><b>Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding">
        <input type="text" name="designation" id="designation" class="inputbox" maxlength="50" />
      </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" valign="top"><nobr><b>Comments</b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" colspan="4">
        <textarea id="comments" name="comments" class="inputbox" style="width:99%" cols="16" rows="5"  maxlength="2500" onkeyup="return ismaxlength(this)" /></textarea>
      </td>
    </tr>

    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Follow Up</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="followUp" id="followUp3" value="1" checked="checked" onclick="toggleFollowUpEdit(1,1);"/>Yes &nbsp;
         <input type="radio" name="followUp" id="followUp4" value="0" onclick="toggleFollowUpEdit(1,0);"/>No
        </td>
        <td class="contenttab_internal_rows"><nobr><div id ="f11"><b>Follow Up date</b></div></nobr></td>
        <td class="padding"><div id="f22">:</div></td>
        <td class="padding"><div id ="f33">
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('followUpDate2','');
          ?>
        </td> </div>
    </tr>

    <tr id="fTRId2">

        <td class="contenttab_internal_rows"><nobr><b>Follow Up By</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="followUpBy" id="followUpBy1" value="1" checked="checked" onclick="toggleEmailSmsEdit(1,1);" />Email &nbsp;
         <input type="radio" name="followUpBy" id="followUpBy2" value="0" onclick="toggleEmailSmsEdit(1,0);" />SMS
        </td>
        <td class="contenttab_internal_rows"><nobr><b>
          <div id="fupD2" style="display:inline">Email Id</div>
         </b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
          <input type="text" name="followUpMethod" id="followUpMethod" class="inputbox" maxlength="100" />
        </td>
    </tr>

 <tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageEdit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');" tabindex="16" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="javascript:hiddenFloatingDiv('EditFollowUpDiv');return false;" tabindex="17" />
   </td>
</tr>
<tr>
   <td height="5px" colspan="6"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->


<?php
// $History: listFollowUpContents.php $
?>