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
  
  class VehicleTyreManager {
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
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addVehicleTyre() {
        global $REQUEST_DATA;
		$tyreNumber = trim($REQUEST_DATA['tyreNumber']);
		$manufacturer = trim($REQUEST_DATA['manufacturer']);
		$modelNumber = trim($REQUEST_DATA['modelNumber']);
		$purchaseDate = trim($REQUEST_DATA['purchaseDate']);
		//$isActive = trim($REQUEST_DATA['isActive']);


		$query = "	INSERT INTO tyre_master (tyreNumber,manufacturer,modelNumber,purchaseDate,isActive) 
					VALUES ('$tyreNumber','$manufacturer','$modelNumber','$purchaseDate',1)";
        
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//addTyreHistory() is used to add new record in database.
// Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addTyreHistory($tyreId) {
        global $REQUEST_DATA;
		$busId = trim($REQUEST_DATA['busNo']);
		$busReading = trim($REQUEST_DATA['busReading']);
		$purchaseDate = trim($REQUEST_DATA['purchaseDate']);
		//$usedAsMainTyre = trim($REQUEST_DATA['usedAsMainTyre']);
		$addTyreType = trim($REQUEST_DATA['addTyreType']);
		$placementReason = trim($REQUEST_DATA['placementReason']);

		 $query = "	INSERT INTO tyre_history (tyreId,busId,busReadingOnInstallation,placementDate,usedAsMainTyre,placementReason) 
					VALUES ($tyreId,$busId,$busReading,'$purchaseDate',$addTyreType,'$placementReason')";
        
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
//
//addTyreHistory() is used to add new record in database.
// Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateVehicleTyreHistory() {
        global $REQUEST_DATA;
		$stockTyreNo = $REQUEST_DATA['stockTyreNo'];
		$stockRegnNo = $REQUEST_DATA['stockRegnNo'];
		$stockVehicleReading = $REQUEST_DATA['stockVehicleReading'];
		$replacementDate = $REQUEST_DATA['replacementDate'];
		$replacementReason = $REQUEST_DATA['replacementReason'];
		$addStockTyreType = $REQUEST_DATA['addStockTyreType'];

		 $query = "	UPDATE	tyre_history 
					SET		busId = $stockRegnNo,
							busReadingOnInstallation = $stockVehicleReading,
							placementDate = '$replacementDate',
							usedAsMainTyre = $addStockTyreType,
							placementReason = '$replacementReason'
					WHERE	tyreId = $stockTyreNo";
        
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//updateStockVehicleTyre() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateVehicleTyre() {
        global $REQUEST_DATA;

		$addStockTyreId = $REQUEST_DATA['addStockTyreId'];

	 $query = "	UPDATE	tyre_master
				SET		isActive = 0
				WHERE	tyreId = $addStockTyreId ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

    }

	//-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateStockVehicleTyre() {
        global $REQUEST_DATA;

		$addStockTyreId = $REQUEST_DATA['stockTyreNo'];

	 $query = "	UPDATE	tyre_master
				SET		isActive = 1
				WHERE	tyreId = $addStockTyreId ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

    }
    
    //-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editVehicleTyre($tyreId) {
        global $REQUEST_DATA;

		$tyreNumber = trim($REQUEST_DATA['tyreNumber']);
		$manufacturer = trim($REQUEST_DATA['manufacturer']);
		$modelNumber = trim($REQUEST_DATA['modelNumber']);
		$purchaseDate = trim($REQUEST_DATA['purchaseDate']);
		//$isActive = trim($REQUEST_DATA['isActive']);

     
	 $query = "	UPDATE	tyre_master 
				SET		tyreNumber = '$tyreNumber',
						manufacturer = '$manufacturer',
						modelNumber = '$modelNumber',
						purchaseDate = '$purchaseDate'
				WHERE	tyreId = $tyreId ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

        //return SystemDatabaseManager::getInstance()->runAutoUpdate('tyre_master', array('tyreNumber','manufacturer','modelNumber','purchaseDate','isActive'), array(trim($REQUEST_DATA['tyreNumber']),trim($REQUEST_DATA['manufacturer']),trim($REQUEST_DATA['modelNumber']),trim($REQUEST_DATA['purchaseDate']),trim($REQUEST_DATA['isActive'])) , "tyreId=$id" );
    }


//-------------------------------------------------------------------------------
//
//updateDamageVehicleTyre() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateDamageVehicleTyre($addTyreId) {
        global $REQUEST_DATA;

		//$isActive = trim($REQUEST_DATA['isActive']);

     
	 $query = "	UPDATE	tyre_master 
				SET		isActive = 0
				WHERE	tyreId = $addTyreId ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

    }

	//-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateExtraVehicleTyre($addTyreId) {
        global $REQUEST_DATA;

		//$isActive = trim($REQUEST_DATA['isActive']);

     
	 $query = "	UPDATE	tyre_master 
				SET		isActive = 2
				WHERE	tyreId = $addTyreId ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

    }

