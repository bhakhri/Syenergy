<?php
//-------------------------------------------------------
// Purpose: To delete degree detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleServiceRepair');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['serviceRepairId']) || trim($REQUEST_DATA['serviceRepairId']) == '') {
        $errorMessage = INVALID_VEHICLE;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleServiceRepairManager.inc.php");
		$vehicleServiceRepairManager = VehicleServiceRepairManager::getInstance();
		
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$getVehicleServiceRepair = $vehicleServiceRepairManager->getVehicleServiceRepair();
			$serviceId = $getVehicleServiceRepair[0]['serviceNo'];
			$busId = $getVehicleServiceRepair[0]['busId'];

			if($serviceId != '') {
				$updateBusService = $vehicleServiceRepairManager->updateBusService($busId,$serviceId);
				if($updateBusService === false) {
					$errorMessage = FAILURE;
				}
			}
	
			if($deleteVehicleServiceRepair = $vehicleServiceRepairManager->deleteVehicleServiceDetail()) {
				if($deleteVehicleServiceRepair === false) {
					$errorMessage = FAILURE;
				}
				$deleteVehicleRepair = $vehicleServiceRepairManager->deleteVehicleRepairDetail();
				if($deleteVehicleRepair === false) {
					$errorMessage = FAILURE;
				}
				
				$deleteVehicleServiceRepair = $vehicleServiceRepairManager->deleteVehicleServiceRepair();
				if($deleteVehicleRepair === false) {
					$errorMessage = FAILURE;
				}

				if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					echo DELETE;
					die;
				 }
				 else {
					echo FAILURE;
					die;
				}
			}
		   else {
				echo DEPENDENCY_CONSTRAINT;
			}
		}
		else {
			echo FAILURE;
			die;
		}

	}
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/03/09    Time: 11:20a
//Updated in $/Leap/Source/Library/Department
//fixed bug nos.1214,1215,1219,1220
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/14/09    Time: 11:34a
//Updated in $/Leap/Source/Library/Department
//modified for access
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:49p
//Created in $/Leap/Source/Library/Department
//used for delete data in department table
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Library/Degree
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:31p
//Updated in $/Leap/Source/Library/Degree
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:22p
//Updated in $/Leap/Source/Library/Degree
//Adding AjaxEnabled Delete functionality
//*********Solved the problem :**********
//Open 2 browsers opening Degree Masters page. On one page, delete a
//Degree. On the second page, the deleted degree is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted Degree in the second page which was left
//untouched. Provide the new Degree Code and click Submit button.A blank
//popup is displayed. It should rather display "The Degree you are trying
//to edit no longer exists".
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:55p
//Created in $/Leap/Source/Library/Degree
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:53p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxEnabled Delete Functionality
//Added Input Data Validation using Javascript
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:09p
//Created in $/Leap/Source/Library/Quota
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
?>

