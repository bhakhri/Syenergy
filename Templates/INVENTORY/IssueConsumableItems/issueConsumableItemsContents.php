<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Inventory  &nbsp;&raquo;&nbsp;Issue Consumable Items</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm" onsubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value;sendReq(listURL,divResultName,searchFormName,'');return false;">
                <input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" />
                  &nbsp;
                  <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value;sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
                  </form>
                  </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Consumable Items Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/inv.gif" style="width:30px;height:30px;" 
                        align="right" onClick="displayWindow('AddConsumableItemsDiv',250,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">  
                </div>           
             </td>
          </tr>
		  <tr><td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="csv" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td></tr>
          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<!--Start Add Div-->
<?php floatingDiv_Start('AddConsumableItemsDiv','Add Consumable Items Details','',' '); ?>
	<form name="addConsumableItems" action="" method="post" onsubmit="return false;">  
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<tr>
				<td class="contenttab_internal_rows">&nbsp;&nbsp;<b>Issuing Deptt./Store<?php echo REQUIRED_FIELD; ?></b></td>
				<td class="padding">:</td>
				<td class="padding"><select size="1" class="selectfield" name="store" id="store" onchange="getDepttName();">
					<option value="">Select</option>
					  <?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getInvDepttData();
					  ?>
				</select>
				</td>
			</tr>   
			<tr>
				<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Issuing Item </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
				<td class="padding">:</td>
				<td class="padding">
					<select size="1" class="selectfield" name="itemCategory" id="itemCategory" onchange="getItemName();" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getItemConsumableCategoryData('',"AND im.itemType=1");
						?>
					</select>
				</td>
				<td colspan="4">
					<span id="showImage"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/issue_items.gif" onclick="getAddRowList();" /></span>
				</td>
			</tr>
		
			<tr>
				<td>
					<select size="1" class="selectfield" name="hiddenItemName" id="hiddenItemName" style="display:none">
						<option value="">Select</option>
							<?php
								//require_once(BL_PATH.'/HtmlFunctions.inc.php');
								//echo HtmlFunctions::getInstance()->getItemCategoryData();
							?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<select size="1" class="selectfield" name="hiddenIssuedTo" id="hiddenIssuedTo" style="display:none">
						<option value="">Select</option>
							<?php
								//require_once(BL_PATH.'/HtmlFunctions.inc.php');
								//echo HtmlFunctions::getInstance()->getItemCategoryData();
							?>
					</select>
				</td>
			</tr>
			
			<tr id="itemRow" style="display:none">
                <td valign="top" colspan="6">
					<div id="addConsumableResults" style="display:none;overflow:auto;height:100px;" >    
                         <!--<h3>Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></h3>  -->
                          <table class="padding" width="90%" border="0"  id="anyid_add">
                            <tbody id="anyidBody_add">
                              <tr class="rowheading">
                                <td  class="searchhead_text" width="10%"><b>Sr. No.</b></td>
                                <td  class="searchhead_text" width="20%"><b>Item Name</b></td>
					            <td  class="searchhead_text" width="20%"><b>Req. Quantity</b></td>
								<td  class="searchhead_text" width="20%"><b>Issued To</b></td>
								<td  class="searchhead_text" width="10%"><b>Action</b></td>
                              </tr>
                              
                            </tbody>
                         </table>
                         <h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1,'add');" title="Add One Row"><b>+</b></a></h3>
					</div>
                 </td>
           </tr>
		   
		   <tr id="dateOfIssue" style="display:none">
			 <td class="contenttab_internal_rows"><b>Issue Date</b></td>
			 <td class="padding">:</td>
			 <td class="padding">
			  <?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->datePicker('issuedDate',date('Y-m-d'));
			  ?>
			 </td>
		   </tr>	
		   <tr id="comments" style="display:none">
			 <td class="contenttab_internal_rows"><b>Comments</b></td>
			 <td class="padding">:</td>
			 <td class="padding">
			  <textarea name="commentsTxt" id="commentsTxt" rows="3" cols="42" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
			 </td>
		   </tr>
			 