//-------------------------------------------------------------------------------
//
//editTyre() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editMainTyre($spareTyre) {
        global $REQUEST_DATA;

	  $query = "	UPDATE	tyre_history 
					SET		usedAsMainTyre = 1
					WHERE	tyreId = $spareTyre ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

        //return SystemDatabaseManager::getInstance()->runAutoUpdate('tyre_master', array('tyreNumber','manufacturer','modelNumber','purchaseDate','isActive'), array(trim($REQUEST_DATA['tyreNumber']),trim($REQUEST_DATA['manufacturer']),trim($REQUEST_DATA['modelNumber']),trim($REQUEST_DATA['purchaseDate']),trim($REQUEST_DATA['isActive'])) , "tyreId=$id" );
    }

//-------------------------------------------------------------------------------
//
//editSpareTyre() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editSpareTyre($mainTyre) {
        global $REQUEST_DATA;

	  $query = "	UPDATE	tyre_history
					SET		usedAsMainTyre = 0
					WHERE	tyreId = $mainTyre ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

        //return SystemDatabaseManager::getInstance()->runAutoUpdate('tyre_master', array('tyreNumber','manufacturer','modelNumber','purchaseDate','isActive'), array(trim($REQUEST_DATA['tyreNumber']),trim($REQUEST_DATA['manufacturer']),trim($REQUEST_DATA['modelNumber']),trim($REQUEST_DATA['purchaseDate']),trim($REQUEST_DATA['isActive'])) , "tyreId=$id" );
    }

//-------------------------------------------------------------------------------
//
//editVehicleType() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
					AND		b.isActive = 1
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
//
//getVehicleTyre() is used to get the list of data 
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getCheckVehicleTyre($conditions='') {
     
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
//getVehicleTyreHistory() is used to get the list of data 
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleTyreHistory($conditions='') {
     
          $query = "SELECT 
							COUNT(th.tyreId) as totalTyres
					FROM	tyre_history th,
							bus b,
							tyre_master tm
					WHERE	th.busId = b.busId
					AND		th.tyreId = tm.tyreId
					AND		tm.isActive = 1
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getVehicleTyre() is used to get the list of data 
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleTyreType($conditions='') {
     
        $query = "SELECT 
							(vt.mainTyres+vt.spareTyres) AS totalVehicleTypeTyre
					FROM	vehicle_type vt,
							bus b
					WHERE	b.vehicleTypeId = vt.vehicleTypeId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getVehicleTyreList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleTyreList($conditions='', $limit = '', $orderBy='tyreNumber') {
     
          $query = "	SELECT 
							tm.tyreId,
							tm.tyreNumber,
							tm.manufacturer,
							tm.modelNumber,
							tm.purchaseDate,
							th.busReadingOnInstallation,
							th.placementReason,
							b.busId,
							b.busNo,
							vt.vehicleType,
							if(tm.isActive = 1,'Yes','No') AS isActive,
							if(th.usedAsMainTyre = 1,'Main','Spare') AS usedAsMainTyre
					FROM	tyre_master tm,
							tyre_history th,
							vehicle_type vt,
							bus b
					WHERE	tm.tyreId = th.tyreId
					AND		th.busId = b.busId
					AND		tm.isActive = 1
					AND		b.isActive = 1
					AND		vt.vehicleTypeId = b.vehicleTypeId
							$conditions
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     //-------------------------------------------------------------------------------
//
//getTotalVehicleTyre() is used to get total no. of records
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalVehicleTyre($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
			        FROM	tyre_master tm,
							tyre_history th,
							bus b
					WHERE	tm.tyreId = th.tyreId
					AND		th.busId = b.busId
					AND		tm.isActive = 1
					AND		b.isActive = 1
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTyreHistoryBus() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleNos($vehicleTypeId) {
     
        $query = "	
					SELECT 
								b.busId, b.busNo
					FROM		bus b, vehicle_type vt
					WHERE		b.vehicleTypeId = vt.vehicleTypeId
					AND			b.isActive = 1
					AND			b.vehicleTypeId = $vehicleTypeId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getTyreHistoryBus() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleSpareTyres($busId,$typeId) {
     
        $query = "	
					SELECT 
								tm.tyreId,
								tm.tyreNumber
					FROM		tyre_master tm, tyre_history th
					WHERE		tm.tyreId = th.tyreId
					AND			tm.isActive = 1
					AND			th.busId = $busId
					AND			th.usedAsMainTyre = $typeId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getTyreHistoryBus() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleMainTyres($busId,$typeId) {
     
        $query = "	
					SELECT 
								tm.tyreId,
								tm.tyreNumber
					FROM		tyre_master tm, tyre_history th
					WHERE		tm.tyreId = th.tyreId
					AND			tm.isActive = 1
					AND			th.busId = $busId
					AND			th.usedAsMainTyre = $typeId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getTyreHistoryBus() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getVehicleExtraTyres() {
     
        $query = "	
					SELECT 
								tyreId,
								tyreNumber
					FROM		tyre_master
					WHERE		tyre_master.isActive = 2";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}
?>
<?php 
// $History: VehicleTyreManager.inc.php $
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 2/04/10    Time: 6:27p
//Updated in $/Leap/Source/Model
//fixed issues
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 1/15/10    Time: 4:39p
//Updated in $/Leap/Source/Model
//make changes and list will be showing not close.
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Model
//fixed bug in fleet management
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/07/10    Time: 2:44p
//Updated in $/Leap/Source/Model
//fixed bug for fleet management
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Model
//fixed bug in fleet management
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Model
//fixed bug on fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Model
//fixed bug during self testing
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