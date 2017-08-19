<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;" title="Add Party"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('PartyActionDiv',340,250);blankValues();return false;"" />&nbsp;</td></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printPartyReport();" title="Print">&nbsp;
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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('PartyActionDiv',''); ?>
<form name="PartyDetail" action="" method="post">  
<input type="hidden" name="partyId" id="partyId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
	<td height="5px"></td></tr>
<tr>
<tr>
	<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Party Name</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
	<td class="padding">:&nbsp;<input type="text" id="partyName" name="partyName" class="inputbox" maxlength="100"/>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Party Code</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
	<td class="padding">:&nbsp;<input type="text" id="partyCode" name="partyCode" class="inputbox" maxlength="50"/>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Party Address</strong></nobr></td>
	<td class="padding">:&nbsp;<textarea id="partyAddress" name="partyAddress" cols="22" rows="3" class="inputbox" style="vertical-align:middle"  maxlength="150" onkeyup="return ismaxlength(this)"></textarea>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Phone(s)</strong></nobr></td>
	<td class="padding">:&nbsp;<input type="text" id="partyPhones" name="partyPhones" class="inputbox" maxlength="50"/>
	</td>
</tr>
<tr>
	<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Fax</strong></nobr></td>
	<td class="padding">:&nbsp;<input type="text" id="partyFax" name="partyFax" class="inputbox" maxlength="50"/>
	</td>
</tr>
<tr>
	<td height="5px"></td>
</tr>
<tr>
	<td align="center" style="padding-right:10px" colspan="4">
	<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
	<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('PartyActionDiv');if(flag==true){getPartyData();flag=false;}return false;" />
</td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->