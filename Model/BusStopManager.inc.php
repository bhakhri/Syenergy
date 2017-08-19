<?php 
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busstop" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class BusStopManager {
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
	public function addBusStop() {
		global $REQUEST_DATA;
        global $sessionHandler;

		return SystemDatabaseManager::getInstance()->runAutoInsert('bus_stop', array('stopName','stopAbbr','transportCharges','instituteId','busRouteId','scheduleTime'), 
        array($REQUEST_DATA['stopName'],strtoupper($REQUEST_DATA['stopAbbr']),$REQUEST_DATA['transportCharges'],$sessionHandler->getSessionVariable('InstituteId'),
        $REQUEST_DATA['routeCode'],$REQUEST_DATA['scheduleTime']) 
        );
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
    public function editBusStop($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('bus_stop', array('stopName','stopAbbr','transportCharges','instituteId','busRouteId','scheduleTime'), 
        array($REQUEST_DATA['stopName'],strtoupper($REQUEST_DATA['stopAbbr']),$REQUEST_DATA['transportCharges'],$sessionHandler->getSessionVariable('InstituteId'),
        $REQUEST_DATA['routeCode'],$REQUEST_DATA['scheduleTime']), 
        "busStopId=$id" 
        );
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
     
        $query = "SELECT busStopId,stopName,stopAbbr,transportCharges,instituteId,busRouteId,scheduleTime 
        FROM bus_stop
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
    public function deleteBusStop($busStopid) {
     
        $query = "DELETE 
        FROM bus_stop 
        WHERE busStopid=$busStopid";
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
    
    public function getBusStopList($conditions='', $limit = '', $orderBy=' bs.stopName') {
        global $sessionHandler;
        
      /*  $query = "SELECT bs.busStopId,bs.stopName,bs.stopAbbr,bs.transportCharges,bs.scheduleTime,bsr.routeCode  
        FROM bus_stop bs, bus_route bsr
        WHERE bs.busRouteId=bsr.busRouteId AND
        bs.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
        $conditions 
        ORDER BY $orderBy $limit";           */   
       
         $query = "  SELECT        
                                     bs.busStopId, bs.stopName, bs.stopAbbr,bs.transportCharges,bs.scheduleTime,
                                     IFNULL(bsr.routeCode,'".NOT_APPLICABLE_STRING."') AS routeCode,
                                     COUNT(bp.studentId) AS studentCount
                     FROM            bus_stop bs 
                                     LEFT JOIN bus_route bsr ON bs.busRouteId = bsr.busRouteId
                                     LEFT JOIN bus_pass bp   ON bs.busRouteId = bp.busRouteId  AND bs.busStopId = bp.busStopId AND bp.validUpto>CURDATE() AND bp.busPassStatus = 1
                     WHERE           bs.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                    $conditions
                     GROUP BY
                                    bs.busStopId  
                     ORDER BY       
                                    $orderBy $limit";    
       // echo  $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSSTOPSS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalBusStop($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                      ( SELECT        
                                     bs.busStopId, bs.stopName, bs.stopAbbr,bs.transportCharges,bs.scheduleTime, bsr.routeCode,
                                     COUNT(bp.studentId) AS studentCount
                        FROM   
                                     bus_stop bs 
                                     LEFT JOIN bus_route bsr ON bs.busRouteId = bsr.busRouteId
                                     LEFT JOIN bus_pass bp   ON bs.busRouteId = bp.busRouteId  AND bs.busStopId = bp.busStopId AND 
                                                                bp.validUpto>CURDATE() AND bp.busPassStatus = 1
                        WHERE      
                                    bs.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                    $conditions
                        GROUP BY
                                     bs.busStopId) AS tt";
                                     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

 //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUS ROUTE CHARGES
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (11.09.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getBusCharges($conditions='') {
        global $sessionHandler;
        
       $query = "	SELECT		routeCharges
					FROM		bus_route
								$conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
   
    public function getBusRoutMappingList($timeTablelabelId='',$routeId='',$orderBy='stopName',$limit='',$conditions='') {
   
       global $sessionHandler;
       
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
       $query = "SELECT 
                      bs.busStopId, bs.stopName, IFNULL(rsm.mappingId,'-1') AS mappingId,
                      bs.stopAbbr, bs.transportCharges 
                 FROM   
                      bus_stop bs LEFT JOIN route_stop_mapping rsm ON 
                            rsm.routeId = bs.busRouteId AND rsm.stopId = bs.busStopId AND rsm.timeTableLabelId = $timeTablelabelId 
                  WHERE
                      bs.busRouteId = $routeId AND
                      bs.instituteId = $instituteId     
                 $conditions 
                 ORDER BY
                        $orderBy $limit";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getBusRoutMappingCount($timeTablelabelId='',$routeId='',$orderBy='',$limit='') {
   
       global $sessionHandler;
       
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
       $query = "SELECT 
                      COUNT(*) AS totalRecords
                 FROM   
                      bus_stop bs LEFT JOIN route_stop_mapping rsm ON 
                            rsm.routeId = bs.busRouteId AND rsm.stopId = bs.busStopId AND rsm.timeTableLabelId = $timeTablelabelId 
                  WHERE
                      bs.busRouteId = $routeId AND
                      bs.instituteId = $instituteId     
                 $conditions ";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function insertStopToRoute($insertValue) {
	
    	    global $REQUEST_DATA;
		
			$query = "INSERT INTO `route_stop_mapping`
					  (timeTableLabelId,routeId,stopId)
					  VALUES
					  $insertValue";

			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deleteRouteStopMapping($condition) {  
        
       global $REQUEST_DATA;    
           
       $query = "DELETE FROM route_stop_mapping WHERE $condition ";
       
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

  
}
?>
<?php
// $History: BusStopManager.inc.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/14/09    Time: 6:36p
//Updated in $/LeapCC/Model
//put route charges and check box 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/24/08    Time: 10:21a
//Updated in $/Leap/Source/Model
//Added functionilty for busRouteId in bus stop master
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:21p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/05/08    Time: 1:00p
//Updated in $/Leap/Source/Model
//Modifies" instituId"  insertion so that it comes from session variable
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/03/08    Time: 8:09p
//Updated in $/Leap/Source/Model
//Modified table name to have underscore
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:29p
//Updated in $/Leap/Source/Model
//Created BusStop Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 4:01p
//Created in $/Leap/Source/Model
//Initial Checkin
?>
