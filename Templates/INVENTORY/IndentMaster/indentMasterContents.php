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
                <td valign="top">Inventory  &nbsp;&raquo;&nbsp;Indent Master</td>
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
                        <td class="content_title">Indent Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="blankValues();displayWindow('AddIndentDiv',460,250);return false;" />&nbsp;</td>
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
<?php floatingDiv_Start('AddIndentDiv','Add Indent Details','',' '); ?>
    <form name="addIndentForm" action="" method="post" onsubmit="return false;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td class="contenttab_internal_rows"><b>Indent Date <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('indentDate1',date('Y-m-d'));
      ?>
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
     <td class="contenttab_internal_rows"><b>Requested to  <?php echo REQUIRED_FIELD; ?></b><br/>(Emp Code)</td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="employeeCode" id="employeeCode" class="inputbox"  />&nbsp;
      <img src="<?php echo IMG_HTTP_PATH;?>/search_icon.gif" onClick="return toggleEmployeeDiv(1);return false;" title="Search" alt="Search" style="margin-bottom: -5px;" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" colspan="3">
      <!--Displays Employee Div-->
      <div id="employeeDiv1" style="display:none;height:150px;width:99%;overflow:auto;"></div>
     </td>
    </tr>
    <tr>
      <td class="padding" colspan="3"><a href="javascript:addOneRow('add');" title="Add One Record"><b>Add More</b></a></td>
    </tr> 
    <tr>
     <td colspan="3">
     <div id="containerDiv" style="height:200px;width:450px;overflow:auto">
       <table id="indentDetailTable_add" border="0" cellpadding="0" cellspacing="2" style="width:100%;">
        <tbody id="indentDetailTableBody_add">
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
   <tr>
     <td class="contenttab_internal_rows"><b>Comments</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <textarea name="commentsTxt" id="commentsTxt" rows="3" cols="42" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
     </td>
   </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm('add');return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddIndentDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<!--Start Edit Div-->
<?php floatingDiv_Start('EditIndentDiv','Edit Indent Details',1,' '); ?>
    <form name="editIndentForm" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="indentId" id="indentId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td class="contenttab_internal_rows"><b>Indent Date <?php echo REQUIRED_FIELD; ?></b></td>
     <td class="padding">:</td>
     <td class="padding">
      <?php
       echo HtmlFunctions::getInstance()->datePicker('indentDate2','');
      ?>
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
     <td class="contenttab_internal_rows"><b>Requested to <?php echo REQUIRED_FIELD; ?></b><br/>(Emp Code)</td>
     <td class="padding">:</td>
     <td class="padding">
      <input type="text" name="employeeCode" id="employeeCode" class="inputbox"  />&nbsp;
      <img src="<?php echo IMG_HTTP_PATH;?>/search_icon.gif" onClick="return toggleEmployeeDiv(2);return false;" title="Search" alt="Search" style="margin-bottom: -5px;" />
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows" colspan="3">
      <!--Displays Employee Div-->
      <div id="employeeDiv2" style="display:none;height:150px;width:99%;overflow:auto;"></div>
     </td>
    </tr>
    <tr>
      <td class="padding" colspan="3"><a href="javascript:addOneRow('edit');" title="Add One Record"><b>Add More</b></a></td>
    </tr> 
   
    <tr>
     <td colspan="3">
     <div id="containerDiv" style="height:200px;width:450px;overflow:auto">
       <table id="indentDetailTable_edit" border="0" cellpadding="0" cellspacing="2" style="width:100%;">
        <tbody id="indentDetailTableBody_edit">
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
   <tr>
     <td class="contenttab_internal_rows"><b>Comments</b></td>
     <td class="padding">:</td>
     <td class="padding">
      <textarea name="commentsTxt" id="commentsTxt" rows="3" cols="42" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
     </td>
   </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateForm('edit');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditIndentDiv');return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Edit Div-->
<?php
// $History: indentMasterContents.php $
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