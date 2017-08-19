<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('VehicleDiv',800,250);blankValues();setMode('add');getVehicleTyres();return false;" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
							<tr>
								<td align="right" colspan="2">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										<tr><td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
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


<?php floatingDiv_Start('VehicleDiv','',1); ?>
<form name="vehicleForm" id="vehicleForm" action="" method="post" enctype="multipart/form-data" target="" class="border">
	<input type="hidden" name="busId" id="busId" value="" />
	<input type="hidden" name="mode" id="mode" value="" />
	<input type="hidden" name="maxFileUpload" id="maxFileUpload" value="-1" />

	<table border='0' cellspacing='0' cellpadding='0'>
		<tr>
			<td valign='' colspan='1' class='contenttab_internal_rows padding'>
				<b>Select Vehicle Type</b>
			</td>
			<td valign='' colspan='1' class='contenttab_internal_rows padding'><b>:</b>
				<select name='vehicleType' id='vehicleType' class='inputbox1' style="width:300px" onChange='getVehicleTyres();'>
                <option value="">Select</option>
				<?php
					echo $htmlFunctions->getVehicleTypes();
				?>
				</select>
			</td>
		</tr>
	</table>
	<br>
	<table border='0' cellspacing='0' cellpadding='0' style="height:400px;width:720px;">
		<tr>
			<td valign='top' colspan='1' class=''>
	<div id="dhtmlgoodies_tabView1" style="height:400px;width:700px;overflow:none;">
		<div class="dhtmlgoodies_aTab" style="height:400px;width:700px;overflow:none;">
			<div style="height:380px;width:700px; overflow:none;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Vehicle Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding" colspan="5" ><nobr>&nbsp;<b>:</b>
									<input type="text" id="busName" name="busName" class="inputbox" maxlength="30" /></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Registration No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="busNo1" name="busNo1" maxlength="2" class="inputbox1" style="width:20px" /></nobr></td>
									<td class="padding"><nobr><b>-</b>
									<input type="text" id="busNo2" name="busNo2" maxlength="2" class="inputbox1" style="width:20px"/></nobr></td>
									<td class="padding"><nobr><b>-</b>
									<input type="text" id="busNo3" name="busNo3" maxlength="2" class="inputbox1" style="width:20px"/></nobr></td>
									<td class="padding"><nobr><b>-</b>
									<input type="text" id="busNo4" name="busNo4" maxlength="4" class="inputbox1" style="width:40px"/></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Model No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
									<input type="text" id="busModel" name="busModel" class="inputbox" maxlength="15" /></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Purchase Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding" colspan="5" ><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('purchaseDate',date('Y-m-d'));
									?></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Seating Capacity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
									<input type="text" id="seatingCapacity" name="seatingCapacity" class="inputbox" maxlength="4" /></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Fuel Capacity<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
									<input type="text" id="fuelCapacity" name="fuelCapacity" class="inputbox" maxlength="6"/></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Manufacturing Year<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
									<select size="1" class="selectfield" name="manYear" id="manYear" style="width:100px;">
									<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->getAdmissionYear(date('Y')); //dont be confused with function name
										?>
									</select></nobr></td>
								</tr>
								<tr id="vehicleRegnNoValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Regn. No. Valid Till</b></nobr></td>
									<td width="79%" class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('regnNoValidTill',date('Y-m-d'));
									?></nobr></td>
								</tr>
								<tr id="divRegnNoValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Regn. No. Valid Till </b></nobr></td>
									<td width="79%" class="contenttab_internal_rows" nowrap colspan="5">&nbsp;&nbsp;&nbsp;<b>:&nbsp;&nbsp;</b><span id="regnNovalid"></span></td>
								</tr>
								<tr id="vehiclePassengerTaxValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Passenger Tax Valid Till</b></nobr></td>
									<td width="79%" class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('passengerTaxValidTill',date('Y-m-d'));
									?></nobr></td>
								</tr>
								<tr id="divPassengerTaxValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Passenger Tax Valid Till</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows" nowrap colspan="5">&nbsp;&nbsp;&nbsp;<b>:&nbsp;&nbsp;</b><span id="passengerTaxvalid"></span></td>
								</tr>
								<tr id="vehiclePassingValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Passing Valid Till</b></nobr></td>
									<td width="79%" class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('passingValidTill',date('Y-m-d'));
									?></nobr></td>
								</tr>
								<tr id="divPassingValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Passing Valid Till</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows" nowrap colspan="5">&nbsp;&nbsp;&nbsp;<b>:&nbsp;&nbsp;</b><span id="passingValid"></span></td>
								</tr>
                                <tr id="divVehicleCategory">
                                    <td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Vehicle Category<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                    <td width="79%" class="padding" colspan="5"><nobr>&nbsp;<b>:</b>
                                        <select name='vehicleCategory' id='vehicleCategory' onChange="javascript:if(this.value=='') {document.getElementById('divVehicleCategory1').style.display=''; } else { document.getElementById('divVehicleCategory1').style.display = '';} return false;" class='inputbox1' style="width:100px">
                                           <option value="">Select</option> 
                                           <option value="1">Non Carrage</option> 
                                           <option value="2">Carrage</option>
                                        </select>
                                    </nobr></td>
                                 </tr>
                                 <tr id="divVehicleCategory1" style1="display:none">
                                    <td class="contenttab_internal_rows" nowrap colspan="5">
                                    <span style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;color:red;'> 
                                        -Carriage(Will be used to transport Students on payment Basis, like Bus)<br>
                                        -Non Carriage (Will be used to transport staff free of cost)
                                     </span>   
                                    </td>
                                 </tr>   
							</table>
						</td>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Engine No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="engineNo" name="engineNo" class="inputbox" maxlength="100" /></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Chassis No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="chasisNo" name="chasisNo" class="inputbox" maxlength="100" /></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Body Maker</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="bodyMaker" name="bodyMaker" class="inputbox" maxlength="200" /></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Chassis Cost</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="chasisCost" name="chasisCost" class="inputbox" maxlength="11"/></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Chassis Purchase Date</b></nobr></td>
									<td class="padding"><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('chasisPurchaseDate',date('Y-m-d'));
									?>
									</td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Body Cost</b></nobr></td>
									<td class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="bodyCost" name="bodyCost" class="inputbox" maxlength="11" /></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Put On Road</b></nobr></td>
									<td class="padding"><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('putOnRoadDate',date('Y-m-d'));
									?>
									</td>
								</tr>
								<tr id="vehicleRoadTaxValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Road Tax Valid Till</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('roadTaxValidTill',date('Y-m-d'));
									?></nobr></td>
								</tr>
								<tr id="divRoadTaxValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Road Tax Valid Till</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows" nowrap colspan="5">&nbsp;&nbsp;&nbsp;<b>:&nbsp;&nbsp;</b><span id="roadTaxValid"></span></td>
								</tr>
								<tr id="vehiclePollutionTaxValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Pollution Tax Valid Till</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<?php
										echo HtmlFunctions::getInstance()->datePicker('pollutionTaxValidTill',date('Y-m-d'));
									?></nobr></td>
								</tr>
								<tr id="divPollutionTaxValidTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Pollution Tax Valid Till</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows" nowrap colspan="5">&nbsp;&nbsp;&nbsp;<b>:&nbsp;&nbsp;</b><span id="pollutionTaxValid"></span></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Vehicle Photo</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="file" id="busPhoto" name="busPhoto" class="inputbox"/></nobr>
									</td>
								</tr>
								<tr>
									<td valign='top' colspan='2' class=''>
                                    <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                     <td>
                                      <div id="busPhotoDiv" style="display:inline"></div>
                                     </td>
                                     <td>&nbsp;</td>
                                     <td valign="top">
                                      <div id="busPhotoDeleteDiv" style="display:inline"></div>
                                     </td>
                                     </tr>
                                    </table>

                                    </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td height="5px" colspan="2"></td></tr>
					<tr>
						<td height="5px" colspan="2">
					 	<iframe id="uploadTargetFrame" name="uploadTargetFrame" src="" style="width:0px;height:0px;border:0px ;">
						</iframe>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="dhtmlgoodies_aTab" style="height:400px;width:700px;overflow:none;">
			<div style="height:380px;width:700px; overflow:none;">
				<!--<input type="hidden" name="busId" id="busId" value="" />-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr id="addInsuranceDate" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Ins. Paid On</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<?php
										echo $htmlFunctions->datePicker('insuranceDate',date('Y-m-d'));
									?>
									</nobr>
									</td>

									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Ins. Due Date</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<?php
										echo $htmlFunctions->datePicker('insuranceDueDate',date('Y-m-d',mktime(0, 0, 0, date('m'), date('d'), date('Y')+1)));
									?>
									</nobr>
									</td>
								</tr>
								<tr id="editInsuranceDate" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Ins. Paid On</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>:</b>&nbsp;&nbsp;<span id="divInsuranceDate"></span></td>

									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Ins. Due Date</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>:</b>&nbsp;&nbsp;<span id="divInsuranceDueDate"></span></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Ins. Company</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<select name='insuringCompany' class='inputbox1' style="width:280px">
									<?php
										echo $htmlFunctions->getInsuringCompany();
									?>
									</select></td>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Policy No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="policyNo" name="policyNo" class="inputbox" maxlength="150" tabIndex="5"/></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Value Insured</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="valueInsured" name="valueInsured" class="inputbox" maxlength="11" tabIndex="1" /></nobr></td>

									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Ins. Premium</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="insurancePremium" name="insurancePremium" class="inputbox" maxlength="11" tabIndex="6" /></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>NCB</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="ncb" name="ncb" class="inputbox" maxlength="11" tabIndex="2"/></nobr></td>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Payment Mode</b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<select name='paymentMode' class='inputbox'>
									<?php
										echo $htmlFunctions->getFeePaymentMode();
									?></nobr></select></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Branch Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="branchName" name="branchName" class="inputbox" maxlength="50" tabIndex="3"/></nobr></td>

									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Agent Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="agentName" name="agentName" class="inputbox" maxlength="50" tabIndex="7"/></nobr></td>

								</tr>
								<tr>
									<td class="contenttab_internal_rows" valign='top'><nobr>&nbsp;&nbsp;<b>Payment Desc.</b></nobr></td>
									<td class="padding"><nobr>&nbsp;<b>:</b>
									<textarea name='paymentDescription' class="inputbox" style="vertical-align:top;" onkeyup="return ismaxlength(this)" maxlength="200" tabIndex="4"></textarea>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="5px" colspan="2"></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="dhtmlgoodies_aTab" style="height:280px;width:700px;overflow:auto;">
			<div style="height:280px;width:700px;overflow:auto;">
				<!--<input type="hidden" name="busId" id="busId" value="" />-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr id="modelNo" style="display:none">
									<td width="20%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Tyres' Model No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="tyreModelNo" name="tyreModelNo" class="inputbox" maxlength="15" /></nobr></td>
									</td>
								</tr>
								<!--<tr id="tyreDivModelNo" style="display:none">
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Tyres' Model No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td class="padding"><nobr>&nbsp;<b>:</b>
									<td width="88%"><div id="divModelNo" style="overflow:auto"></div></td>
								</tr>-->
								<tr id="manufacturingCompany" style="display:none">
									<td width="20%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Tyres' Mfg. Company<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="tyreManufacturingCompany" name="tyreManufacturingCompany" class="inputbox" maxlength="15" /></nobr></td>
									</td>
								</tr>
								<!--<tr id="tyreDivManufacturingCompany" style="display:none">
									<td width="20%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Tyres' Mfg. Company<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td width="79%" class="padding"><nobr>&nbsp;<b>:</b>
									<td><div id="divMaufacturingCompany"></div></td>
								</tr>-->
								<tr id="detailTyres" style="display:none">
									<td colspan="3"><nobr>&nbsp;&nbsp;<b>Details of Tyres<?php echo REQUIRED_FIELD; ?></b></nobr>
									<div id='tyreDetailDiv'></div>
									</td>
								</tr>
								<tr id="tyresInformation" style="display:none">
									<td colspan="3"><nobr>&nbsp;&nbsp;<b>Information of Tyres :</b></nobr>
									<div id='tyreInformationDiv'></div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="5px" colspan="2"></td>
					</tr>
				</table>
			</div>
		</div>

	<div class="dhtmlgoodies_aTab" style="height:400px;width:700px;overflow:none;">
			<div style="height:380px;width:700px; overflow:none;">
				<!-- <input type="text" name="busId" id="busId" value="" /> -->
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Battery No.<?php echo REQUIRED_FIELD; ?><nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></nobr></td>
									<td width="79%" class="padding">
									<input type="text" id="batteryNo" name="batteryNo" class="inputbox" maxlength="50" /></nobr></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Battery Make<?php echo REQUIRED_FIELD; ?><nobr>&nbsp;:</b></nobr></td>
									<td width="79%" class="padding">
									<input type="text" id="batteryMake" name="batteryMake" class="inputbox" maxlength="50"/></nobr></td>
								</tr>
								<tr id="warrantyTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Warranty Till<nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></nobr></td>
									<td width="79%" class="padding">
									<?php
										echo $htmlFunctions->datePicker('warrantyDate',date('Y-m-d'));
									?>
									</nobr>
									</td>

								</tr>
								<tr id="batteryWarrantyTill" style="display:none">
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Warranty Till<nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</b></nobr></td>
									<td width="79%" class="unboldpadding"><div id="batteryWarrantyDate"></div></td>
								</tr>

							</table>
						</td>
					</tr>
					<tr>
						<td height="5px" colspan="2"></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="dhtmlgoodies_aTab" style="height:280px;width:700px;overflow:auto;">
			<div style="height:280px;width:700px; overflow:auto;">
				<!-- <input type="hidden" name="busId" id="busId" value="" />-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>No. of Free Services<?php echo REQUIRED_FIELD; ?></b></nobr></td>
									<td class="padding"><nobr>&nbsp;<b>:</b>
									<input type="text" id="freeService" name="freeService" class="inputbox" maxlength="2" /></nobr></td>

									<td width="40%">
									<input type="image" name="imageField" id="showButton" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getServiceInfo(document.getElementById('freeService').value);return false;" />
									</td>
								</tr>
								<tr>
									<td colspan="3"><nobr>&nbsp;&nbsp;<b>Details of Services :<?php echo REQUIRED_FIELD; ?></b></nobr>
									<div id='serviceDetailDiv' style="overflow:auto"></div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="5px" colspan="2"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
		</td>
	  </tr>
	<tr><td height="5px" colspan="2"></td></tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="2">
		<input type='hidden' name='uniqueId' value='' />
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="doSubmitAction();return false;" />&nbsp;
        <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('VehicleDiv');return false;" /></td>
	</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
   <script type="text/javascript">
	initTabs('dhtmlgoodies_tabView1',Array('Vehicle Info.','Insurance Info.',"Tyres Info.",'Battery Info.','Service Info.'),0,713,315,Array(false,false,false,false,false));
   </script>

