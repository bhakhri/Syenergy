<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CLEANING ROOM
//
//
// Author :Jaineesh
// Created on : (30.04.2009)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('CleaningRoomDetailDiv',470,390);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="CleaningRoomDetailResultDiv"></div></td>
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
<?php floatingDiv_Start('CleaningRoomDetailDiv',''); ?>
<form name="CleaningRoomDetail" action="" method="post">  
<input type="hidden" name="cleanId" id="cleanId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Safaiwala<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
      <td width="65%" class="padding">:&nbsp;<select size="1" class="selectfield" name="safaiwala" id="safaiwala" >
	  <option value="">Select</option>
       <?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getTempEmployeeName();
	  ?>
	  </select>
      </td>
	</tr>
	<tr>
		<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Date</strong></nobr></td>
		<td class="padding">:&nbsp;<?php
		require_once(BL_PATH.'/HtmlFunctions.inc.php');
		echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
		?>
		</td>
	</tr>
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Hostel Name<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
      <td width="65%" class="padding">:&nbsp;<select size="1" class="selectfield" name="hostelName" id="hostelName" >
	  <option value="">Select</option>
      <?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getHostelName();
	 ?>
	 </select>
     </td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;No. of Toilets</b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="toiletsNo" name="toiletsNo" class="inputbox" maxlength="3" ></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;No. of Rooms </b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="roomsNo" name="roomsNo" class="inputbox" maxlength="3"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;No. of Rooms with attached bathroom</b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="roomsAttachedBath" name="roomsAttachedBath" class="inputbox" maxlength="3"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Dry mopping in sq. meters</b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="dryMopping" name="dryMopping" class="inputbox" maxlength="4"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Wet mopping in sq. meters</b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="wetMopping" name="wetMopping" class="inputbox" maxlength="4"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Area of road cleaned in sq. meter </b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="areaRoad" name="areaRoad" class="inputbox" maxlength="4"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;Lifting and disposal of no. of garbage bins</b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="garbageBins" name="garbageBins" class="inputbox" maxlength="3" ></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><nobr><b>&nbsp;No. of work hours <?php echo REQUIRED_FIELD; ?></b></nobr></td>
		<td class="padding">:&nbsp;<input type="text" id="noOfhrs" name="noOfhrs" class="inputbox" maxlength="2"></td>
	</tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;"  />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('CleaningRoomDetailDiv');if(flag==true){getCleaningRoomDetail();flag=false;}return false;" />
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
// $History: listCleaningRoomContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/31/09    Time: 6:11p
//Updated in $/LeapCC/Templates/CleaningRoom
//Gurkeerat: resolved issue 1368
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Templates/CleaningRoom
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:34p
//Updated in $/LeapCC/Templates/CleaningRoom
//bugs fixed nos. 1370 to 1380 of Issues [08-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 3:32p
//Updated in $/LeapCC/Templates/CleaningRoom
//remove mendatory fields 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:29p
//Created in $/LeapCC/Templates/CleaningRoom
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Templates/HostelRoomTypeDetail
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:56a
//Created in $/LeapCC/Templates/HostelRoomTypeDetail
//new template file for hostel room type detail
//
?>