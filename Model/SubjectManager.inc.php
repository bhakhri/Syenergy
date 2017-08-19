<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the Subject  Module
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

//Main responsible for operation in subject table in database

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
	public function addSubject() {
		global $REQUEST_DATA;

		$subjectCode = strtoupper(add_slashes(trim($REQUEST_DATA['subjectCode'])));
		$subjectName = add_slashes(trim($REQUEST_DATA['subjectName']));
		$subjectAbbreviation = add_slashes(trim($REQUEST_DATA['subjectAbbreviation']));
		$subjectTypeId = add_slashes(trim($REQUEST_DATA['subjectTypeId']));
		$subjectCategoryId = add_slashes(trim($REQUEST_DATA['subjectCategoryId']));
		$alternateSubjectName = add_slashes(trim($REQUEST_DATA['alternateSubjectName']));
		$alternateSubjectCode = add_slashes(trim($REQUEST_DATA['alternateSubjectCode']));
		$hasAttendance = add_slashes(trim($REQUEST_DATA['hasAttendance']));
		$hasMarks = add_slashes(trim($REQUEST_DATA['hasMarks']));

		$query = "INSERT INTO subject(subjectCode,subjectName,subjectAbbreviation,subjectTypeId,subjectCategoryId,alternateSubjectName,alternateSubjectCode,hasAttendance,hasMarks) VALUES('$subjectCode', '$subjectName', '$subjectAbbreviation', '$subjectTypeId', '$subjectCategoryId','$alternateSubjectName', '$alternateSubjectCode', '$hasAttendance', '$hasMarks')";
      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

        
	}
    public function editSubject($id) {

		global $REQUEST_DATA;

		$subjectCode = strtoupper(add_slashes(trim($REQUEST_DATA['subjectCode'])));
		$subjectName = add_slashes(trim($REQUEST_DATA['subjectName']));
		$subjectAbbreviation = add_slashes(trim($REQUEST_DATA['subjectAbbreviation']));
		$subjectTypeId = add_slashes(trim($REQUEST_DATA['subjectTypeId']));
		$subjectCategoryId = add_slashes(trim($REQUEST_DATA['subjectCategoryId']));
		$alternateSubjectName = add_slashes(trim($REQUEST_DATA['alternateSubjectName']));
		$alternateSubjectCode = add_slashes(trim($REQUEST_DATA['alternateSubjectCode']));
		$hasAttendance = add_slashes(trim($REQUEST_DATA['hasAttendance']));
		$hasMarks = add_slashes(trim($REQUEST_DATA['hasMarks']));

		$query = "UPDATE subject SET subjectCode = '$subjectCode',
					subjectName = '$subjectName',
					subjectAbbreviation = '$subjectAbbreviation',
					subjectTypeId = '$subjectTypeId',
					subjectCategoryId = '$subjectCategoryId',
					alternateSubjectName = '$alternateSubjectName',
					alternateSubjectCode = '$alternateSubjectCode',
					hasAttendance = '$hasAttendance',
					hasMarks = '$hasMarks'
					where subjectId = $id
			";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
     
        //return SystemDatabaseManager::getInstance()->runAutoUpdate('subject', array('subjectCode','subjectName','subjectAbbreviation','subjectTypeId','subjectCategoryId','hasAttendance','hasMarks'), array(strtoupper($REQUEST_DATA['subjectCode']),$REQUEST_DATA['subjectName'],$REQUEST_DATA['subjectAbbreviation'],$REQUEST_DATA['subjectTypeId'],$REQUEST_DATA['subjectCategoryId'],$REQUEST_DATA['hasAttendance'],$REQUEST_DATA['hasMarks']), "subjectId=$id" );
    }    
    public function getSubject($conditions='') {
     
        $query = "SELECT 
                        s.subjectId,s.subjectCode,s.subjectName,s.subjectAbbreviation,s.subjectTypeId,st.universityId,
                        s.subjectCategoryId, sc.categoryName, s.hasAttendance, s.hasMarks, subt.topic, subt.topicAbbr,
                        IFNULL(s.alternateSubjectName,'".NOT_APPLICABLE_STRING."') AS alternateSubjectName,
                            IFNULL(s.alternateSubjectCode,'".NOT_APPLICABLE_STRING."') AS alternateSubjectCode
                  FROM 
                        subject_type st, subject s LEFT JOIN subject_category sc ON (sc.subjectCategoryId = s.subjectCategoryId)
								LEFT JOIN subject_topic subt ON (s.subjectId = subt.subjectId)
                  WHERE 
                        s.subjectTypeId=st.subjectTypeId   
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
 /*   public function checkInCity($stateId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM city 
        WHERE stateId=$stateId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 */   public function deleteSubject($subjectId) {
     
        $query = "DELETE 
        FROM subject 
        WHERE subjectId=$subjectId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

	public function deleteSubjectTopic($subjectId) {
     
        $query = "DELETE 
        FROM subject_topic 
        WHERE subjectId=$subjectId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    public function getSubjectList($conditions='', $limit = '', $orderBy=' sub.subjectName') {
     
        $query = "SELECT 
                         sub.subjectId, sub.subjectCode, sub.subjectName, 
                         IF(sub.subjectAbbreviation='','".NOT_APPLICABLE_STRING."',sub.subjectAbbreviation) as subjectAbbreviation, 
                         IF(IFNULL(sc.categoryName,'')='','".NOT_APPLICABLE_STRING."',sc.categoryName) as categoryName,
                         subtyp.subjectTypeId, concat(b.universityCode,'-',subtyp.subjectTypeName) as subjectTypeName, 
                         IFNULL(sub.alternateSubjectName,'".NOT_APPLICABLE_STRING."') AS alternateSubjectName,
                            IFNULL(sub.alternateSubjectCode,'".NOT_APPLICABLE_STRING."') AS alternateSubjectCode,
                         IF(sub.hasAttendance=1,'Yes','No') AS hasAttendance, 
                         IF(sub.hasMarks=1,'Yes','No') AS hasMarks 
                  FROM 
                        subject_type subtyp, university b, subject sub LEFT JOIN subject_category sc ON sc.subjectCategoryId = sub.subjectCategoryId  
                  WHERE 
                        sub.subjectTypeId=subtyp.subjectTypeId AND subtyp.universityId = b.universityId $conditions 
                  ORDER BY $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    public function getTotalSubject($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        subject_type subtyp, subject sub LEFT JOIN subject_category sc ON sc.subjectCategoryId = sub.subjectCategoryId  
                  WHERE 
                        sub.subjectTypeId=subtyp.subjectTypeId $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   

    
    // Check subject table DEPENDENCY CONSTRAINT   
    public function getCheckSubject($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS cnt 
                  $conditions ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
    // Check subject table DEPENDENCY CONSTRAINT   
    public function getStudentClassList($conditions='') {
    
        $query = "SELECT 
                     DISTINCT s.classId, c.degreeId, c.batchId, c.branchId
                  FROM 
                     student s, study_period sp, class c  
                  WHERE
                     s.classId = c.classId AND c.studyPeriodId = sp.studyPeriodId $conditions
                  ORDER BY 
                     s.classId ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
    public function getClassList($conditions='') {
    
        $query = "SELECT 
                     DISTINCT c.classId, CAST(IFNULL(sp.periodValue,0) AS UNSIGNED) AS periodValue
                  FROM 
                     class c, study_period sp
                  WHERE
                     c.studyPeriodId = sp.studyPeriodId 
                     $conditions
                  ORDER BY 
                     CAST(IFNULL(sp.periodValue,0) AS UNSIGNED)  ASC";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function updateStudentClass($fieldValue) {
        global $sessionHandler;
        
        $query = "$fieldValue";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
   
    
}

?>