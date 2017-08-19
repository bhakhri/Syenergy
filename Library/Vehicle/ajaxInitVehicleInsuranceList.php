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

	$vehicleId = $REQUEST_DATA['vehicleNo'];
	$insuranceFromDate = $REQUEST_DATA['InsuranceFromDate'];
	$insuranceToDate = $REQUEST_DATA['InsuranceToDate'];

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
    
  //  $totalArray = $vehicleManager->getCountVehicleList($filter);
    $filter = "AND bi.lastInsuranceDate BETWEEN '$insuranceFromDate' AND '$insuranceToDate'";
    $vehicleInsuranceRecordArray = $vehicleManager->getVehicleInsuranceList($vehicleId,$filter);
	//print_r($vehicleInsuranceRecordArray);
    $cnt = count($vehicleInsuranceRecordArray);

	?>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <?php if($cnt > 0 && is_array($vehicleInsuranceRecordArray)) { ?>
				  <tr><td height="10px"></td></tr>
					<tr>
						<td width="10%" class="contenttab_internal_rows1" align="center"><nobr><b>Ins. Date</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="center"><nobr><b>Ins. Due Date</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows"><nobr><b>Ins. Comp.</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows"><nobr><b>Policy No.</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="right"><nobr><b>Value Insured</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows1" align="right"><nobr><b>Ins. Premium</b></nobr></td>
						<td width="6%" class="contenttab_internal_rows1" align="right"><nobr><b>NCB</b></nobr></td>
						<td width="15%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Branch Name</b></nobr></td>
						<td width="10%" class="contenttab_internal_rows"><nobr><b>Agent Name</b></nobr></td>
					</tr>
					<?php
					  
						for($i=0; $i<$cnt; $i++) { 
							$vehicleInsuranceRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['lastInsuranceDate']);
							$vehicleInsuranceRecordArray[$i]['insuranceDueDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['insuranceDueDate']);
							?>
							<tr>
								<td align="center"><?php echo $vehicleInsuranceRecordArray[$i]['lastInsuranceDate'] ?></td>
								<td align="center"><?php echo $vehicleInsuranceRecordArray[$i]['insuranceDueDate'] ?></td>
								<td><?php echo $vehicleInsuranceRecordArray[$i]['insuringCompanyName'] ?></td>
								<td><?php echo $vehicleInsuranceRecordArray[$i]['policyNo'] ?></td>
								<td align="right"><?php echo $vehicleInsuranceRecordArray[$i]['valueInsured'] ?></td>
								<td align="right"><?php echo $vehicleInsuranceRecordArray[$i]['insurancePremium'] ?></td>
								<td align="right"><?php echo $vehicleInsuranceRecordArray[$i]['ncb'] ?></td>
								<td>&nbsp;&nbsp;&nbsp;<?php echo $vehicleInsuranceRecordArray[$i]['branchName'] ?></td>
								<td><?php echo $vehicleInsuranceRecordArray[$i]['agentName'] ?></td>
							</tr>
						<?php
						}
							?>
							<tr><td class="content_title" title="Print" align="right" colspan="9"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printInsuranceReport(); return false;" /></td></tr>
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
// $History: ajaxInitVehicleInsuranceList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:53p
//Created in $/Leap/Source/Library/Vehicle
//new ajax files for vehicle report
//
?>