<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR VEHICLE TYPE LISTING 
//
// Author :Jaineesh
// Created on : (24.11.2009)
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
				<tr>
					<td valign="top">Setup&nbsp;&raquo;&nbsp;Fleet Management&nbsp;&raquo;&nbsp;Extended Vehicle Service </td>
					<td valign="top" align="right">
					<form action="" method="" name="searchForm">
						<input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
						&nbsp;
						<input type="image" name="submit" align="absbottom" src="<?php echo IMG_HTTP_PATH;?>/search.gif" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" />
					</form>
					</td>
				</tr>
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
											<td class="content_title">Extended Vehicle Service : </td>
											<td align="right" class="content_title" ><a href="listVehicleServiceRepair.php" class='redLink' width="80%">Vehicle Service cum Repair</a></td>
											<td class="content_title" title="Add" width="5%"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
											align="right" onClick="displayWindow('AddExtendVehicleService',330,250);blankValues();return false;" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="contenttab_row" valign="top" ><div id="results"> 
								   </div>
								   <!--<tr><td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" /></td></tr>-->
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

<?php floatingDiv_Start('AddExtendVehicleService','Add Extended Vehicle Service'); ?>
<form name="addExtendVehicleService" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="vehicleType" id="vehicleType" onChange="getVehicleDetails()">
			<option value="">Select</option>
			<?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getVehicleTypes();
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" onchange="getVehicleFreeService();">
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3" class="padding"><div id ="vehicleService"></div>
		</tr>
		<tr>
			<td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;&nbsp;<strong>Extended Vehicle Services</strong></nobr></td>
			<td width="65%" class="padding">:
				<input type="text" id="extendedServices" name="extendedServices" class="inputbox" maxlength="2" />
			</td>
			<td><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/next1.gif" onClick="return checkList(this.form,'Add');return false;" /></td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td colspan="3" align="center"><div id ="showVehicleService" style="overflow:auto;height:120px;width:450px;"></div></td>
		</tr>
		<tr id="extendedVehicleService" style="display:none">
				<td align="center" style="padding-right:18px" colspan="4">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddExtendVehicleService'); return false;" />
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
<?php floatingDiv_Start('EditVehicleAccident','Edit Vehicle Accident'); ?>
<form name="editVehicleAccident" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="accidentId" id="accidentId" value="" />
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getEditVehicleDetails()">
			<option value="">Select</option>
			<?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getVehicleTypes();
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" >
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Transport Staff <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="transportStaff" id="transportStaff" >
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getTransportStaffData();
				?></select>
			</td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Bus Route <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busRoute" id="busRoute" >
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusRouteName();
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePickerWithTime('accidentDate1',date('Y-m-d'));?>
			</td>
		</tr>
		<tr>
			<td width="35%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;&nbsp;&nbsp;<strong>Remarks</strong></nobr></td>
			<td width="65%" class="padding">:
			<textarea id="remarks" name="remarks" cols="22" rows="3" class="inputbox" style="vertical-align:top" maxlength="150" onkeyup="return ismaxlength(this)"></textarea>
		</td>
		</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:18px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditVehicleAccident'); return false;" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
    
<?php 
// $History: listExtendVehicleServiceContents.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:54p
//Created in $/Leap/Source/Templates/ExtendVehicleService
//new template file for extend vehicle service
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Templates/VehicleAccident
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Templates/VehicleAccident
//fixed bug in fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/VehicleAccident
//fixed bug on fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/VehicleAccident
//fixed bug during self testing
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/18/09   Time: 12:34p
//Updated in $/Leap/Source/Templates/VehicleAccident
//put new fields accident date as datetime, add remarks field
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 1:04p
//Created in $/Leap/Source/Templates/VehicleAccident
//new template for VehicleAccident
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:29p
//Created in $/Leap/Source/Templates/VehicleInsurance
//new vehicle for insurance
//
//
?>