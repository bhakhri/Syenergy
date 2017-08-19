<?php

//-------------------------------------------------------------------------------
//
//EmployeeManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class VehicleBatteryManager {
    private static $instance = null;
    
    private function __construct() {
    }
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }
    
//-------------------------------------------------------------------------------
//
//addVehicleTyre() is used to add new record in database.
// Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addVehicleBattery() {
        global $REQUEST_DATA;
		$busId = trim($REQUEST_DATA['busNo']);
		$batteryNo = trim($REQUEST_DATA['batteryNo']);
		$batteryMake = trim($REQUEST_DATA['batteryMake']);
		$warrantyDate = trim($REQUEST_DATA['warrantyDate']);
		$meterReading = trim($REQUEST_DATA['meterReading']);
		$replacementCost = trim($REQUEST_DATA['replacementCost']);
		$replacementDate = trim($REQUEST_DATA['replacementDate']);


		$query = "	INSERT INTO bus_battery (busId,batteryNo,batteryMake,warrantyDate,replacementCost,replacementDate,meterReading) 
					VALUES ('$busId','$batteryNo','$batteryMake','$warrantyDate','$replacementCost','$replacementDate','$meterReading')";
        
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


    //-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editVehicleBattery($batteryId='') {
        global $REQUEST_DATA;

		$busId = trim($REQUEST_DATA['busNo']);
		$batteryNo = trim($REQUEST_DATA['batteryNo']);
		$batteryMake = trim($REQUEST_DATA['batteryMake']);
		$warrantyDate = trim($REQUEST_DATA['warrantyDate']);
		$meterReading = trim($REQUEST_DATA['meterReading']);
		$replacementCost = trim($REQUEST_DATA['replacementCost']);
		$replacementDate = trim($REQUEST_DATA['replacementDate']);

     
	 $query = "	UPDATE	bus_battery 
				SET		batteryNo = '$batteryNo',
						batteryMake = '$batteryMake',
						warrantyDate = '$warrantyDate',
						replacementCost = '$replacementCost',
						replacementDate = '$replacementDate',
						meterReading = '$meterReading'
				WHERE	batteryId = $batteryId ";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

    }

//-------------------------------------------------------------------------------
//
//deleteTyreHistory() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 01.12.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteTyreHistory($tyreId) {
     
        $query = "DELETE 
        FROM tyre_history
        WHERE tyreId=$tyreId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

  //-------------------------------------------------------------------------------
//
//deleteVehicleTyre() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteVehicleTyre($tyreId) {
     
        $query = "	UPDATE  
							TYRE_MASTER 
					SET		isActive = 0
					WHERE tyreId=$tyreId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getVehicleTyre() is used to get the list of data 
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleTyre($conditions='') {
     
        $query = "SELECT 
							tm.tyreId,
							tm.tyreNumber,
							tm.manufacturer,
							tm.modelNumber,
							tm.purchaseDate,
							th.busReadingOnInstallation,
							th.usedAsMainTyre,
							th.placementReason,
							b.busId,
							b.busNo,
							tm.isActive,
							th.usedAsMainTyre
					FROM	tyre_master tm,
							tyre_history th,
							bus b
					WHERE	tm.tyreId = th.tyreId
					AND		th.busId = b.busId 
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
 
//-------------------------------------------------------------------------------
//
//getVehicleTyreList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleBattery($conditions='', $limit = '', $orderBy='bt.busId') {
     
          $query = "	SELECT	bt.*,
							vt.vehicleTypeId
					FROM	bus_battery bt,
							bus b,
							vehicle_type vt
					WHERE	bt.busId = b.busId
					AND		b.isActive = 1
					AND		b.vehicleTypeId = vt.vehicleTypeId
							$conditions 
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 //-------------------------------------------------------------------------------
//
//getVehicleTyreList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleBatteryList($conditions='', $limit = '', $orderBy='busId') {
     
       $query = "	SELECT	bb.*,
							b.busNo,
							if(bb.replacementDate='' OR bb.replacementDate='0000-00-00','---',bb.replacementDate) AS replacementDate
					FROM	bus_battery bb,
							bus b
					WHERE	bb.batteryId IN (select max(batteryId) from bus_battery GROUP BY busId)
					AND		b.busId = bb.busId
					AND		b.isActive = 1
							$conditions
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     //-------------------------------------------------------------------------------
//
//getTotalVehicleTyre() is used to get total no. of records
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalVehicleBattery($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
			        FROM	bus_battery bb,
							bus b
					WHERE	bb.batteryId IN (select max(batteryId) from bus_battery GROUP BY busId)
					AND		b.busId = bb.busId
					AND		b.isActive = 1
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getVehicleTyreList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleTax($busId='') {
     
        $query = "	SELECT	max(busNoValidTill) AS busNoValidTill,
							max(passengerTaxValidTill) AS passengerTaxValidTill,
							max(roadTaxValidTill) AS roadTaxValidTill,
							max(pollutionCheckValidTill) AS pollutionCheckValidTill,
							max(passingValidTill) AS passingValidTill
					FROM	bus_entries be
					WHERE	be.busId = $busId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
       
}
?>
<?php 
// $History: VehicleBatteryManager.inc.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Model
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Model
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 1:22p
//Created in $/Leap/Source/Model
//new model for vehicle battery manager
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/04/09   Time: 6:56p
//Updated in $/Leap/Source/Model
//changes for vehicle tyre
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/01/09   Time: 6:59p
//Updated in $/Leap/Source/Model
//changes in interface of vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/25/09   Time: 3:31p
//Created in $/Leap/Source/Model
//new model for vehicle tyre
//
//
?>