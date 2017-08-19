<?php

//-------------------------------------------------------------------------------
//
//HostelRoomTypeDetailManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class HostelRoomTypeDetailManager {
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
//addHostelRoomTypeDetail() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addHostelRoomTypeDetail() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('hostel_room_type_detail', array('hostelId','hostelRoomTypeId','Capacity','noOfBeds','attachedBath','airConditioned','internetFacility','noOfFans','noOfLights'), array($REQUEST_DATA['hostelName'], $REQUEST_DATA['roomType'],$REQUEST_DATA['Capacity'],$REQUEST_DATA['noOfBeds'],$REQUEST_DATA['attachBathroom'],$REQUEST_DATA['airConditioned'],$REQUEST_DATA['internetFacility'],$REQUEST_DATA['noOfFans'],$REQUEST_DATA['noOfLights']));
	}
    //-------------------------------------------------------------------------------
//
//editHostelRoomTypeDetail() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editHostelRoomTypeDetail($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('hostel_room_type_detail', array('hostelId','hostelRoomTypeId','Capacity','noOfBeds','attachedBath','airConditioned','internetFacility','noOfFans','noOfLights'), array($REQUEST_DATA['hostelName'], $REQUEST_DATA['roomType'],$REQUEST_DATA['Capacity'],$REQUEST_DATA['noOfBeds'],$REQUEST_DATA['attachBathroom'],$REQUEST_DATA['airConditioned'],$REQUEST_DATA['internetFacility'],$REQUEST_DATA['noOfFans'],$REQUEST_DATA['noOfLights']), "roomTypeInfoId=$id" );
    }    
    //-------------------------------------------------------------------------------
//
//getHostelRoomTypeDetail() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelRoomTypeDetail($conditions='') {
     
	$query = "		SELECT	h.hostelName,
							hrt.roomType,
							hrtd.Capacity,
							hrtd.noOfBeds,
							hrtd.attachedBath,
							hrtd.airConditioned,
							hrtd.internetFacility,
							hrtd.noOfFans,
							hrtd.noOfLights,
							hrtd.hostelRoomTypeId,
							hrtd.roomTypeInfoId,
							hrtd.hostelId
					FROM	hostel_room_type_detail hrtd,
							hostel h,
							hostel_room_type hrt
					WHERE	hrtd.hostelId = h.hostelId
					AND		hrt.hostelRoomTypeId = hrtd.hostelRoomTypeId
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
    public function deleteHostelRoomTypeDetail($roomTypeInfoId) {
     
       $query = "	DELETE
					FROM	hostel_room_type_detail 
					WHERE	roomTypeInfoId=$roomTypeInfoId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getHostelRoomTypeDetailList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelRoomTypeDetailList($conditions='', $limit = '', $orderBy='') {
     
  $query = "	SELECT	
							hrtd.hostelId,
							hrtd.roomTypeInfoId,
							hrtd.hostelRoomTypeId,
							h.hostelName,
							hrt.roomType,
							hrtd.Capacity,
							hrtd.noOfBeds,
							if(hrtd.attachedBath=1,'Yes','No') as attachedBath,
							if(hrtd.airConditioned=1,'Yes','No') as airConditioned,
							if(hrtd.internetFacility=1,'Yes','No') as internetFacility,
							if(hrtd.noOfFans = 0, '-', hrtd.noOfFans) as noOfFans,
							if(hrtd.noOfLights = 0, '-', hrtd.noOfLights) as noOfLights
					FROM	hostel_room_type_detail hrtd,
							hostel h,
							hostel_room_type hrt
					WHERE	hrtd.hostelId = h.hostelId
					AND		hrt.hostelRoomTypeId = hrtd.hostelRoomTypeId 
							$conditions 
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    //-------------------------------------------------------------------------------
//
//getTotalHostelRoomType() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function getTotalHostelRoomTypeDetail($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	hostel_room_type_detail hrtd,
							hostel h,
							hostel_room_type hrt
					WHERE	hrtd.hostelId = h.hostelId
					AND		hrt.hostelRoomTypeId = hrtd.hostelRoomTypeId
							$conditions ";
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
    
    public function checkForRoomTypeDetailMapping($roomTypeInfoId){
    	$query = "SELECT	
    				COUNT(hostelRoomId) AS cnt 
    			FROM	`hostel_room` hr, `hostel_room_type_detail` hrtd 
    			WHERE	hr.hostelId = hrtd.hostelId
    			AND	hr.hostelRoomTypeId = hrtd.hostelRoomTypeId
    			AND	hr.roomCapacity = hrtd.capacity
    			AND	hrtd.roomTypeInfoId = $roomTypeInfoId";
    	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>

<?php
//$History: HostelRoomTypeDetailManager.inc.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Model
//fixed bugs during self testing
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/12/09    Time: 5:18p
//Updated in $/LeapCC/Model
//fixed bugs Issues Build cc0001.doc
//(Nos.991,992,993,994,995,996,997,998,999,1000)
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Model
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:55a
//Created in $/LeapCC/Model
//new sql file to excute all the queries regarding add, edit, delete or
//list
//
//
?>
