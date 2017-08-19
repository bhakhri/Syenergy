<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR PERIOD SLOT DETAIL
//
//
// Author :Jaineesh
// Created on : (15.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
require_once(TEMPLATES_PATH . "/breadCrumb.php");   
?>

	

    <tr>
        <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('PeriodSlotActionDiv',330,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" colspan="4">
                <div id="PeriodSlotResultDiv">
                 </div></tr>
				 <tr>
					<td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
				 </tr>
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
<?php floatingDiv_Start('PeriodSlotActionDiv',''); ?>
<form name="PeriodSlotDetail" action="" method="post">  
<input type="hidden" name="periodSlotId" id="periodSlotId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr> 
      <td width="9%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Slot Name<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td width="91%" class="padding">:&nbsp;
      <input type="text" id="slotName" name="slotName"  style="width:170px" class="inputbox" maxlength="64"/>
     </td>
   </tr>
    <tr> 
      <td width="9%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Abbr.<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td width="91%" class="padding">:&nbsp;
      <input type="text" id="slotAbbr" name="slotAbbr"  style="width:170px" class="inputbox" maxlength="15"/>
     </td>
   </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;Active</b></nobr></td>
        <td  class="contenttab_internal_rows1" align="left"><b>&nbsp;:</b>
         <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;
        </td>
    </tr>
   
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="emptySlotId();javascript:hiddenFloatingDiv('PeriodSlotActionDiv');if(flag==true){getPeriodSlotData();flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<?php
// $History: listPeriodSlotContents.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/05/09    Time: 11:57a
//Updated in $/LeapCC/Templates/PeriodSlot
//fixed alignment issue & name of heading
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Templates/PeriodSlot
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/30/09    Time: 7:02p
//Updated in $/LeapCC/Templates/PeriodSlot
//fixed bug nos.0000737, 0000736,0000734,0000735, 0000585, 0000584,
//0000583
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Templates/PeriodSlot
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/22/09    Time: 3:21p
//Updated in $/LeapCC/Templates/PeriodSlot
//fixed bug no. 0000148
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:34p
//Created in $/LeapCC/Templates/PeriodSlot
//get the template of period slot
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:17p
//Created in $/Leap/Source/Templates/PeriodSlot
//get the template of period slot
//

?>