<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "busroute" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BusRouteManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BusRouteManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BusRouteManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A BUSROUTE
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addBusRoute($insertValue) {
		global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "INSERT INTO `bus_route`
                  (routeName,routeCode)
                  VALUES
                  $insertValue";
                  
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    
    public function addBus($busRouteId,$busId){
        global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "INSERT INTO `busRouteMapping`
                  (busRouteId,busId)
                  VALUES
                  ('$busRouteId','$busId')";
              
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }

    public function deleteBusMapping($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "DELETE FROM `busRouteMapping` WHERE busRouteId = '$id'";
                   
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }
          
    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A BUSROUTE
//$busRouteId :busRouteId of the BusStop
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteBusRoute($busRouteId) {
     
        $query = "DELETE 
        FROM bus_route 
        WHERE busRouteId=$busRouteId";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }      
    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO check if bus route is mapped with BUS FEES
// Author :NISHU BINDAL
// Created on : (5.April.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------- 
	public function checkBusFee($condition =''){
		$query = "SELECT COUNT(busFeesId) AS cnt FROM `bus_fees` $condition";
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A BUSROUTE
//$id:busRouteId
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------        
    public function editBusRoute($routeName,$routeCode, $id) {
        global $REQUEST_DATA;
        global $sessionHandler;
     
        $query = "UPDATE 
                        `bus_route`
                  SET
                        routeName = '$routeName',
                        routeCode = '$routeCode'
                  WHERE
                        busRouteId=$id";
                   
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSROUTE LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getBusRoute($conditions='') {
     
  $query = "	SELECT
                        br.busRouteId,
			br.routeName,
			br.routeCode,
			brm.busId
		FROM	
                        bus_route br LEFT JOIN busRouteMapping brm ON br.busRouteId = brm.busRouteId  
					$conditions";            
                        
                            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      
    }  
    

    //**AS BUSROUTE TABLE IS INDEPENDENT NO NEED TO CHECK FOR INTEGRITY CONSTRAINTS**//



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSROUTE,STUDENTCOUNT,BUSSTOP LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
//
//--------------------------------------------------------       
    
    public function getBusRouteList($conditions='', $limit = '', $orderBy=' br.routeCode') {                            
       $query = "SELECT
                        br.busRouteId, br.studentCount, br.routeName, 
                        br.routeCode,  br.busNo
                 FROM     
                       (SELECT
                                br.busRouteId, 
                                COUNT(bp.studentId) AS studentCount,
                                br.routeName, br.routeCode, br.routeCharges,
                                IF(brm.busId IS NULL,'".NOT_APPLICABLE_STRING."', busNo ) AS busNo
                         FROM
                                bus_route br
                                LEFT JOIN  bus_pass bp ON  bp.busRouteId = br.busRouteId  AND bp.validUpto>CURDATE() AND bp.busPassStatus=1     
                                LEFT JOIN  busRouteMapping brm ON br.busRouteId = brm.busRouteId 
                                LEFT JOIN  bus b ON b.busId = brm.busId 
                         $conditions                                
                         GROUP BY   
                                br.busRouteId) AS  br
                 ORDER BY 
                          $orderBy $limit";               
                                               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSROUTES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalBusRoute($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                       (SELECT
                                br.busRouteId, if (bs.stopName IS NULL,'--',GROUP_CONCAT(DISTINCT bs.stopName)) AS stopName, 
                                COUNT(bp.studentId) AS studentCount,
                                br.routeName, br.routeCode, br.routeCharges,
                                IF(brm.busId IS NULL,'".NOT_APPLICABLE_STRING."',GROUP_CONCAT(DISTINCT busNo  SEPARATOR ', ')) AS busNo
                         FROM
                                bus_route br
                                LEFT JOIN  bus_pass bp ON  bp.busRouteId = br.busRouteId  AND bp.validUpto>CURDATE() AND bp.busPassStatus=1     
                                LEFT JOIN  bus_stop bs ON  bs.busStopId = bp.busStopId     
                                LEFT JOIN  busRouteMapping brm ON br.busRouteId = brm.busRouteId 
                                LEFT JOIN  bus b ON b.busId = brm.busId   
                         $conditions                                
                         GROUP BY   
                                br.busRouteId) AS  br";
                                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING UPDATE CHARGES TO BUS STOP
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (2.4.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function updateCharges($conditions,$routeCharges) {
        global $sessionHandler;
        
        $query = "UPDATE bus_stop SET transportCharges=$routeCharges $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
	
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSROUTES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkBusRouteLink($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	bus_stop bs $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUSROUTES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkBusPassRouteLink($conditions='') {
    
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	bus_pass bp $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF BUS ROUTE MAPPING
//$conditions :db clauses
// Author :NISHU BINDAL
// Created on : (5.April.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------  
    public function checkBusRouteMapping($condition =''){
    	$query = " SELECT COUNT(busRouteStopMappingId) AS cnt FROM `bus_route_stop_mapping` $condition";
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkBusAccident($conditions='') {
    
        $query = "    SELECT    COUNT(*) AS totalRecords 
                    FROM    bus_accident  $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING STUDENT COUNT OF BUS ROUTE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (12.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getStudentRouteCount($conditions='') {
    
        $query = "	SELECT	COUNT(distinct bp.studentId) AS studentCount 
					FROM	bus_pass bp, student s
					WHERE	bp.studentId = s.studentId
					AND		bp.cancelOnDate = '0000-00-00'
					AND		busPassStatus = 1
							$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE COUNT OF BUS ROUTE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (12.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getEmployeeRouteCount($conditions='') {
    
        $query = "	SELECT	COUNT(distinct ebp.employeeId) AS employeeCount 
					FROM	employee_bus_pass ebp, employee emp
					WHERE	ebp.employeeId = emp.employeeId
					AND		ebp.cancelOnDate IS NULL
					AND		ebp.status = 1
							$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE COUNT OF BUS ROUTE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (12.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getStudentRouteList($conditions='') {
    
        $query = "	SELECT	DISTINCT s.studentId,
							CONCAT(s.firstName,' ',s.lastName) AS studentName, 
							s.rollNo AS rollNo, u.roleId, s.userId, r.roleName,
							i.instituteName
					FROM	bus_pass bp, student s, `user` u, `role` r, institute i
					WHERE	bp.studentId = s.studentId
					AND		s.userId = u.userId
					AND		u.roleId = r.roleId
					AND		u.instituteId = i.instituteId
					AND		bp.cancelOnDate = '0000-00-00'
					AND		busPassStatus = 1
							$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE COUNT OF BUS ROUTE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (12.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getEmployeeRouteList($conditions='') {
    
        $query = "	SELECT	distinct emp.employeeId, CONCAT(emp.employeeName,' ',emp.middleName,' ',emp.lastName) AS studentName, 
							emp.employeeCode AS rollNo, i.instituteName, r.roleName
					FROM 	employee_bus_pass ebp, employee emp, institute i, `user` u, `role` r
					WHERE 	ebp.employeeId = emp.employeeId
					AND		emp.instituteId = i.instituteId
					AND 	ebp.instituteId = i.instituteId
					AND 	emp.userId = u.userId
					AND		u.roleId = r.roleId
					AND		ebp.cancelOnDate IS NULL
					AND		ebp.status = 1
							$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE COUNT OF BUS ROUTE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (12.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getPassengerRouteList($conditions='') {
    
        $query = "	SELECT	distinct s.studentId,
							'Student' AS roleName,
							CONCAT(s.firstName,' ',s.lastName) AS studentName,
							s.rollNo AS rollNo,
							i.instituteName
					FROM	bus_pass bp,
							student s,
							class c,
							institute i
					WHERE	bp.studentId = s.studentId
					AND		c.instituteId = i.instituteId
					AND		s.classId = c.classId
					AND		bp.cancelOnDate = '0000-00-00' 
					AND		bp.busPassStatus = 1
							$conditions
					UNION
					SELECT	distinct emp.employeeId,
							if(r.roleName IS NULL,'--',r.roleName) AS roleName,
							CONCAT(emp.employeeName,' ',emp.middleName,' ',emp.lastName) AS studentName, 
							emp.employeeCode AS rollNo, i.instituteName
					FROM	employee_bus_pass bp, institute i, employee emp
							LEFT JOIN `user` u ON (emp.userId = u.userId)
							LEFT JOIN role r ON (u.roleId = r.roleId)
					WHERE	bp.employeeId = emp.employeeId
					AND		emp.instituteId = i.instituteId
					AND		bp.instituteId = i.instituteId
					AND		bp.cancelOnDate IS NULL
					AND		bp.status = 1
							$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
  
}

// $History: BusRouteManager.inc.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Model
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
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
//*****************  Version 4  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:21p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/03/08    Time: 8:07p
//Updated in $/Leap/Source/Model
//Modify table name to have underscore
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 7:07p
//Updated in $/Leap/Source/Model
//Created BusRoute Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:33p
//Created in $/Leap/Source/Model
//Initial Checkin
?>
