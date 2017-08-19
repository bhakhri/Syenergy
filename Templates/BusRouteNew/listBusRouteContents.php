<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSROUTE LISTING 
//
// Author : Nishu Bindal
// Created on : (17.April.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBusRoute',320,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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

<?php floatingDiv_Start('AddBusRoute','Add Bus Route'); ?>
<form name="AddBusRoute" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
        <td width="2%" class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input style='width:198px' type="text" id="routeName" name="routeName" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="2%" class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input  style='width:198px' type="text" id="routeCode" name="routeCode" class="inputbox" maxlength="10" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Bus</b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"  valign="top"><b><nobr>
            <select name="busId" id="busId" class="inputbox1" style="width: 200px;" >
            <option value=''>Select</option>
            <?php
                $condition=" WHERE vechicleCategoryId = 2 AND isActive = 1";
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBusData('',$condition);
            ?>
            </select></nobr>
        </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBusRoute');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditBusRoute','Edit Bus Route'); ?>
<form name="EditBusRoute" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="busRouteId" id="busRouteId" value="" />  
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input style='width:198px'  type="text" id="routeName" name="routeName" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Vehicle Route Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"><input style='width:198px'  type="text" id="routeCode" name="routeCode" class="inputbox" maxlength="10" /></td>
    </tr>
	
    <tr>    
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Bus</b></nobr></td>
        <td width="2%" class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding"  valign="top"><b><nobr>
            <select name="busId" id="busId" class="inputbox1" style="width: 200px;" >
             <option value=''>Select</option>
            <?php
                $condition=" WHERE vechicleCategoryId = 2 AND isActive = 1";
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getBusData('',$condition);
            ?>
            </select></nobr>
        </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBusRoute');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form> 
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->



