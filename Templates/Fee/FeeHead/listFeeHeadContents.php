<?php 
//This file creates Html Form output in Fee Head Module 
//
// Author :Nishu Bindal
// Created on : 2-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeeHead',350,200);blankValues();return false;" />&nbsp;</td></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <tr>
                                <td align="right" colspan="2">
                   <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                            </td>  
                        </tr>
                    </table>-->
                </td>
             </tr>
          </table>
        </td>
    </tr>
  </table>
        </td>
    </tr>
</table>
    
<?php floatingDiv_Start('AddFeeHead','Add Fee Head'); ?>
<form name="addFeeHead" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headName" id="headName" class="inputbox" maxlength="100"  style="width:250px"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headAbbr" id="headAbbr" class="inputbox" maxlength="10"  style="width:250px" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Refundable Security </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isRefundable" name="isRefundable" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isRefundable" name="isRefundable" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Concessionable </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isConsessionable" name="isConsessionable" value="1"  />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isConsessionable" name="isConsessionable" checked="checked" value="0" />No
        </td>
    </tr>  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Miscellaneous </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isSpecial" name="isSpecial" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isSpecial" name="isSpecial" value="0" checked="checked" />No
        </td>
    </tr>
	<tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Display Order<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="sortOrder" id="sortOrder" class="inputbox" maxlength="2"  style="width:30px" /></td>
    </tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="2"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" /><input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeeHead');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" /></td>
	</tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
	</table>
    </form>
<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditFeeHead','Edit Fee Head'); ?>
<form name="editFeeHead" id="editFeeHead" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="feeHeadId" id="feeHeadId" value="" />
     <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headName" id="headName" class="inputbox" maxlength="100"  style="width:250px"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="headAbbr" id="headAbbr" class="inputbox" maxlength="10"  style="width:250px" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Refundable Security </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isRefundable" name="isRefundable" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isRefundable" name="isRefundable" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Concessionable </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isConsessionable" name="isConsessionable" value="1"  />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isConsessionable" name="isConsessionable" checked="checked" value="0" />No
        </td>
    </tr>  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Miscellaneous </b></nobr></td>
        <td width="79%" class="padding">
         :&nbsp;<input type="radio" id="isSpecial" name="isSpecial" value="1" />Yes &nbsp;&nbsp;&nbsp;
         <input type="radio" id="isSpecial" name="isSpecial" value="0" checked="checked" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Display Order<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="79%" class="padding">:&nbsp;<input type="text" name="sortOrder" id="sortOrder" class="inputbox" maxlength="2"  style="width:30px" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
                    onclick="javascript:hiddenFloatingDiv('EditFeeHead');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table>
</form>
    <?php floatingDiv_End(); ?>

