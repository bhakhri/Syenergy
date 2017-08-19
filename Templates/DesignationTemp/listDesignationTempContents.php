<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TEMPORARY DESIGNATION LISTING 
//
//
// Author :Gurkeerat Sidhu
// Created on : (29.04.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddDesignation',330,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
							<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
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

<?php floatingDiv_Start('AddDesignation','Add Designation'); ?>
<form name="addDesignation" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="32%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Designation Name<?php echo REQUIRED_FIELD ?> </b></nobr></td>
			<td width="68%" class="padding">:&nbsp;<input type="text" id="designationName" name="designationName" class="inputbox" maxlength="100" ></td>
		</tr>
		<tr>    
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Designation Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="designationCode" name="designationCode" class="inputbox" maxlength="10"></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDesignation');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditDesignation','Edit Designation'); ?>
<form name="editDesignation" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="tempDesignationId" id="tempDesignationId" value="" />
			<tr>
				<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Designation Name<?php echo REQUIRED_FIELD ?> </b></nobr></td>
				<td width="70%" class="padding">:&nbsp;<input type="text" id="designationName" name="designationName" class="inputbox" value="" maxlength="100"></td>
			</tr>
			<tr>    
				<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Designation Code<?php echo REQUIRED_FIELD ?> </b></nobr></td>
				<td class="padding">:&nbsp;<input type="text" id="designationCode" name="designationCode" class="inputbox" value="" maxlength="10" ></td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:18px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<img src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditDesignation');" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
    

