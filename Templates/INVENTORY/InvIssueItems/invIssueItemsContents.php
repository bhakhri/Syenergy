<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to timetable to class.
//
// Author : Jainesh
// Created on : (30.06.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Inventory&nbsp;&raquo;&nbsp;Issue Non-Consumable Items</td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
				<td valign="top" class="content">
				<form action="" method="POST" name="listForm" id="listForm">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="contenttab_border2" colspan="2">
							<table width="350" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Deptt./Store<?php echo REQUIRED_FIELD ?></b></nobr></td>
								<td class="contenttab_internal_rows" >&nbsp;<b>:</b>&nbsp;</td>
								<td><select size="1" class="selectfield" name="store" id="store" onchange = "clearData();">
								<option value="">Select</option>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getInvDepttData();
									  ?>
								</select>
							</td>
							<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Category <?php echo REQUIRED_FIELD ?></b></nobr></td>
								<td class="contenttab_internal_rows">&nbsp;<b>:</b>&nbsp;</td>
								<td><select size="1" class="selectfield" name="itemCategory" id="itemCategory" onchange="getItemName();clearItemData();">
								<option value="">Select</option>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getItemConsumableCategoryData('',"AND im.itemType = 2");
									  ?>
								</select>
							</td>
							<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Item Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
								<td class="contenttab_internal_rows">&nbsp;<b>:</b>&nbsp;</td>
								<td>
								<select size="1" class="selectfield" name="itemName" id="itemName" onchange="clearData1();">
									<option value="">Select</option>
										<?php
											//require_once(BL_PATH.'/HtmlFunctions.inc.php');
											//echo HtmlFunctions::getInstance()->getItemCategoryData();
										?>
								</select>
								</td>
							</td>

							<td  align="right" style="padding-left:3px">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/show_items.gif" onClick="getItems(); return false;"/>
							</td>
						</tr>
						<tr>
							<td colspan="4" height="5px"></td>
						</tr>
						</table>
					    </td>
					</tr>
					<tr style="display:none" id="showTitle">
						<td class="contenttab_border" height="20" colspan="2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">Item Details : </td>
							</tr>
							</table>
						</td>
					</tr>
					
					 <tr style="display:none" id="showData">
						<td class="contenttab_row" valign="top" colspan="2"><div id="results" style="overflow:auto;height:350px;">  
						 </div>
						 <!--
						 <div id="txtReason" class="contenttab_internal_rows"><b>Reason</b><?php echo REQUIRED_FIELD;?><b>:&nbsp;</b><textarea class="inputbox" name="reason" id="reason" cols="40" rows="5" style="vertical-align:top;"></textarea></div>-->
						 
						 <div id="showIssuedItem">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<nobr><b>Action : </b>&nbsp;<select size="1" class="selectfield" name="issuedItemStatus" id="issuedItemStatus" onchange="getAction();">
								<option value="">Select</option>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getIssuedItemStatusData();
									  ?>
								</select>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id="issTo" style="width:90px"></span></b>&nbsp;&nbsp;<select size="1" class="selectfield" name="issuedTo" id="issuedTo">
								</select>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id="issDate" style="width:90px"></span>&nbsp;&nbsp;</b>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->datePicker('issuedDate',date('Y-m-d'));
									  ?>
						 </div>
						 </td>
					 </tr>
					
					 <tr  id = 'saveDiv1' style='display:none;'>
						<td align="right" width="55%">
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" value="freeze" onclick="return validateAddForm();return false;" />
						</td>
					</tr>
				</table>
			</form>	
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
<?php 
// $History: invIssueItemsContents.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:10a
//Created in $/Leap/Source/Templates/INVENTORY/InvIssueItems
//new template for issue items
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/18/09    Time: 7:16p
//Updated in $/Leap/Source/Templates/FrozenClass
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/02/09    Time: 12:11p
//Created in $/Leap/Source/Templates/FrozenClass
//new content file to show frozen class template
//
?>