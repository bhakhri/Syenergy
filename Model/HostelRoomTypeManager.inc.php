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

class HostelRoomTypeManager {
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
//addHostelRoomType() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addHostelRoomType() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('hostel_room_type', array('roomType','roomAbbr'), array($REQUEST_DATA['roomType'], $REQUEST_DATA['roomAbbr']));
	}
    //-------------------------------------------------------------------------------
//
//editHostelRoom() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editHostelRoomType($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('hostel_room_type', array('hostelRoomTypeId','roomType','roomAbbr'), array($REQUEST_DATA['hostelRoomTypeId'],$REQUEST_DATA['roomType'],$REQUEST_DATA['roomAbbr']), "hostelRoomTypeId=$id" );
    }    
    //-------------------------------------------------------------------------------
//
//getHostelRoom() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelRoomType($conditions='') {
     
     $query = "	SELECT	hostelRoomTypeId,
							roomType,
							roomAbbr 
					FROM	hostel_room_type
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//checkExistanceHostelRoomType() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function checkExistanceHostelRoomType($conditions='') {
    
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	hostel_room_type_detail
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//checkExistanceInHostelRoom() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function checkExistanceInHostelRoom($conditions='') {
    
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	hostel_room
							$conditions ";
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
    public function deleteHostelRoomType($hostelRoomTypeId) {
     
        $query = "	DELETE 
					FROM	hostel_room_type 
					WHERE	hostelRoomTypeId=$hostelRoomTypeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getHostelRoomTypeList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostelRoomTypeList($conditions='', $limit = '', $orderBy='roomType') {
     
        $query = "	SELECT	hostelRoomTypeId,
							roomType,
							roomAbbr
					FROM	hostel_room_type 
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
    public function getTotalHostelRoomType($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	hostel_room_type
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>

<?php
//$History: HostelRoomTypeManager.inc.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:46p
//Updated in $/LeapCC/Model
//fixed bug nos.0000622,0000623,0000624,0000611
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Model
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:49a
//Created in $/LeapCC/Model
//
//
?>