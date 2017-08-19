<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class OptionalSubjectGroupManager {
	
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
    

//--------------------------------------------------------------
//  THIS FUNCTION IS Optional Subjectwise Group added
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function addOptionalSubjectGroup($groupName,$groupShort,$groupTypeId,$classId,$isOptional,$optionalSubjectId) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "INSERT INTO `group`
                  (groupName, groupShort, parentGroupId, groupTypeId, classId, isOptional, optionalSubjectId) 
                  VALUES
                  ($groupName,$groupShort,0,$groupTypeId,$classId,$isOptional,$optionalSubjectId)";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    public function editOptionalSubjectGroup($condition='',$optionalSubjectId='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "UPDATE `group` SET optionalSubjectId = '$optionalSubjectId' WHERE $condition";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Optional Subjectwise Group added
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function addStudentOptionalSubjectGroup($subjectId, $studentId, $classId, $groupId) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "INSERT INTO `student_optional_subject`
                  (subjectId,studentId,classId,groupId) 
                  VALUES
                  ($subjectId, $studentId, $classId, $groupId)";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
    
    
    public function editStudentOptionalSubjectGroup($condition = '', $subjectId, $studentId, $classId, $groupId) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "UPDATE 
                        `student_optional_subject`
                  SET
                        subjectId = '$subjectId',
                        studentId = '$studentId',
                        classId = '$classId',
                        groupId = '$groupId'
                  WHERE
                        $condition ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
    
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Optional Subjectwise Group added
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function deleteOptionalSubjectGroup($condition) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "DELETE FROM `group` WHERE $condition";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    } 
    
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Optional Subjectwise Group added
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function deleteStudentOptionalSubjectGroup($condition) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "DELETE FROM `student_optional_subject` WHERE $condition";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
        

    public function getClassVisibleRole($conditions='') {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT DISTINCT userId FROM classes_visible_to_role WHERE $conditions ";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");          
    }    
        
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
// Author :Parveen Sharma 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getRegistrationClassList($conditions='', $limit = '', $orderBy=' c.className,sub.subjectCode') {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                         d.classId, c.className, sub.subjectId, sub.subjectName, sub.subjectCode, gt.groupTypeId,
                         IFNULL(SUM(IF(d.subjectType='Career',1,0)),0) AS careerStudent,
                         IFNULL(SUM(IF(d.subjectType='Elective',1,0)),0) AS electiveStudent,
                         IFNULL(COUNT(s.studentId),0) AS totalStudent, IFNULL(grp.groupId,-1) AS groupId
                  FROM
                        `student_registration_master` m, `subject` sub,
                        `student` s, class c, group_type gt,
                        `student_registration_detail` d  LEFT JOIN `group` grp ON grp.classId=d.classId AND grp.optionalSubjectId = d.subjectId  
                  WHERE
                         d.classId=c.classId AND 
                         sub.subjectId = d.subjectId AND
                         m.studentId = s.studentId AND
                         m.registrationId = d.registrationId AND
                         m.instituteId = '$instituteId' AND
                         sub.subjectTypeId = gt.groupTypeId
                         $conditions                         
                   GROUP BY
                         d.classId, d.subjectId
                   HAVING
                         totalStudent > 0    
                   ORDER BY
                         $orderBy";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    
    public function getRegistrationClassListNew($conditions='', $limit = '', $orderBy=' c.className,sub.subjectCode') {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                         d.classId, c.className, sub.subjectId, sub.subjectName, sub.subjectCode, gt.groupTypeId,
                         IFNULL(SUM(IF(d.subjectType='Career',1,0)),0) AS careerStudent,
                         IFNULL(SUM(IF(d.subjectType='Elective',1,0)),0) AS electiveStudent,
                         IFNULL(COUNT(s.studentId),0) AS totalStudent, IFNULL(grp.groupId,-1) AS groupId
                  FROM
                        `student_registration_master` m, `subject` sub, study_period sp, 
                        `student` s, class c, group_type gt,
                        `student_registration_detail` d  LEFT JOIN `group` grp ON grp.classId=d.classId AND grp.optionalSubjectId = d.subjectId  
                  WHERE
                         c.studyPeriodId = sp.studyPeriodId AND   
                         d.classId=c.classId AND 
                         sub.subjectId = d.subjectId AND
                         m.studentId = s.studentId AND
                         m.registrationId = d.registrationId AND
                         m.instituteId = '$instituteId' AND
                         sub.subjectTypeId = gt.groupTypeId
                         $conditions                         
                   GROUP BY
                         d.classId, d.subjectId
                   HAVING
                         totalStudent > 0    
                   ORDER BY
                         $orderBy";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
// Author :Parveen Sharma 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getRegistrationStudentList($conditions='', $limit = '', $orderBy=' c.className,sub.subjectCode') {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT   
                         d.classId, c.className, sub.subjectId, sub.subjectName, sub.subjectCode, gt.groupTypeId, s.studentId, 
                         CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                         IFNULL(s.universityRollNo,'---') AS universityRollNo, IFNULL(s.rollNo,'---') AS rollNo,
                         IFNULL(COUNT(s.studentId),0) AS totalStudent, IFNULL(grp.groupId,-1) AS groupId
                  FROM
                        `student_registration_master` m, `subject` sub,
                        `student` s, class c, group_type gt,
                        `student_registration_detail` d  LEFT JOIN `group` grp ON 
                         grp.classId=d.classId AND grp.optionalSubjectId = d.subjectId  
                  WHERE
                         d.classId=c.classId AND 
                         sub.subjectId = d.subjectId AND
                         m.studentId = s.studentId AND
                         m.registrationId = d.registrationId AND
                         m.instituteId = '$instituteId' AND
                         sub.subjectTypeId = gt.groupTypeId
                         $conditions      
                   GROUP BY
                        d.classId, c.className, sub.subjectId, sub.subjectName, sub.subjectCode, gt.groupTypeId,s.studentId
                   HAVING
                        totalStudent > 0    
                   ORDER BY
                        $orderBy";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
    
    public function getCheckAttendanceList($conditions='') {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT att.studentId
                  FROM  
                        ".ATTENDANCE_TABLE." att 
                  WHERE 
                  $conditions";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
     
    
    public function getCheckTestList($conditions='') {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT t.employeeId
                  FROM  
                        ".TEST_TABLE." t
                  WHERE 
                       t.instituteId = $instituteId
                  $conditions ";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    
    public function getTimeTableList($conditions='') {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT t.employeeId
                  FROM  
                         ".TIME_TABLE_TABLE." t
                  WHERE 
                       t.instituteId = $instituteId
                  $conditions ";
                   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");      
    }    
    
    
    //--------------------------------------------------------------
//  THIS FUNCTION IS Optional Subjectwise Group added
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function checkOptionalSubjectGroup($condition='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT 
                        groupId, groupName, groupShort 
                  FROM 
                        `group` 
                  WHERE 
                        $condition";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }
    
    
     public function checkStudentOptionalSubjectGroup($condition='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        subjectId, studentId, classId, groupId
                  FROM 
                        `student_optional_subject`
                  WHERE
                        $condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
    }   
    
} 
  
?>