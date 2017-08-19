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
  
  class VehicleTaxManager {
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
    public function addVehicleTax() {
        global $REQUEST_DATA;
		$busId = trim($REQUEST_DATA['busNo']);
		$regnNoValidTill = trim($REQUEST_DATA['regnNoValidTill']);
		$passengerTaxValidTill = trim($REQUEST_DATA['passengerTaxValidTill']);
		$roadTaxValidTill = trim($REQUEST_DATA['roadTaxValidTill']);
		$pollutionCheckValidTill = trim($REQUEST_DATA['pollutionCheckValidTill']);
		$passingValidTill = trim($REQUEST_DATA['passingValidTill']);


		$query = "	INSERT INTO bus_entries (busId,busNoValidTill,passengerTaxValidTill,roadTaxValidTill,pollutionCheckValidTill,passingValidTill) 
					VALUES ('$busId','$regnNoValidTill','$passengerTaxValidTill','$roadTaxValidTill','$pollutionCheckValidTill','$passingValidTill')";
        
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//addTyreHistory() is used to add new record in database.
// Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addTyreHistory($tyreId) {
        global $REQUEST_DATA;
		$busId = trim($REQUEST_DATA['busNo']);
		$busReading = trim($REQUEST_DATA['busReading']);
		$purchaseDate = trim($REQUEST_DATA['purchaseDate']);
		$usedAsMainTyre = trim($REQUEST_DATA['usedAsMainTyre']);
		$placementReason = trim($REQUEST_DATA['placementReason']);

		 $query = "	INSERT INTO tyre_history (tyreId,busId,busReadingOnInstallation,placementDate,usedAsMainTyre,placementReason) 
					VALUES ($tyreId,$busId,$busReading,'$purchaseDate',$usedAsMainTyre,'$placementReason')";
        
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
    public function editVehicleTyre($tyreId,$busId) {
        global $REQUEST_DATA;

		$tyreNumber = trim($REQUEST_DATA['tyreNumber']);
		$manufacturer = trim($REQUEST_DATA['manufacturer']);
		$modelNumber = trim($REQUEST_DATA['modelNumber']);
		$purchaseDate = trim($REQUEST_DATA['purchaseDate']);
		$isActive = trim($REQUEST_DATA['isActive']);

     
	 $query = "	UPDATE	tyre_history 
				SET		tyreNumber = '$tyreNumber',
						manufacturer = '$manufacturer',
						modelNumber = '$modelNumber',
						purchaseDate = '$purchaseDate',
						isActive = '$isActive'
				WHERE	tyreId = $id ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

        //return SystemDatabaseManager::getInstance()->runAutoUpdate('tyre_master', array('tyreNumber','manufacturer','modelNumber','purchaseDate','isActive'), array(trim($REQUEST_DATA['tyreNumber']),trim($REQUEST_DATA['manufacturer']),trim($REQUEST_DATA['modelNumber']),trim($REQUEST_DATA['purchaseDate']),trim($REQUEST_DATA['isActive'])) , "tyreId=$id" );
    }

//-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editBus($id) {
        global $REQUEST_DATA;

		$busId = trim($REQUEST_DATA['busNo']);
		$busReading = trim($REQUEST_DATA['busReading']);
		$purchaseDate = trim($REQUEST_DATA['purchaseDate']);
		$usedAsMainTyre = trim($REQUEST_DATA['usedAsMainTyre']);
		$placementReason = trim($REQUEST_DATA['placementReason']);

     
	 $query = "	UPDATE	tyre_history
				SET		busId = $busId,
						busReadingOnInstallation = '$busReading',
						placementDate = '$purchaseDate',
						usedAsMainTyre = '$usedAsMainTyre',
						placementReason = '$placementReason'
				WHERE	tyreId = $id
					";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

        //return SystemDatabaseManager::getInstance()->runAutoUpdate('tyre_master', array('tyreNumber','manufacturer','modelNumber','purchaseDate','isActive'), array(trim($REQUEST_DATA['tyreNumber']),trim($REQUEST_DATA['manufacturer']),trim($REQUEST_DATA['modelNumber']),trim($REQUEST_DATA['purchaseDate']),trim($REQUEST_DATA['isActive'])) , "tyreId=$id" );
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
    public function getVehicleTaxList($conditions='', $limit = '', $orderBy='busId') {
     
        $query = "	SELECT	max(busNoValidTill) AS busNoValidTill,
							max(passengerTaxValidTill) AS passengerTaxValidTill,
							max(roadTaxValidTill) AS roadTaxValidTill,
							max(pollutionCheckValidTill) AS pollutionCheckValidTill,
							max(passingValidTill) AS passingValidTill,
							b.busNo
					FROM	bus_entries be,
							bus b
					WHERE	be.busId = b.busId
					AND		b.isActive = 1
							$conditions
							GROUP BY b.busId
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
    
    public function getTotalVehicleTax($conditions='') {
    
        $query = "	SELECT	max(busNoValidTill) AS busNoValidTill,
							max(passengerTaxValidTill) AS passengerTaxValidTill,
							max(roadTaxValidTill) AS roadTaxValidTill,
							max(pollutionCheckValidTill) AS pollutionCheckValidTill,
							max(passingValidTill) AS passingValidTill,
							b.busNo 
			        FROM	bus_entries be,
							bus b
					WHERE	be.busId = b.busId
					AND		b.isActive = 1
							$conditions
							GROUP BY b.busId";
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
// $History: VehicleTaxManager.inc.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Model
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:22p
//Created in $/Leap/Source/Model
//new model file for vehicle tax
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