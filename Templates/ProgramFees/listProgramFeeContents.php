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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddProgramFee',315,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<!--Start Add Div-->
<?php floatingDiv_Start('AddProgramFee','Add Program Fee'); ?>
<form name="addProgramFee" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Program Fee<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="programFeeName" name="programFeeName" class="inputbox" maxlength="100" style="width:220px;" /></td>
</tr>
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddProgramFee');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr><td height="5px"></td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditProgramFee','Edit Program Fee '); ?>
<form name="editProgramFee" id="editProgramFee" action="" method="post">
<input type="hidden" name="programFeeId" id="programFeeId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  <tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Program Fee<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
    <td width="79%" class="padding">:&nbsp;<input type="text" id="programFeeName" name="programFeeName" class="inputbox" maxlength="100" style="width:220px;" /></td>
</tr>
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditProgramFee');return false;" />
    </td>
</tr>
<tr><td height="5px"></td>
</tr>
</table>
</form>
<?php
floatingDiv_End();
?>
<!--End: Div To Edit The Table-->