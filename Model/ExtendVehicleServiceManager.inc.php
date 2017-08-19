<?php

//-------------------------------------------------------------------------------
//
//EmployeeManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class ExtendVehicleServiceManager {
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
//addVehicleType() is used to add new record in database.
// Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addExtendVehicleFreeService($busId,$serviceType,$serviceNo,$serviceDate,$serviceKM) {
        global $REQUEST_DATA;
		
		 $query = "	INSERT INTO bus_service (busId,serviceType,serviceNo,serviceDueDate,serviceDueKM) 
					VALUES ($busId,$serviceType,'$serviceNo','$serviceDate','$serviceKM')";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editVehicleAccident($id) {
        global $REQUEST_DATA;
		
		$busId = trim($REQUEST_DATA['busNo']);
		$transportStaff = trim($REQUEST_DATA['transportStaff']);
		$busRoute = trim($REQUEST_DATA['busRoute']);
		$accidentDate1 = trim($REQUEST_DATA['accidentDate1']);
		$remarks = trim($REQUEST_DATA['remarks']);


		$query = "	UPDATE bus_accident		SET	busId = '$busId',
											staffId = '$transportStaff',
											busRouteId = '$busRoute',
											accidentDate = '$accidentDate1',
											remarks = '$remarks'
									WHERE	accidentId = $id";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
        
    }    
  //-------------------------------------------------------------------------------
//
//deleteVehicleType() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteVehicleAccident($accidentId) {
     
        $query = "	DELETE 
					FROM	bus_accident 
					WHERE	accidentId=$accidentId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getVehicleAccident() is used to vehicle accident detail
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getVehicleAccidentDetail($accidentId) {
     
        $query = "	SELECT COUNT(*) AS totalRecords 
					FROM	bus_accident ba,
							bus b
					WHERE	ba.accidentId=$accidentId
					AND		ba.busId = b.busId
					AND		b.isActive = 1";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getVehicleType() is used to get the list of data 
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleFreeService($busId) {
        $query = "	SELECT	count(busId) AS countRecords
					FROM	bus_service
					WHERE	serviceType = 1
					AND		busId = $busId
							GROUP BY busId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getExtendedVehicleServicesList($conditions='',$limit = '', $orderBy='busId') {
       
		$query = "	SELECT	b.busNo, 
							count(bs.busId) AS totalFreeServices
					FROM	bus b,
							bus_service bs
					WHERE	bs.busId = b.busId
							$conditions
							GROUP BY bs.busId
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getDoneVehicleServicesList($conditions='',$orderBy='busId') {
       
		$query = "	SELECT	b.busNo, 
							count(bs.busId) AS doneFreeServices
					FROM	bus b,
							bus_service bs
					WHERE	bs.busId = b.busId
							$conditions
							GROUP BY bs.busId
							ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   
     //-------------------------------------------------------------------------------
//
//getTotalVehicleInsurance() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalExtendedVehicleServices($conditions='') {
    
       $query = "	SELECT	b.busNo, 
							count(bs.busId) AS totalFreeServices
					FROM	bus b,
							bus_service bs
					WHERE	bs.busId = b.busId
							$conditions
							GROUP BY bs.busId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}
?>
<?php 
// $History: ExtendVehicleServiceManager.inc.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:53p
//Created in $/Leap/Source/Model
//new file
//
?>