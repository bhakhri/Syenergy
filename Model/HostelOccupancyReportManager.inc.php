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

class HostelOccupancyReportManager {
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
    
    public function getHostelDetailList($fromDate='',$orderBy='h.hostelId'){
       
        $query="SELECT
                    h.hostelId, h.hostelName,h.totalCapacity as noOfBeds,h.roomTotal
                FROM
                    `hostel` h,`hostel_students` hs
		        WHERE 			      
 			        ('$fromDate' <= hs.dateOfCheckOut)
                GROUP BY
                   	h.hostelId
                ORDER BY
                    $orderBy ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }    
     
    
     
     public function getHostelStudentList($fromDate='', $orderBy='h.hostelId') {
       
        $query="SELECT   
                     h.hostelId,COUNT(*) AS totalStudent
                    
                FROM
                   `hostel` h,`hostel_room` r, `hostel_students` hs,`student` s
                WHERE
                  	hs.hostelRoomId = r.hostelRoomId AND 
			s.studentId = hs.studentId AND
                    r.hostelId = h.hostelId       AND
			 ('$fromDate' <= hs.dateOfCheckOut)
                    
                GROUP BY
                    h.hostelId
                ORDER BY
                    $orderBy ";
                 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }   


 public function getVacantRoomList($fromDate='', $orderBy='hostelName, roomName ') {
       
        $query="SELECT
                    DISTINCT h.hostelId, h.hostelName, h.roomTotal, h.totalCapacity,    
                    hr.roomName, hr.roomCapacity, 
                    (hr.roomCapacity - SUM(IF(IFNULL(hs.hostelRoomId,'')='',0,1))) AS staying,
                    SUM(IF(IFNULL(hs.studentId,'')='',0,1)) AS vacant
                FROM 
                    `hostel` h, `hostel_room` hr 
                    LEFT JOIN `hostel_students` hs ON hs.hostelRoomId = hr.hostelRoomId
                WHERE 
                    h.hostelId =hr.hostelId AND
                    ('$fromDate' <= hs.dateOfCheckOut)  
                GROUP BY 
                    hr.hostelId, hr.hostelRoomId
                HAVING 
                    staying > 0 
                UNION
                SELECT
                    DISTINCT h.hostelId, h.hostelName, h.roomTotal, h.totalCapacity,    
                    hr.roomName, hr.roomCapacity, 
                    0 AS staying,
                    SUM(hr.roomCapacity) AS vacant
                FROM 
                    `hostel` h, `hostel_room` hr 
                WHERE 
                    hr.hostelId=h.hostelId AND hr.hostelRoomId NOT IN 
                    (SELECT hostelRoomId FROM hostel_students hs WHERE 
                    hs.hostelRoomId=hr.hostelRoomId AND '$fromDate' <= hs.dateOfCheckOut)    
                GROUP BY
                    hr.hostelId, hr.hostelRoomId    
                ORDER BY
                    $orderBy";

              
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }      
       
    
}
  //$History : $
?>
