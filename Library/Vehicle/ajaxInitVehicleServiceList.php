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

    require_once(MODEL_PATH . "/VehicleManager.inc.php");
    $vehicleManager = VehicleManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlFunctions = HtmlFunctions::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$vehicleId = $REQUEST_DATA['vehicleNo'];
	$busServiceId = $REQUEST_DATA['busService'];
	$serviceFromDate= $REQUEST_DATA['serviceFromDate'];
	$serviceToDate = $REQUEST_DATA['serviceToDate'];

  //  $totalArray = $vehicleManager->getCountVehicleList($filter);
    $filter = "AND bs.doneOnDate BETWEEN '$serviceFromDate' AND '$serviceToDate'";
    $vehicleServiceRecordArray = $vehicleManager->getVehicleServiceList($vehicleId,$busServiceId,$filter);

	//print_r($vehicleServiceRecordArray);
	//die;
    $cnt = count($vehicleServiceRecordArray);

	?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <?php if($cnt > 0 && is_array($vehicleServiceRecordArray)) { ?>
				  <tr><td height="10px"></td></tr>
					<tr>
						<td width="10%" class="contenttab_internal_rows"><nobr><b>Service No.</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="center"><nobr><b>Service Due Date</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="right"><nobr><b>Service Due KM</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="center"><nobr><b>Done On Date</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="right"><nobr><b>Done On KM</b></nobr></td>
					</tr>
					<?php

						for($i=0; $i<$cnt; $i++) {
							if($busServiceId == 2) {
								$vehicleServiceRecordArray[$i]['serviceNo'] = NOT_APPLICABLE_STRING;
								$vehicleServiceRecordArray[$i]['serviceDueDate'] = NOT_APPLICABLE_STRING;
								$vehicleServiceRecordArray[$i]['serviceDueKM'] = NOT_APPLICABLE_STRING;
								$vehicleServiceRecordArray[$i]['doneOnDate'] = UtilityManager::formatDate($vehicleServiceRecordArray[$i]['doneOnDate']);
							}
							else {
								$vehicleServiceRecordArray[$i]['serviceNo'] = $vehicleServiceRecordArray[$i]['serviceNo'];
								$vehicleServiceRecordArray[$i]['serviceDueDate'] = UtilityManager::formatDate($vehicleServiceRecordArray[$i]['serviceDueDate']);
								$vehicleServiceRecordArray[$i]['doneOnDate'] = UtilityManager::formatDate($vehicleServiceRecordArray[$i]['doneOnDate']);
							}

							?>
							<tr>
								<td><?php echo $vehicleServiceRecordArray[$i]['serviceNo'] ?></td>
								<td align="center"><?php echo $vehicleServiceRecordArray[$i]['serviceDueDate'] ?></td>
								<td align="right"><?php echo $vehicleServiceRecordArray[$i]['serviceDueKM'] ?></td>
								<td align="center"><?php echo $vehicleServiceRecordArray[$i]['doneOnDate'] ?></td>
								<td align="right"><?php echo $vehicleServiceRecordArray[$i]['doneOnKM'] ?></td>
							</tr>
						<?php
						}
						?>
							<tr><td class="content_title" title="Print" align="right" colspan="5"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printServiceReport(); return false;" /></td></tr>
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