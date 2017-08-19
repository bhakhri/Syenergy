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
  
  class VehicleTypeManager {
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
    public function addVehicleType() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('vehicle_type', array('vehicleType','mainTyres','spareTyres'), array(trim($REQUEST_DATA['vehicleType']),trim($REQUEST_DATA['mainTyres']),trim($REQUEST_DATA['spareTyres'])));
    }
    
    //-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editVehicleType($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('vehicle_type', array('vehicleType','mainTyres','spareTyres'), array(trim($REQUEST_DATA['vehicleType']),trim($REQUEST_DATA['mainTyres']),trim($REQUEST_DATA['spareTyres'])) , "vehicleTypeId=$id" );
    }    
  //-------------------------------------------------------------------------------
//
//deleteVehicleType() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteVehicleType($vehicleTypeId) {
     
        $query = "DELETE 
        FROM vehicle_type 
        WHERE vehicleTypeId=$vehicleTypeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getVehicleType() is used to get the list of data 
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleType($conditions='') {
     
        $query = "SELECT *
        FROM vehicle_type $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//checkVehicleType() is used to check whether vehicle type is using
//Author : Jaineesh
// Created on : 12.01.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function checkVehicleType($conditions='') {
     
        $query = "	SELECT	COUNT(*) as totalRecords
					FROM	vehicle_type vt,
							bus b
					WHERE	vt.vehicleTypeId = b.vehicleTypeId
					$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleTypeList($conditions='', $limit = '', $orderBy='vehicleType') {
     
        $query = "SELECT *
        FROM vehicle_type $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     //-------------------------------------------------------------------------------
//
//getTotalVehicleType() is used to get total no. of records
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalVehicleType($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM vehicle_type $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}
?>
<?php 
// $History: VehicleTypeManager.inc.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/12/10    Time: 1:32p
//Updated in $/Leap/Source/Model
//fixed bug in Fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:47p
//Created in $/Leap/Source/Model
//new model file for vehicle type
//
?>