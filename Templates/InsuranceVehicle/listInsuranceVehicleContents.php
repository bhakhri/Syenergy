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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick= "displayWindow('AddInsuranceVehicle',330,250);blankValues();return false;"/>&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
							<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												
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

<?php floatingDiv_Start('AddInsuranceVehicle','Add Vehicle Insurance'); ?>
<form name="addInsuranceVehicle" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
		  <td valign="top">
		    <table border="0" cellpadding="0" cellspacing="0">
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
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Registration No. <?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" >
					<option value="">Select</option>
					<?php
						//require_once(BL_PATH.'/HtmlFunctions.inc.php');
						//echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Date</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('insuranceDate',date('Y-m-d'));?>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Due Date</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('insuranceDueDate',date('Y-m-d',mktime(0, 0, 0, date('m'), date('d'), date('Y')+1)));?>
					</td>
				</tr>
				<tr>
					<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Company <?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="insuringCompany" id="insuringCompany" >
					<option value="">Select</option>
					<?php
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->getInsuringCompany();?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Policy No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="policyNo" name="policyNo" class="inputbox" maxlength="150" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Value Insured<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="valueInsured" name="valueInsured" class="inputbox" maxlength="8" ></td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Premium<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="insurancePremium" name="insurancePremium" class="inputbox" maxlength="8" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows" ><nobr><b>&nbsp;&nbsp;&nbsp;NCB(%)</b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="ncb" name="ncb" class="inputbox" maxlength="8" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Payment Mode<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="paymentMode" id="paymentMode" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getFeePaymentMode();
						?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Branch Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="branchName" name="branchName" class="inputbox" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Agent Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="agentName" name="agentName" class="inputbox" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Payment Description</b></nobr></td>
					<td class="padding">:&nbsp;<textarea id="paymentDescription" name="paymentDescription" cols="22" rows="3" class="inputbox" style="vertical-align:top" maxlength="150" onkeyup="return ismaxlength(this)"></textarea></td>
				</tr>
			</table>
		</td>
		</tr>
		
		<tr>
			<td height="5px"></td></tr>
		<tr>
			<td align="center" style="padding-right:15px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddInsuranceVehicle');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditInsuranceVehicle','Edit Vehicle Insurance'); ?>
<form name="editInsuranceVehicle" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="insuranceId" id="insuranceId" value="" />
		<tr>
		  <td valign="top">
		    <table border="0" cellpadding="0" cellspacing="0">
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
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busNo" id="busNo" disabled="disabled" >
					<option value="">Select</option>
					<?php
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->getBusData('',' WHERE isActive=1');?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Date</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('insuranceDate1',date('Y-m-d'));?>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Due Date</b></nobr></td>
					<td class="padding">:&nbsp;<?php require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->datePicker('insuranceDueDate1',date('Y-m-d',mktime(0, 0, 0, date('m'), date('d'), date('Y')+1)));?>
					</td>
				</tr>
				<tr>
					<td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Company <?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="insuringCompany" id="insuringCompany" >
					<option value="">Select</option>
					<?php
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->getInsuringCompany();?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Policy No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="policyNo" name="policyNo" class="inputbox" maxlength="150" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Value Insured<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="valueInsured" name="valueInsured" class="inputbox" maxlength="8" ></td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Insurance Premium<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="insurancePremium" name="insurancePremium" class="inputbox" maxlength="8" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows" ><nobr><b>&nbsp;&nbsp;&nbsp;NCB(%)</b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="ncb" name="ncb" class="inputbox" maxlength="8" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Payment Mode<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<select size="1" class="selectfield" name="paymentMode" id="paymentMode" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getFeePaymentMode();
						?></select>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Branch Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="branchName" name="branchName" class="inputbox" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Agent Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
					<td class="padding">:&nbsp;<input type="text" id="agentName" name="agentName" class="inputbox" ></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Payment Description</b></nobr></td>
					<td class="padding">:&nbsp;<textarea id="paymentDescription" name="paymentDescription" cols="22" rows="3" class="inputbox" style="vertical-align:top" maxlength="150" onkeyup="return ismaxlength(this)"></textarea></td>
				</tr>
			</table>
		</td>
		</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:18px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditInsuranceVehicle'); return false;" />
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
// $History: listInsuranceVehicleContents.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Templates/InsuranceVehicle
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:51p
//Updated in $/Leap/Source/Templates/InsuranceVehicle
//bug fixed for fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/InsuranceVehicle
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/InsuranceVehicle
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:17p
//Created in $/Leap/Source/Templates/InsuranceVehicle
//new template for vehicle insurance
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:29p
//Created in $/Leap/Source/Templates/VehicleInsurance
//new vehicle for insurance
//
//
?>