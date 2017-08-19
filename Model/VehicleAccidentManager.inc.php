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
  
  class VehicleAccidentManager {
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
    public function addVehicleAccident() {
        global $REQUEST_DATA;
		
		$busId = trim($REQUEST_DATA['busNo']);
		$transportStaff = trim($REQUEST_DATA['transportStaff']);
		$busRoute = trim($REQUEST_DATA['busRoute']);
		$accidentDate = trim($REQUEST_DATA['accidentDate']);
		//$accidentDate = $accidentDate.date('h:i:s A');
		$remarks = trim($REQUEST_DATA['remarks']);

		$query = "	INSERT INTO bus_accident (busId,staffId,busRouteId,accidentDate,remarks) 
					VALUES ('$busId','$transportStaff','$busRoute','$accidentDate','$remarks')";

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
    public function getVehicleAccident($conditions='') {
        $query = "	SELECT	b.busId, 
							b.busNo, 
							ts.staffId, 
							br.busRouteId, 
							ts.name, 
							br.routeName, 
							ba.accidentDate, 
							ba.accidentId, 
							ba.remarks,
							vt.vehicleTypeId
					FROM	bus_accident ba, 
							transport_staff ts, 
							bus_route br, 
							bus b,
							vehicle_type vt
					WHERE	ba.busId = b.busId
					AND		ba.staffId = ts.staffId
					AND		ba.busRouteId = br.busRouteId
					AND		b.isActive =1
					AND		b.vehicleTypeId = vt.vehicleTypeId
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
    public function getVehicleAccidentList($conditions='', $limit = '', $orderBy='busNo') {
       
		$query = "	SELECT	b.busNo, ts.name, br.routeName, br.routeCode, ba.accidentDate, ba.accidentId
					FROM	bus_accident ba, transport_staff ts, bus_route br, bus b
					WHERE	ba.busId = b.busId
					AND		ba.staffId = ts.staffId
					AND		ba.busRouteId = br.busRouteId
							$conditions
							ORDER BY $orderBy $limit";
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
    
    public function getTotalVehicleAccident($conditions='') {
    
       $query = "	SELECT COUNT(*) AS totalRecords 
					FROM	bus_accident ba, transport_staff ts, bus_route br, bus b
					WHERE	ba.busId = b.busId
					AND		ba.staffId = ts.staffId
					AND		ba.busRouteId = br.busRouteId 
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}
?>
<?php 
// $History: VehicleAccidentManager.inc.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Model
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Model
//fixed bug in fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Model
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/18/09   Time: 12:34p
//Updated in $/Leap/Source/Model
//put new fields accident date as datetime, add remarks field
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 1:04p
//Created in $/Leap/Source/Model
//new model file for vehicle accident
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:29p
//Created in $/Leap/Source/Model
//new model for vehicle insurance
//
?>