<?php
//-------------------------------------------------------------------------------
//
//StudentReportsManager .
// Author : Jaineesh
// Created on : 07.07.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class TranscriptReportManager {
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
  
    public function getStudentCGPACal($studentId='',$condition='') {
        global $sessionHandler;
      
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                               ROUND(SUM(gradeIntoCredits)/sum(credits),3) as cgpa
                                         
                    from        student_cgpa
                    where      instituteId = $instituteId
                    and        classId IN (select classId from ".TOTAL_TRANSFERRED_MARKS_TABLE." where studentId = $studentId and holdResult = 0)
                    and        classId in (select classId from time_table_classes where holdCompreMarks = 0  and holdPreCompreMarks = 0) 
                    and studentId = $studentId $condition";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }
   
    

    public function getStudentClassList($rollNo='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       c.classId,c.className, sp.periodName, sp.periodValue, sp.studyPeriodId 
                  FROM 
                       class c, study_period sp 
                  WHERE 
                       c.studyPeriodId=sp.studyPeriodId AND
                       c.instituteId = '$instituteId' AND
                       c.isActive IN (1,3) AND
                           CONCAT_WS(',',c.degreeId,c.batchId,c.branchId) IN 
                                (SELECT 
                                    CONCAT_WS(',',cc.degreeId,cc.batchId,cc.branchId)
                                  FROM
                                      class cc, student s WHERE cc.classId = s.classId AND (s.universityRollNo LIKE '".$rollNo."' OR 
                                                s.rollNo LIKE '".$rollNo."'))
                  ORDER BY
                       sp.periodValue ASC";
                                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }
  
  
  public function getStudentSubjectList($classId='',$studentId='',$condition='') {
      
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                    DISTINCT b.classId, b.subjectId, b.credits, sub.subjectCode, sub.subjectName,
                    c.className, c.studyPeriodId, sp.periodName, sp.periodValue, pc.periodicityName, pc.periodicityCode
                FROM 
                    ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, `subject` sub, 
                    class c, study_period sp, periodicity pc
                WHERE    
                    a.classId = b.classId AND a.subjectId = b.subjectId AND a.studentId = '$studentId'
                    AND b.optional=0 AND b.classId = '$classId'
                    AND b.subjectId = sub.subjectId  AND pc.periodicityId = sp.periodicityId 
                    AND b.classId = c.classId AND sp.studyPeriodId = c.studyPeriodId
                    $condition
                UNION
                SELECT 
                    DISTINCT b.classId, b.subjectId, b.credits, sub.subjectCode, sub.subjectName,
                    c.className, c.studyPeriodId, sp.periodName, sp.periodValue, pc.periodicityName, pc.periodicityCode
                FROM 
                    ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b, 
                    optional_subject_to_class oc, `subject` sub, class c, study_period sp, periodicity pc  
                WHERE
                    a.classId = b.classId AND a.subjectId = b.subjectId AND a.studentId = '$studentId'
                    AND oc.classId = b.classId AND b.optional=1 
                    AND oc.parentOfSubjectId = b.subjectId  AND b.classId = '$classId'
                    AND b.subjectId = sub.subjectId AND pc.periodicityId = sp.periodicityId
                    AND b.classId = c.classId AND sp.studyPeriodId = c.studyPeriodId
                    $condition
                UNION
                SELECT 
                    DISTINCT b.classId, b.subjectId, b.credits, sub.subjectCode, sub.subjectName,
                    c.className, c.studyPeriodId, sp.periodName, sp.periodValue, pc.periodicityName, pc.periodicityCode
                FROM 
                    ".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject_to_class b,
                     student_optional_subject oc, `subject` sub, class c, study_period sp, periodicity pc  
                WHERE    
                    a.classId = b.classId AND a.subjectId = b.subjectId AND a.studentId = '$studentId'
                    AND oc.classId = b.classId AND b.optional=1 
                    AND oc.subjectId = b.subjectId  AND b.classId = '$classId' 
                    AND oc.studentId = '$studentId' AND b.subjectId = sub.subjectId
                    AND b.classId = c.classId AND sp.studyPeriodId = c.studyPeriodId
                    AND pc.periodicityId = sp.periodicityId
                    $condition
                 ORDER BY
                    subjectCode, subjectName ";
                                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  } 
  
  public function getStudentGradeList($classId='',$studentId='',$condition='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                      a.classId, a.subjectId, a.studentId, a.gradeId,
                      IF(IFNULL(b.gradeLabel,'')='','I',b.gradeLabel) as gradeLabel, b.gradePoints,
                      a.isActive, '-1' AS updatedExamType
                  FROM
                      ".TOTAL_TRANSFERRED_MARKS_TABLE." a LEFT JOIN grades b ON a.gradeId = b.gradeId
                  WHERE 
                      a.isActive = 1
                      AND a.studentId = '$studentId'
                      AND a.classId = '$classId' 
                  $condition
                  GROUP BY
                     a.classId, a.subjectId
                  UNION
                  SELECT
                      a.classId, a.subjectId, a.studentId, a.gradeId,
                      IF(IFNULL(b.gradeLabel,'')='','I',b.gradeLabel) as gradeLabel, b.gradePoints,
                      a.isActive, '1' AS updatedExamType
                  FROM
                      ".TOTAL_UPDATED_MARKS_TABLE." a LEFT JOIN grades b ON a.gradeId = b.gradeId
                  WHERE 
                      a.isActive = 1
                      AND a.studentId = '$studentId'
                      AND a.classId = '$classId' 
                  $condition
                  GROUP BY
                     a.classId, a.subjectId
                  ORDER BY 
                     classId, subjectId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }
  
  
   public function getStudentTransferClassList($studentId='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query =  "SELECT
                      DISTINCT c.classId, c.className, sp.periodValue, c.batchId, c.degreeId, c.branchId, c.studyPeriodId
                  FROM
                      ".TOTAL_TRANSFERRED_MARKS_TABLE." a, class c, study_period sp
                  WHERE 
                     a.isActive = 1 
                     AND a.studentId = '$studentId' AND c.classId = a.classId 
                     AND sp.studyPeriodId = c.studyPeriodId
                  UNION
                  SELECT
                      DISTINCT c.classId, c.className, sp.periodValue, c.batchId, c.degreeId, c.branchId, c.studyPeriodId
                  FROM
                      ".TOTAL_UPDATED_MARKS_TABLE." a, class c, study_period sp 
                  WHERE 
                     a.isActive = 1 
                     AND a.studentId = '$studentId' AND c.classId = a.classId 
                     AND sp.studyPeriodId = c.studyPeriodId
                  ORDER BY 
                     periodValue ASC";
                                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

}
?>
