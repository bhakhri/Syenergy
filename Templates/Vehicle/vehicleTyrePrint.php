<?php 
//This file is used as printing version for payment history.
//
// Author :Jaineesh
// Created on : 28-01-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    
	require_once(MODEL_PATH . "/VehicleManager.inc.php");
    $vehicleManager = VehicleManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    

	$vehicleId = $REQUEST_DATA['vehicleNo'];
	
	$vehicleDetailArray						=	$vehicleManager->getVehicleDetails($vehicleId);
	$vehicleTyreArray						=	$vehicleManager->getVehicleTyreDetail($vehicleId);
	$cnt = count($vehicleTyreArray);

	$valueArray = array();
	
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
			<tr>
                <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
                <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
                <td align="right" colspan="1" width="25%" class="">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                        </tr>
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                        </tr>
                    </table>
                </td>
            </tr>
			<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Tyre Detail Report Print</th></tr>
			<tr><th colspan="3" <?php echo $reportManager->getReportDataStyle(); ?> align="center"><I>Vehicle No. : <?php echo $vehicleDetailArray[0]['busNo'] ?></I> </th></tr>
			<tr>
				<td valign="top" colspan="3">
					<?php 
					$x = 1;
					if($cnt > 0 && is_array($vehicleTyreArray)) { ?>
						<table border="0" width="100%" class="contenttab_internal_rows">
							<?php
							for($i=0;$i<$cnt;$i++) {
								if($vehicleTyreArray[$i]['usedAsMainTyre'] == 1) { ?>
									<tr><td width="20%" >&nbsp;Model No.</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<?php echo $vehicleTyreArray[$i]['modelNumber'] ?> </td></tr>
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
						</table>
					<?php } ?>
				</td>
			</tr>
	</table>

<?php     
// $History: vehicleTyrePrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>