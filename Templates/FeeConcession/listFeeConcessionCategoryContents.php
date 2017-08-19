<?php
//This file creates Html Form output in Country Module
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeeConcession',300,200);blankValues();return false;" />&nbsp;</td></tr>
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
	

<?php floatingDiv_Start('AddFeeConcession','Add Fee Concession'); ?>
<form name="addFeeConcessionForm" action="" method="post" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Category Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>
        <td width="79%" class="padding"><input type="text" id="categoryName" name="categoryName" class="inputbox"  maxlength="50"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Category Order<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>
        <td width="79%" class="padding"><input type="text" id="categoryOrder" name="categoryOrder" maxlength="3" class="inputbox" maxlength="5"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top">&nbsp;<nobr><b>Description</b></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>
        <td width="79%" class="padding" valign="top">
        <textarea class="inputbox" name="categoryDescription" id="categoryDescription" cols="23" rows="3" maxlength="1000" onkeyup="return ismaxlength(this)"></textarea>
        </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
        <td align="center" style="padding-right:10px" colspan="3">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
            <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddFeeConcession');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
    </tr>
    <tr>
        <td height="5px"></td>
    </tr>
</table>
</form>

<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditFeeConcession','Edit Fee Concession'); ?>
<form name="editFeeConcessionForm" action="" method="post" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">


    <input type="hidden" name="categoryId" id="categoryId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Category Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>
        <td width="79%" class="padding"><input type="text" id="categoryName" name="categoryName" class="inputbox"  maxlength="50"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Category Order<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>
        <td width="79%" class="padding"><input type="text" id="categoryOrder" name="categoryOrder" maxlength="3" class="inputbox" maxlength="5"/></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top">&nbsp;<nobr><b>Description</b></nobr></td>
        <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b>&nbsp;</nobr></td>
        <td width="79%" class="padding" valign="top">
        <textarea class="inputbox" name="categoryDescription" id="categoryDescription" cols="23" rows="3" maxlength="1000" onkeyup="return ismaxlength(this)"></textarea>
        </td>
    </tr>
    <tr>
    <td height="5px"></td></tr>
    <tr>
        <td align="center" style="padding-right:10px" colspan="3">
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
          <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFeeConcession');return false;" />
        </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
</table>
</form>

    <?php floatingDiv_End(); ?>

<?php
//$History: listCountryContents.php $
//
?>