<tr id="button" style="display:none">
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddConsumableItemsDiv');blankValues();if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<!--Start Edit Div-->
<?php floatingDiv_Start('EditConsumableItemsDiv','Edit Consumable Details',1,' '); ?>
    <form name="editConsumableItems" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="invConsumableIssuedId" id="invConsumableIssuedId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<tr><td height="5px"></td></tr>
			<tr>
				<td class="contenttab_internal_rows"><b>&nbsp;&nbsp;Deptt./Store<?php echo REQUIRED_FIELD; ?></b></td>
				<td class="padding">:</td>
				<td><select size="1" class="selectfield" name="store" id="store" disabled="true">
					<option value="">Select</option>
					  <?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getInvDepttData();
					  ?>
				</select>
				</td>
			</tr>   
			<tr>
				<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Item Category</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
				<td class="padding">:</td>
				<td>
					<select size="1" class="selectfield" name="itemCategory" id="itemCategory" onChange="getEditItemName(this.value);" disabled="true" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getItemCategoryData();
						?>
					</select>
				</td>
				<!--
				<td>
					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="addOneRow('','edit');" />
				</td>-->
			</tr>
			<tr>
				<td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Item Name <?php echo REQUIRED_FIELD ?></b></nobr></td>
					<td class="padding">:&nbsp;</td>
					<td>
					<select size="1" class="selectfield" name="editItemName" id="editItemName" disabled="true">
						<option value="">Select</option>
							<?php
								//require_once(BL_PATH.'/HtmlFunctions.inc.php');
								//echo HtmlFunctions::getInstance()->getItemCategoryData();
							?>
					</select>
					</td>
				</td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Quantity <?php echo REQUIRED_FIELD ?></b></nobr></td>
					<td class="padding">:&nbsp;</td>
					<td><input type="text" id="editItemQuantity" name="editItemQuantity" class="inputbox" disabled="true"></td>
				</td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Issued To<?php echo REQUIRED_FIELD ?></b></nobr></td>
					<td class="padding">:&nbsp;</td>
					<td>
					<select size="1" class="selectfield" name="editIssuedTo" id="editIssuedTo">
						<option value="">Select</option>
							<?php
								//require_once(BL_PATH.'/HtmlFunctions.inc.php');
								//echo HtmlFunctions::getInstance()->getItemCategoryData();
							?>
					</select>
					</td>
				</td>
			</tr>
			<!--
			<tr>
				<td>
					<select size="1" class="selectfield" name="hiddenEditItemName" id="hiddenEditItemName" style="display:none">
						<option value="">Select</option>
							<?php
								//require_once(BL_PATH.'/HtmlFunctions.inc.php');
								//echo HtmlFunctions::getInstance()->getItemCategoryData();
							?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<select size="1" class="selectfield" name="hiddenEditIssuedTo" id="hiddenEditIssuedTo" style="display:none">
						<option value="">Select</option>
							<?php
								//require_once(BL_PATH.'/HtmlFunctions.inc.php');
								//echo HtmlFunctions::getInstance()->getItemCategoryData();
							?>
					</select>
				</td>
			</tr>
			-->
			<!--
			<div id="editConsumableResults" style="display:none">
			<tr id="itemRow1" style="display:none">
                <td valign="top" colspan="4">
                          <table class="padding" width="100%" border="0"  id="anyid_edit">
                            <tbody id="anyidBody_edit">
                              <tr class="rowheading">
                                <td  class="searchhead_text"><b>Sr. No.</b></td>
                                <td  class="searchhead_text"><b>Item Name</b></td>
					            <td  class="searchhead_text"><b>Req. Quantity</b></td>
								<td  class="searchhead_text"><b>Issued To</b></td>
								<td  class="searchhead_text"><b></b></td>
                              </tr>
                              
                            </tbody>
                         </table>
                         <h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1,'edit');" title="Add One Row"><b>+</b></a></h3>
                 </td>
           </tr>
		   </div>-->
		   <tr id="dateOfIssue1" style="display:">
			 <td class="contenttab_internal_rows"><b>&nbsp;&nbsp;Issue Date</b></td>
			 <td class="padding">:</td>
			 <td>
			  <?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->datePicker('issuedDate1',date('Y-m-d'));
			  ?>
			 </td>
		   </tr>
		   <tr id="comments1" style="display:">
			 <td class="contenttab_internal_rows"><b>&nbsp;&nbsp;Comments</b></td>
			 <td class="padding">:</td>
			 <td>
			  <textarea name="commentsTxt" class="inputbox" id="commentsTxt" rows="3" cols="42" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
			 </td>
		   </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditConsumableItemsDiv');return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Edit Div-->
<?php
// $History: issueConsumableItemsContents.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:11a
//Created in $/Leap/Source/Templates/INVENTORY/IssueConsumableItems
//new files for issue consumable items
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/09/09   Time: 14:24
//Updated in $/Leap/Source/Templates/INVENTORY/IndentMaster
//Fixed bugs found during self-testing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Templates/INVENTORY/IndentMaster
//Created  "Indent Master" module under "Inventory Management"
?>