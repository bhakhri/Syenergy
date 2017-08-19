<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VehicleReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleManager.inc.php");
    $vehicleManager = VehicleManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlFunctions = HtmlFunctions::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$filter = '';

	$vehicleId = $REQUEST_DATA['vehicleNo'];
	/*
	echo($vehicleNo);
	die;
	*/

	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	  $searchBox = add_slashes(trim($REQUEST_DATA['searchbox']));
      $filter = " AND b.busNo LIKE '$searchBox%' OR vt.vehicleType LIKE '$searchBox%'";
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';

     $orderBy = " ORDER BY $sortField $sortOrderBy";

    ////////////

    /*$totalArray = $vehicleManager->getCountVehicleList($filter);
    $busRecordArray = $vehicleManager->getVehicleList($filter,$limit,$orderBy);
    $cnt = count($busRecordArray);

	$mainArray = array();*/

	$vehicleDetailsArray					=	$vehicleManager->getVehicleDetails($vehicleId);
	$vehicleDetailsArray[0]['purchaseDate'] = UtilityManager::formatDate($vehicleDetailsArray[0]['purchaseDate']);
	$vehicleDetailsArray[0]['chasisPurchaseDate'] = UtilityManager::formatDate($vehicleDetailsArray[0]['chasisPurchaseDate']);
	$vehicleDetailsArray[0]['putOnRoad'] = UtilityManager::formatDate($vehicleDetailsArray[0]['putOnRoad']);

		?>
	<table border='0' cellspacing='0' cellpadding='0' style="height:400px;width:700px;">
		<tr>
			<td valign='top' colspan='1' class=''>
	<div id="dhtmlgoodies_tabView1" style="height:240px;width:980px;overflow:none;">
	<!-- vehicle info -->
		<div class="dhtmlgoodies_aTab" style="height:240px;width:980px;overflow:none;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Model No.</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['modelNumber']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Purchase Date</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['purchaseDate']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Manufacturing Year</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['yearOfManufacturing']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Seating Capacity</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['seatingCapacity']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Fuel Capacity</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['fuelCapacity']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Engine No.</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['engineNo']);?></td>
								</tr>

							</table>
						</td>
						<td>
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Chassis No.</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['chasisNo']); ?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Body Maker</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['bodyMaker']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Chassis Cost</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['chasisCost']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Chassis Purchase Date</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['chasisPurchaseDate']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Body Cost</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['bodyCost']);?></td>
								</tr>
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Put on Road</b></nobr></td>
									<td width="79%" class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo strip_slashes($vehicleDetailsArray[0]['putOnRoad']);?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td class="content_title" title="Print" align="right" colspan="6"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printVehicleReport(); return false;" /></td></tr>
					<tr><td height="5px" colspan="2"></td></tr>
				</table>
		</div>

			<!-- insurance info -->


		<div class="dhtmlgoodies_aTab" style="height:240px;width:980px;overflow:auto;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td width="21%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>From</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('InsuranceFromDate',date('Y-m-d'));?></td>

									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>To</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('InsuranceToDate',date('Y-m-d'));?></td>

									<td  align="right" style="padding-right:5px">&nbsp;&nbsp;
									<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getInsuranceDetail();return false;" /></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><div id="divInsuranceDetail" style="overflow:auto;"></div></td>
					</tr>
				</table>
		</div>
			<!-- fuel info -->

		<div class="dhtmlgoodies_aTab" style="height:240px;width:980px;overflow:auto;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Name</b></nobr></td>
									<td>&nbsp;<b>:</b></td>
									<td class="padding">
									<select size="1" class="selectfield" name="fuelStaffId" id="fuelStaffId" onchange="clearFuelText();">
									<option value="">Select</option>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getTransportStaffData('',"WHERE leavingDate = '' OR leavingDate = '0000-00-00' OR leavingDate IS NULL ");
									  ?>
									</select></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>From</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('fuelFromDate',date('Y-m-d'));?></td>

									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>To</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('fuelToDate',date('Y-m-d'));?></td>

									<td  align="right" style="padding-right:5px">&nbsp;&nbsp;
									<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getFuelDetail();return false;" /></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><div id="divFuelDetail" style="overflow:auto;"></div></td>
					</tr>
				</table>
		</div>
			<!-- accident info -->

		<div class="dhtmlgoodies_aTab" style="height:240px;width:980px;overflow:auto;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Name</b></nobr></td>
									<td>&nbsp;<b>:</b></td>
									<td class="padding">
									<select size="1" class="selectfield" name="accidentStaffId" id="accidentStaffId" onchange="clearAccidentText();">
									<option value="">Select</option>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getTransportStaffData('',"WHERE leavingDate = '' OR leavingDate = '0000-00-00' OR leavingDate IS NULL ");
									  ?>
									</select></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>From</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('accidentFromDate',date('Y-m-d'));?></td>

									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>To</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('accidentToDate',date('Y-m-d'));?></td>

									<td  align="right" style="padding-right:5px">&nbsp;&nbsp;
									<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getAccidentDetail();return false;" /></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><div id="divAccidentDetail" style="overflow:auto;"></div></td>
					</tr>
				</table>
		</div>
			<!-- service info -->

		<div class="dhtmlgoodies_aTab" style="height:240px;width:980px;overflow:auto;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Type</b></nobr></td>
									<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busService" id="busService" onchange="clearServiceText();">
										<option value="">Select</option>
										<?php
											require_once(BL_PATH.'/HtmlFunctions.inc.php');
											echo HtmlFunctions::getInstance()->getVehicleServiceData();
										?></select>
									</td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>From</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('serviceFromDate',date('Y-m-d'));?></td>

									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>To</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('serviceToDate',date('Y-m-d'));?></td>

									<td  align="right" style="padding-right:5px">&nbsp;&nbsp;
									<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getServiceDetail();return false;" /></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><div id="divServiceDetail" style="overflow:auto;"></div></td>
					</tr>
				</table>
		</div>
		<!-- Service details info -->
	<div class="dhtmlgoodies_aTab" style="height:240px;width:980px;overflow:auto;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
					<tr>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" >
								<tr>
									<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Service Type</b></nobr></td>
									<td class="padding">:&nbsp;<select size="1" class="selectfield" name="busService1" id="busService1" onchange="clearDetailsService()">
										<option value="">Select</option>
										<?php
											require_once(BL_PATH.'/HtmlFunctions.inc.php');
											echo HtmlFunctions::getInstance()->getVehicleServiceData();
										?></select>
									</td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>From</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('serviceFromDate1',date('Y-m-d'));?></td>

									<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>To</b></nobr></td>
									<td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
									<?php echo $htmlFunctions->datePicker2('serviceToDate1',date('Y-m-d'));?></td>

									<td  align="right" style="padding-right:5px">&nbsp;&nbsp;
									<input type="image" name="imageField1" id="imageField1" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getAllServiceDetail();return false;" /></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><div id="divAllServiceDetail" style="overflow:auto;"></div></td>
					</tr>
				</table>
		</div>
			<!-- tyre info -->

		<div class="dhtmlgoodies_aTab" style="height:240px;width:980px;overflow:auto;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><div id="divTyreDetail" style="overflow:auto;"></div></td>
					</tr>
			</table>
		</div>
	</div>
	</td>
	</tr>
	</table>

<?php


// for VSS
// $History: ajaxInitVehicleList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:53p
//Created in $/Leap/Source/Library/Vehicle
//new ajax files for vehicle report
//
?>