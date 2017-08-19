<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class TimeTableManager {
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

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH PERIOD LIST
//
// Author :Rajeev Aggarwal
// Created on : (12.08.2008)
// modified on :16.12.2008
// modified by: Pushpender
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getPeriodList($conditions='', $limit = '', $orderBy=' p.periodNumber') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT p.*
        FROM period p, period_slot ps
        WHERE p.periodSlotId=ps.periodSlotId and ps.instituteId = $instituteId $conditions ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH ROOM LIST CONCAT WITH BLOCK
//
// Author :Rajeev Aggarwal
// Created on : (12.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getRoomList($conditions='', $limit = '', $orderBy=' bl.abbreviation') {

        $query = "SELECT roomId,CONCAT(bl.abbreviation,'-',rm.roomAbbreviation) as roomName
		FROM room rm, block bl
		WHERE
		rm.blockId = bl.blockId $conditions
		ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH DATA FROM TIME TABLE
//
// Author :Rajeev Aggarwal
// Created on : (12.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getTimeTable($conditions='') {

        $query = "SELECT *
        FROM ".TIME_TABLE_TABLE."
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function countTimeTableClasses($labelId) {
		$query = "select count(distinct(classId)) as cnt from ".TIME_TABLE_TABLE." where timeTableLabelId = $labelId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getTimeTableClasses($labelId) {
		$query = "select distinct(classId) as classId from ".TIME_TABLE_TABLE." where timeTableLabelId = $labelId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkWithOtherTimeTableLabels($labelId, $classId) {
		$query = "select count(classId) as cnt from time_table_classes where timeTableLabelId != $labelId and classId = $classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of teacher
//
// Author : Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function getTeacherTimeTable ($conditions='', $order='ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber, daysOfWeek',$startDate='',$endDate='',$fieldName='') {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "SELECT
                          DISTINCT
                                    cvtr.classId
                  FROM
                          classes_visible_to_role cvtr
                  WHERE   cvtr.userId = $userId
                          AND cvtr.roleId = $roleId ";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);

        $insertValue = "(0";
        for($i=0;$i<$count; $i++) {
          $insertValue .= ",".$result[$i]['classId'];
        }
        $insertValue .= ")";

        $tableName = "";
        $hodCondition = "";
        if ($count > 0) {
            $tableName = ", classes_visible_to_role cvtr";
            $hodCondition = " AND  cvtr.groupId = gr.groupId
                              AND  cvtr.classId = gr.classId
                              AND  cvtr.groupId = tt.groupId
                              AND  cvtr.classId = cl.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId
                              AND  cl.classId IN $insertValue ";
        }

        global $sessionHandler;
        global $REQUEST_DATA;

        if($startDate=='') {
          $startDate=date('Y-m-d');
        }
        if($endDate=='') {
          $endDate=date('Y-m-d');
        }

        //AND tta.fromDate='".trim($REQUEST_DATA['fromDate'])."' AND tta.toDate='".trim($REQUEST_DATA['toDate'])."'

        if($fieldName=='') {
          $fieldName1 = "DISTINCT
                            -1 AS adjustmentType, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber,
                            CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,gr.groupShort,
                            SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className,
                            gr.classId, sub.subjectName,sub.subjectCode,
                            r.roomName,concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation,emp.employeeName,
                            gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, sub.hasAttendance,
                            (SELECT
                                    DISTINCT e.employeeName
                             FROM
                                    time_table_adjustment tta LEFT JOIN employee e ON e.employeeId = tta.employeeId
                             WHERE
                                    tta.sessionId=tt.sessionId AND tta.instituteId=tt.instituteId AND tta.timeTableLabelId=ttl.timeTableLabelId
                                    AND tta.roomId = tt.roomId AND tta.oldEmployeeId  = emp.employeeId
                                    AND tta.groupId  = tt.groupId   AND tta.daysOfWeek  = tt.daysOfWeek AND tta.periodSlotId  = tt.periodSlotId
                                    AND tta.periodId  = tt.periodId AND tta.subjectId  = tt.subjectId AND tta.timeTableLabelId  = tt.timeTableLabelId
                                    AND tta.isActive=1
                             ) AS adjustEmpName, tt.fromDate ";


            $fieldName2 = "DISTINCT
                                 tt.adjustmentType AS adjustmentType, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber,
                                 CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,gr.groupShort,
                                 SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className,
                                 gr.classId, sub.subjectName,sub.subjectCode,
                                 r.roomName,r.roomAbbreviation,emp.employeeName,
                                 gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, sub.hasAttendance, '' AS adjustEmpName,
                                 tt.fromDate";

	     $order.=",groupShort,subjectCode";
        }
        else {
          $fieldName1 = $fieldName;
          $fieldName2 = $fieldName;
        }

	

        $query = "SELECT
                        $fieldName1
                   FROM
                        period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl,".TIME_TABLE_TABLE." tt, block b, building c  $tableName
                   WHERE
                        tt.periodId = p.periodId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND tt.toDate IS NULL
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
								AND r.blockId = b.blockId AND b.buildingId = c.buildingId
                        AND tt.timeTableId NOT IN
                        (
                            SELECT
                                  tta.timeTableId
                            FROM
                                  time_table_adjustment tta
                            WHERE
                                  tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                  AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                  AND ( (tta.fromDate BETWEEN '$startDate' AND '$endDate')
                                          OR
                                          (tta.toDate BETWEEN '$startDate' AND '$endDate')
                                           OR
                                          (tta.fromDate <= '$startDate' AND tt.toDate>= '$endDate')  )
                                  AND tta.timeTableLabelId=ttl.timeTableLabelId
                                  AND tta.isActive=1
                          )
                         $conditions  $hodCondition
                  UNION ALL
                  SELECT
                        $fieldName2
                   FROM
                        time_table_adjustment tt , period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl  $tableName
                   WHERE
                        tt.periodId = p.periodId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        $conditions
                        AND ( (tt.fromDate BETWEEN '$startDate' AND '$endDate')
                              OR
                              (tt.toDate BETWEEN '$startDate' AND '$endDate')
                               OR
                              (tt.fromDate <= '$startDate' AND tt.toDate>= '$endDate')  )
                        AND tt.isActive=1
                        $hodCondition
                        $order";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of Student/Parent
//
// Author : Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getStudentShowTimeTable ($conditions='', $order='ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber, daysOfWeek',$startDate='',$endDate='',$fieldName='') {
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "SELECT
                          DISTINCT
                                    cvtr.classId
                  FROM
                          classes_visible_to_role cvtr
                  WHERE   cvtr.userId = $userId
                          AND cvtr.roleId = $roleId ";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);

        $insertValue = "(0";
        for($i=0;$i<$count; $i++) {
          $insertValue .= ",".$result[$i]['classId'];
        }
        $insertValue .= ")";

        $tableName = "";
        $hodCondition = "";
        if ($count > 0) {
            $tableName = ", classes_visible_to_role cvtr";
            $hodCondition = " AND  cvtr.groupId = gr.groupId
                              AND  cvtr.classId = gr.classId
                              AND  cvtr.groupId = tt.groupId
                              AND  cvtr.classId = cl.classId
                              AND  cvtr.userId = $userId
                              AND  cvtr.roleId = $roleId
                              AND  cl.classId IN $insertValue ";
        }

        global $sessionHandler;
        global $REQUEST_DATA;

        if($startDate=='') {
          $startDate=date('Y-m-d');
        }
        if($endDate=='') {
          $endDate=date('Y-m-d');
        }


        //AND tta.fromDate='".trim($REQUEST_DATA['fromDate'])."' AND tta.toDate='".trim($REQUEST_DATA['toDate'])."'

        if($fieldName=='') {
          $fieldName1 = "DISTINCT
                                sg.studentId, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber,
                                CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,gr.groupShort,
                                SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className,
                                gr.classId, sub.subjectName,sub.subjectCode,
                                r.roomName,concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation,
                                emp.employeeName, gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, sub.hasAttendance,
                                emp.employeeId, tt.fromDate,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                                IF(IFNULL(s.motherName,'')='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                                IF(IFNULL(s.guardianName,'')='','".NOT_APPLICABLE_STRING."',s.guardianName) AS guardianName";
   	  $order.=",groupShort,subjectCode";
        }
        else {
          $fieldName1 = $fieldName;
        }



        $query = "SELECT
                        $fieldName1
                  FROM
                        student s, student_groups sg, period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl,".TIME_TABLE_TABLE." tt, block b, building c  $tableName
                  WHERE
                        tt.periodId = p.periodId
                        AND sg.studentId = s.studentId
                        AND sg.classId = cl.classId
                        AND sg.groupId = tt.groupId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND tt.toDate IS NULL

                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        AND r.blockId = b.blockId AND b.buildingId = c.buildingId
                       $conditions  $hodCondition
                  UNION
                  SELECT
                        $fieldName1
                  FROM
                        student s, student_optional_subject sg, period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl,".TIME_TABLE_TABLE." tt, block b, building c  $tableName
                  WHERE
                        tt.periodId = p.periodId
                        AND sg.studentId = s.studentId
                        AND sg.classId = cl.classId
                        AND sg.groupId = tt.groupId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND tt.toDate IS NULL
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        AND r.blockId = b.blockId AND b.buildingId = c.buildingId
                       $conditions  $hodCondition
                  $order";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	function getStudentUser($rollNo) {
		$query = "	SELECT	userId
					FROM	user
					WHERE	userName = '$rollNo'
					AND		roleId = 4";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	function getStudentRollNo($rollNo) {
		$query = "	SELECT	COUNT(*) AS totalRecords
					FROM	student
					WHERE	rollNo = '$rollNo'";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	function getStudentRollNoRecord($userId) {
		$query = "	SELECT	rollNo
					FROM	student
					WHERE	userId = '$userId'";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}



//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH PERIOD LIST IN manage Time table
//
// Author :Parveen Sharma
// Created on : (08.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getTimeTablePeriodList($conditions='',$orderBy=' p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber',$fieldName='') {
        global $sessionHandler;

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        if($fieldName=='') {
          $fieldName = "DISTINCT  p.periodSlotId, p.periodNumber,
                                  CONCAT(p.startTime,p.startAmPm) AS psTime, CONCAT(p.endTime,p.endAmPm) AS peTime ";
        }

        $cond =  $conditions;
        if($conditions=='') {
           $cond .= " tt.toDate IS NULL
                      AND tt.instituteId=$instituteId
                      AND tt.sessionId=$sessionId";
        }
        else {
           $cond .= " AND tt.toDate IS NULL
                      AND tt.instituteId=$instituteId
                      AND tt.sessionId=$sessionId";
        }


        $query = "SELECT
                        $fieldName
                  FROM
                        period p LEFT JOIN ".TIME_TABLE_TABLE." tt ON
                        p.periodSlotId IN (SELECT
                                                   DISTINCT p1.periodSlotId
                                             FROM
                                                   period p1,period_slot ps
                                             WHERE
                                                   p1.periodId=tt.periodId
                                                   AND p1.periodSlotId=ps.periodSlotId
                                                   AND ps.instituteId=$instituteId
                                                   AND $conditions)
                  WHERE
                        $cond
                  ORDER BY $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of class
//
// Author : Rajeev Aggarwal
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getClassTimeTable ($conditions='', $order='ORDER BY LENGTH(p.periodNumber)+0,p.periodNumber,tt.daysOfWeek')
 {
	global $sessionHandler;
	$systemDatabaseManager = SystemDatabaseManager::getInstance();
	$userId= $sessionHandler->getSessionVariable('UserId');
	$roleId = $sessionHandler->getSessionVariable('RoleId');

		$query = "	SELECT
							distinct cvtr.classId
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

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

		if ($count > 0) {
			$query = "SELECT
						DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
						SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
						sub.subjectName,sub.subjectAbbreviation,sub.subjectCode,
						r.roomName,concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation,gr.groupShort,emp.employeeAbbreviation,emp.employeeName ,
						sub.subjectId, gr.groupId, cl.classId
				FROM
						".TIME_TABLE_TABLE." tt , period p, `group` gr,  subject sub, employee emp,
						room r, class cl, time_table_labels ttl,time_table_classes ttc, classes_visible_to_role cvtr, block b, building c
				WHERE
						tt.periodId = p.periodId
						AND tt.groupId = gr.groupId
						AND gr.classId = cl.classId
						AND tt.subjectId=sub.subjectId
						AND tt.employeeId=emp.employeeId
						AND tt.roomId = r.roomId
						AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						AND tt.toDate IS NULL
						AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
						AND tt.timeTableLabelId=ttl.timeTableLabelId
						AND ttc.timeTableLabelId=ttl.timeTableLabelId
						AND ttl.isActive=1
						AND ttc.classId=cl.classId
						AND cvtr.classId = cl.classId
						AND r.blockId = b.blockId AND b.buildingId = c.buildingId
						AND cl.classId IN ($insertValue)
						$conditions
						$order ";
		}
		else {

	   $query = "SELECT
						DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
						SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
						sub.subjectName,sub.subjectAbbreviation,sub.subjectCode,
						r.roomName,concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation,gr.groupShort,emp.employeeAbbreviation,emp.employeeName ,
						sub.subjectId, gr.groupId, cl.classId
				FROM
						".TIME_TABLE_TABLE." tt , period p, `group` gr,  subject sub, employee emp,
						room r, class cl, time_table_labels ttl,time_table_classes ttc, block b, building c
				WHERE
						tt.periodId = p.periodId
						AND tt.groupId = gr.groupId
						AND gr.classId = cl.classId
						AND tt.subjectId=sub.subjectId
						AND tt.employeeId=emp.employeeId
						AND tt.roomId = r.roomId
						AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						AND tt.toDate IS NULL
						AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
						AND tt.timeTableLabelId=ttl.timeTableLabelId
						AND ttc.timeTableLabelId=ttl.timeTableLabelId
						AND ttl.isActive=1
						AND ttc.classId=cl.classId
						AND r.blockId = b.blockId AND b.buildingId = c.buildingId
						$conditions
						$order ";
		}
     //echo $query;
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }


//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of room
//
// Author : Rajeev Aggarwal
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getRoomTimeTable($conditions='', $order='ORDER BY LENGTH(p.periodNumber)+0,p.periodNumber,tt.daysOfWeek')
 {
   global $sessionHandler;

   $query = "SELECT DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
            SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
            sub.subjectName,sub.subjectAbbreviation,sub.subjectCode,
            r.roomName, concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation, gr.groupShort,emp.employeeAbbreviation,emp.employeeCode,emp.employeeName,
            gr.groupId, sub.subjectId, cl.classId
            FROM ".TIME_TABLE_TABLE." tt , period p, `group` gr,  subject sub, employee emp, room r, class cl, time_table_labels ttl,time_table_classes ttc, block b, building c
            WHERE tt.periodId = p.periodId
            AND tt.groupId = gr.groupId
			AND gr.classId = cl.classId
            AND tt.subjectId=sub.subjectId
			AND tt.employeeId=emp.employeeId
            AND tt.roomId = r.roomId
            AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
            AND tt.toDate IS NULL
            AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
            AND tt.timeTableLabelId=ttl.timeTableLabelId
			AND ttc.timeTableLabelId=ttl.timeTableLabelId
			AND ttl.isActive=1
			AND ttc.classId=cl.classId
			AND r.blockId = b.blockId AND b.buildingId = c.buildingId
            $conditions
            $order";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }
//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of student
//
// Author : Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function getStudentTimeTable ($rollNo, $order='ORDER BY LENGTH(p.periodNumber)+0,p.periodNumber,tt.daysOfWeek') {
       global $sessionHandler;
	   global $REQUEST_DATA;
	   $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$query = "	SELECT
							distinct cvtr.classId
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

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

	   if ($count > 0) {
		$query = "
					SELECT
								DISTINCT
								tt.periodId,
								tt.daysOfWeek,
								p.periodNumber,
								CONCAT(p.startTime,p.startAmPm,' ',endTime,endAmPm) AS pTime,
								s.studentId,
								sub.subjectCode,
								sub.subjectName,
								sub.subjectAbbreviation,
								emp.employeeName,
								r.roomName,
								concat(c.abbreviation, '-',bl.abbreviation,'-',r.roomAbbreviation) AS roomAbbreviation,
								gr.groupShort,
								SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName,
								cl.className,
								ttc.timeTableLabelId,
								cl.classId,
								gr.groupId,
								sub.subjectId
					FROM		".TIME_TABLE_TABLE." tt,
								`period` p,
								`student` s,
								`subject` sub,
								`employee` emp,
								`room` r,
								`block` bl,
								 building c,
								`student_groups` sg,
								`time_table_labels` ttl,
								`time_table_classes` ttc,
								`group` gr,
								 class cl,
								 classes_visible_to_role cvtr
					WHERE		tt.periodId = p.periodId
					AND			s.studentId=sg.studentId
					AND			tt.subjectId = sub.subjectId
					AND			sg.groupId = gr.groupId
					AND			tt.groupId = sg.groupId
					AND			tt.employeeId=emp.employeeId
					AND			r.blockId = bl.blockId
					AND			bl.buildingId = c.buildingId
					AND			tt.roomId = r.roomId
					AND			tt.toDate IS NULL
					AND			tt.timeTableLabelId = ttl.timeTableLabelId
					AND			ttl.timeTableLabelId = ttc.timeTableLabelId
					AND			sg.classId = ttc.classId
					AND			sg.classId = cl.classId
					AND			s.rollNo='".$rollNo ."'
					AND			tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND			tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND			cvtr.classId = cl.classId
					AND			cl.classId IN ($insertValue)
								$classCond
								$order";
	   }
	   else {

	   $query = "
					SELECT
								DISTINCT
								tt.periodId,
								tt.daysOfWeek,
								p.periodNumber,
								CONCAT(p.startTime,p.startAmPm,' ',endTime,endAmPm) AS pTime,
								s.studentId,
								sub.subjectCode,
								sub.subjectName,
								sub.subjectAbbreviation,
								emp.employeeName,
								r.roomName,
								concat(c.abbreviation, '-',bl.abbreviation,'-',r.roomAbbreviation) AS roomAbbreviation,
								gr.groupShort,
								SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName,
								cl.className,
								ttc.timeTableLabelId,
								cl.classId,
								gr.groupId,
								sub.subjectId
				FROM		".TIME_TABLE_TABLE." tt,
							`period` p,
							`student` s,
							`subject` sub,
							`employee` emp,
							`room` r,
							`block` bl,
							building c,
							`student_groups` sg,
							`time_table_labels` ttl,
							`time_table_classes` ttc,
							`group` gr,
							 class cl
				WHERE		tt.periodId = p.periodId
				AND			s.studentId=sg.studentId
				AND			tt.subjectId = sub.subjectId
				AND			sg.groupId = gr.groupId
				AND			tt.groupId = sg.groupId
				AND			tt.employeeId=emp.employeeId
				AND			r.blockId = bl.blockId
				AND			bl.buildingId = c.buildingId
				AND			tt.roomId = r.roomId
				AND			tt.toDate IS NULL
				AND			tt.timeTableLabelId = ttl.timeTableLabelId
				AND			ttl.timeTableLabelId = ttc.timeTableLabelId
				AND			sg.classId = ttc.classId
				AND			sg.classId = cl.classId
				AND s.rollNo='".$rollNo ."'
				AND			tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
				AND			tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
							$classCond
							$order";
		}

        /*$query = "SELECT tt.periodId, tt.daysOfWeek, p.periodNumber,CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime, gr.classId, s.studentId, sub.subjectCode, emp.employeeName, CONCAT(r.roomAbbreviation,'-',bl.abbreviation) roomName ,gr.groupName
		FROM ".TIME_TABLE_TABLE." tt , `period` p, `group` gr, `student` s, `subject` sub, `employee` emp, `room` r, `block` bl,`student_groups` sg
		WHERE tt.periodId = p.periodId
		AND tt.groupId = gr.groupId
		AND gr.classId = sg.classId
		AND tt.subjectId=sub.subjectId
		AND tt.employeeId=emp.employeeId
		AND r.blockId = bl.blockId
		AND tt.roomId = r.roomId
		AND sg.studentId = s.studentId
		AND sg.groupId = tt.groupId
		AND s.rollNo='".$rollNo ."'
		AND tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
		AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')." ORDER BY p.periodNumber,tt.daysOfWeek

		";
		*/
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

//------------------------------------------------------------------------------------------------
// This Function  gets the data from subject, teacher time table of teacher
//
// Author : Rajeev Aggarwal
// Created on : 22-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function getTeacherClassTimeTable ($conditions='', $orderBy='ORDER BY LENGTH(p.periodNumber)+0,p.periodNumber')
	{
		global $sessionHandler;

		$query = "SELECT DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
		SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId, sub.subjectName,sub.subjectAbbreviation, emp.employeeName,
		r.roomName,r.roomAbbreviation,
        gr.groupId, sub.subjectId, cl.classId
		FROM ".TIME_TABLE_TABLE." tt , period p, `group` gr,  subject sub, employee emp, room r, class cl
		WHERE tt.periodId = p.periodId
		AND gr.classId = cl.classId
		AND tt.roomId = r.roomId
        AND tt.toDate IS NULL
		AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
		AND cl.instituteId=p.instituteId
		AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
        $conditions
        group by p.periodNumber
        $orderBy ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------------------------------
// Purpose: Multi purpose function for time table validations
// Author :Pushpender Kumar Chauhan
// Created on : (18.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function checkIntoTimeTable($conditions='') {

        global $sessionHandler;
        $query = "SELECT tt.timeTableLabelId,tt.timeTableId,tt.roomId,tt.employeeId,tt.groupId,tt.daysOfWeek,tt.periodId,tt.subjectId, s.subjectCode, e.employeeName, g.groupShort,p.periodNumber,r.roomAbbreviation FROM
                  ".TIME_TABLE_TABLE." tt, period p, employee e,`group` g, subject s, room r
                  WHERE tt.toDate IS NULL AND p.periodId=tt.periodId AND e.employeeId=tt.employeeId AND g.groupId=tt.groupId AND s.subjectId=tt.subjectId AND r.roomId=tt.roomId AND
                  (tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." OR tt.instituteId IN (SELECT instituteId FROM employee_can_teach_in WHERE employeeId=tt.employeeId) ) AND
                  tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')." $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// Purpose: update toDate if time table is updated for a teacher
// Author :Pushpender Kumar Chauhan
// Created on : (19.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
 public function updateTimeTable($conditions='') {

        global $sessionHandler;
        $query = "UPDATE ".TIME_TABLE_TABLE." SET toDate=(IF(fromDate=CURRENT_DATE,CURRENT_DATE,(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) )  ) ) WHERE instituteId=".$sessionHandler->getSessionVariable('InstituteId')." AND sessionId=".$sessionHandler->getSessionVariable('SessionId')." $conditions";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// Purpose: add time table data
// Author :Pushpender Kumar Chauhan
// Created on : (19.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
 public function addTimeTable($values) {

        $query = "INSERT INTO ".TIME_TABLE_TABLE." ( `roomId` ,`employeeId` ,`groupId` ,`instituteId` ,`sessionId` ,`daysOfWeek` ,`periodId` ,`subjectId` ,`fromDate` ,`toDate`,`timeTableLabelId`) VALUES $values";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getLabelToClassList($conditions='', $limit = '', $orderBy=' className') {
        /*$query = "SELECT *
		FROM time_table_labels  ttl
		LEFT JOIN time_table_classes ttc
		ON (ttl.timeTableLabelId = ttc.timeTableLabelId $conditions)
		ORDER BY $orderBy";*/
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "SELECT
				         IF(timeTableClassId is NULL,'',timeTableClassId) as timeTableClassId,
				         IF(timeTableLabelId is NULL,'',timeTableLabelId) as timeTableLabelId,
				         cls.classId,className
				 FROM
                        class cls LEFT JOIN time_table_classes ttc ON ttc.classId = cls.classId $conditions
				 WHERE
                        cls.instituteId=".$instituteId." AND
                        cls.sessionId=".$sessionId."
				 ORDER BY
                        $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Active time table Label
//
// Author :Rajeev Aggarwal
// Created on : (18.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getLabelActive($conditions='') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "SELECT isActive, timeTableLabelId FROM time_table_labels $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Inactive Label class
//
// Author :Rajeev Aggarwal
// Created on : (18.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getInActiveLabelToClassList($conditions='', $limit = '', $orderBy=' className') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "SELECT cls.classId,className,timeTableLabelId
				 FROM class cls,time_table_classes ttc
				 WHERE
				 ttc.classId = cls.classId $conditions
				 ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH classes against label
//
// Author :Rajeev Aggarwal
// Created on : (30.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getLabelToClass($labelId) {
		$query = "SELECT classId
				 FROM time_table_classes
				 WHERE timeTableLabelId = $labelId
				";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT SUBJECT TO CLASS DATA
//
// Author :Rajeev Aggarwal
// Created on : (14.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function insertLabelToClass($labelId) {
		global $REQUEST_DATA;

		$chb  = $REQUEST_DATA['chb'];
		$postedClassesList = implode(',', $chb);

		$query = "SELECT a.classId, b.className from time_table_classes a, class b
				 WHERE a.timeTableLabelId != $labelId AND a.classId IN ($postedClassesList) AND a.classId = b.classId";

		$classArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		if (isset($classArray[0]['classId']) and $classArray[0]['classId'] != '') {
			$duplicateClass = '';
			foreach($classArray as $classRecord) {
				$className = $classRecord['className'];
				if (!empty($duplicateClass)) {
					$duplicateClass .= "\n";
				}
				$duplicateClass .= $className;
			}
			echo "Following classes have been linked to other time tables already:\n".$duplicateClass;
			die;
		}


		$chbArr = array();
		$chb1 = array();
		$result1 = array();
		$result2 = array();
		$query = "SELECT classId
				 FROM time_table_classes
				 WHERE timeTableLabelId = $labelId
				";

        $chbArr = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		for($i=0;$i<count($chbArr);$i++)
			$chb1[]= $chbArr[$i]['classId'];
		//print_r($chb);
		//print_r($chb1);

		//if(count($chb)>0)
		$result = array_intersect ($chb, $chb1);
		//print_r($result);
		//if(count($chb)>0 && count($result)>0)
		$result1 = array_diff($chb, $result);
		//print_r($result1);

		//if(count($chb1)>0 && count($result)>0)
		$result2 = array_diff($chb1, $result);
		//print_r($result12);
		//return false;
		//print_r($result);
		if(count($result1)>0)
			$insert_separated = implode(",", $result1);

		if(count($result2)>0)
			$delete_separated = implode(",", $result2);
		// echo $insert_separated ;
		//echo $delete_separated ;
//return false;
		$cnt = count($result1);
		if($labelId)
		{
			if($delete_separated){
				$query = "DELETE
				FROM time_table_classes
				WHERE timeTableLabelId = $labelId and classId IN ($delete_separated)";
				SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
			}
			$insertValue = "";
			if($insert_separated)
			{
			foreach($result1 as $insertId)
			{
				$querySeprator = '';
			    if($insertValue!=''){

					$querySeprator = ",";
			    }
				$insertValue .= "$querySeprator ('".$labelId."','".$insertId."')";
			}
			$query = "INSERT INTO `time_table_classes`
					  (timeTableLabelId,classId)
					  VALUES ".$insertValue;
			}
			SystemDatabaseManager::getInstance()->executeUpdate($query);
			return true;
		}
		else {
			return false;
		}
    }

//------------------------------------------------------------------------------------------------
// This Function gets the data from time table of teacher load
//
// Author : Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getTeacherLoadTimeTable ($conditions='',$orderBy=' emp.employeeName',$limit='',$filter1='',$filter2='') {

        global $sessionHandler;
        $query ="$filter2
						SELECT
                        emp.employeeName AS Name,emp.employeeId, ttlbl.timeTableType
                        $filter1 ,
                        GROUP_CONCAT(DISTINCT CONCAT(sub.subjectCode,'(',
                        (
                          SELECT
                                  GROUP_CONCAT(DISTINCT CONCAT(gr2.groupName,gt.groupTypeCode) ORDER BY gr2.groupName,gt.groupTypeCode SEPARATOR ', ')
                          FROM    ".TIME_TABLE_TABLE." tt2, `group` gr2, group_type gt
                          WHERE   tt2.toDate IS NULL AND
                                  tt2.employeeId=tt.employeeId AND
                                  tt2.subjectId=tt.subjectId AND
                                  tt2.timeTableLabelId = tt.timeTableLabelId AND
                                  tt2.groupId = gr2.groupId AND
                                  gr2.groupTypeId=gt.groupTypeId AND
                                  (tt2.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." OR
                                   tt2.instituteId IN (SELECT instituteId FROM employee_can_teach_in WHERE employeeId=tt.employeeId)) AND
                                   tt2.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        ),')') ORDER BY subjectCode SEPARATOR ', ') AS Subjects
                 FROM
                        time_table_labels ttlbl, ".TIME_TABLE_TABLE." tt, period p, `group` gr,`group_type` gt, `subject` sub, employee emp, room r, class cl,
                        time_table_classes tt1,  subject_type stt
                 WHERE
                        ttlbl.timeTableLabelId = tt.timeTableLabelId
                        AND tt.periodId = p.periodId
                        AND tt.toDate IS NULL
                        AND sub.subjectTypeId = stt.subjectTypeId
                        AND tt.groupId = gr.groupId
                        AND gt.groupTypeId = gr.groupTypeId
                        AND gr.classId = cl.classId
                        AND tt.subjectId = sub.subjectId
                        AND tt.employeeId = emp.employeeId
                        AND tt.roomId = r.roomId
                        AND tt1.timeTableLabelId=tt.timeTableLabelId
                        AND tt1.classId=cl.classId
                        AND (tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        OR tt.instituteId IN (SELECT instituteId FROM employee_can_teach_in WHERE employeeId=tt.employeeId) ) AND
                        tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')." $conditions
                  GROUP BY
                        emp.employeeId, emp.employeeName
                  $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function gets the data from time table of count teacher load
//
// Author : Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
    public function getTeacherCountLoadTimeTable ($conditions='') {

        global $sessionHandler;
        $query ="SELECT
                    COUNT(DISTINCT emp.employeeId) as cnt
                FROM
                    ".TIME_TABLE_TABLE." tt , employee emp,  time_table_labels ttl
                WHERE
                    tt.employeeId=emp.employeeId AND
                    ttl.timeTableLabelId = tt.timeTableLabelId AND
                    tt.toDate IS NULL AND
                    (tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    OR tt.instituteId IN (SELECT instituteId FROM employee_can_teach_in WHERE employeeId=tt.employeeId) ) AND
                    tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function gets the data branchwise fetch teachers
//
// Author : Parveen Sharma
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getBranchTeacher($conditions='') {
        global $sessionHandler;
        $query ="SELECT
                        DISTINCT (e.employeeId), e.employeeName, e.employeeCode FROM `employee` e
                        LEFT JOIN employee_can_teach_in ec ON e.employeeId = ec.employeeId
                 WHERE
                        (e.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." OR
                         ec.instituteId=".$sessionHandler->getSessionVariable('InstituteId').") AND
                         e.isTeaching = 1 AND e.isActive=1
                         $conditions
                         ORDER BY employeeName ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//------------------------------------------------------------------------------------------------
// This Function gets the time table of Period, Daysofweek
//
// Author : Parveen Sharma
// Created on : 16-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
    public function getDaysPeriodsTimeTable($filter='',$conditions='',$orderBy='tt.daysOfWeek,p.periodNumber') {
        global $sessionHandler;
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query ="SELECT
                        $filter
                FROM
                        ".TIME_TABLE_TABLE." tt , period p,  subject sub, employee emp, time_table_labels lbltt, room r, period_slot ps,
                        block bb, building cc
                WHERE
                        r.blockId = bb.blockId
                        AND bb.buildingId = cc.buildingId
                        AND p.periodSlotId = ps.periodSlotId
                        AND tt.periodId = p.periodId
                        AND tt.roomId = r.roomId
                        AND tt.subjectId = sub.subjectId
                        AND tt.employeeId = emp.employeeId
                        AND tt.timeTableLabelId=lbltt.timeTableLabelId
                        AND tt.toDate IS NULL
                        AND tt.sessionId = $sessionId
                        AND lbltt.sessionId = $sessionId
                        AND tt.instituteId= $instituteId
                        AND lbltt.instituteId= $instituteId
                        $conditions
                ORDER BY
                        $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function gets the Teacher Substitutions Record Fetch
//
// Author : Parveen Sharma
// Created on : 19-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
    public function getTeacherSubstitutions($conditions='', $orderBy='', $limit='') {

        global $sessionHandler;
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query ="SELECT
                        tt.employeeId, emp.employeeName, ps.periodSlotId,
                        emp.employeeName, emp.employeeAbbreviation,
                        IF(IFNULL(emp.employeeAbbreviation,'')='',emp.employeeName,CONCAT(emp.employeeName,' (',emp.employeeAbbreviation,')')) AS employeeName1,
                        CONCAT(IFNULL(emp.contactNumber,''),IF(emp.mobileNumber<>'',CONCAT(', ',emp.mobileNumber),'')) AS contactNumber,
                        GROUP_CONCAT(DISTINCT CONCAT(sub.subjectName,'(',sub.subjectCode,') ') ORDER BY sub.subjectCode ASC SEPARATOR ', ') AS subjectName,
                        GROUP_CONCAT(DISTINCT p.periodNumber ORDER BY p.periodSlotId, p.periodNumber ASC SEPARATOR ', ') AS periodNumber,
                        GROUP_CONCAT(DISTINCT p.periodId  ORDER BY p.periodSlotId, p.periodId ASC SEPARATOR ', ') AS periodId
                 FROM
                         period p,  period_slot ps, employee emp, time_table_labels lbltt,
                         ".TIME_TABLE_TABLE." tt, `subject` sub
                 WHERE
                        tt.periodId = p.periodId
                        AND tt.subjectId = sub.subjectId
                        AND p.periodSlotId = ps.periodSlotId
                        AND tt.employeeId = emp.employeeId
                        AND tt.timeTableLabelId=lbltt.timeTableLabelId
                        AND tt.toDate IS NULL
                        AND tt.sessionId = $sessionId
                        AND lbltt.sessionId = $sessionId
                        AND ps.instituteId = $instituteId
                 $conditions
                 GROUP BY tt.employeeId, ps.periodSlotId
                 $orderBy  $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table for timetable labelwise
//
// Author : Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

   public function getTimeTableData($filter='', $conditions='', $orderBy='') {

       global $sessionHandler;

       $query ="SELECT
                        $filter
                FROM
                        ".TIME_TABLE_TABLE." tt , period p, `group` gr,  subject sub, employee emp, room r, class cl, block b, building c
                WHERE
                        tt.periodId = p.periodId   AND  tt.groupId = gr.groupId     AND
                        gr.classId = cl.classId   AND  tt.subjectId=sub.subjectId  AND
                        tt.employeeId=emp.employeeId AND tt.roomId = r.roomId  AND
                        tt.toDate IS NULL AND
								r.blockId = b.blockId AND b.buildingId = c.buildingId AND
                        cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."  AND
                        tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                $conditions
                $orderBy ";

       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

	//------------------------------------------------------------------------------------------------
	// This Function  gets the class subjects
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
	public function getClassSubjects($classId) {
	   $query = "SELECT a.subjectId, b.subjectCode, c.subjectTypeCode, a.optional,a.hasParentCategory from subject_to_class a, subject b, subject_type c, class d
	   where a.subjectId = b.subjectId and a.classId = $classId and b.subjectTypeId = c.subjectTypeId and a.classId = d.classId and c.universityId = d.universityId order by b.subjectCode";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
	//------------------------------------------------------------------------------------------------
	// This Function  gets the class subjects
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
	public function getClassAllSubjects($classId) {
	   $query = "
					SELECT
									a.subjectId,
									b.subjectCode,
									c.subjectTypeCode,
									a.optional
					from			subject_to_class a, subject b, subject_type c, class d
					where			a.subjectId = b.subjectId
					and			a.classId = $classId
					and			b.subjectTypeId = c.subjectTypeId
					and			a.classId = d.classId
					and			c.universityId = d.universityId
					and			a.hasParentCategory = 0
					union
					SELECT
									a.subjectId,
									b.subjectCode,
									c.subjectTypeCode,
									1 as optional
					from			optional_subject_to_class a, subject b, subject_type c, class d
					where			a.subjectId = b.subjectId
					and			a.classId = $classId
					and			b.subjectTypeId = c.subjectTypeId
					and			a.classId = d.classId
					and			c.universityId = d.universityId  order by subjectCode";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

	//------------------------------------------------------------------------------------------------
	// This Function  gets the class subjects based for MBA like streams
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function getClassSubjectsWithOtherSubjects($classId) {
	   $query = "SELECT a.subjectId, b.subjectCode, c.subjectTypeCode, a.optional from subject_to_class a, subject b, subject_type c, class d
	   where a.subjectId = b.subjectId and a.classId = $classId and b.subjectTypeId = c.subjectTypeId and a.classId = d.classId and c.universityId = d.universityId and a.hasParentCategory = 0 union
	   SELECT a.subjectId, a.subjectCode, c.subjectTypeCode, 1 as optional from subject a, subject_type c, class d
	   where a.subjectTypeId = c.subjectTypeId and c.universityId = d.universityId and a.subjectCategoryId != 1
	   order by subjectCode
	   ";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

	//------------------------------------------------------------------------------------------------
	// This Function  gets the class groups
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function getClassGroups($classId) {
	   $query = "SELECT a.groupId, a.groupShort, a.isOptional, b.groupTypeCode, a.optionalSubjectId from `group` a, group_type b where a.classId = $classId and a.groupTypeId = b.groupTypeId ORDER BY a.groupShort";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

	//------------------------------------------------------------------------------------------------
	// This Function  gets the periods based on period slot
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function getPeriods() {
	   $query = "select periodSlotId, periodNumber, startTime, startAmPm, endTime, endAmPm from period";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

	public function getSlotPeriods($periodSlotId = '') {
		global $sessionHandler;
		$str = " and b.isActive = 1";
		if ($periodSlotId != '') {
			$str = " and b.periodSlotId = $periodSlotId";
		}
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "select a.periodNumber, a.startTime, a.startAmPm, a.endTime, a.endAmPm from period a, period_slot b where a.periodSlotId =  b.periodSlotId and b.instituteId = $instituteId $str";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//------------------------------------------------------------------------------------------------
	// This Function  gets the data for current time table
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function getTimeTableCurrentData($timeTableLabelId, $timeTableId = '0',$subjectId='') {
	   
        $subjectCondition = "";
        if($subjectId!='') {
          $subjectCondition = " AND a.subjectId = '$subjectId' ";   
        } 
       
       $query = "
					select
								a.*, b.periodNumber, b.startTime, b.startAmPm, b.endTime, b.endAmPm
					from		".TIME_TABLE_TABLE." a, period b
					where		a.periodId = b.periodId
					and			a.periodSlotId = b.periodSlotId
					and			a.timeTableLabelId = $timeTableLabelId
					and			a.toDate is null
					and			a.timeTableId NOT IN
					(
						select timeTableId FROM time_table_adjustment where toDate >= CURRENT_DATE AND isActive = 1 AND timeTableLabelId = $timeTableLabelId
					)
					and			a.timeTableId NOT IN ($timeTableId)
					$subjectCondition ";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }


	public function getOtherTimeTableCurrentData($timeTableLabelId, $timeTableId = '0',$subjectId='') {
		global $sessionHandler;
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $subjectCondition = "";
        if($subjectId!='') {
          $subjectCondition = " AND a.subjectId = '$subjectId' ";   
        } 
       
        
	   $query = "
					select
								a.*, b.periodNumber, b.startTime, b.startAmPm, b.endTime, b.endAmPm
					from		".TIME_TABLE_TABLE." a, period b, time_table_labels c
					where		a.periodId = b.periodId
					and			a.periodSlotId = b.periodSlotId
					and			a.timeTableLabelId != $timeTableLabelId
					and			a.toDate is null
					and			a.timeTableId NOT IN
					(
						select timeTableId FROM time_table_adjustment where toDate >= CURRENT_DATE AND isActive = 1 AND timeTableLabelId = $timeTableLabelId
					)
					and			a.timeTableId NOT IN ($timeTableId)
					and			a.timeTableLabelId = c.timeTableLabelId
					and			c.sessionId = $sessionId
					and			c.instituteId = $instituteId
					and			c.isActive = 1
					$subjectCondition";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//------------------------------------------------------------------------------------------------
	// This Function  updates the current entries
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function updateCurrentTimeTable($periodSlotId, $classId, $timeTableLabelId, $conditions = '', $conditions2 = '') {
	   global $sessionHandler;
	   $query = "update ".TIME_TABLE_TABLE." set toDate = (IF(fromDate=CURRENT_DATE,CURRENT_DATE,(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) )  ) ) where timeTableLabelId = $timeTableLabelId and groupId in (select groupId from `group` where classId = $classId) and periodSlotId = $periodSlotId $conditions $conditions2 and instituteId=".$sessionHandler->getSessionVariable('InstituteId')." AND sessionId=".$sessionHandler->getSessionVariable('SessionId');
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
   }
   public function updateCurrentTimeTable2($periodSlotId, $classId, $timeTableLabelId, $fromDate) {
	   global $sessionHandler;
	   $query = "update ".TIME_TABLE_TABLE." set toDate = fromDate where timeTableLabelId = $timeTableLabelId and groupId in (select groupId from `group` where classId = $classId) and periodSlotId = $periodSlotId and instituteId=".$sessionHandler->getSessionVariable('InstituteId')." AND sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND fromDate='$fromDate'";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
   }

	public function getGroupStudents($groupId) {
	   $query = "SELECT studentId from student_groups where groupId = $groupId union SELECT studentId from student_optional_subject where groupId = $groupId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function updateCurrentTimeTableDayWise($periodSlotId, $classId, $timeTableLabelId, $conditions) {
	   global $sessionHandler;
	   $query = "update ".TIME_TABLE_TABLE." set toDate = fromDate where timeTableLabelId = $timeTableLabelId and groupId in (select groupId from `group` where classId = $classId) and periodSlotId = $periodSlotId $conditions $conditions2 and instituteId=".$sessionHandler->getSessionVariable('InstituteId')." AND sessionId=".$sessionHandler->getSessionVariable('SessionId');
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//------------------------------------------------------------------------------------------------
	// This Function  gets the data for current class
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function getClassTimeTableAdvanced($periodSlotId, $timeTableLabelId,$classId, $condition = '', $condition2 = '', $condition4 = '') {
	   $query = "
				select
						a.employeeId,
						a.subjectId,
						a.groupId,
						a.roomId
						$condition
				from	".TIME_TABLE_TABLE." a, period b, subject c, `group` d, employee e
				where	a.toDate is null
				and		a.timeTableLabelId = $timeTableLabelId
				and		a.groupId  = d.groupId
				and		d.classId = $classId
				and		a.subjectId = c.subjectId
				and		a.employeeId = e.employeeId
				$condition4
				and a.periodSlotId = $periodSlotId
				group by	a.employeeId, a.subjectId, a.groupId, a.roomId
				$condition2
				order by	c.subjectCode, d.groupShort, e.employeeName
				";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   public function getClassTimeTableAdvancedDayWise($fromDate,$periodSlotId, $timeTableLabelId,$classId, $condition = '', $condition2 = '', $condition4 = '') {
	   $query = "
				select
						a.employeeId,
						a.subjectId,
						a.groupId,
						a.roomId
						$condition
				from	".TIME_TABLE_TABLE." a, period b, subject c, `group` d, employee e
				where	a.toDate is null
				and		a.timeTableLabelId = $timeTableLabelId
				and		a.groupId  = d.groupId
				and		d.classId = $classId
				and		a.subjectId = c.subjectId
				and		a.employeeId = e.employeeId
				$condition4
				and a.periodSlotId = $periodSlotId
				and a.fromDate = '$fromDate'
				group by	a.employeeId, a.subjectId, a.groupId, a.roomId
				$condition2
				order by	c.subjectCode, d.groupShort, e.employeeName
				";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

   public function getExtraClassesTimeTable($periodSlotId, $timeTableLabelId,$classId, $condition = '', $condition2 = '') {
	   $query = "
				select
						a.employeeId,
						a.subjectId,
						a.groupId,
						a.roomId
						$condition
				from	time_table_adjustment a, period b, subject c, `group` d, employee e
				where	a.timeTableLabelId = $timeTableLabelId
				and		a.groupId  = d.groupId
				and		d.classId = $classId
				and		a.subjectId = c.subjectId
				and		a.employeeId = e.employeeId
				and		a.adjustmentType = 4
				and a.periodSlotId = $periodSlotId
				group by	a.employeeId, a.subjectId, a.groupId, a.roomId
				$condition2
				order by	c.subjectCode, d.groupShort, e.employeeName
				";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

	//------------------------------------------------------------------------------------------------
	// This Function  gets all the periods
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function getAllPeriods($periodSlotId) {
	   $query = "SELECT periodId, periodNumber from period where periodSlotId = $periodSlotId order by periodNumber";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

	//------------------------------------------------------------------------------------------------
	// This Function  adds new time table in transaction
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
   public function addNewTimeTableInTransaction($insertStr) {
	   $query = "INSERT INTO ".TIME_TABLE_TABLE."(roomId, employeeId, groupId, classId, instituteId, sessionId, daysOfWeek, periodSlotId, periodId, subjectId, fromDate, toDate, timeTableLabelId) values $insertStr";
	   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
   }

   public function getTimeTableAdjustmentData($timeTableLabelId) {
	   $query = "
					SELECT
								a.*, b.periodNumber, b.startTime, b.startAmPm, b.endTime, b.endAmPm, c.classId
					FROM		time_table_adjustment a, period b, `group` c
					WHERE		a.periodId = b.periodId
					AND			a.periodSlotId = b.periodSlotId
					AND			a.timeTableLabelId = $timeTableLabelId
					AND			a.groupId = c.groupId
					AND			a.toDate >= CURRENT_DATE
					AND			a.isActive = 1";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

/**********************THESE FUNCTIONS WILL BE USED IN SUBSTITUTE TEACHER's TIME TABLE MODULE****************************/

public function getTeacherTimeTableForActiveTimeTable ($conditions='',$order='ORDER BY p.periodNumber,tt.daysOfWeek'){
        global $sessionHandler;
        global $REQUEST_DATA;
        $startDate=trim($REQUEST_DATA['fromDate']);
        $endDate=trim($REQUEST_DATA['toDate']);
        //AND tta.fromDate='".trim($REQUEST_DATA['fromDate'])."' AND tta.toDate='".trim($REQUEST_DATA['toDate'])."'

        $query = "SELECT
                        DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,
                        CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
                        SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
                        sub.subjectName,sub.subjectCode,
                        r.roomName,r.roomAbbreviation, gr.groupShort, emp.employeeName,emp.employeeId,
                        tt.timeTableId,
                        1 AS ttype
                   FROM
                        period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl,".TIME_TABLE_TABLE." tt
                   WHERE
                        tt.periodId = p.periodId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND tt.toDate IS NULL
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        AND tt.timeTableId NOT IN
                          (
                            SELECT
                                  tta.timeTableId
                            FROM
                                  time_table_adjustment tta
                            WHERE
                                  tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                  AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                  AND (
                                        (tta.fromDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (tta.toDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (tta.fromDate <= '$startDate' AND tta.toDate>= '$endDate')
                                  )
								  AND tta.timeTableLabelId=ttl.timeTableLabelId
                                  AND tta.isActive=1
                          )
                        $conditions
                  UNION ALL
                  SELECT
                        DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,
                        CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
                        SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
                        sub.subjectName,sub.subjectCode,
                        r.roomName,r.roomAbbreviation, gr.groupShort, emp.employeeName,emp.employeeId,
                        tt.timeTableId,
                        2 AS ttype
                   FROM
                        time_table_adjustment tt , period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl
                   WHERE
                        tt.periodId = p.periodId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        $conditions
                        AND (
                                (tt.fromDate BETWEEN '$startDate' AND '$endDate')
                                 OR
                                (tt.toDate BETWEEN '$startDate' AND '$endDate')
                                 OR
                                (tt.fromDate <= '$startDate' AND tt.toDate>= '$endDate')
                           )
                        AND tt.isActive=1
                        $order";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



public function getAdjustedTeacherTimeTableForActiveTimeTable ($conditions='',$order='ORDER BY p.periodNumber,tt.daysOfWeek'){
        global $sessionHandler;
        global $REQUEST_DATA;
        $startDate=trim($REQUEST_DATA['fromDate']);
        $endDate=trim($REQUEST_DATA['toDate']);

         $query = "SELECT
                        DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,
                        CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
                        SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
                        sub.subjectName,sub.subjectCode,
                        r.roomName,r.roomAbbreviation, gr.groupShort, emp.employeeName,emp.employeeId,
                        tt.timeTableId,tt.timeTableAdjustmentId,tt.fromDate,tt.toDate,
                        tt.groupId,cl.classId,tt.subjectId
                   FROM
                        time_table_adjustment tt , period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl
                   WHERE
                        tt.periodId = p.periodId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        $conditions
                        AND (
                                (tt.fromDate BETWEEN '$startDate' AND '$endDate')
                                 OR
                                (tt.toDate BETWEEN '$startDate' AND '$endDate')
                                 OR
                                (tt.fromDate <= '$startDate' AND tt.toDate>= '$endDate')
                           )
                        AND tt.isActive=1
                        AND tt.adjustmentType=3
                        $order";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function fetchAttendanceRecords($employeeId,$classId,$groupId,$subjectId,$fromDate,$toDate){
    $query="
            SELECT
                  COUNT(*) AS totalRecords
            FROM  ".ATTENDANCE_TABLE."
            WHERE
                   employeeId=$employeeId
                   AND classId=$classId
                   AND groupId=$groupId
                   AND subjectId=$subjectId
                   AND
                       (
                         ( fromDate BETWEEN '$fromDate' AND '$toDate' )
                          OR
                         ( toDate BETWEEN '$fromDate' AND '$toDate' )
                          OR
                         ( fromDate<= '$fromDate' AND toDate >= '$toDate' )
                       )
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function fetchAttendanceHistory($employeeId,$classId,$groupId,$subjectId,$fromDate,$toDate){
    $query="
            SELECT
                  attendanceId
            FROM  ".ATTENDANCE_TABLE."
            WHERE
                   employeeId=$employeeId
                   AND classId=$classId
                   AND groupId=$groupId
                   AND subjectId=$subjectId
                   AND
                       (
                         ( fromDate BETWEEN '$fromDate' AND '$toDate' )
                          OR
                         ( toDate BETWEEN '$fromDate' AND '$toDate' )
                          OR
                         ( fromDate<= '$fromDate' AND toDate >= '$toDate' )
                       )
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function fetchAdjustedTimeTableData($timeTableAdjustmentId){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');

    $query="
            SELECT
                   tta.employeeId,tta.groupId,g.classId,tta.subjectId,tta.fromDate,tta.toDate
            FROM
                   time_table_adjustment tta,`group` g,`class` cl
            WHERE
                   tta.groupId=g.groupId
                   AND g.classId=cl.classId
                   AND cl.sessionId=$sessionId
                   AND cl.instituteId=$instituteId
                   AND tta.timeTableAdjustmentId=$timeTableAdjustmentId
          ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function quarantineAttendance($attendanceIds){
   global $sessionHandler;
   $userId=$sessionHandler->getSessionVariable('UserId');
   $deletionType=4;
  $query="
           INSERT INTO ".QUARANTINE_ATTENDANCE_TABLE."
             (
               `attendanceId`,`classId`,`groupId`,`studentId`,`subjectId`,
               `employeeId`,`attendanceType`,`attendanceCodeId` ,
               `periodId`,`fromDate` ,`toDate`,`isMemberOfClass`,
               `lectureDelivered`,`lectureAttended`,`userId`,
               `topicsTaughtId` ,`deletedByUserId`,`deletionType`
             )
            SELECT
               `attendanceId`,`classId`,`groupId`,`studentId`,`subjectId`,
               `employeeId`,`attendanceType`,`attendanceCodeId` ,
               `periodId`,`fromDate` ,`toDate`,`isMemberOfClass`,
               `lectureDelivered`,`lectureAttended`,`userId`,
               `topicsTaughtId` ,".$userId.",".$deletionType."
            FROM

                ".ATTENDANCE_TABLE."
            WHERE
                attendanceId IN ($attendanceIds)
        ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}


public function deleteAttendance($attendanceIds){
   global $sessionHandler;
   $query="
            DELETE
            FROM
                ".ATTENDANCE_TABLE."
            WHERE
                attendanceId IN ($attendanceIds)
        ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}

public function getTimeTableRecords ($timeTableId){
        $query =  "SELECT
                         *
                   FROM
                         ".TIME_TABLE_TABLE."
                   WHERE
                         timeTableId=$timeTableId
                        ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function addAdjustedTimeTableRecords($dbQueryString) {
      $query = "INSERT INTO
                            time_table_adjustment
                             (
                               timeTableId,roomId,employeeId,groupId,instituteId,
                               sessionId,daysOfWeek,periodSlotId,periodId,subjectId,
                               fromDate,toDate,isActive,timeTableLabelId,userId,oldEmployeeId,
                               createdOn,cancelledOn,cancelledByUserId,adjustmentType
                             )
                 VALUES
                            $dbQueryString
                 ";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
   }

   public function updateOldEntries($postPeriodSlotId, $timeTableLabelId, $classId, $date) {
	   $query = "update time_table_adjustment set isActive = 0 where periodSlotId = $postPeriodSlotId AND timeTableLabelId = $timeTableLabelId AND fromDate = '$date' and adjustmentType = 4 and groupId IN (select groupId from `group` where classId = $classId)";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
   }


public function updateAdjustedTimeTableRecordsForFutureDates($adjustmentIds,$cancelledByUserId) {
      $query = "UPDATE
                      time_table_adjustment
                SET
                      isActive=0,
                      cancelledByUserId=$cancelledByUserId,
                      cancelledOn=current_date()
                WHERE
                      timeTableAdjustmentId IN ($adjustmentIds)
                      AND ( fromDate > current_date() AND toDate > current_date() )
                 ";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
   }

public function updateAdjustedTimeTableRecordsForIntermediateDates($adjustmentIds,$cancelledByUserId) {
      $query = "UPDATE
                      time_table_adjustment
                SET
                      toDate=current_date(),
                      cancelledByUserId=$cancelledByUserId,
                      cancelledOn=current_date()
                WHERE
                      timeTableAdjustmentId IN ($adjustmentIds)
                      AND ( fromDate <= current_date() AND toDate >= current_date() )
                 ";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
   }

public function deleteAdjustedTimeTableRecords($condition='1=0'){
   global $sessionHandler;
   $cancelledByUserId=$sessionHandler->getSessionVariable('UserId');
   $query="
           UPDATE
                 time_table_adjustment
           SET
                 isActive=0,
                 cancelledByUserId=$cancelledByUserId,
                 cancelledOn=current_date()
           WHERE
                $condition
          ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}


/**********************THESE FUNCTIONS WILL BE USED IN SUBSTITUTE TEACHER's TIME TABLE MODULE****************************/

	public function getFutureEntries($fromDate, $toDate, $timeTableLabelId, $dayId) {
		$query = "SELECT a.*, b.periodNumber, b.startTime, b.startAmPm, b.endTime, b.endAmPm FROM  time_table_adjustment a, period b where a.daysOfWeek = $dayId AND a.timeTableLabelId = $timeTableLabelId AND a.isActive = 1 AND (('$fromDate' BETWEEN  a.fromDate AND a.toDate) OR ('$toDate' BETWEEN  a.fromDate AND a.toDate)) AND a.periodId = b.periodId AND	a.periodSlotId = b.periodSlotId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}



	//------------------------------------------------------------------------------------------------
	// This Function  picks entries from adjustment of future + (entries not from extra classes or entries not belong to this class)
	//
	// Author : Ajinder Singh
	// Created on : 30-Sep-2009
	// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
	//
	//------------------------------------------------------------------------------------------------
	public function getFutureAdjustmentEntries($fromDate, $timeTableLabelId, $groupId) {
		$query = "SELECT a.*, b.periodNumber, b.startTime, b.startAmPm, b.endTime, b.endAmPm FROM  time_table_adjustment a, period b where a.timeTableLabelId = $timeTableLabelId AND a.isActive = 1 AND (('$fromDate' BETWEEN  a.fromDate AND a.toDate) OR ('$fromDate' BETWEEN  a.fromDate AND a.toDate)) AND a.periodId = b.periodId AND	a.periodSlotId = b.periodSlotId AND (a.adjustmentType != 4 OR a.groupId NOT IN (select groupId from `group` where classId = (select classId from `group` where groupId =$groupId)))";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function updateAdjustment($teacherId, $subjectId,$groupId,$roomId,$dayCtr,$period, $periodSlotId) {
		$query = "update time_table_adjustment set isActive = 2 where oldEmployeeId = $teacherId AND subjectId = $subjectId AND groupId = $groupId AND roomId = $roomId  AND daysOfWeek = $dayCtr  AND periodId = (SELECT periodId from period where periodSlotId = $periodSlotId AND periodNumber = '$period') AND periodSlotId = $periodSlotId AND toDate >= CURRENT_DATE AND isActive = 1";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function rollbackAdjustment() {
		$query = "update time_table_adjustment set isActive = 1 where isActive = 2";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function findMissingAdjustment() {
		$query = "select a.*, b.periodNumber, b.startTime, b.startAmPm, b.endTime, b.endAmPm FROM  time_table_adjustment a, period b  where a.isActive = 1 AND a.periodId = b.periodId AND	a.periodSlotId = b.periodSlotId and
		concat_ws('#',a.oldEmployeeId, a.roomId, a.groupId, a.subjectId, a.daysOfWeek,a.periodSlotId,a.periodId) not in
		(select concat_ws('#',employeeId, roomId, groupId, subjectId, daysOfWeek,periodSlotId,periodId) from ".TIME_TABLE_TABLE." where toDate is null)
		";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassAdjustments($timeTableLabelId, $classId) {
		$query = "
					SELECT
								tta.*,
								empNew.employeeName as newEmployee,
								empOld.employeeName as oldEmployee,
								grp.groupShort,
								sub.subjectCode,
								per.periodNumber
					FROM		time_table_adjustment tta,
								employee empNew,
								employee empOld,
								`group` grp,
								subject sub,
								period per
					WHERE		tta.toDate >= CURRENT_DATE
					AND			tta.isActive = 1
					AND			tta.timeTableLabelId = $timeTableLabelId
					AND			tta.employeeId = empNew.employeeId
					AND			tta.oldEmployeeId = empOld.employeeId
					AND			tta.groupId = grp.groupId
					AND			tta.subjectId = sub.subjectId
					AND			grp.classId = $classId
					AND			tta.periodId = per.periodId
			";
	        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}

	public function findDuplicateEntries() {
		global $sessionHandler;
		$query = "
					SELECT
								a.*
					FROM		".TIME_TABLE_TABLE." a, time_table_adjustment b
					WHERE		a.toDate is null
					AND			b.toDate >= CURRENT_DATE
					AND			b.isActive = 1
					AND			a.roomId = b.roomId
					AND			a.employeeId = b.employeeId
					AND			a.groupId = b.groupId
					AND			a.instituteId = b.instituteId
					AND			a.sessionId = b.sessionId
					AND			a.daysOfWeek = b.daysOfWeek
					AND			a.periodSlotId = b.periodSlotId
					AND			a.periodId = b.periodId
					AND			a.subjectId = b.subjectId
					AND			a.timeTableLabelId = b.timeTableLabelId
					AND			a.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					AND			a.sessionId=".$sessionHandler->getSessionVariable('SessionId');
	        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

/**********************THESE FUNCTIONS WILL BE USED IN MOVE TEACHER's TIME TABLE MODULE****************************/

//------------------------------------------------------------------------------------------------
// This Function gets the subject of class
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------


public function getSubjectClassFromTimeTable($orderBy=' sub.subjectCode',$conditions='') {
		global $sessionHandler;
		$query = "
					SELECT		distinct sub.subjectId,
								sub.subjectCode
					FROM		".TIME_TABLE_TABLE." t,
								time_table_classes ttc,
								subject sub,
								class cl,
								subject_to_class stc
					WHERE		t.subjectId = sub.subjectId
					AND			ttc.classId = cl.classId
					AND			stc.classId = cl.classId
					AND			stc.subjectId = sub.subjectId
					AND			t.toDate IS NULL
					AND			cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					AND			cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
								$conditions ORDER BY $orderBy";
	        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//------------------------------------------------------------------------------------------------
// This Function gets the Groups of class
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getGroupsClassFromTimeTable($orderBy=' sub.subjectCode',$conditions='') {
		global $sessionHandler;
			$query = "
					SELECT		distinct gr.groupId,
								gr.groupShort
					FROM		".TIME_TABLE_TABLE." t,
								`group` gr,
								subject sub
					WHERE		t.groupId = gr.groupId
					AND			t.subjectId = sub.subjectId
					AND			t.toDate IS NULL
					AND			t.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					AND			t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
								$conditions ORDER BY $orderBy";
	        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//------------------------------------------------------------------------------------------------
// This Function gets the Employees of class
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getEmployeeClassFromTimeTable($orderBy=' emp.employeeName',$conditions='') {
		global $sessionHandler;
		 $query = "
					SELECT		distinct emp.employeeId,
								emp.employeeName
					FROM		`employee` emp,
								".TIME_TABLE_TABLE." t,
								`group` gr,
								`subject` sub
					WHERE		t.groupId = gr.groupId
					AND			t.subjectId = sub.subjectId
					AND			t.employeeId = emp.employeeId
					AND			t.toDate IS NULL
					AND			t.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					AND			t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
								$conditions ORDER BY $orderBy";
	        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//------------------------------------------------------------------------------------------------
// This Function gets the time table
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getTeacherTimeTableForMoveCopy ($conditions='',$condition='',$order='ORDER BY p.periodNumber,tt.daysOfWeek'){
        global $sessionHandler;
        global $REQUEST_DATA;
		$fromDate = trim($REQUEST_DATA['fromDate']);
		$toDate = trim($REQUEST_DATA['toDate']);

	    $query = "SELECT
                        DISTINCT tt.periodId,
						tt.daysOfWeek,
						p.periodNumber,
                        CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
                        SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className,
						gr.classId,
                        sub.subjectName,
						sub.subjectCode,
                        r.roomName,
						r.roomAbbreviation,
						gr.groupShort,
						emp.employeeName,
						emp.employeeId,
                        tt.timeTableId,
						tt.fromDate,
						tt.toDate,
						tt.employeeId,
						tt.groupId,
						tt.subjectId,
						tt.periodId,
						1 AS ttype
                   FROM
                        period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl,".TIME_TABLE_TABLE." tt
                   WHERE
                        tt.periodId = p.periodId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND tt.toDate IS NULL
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        $conditions $order";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//------------------------------------------------------------------------------------------------
// This Function gets the time table
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getTeacherTimeTableForAdjustment ($conditions=''){
        global $sessionHandler;
        global $REQUEST_DATA;
		$fromDate = trim($REQUEST_DATA['fromDate']);
		$toDate = trim($REQUEST_DATA['toDate']);

	     $query = "SELECT
                        *
                   FROM
                        time_table_adjustment tta $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function gets the time table detail
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function getTimeTableDetail ($insertValue){
        $query =  "SELECT
                         *
                   FROM
                         time_table
                   WHERE
                         timeTableId IN ($insertValue)
                        ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function add the value in time table adjustment
//
// Author : Jaineesh
// Created on : 30-10-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function insertCopyMoveTimeTable ($insertValue){
        $query =  "INSERT INTO time_table_adjustment (timeTableId, roomId, employeeId, groupId, instituteId, sessionId, daysOfWeek, periodSlotId, periodId, subjectId, fromDate, toDate, timeTableLabelId, oldEmployeeId, userId, createdOn, isActive, cancelledByUserId, cancelledOn, adjustmentType) VALUES $insertValue";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function gets latest id for selected values
//
// Author : Jaineesh
// Created on : 30-10-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
	public function getNewTimeTableId($roomId, $employeeId, $groupId, $instituteId, $sessionId, $daysOfWeek, $periodSlotId, $periodId, $subjectId, $timeTableLabelId) {
		$query = "SELECT MAX(timeTableId) as timeTableId FROM ".TIME_TABLE_TABLE." where roomId = $roomId and employeeId = $employeeId and groupId = $groupId and instituteId =  $instituteId and sessionId = $sessionId and daysOfWeek = $daysOfWeek and periodSlotId = $periodSlotId and periodId = $periodId and subjectId = $subjectId and timeTableLabelId = $timeTableLabelId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//------------------------------------------------------------------------------------------------
// This Function updates adjustment
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
	public function updateAdjustmentInTransaction($timeTableId, $newId) {
		$query = "UPDATE time_table_adjustment SET timeTableId = $newId WHERE timeTableId = $timeTableId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

//------------------------------------------------------------------------------------------------
// This Function updates adjustment
//
// Author : Jaineesh
// Created on : 25-02-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getCopyMoveTeacherTimeTableForTimeTable ($conditions='',$order='ORDER BY p.periodNumber,tt.daysOfWeek'){
        global $sessionHandler;
        global $REQUEST_DATA;
        $startDate=trim($REQUEST_DATA['fromDate']);
        $endDate=trim($REQUEST_DATA['toDate']);

         $query = "SELECT
                        DISTINCT tt.periodId, tt.daysOfWeek, p.periodNumber,
                        CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
                        SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
                        sub.subjectName,sub.subjectCode,
                        r.roomName,r.roomAbbreviation, gr.groupShort, emp.employeeName,emp.employeeId,
                        tt.timeTableId,tt.timeTableAdjustmentId,tt.fromDate,tt.toDate,
                        tt.groupId,cl.classId,tt.subjectId
                   FROM
                        time_table_adjustment tt , period p, `group` gr,  subject sub, employee emp, room r, class cl,
                        time_table_labels ttl
                   WHERE
                        tt.periodId = p.periodId
                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                        AND tt.roomId = r.roomId
                        AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                        AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                        $conditions
                        AND (
                                (tt.fromDate BETWEEN '$startDate' AND '$endDate')
                                 OR
                                (tt.toDate BETWEEN '$startDate' AND '$endDate')
                                 OR
                                (tt.fromDate <= '$startDate' AND tt.toDate>= '$endDate')
                           )
                        AND tt.isActive=1
                        AND (tt.adjustmentType = 1 OR tt.adjustmentType = 2)
                        $order";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getTimeTableClassAdjustmentData($timeTableLabelId, $classId, $subjectId='') {
       
       $subjectCondition = "";
       if($subjectId!='') {
         $subjectCondition = " AND a.subjectId = '$subjectId' ";   
       } 
        
	   $query = "
					SELECT
								a.*, b.periodNumber, b.startTime, b.startAmPm, b.endTime, b.endAmPm, c.classId
					FROM		time_table_adjustment a, period b, `group` c
					WHERE		a.periodId = b.periodId
					AND			a.periodSlotId = b.periodSlotId
					AND			a.timeTableLabelId = $timeTableLabelId
					AND			a.groupId = c.groupId
					AND			a.toDate >= CURRENT_DATE
					AND			a.isActive = 1
					AND			c.classId = $classId
                    $subjectCondition ";
                    
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getTeacherOtherInstituteData($teacherId, $dayId, $subjectId='') {
		global $sessionHandler;
		$currentInstituteId = $sessionHandler->getSessionVariable('InstituteId');
		$currentSessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $subjectCondition = "";
        if($subjectId!='') {
          $subjectCondition = " AND a.subjectId = '$subjectId' ";   
        } 

		$query = "
				SELECT
								a.*,
								b.startTime,
								b.startAmPm,
								b.endTime,
								b.endAmPm
				FROM			 ".TIME_TABLE_TABLE." a, period b, time_table_labels c
				WHERE			a.periodId = b.periodId
				AND				a.employeeId = '$teacherId'
				AND				a.daysOfWeek = '$dayId'
				AND				a.toDate IS NULL
				AND				a.instituteId != '$currentInstituteId'
				AND				a.sessionId = '$currentSessionId'
				AND				a.instituteId = c.instituteId
				AND				a.sessionId = c.sessionId
				AND				a.timeTableLabelId = c.timeTableLabelId
				AND				c.isActive = 1
				$subjectCondition";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	public function getTeacherOtherInstituteDayData($teacherId, $fromDate, $subjectId='') { 
		global $sessionHandler;
		$currentInstituteId = $sessionHandler->getSessionVariable('InstituteId');
		$currentSessionId = $sessionHandler->getSessionVariable('SessionId');

        $subjectCondition = "";
        if($subjectId!='') {
          $subjectCondition = " AND a.subjectId = '$subjectId' ";   
        } 
        
		$query = "
				SELECT
								a.*,
								b.startTime,
								b.startAmPm,
								b.endTime,
								b.endAmPm
				FROM			 ".TIME_TABLE_TABLE." a, period b, time_table_labels c
				WHERE			a.periodId = b.periodId
				AND				a.employeeId = '$teacherId'
				AND				a.fromDate = '$fromDate'
				AND				a.toDate IS NULL
				AND				a.instituteId != '$currentInstituteId'
				AND				a.sessionId = '$currentSessionId'
				AND				a.instituteId = c.instituteId
				AND				a.sessionId = c.sessionId
				AND				a.timeTableLabelId = c.timeTableLabelId
				AND				c.isActive = 1
				$subjectCondition";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getRoomOtherInstituteData($roomId, $dayId) {
		global $sessionHandler;
		$currentInstituteId = $sessionHandler->getSessionVariable('InstituteId');
		$currentSessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "
				SELECT
								a.*,
								b.startTime,
								b.startAmPm,
								b.endTime,
								b.endAmPm
				FROM			 ".TIME_TABLE_TABLE." a, period b, time_table_labels c
				WHERE			a.periodId = b.periodId
				AND				a.roomId = '$roomId'
				AND				a.daysOfWeek = '$dayId'
				AND				a.toDate IS NULL
				AND				a.instituteId != '$currentInstituteId'
				AND				a.sessionId = '$currentSessionId'
				AND				a.instituteId = c.instituteId
				AND				a.sessionId = c.sessionId
				AND				a.timeTableLabelId = c.timeTableLabelId
				AND				c.isActive = 1
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getRoomOtherInstituteDayData($roomId, $fromDate) {
		global $sessionHandler;
		$currentInstituteId = $sessionHandler->getSessionVariable('InstituteId');
		$currentSessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "
				SELECT
								a.*,
								b.startTime,
								b.startAmPm,
								b.endTime,
								b.endAmPm
				FROM			 ".TIME_TABLE_TABLE." a, period b, time_table_labels c
				WHERE			a.periodId = b.periodId
				AND				a.roomId = '$roomId'
				AND				a.fromDate = '$fromDate'
				AND				a.toDate IS NULL
				AND				a.instituteId != '$currentInstituteId'
				AND				a.sessionId = '$currentSessionId'
				AND				a.instituteId = c.instituteId
				AND				a.sessionId = c.sessionId
				AND				a.timeTableLabelId = c.timeTableLabelId
				AND				c.isActive = 1
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subjects associated with a class and time table
// Author : Dipanjan Bhattacharjee
// Created on : (17.03.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getClassSubjectsList($timeTableLabelId,$classId,$orderBy='s.subjectCode',$limit='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "
                  SELECT
                        DISTINCT s.subjectId,s.subjectCode,s.subjectName,
                        s.subjectAbbreviation
                  FROM
                        `subject` s,subject_to_class stc,
                        `class` c,time_table_classes ttc
                  WHERE
                         ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.classId=$classId
                         AND ttc.timeTableLabelId=$timeTableLabelId
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
                  ORDER BY $orderBy
                  $limit
        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalClassSubjects($timeTableLabelId,$classId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "
                  SELECT
                         COUNT(*) AS totalRecords
                  FROM
                        `subject` s,subject_to_class stc,
                        `class` c,time_table_classes ttc
                  WHERE
                         ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.classId=$classId
                         AND ttc.timeTableLabelId=$timeTableLabelId
                         AND c.instituteId=$instituteId
                         AND c.sessionId=$sessionId
        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subjects associated with a class and time table
// Author : Jaineesh
// Created on : (07.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getTimeTableAllClasses($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "
						SELECT
								DISTINCT(ttc.classId),
								className
						FROM	time_table_classes ttc,
								class c
						WHERE	ttc.classId = c.classId
								$conditions
                        ORDER BY c.degreeId,c.branchId,c.studyPeriodId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subjects associated with a class and time table
// Author : Jaineesh
// Created on : (07.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getAllTimeTable($conditions='',$orderBy=' timeTableLabelId') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "
					SELECT
						    timeTableLabelId, labelName, startDate, endDate, isActive, timeTableType
				    FROM
                            time_table_labels
				    WHERE	instituteId ='".$instituteId."'
					AND		sessionId='".$sessionId."'
							$conditions
							ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subjects associated with a class and time table
// Author : Jaineesh
// Created on : (07.07.10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getTimeTableClassSubjects($conditions,$orderBy='s.subjectCode') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "
					SELECT
							DISTINCT s.subjectId,s.subjectCode
					FROM
							`subject` s,
							subject_to_class stc,
							`class` c,
							 ".TIME_TABLE_TABLE." tt
					WHERE	tt.classId=c.classId
					AND		c.classId=stc.classId
                    AND		stc.subjectId=s.subjectId
                    AND		c.instituteId=$instituteId
                    AND		c.sessionId=$sessionId
							$conditions
							ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subjects associated with a class and time table
// Author : Jaineesh
// Created on : (07.07.10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getTimeTableClassTeacher($conditions,$orderBy='employeeName') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "
					SELECT
							DISTINCT emp.employeeId,
							CONCAT(emp.employeeName,' ',emp.middleName,' ',emp.lastName) as employeeName
					FROM
							`employee` emp,
							`class` c,
							 ".TIME_TABLE_TABLE." tt
					WHERE	tt.classId=c.classId
					AND		tt.employeeId = emp.employeeId
                    AND		c.instituteId=$instituteId
                    AND		c.sessionId=$sessionId
							$conditions
							ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subjects associated with a class and time table
// Author : Jaineesh
// Created on : (07.07.10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getTimeTableClassSubjectTeacher($conditions,$limit='',$orderBy='employeeName') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "
					SELECT
							DISTINCT emp.employeeId,
							CONCAT(emp.employeeName,' ',emp.middleName,' ',emp.lastName) as employeeName,
							GROUP_CONCAT(distinct sub.subjectCode SEPARATOR ' , ') AS subjectCode,
							GROUP_CONCAT(distinct c.className SEPARATOR ' , ') AS className, ttl.labelName
					FROM
							`employee` emp,
							subject sub,
							`class` c,
							 ".TIME_TABLE_TABLE." tt,
							time_table_labels ttl
					WHERE	tt.classId=c.classId
					AND		tt.employeeId = emp.employeeId
					AND		tt.subjectId = sub.subjectId
					AND		tt.timeTableLabelId = ttl.timeTableLabelId
                    AND		c.instituteId=$instituteId
                    AND		c.sessionId=$sessionId
							$conditions
							GROUP BY emp.employeeId, c.classId
							ORDER BY $orderBy
							$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Label of Time Table
// Author : Jaineesh
// Created on : (08.07.10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getTimeTableLabel($timeTableLabelId) {
        global $sessionHandler;

         $query = "
					SELECT
							labelName
					FROM
							time_table_labels
					WHERE	timeTableLabelId=".$timeTableLabelId;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Label of Time Table
// Author : Jaineesh
// Created on : (08.07.10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getClassName($classId) {
        global $sessionHandler;

        $query = "
					SELECT
							className
					FROM
							class
					WHERE	classId IN ($classId)";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subject
// Author : Jaineesh
// Created on : (08.07.10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getSubjectName($subjectId) {
        global $sessionHandler;

        $query = "
					SELECT
							subjectCode
					FROM
							subject
					WHERE	subjectId=".$subjectId;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Subject
// Author : Jaineesh
// Created on : (08.07.10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getEmployeeName($employeeId) {
        global $sessionHandler;

        $query = "
					SELECT
							CONCAT(emp.employeeName,' ',emp.middleName,' ',emp.lastName) as employeeName
					FROM
							employee emp
					WHERE	emp.employeeId=".$employeeId;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //--------------------------------------------------------------
//  THIS FUNCTION IS Delete Fee Cycle Classes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function deleteAssignTimeTableClasses($condition='') {
        global $sessionHandler;

        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "DELETE FROM time_table_classes WHERE $condition";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//--------------------------------------------------------------
//  THIS FUNCTION IS Add Fee Cycle Classes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------
    public function addTimeTableClasses($fieldValue) {
        global $sessionHandler;

        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "INSERT INTO `time_table_classes`
                  (`timeTableLabelId`,`classId`)
                  VALUES
                  $fieldValue ";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


    public function getCheckTimeTableClasses($condition='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query ="SELECT
                        tc.timeTableClassId, tc.timeTableLabelId, tc.classId, tc.holdCompreMarks, tc.holdPreCompreMarks
                 FROM
                        time_table_classes tc, class c
                 WHERE
                       tc.classId = c.classId AND
                       c.instituteId = $instituteId
                       $condition ";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getCheckTimeTable($condition='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query ="SELECT
                       COUNT(*) AS cnt
                 FROM
                        ".TIME_TABLE_TABLE." tc, class c
                 WHERE
                       tc.classId = c.classId AND
                       c.instituteId = $instituteId
                 $condition ";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getClassTimeTableAdvancedNew($periodSlotId, $timeTableLabelId,$classId, $subjectId,$condition='') {
    
       $query = "SELECT
                       a.employeeId, a.subjectId, a.groupId, a.roomId,
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=1,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'monday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=2,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'tuesday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=3,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'wednesday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=4,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'thursday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=5,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'friday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=6,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'saturday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=7,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'sunday'
                 FROM 
                        ".TIME_TABLE_TABLE." a, period b, `subject` c, `group` d, employee e
                 WHERE  
                        a.toDate is null
                        AND a.periodId = b.periodId
                        AND a.subjectId = c.subjectId
                        AND a.groupId = d.groupId
                        AND a.employeeId = e.employeeId
                        AND a.timeTableLabelId = '$timeTableLabelId'
                        AND a.classId = '$classId'
                        AND a.periodSlotId = '$periodSlotId'
                        AND a.subjectId = '$subjectId' 
                        $condition
                GROUP BY
                        a.timeTableLabelId, a.periodSlotId, a.classId, a.employeeId, a.subjectId, a.groupId, a.roomId 
                ORDER BY 
                        c.subjectCode, d.groupShort, e.employeeName";
                       
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
    public function getClassTimeTableAdvancedAllNew($periodSlotId, $timeTableLabelId,$classId, $condition='') {
    
       $query = "SELECT
                       a.employeeId, a.subjectId, a.groupId, a.roomId,
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=1,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'monday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=2,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'tuesday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=3,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'wednesday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=4,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'thursday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=5,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'friday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=6,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'saturday',
                       GROUP_CONCAT(DISTINCT IF(a.daysOfWeek=7,IF(b.periodNumber<>'',CONCAT(b.periodNumber,','),''),'') 
                          ORDER BY b.periodNumber,a.daysOfWeek ASC SEPARATOR '') AS 'sunday'
                 FROM 
                        ".TIME_TABLE_TABLE." a, period b, `subject` c, `group` d, employee e
                 WHERE  
                        a.toDate is null
                        AND a.periodId = b.periodId
                        AND a.subjectId = c.subjectId
                        AND a.groupId = d.groupId
                        AND a.employeeId = e.employeeId
                        AND a.timeTableLabelId = '$timeTableLabelId'
                        AND a.classId = '$classId'
                        AND a.periodSlotId = '$periodSlotId'
                        $condition
                GROUP BY
                        a.timeTableLabelId, a.periodSlotId, a.classId, a.employeeId, a.subjectId, a.groupId, a.roomId 
                ORDER BY 
                        c.subjectCode, d.groupShort, e.employeeName";
                       
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
}
?>