<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete
// functionality
//
// Author : Jaineesh
// Created on : (25.01.2010 )
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
    require_once(MODEL_PATH . "/VehicleServiceRepairManager.inc.php");
	$vehicleServiceRepairManager = VehicleServiceRepairManager::getInstance();


	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlFunctions = HtmlFunctions::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$vehicleId = $REQUEST_DATA['vehicleNo'];
	$busServiceId = $REQUEST_DATA['busService1'];
	$serviceFromDate= $REQUEST_DATA['serviceFromDate1'];
	$serviceToDate = $REQUEST_DATA['serviceToDate1'];

  //$totalArray = $vehicleManager->getCountVehicleList($filter);
    $filter = "AND b.busId = $vehicleId  AND vsr.serviceType = $busServiceId AND vsr.serviceDate BETWEEN '$serviceFromDate' AND '$serviceToDate' ";
   // $vehicleServiceRecordArray = $vehicleManager->getVehicleServiceList($vehicleId,$busServiceId,$filter);

	$condition1 = ",(
						(SELECT		IFNULL(SUM(vso.amount),0)
							FROM	vehicle_service_oil vso
							WHERE	vsr.serviceRepairId= vso.serviceRepairId)
								+
						(SELECT IFNULL(SUM(vsrd.amount),0)
							FROM	 vehicle_service_repair_detail vsrd
							WHERE	vsrd.serviceRepairId=vsr.serviceRepairId)
					)as total";
	$getVehicleServiceRepairArray = $vehicleServiceRepairManager->getVehicleServiceRepairList($filter,'','vsr.serviceDate',$condition1);

	//print_r($getVehicleServiceRepairArray);
	//die;
    $cnt = count($getVehicleServiceRepairArray);

	?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <?php if($cnt > 0 && is_array($getVehicleServiceRepairArray)) { ?>
				  <tr><td height="10px"></td></tr>
					<tr>
						<td width="10%" class="contenttab_internal_rows1" align="left"><nobr><b>Service Date</b></nobr></td>
						<td width="15%" class="contenttab_internal_rows1" align="right"><nobr><b>KM Reading</b></nobr></td>
						<td width="15%" class="contenttab_internal_rows1" align="right"><nobr><b>Bill/Ticket No.</b></nobr></td>
						<td width="1%" class="contenttab_internal_rows1" align="right"><nobr></nobr></td>
						<td width="13%" class="contenttab_internal_rows1" align="left"><nobr><b>Serviced At</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="right"><nobr><b>Total Amount (Rs.)</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="right"><nobr></nobr></td>
						<td width="2%" class="contenttab_internal_rows1" align="center"><nobr><b>Details</b></nobr></td>
					</tr>
					<?php

						for($i=0; $i<$cnt; $i++) {
								$getVehicleServiceRepairArray[$i]['serviceDate'] = UtilityManager::formatDate($getVehicleServiceRepairArray[$i]['serviceDate']);
								$getVehicleServiceRepairArray[$i]['servicedAt'] = $getVehicleServiceRepairArray[$i]['servicedAt'];

							?>
							<tr>
								<td align="left"><?php echo $getVehicleServiceRepairArray[$i]['serviceDate'] ?></td>
								<td align="right"><?php echo $getVehicleServiceRepairArray[$i]['kmReading'] ?></td>
								<td align="right"><?php echo $getVehicleServiceRepairArray[$i]['billNo'] ?></td>
								<td width="10%" class="contenttab_internal_rows1" align="right"><nobr></nobr></td>
								<td align="left" width="13%"><?php echo $getVehicleServiceRepairArray[$i]['servicedAt'] ?></td>
								<td align="right" width="10%"><?php echo $getVehicleServiceRepairArray[$i]['total'] ?></td>
								<td width="10%" class="contenttab_internal_rows1" align="right"><nobr></nobr></td>
								<td align="center" width="2%"><a href="#"onClick="getServiceDetails(<?php echo $getVehicleServiceRepairArray[$i]['serviceRepairId']?>);displayWindow('ServiceDetails',330,250);return false;" name="Detail" title="Detail"><u>Detail</u></a></td>
							</tr>
						<?php
						}
						?>
						<?php
					 }
					 else { ?>
						<tr><td height="10px"></td></tr>
						<tr><td class="contenttab_internal_rows1" align="center"><b>No Record Found</b></td></tr>
					<?php
					 }
					?>
				</table>
			</td>
		</tr>
		<tr><td height="5px" colspan="2"></td></tr>
	</table>


<?php


// for VSS
// $History: ajaxInitVehicleServiceList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:53p
//Created in $/Leap/Source/Library/Vehicle
//new ajax files for vehicle report
//
?>