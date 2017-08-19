<?php
//-------------------------------------------------------------------------------

//StudentReportsManager .
// Author : Ipta Thakur
// Created on : 04.11.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FinalTranscriptReport {
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

     

public function getStudentFinalCGPAGrade($condition=''){
               global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');

        $query="SELECT
                    finalGradeId, minval, maxval, grade, `point`
                FROM 
                     final_grade
                 WHERE
                    instituteId=$instituteId AND 
                    sessionId=$sessionId 
                 $condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    } 


public function getSingleField($table, $field, $conditions='') {
		$query = "SELECT $field FROM $table $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	       }

public function getScStudentGradeDetails($studentId='',$condition='',$orderBy=' classId, subjectCode') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "
                SELECT
                                a.classId,
                                a.subjectId,
                                a.studentId,
                                a.gradeId,
                                if(b.gradeLabel is null, 'I', b.gradeLabel) as gradeLabel,
                                c.subjectCode,
                                c.subjectName,
                                d.periodName,
                                f.credits,
                                b.gradePoints,
                                a.isActive,
                                '-1' AS updatedExamType
                FROM
                                subject c,
                                study_period d,
                                class e,
                                subject_to_class f,
                                time_table_classes g,
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a left join grades b on (a.gradeId = b.gradeId AND b.instituteId = $instituteId)
                WHERE            a.subjectId = c.subjectId
                AND                a.classId = e.classId
                AND                d.studyPeriodId = e.studyPeriodId
                AND                a.classId = f.classId
                AND                a.subjectId = f.subjectId
                AND                a.holdResult = 0
                AND                a.classId = g.classId
                AND                g.holdCompreMarks = 0
                AND             a.isActive = 1
                AND a.studentId = $studentId
                                $condition
                group by        a.subjectId,a.classId
                UNION
                SELECT
                                a.classId,
                                a.subjectId,
                                a.studentId,
                                a.gradeId,
                                if(b.gradeLabel is null, 'I', b.gradeLabel) as gradeLabel,
                                c.subjectCode,
                                c.subjectName,
                                d.periodName,
                                f.credits,
                                b.gradePoints,
                                a.isActive,
                                '1' AS updatedExamType
                FROM
                                subject c,
                                study_period d,
                                class e,
                                subject_to_class f,
                                time_table_classes g,
                                ".TOTAL_UPDATED_MARKS_TABLE." a left join grades b on (a.gradeId = b.gradeId AND b.instituteId = $instituteId)
                WHERE           a.subjectId = c.subjectId
                AND             a.classId = e.classId
                AND             d.studyPeriodId = e.studyPeriodId
                AND             a.classId = f.classId
                AND             a.subjectId = f.subjectId
                AND             a.classId = g.classId
                AND             g.holdCompreMarks = 0
                     AND                  a.holdResult = 0
                AND             a.isActive = 1
                AND a.studentId = $studentId
                                $condition
                group by        a.subjectId,a.classId
                ORDER BY       $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}




