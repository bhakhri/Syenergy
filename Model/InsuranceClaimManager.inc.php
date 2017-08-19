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
  
  class InsuranceClaimManager {
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
    public function addInsuranceClaim($selfExpenses) {
        global $REQUEST_DATA;
		
		$busId = trim($REQUEST_DATA['busNo']);
		$claimDate = trim($REQUEST_DATA['claimDate']);
		$claimAmount = trim($REQUEST_DATA['claimAmount']);
		$totalExpenses = trim($REQUEST_DATA['totalExpenses']);
		//$selfExpenses = trim($REQUEST_DATA['selfExpenses']);
		$ncbClaim = trim($REQUEST_DATA['ncbClaim']);
		$loggingClaim = trim($REQUEST_DATA['loggingClaim']);
		$settlementDate = trim(addslashes($REQUEST_DATA['settlementDate']));


		$query = "	INSERT INTO vehicle_insurance_claim (busId,claimDate,claimAmount,totalExpenses,selfExpenses,ncb,loggingClaim,dateOfSettlement) 
		VALUES ('$busId','$claimDate','$claimAmount','$totalExpenses','$selfExpenses','$ncbClaim','$loggingClaim','$settlementDate')";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editInsuranceClaim($id,$selfExpenses) {
        global $REQUEST_DATA;
		
		$busId = trim($REQUEST_DATA['busNo']);
		$claimDate = trim($REQUEST_DATA['claimDate']);
		$claimAmount = trim($REQUEST_DATA['claimAmount']);
		$totalExpenses = trim($REQUEST_DATA['totalExpenses']);
		//$selfExpenses = trim($REQUEST_DATA['selfExpenses']);
		$ncbClaim = trim($REQUEST_DATA['ncbClaim']);
		$loggingClaim = trim($REQUEST_DATA['loggingClaim']);
		$settlementDate = trim(addslashes($REQUEST_DATA['settlementDate']));


		$query = "	UPDATE vehicle_insurance_claim SET	busId = $busId,
														claimDate = '$claimDate',
														claimAmount = '$claimAmount',
														totalExpenses = '$totalExpenses',
														selfExpenses = '$selfExpenses',
														ncb = '$ncbClaim',
														loggingClaim = '$loggingClaim',
														dateOfSettlement = '$settlementDate'
										WHERE	claimId = $id";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
        
    }    
  //-------------------------------------------------------------------------------
//
//deleteVehicleType() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteInsuranceClaim($claimId) {
     
        $query = "	DELETE 
					FROM vehicle_insurance_claim 
					WHERE claimId=$claimId";
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
    public function getInsuranceClaim($conditions='') {
        $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	vehicle_insurance_claim
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getVehicleType() is used to get the list of data 
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleInsuranceClaim($conditions='') {
       $query = "	SELECT	vic.*,
							b.busNo,
							vt.vehicleTypeId
					FROM	vehicle_insurance_claim vic,
							bus b,
							vehicle_type vt
					WHERE	vic.busId = b.busId
					AND		vt.vehicleTypeId = b.vehicleTypeId
							$conditions";
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
    public function getInsuranceClaimList($conditions='', $limit = '', $orderBy='busId') {
       
		$query = "	SELECT	vic.*,
							b.busNo
					FROM	vehicle_insurance_claim vic, 
							bus b
					WHERE	b.busId = vic.busId
							$conditions 
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
    
    public function getTotalInsuranceClaim($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	vehicle_insurance_claim vic, 
							bus b
					WHERE	b.busId = vic.busId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getInsuranceDate() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function checkInsuranceDate($insuranceDate,$busNo) {
    
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	bus_insurance 
					WHERE	('$insuranceDate' BETWEEN lastInsuranceDate AND insuranceDueDate)
					AND		busId = '$busNo'
					$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//checkBus() is used to get total no. of records
//Author : Jaineesh
// Created on : 26.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getNCBValues($busId) {
    
       $query = "	SELECT	MAX(insuranceId), 
							MAX(ncb) AS ncb
					FROM	bus_insurance
					WHERE	busId = $busId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>
<?php 
// $History: InsuranceClaimManager.inc.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/08/10    Time: 6:47p
//Updated in $/Leap/Source/Model
//fixed bug nos. 0002810, 0002808, 0002807, 0002806, 0002803, 0002804
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:45a
//Created in $/Leap/Source/Model
//new model file for vehicle insurance claim
//
//
?>