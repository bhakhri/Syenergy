<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR VEHICLE TYPE LISTING 
//
// Author :Jaineesh
// Created on : (24.11.2009)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick= "displayWindow('AddVehicleAccident',330,250);blankValues();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
							<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										
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

<?php floatingDiv_Start('AddVehicleAccident','Add a Vehicle Accident Report'); ?>
<form name="addVehicleAccident" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
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
				echo HtmlFunctions::getInstance()->datePickerWithTime('accidentDate',date('Y-m-d H:i'));?>
			</td>
		</tr>
		<tr>
			<td width="35%" class="contenttab_internal_rows" valign="top"><nobr>&nbsp;&nbsp;&nbsp;<strong>Remarks</strong>
           <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                        echo HtmlFunctions::getInstance()->getHelpLink('Remarks',HELP_VEHICLE_ACCIDENT_REMARKS); ?>
                        </nobr></td>
			<td width="65%" class="padding">:
			<textarea id="remarks" name="remarks" cols="22" rows="3" class="inputbox" style="vertical-align:top" maxlength="150" onkeyup="return ismaxlength(this)"></textarea>
		</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddVehicleAccident');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
			<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Vehicle Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
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
 <!--End: Div To Edit The Table-->      
<?php floatingDiv_End();
   //Help Module Div starts
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
// $History: listVehicleAccidentContents.php $
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