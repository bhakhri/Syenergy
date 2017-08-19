<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Gurkeerat Sidhu
// Created on : (08.05.2009 )
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
				<form name="searchBox1" onSubmit="getInventoryDepartmentData(); return false;">
            <tr>
                <td valign="top">Inventory&nbsp;&raquo;&nbsp;Deptt./Store Master </td>
				<td valign="top" align="right">
                <input type="text" name="searchbox" id="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="getInventoryDepartmentData(); return false;"/>&nbsp;
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
                        <td class="content_title">Deptt./Store Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('InventoryDepartmentDiv',340,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" colspan="4">
                <div id="InventoryDepartmentResultDiv">
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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('InventoryDepartmentDiv',''); ?>
<form name="InventoryDepartmentDetail" action="" method="post">  
<input type="hidden" name="invDepttId" id="invDepttId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Deptt./Store Name</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="inventoryDeptName" name="inventoryDeptName"  class="inputbox" maxlength="100" onkeydown="return sendKeys(1,'inventoryDeptName',event);" /></td>
	</tr>
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Abbreviation</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="abbr" name="abbr"  class="inputbox" maxlength="50" onkeydown="return sendKeys(1,'abbr',event);"/></td>
	</tr>
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Deptt./Store Type</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
	  <select size="1" class="selectfield" name="departmentType" id="departmentType">
		 <option value="">Select</option>
			<?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->getInventoryDepartmentType();
			?>
	 </select>
	 </td>
   </tr>
   <tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Employee Name</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
	  <select size="1" class="selectfield" name="employeeName" id="employeeName">
		 <option value="">Select</option>
			<?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->getTeacher();
			?>
	 </select>
	 </td>
   </tr>
   <tr>
		<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>From Date</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
		<td class="padding">:&nbsp;
		<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('fromDate',date(''));
		?>
		</td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>To Date</strong></nobr></td>
		<td class="padding">:&nbsp;
		<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('toDate',date(''));
		?>
		</td>
	</tr>
   <tr>
	 <td class="contenttab_internal_rows" valign="top"><b>&nbsp;&nbsp;Description</b></td>
	 <td class="padding">:
		<textarea name="description" id="description" rows="3" cols="42" class="inputbox" maxlength="200" style="vertical-align:top" onkeyup="return ismaxlength(this);" onkeydown="return sendKeys(1,'description',event);" ></textarea>
	</td>
   </tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('InventoryDepartmentDiv');if(flag==true){getInventoryDepartmentData();flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



