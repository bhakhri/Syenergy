<?php
//-------------------------------------------------------------------------------

//StudentReportsManager .
// Author : Ipta Thakur
// Created on : 04.11.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FinalResultManager {
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

      public function getStudentList($condition='',$orderBy='') {
      
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
      
        $query = "SELECT    
                       DISTINCT att.studentId, att.classId 
                       IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                       IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                       CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, 
                       IFNULL(s.studentPhoto,'') AS studentPhoto
                  FROM
                       ".TOTAL_TRANSFERRED_MARKS_TABLE." att , class cls, student s
                  WHERE     
                      att.studentId = s.studentId AND
                      att.classId = cls.classId   AND 
                      cls.instituteId = '".$instituteId."' AND
                      (IFNULL(cls.internalPassMarks,0) > 0 OR IFNULL(externalPassMarks,0) >0) AND
                      cls.isActive IN(1,3)
                  $condition    
                  ORDER BY 
                      $orderBy";
                            
       return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getSubjectList($condition='',$orderBy='') {
      
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
      
        $query = "SELECT    
                       DISTINCT att.subjectId, sub.subjectCode, sub.subjectName 
                  FROM
                       ".TOTAL_TRANSFERRED_MARKS_TABLE." att , `subject` sub
                  WHERE     
                      att.subjectId = sub.subjectId 
                      $condition    
                  ORDER BY 
                      $orderBy";
                            
       return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getResultList($condition='',$orderBy='') {
      
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
      
        $query = "SELECT    
                       conductingAuthority, studentId, classId, subjectId, 
                       IFNULL(SUM(maxMarks),0) AS maxMarks, IFNULL(SUM(marksScored),0) AS marksScored, 
                       holdResult, marksScoredStatus, isActive
                  FROM
                       ".TOTAL_TRANSFERRED_MARKS_TABLE." att 
                  WHERE     
                       isActive = 1  
                       $condition  
                  GROUP BY
                        studnetId     
                  UNION
                  SELECT    
                       conductingAuthority, studentId, classId, subjectId, 
                       IFNULL(SUM(maxMarks),0) AS maxMarks, IFNULL(SUM(marksScored),0) AS marksScored, 
                       holdResult, marksScoredStatus, isActive
                  FROM
                       ".TOTAL_UPDATED_MARKS_TABLE." att 
                  WHERE     
                       isActive = 1  
                       $condition        
                  GROUP BY
                        studnetId     
                  ORDER BY 
                       $orderBy";
                            
       return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

}




