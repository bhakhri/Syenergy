<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR UNIVERSITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (14.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddCompanyDiv','',650,250,200,100);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddCompanyDiv','Add Company'); ?>
<form name="AddCompany" id="AddCompany" action="" method="post" style="display:inline" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="companyName" name="companyName" class="inputbox" maxlength="250" /></td>
        
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="companyCode" name="companyCode" class="inputbox" maxlength="50" /></td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows" valign="top"><nobr><b>Address<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" colspan="4">
        <textarea id="contactAddress" name="contactAddress" class="inputbox" style="width:99%" cols="16" rows="5"  maxlength="250" onkeyup="return ismaxlength(this)" /></textarea>
      </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Contact Person<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="contactPerson" name="contactPerson" class="inputbox" maxlength="50" /></td>
        
        <td class="contenttab_internal_rows"><nobr><b>Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="designation" name="designation" class="inputbox" maxlength="50"/></td>
    </tr>
    
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Landline</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="landline" name="landline" class="inputbox" maxlength="50" ></td>
        
        <td class="contenttab_internal_rows"><nobr><b>Mobile No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="mobileNo" name="mobileNo" class="inputbox" maxlength="10" /></td>
    </tr>
    
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Email Id<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding" colspan="4">
         <input type="text" id="emailId" name="emailId" style="width:99%" class="inputbox" maxlength="50"  />
        </td>
    </tr>
    
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Industry Type</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="industryType" id="industryType1" value="1" checked="checked" />Established &nbsp;
         <input type="radio" name="industryType" id="industryType2" value="0" />Startup
        </td>
        
        <td class="contenttab_internal_rows"><nobr><b>Active</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="isActive" id="isActive1" value="1" checked="checked" />Yes &nbsp;
         <input type="radio" name="isActive" id="isActiv2" value="0" />No
        </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows" valign="top"><nobr><b>Remarks</b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" colspan="4">
        <textarea id="remarks" name="remarks" class="inputbox" style="width:99%" cols="16" rows="5"  maxlength="250" onkeyup="return ismaxlength(this)" /></textarea>
      </td>
    </tr>
    <tr>
     <td align="center" style="padding-right:10px" colspan="6">
      <input type="image" name="imageAdd" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');" tabindex="16"/>
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddCompanyDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="17" />
    </td>
   </tr>
   <tr><td colspan="6" height="5px"></td></tr>
</table>
</form>
 <?php floatingDiv_End(); ?>
<!--End Add Div-->
                                                           
<!--Start Edit Div-->
<?php floatingDiv_Start('EditCompanyDiv','Edit Company '); ?>
<form name="EditCompany" id="EditCompany" action="" method="post" style="display:inline" onsubmit="return false;">
<input type="hidden" name="companyId" id="companyId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="companyName" name="companyName" class="inputbox" maxlength="250" /></td>
        
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="20%" class="padding"><input type="text" id="companyCode" name="companyCode" class="inputbox" maxlength="50"  /></td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows" valign="top"><nobr><b>Address<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" colspan="4">
        <textarea id="contactAddress" name="contactAddress" class="inputbox" style="width:99%" cols="16" rows="5"  maxlength="250" onkeyup="return ismaxlength(this)"  /></textarea>
      </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Contact Person<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="contactPerson" name="contactPerson" class="inputbox" maxlength="50"  /></td>
        
        <td class="contenttab_internal_rows"><nobr><b>Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="designation" name="designation" class="inputbox" maxlength="50"  /></td>
    </tr>
    
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Landline</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="landline" name="landline" class="inputbox" maxlength="50" /></td>
        
        <td class="contenttab_internal_rows"><nobr><b>Mobile No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding"><input type="text" id="mobileNo" name="mobileNo" class="inputbox" maxlength="50" /></td>
    </tr>
    
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Email Id<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td class="padding" colspan="4">
         <input type="text" id="emailId" name="emailId" style="width:99%" class="inputbox" maxlength="50"  />
        </td>
    </tr>
    
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Industry Type</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="industryType" id="industryType3" value="1" checked="checked" />Established &nbsp;
         <input type="radio" name="industryType" id="industryType4" value="0" />Startup
        </td>
        
        <td class="contenttab_internal_rows"><nobr><b>Active</b></nobr></td>
        <td class="padding">:</td>
        <td class="padding">
         <input type="radio" name="isActive" id="isActive3" value="1" checked="checked" />Yes &nbsp;
         <input type="radio" name="isActive" id="isActiv4" value="0" />No
        </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows" valign="top"><nobr><b>Remarks<?php echo REQUIRED_FIELD; ?></b></nobr></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" colspan="4">
        <textarea id="remarks" name="remarks" class="inputbox" style="width:99%" cols="16" rows="5"  maxlength="250" onkeyup="return ismaxlength(this)" /></textarea>
      </td>
    </tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageEdit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');" tabindex="16" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="javascript:hiddenFloatingDiv('EditCompanyDiv');return false;" tabindex="17" />
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
// $History: listCompanyContents.php $
?>