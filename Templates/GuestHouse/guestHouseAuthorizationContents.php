<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

 require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
        </td>
      </tr>
	   <tr height="30">
	<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								</tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
            
                   
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                 <td>
                  <div id="results"></div>
                 </td>
                </tr>
                </table>  
             </td>
          </tr>
          <tr>
                <td>
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

<?php floatingDiv_Start('GuestHouseDiv','Guest House Authorization'); ?>
    <form name="guestHouse" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="bookingId" id="bookingId" value="" />
    <input type="hidden" name="arrival" id="arrival" value="" />
    <input type="hidden" name="departure" id="departure" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Booking No.</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td class="contenttab_internal_rows">&nbsp;
         <div id="bookingNoDiv" style="display:inline;" />
        </td>
        <td class="contenttab_internal_rows"><nobr><b>Guest Name</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td class="contenttab_internal_rows">&nbsp;
         <div id="guestNameDiv" style="display:inline;" ></div>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Arrival Date</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td class="contenttab_internal_rows">&nbsp;
         <div id="arrivalDateDiv" style="display:inline;" />
        </td>
        <td class="contenttab_internal_rows"><nobr><b>Departure Date</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td class="contenttab_internal_rows">&nbsp;
         <div id="departureDateDiv" style="display:inline;" />
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Budget Head</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td class="contenttab_internal_rows">&nbsp;
         <div id="budgetHeadDiv" style="display:inline;" />
        </td>
        <td class="contenttab_internal_rows"><b>Allocate</b><?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td class="contenttab_internal_rows" >
         <input type="radio" name="allocate" value="1" onclick="toggleData(this.value);"> Yes &nbsp; 
         <input type="radio" name="allocate" value="0" onclick="toggleData(this.value);"> No
      </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Guest House</b></nobr></td>
        <td class="padding" width="1%">:</td>
        <td class="padding">
         <select size="1" class="inputbox" name="hostelId" id="hostelId" onchange="getRooms(this.value);" disabled="disabled">
          <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getHostelName('',' WHERE hostelType='.GUEST_HOUSE_TYPE);
              ?>
        </select>
    </td>
    <td class="contenttab_internal_rows"><nobr><b>Room</b></nobr></td>
    <td class="padding" width="1%">:</td>
     <td class="padding">
        <select size="1" class="selectfield" name="room" id="room" disabled="disabled">
         <option value="">Select</option>
       </select>
    </td>
   </tr>
   <tr>
        <td class="contenttab_internal_rows"><nobr><b>Reason</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td class="padding" colspan="4">
           <textarea name="reason" id="reason" cols="57" rows="2" maxlength="250" onkeyup="return ismaxlength(this);" disabled="disabled"></textarea>
        </td>
  </tr>
  <tr id="alternateArrangementTrId" style="display:none">
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Alternative<br/> Arrangement</b></nobr></td>
        <td class="padding" width="1%" valign="top">:</td>
        <td width="78%" class="contenttab_internal_rows" style="padding-left:5px;" colspan="4">
        <div id="alternateArrangement" style="width:477px;height:45px;overflow:auto;"></div>
        </td>
   </tr>
  <tr>
        <td class="contenttab_internal_rows" valign="top" style="padding-top:3px;"><nobr><b>Vacant Rooms</b></nobr></td>
        <td width="1%" class="padding" valign="top">:</td>
        <td class="contenttab_internal_rows" colspan="4">
          <div id="vacantRoomDiv" style="max-height:150px;overflow:auto;vertical-align:top;"></div>
        </td>
  </tr>
<tr><td height="5px" colspan="6"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateAddForm(this.form);return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('GuestHouseDiv');return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="6"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->
<?php
// $History: roomAllocationContents.php $
//
?>