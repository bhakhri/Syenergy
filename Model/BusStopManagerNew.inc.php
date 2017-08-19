<?php 
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busstop" TABLE
// Author :Nishu Bindal
// Created on : (22.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class BusStopManagerNew {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BusStopManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BusStopManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A BUSSTOP
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addBusStop($busStopCityId) {
	global $REQUEST_DATA;
        global $sessionHandler;

	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $stopName = add_slashes(trim($REQUEST_DATA['stopName']));
        $stopAbbr = add_slashes(trim($REQUEST_DATA['stopAbbr']));     
        $query = "INSERT INTO `bus_stop_new`  (busStopId,busStopCityId,stopName,stopAbbr,instituteId)
        			VALUES ('','$busStopCityId','$stopName','$stopAbbr','$instituteId')";
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
	}

	public function addBusStopCity($cityName){
		$query ="INSERT INTO `bus_stop_city` (busStopCityId,cityName) VALUES('','$cityName')"; 
		 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getBusStopCity($conditions){
		$query ="SELECT COUNT(busStopCityId) AS totalRecord FROM `bus_stop_city` $conditions";
		
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A BUSSTOP
//
//$id:busStopId
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editBusStop($id,$busStopCityId) {
        global $REQUEST_DATA;
        $stopName = add_slashes(trim($REQUEST_DATA['stopName']));
        $stopAbbr = add_slashes(trim($REQUEST_DATA['stopAbbr']));
    	
    	$query ="UPDATE	`bus_stop_new` 
    					SET busStopCityId = '$busStopCityId',
    					 stopName = '$stopName',
    					 stopAbbr = '$stopAbbr'
    			WHERE	busStopId = '$id'
    					";
    	   
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSSTOP LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getBusStop($conditions='') {
     
        $query = "SELECT bs.busStopId,bsc.cityName,bs.stopName,bs.stopAbbr,bs.busStopCityId 
       			 FROM `bus_stop_new` bs , `bus_route` br , `bus_stop_city` bsc
       			 WHERE	bs.busStopCityId = bsc.busStopCityId
                    
        $conditions"; 
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//**AS BUSSTOP TABLE IS INDEPENDENT NO NEED TO CHECK TO INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSSTOP
//
//$cityId :busStopid of the BusStop
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBusStop($busStopId) {
     
        $query = "DELETE 
        FROM `bus_stop_new` 
        WHERE busStopId=$busStopId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSSTOP LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getBusStopList($conditions='', $limit = '', $orderBy='cityName') {
        global $sessionHandler;  
         $query = " SELECT bs.busStopId,bsc.cityName,bs.stopName,bs.stopAbbr 
       			 FROM `bus_stop_new` bs , `bus_stop_city` bsc
       			 WHERE	bs.busStopCityId = bsc.busStopCityId
        		$conditions            
                     ORDER BY
                                    $orderBy $limit";    

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSSTOPSS
//$conditions :db clauses
// Author :NISHU BINDAL
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalBusStop($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(bs.busStopId) AS totalRecords
                        FROM `bus_stop_new` bs ,  `bus_stop_city` bsc
       			 WHERE	bs.busStopCityId = bsc.busStopCityId
                        $conditions 
                  ";
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUS STOP CITY
// Author :NISHU BINDAL
// Created on : (14.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------    
	public function fetchBusStopCity($conditions =''){
		$query="SELECT busStopCityId,cityName FROM `bus_stop_city` $conditions ORDER BY cityName";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	public function checkForBusStopMapping($busStopId){
		$query = "SELECT count(busRouteStopMappingId) As totalRecord FROM `bus_route_stop_mapping` WHERE busStopId = '$busStopId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
  
  
    
 

  
}
?>

