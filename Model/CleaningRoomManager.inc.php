<?php

//-------------------------------------------------------------------------------
//
//CleaningRoomManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CleaningRoomManager {
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
//addCleaningRoom() is used to add new record in database.
// Author : Jaineesh
// Created on : 30.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addCleaningRoom() {
		global $REQUEST_DATA;
		global $sessionHandler;

		return SystemDatabaseManager::getInstance()->runAutoInsert('hostel_cleaning_record', array('tempEmployeeId','Dated','hostelId','toiletsCleaned','noOfRoomsCleaned','attachedRoomToiletsCleaned','dryMoppingInSqMeter','wetMoppingInSqMeter','roadCleanedInSqMeter','noOfGarbageBinsDisposal','noOfHoursWorked'), array($REQUEST_DATA['safaiwala'],$REQUEST_DATA['date'],$REQUEST_DATA['hostelName'],$REQUEST_DATA['toiletsNo'],$REQUEST_DATA['roomsNo'],$REQUEST_DATA['roomsAttachedBath'],$REQUEST_DATA['dryMopping'],$REQUEST_DATA['wetMopping'],$REQUEST_DATA['areaRoad'],$REQUEST_DATA['garbageBins'],$REQUEST_DATA['noOfhrs']));
	}
    //-------------------------------------------------------------------------------
//
//editHostelRoom() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editCleaningRoom($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('hostel_cleaning_record', array('tempEmployeeId','Dated','hostelId','toiletsCleaned','noOfRoomsCleaned','attachedRoomToiletsCleaned','dryMoppingInSqMeter','wetMoppingInSqMeter','roadCleanedInSqMeter','noOfGarbageBinsDisposal','noOfHoursWorked'), array($REQUEST_DATA['safaiwala'],$REQUEST_DATA['date'],$REQUEST_DATA['hostelName'],$REQUEST_DATA['toiletsNo'],$REQUEST_DATA['roomsNo'],$REQUEST_DATA['roomsAttachedBath'],$REQUEST_DATA['dryMopping'],$REQUEST_DATA['wetMopping'],$REQUEST_DATA['areaRoad'],$REQUEST_DATA['garbageBins'],$REQUEST_DATA['noOfhrs']), "cleanId=$id" );
    }
//-------------------------------------------------------------------------------
//
//getHostelRoom() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getCleaningRoom($conditions='') {
     
    $query = "	SELECT 
							hcr.*,
							hs.hostelId,
							hs.hostelName,
							et.tempEmployeeId,
							et.tempEmployeeName
					FROM	hostel_cleaning_record hcr,
							employee_temp et,
							hostel hs
					WHERE	hcr.hostelId = hs.hostelId 
					AND		hcr.tempEmployeeId = et.tempEmployeeId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
     
//-------------------------------------------------------------------------------
//
//deleteHostelRoom() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   
    public function deleteHostelRoomTypeDetail($id) {
     
       $query = "	DELETE 
					FROM hostel_cleaning_record 
					WHERE cleanId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
//
//getHostelRoomList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getCleaningRoomDetailList($conditions='', $limit = '', $orderBy=' hr.roomName') {
     
  $query = "	SELECT 
							hcr.*,
							hs.hostelName,
							et.tempEmployeeName
					FROM	hostel_cleaning_record hcr,
							employee_temp et,
							hostel hs
					WHERE	hcr.hostelId = hs.hostelId
					AND		hcr.tempEmployeeId = et.tempEmployeeId
							$conditions
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
//
//getTotalHostelRoom() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getTotalCleaningRoomDetail($conditions='') {
    
     $query = "		SELECT	COUNT(*) AS totalRecords
					FROM	hostel_cleaning_record hcr,
							employee_temp et,
							hostel hs
					WHERE	hcr.hostelId = hs.hostelId
					AND		hcr.tempEmployeeId = et.tempEmployeeId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getTotalCleaningHistory() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getTotalCleaningHistoryDetail($conditions) {
    
   $query = "		SELECT	COUNT(*) AS totalRecords
					FROM	hostel_cleaning_record hcr,
							employee_temp et,
							hostel hs
					WHERE	hcr.hostelId = hs.hostelId
					AND		hcr.tempEmployeeId = et.tempEmployeeId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getCleaningHistoryList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getCleaningHistoryList($conditions,$orderBy=' hr.roomName') {
     
 $query = "	SELECT 
							hcr.*,
							hs.hostelName,
							et.tempEmployeeName
					FROM	hostel_cleaning_record hcr,
							employee_temp et,
							hostel hs
					WHERE	hcr.hostelId = hs.hostelId
					AND		hcr.tempEmployeeId = et.tempEmployeeId
							$conditions
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getTotalCleaningHistory() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getTotalSafaiwalaAbsentDetail($conditions) {
    
 echo $query = "		SELECT	COUNT(*) AS totalRecords
					FROM	hostel_cleaning_record hcr,
							employee_temp et
					WHERE	hcr.tempEmployeeId = et.tempEmployeeId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getCleaningHistoryList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getSafaiwalaAbsentList($conditions,$orderBy=' hr.tempEmployeeId') {
     
 echo $query = "		SELECT 
						hcr.tempEmployeeId,
						hcr.Dated,
						et.tempEmployeeName,
						COUNT(hcr.Dated) AS cntDate
				FROM	hostel_cleaning_record hcr,
						employee_temp et
				WHERE	hcr.tempEmployeeId = et.tempEmployeeId
						$conditions
						ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


}
?>

<?php
  //$History: CleaningRoomManager.inc.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:07p
//Updated in $/LeapCC/Model
//make the changes as per discussion with pushpender sir
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 3:32p
//Updated in $/LeapCC/Model
//remove mendatory fields 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:28p
//Created in $/LeapCC/Model
//new queries for cleaning room manager
//
//

?>