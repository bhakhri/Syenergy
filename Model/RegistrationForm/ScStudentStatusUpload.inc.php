<?php
//-------------------------------------------------------
// THIS FILE Contains All The DataBase Queries Of The Scholar Status
// Author : Ankur Aggarwal
// Created on : 25-Aug-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); 

class StudentStatusUpload {
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

// This will add the entry into student_hostel_bus_status table
	public function addStudentStatusInTransaction($str) {
        global $sessionHandler; 
		$query = "INSERT INTO 
				`student_hostel_bus_status` (classId,studentId,studentRollNo,dayScholar,hostler)
			 VALUES 
				 $str";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function validateStudent($condition) {
		global $sessionHandler; 
        $query = "select studentId,classId,firstName,lastName, fatherName from student  $condition ";

		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}


// This functions checks for the existing record of the given classId and rollNo  
     public function checkExistingRecord($classId,$studentId) {
	global $sessionHandler; 
	$query="SELECT 
		      COUNT(sh.studentRollNo) 
		FROM  
		      `student_hostel_bus_status` sh
		WHERE 
		      sh.studentId = '$studentId' AND 
		      sh.classId  = $classId ";
	
	return SystemDatabaseManager::getInstance()->executeQuery($query);
     } 


// This function will fetch the student list on the update status functionality 
    public function getStudentList($condition='', $limit = '', $orderBy='studentName',$classId='0') {         
        
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();  
        if($orderBy=='') {
          $orderBy ='studentName';
        }  
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
		       sh.studentRollNo,sh.dayScholar,sh.hostler,s.studentId,
                       IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                       CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) As studentName, studentPhoto
                  FROM 
                       student_hostel_bus_status sh
		       LEFT JOIN student s ON ( s.universityRollNo = sh.studentRollNo ) 
                  WHERE
			sh.classId IN ($classId)
                  $condition 
                  ORDER BY 
                        $orderBy
                  $limit ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



  public function updateDayScholarStatus($studentId,$classId='0') {
         
    global $sessionHandler; 
    
    if($classId=='') {
      $classId='0';  
    }
         
	$query="UPDATE
			student_hostel_bus_status sh
		SET
			sh.dayScholar=1, 
			sh.hostler=0
		WHERE
			sh.classId IN ($classId)
			AND sh.studentId='$studentId'";

        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	
    }	

  public function updateHostlerStatus($studentId,$classId='0') {
     global $sessionHandler; 
     if($classId=='') {
       $classId='0';  
     }
	$query="UPDATE
			student_hostel_bus_status sh
		SET
			sh.dayScholar=0, 
			sh.hostler=1
		WHERE
			sh.classId IN ($classId)
			AND sh.studentId='$studentId'";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
 
	
// This functions checks for the existing record of the given classId and rollNo  
     public function checkExistingRecordNew($classId,$studentId) {
          
          global $sessionHandler;    
          
          if($classId=='') {
           $classId='0';  
          }
         
          $query="SELECT 
                      COUNT(sh.studentRollNo) AS cnt 
                  FROM  
                      `student_hostel_bus_status` sh
                  WHERE 
                      sh.studentId = '$studentId' AND 
                      sh.classId  = '$classId' ";
            
            return SystemDatabaseManager::getInstance()->executeQuery($query);
     } 	
     
     public function updateOtherStatus($studentId,$classId='0') {
         
         global $sessionHandler; 
         
         if($classId=='') {
           $classId='0';  
         }
         
         $query="DELETE FROM student_hostel_bus_status WHERE classId IN ($classId) AND studentId='$studentId'";
         
         return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");  
    }
	 public function getUserId($userName) {

       $query = "	SELECT	userId
					FROM	user
					WHERE	userName = '$userName'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    	public function getStudentId($rollNo) {
		 $query = "select
								a.studentId,
								a.classId,
								b.sessionId,
								b.instituteId,
								concat(a.firstName,' ', a.lastName) as studentName

				 from			student a, class b
				 where			LOWER(trim(a.rollNo)) = LOWER(trim('$rollNo'))
				 and			a.classId = b.classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
		
	public function checkStudentClassMapping($studentId, $classId) {
		$query = "SELECT b.userName from student_teacher_mentorship a, user b where a.studentId = $studentId and a.classId = $classId and a.userId = b.userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
	
	 public function insertUserStudentClass($str) {
	$query = "INSERT INTO `student_teacher_mentorship` (classId,userId,studentId) VALUES $str";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }
	
		
    
    

}


