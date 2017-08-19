<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 require_once(BL_PATH . "/helpMessage.inc.php");
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;" title = "Add Item Category"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('ItemCategoryActionDiv',315,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" >
								<div id="results"></div></td>
							</tr>
							<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printItemCategoryReport();" title = "Print">&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" title = "Export To Excel">
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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('ItemCategoryActionDiv',''); ?>
<form name="ItemCategoryDetail" action="" method="post">  
<input type="hidden" name="itemCategoryId" id="itemCategoryId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
	<td height="5px"></td></tr>
<tr>
<tr> 
	<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Category Name</strong><?php echo REQUIRED_FIELD; ?></nobr>
	
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Item Code',HELP_CAT_NAME);
    	?>
	
	</td>
	<td width="65%" class="padding">:</td>
	<td><input type="text" id="categoryName" name="categoryName"  style="width:180px" class="inputbox" maxlength="100"/>
	</td>
</tr>
<tr> 
	<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Category Code</strong><?php echo REQUIRED_FIELD; ?></nobr>
	
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Item Code',HELP_CAT_CODE);
    	?>
	

	</td>
	<td width="65%" class="padding">:</td>
	<td><input type="text" id="categoryCode" name="categoryCode"  style="width:180px" class="inputbox" maxlength="50"/>
	</td>
</tr>
<?php //Category type: 1- Consumable 2- Non-Consumable ?>
<tr> 
	<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Category Type</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
	<td width="65%" class="padding">:</td>
	<td><input class="contenttab_internal_rows" type="radio"  value='1' name="categoryType" id="cat1"/>Consumable&nbsp;&nbsp
	<input class="contenttab_internal_rows" type="radio" value='2' name="categoryType" id="cat2" checked/>Non-Consumable</td>
</tr>
<tr>
	<td height="5px"></td>
</tr>
<tr>
	<td align="center" style="padding-right:10px" colspan="4">
	<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
	<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ItemCategoryActionDiv');if(flag==true){getItemCategoryData();flag=false;}return false;" />
</td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<!--Help  Details  Div-->
<?php floatingDiv_End(); ?>
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
	   <tr>
	     <td height="5px"></td></tr>
	   <tr>
		<td width="89%">
		<div id="helpInfo" style="vertical-align:top;" ></div>
		</td>
		</tr>
	</table>
</div>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



