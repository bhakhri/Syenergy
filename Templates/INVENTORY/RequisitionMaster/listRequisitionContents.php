<?php
//-------------------------------------------------------
// Purpose: to design the layout for Requisition Master.
//
// Author : Jaineesh
// Created on : (02 Aug 2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;" title = "Add Requisition"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddRequisition',360,250);blankValues();return false;" />&nbsp;</td></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title="print">&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" title="Export To Excel">
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
<?php floatingDiv_Start('AddRequisition','Add Requisition'); ?>
<form name="AddRequisition" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px" ></td></tr>
    <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Requisition No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="requisitionNo" id="requisitionNo" class="inputbox" value="" maxlength="30" onkeydown="return sendKeys(1,'requisitionNo',event);" readonly="readonly"/>
        </td>
	</tr>
	<tr>
        <!--<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Category<?php echo REQUIRED_FIELD ?></b></nobr></td>-->
        <td class="padding" align="left" ><select size="1" class="selectfield" name="itemCategory" id="itemCategory" style="display:none">
			<option value="">Select</option>
				  <?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getItemCategoryData();
				  ?>
			</select></td>
	</tr>
	<tr>
        <td class="padding" align="left"><select size="1" class="selectfield" name="itemCode" id="itemCode" style="display:none">
			<option value="">Select</option>
				  <?php
					  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  //echo HtmlFunctions::getInstance()->getItemData();
				  ?>
			</select> 
        </td>
	</tr>
	<tr>
		<td colspan="6">
		<div id="repairDetail" style="height:150px;overflow:auto;">
			<table class="padding" width="100%" border="0"  id="anyid1_add">
				<tbody id="anyidBody1_add">
				  <tr class="rowheading">
					<td class="searchhead_text" width="6%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
					<td class="searchhead_text" width="15%" align="left"><nobr><b>Item Category</b></nobr></td>
					<td class="searchhead_text" width="15%" align="left"><nobr><b>Item </b></nobr></td>
					<td class="searchhead_text" width="15%" align="left"><nobr><b>Quantity Required</b></nobr></td>
					<td class="searchhead_text" width="7%" align="center"><nobr><b>Action</b></nobr></td>
				  </tr>
				</tbody>
			 </table>               
			<div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
				<a href="javascript:addRequisitionOneRow(1,'add');" title="Add One Row"><b>+</b></a>
			</div>
		</div></td>
	</tr>
	<!--
	
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Code <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="itemCode" id="itemCode">
			<option value="">Select</option>
				  <?php
					  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  //echo HtmlFunctions::getInstance()->getItemData();
				  ?>
			</select> 
        </td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Quantity Required<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="quantityRequired" id="quantityRequired" class="inputbox" value="" maxlength="6" onkeydown="return sendKeys(1,'quantityRequired',event);"/></td>
	</tr>
	-->
   <tr>
		<td height="5px" colspan="3"></td>
   </tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="3">
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
		<input type="image" name="addCamcel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddRequisition');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
		</td>
	</tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditRequisition','Edit Requisition'); ?>
<form name="EditRequisition" action="" method="post">
	<input type="hidden" name="requisitionTransId" id="requisitionTransId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px" colspan="3"></td></tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Requisition No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="requisitionNo" id="requisitionNo" class="inputbox" value="" maxlength="30" onkeydown="return sendKeys(1,'requisitionNo',event);" readonly="readonly"/>
        </td>
	</tr>
	<tr>
        <!--<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Category<?php echo REQUIRED_FIELD ?></b></nobr></td>-->
        <td class="padding"  align="left" ><select size="1" class="selectfield" name="itemCategory1" id="itemCategory1" style="display:none">
			<option value="">Select</option>
				  <?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getItemCategoryData();
				  ?>
			</select></td>
	</tr>
	<tr>
        <td class="padding"><select size="1" class="selectfield" name="itemCode1" id="itemCode1" style="display:none">
			<option value="">Select</option>
				  <?php
					  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  //echo HtmlFunctions::getInstance()->getItemData();
				  ?>
			</select> 
        </td>
	</tr>

	<tr>
		<td colspan="6">
		<div id="repairDetail" style="height:150px;overflow:auto;">
			<table class="padding" width="100%" border="0"  id="anyid1_edit">
				<tbody id="anyidBody1_edit">
				  <tr class="rowheading">
					<td class="searchhead_text" width="6%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
					<td class="searchhead_text" width="15%" align="left"><nobr><b>Item Category</b></nobr></td>
					<td class="searchhead_text" width="15%" align="left"><nobr><b>Item </b></nobr></td>
					<td class="searchhead_text" width="15%" align="left"><nobr><b>Quantity Required</b></nobr></td>
					<td class="searchhead_text" width="7%" align="center"><nobr><b>Action</b></nobr></td>
				  </tr>
				</tbody>
			 </table>               
			<div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
				<a href="javascript:addRequisitionOneRow(1,'edit');" title="Add One Row"><b>+</b></a>
			</div>
		</div></td>
	</tr>
	<!--
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Category<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="itemCategory" id="itemCategory" onChange="getEditItem('','');">
			<option value="">Select</option>
				  <?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getItemCategoryData();
				  ?>
			</select>
		</td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Code <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="itemCode" id="itemCode">
			<option value="">Select</option>
				  <?php
					  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  //echo HtmlFunctions::getInstance()->getItemData();
				  ?>
			</select>
        </td>
	</tr>
	
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Quantity Required<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="quantityRequired" id="quantityRequired" class="inputbox" value="" maxlength="6" onkeydown="return sendKeys(1,'quantityRequired',event);"/></td>
	</tr>-->
	<tr><td height="5px" colspan="3"></td></tr>
        <tr>
            <td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditRequisition');return false;"  />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php 
floatingDiv_End(); 
// $History: $
//
?>