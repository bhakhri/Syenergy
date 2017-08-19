<?php

//-------------------------------------------------------------------------------
//
//HostelRoomManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class HostelRoomManager {
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
//addHostelRoom() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addHostelRoom() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('hostel_room', array('hostelId',
																						'roomName',
																						'roomCapacity',
																						'hostelRoomTypeId',
																						'roomFloor'), 
																				  array($REQUEST_DATA['hostelId'],
																				  strtoupper($REQUEST_DATA['roomName']), 
																				             $REQUEST_DATA['roomCapacity'],
																				             $REQUEST_DATA['hostelroomtype'],
																				             $REQUEST_DATA['roomFloor']
																							 ));
	}
    //-------------------------------------------------------------------------------
//
//editHostelRoom() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editHostelRoom($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('hostel_room', array('hostelId',
        																				'roomName',
        																				'roomCapacity',
        																				'hostelRoomTypeId',
        																				'roomFloor'
																						),
        																		  array($REQUEST_DATA['hostelId'],
        																		  strtoupper($REQUEST_DATA['roomName']), 
        																		             $REQUEST_DATA['roomCapacity'],
        																		             $REQUEST_DATA['hostelroomtype'],
																							 $REQUEST_DATA['roomFloor']), 
        																		             "hostelRoomId=$id" );
    }    
//-------------------------------------------------------------------------------
//
//getHostelRoom() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelRoom($conditions='') {
     
       $query = "	SELECT	hr.hostelRoomId,
							hr.hostelId,
							hr.roomName,
							hr.roomCapacity,
							hr.hostelRoomTypeId,
							hr.roomFloor
					FROM	hostel_room hr,
							hostel_room_type hrt
					WHERE	hr.hostelRoomTypeId = hrt.hostelRoomTypeId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//deleteHostelRoom() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------   
    public function deleteHostelRoom($hostelRoomId) {
     
        $query = "DELETE 
        FROM hostel_room 
        WHERE hostelRoomId=$hostelRoomId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getHostelRoomList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelRoomList($filter='', $orderBy='',$limit = '') {
     
       $query = "	SELECT 
							hr.hostelRoomId,
							hr.hostelId,
							hs.hostelName,
							hr.roomName,
							hr.roomCapacity,
							hs.hostelName,
							hrt.roomType,
							hr.roomFloor
					FROM	hostel_room hr,
							hostel hs,
							hostel_room_type hrt
					WHERE	hr.hostelId=hs.hostelId
					AND		hr.hostelRoomTypeId = hrt.hostelRoomTypeId
							$conditions
							$filter
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getTotalHostelRoom() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getTotalHostelRoom($conditions='') {
    
        $query = "	SELECT COUNT(*) AS totalRecords 
					FROM	hostel_room hr,
							hostel hs,
							hostel_room_type hrt
					WHERE	hr.hostelId=hs.hostelId
					AND		hr.hostelRoomTypeId = hrt.hostelRoomTypeId
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getHostelRoomList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelRoomTypeDetail($hostelRoomTypeId,$hostelId) {
     
       $query = "	SELECT 
							capacity,
							Fee
					FROM	hostel_room_type_detail
					WHERE	hostelRoomTypeId = $hostelRoomTypeId
					AND		hostelId = $hostelId
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalHostel() is used to get the data.
//Author : Jaineesh
// Created on : 23.07.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalHostel($hostelId) {
     
      $query = "	SELECT	COUNT(roomName) AS cnt,
							SUM(roomCapacity) AS roomCapacity
					FROM	hostel_room hr
					WHERE	hr.hostelId =  $hostelId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getHostelCapacity() is used to get the data.
//Author : Jaineesh
// Created on : 23.07.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelCapacity($hostelId) {
     
      $query = "	SELECT	roomTotal, 
							totalCapacity
					FROM	hostel
					WHERE	hostelId =  $hostelId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getHostelCapacity() is used to get the data.
//Author : Jaineesh
// Created on : 23.07.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function getEditHostelCapacity($hostelRoomId,$hostelId='') {
     
      $query = "	SELECT	SUM(roomCapacity) AS roomCapacity
					FROM	hostel_room
					WHERE	hostelRoomId != '$hostelRoomId'
					AND	hostelId = '$hostelId'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
// used to get room types of hostel.
//Author : Nishu bindal
// Created on : 14.03.12
// Copyright 2008-2012: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function fetchRoomTypes($hostelId) {
     
      $query = "	SELECT		hrt.roomType, hrt.hostelRoomTypeId	
      					FROM	`hostel_room_type_detail` hrd , `hostel_room_type` hrt
      					WHERE	hrd.hostelRoomTypeId = hrt.hostelRoomTypeId
      					AND	hrd.hostelId = '$hostelId'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>

<?php
  //$History: HostelRoomManager.inc.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/26/09   Time: 4:20p
//Updated in $/LeapCC/Model
//done changes to save, edit & show hostel type according to hostel name
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:49p
//Created in $/LeapCC/Model
//put new functions getTotalHostel(), getHostelCapacity(),
//getEditHostelCapacity()
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/16/09    Time: 1:22p
//Updated in $/Leap/Source/Model
//Put new messages for hostel room type 
//Get capacity & rent by selecting room type
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/05/09    Time: 1:07p
//Updated in $/Leap/Source/Model
//add new field hostel room type
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 3/19/09    Time: 10:51a
//Updated in $/Leap/Source/Model
//fixed bug to give room name according to hostel name
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:33p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/03/08    Time: 8:04p
//Updated in $/Leap/Source/Model
//modified in table fields
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/01/08    Time: 11:58a
//Updated in $/Leap/Source/Model
?>
