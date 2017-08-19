<?php
//-------------------------------------------------------------------------------
//
//	Class Wise Evaluation Reports Manager
// 	Author : Aditi Miglani
// 	Created on : 09 Aug 2011
// 	Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ClassWiseEvaluationReportManager {
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


//----------------------------------------------------------------------------------------------------
//function created for fetching subjects and subject Types
// Author :Parveen Sharma
// Created on : 04-12-08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function getAllSubjectAndSubjectTypes($conditions='', $orderBy=' classId, subjectTypeId, subjectCode',$sortBy='ASC') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query ="SELECT
                          DISTINCT su.hasAttendance, su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId
                 FROM
                           group_type gt, subject_type st, `subject` su,
                           subject_to_class sc LEFT JOIN  ".TIME_TABLE_TABLE."  tt ON sc.subjectId = tt.subjectId
                                AND tt.toDate IS NULL AND tt.sessionId=".$sessionId." AND tt.instituteId=".$instituteId."
                           LEFT JOIN `class` c ON sc.classId = c.classId
                           LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                 WHERE
                           su.subjectId=sc.subjectId
                           AND st.subjectTypeId = su.subjectTypeId
                           AND su.hasAttendance = 1
                           AND c.instituteId=".$instituteId."
                           AND c.sessionId=".$sessionId."
                           AND c.isActive IN (1,3)
                           AND sc.hasParentCategory=0
                           AND g.groupId IS NOT NULL
                           AND su.subjectId IS NOT NULL
                           AND gt.groupTypeId = g.groupTypeId
                 $conditions
                 $groupBy
                 UNION
                 SELECT
                           DISTINCT su.hasAttendance, su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId
                 FROM
                           group_type gt, subject_type st, `subject` su,
                           student_optional_subject sc LEFT JOIN  ".TIME_TABLE_TABLE."  tt ON sc.subjectId = tt.subjectId
                                AND tt.toDate IS NULL AND tt.sessionId=".$sessionId." AND tt.instituteId=".$instituteId."
                           LEFT JOIN `class` c ON sc.classId = c.classId
                           LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                 WHERE
                           su.subjectId=sc.subjectId
                           AND st.subjectTypeId = su.subjectTypeId
                           AND su.hasAttendance = 1
                           AND c.instituteId=".$instituteId."
                           AND c.sessionId=".$sessionId."
                           AND c.isActive IN (1,3)
                           AND g.groupId IS NOT NULL
                           AND su.subjectId IS NOT NULL
                           AND gt.groupTypeId = g.groupTypeId
                 $conditions
                 $groupBy
                 ORDER BY $orderBy $sortBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
    
    public function getEmployeeGroupList($conditions='', $orderBy=' classId, subjectTypeId, subjectCode, employeeName, groupName',$sortBy='ASC') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query ="SELECT
                      t.classId, t.subjectId, t.subjectTypeId, t.employeeId, t.groupName, t.employeeName, t.subjectCode
                 FROM
                     (SELECT
                              tt.classId, su.subjectId, tt.employeeId,  su.subjectTypeId, su.subjectCode,
                              CONCAT(employeeName,' (',employeeCode,')') AS employeeName,
                              GROUP_CONCAT(DISTINCT g.groupName ORDER BY g.groupName SEPARATOR ', ') AS groupName
                      FROM
                               employee emp, `subject` su,  ".TIME_TABLE_TABLE."  tt, `group` g 
                      WHERE                               
                               tt.toDate IS NULL AND
                               g.groupId = tt.groupId AND
                               tt.subjectId = su.subjectId AND
                               emp.employeeId = tt.employeeId
                      $conditions    
                      GROUP BY 
                             tt.classId, su.subjectId, tt.employeeId ) AS t
                  ORDER BY
                       $orderBy $sortBy";
                         
         return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

//----------------------------------------------------------------------------------------------------
//general function created for one or more fields from one or more table with option of conditions

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSingleField($table, $field, $conditions='') {
		$query = "SELECT $field FROM $table $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}




}
?>

