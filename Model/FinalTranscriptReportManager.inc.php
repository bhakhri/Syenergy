<?php
//-------------------------------------------------------------------------------
//StudentReportsManager .
// Author : Ipta Thakur
// Created on : 04.11.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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

public function getClassSubjectsWithOtherSubjects($condition='') {
       
       global $sessionHandler;    
         
	   $query = "SELECT 
                        b.subjectId, b.subjectCode, b.subjectName, b.hasAttendance, b.hasMarks,
                        a.classId, a.optional, a.credits, s.sessionName 
                 FROM 
                        subject_to_class a, `subject` b, class d, `session` s
	             WHERE 
                        a.subjectId = b.subjectId AND
                        a.classId = d.classId AND 
                        d.isActive IN (1,2,3) AND
			d.sessionId = s.sessionId AND 
                        a.optional = 0 
                 $condition
                 UNION
                 SELECT 
                        b.subjectId, b.subjectCode, b.subjectName, b.hasAttendance, b.hasMarks,
                        a.classId, a.optional, a.credits, s.sessionName  
                 FROM 
                        student_optional_subject ss, subject_to_class a, `subject` b, class d, `session` s
                 WHERE 
                        a.subjectId = ss.subjectId AND
                        ss.classId = a.classId AND
                        a.subjectId = b.subjectId AND
                        a.classId = d.classId AND 
                        d.isActive IN (1,2,3) AND
			d.sessionId = s.sessionId AND 
                        a.optional = 1
                 $condition
	             ORDER BY
                        classId, subjectCode";
            //echo $query;
            //die;             
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
 
public function getAllDetailsStudentList($conditions='', $order=' rollNo', $limit='') {
        global $sessionHandler;
        $query = "SELECT
                            DISTINCT  CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                            IF(a.rollNo='','".NOT_APPLICABLE_STRING."',a.rollNo) AS rollNo,
                            CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) as programme,
                            f.periodName,
                            a.classId as class_id,
                            a.studentId,
                            IF(a.studentEmail='','".NOT_APPLICABLE_STRING."',a.studentEmail) AS studentEmail,
                            universityRollNo,
                            IF(a.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = a.corrCityId)) AS corrCityId,
                            IF(a.classId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT periodName FROM study_period sp, class cls WHERE sp.studyPeriodId = cls.studyPeriodId and cls.classId = a.classId)) AS studyPeriod,
                            IF(a.studentMobileNo='','".NOT_APPLICABLE_STRING."',a.studentMobileNo) AS studentMobileNo ,
                            IF(IFNULL(corrAddress1,'')='','', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=a.corrCityId),' ',(SELECT stateName from states where states.stateId=a.corrStateId),' ',(SELECT countryName from countries where countries.countryId=a.corrCountryId),IF(IFNULL(a.corrPinCode,'')='','',CONCAT('-',a.corrPinCode)))) AS corrAddress,
                            IF(IFNULL(permAddress1,'')='','', CONCAT(permAddress1,' ',IFNULL(permAddress2,''),' ',(SELECT cityName from city where city.cityId=a.permCityId),' ',(SELECT stateName from states where states.stateId=a.permStateId),' ',(SELECT countryName from countries where countries.countryId=a.permCountryId),IF(IFNULL(a.permPinCode,'')='','',CONCAT('-',a.permPinCode)))) AS permAddress,
                            fatherName AS fatherName, dateOfBirth AS DOB, studentPhoto AS Photo,
                            SUBSTRING_INDEX(b.classname,'".CLASS_SEPRATOR."',-4)  AS className, bch.endDate
                  FROM
                            student_groups ss, student a, class b,
                            university c, degree d,  branch e, study_period f, batch bch
                  WHERE
                            ss.studentId = a.studentId
                            AND ss.classId = b.classId
                            AND bch.batchId = b.batchId
                            AND bch.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                            AND b.universityId = c.universityId
                            AND b.degreeId = d.degreeId
                            AND b.branchId = e.branchId
                            AND b.studyPeriodId = f.studyPeriodId
                            AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions
                  ORDER BY  $order $limit";

        // AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


}