<?php
// $History: listVehicleContents.php $
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 2/10/10    Time: 3:24p
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bug no.0002800
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 1/25/10    Time: 11:14a
//Updated in $/Leap/Source/Templates/Vehicle
//Show latest vehicle insurance detail non-editable
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/21/10    Time: 5:25p
//Updated in $/Leap/Source/Templates/Vehicle
//solve problem to upload max size photo
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/13/10    Time: 11:00a
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bug in fleet management
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 1/12/10    Time: 1:32p
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bug in Fleet management
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bug in fleet management
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bug on fleet management
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 12/29/09   Time: 10:10a
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bugs
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/28/09   Time: 12:24p
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bug nos. 0002378, 0002377, 0002376, 0002374, 0002373
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/26/09   Time: 10:15a
//Updated in $/Leap/Source/Templates/Vehicle
//bug fixed nos. 0002370, 0002369
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/Vehicle
//fixed bug during self testing
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/21/09   Time: 12:20p
//Updated in $/Leap/Source/Templates/Vehicle
//Put validation message on engine No. chassis no. & correct the tab
//order of vehicle insurance
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Templates/Vehicle
//put DL image in transport staff and changes in modules
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/10/09   Time: 5:54p
//Updated in $/Leap/Source/Templates/Vehicle
//files released for jaineesh to continue FLEET MANAGEMENT
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/12/09    Time: 10:14
//Updated in $/Leap/Source/Templates/Vehicle
//checked in files
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/07/09   Time: 12:43p
//Created in $/Leap/Source/Templates/Vehicle
//initial file check-in
//

?>