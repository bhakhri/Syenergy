<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR VEHICLE TAX
//
// Author :Jaineesh
// Created on : (15.12.2009)
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddVehicleTax',330,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddVehicleTax','Add Vehicle Tax'); ?>
<form name="addVehicleTax" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails()">
			<option value="">Select</option>
			<?php
			  require_once(BL_PATH.'/HtmlFunctions.inc.php');
			  echo HtmlFunctions::getInstance()->getVehicleTypes();
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" onChange="getBusDetail();" >
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Regn. No. Valid Till</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('regnNoValidTill','');
		?></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Passenger Tax Valid Till</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('passengerTaxValidTill','');
		?></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Road Tax Valid Till</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('roadTaxValidTill','');
		?></td>
		</tr>
		
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Pollution Check Valid Till</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('pollutionCheckValidTill','');
		?></td>
		</tr>

		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Passing Valid Till</b></nobr></td>
			<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->datePicker('passingValidTill','');
		?></td>
		</tr>
		
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddVehicleTax');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php 
// $History: listVehicleTaxContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/05/10    Time: 11:03a
//Updated in $/Leap/Source/Templates/VehicleTax
//fixed bug nos. 0002484, 0002427
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Templates/VehicleTax
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/01/10    Time: 12:30
//Updated in $/Leap/Source/Templates/VehicleTax
//Done bug fixing.
//Bug ids---
//0002521
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/VehicleTax
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:22p
//Created in $/Leap/Source/Templates/VehicleTax
//new template for vehicle tax
//
//
?>