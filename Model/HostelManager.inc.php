<?php
//-------------------------------------------------------------------------------
//
//HostelManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class HostelManager {
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
//addHostel() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addHostel() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('hostel', array('hostelName','hostelCode','roomTotal','hostelType','floorTotal','totalCapacity','wardenName','wardenContactNo'), 
        array($REQUEST_DATA['hostelName'],strtoupper($REQUEST_DATA['hostelCode']), $REQUEST_DATA['roomTotal'], $REQUEST_DATA['hostelType'], $REQUEST_DATA['floorTotal'], $REQUEST_DATA['totalCapacity'], $REQUEST_DATA['wardenName'], $REQUEST_DATA['wardenContactNo']) );
	}
    
    //-------------------------------------------------------------------------------
//
//editHostel() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editHostel($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('hostel', array('hostelName','hostelCode','roomTotal','hostelType','floorTotal','totalCapacity','wardenName','wardenContactNo'), 
        array($REQUEST_DATA['hostelName'], strtoupper($REQUEST_DATA['hostelCode']), $REQUEST_DATA['roomTotal'], $REQUEST_DATA['hostelType'], $REQUEST_DATA['floorTotal'], $REQUEST_DATA['totalCapacity'],$REQUEST_DATA['wardenName'], $REQUEST_DATA['wardenContactNo']), "hostelId=$id" );
    }    
    
    //-------------------------------------------------------------------------------
//
//getHostel() is used to get the data.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getHostel($conditions='') {
     
       $query = "SELECT 
						hostelId, 
						hostelName, 
						hostelCode, 
						roomTotal, 
						hostelType, 
						floorTotal, 
						totalCapacity,
                        IFNULL(wardenName,'') AS wardenName,
                        IFNULL(wardenContactNo,'') AS  wardenContactNo
				FROM	hostel 
						$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS hostelId EXISTS IN "hostel_room" TABLE OR NOT(DELETE CHECK)
//
//$hostelId :hostelId of the hostel
// Author :Gurkeerat Sidhu 
// Created on : (16.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInHostelRoom($hostelId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM hostel_room 
        WHERE hostelId=$hostelId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
//-------------------------------------------------------------------------------
//
//deleteDesignation() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    public function deleteHostel($hostelId) {
     
        $query = "DELETE 
        FROM hostel 
        WHERE hostelId=$hostelId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    //-------------------------------------------------------------------------------
//
//getHostelList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getHostelList($conditions='', $limit = '', $orderBy=' hostelName') {
     
       $query = "	SELECT 
							hostelId,
							hostelName, 
							hostelCode,
							roomTotal,
							hostelType,
							floorTotal,
							totalCapacity, 
                            IFNULL(wardenName,'') AS wardenName,
                            IFNULL(wardenContactNo,'') AS wardenContactNo  
					FROM	hostel
							$conditions
							ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getTotalHostel() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalHostel($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM hostel 
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
//-------------------------------------------------------------------------------
//
//getTotalHostelRoomType() is used to get total no. of records
//Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function checkExistanceHostelRoom($conditions='') {
    
       $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	hostel_room_type_detail
							$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>
<?php
  //$History : $
?>