<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DutyLeaveManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DutyLeaveManager" CLASS
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DutyLeaveManager" CLASS
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

    public function getStudentInfo($conditions='',$labelId) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        /*
        $query = "SELECT
                        s.studentId,s.classId
                  FROM
                        student s,`class` c
                  WHERE
                        s.classId=c.classId
                        AND c.instituteId=$instituteId
                        AND c.sessionId=$sessionId
                        $conditions";
        */

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

    public function getStudentPeriodSlot($studentId,$classId,$labelId) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT
                         DISTINCT t.periodSlotId
                  FROM
                         ".TIME_TABLE_TABLE."  t,student_groups sg
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
                         ".TIME_TABLE_TABLE."  t,student_optional_subject sos
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


    public function getId($rollNo) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
							studentId
				  FROM
							student
				  WHERE
							rollNo = '$rollNo'
				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



	public function getstudentId($rollNo) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
							*
				  FROM		class c1
				  WHERE 
							CONCAT_WS(',',c1.batchId,c1.branchId,c1.degreeId,c1.universityId) IN
							(
								SELECT 
										CONCAT_WS(',',c.batchId,c.branchId,c.degreeId,c.universityId) AS className
								FROM 
										class c LEFT JOIN student s ON c.classId = s.classId 
								WHERE 
										s.studentId = 9834 ) AND
								c1.isActive IN (1,3)

				 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


  public function checkWithSelfEvent($eventId,$dutyLeaveDate) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                       duty_event
                  WHERE
                        instituteId=$instituteId
                        AND sessionId=$sessionId
                        AND ( '$dutyLeaveDate' >= startDate AND '$dutyLeaveDate' <= endDate )
                        AND eventId=$eventId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   public function checkWithOtherEvents($studentId,$classId,$periodIds,$dutyLeaveDate,$eventId,$subjectId,$groupIds) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        /*
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                        ".DUTY_LEAVE_TABLE." 
                  WHERE
                        instituteId=$instituteId
                        AND sessionId=$sessionId
                        AND dutyDate='$dutyLeaveDate'
                        AND studentId=$studentId
                        AND classId=$classId
                        AND periodId IN (".$periodIds.")
                        AND subjectId=$subjectId
                        AND groupId IN (".$groupIds.")
                        AND eventId!=$eventId
                  ";
         */
         $query = "SELECT
                        de.eventId,
                        de.eventTitle
                  FROM
                        ".DUTY_LEAVE_TABLE."  dl,duty_event de
                  WHERE
                        de.eventId=dl.eventId
                        AND dl.instituteId=$instituteId
                        AND dl.sessionId=$sessionId
                        AND dl.dutyDate='$dutyLeaveDate'
                        AND dl.studentId=$studentId
                        AND dl.classId=$classId
                        AND dl.periodId IN (".$periodIds.")
                        AND dl.subjectId=$subjectId
                        AND dl.groupId IN (".$groupIds.")
                        AND dl.rejected !=".DUTY_LEAVE_REJECT."
                        AND dl.eventId!=$eventId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function updateEventStatus($studentId, $dutyLeaveDate, $periodId) {

        $query = "UPDATE 
							 ".DUTY_LEAVE_TABLE."  
				  SET
							rejected = '3' 
				  WHERE 
							studentId = '$studentId'
				  AND		dutyDate = '$dutyLeaveDate'
				  AND		periodId = 	'$periodId'
				";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


    public function deleteDutyLeave($studentId,$classId,$dutyLeaveDate,$eventId,$instituteId,$sessionId) {

        $query = "DELETE
                  FROM
                         ".DUTY_LEAVE_TABLE." 
                  WHERE
                        studentId=$studentId
                        AND classId=$classId
                        AND dutyDate='$dutyLeaveDate'
                        AND eventId=$eventId
                        AND sessionId=$sessionId
                        AND instituteId=$instituteId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function insertDutyLeave($insertString) {

        $query = "INSERT IGNORE
                  INTO
                         ".DUTY_LEAVE_TABLE."  (eventId,dutyDate,studentId,classId,periodId,achievement,place,instituteId,sessionId,subjectId,groupId)
                  VALUES  $insertString
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    public function getEventList($conditions='', $limit = '', $orderBy=' eventTitle') {

        $query = "SELECT
                        *
                  FROM
                        duty_event
                        $conditions
                  ORDER BY $orderBy
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
    public function getTotalEvent($conditions='') {

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        duty_event
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getSelectedTimeTableClasses() {
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
        $insertValue = "";
            for($i=0;$i<$count; $i++) {
                $querySeprator = '';
                if($insertValue!='') {
                    $querySeprator = ",";
                }
                $insertValue .= "$querySeprator ('".$result[$i]['classId']."')";
            }

        if ($count > 0 ) {
            $query = "
                        SELECT
                               DISTINCT(ttc.classId),className
                        FROM
                              time_table_classes ttc,
                              class c,
                              classes_visible_to_role cvtr,
                              time_table_labels ttl
                        WHERE
                               ttc.classId = c.classId
                        AND    ttc.timeTableLabelId=ttl.timeTableLabelId
                        AND    ttl.timeTableLabelId =1
                        AND    cvtr.classId = c.classId
                        AND    cvtr.classId = ttc.classId
                        AND    c.classId IN ($insertValue)
                        ORDER BY c.degreeId,c.branchId,c.studyPeriodId";
        }
        else {
            $query = "
                        SELECT
                                        DISTINCT(ttc.classId),
                                        className
                        FROM            time_table_classes ttc,
                                        class c,
                                        time_table_labels ttl
                        WHERE
                                ttc.classId = c.classId
                                AND ttc.timeTableLabelId=ttl.timeTableLabelId
                                AND ttl.isActive=1
                        ORDER BY c.degreeId,c.branchId,c.studyPeriodId";
        }

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    public function dutyLeaveCount($conditions=''){
		global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     	$query="SELECT    
				  	COUNT(*) AS cnt, subjectId, classId, studentId
				FROM
				  	 ".DUTY_LEAVE_TABLE." 
				WHERE
				  	rejected NOT IN (".DUTY_LEAVE_REJECT.")
				  	AND instituteId='$instituteId'
				  	$conditions
				GROUP BY
				  	subjectId, classId, studentId";
	     	
 	    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
 	 }
    


public function dutyLeaveList($conditions='', $limit = '', $orderBy=' c.className'){     
    $query="SELECT              
                  DISTINCT dl.studentId, dl.classId,  dl.eventId, dl.dutyDate, dl.periodId,
                  IFNULL(dl.rejected,-1) AS rejected, de.eventTitle, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
                  IFNULL(dl.subjectId,'') AS subjectId, IFNULL(dl.groupId,'') AS groupId
             FROM
                  duty_event de, `period` p, student s, student_groups sg,
                   ".DUTY_LEAVE_TABLE."  dl LEFT JOIN `subject` sub ON dl.subjectId=sub.subjectId 
             WHERE
                  s.studentId=dl.studentId
                  AND s.studentId=sg.studentId 
                  AND sg.classId=dl.classId
                  AND dl.eventId=de.eventId
                  AND dl.periodId=p.periodId
                  $conditions
            HAVING 
                  rejected !=".DUTY_LEAVE_REJECT."
            UNION
            SELECT              
                  DISTINCT dl.studentId, dl.classId, dl.eventId, dl.dutyDate, dl.periodId,
                  IFNULL(dl.rejected,-1) AS rejected, de.eventTitle, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
                  IFNULL(dl.subjectId,'') AS subjectId, IFNULL(dl.groupId,'') AS groupId 
             FROM
                  duty_event de, `period` p, student s, student_optional_subject sg,  
                   ".DUTY_LEAVE_TABLE."  dl LEFT JOIN `subject` sub ON dl.subjectId=sub.subjectId
             WHERE
                  s.studentId=dl.studentId
                  AND s.studentId=sg.studentId  
                  AND sg.subjectId = dl.subjectId 
                  AND sg.classId=dl.classId
                  AND dl.eventId=de.eventId
                  AND dl.periodId=p.periodId
                  $conditions
            HAVING 
                  rejected !=".DUTY_LEAVE_REJECT."
            ORDER BY 
                  $orderBy
            $limit";
            
    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
}
// this function extracts the list of duty leaves for admin
public function dutyLeaveAdminList($conditions='', $limit = '', $orderBy=' c.className'){
   
    $query="SELECT              
                  DISTINCT dl.studentId, dl.classId,  dl.eventId, dl.dutyDate, dl.periodId,
                  IFNULL(dl.rejected,-1) AS rejected, de.eventTitle, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
                  IFNULL(dl.subjectId,'') AS subjectId, IFNULL(dl.groupId,'') AS groupId
             FROM
                  duty_event de, student s, student_groups sg,  ".DUTY_LEAVE_TABLE."  dl 
                  LEFT JOIN `subject` sub ON dl.subjectId=sub.subjectId 
                  LEFT JOIN `period` p ON dl.periodId=p.periodId     
             WHERE
                  s.studentId=dl.studentId
                  AND s.studentId=sg.studentId 
                  AND sg.classId=dl.classId
                  AND dl.eventId=de.eventId
                  $conditions
            UNION
            SELECT              
                  DISTINCT dl.studentId, dl.classId, dl.eventId, dl.dutyDate, dl.periodId,
                  IFNULL(dl.rejected,-1) AS rejected, de.eventTitle, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
                  s.rollNo, p.periodNumber, IFNULL(sub.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode,
                  IFNULL(dl.subjectId,'') AS subjectId, IFNULL(dl.groupId,'') AS groupId 
             FROM
                  duty_event de, student s, student_optional_subject sg,  
                   ".DUTY_LEAVE_TABLE."  dl 
                  LEFT JOIN `subject` sub ON dl.subjectId=sub.subjectId
                  LEFT JOIN `period` p ON dl.periodId=p.periodId     
             WHERE
                  s.studentId=dl.studentId
                  AND s.studentId=sg.studentId  
                  AND sg.subjectId = dl.subjectId 
                  AND sg.classId=dl.classId
                  AND dl.eventId=de.eventId
            $conditions
            ORDER BY 
                  $orderBy
            $limit";
            
    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
}

    public function getStudentGroupInfo($studentId,$classId){
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query="
                SELECT
                       DISTINCT groupId
                FROM
                       student_groups
                WHERE
                       studentId=$studentId
                       AND classId=$classId
                       AND sessionId=$sessionId
                       AND instituteId=$instituteId
              UNION ALL
                SELECT
                       DISTINCT groupId
                FROM
                       student_optional_subject
                WHERE
                       studentId=$studentId
                       AND classId=$classId;
                ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    public function getStudentSubjectInfoFromTimeTable($studentId,$classId,$groupIds,$periodId,$daysOfWeek,$labelId){

      /*
       $query="SELECT
                      DISTINCT t.subjectId,su.subjectCode
                 FROM
                       ".TIME_TABLE_TABLE."  t,student s,
                      time_table_labels ttl,
                      `subject` su
                 WHERE
                      s.classId=t.classId
                      AND t.timeTableLabelId=ttl.timeTableLabelId
                      AND t.subjectId=su.subjectId
                      AND ttl.isActive=1
                      AND s.studentId=$studentId
                      AND t.classId=$classId
                      AND t.periodId=$periodId
                      AND t.daysOfWeek=$daysOfWeek
                      AND t.groupId IN ($groupIds)
                      AND t.toDate IS NULL
                 ";
        */

        $query="SELECT
                      DISTINCT t.subjectId,su.subjectCode
                 FROM
                       ".TIME_TABLE_TABLE."  t,student s,
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
                       ".TIME_TABLE_TABLE."  t,student s,
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


     public function getStudentBulkAttendanceRecord($studentId,$classId,$subjectId,$dutyDate){

        $query="SELECT
                      COUNT(att.studentId) AS totalRecords
                 FROM
                      ".ATTENDANCE_TABLE." att
                 WHERE
                      att.studentId=$studentId
                      AND att.classId=$classId
                      AND att.subjectId='$subjectId'
                      AND att.attendanceType=1
                      AND (att.fromDate >='$dutyDate' AND att.toDate<='$dutyDate')
                 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

     public function getStudentDailyAttendanceRecord($studentId,$classId,$subjectId,$periodId,$dutyDate){

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
                      AND (att.fromDate ='$dutyDate')
                      AND att.attendanceCodeId IN (SELECT attendanceCodeId FROM attendance_code WHERE instituteId=$instituteId AND attendanceCodePercentage=100 GROUP BY attendanceCodePercentage)
                 ";

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

  public function checkStudentDutyLeaveExistence($studentId,$classId,$periodId,$dutyDate,$eventId=-1){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $eventCondition='';
        if($eventId!=-1){
            $eventCondition=' AND eventId='.$eventId;
        }
        $query="SELECT
                      studentId,
                      classId,
                      eventId
                FROM
                       ".DUTY_LEAVE_TABLE." 
                WHERE
                      studentId=$studentId
                      AND classId=$classId
                      AND periodId=$periodId
                      AND dutyDate='$dutyDate'
                      AND sessionId=$sessionId
                      AND instituteId=$instituteId
                      $eventCondition
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

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

    public function updateDutyLeave($studentId,$classId,$periodId,$eventId,$dutyDate,$dutyLeaveStatus){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

		$condition ="";
		if($eventId!='') {
		  $condition ="AND eventId=$eventId";
		}
        $query="UPDATE
                       ".DUTY_LEAVE_TABLE." 
                SET
                      rejected=$dutyLeaveStatus
                WHERE
                      studentId=$studentId
                      AND classId=$classId
                      AND periodId=$periodId
                      AND dutyDate='$dutyDate'
					  $condition";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    
//The following function is used to count the total number of duty leaves per student per subject
public function countDutyLeavePerSubject($conditions='',$havingCondition=''){

    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    
    $dutyLeaveLimit=$sessionHandler->getSessionVariable('DUTY_LEAVE_LIMIT'); 
    
    $query="SELECT
                 SUM(t.totalDutyLeave) AS cnt, t.subjectId, t.studentId, t.classId,
                 t.subjectCode,t.rollNo, t.dutyApprove 
            FROM
                (SELECT      
		              COUNT(DISTINCT dl.dutyLeaveId) AS totalDutyLeave, dl.subjectId, dl.studentId, dl.classId,
                      CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectCode,s.rollNo,
		              '".DUTY_LEAVE_APPROVE."' AS dutyApprove 
                 FROM
		              duty_event de, `period` p, student s, student_groups sg,
		               ".DUTY_LEAVE_TABLE."  dl, `subject` sub
                 WHERE
                      s.studentId=dl.studentId
                      AND dl.subjectId=sub.subjectId  
                      AND s.studentId=sg.studentId 
                      AND sg.classId=dl.classId
                      AND dl.eventId=de.eventId
                      AND dl.periodId=p.periodId
		              $conditions
                GROUP BY 
		            dl.subjectId, dl.studentId, dl.classId 
                UNION
                SELECT              
                      COUNT(DISTINCT dl.dutyLeaveId) AS totalDutyLeave, dl.subjectId, dl.studentId, dl.classId,
                      CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectCode,s.rollNo,
                      '".DUTY_LEAVE_APPROVE."' AS dutyApprove              
                FROM
                      duty_event de, `period` p, student s, student_optional_subject sg,  
                       ".DUTY_LEAVE_TABLE."  dl,`subject` sub 
                 WHERE
                      s.studentId=dl.studentId
                      AND dl.subjectId=sub.subjectId
                      AND s.studentId=sg.studentId  
                      AND sg.subjectId = dl.subjectId 
                      AND sg.classId=dl.classId
                      AND dl.eventId=de.eventId
                      AND dl.periodId=p.periodId
		              $conditions
	             GROUP BY 
		             dl.subjectId, dl.studentId, dl.classId)  AS t
              GROUP BY
                   t.subjectId, t.studentId, t.classId        
              $havingCondition  
              ORDER BY 
                    studentId, subjectId, classId  ASC";
            
    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
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


public function getExactGroupIdFromTimeTable($classId,$subjectId,$groupIds,$periodId,$daysOfWeek,$labelId){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query="SELECT
                      DISTINCT groupId
                FROM
                       ".TIME_TABLE_TABLE." 
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



//--------------------------------------------------------------
//  THIS FUNCTION IS Findout student payment receipt Details. 
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getStudentClassDetail($condition='') {
      
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();    
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       s.studentId, c.classId, c.batchId, c.degreeId, c.branchId,
                       IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                       IF(IFNULL(s.regNo,'')='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                       IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                       CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) As studentName,
                       IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName, s.isLeet,
                       sp.studyPeriodId, sp.periodValue, s.isMigration, s.migrationClassId
                  FROM 
                       student s,class c, study_period sp
                  WHERE
                       s.classId = c.classId AND
                       c.studyPeriodId = sp.studyPeriodId
                  $condition ";
                  
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------
// THIS FUNCTION fetch fee Cycle Classes  
//
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//

//-------------------------------------------------------------------------------    
    public function getPreviousClasses($condition='',$orderBy=' c.branchId, c.studyPeriodId',$studentId='',$id='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        if($orderBy=='') {
          $orderBy = " c.branchId, c.studyPeriodId";  
        }
        
        $query = "SELECT 
                        DISTINCT 
                             c.classId, c.instituteId, c.sessionId, c.className
                  FROM 
                        study_period sp, class c
                  WHERE 
                        c.instituteId = '".$instituteId."' AND
                        c.studyPeriodId = sp.studyPeriodId AND
                        c.isActive IN (1,3)
                  $condition  
                  ORDER BY 
                        $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }         
    


public function getStudentDutyLeaveCount($conditions=''){

    $query="SELECT
					COUNT(*) AS cnt
			FROM
				(SELECT
					  DISTINCT dl.studentId,
					  dl.classId,
					  dl.subjectId,
					  dl.groupId,
					  dl.eventId,
					  dl.dutyDate,
					  dl.periodId,
					  IFNULL(dl.rejected,-1) AS rejected,
					  de.eventTitle,
					  CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
					  s.rollNo,
					  p.periodNumber,
					  sub.subjectCode,
					  emp.employeeName
				 FROM
					  student s,  duty_event de,student_groups sg, `period` p, `subject` sub,  ".TIME_TABLE_TABLE."  tt,
					   ".DUTY_LEAVE_TABLE."  dl, employee emp
				 WHERE
					  s.studentId=dl.studentId
					  AND s.classId = sg.classId
					  AND s.studentId=sg.studentId
					  AND sg.classId=dl.classId
					  AND dl.eventId=de.eventId
					  AND dl.periodId=p.periodId
					  AND dl.subjectId=sub.subjectId
					  AND tt.classId = dl.classId
					  AND tt.subjectId = dl.subjectId
					  AND tt.groupId = dl.groupId
					  AND tt.periodId = dl.periodId
					  AND tt.employeeId = emp.employeeId
					  $conditions
				 UNION
				 SELECT
					  DISTINCT dl.studentId,
					  dl.classId,
					  dl.subjectId,
					  dl.groupId,
					  dl.eventId,
					  dl.dutyDate,
					  dl.periodId,
					  IFNULL(dl.rejected,-1) AS rejected,
					  de.eventTitle,
					  CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
					  s.rollNo,
					  p.periodNumber,
					  sub.subjectCode,
					  emp.employeeName
				 FROM
					  student s, duty_event de, student_optional_subject sos,  ".TIME_TABLE_TABLE."  tt,
					  `period` p, `subject` sub,  ".DUTY_LEAVE_TABLE."  dl, employee emp,student_groups sg
				 WHERE
					  s.studentId=dl.studentId
					  AND s.studentId=sos.studentId
					  AND s.classId = sg.classId
					  AND sos.classId=dl.classId
					  AND dl.eventId=de.eventId
					  AND dl.periodId=p.periodId
					  AND dl.subjectId=sub.subjectId
					  AND tt.classId = dl.classId
					  AND tt.subjectId = dl.subjectId
					  AND tt.groupId = dl.groupId
					  AND tt.periodId = dl.periodId
					  AND tt.employeeId = emp.employeeId
				 $conditions) AS t";
    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
}


public function getStudentDutyLeave($conditions='', $limit = '', $orderBy=' eventTitle'){

  $query="SELECT
					  DISTINCT dl.studentId,
					  dl.classId,
					  dl.subjectId,
					  dl.groupId,
					  dl.eventId,
					  dl.dutyDate,
					  dl.periodId,
					  IFNULL(dl.rejected,-1) AS rejected,
					  de.eventTitle,
					  CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
					  s.rollNo,
					  p.periodNumber,
					  sub.subjectCode,
					  emp.employeeName
				 FROM
					  student s,  duty_event de,student_groups sg, `period` p, `subject` sub,  ".TIME_TABLE_TABLE."  tt,
					   ".DUTY_LEAVE_TABLE."  dl, employee emp
				 WHERE
					  s.studentId=dl.studentId
					  AND s.studentId=sg.studentId
					  AND s.classId = sg.classId
					  AND sg.classId=dl.classId
					  AND dl.eventId=de.eventId
					  AND dl.periodId=p.periodId
					  AND dl.subjectId=sub.subjectId
					  AND tt.classId = dl.classId
					  AND tt.subjectId = dl.subjectId
					  AND tt.groupId = dl.groupId
					  AND tt.periodId = dl.periodId
					  AND tt.employeeId = emp.employeeId
					  $conditions
				 UNION
				 SELECT
					  DISTINCT dl.studentId,
					  dl.classId,
					  dl.subjectId,
					  dl.groupId,
					  dl.eventId,
					  dl.dutyDate,
					  dl.periodId,
					  IFNULL(dl.rejected,-1) AS rejected,
					  de.eventTitle,
					  CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'') ) AS studentName,
					  s.rollNo,
					  p.periodNumber,
					  sub.subjectCode,
					  emp.employeeName
				 FROM
					  student s, duty_event de, student_optional_subject sos,  ".TIME_TABLE_TABLE."  tt,
					  `period` p, `subject` sub,  ".DUTY_LEAVE_TABLE."  dl, employee emp,student_groups sg
				 WHERE
					  s.studentId=dl.studentId
					  AND s.classId = sg.classId
					  AND s.studentId=sos.studentId
					  AND sos.classId=dl.classId
					  AND dl.eventId=de.eventId
					  AND dl.periodId=p.periodId
					  AND dl.subjectId=sub.subjectId
					  AND tt.classId = dl.classId
					  AND tt.subjectId = dl.subjectId
					  AND tt.groupId = dl.groupId
					  AND tt.periodId = dl.periodId
					  AND tt.employeeId = emp.employeeId
			 $conditions
             ORDER BY $orderBy
             $limit";
	/*echo $query;
	die;*/
    return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
  }
  
  public function checkStudentMedicalLeaveExistence($studentId,$classId,$subjectId,$periodId,$dutyDate){

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        
        $query="SELECT
                      COUNT(studentId) AS totalRecords 
                FROM
                       ".MEDICAL_LEAVE_TABLE." 
                WHERE
                      studentId=$studentId
                      AND classId=$classId
                      AND periodId=$periodId
                      AND medicalLeaveDate='$dutyDate'
                      AND subjectId = '$subjectId'
                      AND sessionId=$sessionId
                      AND instituteId=$instituteId
                      AND approvedStatus = '".MEDICAL_LEAVE_APPROVE."'
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
}

// $History: DutyLeaveManager.inc.php $
?>
