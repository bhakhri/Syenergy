<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "room_type" table
// Author :Gurkeerat Sidhu 
// Created on : (19.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class RoomTypeManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "RoomTypeManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "RoomTypeManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A RoomType
//
// Author :Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addRoomType() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO room_type (roomType,abbr) 
      VALUES('".addslashes($REQUEST_DATA['roomType'])."','".addslashes(strtoupper($REQUEST_DATA['abbr']))."')"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING RoomType
//
//$id:roomTypeId
// Author :Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editRoomType($id) {
        global $REQUEST_DATA;
        
     $query="UPDATE room_type SET roomType ='".addslashes($REQUEST_DATA['roomType'])."',abbr ='".addslashes(strtoupper($REQUEST_DATA['abbr']))."'
        WHERE   roomTypeId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING RoomType LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getRoomType($conditions='') {
        $query = "SELECT * 
        FROM room_type
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Room Type LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getRoomTypeList($conditions='',  $orderBy=' roomType', $limit = '') {
     
        $query = "	SELECT * 
					FROM room_type  
					$conditions
			        ORDER BY $orderBy 
					$limit";
        
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Room Type
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalRoomType($conditions='') {
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM room_type 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Room Type
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkRoomType($conditions='') {
         
        $query = "SELECT count(roomTypeId) AS foundRecord 
        FROM  room_type 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
   
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING Room Type
//
//$Id :roomTypeId
// Author :Gurkeerat Sidhu 
// Created on : (19.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteRoomType($id) {
     
        $query = "DELETE 
        FROM room_type
        WHERE roomTypeId=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

    
     public function checkInRoom($roomTypeId='') {
         
        $query = "SELECT count(*) AS cnt FROM  room  WHERE roomTypeId=$roomTypeId  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


}

?>
