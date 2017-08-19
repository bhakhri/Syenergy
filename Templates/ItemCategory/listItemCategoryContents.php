<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
				<form name="searchBox1" onSubmit="getItemCategoryData(); return false;">
            <tr>
                <td valign="top">Inventory Master&nbsp;&raquo;&nbsp;Item Category Master </td>
				<td valign="top" align="right">
                <input type="text" name="searchbox" id="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="getItemCategoryData(); return false;"/>&nbsp;
                  </td>
            </tr>
			</form>
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
                        <td class="content_title">Item Category Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('ItemCategoryActionDiv',340,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" colspan="4">
                <div id="ItemCategoryResultDiv">
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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('ItemCategoryActionDiv',''); ?>
<form name="ItemCategoryDetail" action="" method="post">  
<input type="hidden" name="itemCategoryId" id="itemCategoryId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Item Category </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="categoryName" name="categoryName"  style="width:170px" class="inputbox" />
     </td>
   </tr>
    <tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Abbreviation </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="abbr" name="abbr"  style="width:170px" class="inputbox" maxlength="20"/>
     </td>
   </tr>
  <tr>
    <td height="5px"></td></tr>
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
<?php floatingDiv_End(); ?>
<!--End Add Div-->



