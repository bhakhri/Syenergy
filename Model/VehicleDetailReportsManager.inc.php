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

class VehicleDetailReportsManager {
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
    
    public function getVehicleDetailList($fromDate='',$orderBy='brn.routeName'){
       
        $query="SELECT 
			brn.routeName,brm.busStopId,bsc.cityName,brn.busRouteId
		FROM
	 		`bus_route_new` brn,`bus_route_stop_mapping` brm,`bus_route_student_mapping` brsm,`bus_stop_city` bsc,`class` c, `batch` b, `student` s 
		WHERE 
			brm.busStopId=brsm.busStopId AND 
			brm.busRouteId=brsm.busRouteId AND 
			bsc.busStopCityId=brsm.busStopCityId AND 
			brm.busRouteStopMappingId=brsm.busRouteStopMappingId AND 
			brm.busRouteId=brn.busRouteId AND
			s.studentId=brsm.studentId AND
			c.classId=brsm.classId AND
			b.batchId=c.batchId
			AND ('$fromDate' <= brsm.validTo)
                GROUP BY
                    	brn.busRouteId
                ORDER BY
                   	 $orderBy ";
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }    
     
     public function getProgrammeList($fromDate='', $orderBy='d.degreeId, b.batchId, br.branchId') {
       
        $query="SELECT
                    br.branchId, d.degreeId, b.batchId, 
                    d.degreeName, d.degreeCode, b.batchName, br.branchName, br.branchCode   
                FROM
                      `bus_route_new` brn,`bus_route_stop_mapping` brm,`bus_route_student_mapping` brsm,`bus_stop_city` bsc,`class` c, 
                    `batch` b, `student` s, `degree` d, `branch` br
                WHERE
			
			brm.busStopId=brsm.busStopId AND 
			brm.busRouteId=brsm.busRouteId AND 
			bsc.busStopCityId=brsm.busStopCityId AND 
			brm.busRouteStopMappingId=brsm.busRouteStopMappingId AND 
			brm.busRouteId=brn.busRouteId AND
			s.studentId=brsm.studentId AND
			c.classId=brsm.classId AND
                  	  c.degreeId = d.degreeId AND
                    	br.branchId = c.branchId AND b.batchId = c.batchId 
			AND ('$fromDate' <= brsm.validTo)
                GROUP BY
                    b.batchId, d.degreeId, br.branchId
                ORDER BY
                    $orderBy ";
                    
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }   
     
     public function getVehicleStudentList($fromDate='', $orderBy='brn.routeName, b.batchName, d.degreeCode') {
       
        $query="SELECT   
                    b.batchId, d.degreeId, br.branchId,brn.routeName,brm.busStopId,bsc.cityName,brn.busRouteId,  
                    b.batchName,  br.branchName, br.branchCode, 
                    d.degreeName, d.degreeCode, COUNT(*) AS totalStudent,
                    SUM(IF(s.studentGender='M',1,0)) AS totalBoys,
                    SUM(IF(s.studentGender='F',1,0)) AS totalGirls
			
                FROM
                    `bus_route_new` brn,`bus_route_stop_mapping` brm,`bus_route_student_mapping` brsm,`bus_stop_city` bsc, `class` c, 
                    `batch` b, `student` s, `degree` d, `branch` br   
                WHERE
                    	brm.busStopId=brsm.busStopId AND 
			brm.busRouteId=brsm.busRouteId AND 
			bsc.busStopCityId=brsm.busStopCityId AND 
			brm.busRouteStopMappingId=brsm.busRouteStopMappingId AND 
			brm.busRouteId=brn.busRouteId AND
			s.studentId=brsm.studentId AND
			c.classId=brsm.classId AND
                  	 c.degreeId = d.degreeId AND
                    	br.branchId = c.branchId AND 
			b.batchId = c.batchId  AND 
			('$fromDate' <= brsm.validTo)
                GROUP BY
                    brn.busRouteId, b.batchId, d.degreeId, br.branchId
                ORDER BY
                    $orderBy ";
                    
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }      
    
}
  //$History : $
?>
