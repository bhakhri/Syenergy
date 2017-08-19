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
  
  class TyreRetreadingManager {
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
    public function addTyreRetreading($tyreId,$busId) {
        global $REQUEST_DATA;
		global $sessionHandler;

		$retreadingDate = trim($REQUEST_DATA['retreadingDate']);
		$reading = trim($REQUEST_DATA['reading']);
		$replacementReason = trim(addslashes($REQUEST_DATA['replacementReason']));
		$userId = $sessionHandler->getSessionVariable('UserId');

		$query = "	INSERT INTO tyre_retreading (tyreId,busId,retreadingDate,totalRun,retreadingRecommendedBy,reason) 
					VALUES ($tyreId,$busId,'$retreadingDate',$reading,$userId,'$replacementReason')";
        
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
    public function updateTyreRetreading($tyreId,$busId) {
        global $REQUEST_DATA;
		$retreadingId = $REQUEST_DATA['retreadingId'];
		$retreadingDate = trim($REQUEST_DATA['retreadingDate']);
		$reading = trim($REQUEST_DATA['reading']);
		$replacementReason = trim(addslashes($REQUEST_DATA['replacementReason']));
     
        $query = "	UPDATE tyre_retreading 
					SET tyreId = $tyreId,
						busId = $busId,
						retreadingDate = '$retreadingDate',
						totalRun = $reading,
						reason = '$replacementReason'
					WHERE retreadingId = $retreadingId";
        
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
    public function deleteTyreRetreading($retreadingId) {
     
        $query = "	DELETE 
					FROM tyre_retreading 
					WHERE retreadingId=$retreadingId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
    //-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTyreRetreadingList($conditions='', $limit = '', $orderBy='tyreNumber') {
     
        $query = "	SELECT	tr.retreadingId,
							tm.tyreNumber,
							b.busNo,
							tr.retreadingDate,
							tr.totalRun
					FROM	tyre_retreading tr,
							bus b,
							tyre_master tm
					WHERE	tr.tyreId = tm.tyreId
					AND		tr.busId = b.busId
					AND		tm.isActive = 1
					AND		b.isActive = 1
							$conditions 
							ORDER BY $orderBy $limit 

					";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	

	//-------------------------------------------------------------------------------
//
//getVehicleTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getRetreading($conditions='', $limit = '', $orderBy='vehicleType') {
     
        $query = "	SELECT	tr.retreadingId,
							tm.tyreNumber,
							b.busNo,
							tr.retreadingDate,
							tr.totalRun,
							tr.reason
					FROM	tyre_retreading tr,
							bus b,
							tyre_master tm
					WHERE	tr.tyreId = tm.tyreId
					AND		tr.busId = b.busId
							$conditions 
					";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
     //-------------------------------------------------------------------------------
//
//getTotalVehicleType() is used to get total no. of records
//Author : Jaineesh
// Created on : 24.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalTyreRetreading($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	tyre_retreading tr,
							bus b,
							tyre_master tm
					WHERE	tr.tyreId = tm.tyreId
					AND		tr.busId = b.busId 
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
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTyreHistoryBus($tyreNo) {
     
        $query = "	
			SELECT 
						th.busId, b.busNo, th.tyreId
			FROM		tyre_history th, bus b, tyre_master tm
			WHERE		th.busId = b.busId
			AND			th.tyreId = tm.tyreId
			AND			tm.tyreNumber = '$tyreNo'
			AND			tm.isActive = 1
			AND			b.isActive = 1
			order by	historyId desc limit 0,1
		";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 //getTyreHistory() is used to get list of buses with which tyre has been used and how many times it has been retreaded before
   public function getTyreHistory($tyreNo) {
     
        $query = "    
            select bs.busNo,bs.busName,count(tr.retreadingId) as noOfRetreading
            from bus bs, tyre_history th, tyre_master tm, tyre_retreading tr
            where tm.tyreNumber='$tyreNo' 
            and th.tyreId=tm.tyreId
            and th.busId=bs.busId
            and tr.tyreId=tm.tyreId
            and tr.tyreId=th.tyreId
            group by bs.busName
            order by historyId;
        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//-------------------------------------------------------------------------------
//
//getTyreHistoryBus() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTyreRetreading($tyreNo) {
     
        $query = "	
			select 
						th.busId, b.busNo, th.tyreId
			from		tyre_history th, bus b, tyre_master tm
			where		th.busId = b.busId
			and			th.tyreId = tm.tyreId
			and			tm.tyreNumber = '$tyreNo'
			and			tm.isActive = 1
			order by	historyId desc limit 0,1
		";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
//
//getTyreHistoryBus() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTyreRetreadingReport($conditions='',$limit='',$orderBy='') {
     
        $query = "	
			select 
						b.busNo,
						tr.retreadingId,
						tr.retreadingDate,
						tr.totalRun,
						tr.reason
			from		bus b, 
						tyre_master tm,
						tyre_retreading tr
			where		tr.busId = b.busId
			and			b.isActive = 1
						$conditions
						ORDER BY $orderBy
		";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getTyreHistoryBus() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTyreList($tyreNo) {
     
        $query = "	
			SELECT 
						COUNT(*) AS tyreRecords
			FROM		bus b,
						tyre_master tm,
						tyre_history th
			WHERE		th.busId = b.busId
			AND			th.tyreId = tm.tyreId		
			AND			b.isActive = 1
			AND			tm.tyreNumber = '$tyreNo'
						$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------
//
//getTyreRetreadingReason() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 25.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTyreRetreadingReason($retreadingId) {
     
        $query = "	
			SELECT 
						reason
			FROM		tyre_retreading
			WHERE		retreadingId = $retreadingId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

       
}
?>
<?php 
// $History: TyreRetreadingManager.inc.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/03/10    Time: 10:14a
//Updated in $/Leap/Source/Model
//put new report tyre retreading
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 2/01/10    Time: 7:13p
//Updated in $/Leap/Source/Model
//Add new report for insurance due report
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Model
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/05/09   Time: 10:33a
//Updated in $/Leap/Source/Model
//Modification  in code for search
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:36p
//Created in $/Leap/Source/Model
//new model file for Tyre Retreading 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:47p
//Created in $/Leap/Source/Model
//new model file for vehicle type
//
?>