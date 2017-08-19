<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR MEDICAL LEAVE
// Author : Aditi Miglani
// Created on : 20 Sept 2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class MedicalLeaveManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "MedicalLeaveManager" CLASS
// Author : Aditi Miglani
// Created on : 20 Sept 2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "MedicalLeaveManager" CLASS
// Author : Aditi Miglani
// Created on : 20 Sept 2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//*******FOLLOWING FUNCTIONS ARE USED IN "UPLOAD MEDICAL LEAVE ENTRIES"**********

	public function getStudentInfo($conditions='',$labelId) {
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
	    
		$query="
		         SELECT
		               DISTINCT sg.studentId,sg.classId
		         FROM
		               student_groups sg,student s,
		               time_table_labels ttl,time_table_classes ttc
		         WHERE
		               s.studentId=sg.studentId
		               AND sg.classId=ttc.classId
		               AND ttc.timeTableLabelId=ttl.timeTableLabelId
		               AND ttl.instituteId=$instituteId
		               AND ttl.sessionId=$sessionId
		               AND ttl.timeTableLabelId=$labelId
		               $conditions
		       UNION
		         SELECT
		               DISTINCT sos.studentId,sos.classId
		         FROM
		               student_optional_subject sos,student s,
		               time_table_labels ttl,time_table_classes ttc
		         WHERE
		               s.studentId=sos.studentId
		               AND sos.classId=ttc.classId
		               AND ttc.timeTableLabelId=ttl.timeTableLabelId
		               AND ttl.instituteId=$instituteId
		               AND ttl.sessionId=$sessionId
		               AND ttl.timeTableLabelId=$labelId
		               $conditions
		       ";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentSubjectInfoFromTimeTable($studentId,$classId,$groupIds,$periodId,$daysOfWeek,$labelId){
	       	$query="SELECT
		              DISTINCT t.subjectId,su.subjectCode
		         FROM
		               ".TIME_TABLE_TABLE." t,student s,
		              time_table_labels ttl,
		              `subject` su,
		              student_groups sg
		         WHERE
		              s.studentId=sg.studentId
		              AND sg.classId=t.classId
		              AND t.timeTableLabelId=ttl.timeTableLabelId
		              AND t.subjectId=su.subjectId
		              AND s.studentId=$studentId
		              AND t.classId=$classId
		              AND t.periodId=$periodId
		              AND t.daysOfWeek=$daysOfWeek
		              AND t.groupId IN ($groupIds)
		              AND t.timeTableLabelId=$labelId
		              AND t.toDate IS NULL
		        UNION
		           SELECT
		              DISTINCT t.subjectId,su.subjectCode
		         FROM
		               ".TIME_TABLE_TABLE." t,student s,
		              time_table_labels ttl,
		              `subject` su,
		              student_optional_subject sos
		         WHERE
		              s.studentId=sos.studentId
		              AND sos.classId=t.classId
		              AND t.timeTableLabelId=ttl.timeTableLabelId
		              AND t.subjectId=su.subjectId
		              AND s.studentId=$studentId
		              AND t.classId=$classId
		              AND t.periodId=$periodId
		              AND t.daysOfWeek=$daysOfWeek
		              AND t.groupId IN ($groupIds)
		              AND t.timeTableLabelId=$labelId
		              AND t.toDate IS NULL
		         ";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	public function getExactGroupIdFromTimeTable($classId,$subjectId,$groupIds,$periodId,$daysOfWeek,$labelId){
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');

		$query="SELECT
		              DISTINCT groupId
		        FROM
		              time_table
		        WHERE
		              instituteId=$instituteId
		              AND classId=$classId
		              AND subjectId=$subjectId
		              AND periodId=$periodId
		              AND daysOfWeek=$daysOfWeek
		              AND timeTableLabelId=$labelId
		              AND groupId IN (".$groupIds.")
						  AND toDate IS NULL
		       ";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	public function getStudentPeriodSlot($studentId,$classId,$labelId) {
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$query = "SELECT
		                 DISTINCT t.periodSlotId
		          FROM
		                 ".TIME_TABLE_TABLE." t,student_groups sg
		          WHERE
		                sg.groupId=t.groupId
		                AND sg.instituteId=$instituteId
		                AND sg.sessionId=$sessionId
		                AND t.toDate IS NULL
		                AND t.timeTableLabelId=$labelId
		                AND sg.studentId=$studentId
		                AND sg.classId=$classId
		          UNION
		          SELECT
		                 DISTINCT t.periodSlotId
		          FROM
		                 ".TIME_TABLE_TABLE." t,student_optional_subject sos
		          WHERE
		                sos.groupId=t.groupId
		                AND t.instituteId=$instituteId
		                AND t.sessionId=$sessionId
		                AND t.toDate IS NULL
		                AND t.timeTableLabelId=$labelId
		                AND sos.studentId=$studentId
		                AND sos.classId=$classId
		               ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getPeriodIdInfo($periodNumbers,$periodSlotId) {
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT
		                DISTINCT p.periodId
		          FROM
		               `period` p,period_slot ps
		          WHERE
		                p.periodSlotId=ps.periodSlotId
		                AND ps.instituteId=$instituteId
		                AND p.periodNumber IN (".$periodNumbers.")
		                AND p.periodSlotId=$periodSlotId
		          ORDER BY p.periodId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}  

	public function getSubjectAndGroupInfoFromAttendance($studentId,$classId,$periodId,$fromDate,$labelId) {
		$query = "SELECT
		               DISTINCT att.studentId,att.classId,att.subjectId,att.groupId
		          FROM
		               ".ATTENDANCE_TABLE." att,
		               time_table_classes ttc
		          WHERE
		               ttc.classId=att.classId
		               AND ttc.timeTableLabelId=$labelId
		               AND ttc.classId=$classId
		               AND att.studentId=$studentId
		               AND att.classId=$classId
		               AND att.periodId=$periodId
		               AND att.fromDate='".$fromDate."'
		               AND att.attendanceType=2
		          ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function deleteMedicalLeave($studentId,$classId,$medicalLeaveDate,$instituteId,$sessionId) {
		$query = "DELETE
		          FROM
		                 ".MEDICAL_LEAVE_TABLE."                       
		          WHERE
		                studentId=$studentId
		                AND classId=$classId
		                AND medicalLeaveDate='$medicalLeaveDate'
		                AND sessionId=$sessionId
		                AND instituteId=$instituteId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function insertMedicalLeave($insertString) {
		$query = "INSERT IGNORE INTO  ".MEDICAL_LEAVE_TABLE."  
		          (medicalLeaveDate,studentId,classId,periodId,instituteId,sessionId,subjectId,groupId)
		          VALUES  
		          $insertString
		          ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

//*******END OF FUNCTIONS USED IN "UPLOAD MEDICAL LEAVE ENTRIES"**********

//*******FOLLOWING FUNCTIONS ARE USED IN "MEDICAL LEAVE CONFLICT REPORT"**********

	public function getAbsentAttendanceCodeId(){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query="SELECT
                      attendanceCodeId
                FROM
                      attendance_code
                WHERE
                      instituteId=$instituteId
                      AND attendanceCodePercentage=0
                GROUP BY attendanceCodePercentage
               ";
               return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
        }

	public function checkStudentMedicalLeaveExistence($studentId,$classId,$periodId,$medicalLeaveDate){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query="SELECT
                      studentId,
                      classId
                FROM
                       ".MEDICAL_LEAVE_TABLE." 
                WHERE
                      studentId=$studentId
                      AND classId=$classId
                      AND periodId=$periodId
                      AND medicalLeaveDate='$medicalLeaveDate'
                      AND sessionId=$sessionId
                      AND instituteId=$instituteId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

	public function updateMedicalLeave($studentId,$classId,$periodId,$medicalLeaveDate,$medicalLeaveStatus){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

		$condition ="";
        	$query="UPDATE
                       ".MEDICAL_LEAVE_TABLE." 
                SET
                      approvedStatus=$medicalLeaveStatus
                WHERE
                      studentId=$studentId
                      AND classId=$classId
                      AND periodId=$periodId
                      AND medicalLeaveDate='$medicalLeaveDate'";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function updateAttendance($studentId,$classId,$subjectId,$periodId,$attendanceDate,$absentAttendanceCodeId){

        $query="UPDATE
                      ".ATTENDANCE_TABLE."
                SET
                      attendanceCodeId=$absentAttendanceCodeId
                WHERE
                      studentId=$studentId
                      AND classId=$classId
                      AND subjectId=$subjectId
                      AND periodId=$periodId
                      AND fromDate='$attendanceDate'
                      AND attendanceType=2
               ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//The following function is used to count the total number of medical leaves per student per subject
public function countMedicalLeavePerSubject($conditions='', $havingCondition=''){

     global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    
    $medicalLeaveLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_LIMIT'); 
    $havingCondition=''; 
    if($medicalLeaveLimit!='') {
       $havingCondition = "HAVING cnt > '$medicalLeaveLimit' ";
    }
    
    $query="SELECT
                 SUM(t.totalMedicalLeave) AS cnt, t.subjectId, t.studentId, t.classId,
                 t.subjectCode,t.rollNo, t.medicalApprove 
            FROM
                (SELECT      
                      COUNT(DISTINCT ml.medicalLeaveId) AS totalMedicalLeave, ml.subjectId, ml.studentId, ml.classId,
                      CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectCode,s.rollNo,
                      '".MEDICAL_LEAVE_APPROVE."' AS medicalApprove 
                 FROM
                      `period` p, student s, student_groups sg,
                       ".MEDICAL_LEAVE_TABLE."  ml, `subject` sub
                 WHERE
                      s.studentId=ml.studentId
                      AND ml.subjectId=sub.subjectId  
                      AND s.studentId=sg.studentId 
                      AND sg.classId=ml.classId
                      AND ml.periodId=p.periodId
                      AND ml.approvedStatus=".MEDICAL_LEAVE_APPROVE."
                      AND ml.instituteId='$instituteId'
                      $conditions
                GROUP BY 
                    ml.subjectId, ml.studentId, ml.classId 
                UNION
                SELECT              
                      COUNT(DISTINCT ml.medicalLeaveId) AS totalMedicalLeave, ml.subjectId, ml.studentId, ml.classId,
                      CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectCode,s.rollNo,
                      '".MEDICAL_LEAVE_APPROVE."' AS medicalApprove              
                FROM
                      `period` p, student s, student_optional_subject sg,  
                       ".MEDICAL_LEAVE_TABLE."  ml,`subject` sub 
                 WHERE
                      s.studentId=ml.studentId
                      AND ml.subjectId=sub.subjectId
                      AND s.studentId=sg.studentId  
                      AND sg.subjectId = ml.subjectId 
                      AND sg.classId=ml.classId
                      AND ml.periodId=p.periodId
                      AND ml.approvedStatus=".MEDICAL_LEAVE_APPROVE."
                      AND ml.instituteId='$instituteId'
                      $conditions
                 GROUP BY 
                     ml.subjectId, ml.studentId, ml.classId)  AS t
              GROUP BY
                   t.subjectId, t.studentId, t.classId        
              $havingCondition  
              ORDER BY 
                    studentId, subjectId, classId  ASC";
            
            
            return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    
    public function medicalLeaveCount($conditions=''){
		global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     	$query="SELECT    
				  	COUNT(*) AS cnt, subjectId, classId, studentId
				FROM
				  	 ".MEDICAL_LEAVE_TABLE." 
				WHERE
				  	approvedStatus NOT IN (".MEDICAL_LEAVE_REJECT.")
				  	AND instituteId='$instituteId'
				  	$conditions
				GROUP BY
				  	subjectId, classId, studentId";
	     	
 	    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
 	 }
    
	public function medicalLeaveList($conditions='', $limit = '', $orderBy=' c.className'){
	global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
	     	$query="SELECT              
		                  DISTINCT ml.studentId, ml.classId, ml.medicalLeaveDate, ml.periodId,
		                  IFNULL(ml.approvedStatus,-1) AS approvedStatus, 
                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
		                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
		                  IFNULL(ml.subjectId,'') AS subjectId, IFNULL(ml.groupId,'') AS groupId
		             FROM
		                  `period` p, student s, student_groups sg,
		                   ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN `subject` sub ON ml.subjectId=sub.subjectId 
		             WHERE
		                  s.studentId=ml.studentId
		                  AND s.studentId=sg.studentId 
		                  AND sg.classId=ml.classId
		                  AND ml.periodId=p.periodId
		                  AND ml.instituteId='$instituteId'
		                  $conditions
		            HAVING 
		                  approvedStatus !=".MEDICAL_LEAVE_REJECT."
		            UNION
		            SELECT              
		                  DISTINCT ml.studentId, ml.classId, ml.medicalLeaveDate, ml.periodId,
		                  IFNULL(ml.approvedStatus,-1) AS approvedStatus, 
                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
		                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
		                  IFNULL(ml.subjectId,'') AS subjectId, IFNULL(ml.groupId,'') AS groupId 
		             FROM
		                  `period` p, student s, student_optional_subject sg,  
		                   ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN `subject` sub ON ml.subjectId=sub.subjectId
		             WHERE
		                  s.studentId=ml.studentId
		                  AND s.studentId=sg.studentId  
		                  AND sg.subjectId = ml.subjectId 
		                  AND sg.classId=ml.classId
		                  AND ml.periodId=p.periodId
		                  AND ml.instituteId='$instituteId'
		                  $conditions
		            HAVING 
		                  approvedStatus !=".MEDICAL_LEAVE_REJECT."
		            ORDER BY 
		                  $orderBy
		            $limit";
		    
	    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}
	
	
	
	
	
	//this function extracts the list of medical leave for admin
	public function medicalLeaveAdminList($conditions='', $limit = '', $orderBy=' c.className'){
	global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
	     	$query="SELECT              
		                  DISTINCT ml.studentId, ml.classId, ml.medicalLeaveDate, ml.periodId,
		                  IFNULL(ml.approvedStatus,-1) AS approvedStatus, 
                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
		                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
		                  IFNULL(ml.subjectId,'') AS subjectId, IFNULL(ml.groupId,'') AS groupId
		             FROM
		                  `period` p, student s, student_groups sg,
		                   ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN `subject` sub ON ml.subjectId=sub.subjectId 
		             WHERE
		                  s.studentId=ml.studentId
		                  AND s.studentId=sg.studentId 
		                  AND sg.classId=ml.classId
		                  AND ml.periodId=p.periodId
		                  AND ml.instituteId='$instituteId'
		                  $conditions
		            UNION
		            SELECT              
		                  DISTINCT ml.studentId, ml.classId, ml.medicalLeaveDate, ml.periodId,
		                  IFNULL(ml.approvedStatus,-1) AS approvedStatus, 
                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
		                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
		                  IFNULL(ml.subjectId,'') AS subjectId, IFNULL(ml.groupId,'') AS groupId 
		             FROM
		                  `period` p, student s, student_optional_subject sg,  
		                   ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN `subject` sub ON ml.subjectId=sub.subjectId
		             WHERE
		                  s.studentId=ml.studentId
		                  AND s.studentId=sg.studentId  
		                  AND sg.subjectId = ml.subjectId 
		                  AND sg.classId=ml.classId
		                  AND ml.periodId=p.periodId
		                  AND ml.instituteId='$instituteId'
		                  $conditions
		            ORDER BY 
		                  $orderBy
		            $limit";
		    
	    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	public function getStudentAttendanceRecord($studentId,$classId,$subjectId){
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$query="SELECT
		              s.studentId,
		              ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
		              SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered
		        FROM
		              student s
		              INNER JOIN  ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
		              LEFT  JOIN   attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = $instituteId)
		        WHERE
		              s.studentId=$studentId
		              AND att.classId=$classId
		              AND att.subjectId='$subjectId'
		              GROUP BY att.subjectId
		         ";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    	}

	public function getStudentDailyAttendanceRecord($studentId,$classId,$subjectId,$periodId,$medicalLeaveDate){
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$query="SELECT
		              COUNT(att.studentId) AS totalRecords
		         FROM
		              ".ATTENDANCE_TABLE." att
		         WHERE
		              att.studentId=$studentId
		              AND att.classId=$classId
		              AND att.subjectId='$subjectId'
		              AND att.periodId=$periodId
		              AND att.attendanceType=2
		              AND (att.fromDate ='$medicalLeaveDate')
		              AND att.attendanceCodeId IN (SELECT attendanceCodeId FROM attendance_code WHERE instituteId=$instituteId AND attendanceCodePercentage=100 GROUP BY attendanceCodePercentage)
		         ";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    	}

	public function getStudentBulkAttendanceRecord($studentId,$classId,$subjectId,$medicalLeaveDate){
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$query="SELECT
		              COUNT(att.studentId) AS totalRecords
		         FROM
		              ".ATTENDANCE_TABLE." att
		         WHERE
		              att.studentId=$studentId
		              AND att.classId=$classId
		              AND att.subjectId='$subjectId'
		              AND att.attendanceType=1
		              AND (att.fromDate >='$medicalLeaveDate' AND att.toDate<='$medicalLeaveDate')
		         ";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
   	}
    
     public function checkStudentDutyLeaveExistence($studentId,$classId,$subjectId,$periodId,$dutyDate){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        
        $query="SELECT
                      COUNT(studentId) AS totalRecords 
                FROM
                       ".DUTY_LEAVE_TABLE." 
                WHERE
                      studentId=$studentId
                      AND classId=$classId
                      AND periodId=$periodId
                      AND dutyDate='$dutyDate'
                      AND subjectId = '$subjectId'
                      AND sessionId=$sessionId
                      AND instituteId=$instituteId
                      AND rejected = '".DUTY_LEAVE_APPROVE."'
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }



//*******END OF FUNCTIONS USED IN "MEDICAL LEAVE CONFLICT REPORT"**********
}
?>
