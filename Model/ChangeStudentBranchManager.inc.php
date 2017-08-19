<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ChangeStudentBranchManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ChangeStudentBranchManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ChangeStudentBranchManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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


public function getSelectedTimeTableClassesForBranchChange() {
        global $sessionHandler;
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT 
                            DISTINCT cvtr.classId 
                    FROM    
                            classes_visible_to_role cvtr
                    WHERE   
                            cvtr.userId = $userId
                            AND cvtr.roleId = $roleId";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);
        $inClassesConditions='';
        if($count>0){
            $classIds=UtilityManager::makeCSList($result,'classId');
            $inClassesConditions=' AND c.classId IN ('.$classIds.')';
        }
        $query = "
                   SELECT 
                          DISTINCT(ttc.classId) AS classId,c.className
                   FROM    
                          time_table_classes ttc, class c,time_table_labels ttl
                   WHERE
                          ttl.timeTableLabelId=ttc.timeTableLabelId
                          AND ttl.isActive=1
                          AND ttc.classId = c.classId 
                          AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                          AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                          $inClassesConditions
                          AND c.classId NOT IN (SELECT DISTINCT classId FROM ".ATTENDANCE_TABLE.")
                          AND c.classId NOT IN (SELECT DISTINCT classId FROM ".TEST_TABLE.")
                          AND c.isFrozen=0
                   ORDER BY c.degreeId,c.branchId,c.studyPeriodId";
        
        return $systemDatabaseManager->executeQuery($query, "Query: $query");
        
    }
    
public function getEquivalentClassesWithDifferentBrach($classId){
    global $sessionHandler;
    $userId= $sessionHandler->getSessionVariable('UserId');
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    $systemDatabaseManager = SystemDatabaseManager::getInstance();

    $query = "SELECT 
                        DISTINCT cvtr.classId 
                FROM    
                        classes_visible_to_role cvtr
                WHERE   
                        cvtr.userId = $userId
                        AND cvtr.roleId = $roleId";

    $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
    $count = count($result);
    $inClassesConditions='';
    if($count>0){
        $classIds=UtilityManager::makeCSList($result,'classId');
        $inClassesConditions=' AND c1.classId IN ('.$classIds.')';
    }
    
    $query="SELECT
                  DISTINCT c1.classId,c1.className
            FROM
                  class c1,class c2
            WHERE
                  c1.instituteId=c2.instituteId
                  AND c1.universityId=c2.universityId
                  AND c1.batchId=c2.batchId
                  AND c1.degreeId=c2.degreeId
                  AND c1.sessionId=c2.sessionId
                  AND c1.studyPeriodId=c2.studyPeriodId
                  AND c1.branchId!=c2.branchId
                  AND c2.classId=$classId
                  AND c1.classId NOT IN (SELECT DISTINCT classId FROM ".ATTENDANCE_TABLE.")
                  AND c1.classId NOT IN (SELECT DISTINCT classId FROM ".TEST_TABLE.")
                  $inClassesConditions
            ";
            
     return $systemDatabaseManager->executeQuery($query,"Query: $query");             
}
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getStudentList($conditions='', $limit = '', $orderBy=' s.studentName') {
     
        $query = "SELECT 
                        DISTINCT
                                s.studentId,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                s.rollNo,
                                s.universityRollNo,
                                s.classId AS newClassId,
                                c.classId
                  FROM
                       student s,class c
                  WHERE
                       s.classId=c.classId
                       $conditions 
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalStudents($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM
                       student s,class c
                  WHERE
                       s.classId=c.classId
                       $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function checkAtttendance($classIds) {
    
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM
                       ".ATTENDANCE_TABLE."
                  WHERE
                      classId IN (".$classIds.")
                        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
   public function checkTest($classIds) {
    
        $query = "SELECT 
                        COUNT(*) AS cnt
                  FROM
                       ".TEST_TABLE."
                  WHERE
                      classId IN (".$classIds.")
                        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
   public function updateStudentClass($studentId,$classId) {
    
        $query = "UPDATE 
                        student
                  SET
                        classId=$classId
                  WHERE
                      studentId=$studentId
                        ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
  
  public function deleteStudentCompulsoryGroupAllocation($studentId,$classId) {
    
        $query = "DELETE 
                  FROM
                        student_groups
                  WHERE
                      studentId=$studentId
                      AND classId=$classId
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
  }
  
  public function deleteStudentOptionalGroupAllocation($studentId,$classId) {

        $query = "DELETE 
                  FROM
                        student_optional_subject
                  WHERE
                      studentId=$studentId
                      AND classId=$classId
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
  }
  
}
// $History: ChangeStudentBranchManager.inc.php $
?>