<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "building" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BuildingManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BuildingManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BuildingManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
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
// THIS FUNCTION IS USED FOR ADDING A Building
//
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addBuilding() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('building', array('buildingName','abbreviation'), array(strtoupper(trim($REQUEST_DATA['buildingName'])),trim($REQUEST_DATA['abbreviation'])) );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Building
//
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editBuilding($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('building', array('buildingName','abbreviation'), array(strtoupper(trim($REQUEST_DATA['buildingName'])),trim($REQUEST_DATA['abbreviation'])), "buildingId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Building LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getBuilding($conditions='') {
     
        $query = "SELECT buildingId,buildingName,abbreviation
        FROM building
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS buildingId EXISTS IN "block" TABLE OR NOT(DELETE CHECK)
//
//$buildingId :buildingId of the Building
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInBlock($buildingId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM block 
        WHERE buildingId=$buildingId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS buildingId EXISTS IN "room" TABLE OR NOT(DELETE CHECK)
//
//$buildingId :buildingId of the Building
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInRoom($buildingId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM room 
        WHERE buildingId=$buildingId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Building
//
//$buildingId :buildingId of the Building
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBuilding($buildingId) {
     
        $query = "DELETE 
        FROM building 
        WHERE buildingId=$buildingId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Building LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getBuildingList($conditions='', $limit = '', $orderBy=' bu.buildingName') {
     
        $query = "SELECT bu.buildingId,bu.buildingName,bu.abbreviation
        FROM building bu $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Buildings
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalBuilding($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM building bu $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}

// $History: BuildingManager.inc.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 13/08/09   Time: 12:00
//Updated in $/LeapCC/Model
//Added the check in during building deletion---If this building is used
//in "room" table then it should not be deleted
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:20p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 6:54p
//Updated in $/Leap/Source/Model
//Created Building Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:28p
//Created in $/Leap/Source/Model
//Initial Checkin
?>
