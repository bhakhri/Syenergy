<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
	$accidentStaffId = $REQUEST_DATA['accidentStaffId'];
	$accidentFromDate= $REQUEST_DATA['accidentFromDate'];
	$accidentToDate = $REQUEST_DATA['accidentToDate'];
	

  //  $totalArray = $vehicleManager->getCountVehicleList($filter);
    $filter = "HAVING accidentDate BETWEEN '$accidentFromDate' AND '$accidentToDate'";
    $vehicleAccidentRecordArray = $vehicleManager->getVehicleAccidentList($vehicleId,$accidentStaffId,$filter);
	//print_r($vehicleAccidentRecordArray);
    $cnt = count($vehicleAccidentRecordArray);
	
	?>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <?php if($cnt > 0 && is_array($vehicleAccidentRecordArray)) { ?>
				  <tr><td height="10px"></td></tr>
					<tr>
						<td width="10%" class="contenttab_internal_rows"><nobr><b>Staff Name</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows"><nobr><b>Route Name</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="center"><nobr><b>Date</b></nobr></td>
					</tr>
					<?php
					  
						for($i=0; $i<$cnt; $i++) { 
							$vehicleAccidentRecordArray[$i]['accidentDate'] = UtilityManager::formatDate($vehicleAccidentRecordArray[$i]['accidentDate']);
							?>
							<tr>
								
								<td><?php echo $vehicleAccidentRecordArray[$i]['name'] ?></td>
								<td><?php echo $vehicleAccidentRecordArray[$i]['routeName'] ?></td>
								<td align="center"><?php echo $vehicleAccidentRecordArray[$i]['accidentDate'] ?></td>
							</tr>
						<?php
						}
						?>
						<tr><td class="content_title" title="Print" align="right" colspan="5"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printAccidentReport(); return false;" /></td></tr>	
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
// $History: ajaxInitVehicleAccidentList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:53p
//Created in $/Leap/Source/Library/Vehicle
//new ajax files for vehicle report
//
?>