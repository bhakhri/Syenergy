<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (27.01.2010 )
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

    ////////////
    
	$vehicleTyreArray						=	$vehicleManager->getVehicleTyreDetail($vehicleId);
	$cnt = count($vehicleTyreArray);
		?>
			<!-- tyre info -->

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<tr>
				<td valign="top">
					<?php 
					$x = 1;
					if($cnt > 0 && is_array($vehicleTyreArray)) { ?>
						<table border="0" width="100%" class="contenttab_internal_rows">
							<?php
							for($i=0;$i<$cnt;$i++) {
								if($vehicleTyreArray[$i]['usedAsMainTyre'] == 1) { ?>
									<tr><td width="20%">&nbsp;Model No.</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<?php echo $vehicleTyreArray[$i]['modelNumber'] ?> </td></tr>
									<tr><td width="20%">&nbsp;Manufacturer</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<?php echo $vehicleTyreArray[$i]['manufacturer'] ?> </td></tr>
									<tr><td width="20%">&nbsp;Main Tyre No. <?php echo $x ?></td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<?php echo $vehicleTyreArray[$i]['tyreNumber'] ?></td></tr>
									<tr><td height="2%"><hr></td></tr>
								<?php 	
								}
								if($vehicleTyreArray[$i]['usedAsMainTyre'] == 0) { ?>
									<tr><td width="20%">&nbsp;Model No.</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<?php echo $vehicleTyreArray[$i]['modelNumber'] ?> </td></tr>
									<tr><td width="20%">&nbsp;Manufacturer</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<?php echo $vehicleTyreArray[$i]['manufacturer'] ?> </td></tr>
									<tr><td width="20%">&nbsp;Spare Tyre No. <?php echo $x ?></td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<?php echo $vehicleTyreArray[$i]['tyreNumber']?></td></tr>
									<tr><td height="2%"><hr></td></tr>
									<?php 
								}
								$x++;
							}
							?>
							<tr><td class="content_title" title="Print" align="right" colspan="5"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printTyreReport(); return false;" /></td></tr>	
						</table>
					<?php } ?>
				</td>
			</tr>
	</table>
<?php
    
// for VSS
// $History: ajaxInitVehicleTyreList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:53p
//Created in $/Leap/Source/Library/Vehicle
//new ajax files for vehicle report
//
?>