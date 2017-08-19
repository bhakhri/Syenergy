<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CityManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addCity() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('city', array('cityCode','cityName','stateId'), array(strtoupper($REQUEST_DATA['cityCode']),$REQUEST_DATA['cityName'],$REQUEST_DATA['states']) );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editCity($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('city', array('cityCode','cityName','stateId'), array(strtoupper($REQUEST_DATA['cityCode']),$REQUEST_DATA['cityName'],$REQUEST_DATA['states']), "cityId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getCity($conditions='') {
     
        $query = "SELECT cityId,cityCode,cityName,stateId 
        FROM city
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS CITYID EXISTS IN INSTITUTE TABLE OR NOT(DELETE CHECK)
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInInstitute($cityId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM institute 
        WHERE cityId=$cityId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteCity($cityId) {
     
        $query = "DELETE 
        FROM city 
        WHERE cityId=$cityId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getCityList($conditions='', $limit = '', $orderBy=' ct.cityName') {
     
        $query = "SELECT ct.cityId, ct.cityCode, ct.cityName, ct.stateId, st.stateName 
        FROM city ct, states st
        WHERE ct.stateId=st.stateId $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalCity($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM city ct, states st
        WHERE ct.stateId=st.stateId $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
?>
<?php
// $History: CityManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:22p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:52p
//Updated in $/Leap/Source/Model
//Complete Comment Insertion
?>
