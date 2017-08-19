<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Gurkeerat Sidhu
// Created on : (05.05.2009 )
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
				<form name="searchForm" onSubmit="getAlertsCategoryData(); return false;">
            <tr>
                <td valign="top">Inventory Master&nbsp;&raquo;&nbsp;Supplier Master </td>
				<td valign="top" align="right">
                <input type="text" name="searchbox" id="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,''); return false;"/>&nbsp;
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
                        <td class="content_title">Supplier Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddSupplierDetail',370,250);blankValues();return false;" />&nbsp;</td>
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
<?php floatingDiv_Start('AddSupplierDetail','Add Supplier'); ?>
<form name="AddSupplierDetail" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Company Name </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="companyName" name="companyName"  tabindex="1" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Supplier Code </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="supplierCode" name="supplierCode" maxlength="10" tabindex="2" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Address</strong></nobr></td>
      <td class="padding" valign="top">:&nbsp;</td>
      <td><textarea class="inputbox" name="address" id="address" cols="15" rows="4" tabindex="3" style="vertical-align:top;"></textarea>
     </td>
    </tr>
    <tr> 
            <td class="contenttab_internal_rows">&nbsp;&nbsp;<?php echo REQUIRED_FIELD ?><nobr><b>Country </b></nobr></td>
            <td class="padding">:&nbsp;</td>
            <td>
            <select size="1" class="selectfield" name="countryId" id="countryId" tabindex="4" onchange="autoPopulate(this.value, 'states', 'Add')";>
            <option value="" selected="selected">Select</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getCountriesData();
            ?>
            </select>
            </td>
     </tr>
     <tr>
            <td class="contenttab_internal_rows">&nbsp;&nbsp;<?php echo REQUIRED_FIELD ?><nobr><b>State </b></nobr></td>
            <td class="padding">:&nbsp;</td>
            <td><select size="1" class="selectfield" name="stateId" id="stateId" tabindex="5"
            onchange="autoPopulate(this.value, 'city', 'Add')";>
            <option value="" >Select</option>
            </select>
            </td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows">&nbsp;&nbsp;<?php echo REQUIRED_FIELD ?><nobr><b>City </b></nobr></td>
            <td class="padding">:&nbsp;</td>
            <td><select size="1" class="selectfield" name="cityId" id="cityId" tabindex="6">
             <option value="">Select</option>
                </select>
            </td>
    </tr>
    <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Contact Person </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="contactPerson" name="contactPerson"  tabindex="7" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Contact Person Phone</strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="contactPersonPhone" name="contactPersonPhone" tabindex="8" maxlength="25" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Company Phone </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="companyPhone" name="companyPhone" tabindex="9" maxlength="25" class="inputbox" />
     </td>
   </tr>
  <tr>
    <td height="5px" colspan="3"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="3">
       <input type="image" name="imageField" tabindex="10" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" tabindex="11" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddSupplierDetail');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->
 <!--Start Edit Div-->
<?php floatingDiv_Start('EditSupplierDetail','Edit Supplier'); ?>
<form name="EditSupplierDetail" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="supplierId" id="supplierId" value="" />
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Company Name </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="companyName" name="companyName"  tabindex="1" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Supplier Code </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="supplierCode" name="supplierCode" maxlength="10" tabindex="2" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Address</strong></nobr></td>
      <td class="padding" valign="top">:&nbsp;</td>
      <td><textarea class="inputbox" name="address" id="address" cols="15" rows="4" tabindex="3" style="vertical-align:top;"></textarea>
     </td>
    </tr>
    <tr> 
            <td class="contenttab_internal_rows">&nbsp;&nbsp;<?php echo REQUIRED_FIELD ?><nobr><b>Country </b></nobr></td>
            <td class="padding">:&nbsp;</td>
            <td>
            <select size="1" class="selectfield" name="countryId" id="countryId" tabindex="4" onchange="autoPopulate(this.value, 'states', 'Edit')";>
            <option value="" selected="selected">Select</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getCountriesData();
            ?>
            </select>
            </td>
     </tr>
     <tr>
            <td class="contenttab_internal_rows">&nbsp;&nbsp;<?php echo REQUIRED_FIELD ?><nobr><b>State </b></nobr></td>
            <td class="padding">:&nbsp;</td>
            <td><select size="1" class="selectfield" name="stateId" id="stateId" tabindex="5"
            onchange="autoPopulate(this.value, 'city', 'Edit')";>
            <option value="" >Select</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getStatesData();
            ?>
            </select>
            </td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows">&nbsp;&nbsp;<?php echo REQUIRED_FIELD ?><nobr><b>City </b></nobr></td>
            <td class="padding">:&nbsp;</td>
            <td><select size="1" class="selectfield" name="cityId" id="cityId" tabindex="6">
            <option value="">Select</option>
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getCityData( );
            ?>
            </select>
            </td>
    </tr>
    <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Contact Person </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="contactPerson" name="contactPerson"  tabindex="7" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Contact Person Phone</strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="contactPersonPhone" name="contactPersonPhone" tabindex="8" maxlength="25" class="inputbox" />
     </td>
   </tr>
   <tr> 
      <td  class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<?php echo REQUIRED_FIELD; ?><strong>Company Phone </strong></nobr></td>
      <td  class="padding">:</td>
      <td><input type="text" id="companyPhone" name="companyPhone" tabindex="9" maxlength="25" class="inputbox" />
     </td>
   </tr>
  <tr>
    <td height="5px" colspan="3"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="3">
       <input type="image" name="imageField" tabindex="10" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" tabindex="11" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditSupplierDetail');return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Edit Div-->


