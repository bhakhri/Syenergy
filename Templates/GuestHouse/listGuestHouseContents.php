<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick=" displayWindow('GuestHouseDiv',315,250);blankValues();return false;  " />&nbsp;</td></tr>
             <tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
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

<?php floatingDiv_Start('GuestHouseDiv','','',' '); ?>
    <form name="guestHouse" action="" method="post">  
    <input type="hidden" name="bookingId" id="bookingId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr id="bookingNumRowId" style="display:none">
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Booking No.</b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="padding"><input type="text" id="bookingNumber" name="bookingNumber" class="inputbox" style="width:238px;" disabled="disabled" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Guest Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="padding"><input type="text" id="guestName" name="guestName" class="inputbox" style="width:238px;" maxlength="50" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Arrival Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="padding">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('arrivalDate',date('Y-m-d'));
        ?>
        &nbsp;Time : &nbsp;
        <input type="text" id="startTime" name="startTime" class="inputbox" style="width:50px" maxlength="5"/>
        <select size="1" name="startAmPm" id="startAmPm"  class="selectfield" style="width:45px">
            <option value="AM" width="10%">AM</option>
            <option value="PM">PM</option>
            </select>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Departure Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="padding">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('departureDate',date('Y-m-d'));
        ?>
        &nbsp;Time : &nbsp;
        <input type="text" id="endTime" name="endTime" class="inputbox" style="width:50px" maxlength="5"/>
        <select size="1" name="endAmPm" id="endAmPm" class="selectfield" style="width:45px">
            <option value="AM" width="10%">AM</option>
            <option value="PM">PM</option>
            </select>
        </td>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Budget Head<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td width="78%" class="padding"><select size="1" class="selectfield" name="budgetHead" id="budgetHead" style="width:240px;">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getBudgetHeadsData(' WHERE headTypeId='.GUEST_HOUSE);
              ?>
        </select>
    </td>
   </tr>
   <tr id="alterArrTrId" style="display:none">
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Alternative<br/> Arrangement<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" width="1%" valign="top">:</td>
        <td width="78%" class="padding">
        <textarea name="alternativeArrangement" id="alternativeArrangement" rows="3" class="inputbox" style="width:98%"></textarea>
        </td>
   </tr>
   <tr id="reasonTrId" style="display:none">
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Reason for<br/>Rejection</b></nobr></td>
        <td class="padding" width="1%" valign="top">:</td>
        <td width="78%" class="contenttab_internal_rows" style="padding-left:5px;">
        <div id="rejectionReason" style="width:250px;height:60px;overflow:auto"></div>
        </td>
   </tr>
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form);return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('GuestHouseDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
     </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<?php
// $History: listCityContents.php $
?>