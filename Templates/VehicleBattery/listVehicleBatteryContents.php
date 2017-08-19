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
 require_once(BL_PATH.'/helpMessage.inc.php');    
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddVehicleBattery',330,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddVehicleBattery','Add/Replace Vehicle Battery'); ?>
<form name="addVehicleBattery" action="" method="post">
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
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">&nbsp;<b>:</b>&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" >
				<option value="">Select</option>
				<?php
					//require_once(BL_PATH.'/HtmlFunctions.inc.php');
					//echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Battery No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="batteryNo" name="batteryNo" class="inputbox" maxlength="40" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Battery Make<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="batteryMake" name="batteryMake" class="inputbox" maxlength="40" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Warranty Till</b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('warrantyDate',date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')+1)));
			?>
			</nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Meter Reading<?php echo REQUIRED_FIELD; 
            require_once(BL_PATH.'/HtmlFunctions.inc.php');   
            echo HtmlFunctions::getInstance()->getHelpLink('Vehicle Meter Reading',HELP_VEHICLE_METER_READING);?> </b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="meterReading" name="meterReading" class="inputbox" maxlength="9" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Cost<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="replacementCost" name="replacementCost" class="inputbox" maxlength="8" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Replacement Date</b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('replacementDate',date('Y-m-d'));
			?>
			</nobr></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddVehicleBattery');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<!--Start Edit Div-->
<?php floatingDiv_Start('EditVehicleBattery','Edit Vehicle Battery'); ?>
<form name="editVehicleBattery" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="batteryId" id="batteryId" value="" />  
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Select Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td class="padding">:&nbsp;<select size="1" class="inputbox1" name="vehicleType" id="vehicleType" onChange="getVehicleDetails()" disabled="disabled">
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
			<td class="padding">&nbsp;<b>:</b>&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" disabled="disabled" >
				<option value="">Select</option>
				<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');
				?></select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Battery No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="batteryNo" name="batteryNo" class="inputbox" maxlength="40" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Battery Make<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="batteryMake" name="batteryMake" class="inputbox" maxlength="40" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Warranty Till</b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('warrantyDate1',date('Y-m-d'));
			?>
			</nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Meter Reading<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="meterReading" name="meterReading" class="inputbox" maxlength="9" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Cost<?php echo REQUIRED_FIELD; ?></b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<input type="text" id="replacementCost" name="replacementCost" class="inputbox" maxlength="8" /></nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Replacement Date</b></nobr></td>
			<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
			<?php
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('replacementDate1',date('Y-m-d'));
			?>
			</nobr></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>

		<tr>
		<td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" tabIndex="9" />
		<input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditVehicleBattery');return false;" tabIndex="10"/>
        </td>
</tr>
</table>
</form>
 <?php floatingDiv_End(); 
  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<?php 
// $History: listVehicleBatteryContents.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 2/04/10    Time: 6:27p
//Updated in $/Leap/Source/Templates/VehicleBattery
//fixed issues
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Templates/VehicleBattery
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:51p
//Updated in $/Leap/Source/Templates/VehicleBattery
//bug fixed for fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/VehicleBattery
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/VehicleBattery
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 1:22p
//Created in $/Leap/Source/Templates/VehicleBattery
//new template for vehicle battery
//
//
?>