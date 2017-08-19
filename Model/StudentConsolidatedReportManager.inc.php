<?php
//-------------------------------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "Student " TABLE
// Author : Alka
// Created on : 07.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudentConsolidatedReportManager {
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

    function getStudentList($condition='',$sortField=' universityRollNo',$orderBy='ASC') {
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                        DISTINCT 
                        s.studentId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        s.rollNo,s.universityRollNo, c.classId, c.className
                  FROM
                        class c, student s LEFT JOIN student_groups sg ON s.studentId = sg.studentId 
                  WHERE      
                        c.classId = sg.classId AND
                        c.isActive IN (1,3)
                        $condition 
                  ORDER BY
                        $sortField $orderBy ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    function getSubjectList($condition='',$groupBy='',$orderBy='subjectCode') {    
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query ="SELECT
                           DISTINCT su.subjectId, su.subjectCode, su.subjectName
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
                 $condition
                 $groupBy
                 UNION
                 SELECT
                           DISTINCT su.subjectId, su.subjectCode, su.subjectName
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
                 $condition
                 $groupBy
                 ORDER BY 
                    $orderBy $sortBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getStudentAttendanceReport($condition='',$orderBy='',$consolidated='',$limit='',$percentCondition='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $lowerMedicalLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT');
        $higherMedicalLimit=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
        
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
                        t.fromDate, t.toDate, t.lectureAttended AS attended, t.lectureDelivered AS delivered,
                        t.leaveTaken, t.medicalLeaveTaken,
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
                             IFNULL(SUM(tt.leaveTaken),0) AS leaveTaken ,
                             IFNULL(SUM(tt.medicalleaveTaken),0) AS medicalleaveTaken 
                             $groupByField1
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
                                                   DISTINCT IF(IFNULL(ml.medicalLeaveId,'')='',IF(IFNULL(dl.dutyLeaveId,'')='','',1),'')
                                            FROM
                                                    ".DUTY_LEAVE_TABLE."  dl LEFT JOIN  ".MEDICAL_LEAVE_TABLE."  ml ON 
                                                                   dl.studentId = ml.studentId AND
                                                                   dl.classId   = ml.classId   AND
                                                                   dl.subjectId = ml.subjectId AND
                                                                   dl.groupId   = ml.groupId   AND
                                                                   dl.periodId  = ml.periodId  AND
                                                                   dl.dutyDate = ml.medicalLeaveDate AND
                                                                   ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."  
                                            WHERE
                                                   dl.studentId = att.studentId AND
                                                   dl.classId   = att.classId   AND
                                                   dl.subjectId = att.subjectId AND
                                                   dl.groupId   = att.groupId   AND
                                                   dl.periodId  = att.periodId  AND
                                                   att.fromDate = dl.dutyDate   AND
                                                   att.toDate   = dl.dutyDate   AND
                                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."),''),'')),'') AS leaveTaken,
                                                   
                                 IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                        (SELECT
                                                   DISTINCT IF(IFNULL(dl.dutyLeaveId,'')='',IF(IFNULL(ml.medicalLeaveId,'')='','',1),'')
                                            FROM

                                                    ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN  ".DUTY_LEAVE_TABLE."  dl ON 
                                                                   dl.studentId = ml.studentId AND
                                                                   dl.classId   = ml.classId   AND
                                                                   dl.subjectId = ml.subjectId AND
                                                                   dl.groupId   = ml.groupId   AND
                                                                   dl.periodId  = ml.periodId  AND
                                                                   dl.dutyDate = ml.medicalLeaveDate AND
                                                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."   
                                            WHERE
                                                   ml.studentId = att.studentId AND
                                                   ml.classId   = att.classId   AND
                                                   ml.subjectId = att.subjectId AND
                                                   ml.groupId   = att.groupId   AND
                                                   ml.periodId  = att.periodId  AND
                                                   att.fromDate = ml.medicalLeaveDate  AND
                                                   att.toDate   = ml.medicalLeaveDate  AND
                                                   ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."),''),'')),'') AS medicalLeaveTaken                         
                                                   
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
    
        $resultArray= SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($resultArray===false) {
              return false;
        }
        else{
            if($consolidated!='') { 
                for($i=0;$i<count($resultArray);$i++) {
                      if($resultArray[$i]['per'] >= $lowerMedicalLimit && $resultArray[$i]['per'] <= $higherMedicalLimit) { 
                          $medicalLeaveTaken = $resultArray[$i]['medicalLeaveTaken'];
                          
                          for($j=1;$j<=$medicalLeaveTaken;$j++){
                              $attend = $resultArray[$i]['attended'];
                              $leaveTaken = $resultArray[$i]['leaveTaken'];
                              $delivered = $resultArray[$i]['delivered'];
                              if($delivered>0) {    
                              $resultArray[$i]['per']=(($attend+$leaveTaken+$j)/$delivered)*100;
                              if($resultArray[$i]['per']>=$higherMedicalLimit){
                                break;
                              }
                            }
                          }
                      }
                  }
                  return $resultArray; 
            }
            else{
                return $resultArray;
            }
        }
    }
   public function getConsolidatedMarksDetails($conditions,$conditions2,$sortField=' universityRollNo',$sortOrderBy=' ASC') {

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query = "SELECT
                    DISTINCT s.studentId,CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                    s.rollNo,s.universityRollNo, CEIL(SUM(ttm.maxMarks)) AS maxMarks,
                    SUM(ttm.marksScored) AS marksScored,sub.subjectCode,ttm.subjectId,sub.subjectTypeId,
                    IFNULL(sot.internalTotalMarks,'') as internalTotalMarks,IFNULL(sot.externalTotalMarks,'') as externalTotalMarks,
                    IFNULL((SELECT
			                     CONCAT(grd.gradeLabel,'%^%',grd.gradePoints)
 	                        FROM
			                    grades grd, grades_set grdset
	                        WHERE
		                        ttm.gradeId=grd.gradeId
		                        AND grd.gradeSetId=grdset.gradeSetId
		                        AND grdset.isActive=1),'') AS grade
              FROM
                    student_groups sg,class c, student s,`subject` sub,
                    ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, subject_to_class sot 
              WHERE
                    s.studentId=sg.studentId
                    and sg.classId=c.classId
                    and sub.subjectId = ttm.subjectId
                    and ttm.classId=sg.classId
                    and ttm.subjectId=sot.subjectId 
                    and ttm.classId=sot.classId
                    and ttm.studentId=s.studentId
                    and sot.optional = 0
              $conditions
              GROUP BY ttm.classId, ttm.subjectId,ttm.studentId, sg.groupId
              $conditions2      
              UNION
              SELECT
                    DISTINCT s.studentId,CONCAT(s.firstName,' ',s.lastName) AS studentName,s.rollNo,s.universityRollNo,
                    CEIL(SUM(DISTINCT ttm.maxMarks)) AS maxMarks,
                    (SUM(DISTINCT ttm.marksScored)) AS marksScored,sub.subjectCode,ttm.subjectId,sub.subjectTypeId,
                    IFNULL(sot.internalTotalMarks,'') as internalTotalMarks,IFNULL(sot.externalTotalMarks,'') as externalTotalMarks,
                    IFNULL((SELECT
                                 CONCAT(grd.gradeLabel,'%^%',grd.gradePoints)
                           FROM
                                grades grd,grades_set grdset
                          WHERE
                               ttm.gradeId=grd.gradeId
                               and grd.gradeSetId=grdset.gradeSetId
                               and grdset.isActive=1),'') AS grade
              FROM
                    student_groups sg,class c, student s,subject sub, subject_to_class sot, 
                    ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, optional_subject_to_class opt 
              WHERE
                    s.studentId=sg.studentId
                    AND ttm.subjectId=opt.subjectId 
                    AND ttm.classId=opt.classId
                    AND ttm.classId=sot.classId 
                    AND opt.parentOfSubjectId = sot.subjectId
                    and sg.classId=c.classId
                    and sub.subjectId = ttm.subjectId
                    and ttm.classId=sg.classId
                    and ttm.studentId=s.studentId
                    and sot.optional = 1
              $conditions
              GROUP BY ttm.classId, ttm.subjectId,ttm.studentId, sg.groupId
              $conditions2  
              UNION
              SELECT
                    DISTINCT s.studentId,CONCAT(s.firstName,' ',s.lastName) AS studentName,s.rollNo,s.universityRollNo,
                    CEIL(SUM(DISTINCT ttm.maxMarks)) AS maxMarks,
                    (SUM(DISTINCT ttm.marksScored)) AS marksScored,sub.subjectCode,ttm.subjectId,sub.subjectTypeId,
                    IFNULL(sot.internalTotalMarks,'') as internalTotalMarks,IFNULL(sot.externalTotalMarks,'') as externalTotalMarks,
                    IFNULL((SELECT
                                 CONCAT(grd.gradeLabel,'%^%',grd.gradePoints)
                           FROM
                                grades grd,grades_set grdset
                          WHERE
                               ttm.gradeId=grd.gradeId
                               and grd.gradeSetId=grdset.gradeSetId
                               and grdset.isActive=1),'') AS grade
              FROM
                    student_groups sg,class c, student s,subject sub, subject_to_class sot, 
                    ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, student_optional_subject opt 
              WHERE
                    s.studentId=sg.studentId
                    AND ttm.subjectId=opt.subjectId 
                    AND ttm.classId=opt.classId
                    AND ttm.classId=sot.classId 
                    AND opt.subjectId = sot.subjectId
                    and sg.classId=c.classId
                    and sub.subjectId = ttm.subjectId
                    and ttm.classId=sg.classId
                    and ttm.studentId=s.studentId
                    and sot.optional = 1
              $conditions
              GROUP BY ttm.classId, ttm.subjectId,ttm.studentId, sg.groupId
              $conditions2                                                                                     
		      ORDER BY 
                     $sortField $sortOrderBy,  studentId,   subjectCode ";
                    
              return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	public function getGraceMarks($studentIdList, $classId, $subjectId) {
			 $query = "SELECT studentId, graceMarks from ".TEST_GRACE_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId IN ($studentIdList)";
	       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		 }
      public function getSingleField($table, $field, $conditions='') {
		$query = "SELECT $field FROM $table $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
        
      public function getStudentWithPer($conditionForPer,$degreeAndSubject,$averagePer) {
          $query="  SELECT  
		        studentId, classId, subjectId,
			SUM(marksScored)/SUM(maxMarks)*100 AS per
		    FROM 
			total_transferred_marks1 WHERE $conditionForPer 	
		    $degreeAndSubject
                   
		    GROUP BY 
				studentId, classId, subjectId
		    HAVING
			per $averagePer";
			    
	
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}  
     public function getRangeDetails($rangeFields,$condition1,$condition2) {

		/*$query = "SELECT  
				  tt.classId, tt.subjectId,	
                                  $rangeFields
                          FROM
                                  (SELECT 
	                                    ttm.classId, ttm.subjectId, 
	                                    SUM(ttm.marksScored) AS mks, SUM(ttm.maxMarks) AS mm
                                   FROM 
	                                    ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm 
                                   WHERE 
	                                    $condition1 $condition2
                                  GROUP BY 
	                                   ttm.classId, ttm.subjectId, ttm.studentId) AS tt
                       GROUP BY
	                        tt.classId, tt.subjectId";*/
       $query = "SELECT  
		         tt.classId, tt.subjectId,	
                         $rangeFields
                 FROM
                      (SELECT 
	                     ttm.classId, ttm.subjectId, 
	                     SUM((ttm.marksScored)) AS mks, SUM(ttm.maxMarks) AS mm,IFNULL(b.graceMarks,0) AS gmks
                       FROM 
                            ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm LEFT JOIN ".TEST_GRACE_MARKS_TABLE." b 
                       ON 
                                (ttm.classId = b.classId and ttm.subjectId = b.subjectId and ttm.studentId = b.studentId ) 
                       WHERE 
	                         $condition1 $condition2
                       GROUP BY 
	                         ttm.classId, ttm.subjectId, ttm.studentId) AS tt
                GROUP BY
	                        tt.classId, tt.subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
      

}
?>
