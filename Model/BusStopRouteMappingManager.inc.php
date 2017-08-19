<?php 
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busstopRouteMapping" 
// Author :Nishu Bindal
// Created on : (22.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class BusStopRouteMappingManager{
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BusStopRouteMappingManager" CLASS
//
// Author :Nishu Bindal 
// Created on : (29.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BusStopRouteMappingManager" CLASS
//
//
// Author :Nishu Bindal 
// Created on : (29.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
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
// THIS FUNCTION IS USED FOR Checking bus is already mapped with bus stop
//$conditions :db clauses
// Author :Nishu Bindal 
// Created on : (29.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function checkForAlreadyMapped($conditions='') {
     
        $query = "SELECT count(bsm.busRouteStopMappingId) As totalRecord 
       			 FROM  `bus_route_stop_mapping` bsm
       			 $conditions
               "; 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//**AS BUSSTOP TABLE IS INDEPENDENT NO NEED TO CHECK TO INTEGRITY CONSTRAINTS**//

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUS STOP Route Mapping
// Author :Nishu Bindal
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBusStopRouteMapping($busRouteStopMappingId) {
     
        $query = "DELETE 
        FROM `bus_route_stop_mapping`
        WHERE busRouteStopMappingId= $busRouteStopMappingId";
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
    
    public function getBusStopMappingList($conditions='', $limit = '', $orderBy='cityName') {
        global $sessionHandler;  
         $query = " SELECT bs.busStopId,bsc.cityName,bs.stopName,bs.stopAbbr, br.routeName,bsm.scheduledTime ,bsm.busRouteStopMappingId 
       			 FROM `bus_stop_new` bs , `bus_stop_city` bsc, `bus_route_new` br,`bus_route_stop_mapping` bsm
       			 WHERE	bs.busStopCityId = bsc.busStopCityId
       			 AND	bsm.busRouteId = br.busRouteId
       			 AND	bs.busStopId = bsm.busStopId 
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
    public function getTotalBusStopMapping($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(bsm.busRouteStopMappingId) AS totalRecords
                       FROM `bus_stop_new` bs , `bus_stop_city` bsc, `bus_route_new` br,`bus_route_stop_mapping` bsm
       			 WHERE	bs.busStopCityId = bsc.busStopCityId
       			 AND	bsm.busRouteId = br.busRouteId
       			 AND	bs.busStopId = bsm.busStopId
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
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CHECKING IF ROUTE IS ALREADY MAPPED WITH STUDENT
// Author :NISHU BINDAL
// Created on : (14.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------  	
	public function checkForBusStopRouteMapping($busRouteStopMappingId){
		$query = "SELECT count(busRouteStudentMappingId) As totalRecord FROM `bus_route_student_mapping` WHERE busRouteStopMappingId = '$busRouteStopMappingId'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING STOP NAMES CORRESPONG TO CITY 
// Author :NISHU BINDAL
// Created on : (14.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------  
	public function fetchStopNames($cityId){
		$query = "SELECT busStopId , stopName FROM `bus_stop_new` WHERE busStopCityId = '$cityId' ORDER BY stopName";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR FETCHING BUS STOP ROUTE MAPPING 
// Author :NISHU BINDAL
// Created on : (14.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function getBusStopRouteMapping($busRouteStopMappingId){
		$query = "SELECT bsm.busRouteStopMappingId,bsm.busStopId,bsm.busRouteId,bsm.scheduledTime,bs.busStopCityId
				FROM `bus_route_stop_mapping` bsm, `bus_stop_new` bs
				WHERE	bsm.busStopId = bs.busStopId
				AND 	busRouteStopMappingId  = '$busRouteStopMappingId'";
				
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");		
	}
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING  BUS STOP ROUTE MAPPING 
// Author :NISHU BINDAL
// Created on : (14.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function editBusStopRouteMapping($busRouteStopMappingId,$busStopId,$busRouteId,$scheduledTime){
		$scheduleTime = add_slashes(trim($scheduleTime)); 
   
		$query = "UPDATE `bus_route_stop_mapping` 
				SET	busStopId = '$busStopId', 
					busRouteId = '$busRouteId',
					scheduledTime = '$scheduledTime'
				WHERE busRouteStopMappingId = '$busRouteStopMappingId'";
			
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING BUS STOP ROUTE MAPPING 
// Author :NISHU BINDAL
// Created on : (14.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	
	public function addBusStopRouteMapping($stopName,$routeCode,$scheduleTime){
		$query ="INSERT INTO `bus_route_stop_mapping` (busRouteStopMappingId,busStopId,busRouteId,scheduledTime) 
						VALUES ('','$stopName','$routeCode','$scheduleTime')";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
	
	 
}
?>

