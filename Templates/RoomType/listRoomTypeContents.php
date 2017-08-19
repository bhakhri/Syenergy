<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Gurkeerat Sidhu
// Created on : (19.05.2009 )
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('RoomTypeActionDiv',340,250);blankValues();return false;"/>&nbsp;</td></tr>
            <tr>
	
								<td class="contenttab_row" colspan="2" valign="top" ><div id="RoomTypeResultDiv"></div></td>

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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('RoomTypeActionDiv',''); ?>
<form name="RoomTypeDetail" action="" method="post">  
<input type="hidden" name="roomTypeId" id="roomTypeId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr> 
      <td width="25%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Room Type </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="75%" class="padding">:&nbsp;
      <input type="text" id="roomType" name="roomType"  style="width:170px" class="inputbox" />
     </td>
   </tr>
    <tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Abbreviation </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td class="padding">:&nbsp;
      <input type="text" id="abbr" name="abbr"  style="width:170px" class="inputbox" maxlength="20"/>
     </td>
   </tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('RoomTypeActionDiv');if(flag==true){getRoomTypeData();flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



