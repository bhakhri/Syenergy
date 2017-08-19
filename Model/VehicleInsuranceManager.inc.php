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
  
  class VehicleInsuranceManager {
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
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addVehicleInsurance() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('insurance_company', array('insuringCompanyName','insuringCompanyDetails'), array(trim($REQUEST_DATA['insuringCompanyName']),trim($REQUEST_DATA['insuringCompanyDetails'])));
    }
    
    //-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editVehicleInsurance($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('insurance_company', array('insuringCompanyName','insuringCompanyDetails'), array(trim($REQUEST_DATA['insuringCompanyName']),trim($REQUEST_DATA['insuringCompanyDetails'])) , "insuringCompanyId=$id" );
    }    
  //-------------------------------------------------------------------------------
//
//deleteVehicleType() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteVehicleInsurance($insuringCompanyId) {
     
        $query = "DELETE 
        FROM insurance_company 
        WHERE insuringCompanyId=$insuringCompanyId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getVehicleType() is used to get the list of data 
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleInsurance($conditions='') {
       $query = "SELECT *
        FROM insurance_company $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleInsuranceList($conditions='', $limit = '', $orderBy='insuringCompanyName') {
     
        $query = "SELECT *
        FROM insurance_company $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     //-------------------------------------------------------------------------------
//
//getTotalVehicleInsurance() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalVehicleInsurance($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM insurance_company $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleInsuranceHistory($conditions='', $limit = '', $orderBy='insuringCompanyName') {
		
	 $query = "	SELECT 
							bs.busId,
							bs.busName,
							bs.busNo,
							IF(bs.isActive=1,'Yes','No') AS isActive,
							ic.insuringCompanyName,
							bi.lastInsuranceDate,
							bi.insuranceDueDate,
							bi.valueInsured,
							bi.insurancePremium,
							bi.ncb,
							bi.policyNo
					FROM 
							bus bs,
							bus_insurance bi,
							insurance_company ic
					WHERE	bi.busId = bs.busId
					AND		ic.insuringCompanyId = bi.insuringCompanyId
							$conditions 
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
       
}
?>
<?php 
// $History: VehicleInsuranceManager.inc.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:29p
//Created in $/Leap/Source/Model
//new model for vehicle insurance
//
?>