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

  class VehicleServiceRepairManager {
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
    public function updateVehicleService($busId,$serviceId) {
        global $REQUEST_DATA;

		$query = "	UPDATE	bus_service
					SET		done = 1,
							doneOnDate = '".$REQUEST_DATA['serviceDate']."'
					WHERE	busId = $busId
					AND		serviceId = $serviceId";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------------------------------
//
//addVehicleTyre() is used to add new record in database.
// Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateBusService($busId,$serviceId) {
        global $REQUEST_DATA;

		$query = "	UPDATE	bus_service
					SET		done = 0,
							doneOnDate = '0000-00-00'
					WHERE	busId = $busId
					AND		serviceId = $serviceId";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------------------------------
//
//updateVehicleServiceRepair() is used to UPDATE vehicle service repair
//Author : Jaineesh
// Created on : 10.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateVehicleServiceRepair() {
        global $REQUEST_DATA;

		$serviceRepairId = trim($REQUEST_DATA['serviceRepairId']);
		$vehicleTypeId = trim($REQUEST_DATA['vehicleType']);
		$busId = trim($REQUEST_DATA['busNo']);
		$busService = trim($REQUEST_DATA['busService']);
		$serviceDate = trim($REQUEST_DATA['serviceDate1']);
		$readingEntry = trim($REQUEST_DATA['readingEntry']);
		$billNo = trim($REQUEST_DATA['billNo']);
		$servicedAt = trim($REQUEST_DATA['servicedAt']);


	 $query = "	UPDATE	vehicle_service_repair
				SET		serviceDate = '$serviceDate',
						kmReading = '$readingEntry',
						billNo = '$billNo',
						servicedAt = '$servicedAt'
				WHERE	serviceRepairId = $serviceRepairId
				";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

	//-------------------------------------------------------------------------------
//
//insertVehicleServiceRepair() is used to Add vehicle service repair detail
//Author : Jaineesh
// Created on : 10.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function insertVehicleServiceRepair() {
        global $REQUEST_DATA;

		$vehicleTypeId = trim($REQUEST_DATA['vehicleType']);
		$busId = trim($REQUEST_DATA['busNo']);
		$busService = trim($REQUEST_DATA['busService']);
		$serviceNo = trim($REQUEST_DATA['serviceNo']);
		$serviceDate = trim($REQUEST_DATA['serviceDate']);
		$readingEntry = trim($REQUEST_DATA['readingEntry']);
		$billNo = trim($REQUEST_DATA['billNo']);
		$servicedAt = trim($REQUEST_DATA['servicedAt']);


	 $query = "	INSERT INTO vehicle_service_repair
				(	vehicleTypeId,
					busId,
					serviceType,
					serviceNo,
					serviceDate,
					kmReading,
					billNo,
					servicedAt
				)
				VALUES (
					'$vehicleTypeId',
					'$busId',
					'$busService',
					'$serviceNo',
					'$serviceDate',
					'$readingEntry',
					'$billNo',
					'$servicedAt'
				)";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------------------------------
// used to Delete Vehicle Service Detail
// Author : Jaineesh
// Created on : 11.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function deleteVehicleServiceDetail() {
		global $REQUEST_DATA;
        global $sessionHandler;

		$serviceRepairId = trim($REQUEST_DATA['serviceRepairId']);

		$query = "	DELETE
					FROM	vehicle_service_oil
					WHERE	serviceRepairId = ".$serviceRepairId."";

	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Delete Vehicle Repair Detail
// Author : Jaineesh
// Created on : 11.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function deleteVehicleRepairDetail() {
		global $REQUEST_DATA;
        global $sessionHandler;

		$serviceRepairId = trim($REQUEST_DATA['serviceRepairId']);

		$query = "	DELETE
					FROM	vehicle_service_repair_detail
					WHERE	serviceRepairId = ".$serviceRepairId."";

	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Delete Vehicle Repair Detail
// Author : Jaineesh
// Created on : 11.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function deleteVehicleServiceRepair() {
		global $REQUEST_DATA;
        global $sessionHandler;

		$serviceRepairId = trim($REQUEST_DATA['serviceRepairId']);

		$query = "	DELETE
					FROM	vehicle_service_repair
					WHERE	serviceRepairId = ".$serviceRepairId."";

	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}


//-------------------------------------------------------------------------------
//
//insertVehicleServiceRepairDetail() is used to Add vehicle service repair detail
//Author : Jaineesh
// Created on : 11.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function insertVehicleServiceRepairDetail($str) {
        global $REQUEST_DATA;


	 $query = "	INSERT INTO vehicle_service_repair_detail
				(	serviceRepairId,
					type,
					item,
					amount
				)
				VALUES $str";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------------------------------
//
//insertVehicleServiceOil() is used to Add vehicle service oil
//Author : Jaineesh
// Created on : 10.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function insertVehicleServiceOil($serviceRepairId,$actionId,$amount,$kmRun,$kmChangeRun) {
        global $REQUEST_DATA;

		$vehicleTypeId = trim($REQUEST_DATA['vehicleType']);
		$busId = trim($REQUEST_DATA['busNo']);
		$busService = trim($REQUEST_DATA['busService']);
		$serviceDate = trim($REQUEST_DATA['serviceDate']);


	 $query = "	INSERT INTO vehicle_service_oil
				(	serviceRepairId,
					actionId,
					amount,
					kmRun,
					changeKM
				)
				VALUES (
					'$serviceRepairId',
					'$actionId',
					'$amount',
					'$kmRun',
					'$kmChangeRun'
				)";

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
    public function deleteVehicleService($serviceId) {

        $query = "	UPDATE	bus_service
					SET		done=0,
							doneOnDate = '0000-00-00',
							doneOnKM = 0
					WHERE	serviceId = $serviceId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

 //-------------------------------------------------------------------------------
//
//getVehicleServiceRepairList() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleServiceRepairList($conditions='',$limit='',$orderBy='',$condition1='') {

        $query = "SELECT	vsr.serviceDate,
							b.busId,
							b.busNo,
							vsr.serviceRepairId,
							vsr.serviceDate,
							vsr.kmReading,
							vsr.billNo,
							vsr.servicedAt,
							vt.vehicleTypeId
							$condition1
					FROM	vehicle_service_repair vsr,
							bus b,
							vehicle_type vt
					WHERE	vsr.busId = b.busId
					AND		b.isActive = 1
					AND		vsr.vehicleTypeId = vt.vehicleTypeId
							$conditions
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalVehicleServiceRepair() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalVehicleServiceRepair($conditions='') {

        $query = "SELECT	COUNT(*) AS totalRecords
					FROM	vehicle_service_repair vsr,
							bus b,
							vehicle_type vt
					WHERE	vsr.busId = b.busId
					AND		b.isActive = 1
					AND		vsr.vehicleTypeId = vt.vehicleTypeId
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
//
//getVehicleServiceRepairList() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleServiceRepairValues($conditions='') {

        $query = "SELECT	vsr.serviceDate,
							vsr.busId,
							b.busNo,
							vsr.serviceRepairId,
							vsr.serviceDate,
							vsr.kmReading,
							vsr.billNo,
							vsr.servicedAt,
							vt.vehicleTypeId,
							vsr.serviceType,
							(SELECT bs.serviceNo FROM bus_service bs WHERE vsr.serviceNo = bs.serviceId) AS serviceNo
					FROM	vehicle_service_repair vsr,
							bus b,
							vehicle_type vt,
							bus_service bs
					WHERE	vsr.busId = b.busId
					AND		b.isActive = 1
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getVehicleServiceOilValues() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleServiceOilValues($conditions='') {

        $query = "SELECT	vso.*
					FROM	vehicle_service_oil vso
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
//
//getVehicleServiceOilValues() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleServiceRepair($conditions='') {

		global $REQUEST_DATA;

		$serviceRepairId = trim($REQUEST_DATA['serviceRepairId']);

        $query = "	SELECT	vsr.serviceNo,
							vsr.busId
					FROM	vehicle_service_repair vsr
					WHERE	serviceRepairId = ".$serviceRepairId." ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getVehicleServiceRepairDetailValues() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleServiceRepairDetailValues($conditions='') {

        $query = "	SELECT	vsrd.*
					FROM	 vehicle_service_repair_detail vsrd
							$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   //-------------------------------------------------------------------------------
//
//getVehicleTyre() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleService($conditions='') {

        $query = "SELECT	bs.serviceId,
							b.busId,
							b.busNo,
							bs.doneOnDate,
							bs.doneOnKM,
							bs.serviceType,
							bs.serviceNo,
							vt.vehicleTypeId
					FROM	bus_service bs,
							bus b,
							vehicle_type vt
					WHERE	bs.busId = b.busId
					AND		b.isActive = 1
					AND		b.vehicleTypeId = vt.vehicleTypeId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	   //-------------------------------------------------------------------------------
//
//getVehicleServiceDetail() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleFreeServiceDetail($conditions='') {

        $query = "SELECT	bs.serviceId,
							bs.busId,
							bs.doneOnDate,
							bs.doneOnKM,
							bs.serviceType,
							bs.serviceNo
					FROM	bus_service bs,
							bus b
					WHERE	bs.busId = b.busId
					AND		b.isActive = 1
					AND		bs.doneOnKM != 0
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getVehicleTyre() is used to get the list of data
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleFreeService($conditions='') {

        $query = "SELECT	bs.serviceId,
							bs.serviceNo
					FROM	bus_service bs,
							bus b
					WHERE	bs.busId = b.busId
					AND		b.isActive = 1
					AND		bs.done = 0
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
    public function getVehicleBattery($conditions='', $limit = '', $orderBy='busId') {

        $query = "	SELECT	*
					FROM	bus_battery bb
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
    public function getVehicleServiceList($conditions='', $limit = '', $orderBy='busId') {

       $query = "	SELECT	bs.*,
							b.busNo,
							if(bs.serviceType = 1,'Free','Paid') AS serviceType,
							if(bs.serviceDueKM = 0,'---',bs.serviceDueKM) AS serviceDueKM
					FROM	bus_service bs,
							bus b
					WHERE	bs.busId = b.busId
					AND		bs.done != 0
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

    public function getTotalVehicleService($conditions='') {

        $query = "	SELECT	COUNT(*) AS totalRecords
			        FROM	bus_service bs,
							bus b
					WHERE	bs.busId = b.busId
					AND		bs.done != 0
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

//-------------------------------------------------------------------------------
//
//getVehicleInsuranceDetail() is used to vehicle insurance detail
//Author : Jaineesh
// Created on : 06.01.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleServiceDetail($serviceId) {

        $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	bus_service bs,
							bus b
					WHERE	bs.serviceId = $serviceId
					AND		bs.busId = b.busId
					AND		b.isActive = 1";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}
?>
<?php
// $History: VehicleServiceManager.inc.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Model
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/13/10    Time: 12:20p
//Updated in $/Leap/Source/Model
//fixed bug nos. 0002589
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Model
//fixed bug in fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Model
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:05p
//Created in $/Leap/Source/Model
//new model file for vehicle service
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