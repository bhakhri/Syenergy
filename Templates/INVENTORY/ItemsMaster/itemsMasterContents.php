<?php
//-------------------------------------------------------
// Purpose: to design the layout for Hostel.
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;" title = "Add Item"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddDescription',360,250);cleanUpTable();return false;" />&nbsp;</td></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title="Print">&nbsp;
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
<?php floatingDiv_Start('AddItem','Add Item'); ?>
<form name="AddItem" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px" colspan="3"></td></tr>
    <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Cateogy Code <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="categoryCode" id="categoryCode" onChange="getCategory(this.value,'Add');">
			<option value="">Select</option>
				  <?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getItemCategoryData();
				  ?>
			</select>
        </td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Category Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="categoryName" id="categoryName" class="inputbox" value="" maxlength="100" onkeydown="return sendKeys(1,'itemPrefix',event);" readonly="readonly"/></td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Name <?php echo REQUIRED_FIELD ?></b>
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Item Name',HELP_ITEM_NAME);
    	?>		
	</nobr></td>
        <td class="padding">:&nbsp;<input name="itemName" id="itemName" class="inputbox" value="" maxlength="100" onkeydown="return sendKeys(1,'itemName',event);"/>  
        </td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Code<?php echo REQUIRED_FIELD ?></b>
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Item Code',HELP_ITEM_CODE);
    	?>
	</nobr></td>
        <td class="padding">:&nbsp;<input name="itemCode" id="itemCode" class="inputbox" value="" maxlength="20" onkeydown="return sendKeys(1,'itemCode',event);"/></td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Re-order Level<?php echo REQUIRED_FIELD ?></b>
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Reorder Manager',HELP_REORDER_MANAGER);
    	?>	
	</nobr></td>
        <td class="padding">:&nbsp;<input name="reorderLevel" id="reorderLevel" class="inputbox" value="" maxlength="3" onkeydown="return sendKeys(1,'reorderLevel',event);"/></td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Unit<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="unit" id="unit">
			<option value="">Select</option>
				  <?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getUnitOfMeasurementData();
				  ?>
			</select>
		</td>
   </tr>
   <tr>
		<td height="5px" colspan="3"></td>
   </tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="3">
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
		<input type="image" name="addCamcel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddItem');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditItem','Edit Item'); ?>
<form name="EditItem" action="" method="post">
	<input type="hidden" name="itemId" id="itemId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px" colspan="3"></td></tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Cateogy Code <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="categoryCode" id="categoryCode" onChange="getCategory(this.value,'Edit');">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getItemCategoryData();
              ?>
        </select>
        </td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Category Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="categoryName" id="categoryName" class="inputbox" value="" maxlength="100" onkeydown="return sendKeys(1,'itemPrefix',event);" readonly="readonly"/></td>
	</tr>
	<tr id="itemPrefixRow" style="display:none">
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Prefix<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<input name="itemPrefix" id="itemPrefix" class="inputbox" value="" maxlength="15" onkeydown="return sendKeys(1,'itemPrefix',event);"/>
        </td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Name <?php echo REQUIRED_FIELD ?></b>
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Item Name',HELP_ITEM_NAME);
    	?></nobr></td>
<td class="padding">:&nbsp;<input name="itemName" id="itemName" class="inputbox" value="" maxlength="100" onkeydown="return sendKeys(1,'itemName',event);"/>  
        </td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Code<?php echo REQUIRED_FIELD ?></b>
	
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Item Code',HELP_ITEM_CODE);
    	?>

	</nobr></td>
        <td class="padding">:&nbsp;<input name="itemCode" id="itemCode" class="inputbox" value="" maxlength="20" onkeydown="return sendKeys(1,'itemName',event);"/></td>
	</tr>
	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Re-order Level<?php echo REQUIRED_FIELD ?></b>
	<?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getHelpLink('Reorder Manager',HELP_REORDER_MANAGER);
    	?>
	</nobr></td>
        <td class="padding">:&nbsp;<input name="reorderLevel" id="reorderLevel" class="inputbox" value="" maxlength="3" onkeydown="return sendKeys(1,'reorderLevel',event);"/></td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Unit<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;<select size="1" class="selectfield" name="unit" id="unit">
			<option value="">Select</option>
				  <?php
					  require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getUnitOfMeasurementData();
				  ?>
			</select>
		</td>
	</tr>
	<tr><td height="5px" colspan="3"></td></tr>
        <tr>
            <td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditItem');return false;"  />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>


<!--Help  Details  Div-->
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
<!--Help  Details  End -->


<!--Item Description -->
<?php  floatingDiv_Start('AddDescription','Add Item Description'); ?>
<form name="addDescription" id="addDescription" method="post" onsubmit="return false;">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 <tr><td height="5px" colspan ="3"></td></tr>
  <tr>
     <td colspan="2" class="contenttab_internal_rows" style="padding-bottom:8px;"><nobr><b>&nbsp;Category<?php echo REQUIRED_FIELD ?></b></nobr><b>:</b>&nbsp;

      <select name="categoryCode" id="categoryCode" class="inputbox" style='width:155px'  onchange="populateItemValues(this.value); return false;">
        <option value="">SELECT</option>
            <?php
                                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
			         echo HtmlFunctions::getInstance()->getItemCategoryData1();
            ?>
      </select>
   <select name="unitTypeHidden" id="unitTypeHidden" style ="display:none;">
        <option value="">SELECT</option>
    <?php
                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
					  echo HtmlFunctions::getInstance()->getUnitOfMeasurementData();
   ?>
</select>

     </td>
   </tr>
   <tr>
    <td width="100%" colspan="2" style="width:550px;" >
    <div id="tableDiv" style="height:300px;width:600px;overflow:auto;">
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
        <tbody id="anyidBody">
            <tr class="rowheading">
                <td width="5%" class="contenttab_internal_rows"><b>#</b></td>
                <td width="45%" class="contenttab_internal_rows"><b>Item Name</b></td>
                <td width="20%" class="contenttab_internal_rows"><b>Item Code</b></td>
                <td width="10%" class="contenttab_internal_rows"><b>Reorder Level</b><br><span style="font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;color:red;">( Min Stock Qty.)</span></td>
                 <td width="10%" class="contenttab_internal_rows"><b>Unit</b></td>
                 <td width="10%" class="contenttab_internal_rows"><b>Delete</b></td>
            </tr>
        </tbody>
        </table>
    </div>    
    </td>
    </tr>
   
    <tr>
    <td colspan="2">
    <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
       <a href="javascript:addOneRow(1);" title="Add Row"><font class="textClass"><b><nobr><u>Add More</u></b></font></a>
    </td>
    </tr> 
  
  <tr>
    <td height="5px" colspan="2"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateItemDescription();return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddDescription');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
 </tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--Help  Details  End -->

