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

require_once(BL_PATH.'/HtmlFunctions.inc.php');
//get list of suppliers
$supplierString= HtmlFunctions::getInstance()->getSupplierData();
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Inventory  &nbsp;&raquo;&nbsp;Order Master</td>
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
                        <td class="content_title">Order Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="blankValues();displayWindow('AddOrderDiv',460,250);return false;" />&nbsp;</td>
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
<?php floatingDiv_Start('AddOrderDiv','Add Order Details','',' '); ?>
    <form name="addOrderForm" action="" method="post" onsubmit="return false;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td class="contenttab_internal_rows"><b>Order Date <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding" width="1%">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('orderDate1',date('Y-m-d'));
      ?>
     </td>
    </tr>   
    <tr>
     <td class="contenttab_internal_rows"><b>Supplier <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <select name="supplierId" id="supplierId" class="inputbox" onchange="cleanUpTable('add');">
       <option value="">Select</option>
       <?php
        echo $supplierString;
       ?>
      </selct>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>No. of Items</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="itemCountTxt" id="itemCountTxt" class="inputbox" onblur="createMultipleRows('add',this.value)" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Dispatch <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="radio" name="dispatchChk" id="dispatchChk1" value="1" onclick="dispatchToggle(1,1)" />Yes&nbsp;
      <input type="radio" name="dispatchChk" id="dispatchChk2" value="0" onclick="dispatchToggle(1,0)" checked="checked" />No
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Dispatch Date</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('dispatchDate1','');
      ?>
     </td>
    </tr>
    <tr>
      <td class="padding" colspan="3"><a href="javascript:addOneRow('add');" title="Add One Record"><b>Add More</b></a></td>
    </tr> 
   
    <tr>
     <td colspan="3">
     <div id="containerDiv" style="height:200px;width:450px;overflow:auto">
       <table id="orderDetailTable_add" border="0" cellpadding="0" cellspacing="2" style="width:100%;">
        <tbody id="orderDetailTableBody_add">
         <tr  class="rowheading" id="addTrId">
          <th align="left" width="3%">#</th>
          <th width="20%" align="left">Item Code</th>
          <th width="20%" align="left">Name</th>
          <th width="20%" align="right">Quantity</th>
          <th align="right" width="2%" class="searchhead_text">Delete</th>
       </tr>
      </tbody>
    </table>
    </div>
     </td>    
   </tr>
   
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm('add',0);return false;" />
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif" onClick="return validateForm('add',1);return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddOrderDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />        </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<!--Start Edit Div-->
<?php floatingDiv_Start('EditOrderDiv','Edit Order Details',1,' '); ?>
    <form name="editOrderForm" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="orderId" id="orderId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td class="contenttab_internal_rows"><b>Order Date <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding" width="1%">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('orderDate2','');
      ?>
     </td>
    </tr>   
    <tr>
     <td class="contenttab_internal_rows"><b>Supplier <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <select name="supplierId" id="supplierId" class="inputbox" onchange="cleanUpTable('edit');" disabled="disabled">
       <option value="">Select</option>
       <?php
        echo $supplierString;
       ?>
      </selct>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>No. of Items</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="itemCountTxt" id="itemCountTxt" class="inputbox" onblur="createMultipleRows('edit',this.value)" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Dispatch <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="radio" name="dispatchChk" id="dispatchChk3" value="1" onclick="dispatchToggle(2,1)" />Yes&nbsp;
      <input type="radio" name="dispatchChk" id="dispatchChk4" value="0" onclick="dispatchToggle(2,0)" checked="checked" />No
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Dispatch Date</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('dispatchDate2','');
      ?>
     </td>
    </tr>
    <tr>
      <td class="padding" colspan="3"><a href="javascript:addOneRow('edit');" title="Add One Record"><b>Add More</b></a></td>
    </tr> 
   
    <tr>
     <td colspan="3">
     <div id="containerDiv" style="height:200px;width:450px;overflow:auto">
       <table id="orderDetailTable_edit" border="0" cellpadding="0" cellspacing="2" style="width:100%;">
        <tbody id="orderDetailTableBody_edit">
         <tr  class="rowheading" id="editTrId">
          <th align="left" width="3%">#</th>
          <th width="20%"   align="left">Item Code</th>
          <th width="20%"   align="left">Name</th>
          <th width="20%"   align="right">Quantity</th>
          <th align="right" width="2%" class="searchhead_text">Delete</th>
       </tr>
      </tbody>
    </table>
    </div>
     </td>    
   </tr>
   
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateForm('edit',0);return false;" />
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif" onClick="return validateForm('edit',1);return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditOrderDiv');return false;" />        </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Edit Div-->
<?php
// $History: orderMasterContents.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/09/09   Time: 14:24
//Updated in $/Leap/Source/Templates/INVENTORY/OrderMaster
//Fixed bugs found during self-testing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:40
//Created in $/Leap/Source/Templates/INVENTORY/OrderMaster
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 2/09/09    Time: 19:10
//Updated in $/Leap/Source/Templates/OrderMaster
//Corrected javascript calling of dispatch date display
?>