<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">Inventory  &nbsp;&raquo;&nbsp;Order Receive Master</td>
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
                        <td class="content_title">Received Orders Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddReceiveDiv',550,250);blankValues();return false;" />&nbsp;</td>
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
          
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<!--Start Add Div-->
<?php floatingDiv_Start('AddReceiveDiv','Add Order Receive Details','',' '); ?>
    <form name="addReceiveForm" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="orderId" id="orderId" value="" />
    <input type="hidden" name="dispatchDate" id="dispatchDate" value="" />
    <input type="hidden" name="totalItems" id="totalItems" value="0" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td class="contenttab_internal_rows"><b>Receive Date<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('receiveDate1',date('Y-m-d'));
      ?>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Order No<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="orderNo" id="orderNo" class="inputbox" onblur="getOrderDetails(this.value)" value="" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Update Stock<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="radio" name="updateStore" id="updateStore1" value="1" />Yes&nbsp;
      <input type="radio" name="updateStore" id="updateStore2" value="0" checked="checked" />No
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Tax<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="taxAmount" id="taxAmount" class="inputbox" value="0" onblur="calculateTotalAmount(this.value,document.addReceiveForm.totalItems.value,'add');" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Total Amount</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="totalAmount" id="totalAmount" class="inputbox" value="0" readonly="readonly" />
     </td>
    </tr>   
    <tr>
     <td class="contenttab_internal_rows"><b>Order Date</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <div id="orderDate1" style="display:inline"></div>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Supplier</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <div id="orderSupplier1" style="display:inline"></div>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Dispatch Date</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <div id="dispatchDate1" style="display:inline"></div>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" colspan="3">
      <b><u>Order Details</u></b>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" colspan="3">
      <div id="orderDetailsDiv1" style="width:400px;height:200px;overflow:auto;"></div>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Remarks</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <textarea name="commentsTxt" id="commentsTxt" rows="3" cols="35" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
     </td>
    </tr>
    <tr>
        <td align="center" style="padding-right:10px" colspan="3">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm('add',document.addReceiveForm.totalItems.value);return false;" />
            <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddReceiveDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
    </tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<!--Start Edit Div-->
<?php floatingDiv_Start('EditReceiveDiv','Edit Received Order Details','',' '); ?>
    <form name="editReceiveForm" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="orderId" id="orderId" value="" />
    <input type="hidden" name="dispatchDate" id="dispatchDate" value="" />
    <input type="hidden" name="totalItems" id="totalItems" value="0" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td class="contenttab_internal_rows"><b>Receive Date<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('receiveDate2','');
      ?>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Order No<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="orderNo" id="orderNo" class="inputbox" disabled="disabled" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Update Stock<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="radio" name="updateStore" id="updateStore3" value="1" />Yes&nbsp;
      <input type="radio" name="updateStore" id="updateStore4" value="0" />No
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Tax<?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="taxAmount" id="taxAmount" class="inputbox" value="0" onblur="calculateTotalAmount(this.value,document.editReceiveForm.totalItems.value,'edit');" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Total Amount</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="totalAmount" id="totalAmount" class="inputbox" value="0" readonly="readonly" />
     </td>
    </tr>   
    <tr>
     <td class="contenttab_internal_rows"><b>Order Date</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <div id="orderDate2" style="display:inline"></div>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Supplier</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <div id="orderSupplier2" style="display:inline"></div>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Dispatch Date</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <div id="dispatchDate2" style="display:inline"></div>
     </td>
    </tr> 
    <tr>
     <td class="contenttab_internal_rows" colspan="3">
      <b><u>Order Details</u></b>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" colspan="3">
      <div id="orderDetailsDiv2" style="width:400px;height:150px;overflow:auto;"></div>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Remarks</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <textarea name="commentsTxt" id="commentsTxt" rows="3" cols="35" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
     </td>
    </tr>
    <tr>
        <td align="center" style="padding-right:10px" colspan="3">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm('edit',document.editReceiveForm.totalItems.value);return false;" />
            <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditReceiveDiv');return false;" />
        </td>
    </tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<!--End Add Div-->
<?php
// $History: receiveMasterContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/09/09    Time: 15:14
//Updated in $/Leap/Source/Templates/INVENTORY/ReceiveMaster
//Updated "Order Receive Master"----Added "update stock" field and added
//the code : if update stock option is yes then main item master table is
//also updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/09    Time: 15:31
//Updated in $/Leap/Source/Templates/INVENTORY/ReceiveMaster
//Updated "Receive Order Master" : Added tax ,total amount and item price
//amount fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/09/09    Time: 16:54
//Created in $/Leap/Source/Templates/INVENTORY/ReceiveMaster
//Created module "Order Receive Master"
?>