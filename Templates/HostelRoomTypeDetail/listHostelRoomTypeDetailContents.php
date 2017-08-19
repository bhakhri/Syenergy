<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TRAINING
//
//
// Author :Jaineesh
// Created on : (28.02.2009 )
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
									<?php 
                                       $specialSearchCondition="getHostelRoomTypeDetailData();";
                                      require_once(TEMPLATES_PATH . "/searchForm.php"); 
                                    ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('HostelRoomTypeDetailDiv',340,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="HostelRoomTypeDetailResultDiv"></div></td>
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
<?php floatingDiv_Start('HostelRoomTypeDetailDiv',''); ?>
<form name="HostelRoomTypeDetail" action="" method="post">  
<input type="hidden" name="roomTypeInfoId" id="roomTypeInfoId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Hostel Name<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
      <td width="65%" class="padding">:&nbsp;<select size="1" class="selectfield" name="hostelName" id="hostelName" tabindex="1">
	  <option value="">Select</option>
      <?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getHostelName();
	 ?>
	 </select>
     </td>
	</tr>
	<tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Hostel Room Type<?php echo REQUIRED_FIELD; ?></strong></nobr></td>
      <td class="padding">:&nbsp;<select size="1" class="selectfield" name="roomType" id="roomType" tabindex="2">
		<option value="">Select</option>
		<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getHostelRoomTypeData();
		?></select>
	 </td>
	</tr>
	<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;Capacity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="capacity" name="capacity" class="inputbox" tabindex="3" maxlength="4"></td>
	</tr>
	<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;No. of Beds<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="noBeds" name="noBeds" class="inputbox" tabindex="4" maxlength="2"></td>
	</tr>
	<tr>
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Attached Bathroom</strong></nobr></td>
      <td class="padding">:&nbsp;<select size="1" class="selectfield1" name="attachBathroom" id="attachBathroom" tabindex="5">
		<option value="1" selected="selected">Yes</option>
		<option value="0">No</option>
		
	 </td>
	</tr>
	<tr>
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Air Conditioned</strong></nobr></td>
      <td class="padding">:&nbsp;<select size="1" class="selectfield1" name="airConditioned" id="airConditioned" tabindex="6">
		<option value="1" selected="selected">Yes</option>
		<option value="0">No</option>
		
	 </td>
	</tr>
	<tr>
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Internet Facility</strong></nobr></td>
      <td class="padding">:&nbsp;<select size="1" class="selectfield1" name="internetFacility" id="internetFacility" tabindex="7">
		<option value="1" selected="selected">Yes</option>
		<option value="0">No</option>
		
	 </td>
	</tr>
	<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;No. of Fans </b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="noOfFans" name="noOfFans" class="inputbox" tabindex="8" maxlength="2"></td>
	</tr>
	<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;No. of Lights</b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="noOfLights" name="noOfLights" class="inputbox" tabindex="9" maxlength="2"></td>
	</tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" tabindex="11" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('HostelRoomTypeDetailDiv');if(flag==true){getHostelRoomTypeDetailData();flag=false;}return false;" tabindex="12" />
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
// $History: listHostelRoomTypeDetailContents.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Templates/HostelRoomTypeDetail
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/11/09    Time: 6:32p
//Updated in $/LeapCC/Templates/HostelRoomTypeDetail
//fixed issue nos.0000093,0000094,0000096
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/29/09    Time: 12:37p
//Updated in $/LeapCC/Templates/HostelRoomTypeDetail
//make the bread crumb correct
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/09/09    Time: 10:26a
//Updated in $/LeapCC/Templates/HostelRoomTypeDetail
//make asterick sign on left side
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/12/09    Time: 5:18p
//Updated in $/LeapCC/Templates/HostelRoomTypeDetail
//fixed bugs Issues Build cc0001.doc
//(Nos.991,992,993,994,995,996,997,998,999,1000)
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
