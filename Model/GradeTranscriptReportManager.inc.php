<?php
//-------------------------------------------------------------------------------
//
//StudentReportsManager .
// Author : Jaineesh
// Created on : 07.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GradeTranscriptReportManager {
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
    //function created for fetching students matching conditions
    // Author :Parveen Sharma
    // Created on : 23-Sep-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
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

	


//----------------------------------------------------------------------------------------------------
	//function created for counting students matching conditions

	// Author :Ajinder Singh
	// Created on : 13-Sep-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function countRecords($conditions='') {
		global $sessionHandler;
		$query = "SELECT
							COUNT(*) AS cnt
				  FROM		student a, class b, university c, degree d,  branch e, study_period f
				  WHERE		a.classId = b.classId
				  AND		b.universityId = c.universityId
				  AND		b.degreeId = d.degreeId
				  AND		b.branchId = e.branchId
				  AND		b.studyPeriodId = f.studyPeriodId
				  AND		b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
				  AND		b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
							$conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


       


        public function getTestTypeCategoryCount($studentId,$classId,$subjectIds,$condition=''){
                global $sessionHandler;

    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');

    $query="
            SELECT
             MAX( t1.categoryCount ) AS categoryCount,
             t1.testTypeName,t1.testTypeCategoryId,
             t1.testTypeAbbr
            FROM (
              SELECT
                    t.testAbbr, t.subjectId, ttc.testTypeName, count( ttc.testTypeName ) AS categoryCount,
                    ttc.testTypeCategoryId ,
                    ttc.testTypeAbbr
              FROM
                    ".TEST_TABLE." t, ".TEST_MARKS_TABLE." tm, test_type_category ttc, `subject` sub
              WHERE
                    t.testId = tm.testId
                    AND t.subjectId = tm.subjectId
                    AND t.subjectId = sub.subjectId
                    AND t.testTypeCategoryId = ttc.testTypeCategoryId
                    AND tm.studentId =$studentId
                    AND t.classId =$classId
                    AND t.instituteId=$instituteId
                    AND t.sessionId=$sessionId
                    AND t.subjectId
                    IN ( $subjectIds )
                    $condition
                    GROUP BY ttc.testTypeCategoryId, t.subjectId
                    ORDER BY t.subjectId
                    ) AS t1
                    GROUP BY t1.testTypeName
                    ORDER BY t1.testTypeCategoryId
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


    public function getTestTypesDetails($testTypeCategoryIds){

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $query="SELECT
                      tt.testTypeId,
                      tt.testTypeName,
                      tt.testTypeCode
                FROM
                      test_type tt
                WHERE
                      testTypeCategoryId IN ($testTypeCategoryIds)
                ORDER BY tt.testTypeId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



      public function getSubjectWiseTestTransferredMarks($testTypeIds,$studentId,$classId,$subjectIds,$condition=''){
            global $sessionHandler;

            $instituteId = $sessionHandler->getSessionVariable('InstituteId');
            $sessionId = $sessionHandler->getSessionVariable('SessionId');

            $query="SELECT
                       ttt.classId, ttt.subjectId, ttt.studentId, ttt.conductingAuthority, ttt.testTypeCategoryId,
                       ttt.maxMarks, ttt.marksScored, ttt.gradeId, ttt.gradePoints, ttt.gradeLabel 
                    FROM
                        (SELECT
                              ttf.classId, ttf.subjectId, ttf.studentId, tt.conductingAuthority, tt.testTypeCategoryId,
                              SUM(ttf.maxMarks) AS maxMarks, SUM(ttf.marksScored) AS marksScored,
                              ttf.gradeId, gr.gradePoints, IF(IFNULL(gr.gradeLabel,'')='','I',gr.gradeLabel) AS gradeLabel
                        FROM
                              test_type tt, 
                              ".TEST_TRANSFERRED_MARKS_TABLE." ttf  LEFT JOIN grades gr ON (ttf.gradeId = gr.gradeId AND gr.instituteId = $instituteId)
                        WHERE
                              ttf.testTypeId IN ($testTypeIds)
                              AND ttf.studentId=$studentId
                              AND ttf.classId=$classId
                              AND ttf.subjectId in ($subjectIds)
                              AND ttf.testTypeId=tt.testTypeId
                              $condition
                        GROUP BY
                               ttf.classId, ttf.subjectId, ttf.studentId, tt.conductingAuthority, tt.testTypeCategoryId) AS ttt
                    ORDER BY ttt.testTypeCategoryId, ttt.subjectId";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }




   //-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
    public function getSubjectTransferredDetails($condition='') {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
        $query = "SELECT 
                        CONCAT(stu.firstName,' ',stu.lastName) as studentName,stu.rollNo,stu.universityRollNo,s.subjectCode, 
                        ttm.classId, IF(tgm.graceMarks IS NULL,ceil(sum( marksScored )),ceil((sum(marksScored) + tgm.graceMarks))) AS tmarksScored, 
                        ttm.subjectId,  SUM(ttm.marksScored) AS marksScored, SUM(ttm.maxMarks) AS maxMarks,  gr.gradePoints, gr.gradeLabel
                   FROM  
                        time_table_classes ttc, subject s,student stu,time_table_labels ttl,
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm 
                        LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON (tgm.classId = ttm.classId AND ttm.subjectId = tgm.subjectId AND ttm.studentId = tgm.studentId)
                        LEFT JOIN grades gr ON (ttm.gradeId = gr.gradeId AND gr.instituteId = $instituteId)
                   WHERE 
                        ttm.classId = ttc.classId
                        AND stu.studentId = ttm.studentId
                        AND ttm.conductingAuthority IN (1)
                        AND ttc.timeTableLabelId=ttl.timeTableLabelId
                        AND ttm.subjectId = s.subjectId
                        AND ttl.instituteId = $instituteId    
                        $condition
                   GROUP BY 
                        ttm.subjectId, ttm.studentId, ttm.classId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
      



      public function getAttendanceGradeDeduct($condition=''){
               global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');

        $query="SELECT
                    attendanceGradeId, minval,maxval,`point`
                FROM 
                     attendance_grade_deduct
                WHERE
                    instituteId=$instituteId AND 
                    sessionId=$sessionId 
                $condition";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

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



            public function getStudentAttendanceReport($condition='',$orderBy='',$consolidated='',$limit='',$percentCondition='')  {
                      global $REQUEST_DATA;
                      global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        if($orderBy=='') {
          $orderBy = "subjectName";
        }

        $groupByField1 ='';
        $groupByField2 ='';
        $groupBy='';
        if($consolidated=='') {
          $groupByField1 = " ,tt.groupId, tt.groupName";
          $groupByField2 = " ,t.groupId, t.groupName";
          $groupBy = " ,tt.groupId";
        }

        $query = "SELECT
                        t.studentId, t.classId, t.subjectId, t.subjectCode, t.subjectName, t.className, t.studentName,
                        IFNULL(t.employeeName,'".NOT_APPLICABLE_STRING."') AS employeeName,
                        t.subjectTypeId, t.subjectTypeName, t.rollNo, t.universityRollNo,
                        CONCAT(t.subjectName,' (',t.subjectCode,')') AS subjectName1, t.periodName,
                        t.fromDate, t.toDate, t.lectureAttended AS attended, t.lectureDelivered AS delivered, t.leaveTaken,
                        IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100) AS per,
                        IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) AS per1
                        $groupByField2
                  FROM
                     (SELECT

                             tt.studentId, tt.classId, tt.subjectId, tt.subjectCode, tt.subjectName, tt.className, tt.studentName,
                             tt.rollNo, tt.universityRollNo, MIN(tt.fromDate) AS fromDate, MAX(tt.toDate) AS toDate,  tt.periodName,
                             GROUP_CONCAT(DISTINCT tt.employeeName SEPARATOR ', ')  AS employeeName,
                             tt.subjectTypeId, tt.subjectTypeName,
                             IFNULL(SUM(tt.lectureAttended),0) AS lectureAttended, IFNULL(SUM(tt.lectureDelivered),0) AS lectureDelivered,
                             IFNULL(SUM(tt.leaveTaken),0) AS leaveTaken  $groupByField1
                      FROM
                         (SELECT
                                att.classId, att.subjectId, att.groupId, att.studentId, su.subjectCode, su.subjectName, c.className,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                st.subjectTypeId, st.subjectTypeName,
                                IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId, grp.groupName,
                                att.fromDate, att.toDate, IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber,
                                IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
                                IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2,(ac.attendanceCodePercentage/100),att.lectureAttended)) AS lectureAttended,
                                IF(att.isMemberOfClass=0, '', att.lectureDelivered) AS lectureDelivered, sp.periodName,
                                (SELECT
                                      GROUP_CONCAT(DISTINCT e.employeeName,' (',e.employeeCode,')' SEPARATOR ', ')
                                 FROM
                                      employee e,  ".TIME_TABLE_TABLE."  tt
                                 WHERE
                                      e.employeeId = tt.employeeId AND tt.employeeId = att.employeeId AND
                                      tt.classId=att.classId  AND tt.subjectId = att.subjectId AND
                                      tt.groupId=att.groupId AND tt.toDate IS NULL)  AS employeeName,
                                IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                        (SELECT
                                                   DISTINCT IF(IFNULL(dl.dutyLeaveId,'')='','',1)
                                            FROM
                                                    ".DUTY_LEAVE_TABLE."  dl
                                            WHERE
                                                   dl.studentId = att.studentId AND
                                                   dl.classId   = att.classId   AND
                                                   dl.subjectId = att.subjectId AND
                                                   dl.groupId   = att.groupId   AND
                                                   dl.periodId  = att.periodId  AND
                                                   att.fromDate = dl.dutyDate   AND
                                                   att.toDate   = dl.dutyDate   AND
                                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."),''),'')),'') AS leaveTaken
                          FROM
                                group_type gt, `group` grp, class c, study_period sp, subject_type st, `subject` su,
                                student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                                LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                                LEFT JOIN period p ON att.periodId = p.periodId
                          WHERE
                                sp.studyPeriodId = c.studyPeriodId AND
                                gt.groupTypeId = grp.groupTypeId  AND
                                att.groupId   = grp.groupId       AND
                                att.subjectId = su.subjectId      AND
                                st.subjectTypeId = su.subjectTypeId AND
                                att.classId   = c.classId
                          $condition) AS tt
                       GROUP BY
                          tt.studentId, tt.classId, tt.subjectId $groupBy) AS t
                  $percentCondition
                  ORDER BY
                        $orderBy $limit";
	//	echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }




      public function getStudent($condition=''){
              global $sessionHandler;
     $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     $sessionId   = $sessionHandler->getSessionVariable('SessionId');
 
     $query="SELECT 
                  *  
             FROM 
                 student
              WHERE 
                 classId IN(SELECT 
                                 classId 
                            FROM
                                class
                            WHERE
                                batchId=$batchId AND
                                degreeId=$degreeId AND
                                branchId=$branchId AND
                                IsActive IN (1,2,3));
              $condition ";
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
   
   
    public function getSubjectsClassWiseCount($condition='') {
       
       global $sessionHandler;    
         
       $query = "SELECT 
                       t.classId, COUNT(t.subjectId) AS subjectCount, t.className
                  FROM 
                    (SELECT 
                            b.subjectId, b.subjectCode, b.subjectName, b.hasAttendance, b.hasMarks,
                            a.classId, a.optional, a.credits, d.className 
                     FROM 
                            subject_to_class a, `subject` b, class d
                     WHERE 
                            a.subjectId = b.subjectId AND
                            a.classId = d.classId AND 
                            d.isActive IN (1,2,3) AND
                            a.optional = 0
                     $condition
                     UNION
                     SELECT 
                            b.subjectId, b.subjectCode, b.subjectName, b.hasAttendance, b.hasMarks,
                            a.classId, a.optional, a.credits , d.className
                     FROM 
                            student_optional_subject ss, subject_to_class a, `subject` b, class d
                     WHERE 
                            a.subjectId = ss.subjectId AND
                            ss.classId = a.classId AND
                            a.subjectId = b.subjectId AND
                            a.classId = d.classId AND 
                            d.isActive IN (1,2,3) AND
                            a.optional = 1
                     $condition) AS t
                  GROUP BY
                       t.classId ";
                         
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   
   public function getStudentTestTypeCategoryCount($classId='',$subjectIds='0',$studentId=''){
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        if($subjectIds=='') {
          $subjectIds=0;  
        }

        $query="
                SELECT  
                        DISTINCT MAX(t1.categoryCount ) AS categoryCount, t1.testTypeName,t1.testTypeCategoryId, t1.testTypeAbbr
                FROM (
                  SELECT
                        t.testAbbr, t.subjectId, ttc.testTypeName, count( ttc.testTypeName ) AS categoryCount,
                        ttc.testTypeCategoryId, ttc.testTypeAbbr, tm.maxMarks
                  FROM
                        ".TEST_TABLE." t, ".TEST_MARKS_TABLE." tm, test_type_category ttc, `subject` sub
                  WHERE
                        t.testId = tm.testId
                        AND tm.studentId = '$studentId'
                        AND t.subjectId = tm.subjectId
                        AND t.subjectId = sub.subjectId
                        AND t.testTypeCategoryId = ttc.testTypeCategoryId
                        AND t.classId ='$classId'
                        AND t.instituteId='$instituteId'
                        AND t.sessionId='$sessionId'
                        AND t.subjectId
                        IN ( $subjectIds )
                        GROUP BY ttc.testTypeCategoryId, t.subjectId, t.classId, tm.studentId, tm.subjectId
                        ORDER BY t.subjectId
                        ) AS t1
                        GROUP BY t1.testTypeName
                        ORDER BY t1.testTypeCategoryId
               ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
    
   
   
}

?>

