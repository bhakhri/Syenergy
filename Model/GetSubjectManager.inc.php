<?php
//-------------------------------------------------------
//  This File contains all functions for SMSDetails for Student/Employee
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class SubjectManager {
	
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

	function addSubjectDetail($str){
		

		global $REQUEST_DATA;
		global $sessionHandler;
		$query = "INSERT INTO `student_academic_subject`(studentId,subjectName,marks,maxMarks,previousClassId)
		VALUES $str";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	public function deleteSubjectDetail($studentId,$previousClassId){

	    $query = "DELETE FROM `student_academic_subject` 
		      WHERE 
			    studentId=$studentId AND 
			    previousClassId=$previousClassId";

  	    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    	}


 public function getSubjectValues($conditions='') {
        global $sessionHandler;
        
        $query = "SELECT 
                        * 
                  FROM 
                        `student_academic_subject`
                  $conditions";
                  
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
}
?>

