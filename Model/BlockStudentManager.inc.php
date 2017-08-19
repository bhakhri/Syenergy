<?php 
//-------------------------------------------------------
//  This File contain the block student bussines logics 
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');


class BlockStudentManager {
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

	public function addStudent($str) {
	
	   global $sessionHandler;	
	
  	   $query="INSERT INTO block_stu 
		       (studentId,isStatus,message,userId,date) 
		       VALUES 
		       $str "; 
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getStudent($conditions='') {
 	
		  $query="SELECT 
				        DISTINCT 
                            a.studentId, 
                            IFNULL(a.fatherName,'')  AS fatherName, 
                            CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) AS studentName,
                            IFNULL(a.fatherEmail,'') AS fatherEmail, 
                            IFNULL(a.studentEmail,'') AS studentEmail   	
			      FROM 
				       student a
			      $conditions";

     		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentList($conditions='', $limit = '', $orderBy='rollNo') {
 	
		  $query="SELECT 
				        a.blockId, a.message,
				        CONCAT(IFNULL(b.firstName,''),' ',IFNULL(b.lastName,'')) AS studentName,
				        b.rollNo, c.className, b.studentId,
                        IF(a.isStatus=1,'Blocked','".NOT_APPLICABLE_STRING."') AS isStatus  
			      FROM 
				        block_stu a,student b, class c 
			      WHERE 
				        a.studentId=b.studentId AND
				        c.classId = b.classId 
			            $conditions 
			      ORDER BY 
                        $orderBy $limit ";
	
     		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
		 

	public function getTotalStudent($conditions='') {
 	
		  $query="SELECT 
				        COUNT(*) AS totalRecords
                  FROM      	
			           (SELECT 
                                a.blockId, a.message,
                                CONCAT(IFNULL(b.firstName,''),' ',IFNULL(b.lastName,'')) AS studentName,
                                b.rollNo, c.className, b.studentId,
                                IF(a.isStatus=1,'Blocked','".NOT_APPLICABLE_STRING."') AS isStatus  
                        FROM 
                                block_stu a,student b, class c 
                        WHERE 
                                a.studentId=b.studentId AND
                                c.classId = b.classId 
                                $conditions) AS t ";
			
     		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	 
    public function deleteStudent($blockId) {
        
        $query = "DELETE FROM block_stu WHERE blockId IN ($blockId) ";
        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
}
?>
