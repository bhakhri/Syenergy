<?php
//-------------------------------------------------------------------------------
//
//HostelManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class HostelDetailReportsManager {
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
    
    public function getHostelDetailList($fromDate='',$orderBy='h.hostelName'){
       
        $query="SELECT
                    h.hostelId, h.hostelName, h.hostelType, h.floorTotal, h.totalCapacity
                FROM
                    hostel h,hostel_room r, hostel_students hs, class c, batch b, student s
                WHERE
                    hs.hostelRoomId = r.hostelRoomId AND s.studentId = hs.studentId AND
                    r.hostelId = h.hostelId AND c.classId = hs.classId AND 
                    b.batchId = c.batchId AND ('$fromDate' <= hs.dateOfCheckOut)
                GROUP BY
                    h.hostelId
                ORDER BY
                    $orderBy ";
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }    
     
     public function getProgrammeList($fromDate='', $orderBy='d.degreeId, b.batchId, br.branchId') {
       
        $query="SELECT
                    br.branchId, d.degreeId, b.batchId, 
                    d.degreeName, d.degreeCode, b.batchName, br.branchName, br.branchCode   
                FROM
                    hostel h,hostel_room r, hostel_students hs, class c, 
                    batch b, student s, degree d, branch br
                WHERE
                    hs.hostelRoomId = r.hostelRoomId AND s.studentId = hs.studentId AND
                    r.hostelId = h.hostelId AND c.classId = hs.classId AND c.degreeId = d.degreeId AND
                    br.branchId = c.branchId AND b.batchId = c.batchId AND ('$fromDate' <= hs.dateOfCheckOut)
                GROUP BY
                    b.batchId, d.degreeId, br.branchId
                ORDER BY
                    $orderBy ";
                    
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }   
     
     public function getHostelStudentList($fromDate='', $orderBy='h.hostelName, b.batchName, d.degreeCode') {
       
        $query="SELECT   
                    b.batchId, d.degreeId, br.branchId, r.hostelId,  
                    b.batchName, h.hostelCode, h.hostelName, br.branchName, br.branchCode, 
                    d.degreeName, d.degreeCode, COUNT(*) AS totalStudent,
                    SUM(IF(s.studentGender='M',1,0)) AS totalBoys,
                    SUM(IF(s.studentGender='F',1,0)) AS totalGirls
                FROM
                    hostel h,hostel_room r, hostel_students hs, class c, 
                    batch b, student s, degree d, branch br   
                WHERE
                    hs.hostelRoomId = r.hostelRoomId AND s.studentId = hs.studentId AND
                    r.hostelId = h.hostelId AND c.classId = hs.classId AND c.degreeId = d.degreeId  AND
                    br.branchId = c.branchId AND b.batchId = c.batchId AND ('$fromDate' <= hs.dateOfCheckOut)
                GROUP BY
                    h.hostelId, b.batchId, d.degreeId, br.branchId
                ORDER BY
                    $orderBy ";
                    
                    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }      
    
}
  //$History : $
?>
