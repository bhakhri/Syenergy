<?php
//-------------------------------------------------------------------------------
//StudentReportsManager .
// Author : Parveen Sharma
// Created on : 07.07.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudentReportsManager {
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

//-------------------------------------------------------------------------------
// addBusPass() is used to add new record in database.
// Author : Parveen Sharma
// Created on : 12.06.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addBusPass($strValue='') {
       global $REQUEST_DATA;
       global $sessionHandler;

      // $query = "INSERT INTO bus_pass (studentId,classId,busStopId,busRouteId,userId,receiptNo,validUpto,addedOnDate,busPassStatus)
      //           VALUES ('".$REQUEST_DATA['studentId']."','".$REQUEST_DATA['classId']."','".$REQUEST_DATA['busStopId']."','".$REQUEST_DATA['busRouteId']."','".$sessionHandler->getSessionVariable('UserId')."','".$REQUEST_DATA['receiptNo']."','".$REQUEST_DATA['validUpto']."','".date('Y-m-d')."','1')";

       $query = "INSERT INTO bus_pass (busId,studentId,classId,busStopId,busRouteId,userId,receiptNo,validUpto,addedOnDate,busPassStatus)
                 $strValue ";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

    public function addStudentBusPass($busStop='0',$busRoute='0',$condition='') {
       global $REQUEST_DATA;
       global $sessionHandler;

       $query = "UPDATE student SET busStopId = '".$busStop."', busRouteId ='".$busRoute."' $condition ";

     
       return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// editBusPass() is used to edit new record in database.
// Author : Parveen Sharma
// Created on : 12.06.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function editBusPass($id) {
        global $REQUEST_DATA;
        global $sessionHandler;

        $query = "UPDATE bus_pass SET userId = '".$sessionHandler->getSessionVariable('UserId')."', cancelOnDate ='".date('Y-m-d')."', busPassStatus = '".$REQUEST_DATA['busPassStatus'] ."' WHERE busPassId='$id'";
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
// Purpose: To count Audit Trail details
// Author : Kavish Manjkhola
// Created on : 24.03.2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function getAuditTrailCount($conditions) {
		$query = "
					SELECT
							count(*) as cnt
					FROM
							audit_trail at, user u
					$conditions
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// Purpose: To select Audit Trail details
// Author : Kavish Manjkhola
// Created on : 24.03.2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function getAuditTrailDetails($conditions,$orderBy) {
		$query = "
					SELECT
							at.auditType,u.userName, at.auditDateTime, at.description
					FROM
							audit_trail at, user u
					$conditions
					ORDER BY $orderBy
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getAuditTrailDetailsNew($conditions,$orderBy) {
		$query = "
					SELECT
							at.auditType,u.userName, at.auditDateTime, at.description,at.userip,r.roleName
					FROM
							audit_trail at, user u, role r
					$conditions
					ORDER BY $orderBy
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


    public function editStudentBusPass() {
       global $REQUEST_DATA;
       global $sessionHandler;

       if($REQUEST_DATA['busPassStatus']==2) {
          $query = "UPDATE student SET busStopId = NULL, busRouteId = NULL WHERE studentId = '".$REQUEST_DATA['studentId']."' AND classId = '".$REQUEST_DATA['classId']."'";
          return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
       }
       else {
            return true;
       }
    }


//-------------------------------------------------------------------------------
// deleteBusPass() is used to edit new record in database.
// Author : Parveen Sharma
// Created on : 12.06.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteBusPass($id) {

        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('bus_pass',
        array('timeTableLabelId','studentId','classId','busStopId','busRouteId','userId','receiptNo','validUpto','addedOnDate','busPassStatus','cancelOnDate'),
        array('' ),"busPassId=$id");
    }



//-------------------------------------------------------------------------------
//
//addHostel() is used to add new record in database.
// Author : Jaineesh
// Created on : 27.06.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getSingleStudentList($conditions='') {
		global $sessionHandler;
        $query = "SELECT s.studentId, s.rollNo,s.firstName,s.lastName,s.studentMobileNo,s.fatherName,s.dateOfLeaving,s.corrAddress1,s.corrAddress2,s.permAddress1,s.permAddress2,s.regNo,s.universityRollNo,s.universityRegNo,s.dateOfBirth,s.studentPhoto,c.classId, b.branchId, b.branchName
        FROM student s, class c, branch b
        WHERE s.classId = c.classId AND c.branchId = b.branchId AND c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		AND c.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    public function getAllStudentList($conditions='') {
		global $sessionHandler;
        $query = "SELECT s.studentId, s.rollNo,s.firstName,s.lastName,s.studentMobileNo,s.fatherName,s.dateOfLeaving,s.corrAddress1,s.corrAddress2,s.permAddress1,s.permAddress2,s.regNo,s.universityRollNo,s.universityRegNo,s.dateOfBirth,s.studentPhoto, c.classId, d.degreeName, p.periodicityName, sp.periodName
        FROM student s, class c, branch b, periodicity p, study_period sp, degree d
        WHERE s.classId = c.classId AND c.degreeId = d.degreeId AND c.studyPeriodId=sp.studyPeriodId AND sp.periodicityId=p.periodicityId AND c.branchId=b.branchId AND c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		AND c.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."' $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------
// Function gets classId from the using sessionId,instituteId,degreeId,universityId,batchId,branchId
//
// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------

	public function getClassId($classFilter)
	{
		global $REQUEST_DATA;
		global $sessionHandler;
		$query="SELECT classId FROM class $classFilter AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//funciton return the array of StudentListReport based on th selected fields

// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------

	public function getStudentListReportList($filter,$conditions='',$limit='')
	{
		$query="SELECT $filter $conditions $limit ";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentList($classId, $limit, $sortBy) {
		$query = "
					SELECT
								DISTINCT d.studentId, d.rollNo, CONCAT(d.firstName,' ',d.lastName) AS studentName
					FROM		student d, student_groups e
					WHERE		e.classId = $classId and d.studentId = e.studentId
					ORDER BY	$sortBy
					LIMIT		$limit;
				 ";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	public function getTotalMarksData2($classId, $studentIdList) {

		$query = "SELECT
						tt.studentId, tt.subjectId, tt.conductingAuthority,
						tt.maxMarks, tt.marksScored, tt.marksScoredStatus, tt.gradeId,
						IFNULL((SELECT
								    graceMarks
						      FROM
								    ".TEST_GRACE_MARKS_TABLE."
					  	      WHERE
						           classId = tt.classId AND subjectId = tt.subjectId AND studentId = tt.studentId),'0') AS graceMarks
					FROM
						".TOTAL_TRANSFERRED_MARKS_TABLE." tt
					WHERE
						tt.classId = $classId AND
						tt.studentId IN ($studentIdList) ";

		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

    
    public function getTotalMarksData4($classId, $studentIdList) {

        $query = "SELECT
                        tt.studentId, tt.subjectId, tt.conductingAuthority,
                        tt.maxMarks, tt.marksScored, tt.marksScoredStatus, tt.gradeId,
                        IFNULL((SELECT
                                    CONCAT(IFNULL(internalGraceMarks,0),'!~~!!~~!',IFNULL(externalGraceMarks,0),'!~~!!~~!',IFNULL(totalGraceMarks,0))  
                              FROM
                                    ".TEST_GRACE_MARKS_TABLE."
                                WHERE
                                   classId = tt.classId AND subjectId = tt.subjectId AND studentId = tt.studentId),'0') AS graceMarks
                    FROM
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." tt
                    WHERE
                        tt.classId = $classId AND
                        tt.studentId IN ($studentIdList) ";

        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }


	public function getGradeIdLabels($gradeList) {
		$query = "select gradeId, gradeLabel from grades where gradeId in ($gradeList)";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//----------------------------------------------------------------------------------------------------
//funciton return the array of Subjects based on the class

// Author :Ajinder Singh
// Created on : 16-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd..
//
//----------------------------------------------------------------------------------------------------
	public function getSubjectList($condition) {
		 $query = "SELECT
                        DISTINCT a.subjectId, a.subjectCode, a.subjectName,a.hasAttendance
                    FROM subject a,".ATTENDANCE_TABLE." b WHERE a.subjectId = b.subjectId AND b.classId IN ($condition) ORDER BY a.subjectCode";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	public function getTestSubjectList($classesList) {
        $query = "SELECT
                        DISTINCT a.subjectId, a.subjectCode, a.subjectName
                    FROM subject a, ".TEST_TABLE." b WHERE a.subjectId = b.subjectId AND b.classId IN ($classesList)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	public function getTransferredSubjectList($classesList) {
        $query = "SELECT
                        DISTINCT a.subjectId, a.subjectCode, a.subjectName, a.hasAttendance, a.hasMarks
                    FROM subject a, ".TOTAL_TRANSFERRED_MARKS_TABLE." b WHERE a.subjectId = b.subjectId AND b.classId IN ($classesList)
                    ORDER BY a.subjectCode";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   public function getClassSubjectsWithOtherSubjects($classId) {
	   $query = "SELECT a.subjectId, b.subjectCode, c.subjectTypeCode, a.optional, b.subjectName, b.hasAttendance, b.hasMarks from subject_to_class a, subject b, subject_type c, class d
	   where a.subjectId = b.subjectId and a.classId = $classId and b.subjectTypeId = c.subjectTypeId and a.classId = d.classId and c.universityId = d.universityId and a.hasParentCategory = 0 union
	   SELECT a.subjectId, a.subjectCode, c.subjectTypeCode, 1 as optional, a.subjectName, a.hasAttendance, a.hasMarks from subject a, subject_type c, class d
	   where a.subjectTypeId = c.subjectTypeId and c.universityId = d.universityId and a.subjectCategoryId != 1
	   order by subjectCode
	   ";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

    public function getTeacherSubjectList($classId, $timeTable, $employeeId) {
        $query = "SELECT
                        DISTINCT tt.subjectId, sub.subjectCode, sub.subjectCode, sub.hasAttendance, sub.hasMarks
                  FROM
                        subject sub,  ".TIME_TABLE_TABLE."  tt
                  WHERE
                        timeTableLabelId = $timeTable and employeeId = $employeeId and
                        tt.subjectId = sub.subjectId and tt.subjectId = sub.subjectId and
                        tt.groupId in (select groupId from `group` where classId = $classId)
                  ORDER BY
                        sub.subjectCode ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getUserSubjectList($classId, $timeTable, $userId) {
        $query = "SELECT
                        DISTINCT tt.subjectId, sub.subjectCode, sub.subjectCode, sub.hasAttendance, sub.hasMarks
                  FROM
                        subject sub,  ".TIME_TABLE_TABLE."  tt
                  WHERE
                        timeTableLabelId = $timeTable and employeeId = $employeeId and
                        tt.subjectId = sub.subjectId and tt.subjectId = sub.subjectId and
                        tt.groupId in (select groupId from `group` where classId = $classId)";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------
//funciton return records for attendance report.

// Author :Ajinder Singh
// Created on : 16-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getAttendanceData($classId, $subjectId, $sortField, $sortOrderBy,$limit = '',$attendanceCodeWise='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT
				        s.studentId, s.rollNo, s.universityRollNo, CONCAT(IFNULL(s.firstName,''), ' ',IFNULL(s.lastName,'')) AS studentName,
				        FORMAT( SUM(IF(att.isMemberOfClass=0,0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended )) ) , 1 ) AS lectureAttended,
				        SUM(IF(att.isMemberOfClass=0,0, att.lectureDelivered)) AS lectureDelivered,
				        CONVERT( SUBSTRING( LEFT( s.rollNo, length( s.rollNo ) - LENGTH( c.rollNoSuffix ) ) , LENGTH( c.rollNoPrefix ) +1 ) , UNSIGNED ) AS numericRollNo,
				        (lectureDelivered - lectureAttended) as lectureMissed,
				        ROUND((lectureAttended/lectureDelivered)*100,1) as percentage
                        $attendanceCodeWise
				  FROM 
                        student s 
                        INNER JOIN  ".ATTENDANCE_TABLE."  att ON att.studentId = s.studentId
				        LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  and ac.instituteId = $instituteId)
				        INNER JOIN subject su ON su.subjectId = att.subjectId
				        INNER JOIN class c ON s.classId = c.classId
				  WHERE
				        att.classId = $classId AND
				        att.subjectId = $subjectId
				  GROUP BY
				        att.subjectId, att.studentId 
                  ORDER BY 
                        $sortField $sortOrderBy $limit";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//funciton return records for missed attendance report for all classes, all subjects, all groups

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getAllClassMissedAttendanceReport($labelId='', $tillDate='', $sortField='', $sortOrderBy='',$condition='') {

        $query = "SELECT
                    b.className AS className,
                    c.subjectCode,
                    d.groupName,
                    e.employeeName,
                    MAX(toDate) AS testDate,
                    DATE_FORMAT(max(toDate), '%d-%b-%y') AS toDate
                    FROM  ".ATTENDANCE_TABLE."  a, class b, subject c, `group` d, employee e, time_table_classes f
                    WHERE a.classId = b.classId
                    AND a.subjectId = c.subjectId
                    AND a.groupId = d.groupId
                    AND a.employeeId = e.employeeId
                    AND a.classId = f.classId
                    AND f.timeTableLabelId = $labelId
                    $condition
                    GROUP BY a.classId, a.subjectId, a.groupId, a.employeeId having testDate < '$tillDate'
                    ORDER BY $sortField $sortOrderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }


//----------------------------------------------------------------------------------------------------
//funciton return records for missed attendance report for selected class, all subjects, all groups

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getAllSubjectMissedAttendanceReport($classId, $tillDate, $sortField, $sortOrderBy,$condition='') {
        $query = "    SELECT
                                b.className AS className,
                                c.subjectCode,
                                d.groupName,
                                e.employeeName,
                                MAX(toDate) AS testDate,
                                DATE_FORMAT(max(toDate), '%d-%b-%y') AS toDate
                    FROM         ".ATTENDANCE_TABLE."  a, class b, subject c, `group` d, employee e
                    WHERE        a.classId = b.classId AND
                                a.classId = $classId AND
                                a.subjectId = c.subjectId AND
                                a.groupId = d.groupId AND
                                a.employeeId = e.employeeId
                                $condition
                    GROUP BY    a.classId, a.subjectId, a.groupId, a.employeeId having testDate < '$tillDate'
                    ORDER BY    $sortField $sortOrderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------
//funciton return records for missed attendance report for selected class, selected subject subjects, all groups

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getOneSubjectMissedAttendanceReport($classId, $subjectId, $tillDate, $sortField, $sortOrderBy,$condition='') {
        $query = "    SELECT
                    b.className AS className,
                    c.subjectCode,
                    d.groupName,
                    e.employeeName,
                    MAX(toDate) AS testDate,
                    DATE_FORMAT(max(toDate), '%d-%b-%y') AS toDate
                    FROM  ".ATTENDANCE_TABLE."  a, class b, subject c, `group` d, employee e
                    WHERE a.classId = b.classId
                    AND a.classId = $classId
                    AND a.subjectId = $subjectId
                    AND a.subjectId = c.subjectId
                    AND a.groupId = d.groupId
                    AND a.employeeId = e.employeeId
                    $condition
                    GROUP BY a.classId, a.subjectId, a.groupId, a.employeeId having testDate < '$tillDate'
                    ORDER BY $sortField $sortOrderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getClassesVisibleToRole() {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

		$query = "	SELECT
							distinct cvtr.classId
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

//----------------------------------------------------------------------------------------------------
//general function created for one or more fields from one or more table with option of conditions

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSingleField($table, $field, $conditions='') {
		$query = "SELECT $field FROM $table $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//function created for fetching all subjects of one type

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSubjectListByType($degree, $subjectTypeId) {
        $query = "SELECT
                         a.subjectId, a.subjectCode as subjectName,
                         a.hasMarks, a.hasAttendance
                  FROM
                        subject a, subject_to_class b
                  WHERE
                        a.subjectId = b.subjectId AND
                        b.classId = $degree AND
                        a.subjectTypeId = $subjectTypeId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------
//function created for fetching marks distribution list for all subejects of one type

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getMarksDistributionAllSubjects($degree, $subjectTypeId, $sortField, $limit='',$condition='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        conductingAuthority,
                        IF(b.conductingAuthority = 1, 'INTERNAL', 'EXTERNAL') as conductingAuthority2,
                        a.subjectId,
                        a.subjectCode,
                        b.testTypeName,
                        b.weightageAmount,
                        ROUND(b.weightagePercentage,1) as weightagePercentage
                FROM    subject a, test_type b, class c, subject_to_class d
                WHERE
                        a.subjectId = d.subjectId AND (b.subjectId = d.subjectId) AND
                        c.classId = $degree AND
                        c.classId = d.classId AND
                        a.subjectTypeId = $subjectTypeId AND
                        b.instituteId = $instituteId
                        $condition
                UNION
                SELECT  conductingAuthority,
                        IF(b.conductingAuthority = 1, 'INTERNAL', 'EXTERNAL') as conductingAuthority2,
                        a.subjectId,
                        a.subjectCode,
                        b.testTypeName,
                        b.weightageAmount,
                        ROUND(b.weightagePercentage,1) as weightagePercentage
                FROM    subject a, test_type b, class c , subject_to_class d
                WHERE
                        c.classId = $degree AND
                        a.subjectTypeId = $subjectTypeId AND
                        b.subjectTypeId = $subjectTypeId AND
                        a.subjectId = d.subjectId AND
                        b.instituteId = $instituteId AND
                        c.classId = d.classId
                        $condition
                        AND
                        a.subjectId NOT IN(SELECT IF(subjectId IS NULL,0, subjectId) FROM test_type where instituteId = $instituteId) AND
                        CASE
                            WHEN b.subjectId IS NULL AND (b.studyPeriodId = c.studyPeriodId OR b.studyPeriodId IS NULL) AND (b.branchId = c.branchId OR b.branchId IS NULL) AND (b.degreeId = c.degreeId OR b.degreeId IS NULL) AND b.universityId = c.universityId THEN 2
                        ELSE
                            NULL
                        END
                        IS NOT NULL

                ORDER BY $sortField $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------
// function created for fetching marks distribution list for one subject

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getMarksDistributionOneSubject($degree, $subjectTypeId, $subjectId, $sortField, $limit='',$condition='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        conductingAuthority,
                        IF(b.conductingAuthority = 1, 'INTERNAL', 'EXTERNAL') as conductingAuthority2,
                        a.subjectId,
                        a.subjectCode,
                        b.testTypeName,
                        b.weightageAmount,
                        ROUND(b.weightagePercentage,1) AS weightagePercentage
                FROM    subject a, test_type b
                WHERE    a.subjectId = $subjectId AND
                        a.subjectId = b.subjectId AND
                        b.instituteId = $instituteId AND
                        a.subjectTypeId = $subjectTypeId
                        $condition
                UNION
                SELECT
                        conductingAuthority,
                        IF(b.conductingAuthority = 1, 'INTERNAL', 'EXTERNAL') as conductingAuthority2,
                        a.subjectId,
                        a.subjectCode,
                        b.testTypeName,
                        b.weightageAmount,
                        ROUND(b.weightagePercentage,1) AS weightagePercentage
                FROM    subject a, test_type b, class c
                WHERE    c.classId = $degree AND
                        a.subjectId = $subjectId AND
                        a.subjectTypeId = $subjectTypeId AND
                        b.instituteId = $instituteId AND
                        b.subjectTypeId = $subjectTypeId
                        $condition AND
                        a.subjectId NOT in (SELECT IF(subjectId IS NULL,0, subjectId) FROM test_type where instituteId = $instituteId) AND
                        CASE
                            WHEN (b.subjectId IS NULL) AND
                            (b.studyPeriodId = c.studyPeriodId OR b.studyPeriodId is null) AND
                            (b.branchId = c.branchId OR b.branchId IS NULL) AND
                            (b.degreeId = c.degreeId or b.degreeId is null) AND b.universityId = c.universityId THEN 2
                        ELSE
                            NULL
                        END
                        IS NOT NULL
                        ORDER BY $sortField $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------
//function created for fetching distinct tests entered for a class

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getClassTestTypes($classId, $conditions='') {
		$query = "	SELECT
							DISTINCT(a.testTypeCategoryId),
							b.testTypeName
					FROM	".TEST_TABLE." a, test_type_category b,  ".TEST_MARKS_TABLE."  c
					WHERE	a.testTypeCategoryId = b.testTypeCategoryId AND
							a.classId = $classId AND
							a.testId = c.testId
							$conditions
					GROUP BY a.testTypeCategoryId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//function created for fetching all tests by testTypeId

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getTestDetails($testTypeCategoryId, $conditions='') {
		$query = "	SELECT
							DISTINCT(a.testId) AS testId,
							CONCAT(c.testTypeAbbr, '-',a.testIndex) AS testName,
							a.maxMarks
					FROM	".TEST_TABLE." a,  ".TEST_MARKS_TABLE."  b, test_type_category c
					WHERE	a.testTypeCategoryId = $testTypeCategoryId AND
							a.subjectId = b.subjectId AND
							a.testId = b.testId AND
							a.testTypeCategoryId = c.testTypeCategoryId
							$conditions
					ORDER BY LENGTH(testIndex),testIndex";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	public function getTestDetailsConsolidated($testTypeCategoryId, $conditions='') {
		$query = "	SELECT
							DISTINCT c.testTypeAbbr, a.testIndex,c.testTypeCategoryId,
							CONCAT(c.testTypeAbbr, '-',a.testIndex) AS testName
					FROM	".TEST_TABLE." a,  ".TEST_MARKS_TABLE."  b, test_type_category c
					WHERE	a.testTypeCategoryId = $testTypeCategoryId AND
							a.subjectId = b.subjectId AND
							a.testId = b.testId AND
							a.testTypeCategoryId = c.testTypeCategoryId
							$conditions
					ORDER BY LENGTH(testIndex),testIndex";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	public function countTestWiseMarksResult($classId, $groupId) {
		   $query = "SELECT
							count(a.studentId) as cnt
				FROM		student a, student_groups sg
				WHERE		a.studentId = sg.studentId
				AND			sg.classId = $classId
				and			sg.groupId = $groupId
				UNION
				SELECT
							count(a.studentId) as cnt
				FROM		student a, student_optional_subject sg
				WHERE		a.studentId = sg.studentId
				AND			sg.classId = $classId
				and			sg.groupId = $groupId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	public function countTestWiseMarksResultConsolidated($classId) {
		   $query = "SELECT
							count(distinct(a.studentId)) as cnt
				FROM		student a, student_groups sg
				WHERE		a.studentId = sg.studentId
				AND			a.classId = sg.classId
				AND			a.classId = $classId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

//----------------------------------------------------------------------------------------------------
// function created for fetching student wise test wise marks

// Author :Ajinder Singh
// Created on : 23-Sep-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getTestWiseMarksResult($classId, $firstString, $secondString, $limit = '', $tableName, $order = ' a.rollNo') {
           $query = "SELECT
                            a.studentId,
                            a.rollNo,
                            a.universityRollNo,
                            a.rollNo AS numericRollNo,
                            UCASE(CONCAT(firstName,' ',lastName)) as studentName
                            $firstString
                FROM        student a, class b, $tableName sg
                WHERE       a.studentId = sg.studentId
                AND         a.classId = b.classId
                AND         sg.classId = $classId
                $secondString
                ORDER BY a.isLeet, $order
                $limit
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//function created for fetching student wise test wise marks

// Author :Ajinder Singh
// Created on : 23-Sep-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getTestWiseMarksResultConsolidated($classId, $subjectId, $firstString, $secondString, $limit = '') {
		   $query = "SELECT
							a.studentId,
							a.rollNo,
							a.universityRegNo,
							CONVERT(SUBSTRING(LEFT( a.rollNo, length(a.rollNo) - LENGTH(b.rollNoSuffix)), LENGTH(b.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
							UCASE(CONCAT(firstName,' ',lastName)) as studentName,
							IFNULL((SELECT group_concat(groupShort) from `group` g, student_groups sg where g.groupId = sg.groupId and sg.studentId = a.studentId and g.classId = sg.classId and g.classId = $classId and g.groupTypeId = (select groupTypeId from group_type where groupTypeName IN (select subjectTypeName from subject_type where subjectTypeId = (select subjectTypeId from subject where subjectId = $subjectId)))),'GNA') AS groupName
							$firstString
				FROM		student a, class b
				WHERE		a.classId = b.classId
				AND			a.classId = $classId
							$secondString
				ORDER BY	a.isLeet, numericRollNo
				$limit
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}



//----------------------------------------------------------------------------------------------------
//function created for fetching students from test_transferred_marks for a particular class and particular subject

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function countClassSubjectStudents($classId, $subjectId) {
		$query = "SELECT
							COUNT(DISTINCT(CONCAT(studentId,classId,subjectId))) AS cnt
				  FROM		".TEST_TRANSFERRED_MARKS_TABLE."
				  WHERE		classId = $classId AND
							subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//function created for fetching rangewise student count

// Author :Ajinder Singh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSubjectWiseConsolidatedMarks($classId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT
							a.rangeLabel,
							(
								SELECT
										COUNT(b.studentId) AS cnt
								FROM	student b
								WHERE	b.studentId
								IN (
									SELECT
												c.studentId
									FROM		".TEST_TRANSFERRED_MARKS_TABLE." c
									WHERE		c.classId = $classId AND
												c.subjectId = $subjectId
									GROUP BY	CONCAT(c.studentId, c.classId, c.subjectId)
									HAVING		ROUND(SUM(c.marksScored)/SUM(c.maxMarks)*100)
									BETWEEN		a.rangeFrom AND a.rangeTo
								   )
							) AS studentCount
				  FROM		range_levels a WHERE a.instituteId = $instituteId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");

	}

//----------------------------------------------------------------------------------------------------
//function created for fetching distinct subjects from test_transferred_marks for a particular class

// Author :Ajinder Singh
// Created on : 22-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getClassConsolidatedSubjects($classId) {
		$query = "SELECT
							DISTINCT(a.subjectId) as subjectId,
							b.subjectCode
				  FROM		".TEST_TRANSFERRED_MARKS_TABLE." a, subject b
				  WHERE		a.subjectId = b.subjectId AND
							a.classId = $classId
				  ";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}


	//----------------------------------------------------------------------------------------------------
	//function created for fetching rangewise classwise subjectwise marks.

	// Author :Ajinder Singh
	// Created on : 22-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	/*	Please note that same $conditions part is made in
		1. Library/StudentReports/initSubjectwiseConsolidatedReport.php
		2. Templates/listSubjectWiseConsolidatedGraphPrint.php
		3. Templates/listSubjectWiseConsolidatedReportPrint.php
	*/
	public function getAllSubjectConsolidatedMarks($conditions) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
		$query = "SELECT
							a.rangeLabel
							$conditions
				  FROM		range_levels a";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data for student label reports

	// Author :Ajinder Singh
	// Created on : 22-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getStudentLabelData($universityId, $degreeId, $branchId) {
		global $REQUEST_DATA;
		$query = "	SELECT
								s.firstName,
								s.lastName,
								s.studentGender,
								s.corrAddress1,
								s.corrAddress2,
								s.corrPinCode,
								s.corrPhone,
								s.fatherName,
								st.stateCode,
								ct.cityName
					FROM		class c, student s
					LEFT JOIN	states st ON ( s.corrStateId = st.stateId )
					LEFT JOIN	city ct ON ( s.corrCityId = ct.cityId )
					WHERE		s.classId = c.classId AND
								c.universityId = '".$universityId."' AND
								c.degreeId='".$degreeId."' AND
								c.branchId='".$branchId."' AND
								c.studyPeriodId = '".$REQUEST_DATA['studyPeriodId']."' AND
								c.batchId='".$REQUEST_DATA['batchId']."'
					ORDER BY	s.firstName ASC, s.lastName ASC";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of employees who teach to a particular group

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getEmployeeList($classId, $conditions = '') {
			global $sessionHandler;
			$query = "
					SELECT
							DISTINCT(a.employeeId) AS employeeId,
							b.employeeName
					FROM	".TEST_TABLE." a, employee b
					WHERE	a.employeeId = b.employeeId
					AND		a.classId = $classId
							$conditions
					ORDER BY b.employeeName
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");

	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of groups which are taught by a particular employee

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getEmployeeGroupList($classId, $employeeId, $conditions='') {
			global $sessionHandler;
			$query = "
					SELECT
							DISTINCT(a.groupId) AS groupId,
							b.groupShort
					FROM	".TEST_TABLE." a, `group` b
					WHERE	a.groupId = b.groupId
					AND		a.classId = $classId
					AND		a.employeeId = $employeeId
							$conditions
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");

	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of tests of a particular class and particular subject

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestList($classId, $conditions = '') {
			$query = "
					SELECT
								testId,
								CONCAT(testAbbr,'-', testIndex) as testName
					FROM		".TEST_TABLE."
					WHERE		classId = $classId
								$conditions
					ORDER BY	testName";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of total students of a class for a particular test

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestTotalStudents($classId, $testId) {
		$query = "SELECT
							count(a.studentId) as cnt
				 FROM		 ".TEST_MARKS_TABLE."  a, student b
				 WHERE		a.testId = $testId AND
							a.isMemberOfClass = 1 AND
							a.studentId = b.studentId AND
							b.classId = $classId
			";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching data of present students of a class for a particular test

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestPresentStudents($classId, $testId) {
		$query = "SELECT
							COUNT(a.studentId) as cnt
				  FROM		 ".TEST_MARKS_TABLE."  a, student b
				  WHERE		a.testId = $testId AND
							a.isMemberOfClass = 1 AND
							a.isPresent = 1 AND
							a.studentId = b.studentId AND
							b.classId = $classId
			";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching  range wise marks

	// Author :Ajinder Singh
	// Created on : 27-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getClassWiseConsolidatedMarks($classId, $testId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT
						a.rangeLabel, (
										SELECT
												COUNT( * ) FROM student
										WHERE	classId = $classId
										AND		studentId IN (
																SELECT		c.studentId
																FROM		 ".TEST_MARKS_TABLE."  c, student d
																WHERE		c.testId = $testId AND
																			c.studentId = d.studentId AND
																			d.classId = $classId AND
																			c.isMemberOfClass = 1 AND
																			c.isPresent = 1
																GROUP BY	studentId
																HAVING (
																			SUM( marksScored ) / SUM( maxMarks ) *100 BETWEEN a.rangeFrom AND a.rangeTo
																		)
																)
									 ) AS cnt
					FROM range_levels a where a.instituteId = $instituteId ORDER BY a.rangeTo DESC";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching  student and class details on base of roll number

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getStudentIdClass($rollNo) {
		$query = "SELECT
							a.classId,
							a.studentId,
							a.firstName,
							a.lastName,
							b.className
				  FROM		student a, class b
				  WHERE		a.rollNo = '".$rollNo."' AND
							a.classId = b.classId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for subject types for which subjects have been used in tests in test transferred marks table

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestSubjectTypes($classId) {
		$query = "SELECT
							DISTINCT(b.subjectTypeId) AS subjectTypeId,
							c.subjectTypeName
				  FROM      ".TEST_TRANSFERRED_MARKS_TABLE." a, subject  b, subject_type c
				  WHERE     a.classId = $classId AND
							a.subjectId = b.subjectId AND
							b.subjectTypeId = c.subjectTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for subject types for which subjects have been used in tests in test transferred marks table

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestTypes($classId, $subjectList) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT
							DISTINCT(a.testTypeId) AS testTypeId,
							c.testTypeName,
							c.evaluationCriteriaId
				  FROM      ".TEST_TRANSFERRED_MARKS_TABLE." a, subject b, test_type c
				  WHERE     a.subjectId = b.subjectId AND
							a.subjectId IN ($subjectList) AND
							a.classId = $classId AND
							a.testTypeId = c.testTypeId AND
							c.instituteId = $instituteId
				  ORDER BY  testTypeId, b.subjectName";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for subjects under subject type for which marks have been
	//transferred and subject belong to specified class

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getSubjectTypeSubjects($subjectTypeId, $classId) {
		$query = "SELECT
							DISTINCT(a.subjectId) AS subjectId,
							b.subjectCode
				  FROM		".TEST_TRANSFERRED_MARKS_TABLE." a, subject b
				  WHERE		a.classId = $classId AND
							a.subjectId = b.subjectId AND
							b.subjectTypeId = $subjectTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching attendance marks for a student and for a subject and for that particular class

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getAttendanceMarks($classId, $studentId, $subjectId) {
		$query = "	SELECT
							FORMAT(SUM(IF(a.isMemberOfClass=0,0, IF(a.attendanceType =2, (b.attendanceCodePercentage /100), a.lectureAttended )) ) , 1 ) AS lectureAttended,
							SUM(IF(a.isMemberOfClass=0,0, a.lectureDelivered)) AS lectureDelivered
					FROM	 ".ATTENDANCE_TABLE."  a, attendance_code b
					WHERE	a.classId = $classId AND
							a.studentId = $studentId AND
							a.subjectId = $subjectId AND
							a.attendanceCodeId = b.attendanceCodeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching marks for a student and for a subject and for that particular class and that particualr test

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getMarks($classId, $studentId, $subjectId, $testId) {
		$query = "SELECT
							a.marksScored
				 FROM		 ".TEST_MARKS_TABLE." a, ".TEST_TABLE." b
				 WHERE		a.subjectId = $subjectId AND
							a.studentId = $studentId AND
							b.classId = $classId AND
							b.testId = $testId AND
							a.subjectId = b.subjectId AND
							a.testId = b.testId";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching marks transferred for a student and for a subject and for that particular class and that particualr test type

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTotalMarks($classId, $subjectId, $studentId, $testTypeId) {
		$query = "SELECT
							marksScored
				  FROM		".TEST_TRANSFERRED_MARKS_TABLE."
				  WHERE		classId = $classId AND
							subjectId =  $subjectId AND
							studentId = $studentId AND
							testTypeId = $testTypeId";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching tests for specific test types and specific subjects for that particular class and particular student

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTests($testTypeList = 0, $subjectList = 0, $classId, $studentId) {
		$query = "SELECT
								DISTINCT (a.testId) as testId,
								CONCAT(a.testAbbr,'-',testIndex) AS testName
				  FROM			".TEST_TABLE." a
				  LEFT JOIN		 ".TEST_MARKS_TABLE." b
				  ON			(
									a.testId = b.testId AND
									a.subjectId = b.subjectId
								)
				  WHERE			a.classId = $classId AND
								a.testTypeId in ($testTypeList) AND
								a.subjectId in ($subjectList) ";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for counting tests for a particular test type and for a list of subjects

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function countTestTypeSubjectListTest($testTypeId, $subjectList, $classId) {
		$query = "SELECT
							COUNT(*) AS cnt
				  FROM		".TEST_TABLE."
				  WHERE		classId = $classId AND
							subjectId IN($subjectList) AND
							testTypeId = $testTypeId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching different classes from test

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getTestClasses($conditions = '') {
		$query = "SELECT
							DISTINCT(a.classId) AS classId
				  FROM		".TEST_TABLE." a, class b
				  WHERE		a.classId = b.classId $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching tests taken for a particular class, subject and group

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getClassSubjectGroupTests($classId, $subjectId, $groupId) {
		$query = "SELECT
							a.testId,
							CONCAT(b.testTypeName,'-',a.testIndex) AS testName,
							SUBSTRING_INDEX(c.className,'-',-3) AS className,
							d.subjectCode,
							e.employeeCode,
							e.employeeName
				  FROM		".TEST_TABLE." a, test_type_category b, class c, subject d, employee e
				  WHERE		a.classId = $classId AND
							a.subjectId = $subjectId AND
							a.groupId = $groupId AND
							a.testTypeCategoryId = b.testTypeCategoryId AND
							a.classId = c.classId AND
							a.employeeId = e.employeeId AND
							a.subjectId = d.subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching subjects which are taught to a particular class

	// Author :Ajinder Singh
	// Created on : 29-Aug-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getClassTeachingSubjectList($classId) {
		global $sessionHandler;
		$query = "SELECT
							DISTINCT(a.subjectId) AS subjectId,
							a.subjectCode,
							a.subjectName
				  FROM		subject a, subject_to_class b
				  WHERE		a.subjectId = b.subjectId
				  AND		b.classId = $classId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching students matching conditions

	// Author :Ajinder Singh
	// Created on : 13-Sep-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getAllDetails($conditions='', $order, $limit) {
		global $sessionHandler;
		$query = "SELECT
							CONCAT(a.firstName,' ', a.lastName) as studentName,
							a.rollNo,
							CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) as programme,
							f.periodName
				  FROM		student a, class b, university c, degree d,  branch e, study_period f
				  WHERE		a.classId = b.classId
				  AND		b.universityId = c.universityId
				  AND		b.degreeId = d.degreeId
				  AND		b.branchId = e.branchId
				  AND		b.studyPeriodId = f.studyPeriodId
				  AND		b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
				  AND		b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
							$conditions
				  ORDER BY	$order $limit";
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


     //----------------------------------------------------------------------------------------------------
    //function created for fetching subjects and subject Types
    // Author :Parveen Sharma
    // Created on : 04-12-08
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getAllSubjectAndSubjectTypes($conditions='', $filter='', $groupBy='', $orderBy='',$sortBy='ASC') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query ="SELECT
                           $filter
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
                           $filter
                 FROM
                           group_type gt, subject_type st, `subject` su,subject_to_class sc,
                           student_optional_subject gb LEFT JOIN  ".TIME_TABLE_TABLE."  tt ON gb.subjectId = tt.subjectId
                                AND tt.toDate IS NULL AND tt.sessionId=".$sessionId." AND tt.instituteId=".$instituteId."
                           LEFT JOIN `class` c ON gb.classId = c.classId
                           LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                 WHERE
                           su.subjectId=gb.subjectId
                           AND sc.classId = c.classId
						   AND sc.subjectId = su.subjectId
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
 UNION
                 SELECT
                           $filter
                 FROM
                           group_type gt, subject_type st, `subject` su,subject_to_class sc,
                           optional_subject_to_class gb LEFT JOIN  ".TIME_TABLE_TABLE."  tt ON gb.subjectId = tt.subjectId
                                AND tt.toDate IS NULL AND tt.sessionId=".$sessionId." AND tt.instituteId=".$instituteId."
                           LEFT JOIN `class` c ON gb.classId = c.classId
                           LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                 WHERE
                           su.subjectId=gb.subjectId
                           AND sc.classId = c.classId
                           AND gb.subjectId = su.subjectId
                           AND gb.parentOfSubjectId = sc.subjectId
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

    //----------------------------------------------------------------------------------------------------
    //function created for fetching subjects for whom attendane has been taken

    // Author :Parveen Sharma
    // Created on : 04-12-08
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getSubjectAttendanceClasses($degreeId) {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        if($degreeId!='' && $degreeId!='all') {
           $cond = " AND  c.classId = ".$degreeId;
        }

        $query ="SELECT
                        DISTINCT  sub.subjectId, sub.subjectCode AS subjectName, sub.hasAttendance, sub.hasMarks
                 FROM
                        `subject` sub, `subject_to_class` subtocls,`subject_type` st, class c
                 WHERE
                        sub.subjectId = subtocls.subjectId AND
                        subtocls.classId = c.classId AND
                        sub.hasAttendance = 1 AND
                        c.isActive=1  AND
                        c.instituteId = $instituteId  AND
                        c.sessionId = $sessionId
                 $cond ";

     /* $cond='';
        if($degreeId!='' && $degreeId!='all') {
           $cond = " AND  c.classId = ".$degreeId;
        }
        $query ="SELECT
                        DISTINCT d.subjectId, b.subjectCode AS subjectName, b.hasAttendance, b.hasMarks
                 FROM
                        subject b, class c, ".ATTENDANCE_TABLE." d
                 WHERE
                        d.classId = c.classId AND
                        b.hasAttendance = 1 AND
                        b.subjectId = d.subjectId AND
                        c.isActive=1  AND
                        c.instituteId = $instituteId  AND
                        c.sessionId = $sessionId
                        $cond ";
      */
       /* $query = "
                SELECT      DISTINCT(a.subjectId),
                            b.subjectCode AS subjectName, b.hasAttendance, b.hasMarks
                FROM        subject_to_class a, subject b, class c,  ".ATTENDANCE_TABLE."  d
                WHERE       a.subjectId = b.subjectId
                AND         a.classId = c.classId
                AND         d.classId  = c.classId
                AND         b.subjectId = d.subjectId
                AND         c.isActive=1
                AND         c.instituteId = $instituteId
                AND         c.sessionId = $sessionId
                $cond
                ORDER BY    subjectName "; */

        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }


    //----------------------------------------------------------------------------------------------------
    //function created for fetching groups allocated to a subject

    // Author :Parveen Sharma
    // Created on : 12-04-08
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getSubjectGroups($degreeId='',$subjectId='') {

         global $sessionHandler;

         $conditions = "";

         if($degreeId !='' && $degreeId !='all') {
            $conditions .= " AND att.classId = ".$degreeId;
         }

         if($subjectId !=''  && $subjectId !='all') {
            $conditions .= " AND att.subjectId = ".$subjectId;
         }

         $query = "SELECT
                            DISTINCT g.groupId, g.groupName
                   FROM
                            ".ATTENDANCE_TABLE." att, `group` g, class c, subject s
                   WHERE
                            att.subjectId = s.subjectId
                            AND s.hasAttendance = 1
                            AND att.groupId=g.groupId
                            AND att.classId = c.classId
                            AND g.classId = c.classId
                            AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------------------
// Function gets records for Percentage Attendance wise report
//
// Author :Arvind Singh Rawat
// Created on : 23-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getStudentPercentageAttendanceReport($classCondition='',$condition='',$groupBy='',$orderBy='studentName',$limit='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $fieldName1='';
        $fieldName2='';
        if($groupBy!='') {
          $fieldName1 = ", tt.groupId, tt.groupName, tt.groupTypeId";
          $fieldName2 = ", grp.groupId, grp.groupName, grp.groupTypeId";
        }

        $query="SELECT
                       DISTINCT tt.classId,tt.subjectId, tt.studentId,
                       tt.studentName, tt.universityRollNo, tt.rollNo,
                       tt.subjectName, tt.subjectCode,tt.subjectTypeId,
                       ((tt.attended+tt.leavesTaken)/tt.delivered*100) AS per,
                       tt.attended,tt.delivered, tt.leavesTaken $fieldName1
                FROM
                    (SELECT
                           CONCAT(IFNULL(scs.firstName,''),' ',IFNULL(scs.lastName,'')) AS studentName,
                           IFNULL(scs.universityRollNo,'---') AS universityRollNo, IFNULL(scs.rollNo,'---') AS rollNo,
                           att.classId, att.subjectId,att.studentId,
                           sub.subjectName, sub.subjectCode,  sub.subjectTypeId,
                           IFNULL(SUM(IF(att.isMemberOfClass=0,0,IF(att.attendanceType =2,(ac.attendanceCodePercentage/100),att.lectureAttended))),0) AS attended,
                           IFNULL(SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered)),0) AS delivered,
                           IFNULL((SELECT
                                         SUM(IF(att1.isMemberOfClass=0,0,IF(att1.attendanceType=2,1,0))) AS dutyLeave
                                   FROM
                                          ".DUTY_LEAVE_TABLE."  dl, ".ATTENDANCE_TABLE." att1
                                         LEFT JOIN  attendance_code ac1 ON
                                         (ac1.attendanceCodeId = att1.attendanceCodeId AND ac1.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                                   WHERE
                                        att1.studentId = dl.studentId AND
                                        att1.classId = dl.classId AND
                                        att1.subjectId = dl.subjectId AND
                                        att1.groupId = dl.groupId AND
                                        att1.periodId  = dl.periodId AND
                                        att1.fromDate = dl.dutyDate AND
                                        att1.toDate = dl.dutyDate AND
                                        dl.studentId = att.studentId AND
                                        dl.classId = att.classId AND
                                        dl.subjectId = att.subjectId AND
                                        (dl.periodId = att.periodId OR att.periodId IS NULL) AND
                                        dl.rejected = ".DUTY_LEAVE_APPROVE."),0) AS leavesTaken
                           $fieldName2
                      FROM
                           group_type gt, `subject` sub, student scs, ".ATTENDANCE_TABLE." att
                           LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = $instituteId)
                           LEFT JOIN `group` grp ON att.groupId = grp.groupId
                      WHERE
                           gt.groupTypeId = grp.groupTypeId AND
                           sub.subjectId = att.subjectId AND
                           scs.studentId = att.studentId
                           $classCondition
                      GROUP BY
                           att.classId, att.studentId, att.subjectId $groupBy) AS tt
               $condition
               ORDER BY
                      $orderBy $limit";

               return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



	//-------------------------------------------------------
	//  THIS FUNCTION IS USED FOR fetching time table classes for which marks have been totalled
	//
	// Author :Ajinder Singh
	// Created on : (15.11.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	public function getMarksTotalClasses($labelId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT
									DISTINCT(a.classId),
									className
					FROM			".TOTAL_TRANSFERRED_MARKS_TABLE." a, time_table_classes b, class c
					WHERE			a.classId = b.classId
					AND			a.classId = c.classId
					AND			b.timeTableLabelId = $labelId
					AND			c.instituteId = $instituteId
					ORDER BY		c.degreeId,c.branchId,c.studyPeriodId
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}


    //----------------------------------------------------------------------------------------------------
    //function created for counting students matching conditions
    // Author :Parveen Sharma
    // Created on : 12-01-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------

    public function getStudentICardCount($conditions='') {

        global $sessionHandler;

       /* $query = "SELECT
                            COUNT(*) AS cnt
                  FROM      class b, university c, degree d,  branch e, study_period f, batch  btch, institute ins,
                            student a LEFT JOIN bus_pass  bpass  ON (a.studentId = bpass.studentId AND bpass.busPassStatus =1)
                  WHERE     a.classId = b.classId
                            AND btch.batchId = b.batchId
                            AND b.universityId = c.universityId
                            AND b.degreeId = d.degreeId
                            AND b.branchId = e.branchId
                            AND b.studyPeriodId = f.studyPeriodId
                            AND a.studentStatus = 1
                            AND b.instituteId = ins.instituteId
                            AND b.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                            AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions ";
        */
        $query = "SELECT
                          COUNT(DISTINCT a.studentId) AS cnt
                  FROM   university c, degree d, branch e, study_period f,
                         student a LEFT JOIN bus_pass  bpass  ON (a.studentId = bpass.studentId AND bpass.busPassStatus = 1)
                         LEFT JOIN student_groups sg ON a.studentId = sg.studentId AND a.classId = sg.classId
                         LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                         INNER JOIN class b ON b.classId = a.classId OR sg.classId = b.classId
                  WHERE b.universityId = c.universityId
                        AND b.degreeId = d.degreeId
                        AND b.branchId = e.branchId
                        AND b.studyPeriodId = f.studyPeriodId

                        AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
                  $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //----------------------------------------------------------------------------------------------------
    // function created for students matching conditions
    // Author :Parveen Sharma
    // Created on : 12-01-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
     public function getStudentICardDetails($conditions='', $orderBy=' rollNo', $limit='') {
        global $sessionHandler;
        $query = "SELECT
                            DISTINCT CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                            IF(IFNULL(a.rollNo,'')='','".NOT_APPLICABLE_STRING."',a.rollNo) AS rollNo, a.regNo,
                            IF(IFNULL(a.fatherName,'')='','".NOT_APPLICABLE_STRING."',a.fatherName) AS fatherName,
                            IF(INSTR(LOWER(b.className),'bridge')>0, 
                               TRIM(SUBSTRING_INDEX(b.className,'-',-3)),
                               CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode)) AS programme,
                            IF(INSTR(LOWER(b.className),'bridge')>0, 
                               TRIM(SUBSTRING_INDEX(b.className,'-',-3)),
                               CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode,' ',f.periodName)) AS className,
                            f.periodName, a.studentBloodGroup, b.className AS fullClassName,
                            a.classId as class_id,
                            a.studentId,
                            e.branchCode AS Course,
                            d.degreeName AS collegeName,
                            CONCAT(YEAR(btch.startDate),'-',YEAR(btch.endDate)) AS studentSession,
                            IF(a.studentEmail='','".NOT_APPLICABLE_STRING."',a.studentEmail) AS studentEmail,
                            IF(IFNULL(a.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',a.universityRollNo) AS universityRollNo,
                            IFNULL(a.permAddress1,'".NOT_APPLICABLE_STRING."') AS tpermAddress1,
                            IFNULL(a.permAddress2,'".NOT_APPLICABLE_STRING."') AS tpermAddress2,
                            IF(IFNULL(a.permCityId,'')='','".NOT_APPLICABLE_STRING."',(SELECT cityName from city where city.cityId=a.permCityId)) AS tpermCity,
                            IF(IFNULL(a.permStateId,'')='','".NOT_APPLICABLE_STRING."',(SELECT stateName from states where states.stateId=a.permStateId)) AS tpermState,
                            IF(IFNULL(a.permCountryId,'')='','".NOT_APPLICABLE_STRING."',(SELECT countryName from countries where countries.countryId=a.permCountryId)) AS tpermCountry,
                            IFNULL(a.permPinCode,'".NOT_APPLICABLE_STRING."') AS tpermPinCode,
                            IF(a.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = a.corrCityId)) AS corrCityId,
                            IF(a.classId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT periodName FROM study_period sp, class cls WHERE sp.studyPeriodId = cls.studyPeriodId and cls.classId = a.classId)) AS studyPeriod,
                            IF(a.studentMobileNo='','".NOT_APPLICABLE_STRING."',a.studentMobileNo) AS studentMobileNo ,
                            IF(corrAddress1 IS NULL OR corrAddress1='','', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=a.corrCityId),' ',(SELECT stateName from states where states.stateId=a.corrStateId),' ',(SELECT countryName from countries where countries.countryId=a.corrCountryId),IF(a.corrPinCode IS NULL OR a.corrPinCode='','',CONCAT('-',a.corrPinCode)))) AS corrAddress,
                            IF(permAddress1 IS NULL OR permAddress1='','', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=a.permCityId),' ',(SELECT stateName from states where states.stateId=a.permStateId),' ',(SELECT countryName from countries where countries.countryId=a.permCountryId),IF(a.permPinCode IS NULL OR a.permPinCode='','',CONCAT('-',a.permPinCode)))) AS permAddress,
                            dateOfBirth AS DOB, studentPhoto AS Photo,
                            ins.instituteId,ins.instituteCode,ins.instituteName,ins.instituteAbbr,ins.instituteLogo,
                            ins.instituteAddress1,ins.instituteAddress2, ins.employeePhone AS insPhone,
                            ins.instituteEmail,ins.instituteWebsite,
                            IF(bpass.busRouteId IS NULL,'0',(SELECT routeCode FROM bus_route broute WHERE broute.busRouteId=bpass.busRouteId)) AS routeCode,
                            IF(bpass.busStopId IS NULL,'0', (SELECT stopName  FROM bus_stop bstop   WHERE bstop.busStopId=bpass.busStopId)) AS stopName,
                            a.compExamBy, a.compExamRank, a.compExamRollNo, bpass.busRouteId,  bpass.busStopId,
                            bpass.receiptNo, bpass.validUpto, bpass.busPassStatus, bpass.busPassId,
                            b.instituteId, b.sessionId, b.classId, IFNULL(bus.busNo,'') AS busNo
                 FROM
                          batch  btch, institute ins,
                          university c, degree d, branch e, study_period f,
                          student a LEFT JOIN student_groups sg ON a.studentId = sg.studentId
                                    LEFT JOIN bus_pass  bpass  ON
                                      (a.studentId = bpass.studentId  AND bpass.classId = a.classId  AND bpass.busPassStatus = 1)
                                    LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                                    LEFT JOIN class b ON a.classId = b.classId
                                    LEFT JOIN bus ON bus.busId = bpass.busId
                 WHERE
                          btch.batchId = b.batchId
                          AND b.instituteId = ins.instituteId
                          AND b.universityId = c.universityId
                          AND b.degreeId = d.degreeId
                          AND b.branchId = e.branchId

                          AND b.studyPeriodId = f.studyPeriodId
                          AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                          AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
                  $conditions
                  GROUP BY a.studentId
                  ORDER BY $orderBy $limit ";

                /*  FROM      class b, university c, degree d,  branch e, study_period f, batch  btch, institute ins,
                            student a LEFT JOIN bus_pass  bpass  ON (a.studentId = bpass.studentId AND a.classId = bpass.classId AND bpass.busPassStatus = 1)
                  WHERE     a.classId = b.classId
                            AND btch.batchId = b.batchId
                            AND b.universityId = c.universityId
                            AND b.degreeId = d.degreeId
                            AND b.branchId = e.branchId
                            AND b.studyPeriodId = f.studyPeriodId
                            AND a.studentStatus = 1
                            AND b.instituteId = ins.instituteId
                            AND b.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                            AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                            $conditions
                  ORDER BY  $order $limit";
              */
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //----------------------------------------------------------------------------------------------------
    //function created for students bus pass count
    // Author :Parveen Sharma
    // Created on : 12-01-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------

    public function getStudentBusPassCount($conditions='') {

        global $sessionHandler;

        $query = "SELECT
                            COUNT(DISTINCT a.studentId) AS cnt
                  FROM
                           class c, student a
                           LEFT JOIN bus_pass bpass ON bpass.studentId=a.studentId AND bpass.classId=a.classId AND bpass.busPassStatus = 1
                  WHERE

                           a.classId = c.classId AND
                           c.sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND
                           c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //----------------------------------------------------------------------------------------------------
    // function created for students bus pass
    // Author :Parveen Sharma
    // Created on : 12-01-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getStudentBusPassDetails($conditions='', $order='', $limit='') {
        global $sessionHandler;

        $query = "SELECT
                           DISTINCT
                           CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) AS studentName,
                           IFNULL(a.rollNo,'') AS rollNo, IFNULL(a.regNo,'') AS regNo,
                           IFNULL(bpass.busPassId,'') AS busPassId, a.classId,
                           a.studentId , IFNULL(bpass.receiptNo,'') AS receiptNo, IFNULL(bpass.validUpto,'') AS validUpto,
                           IF(bpass.busRouteId IS NULL,'0',(SELECT routeCode FROM bus_route broute WHERE broute.busRouteId=bpass.busRouteId)) AS routeCode,
                           IF(bpass.busStopId IS NULL,'0', (SELECT stopName  FROM bus_stop bstop   WHERE bstop.busStopId=bpass.busStopId)) AS stopName,
                           bpass.busRouteId, bpass.busStopId, bpass.busPassStatus, a.studentBloodGroup, IFNULL(b.busNo,'') AS busNo
                   FROM
                           class c, student a
                           LEFT JOIN bus_pass bpass ON bpass.studentId=a.studentId AND bpass.classId=a.classId AND bpass.busPassStatus = 1
                           LEFT JOIN bus b ON bpass.busId =b.busId
                   WHERE
                           a.classId = c.classId AND
                           c.sessionId='".$sessionHandler->getSessionVariable('SessionId')."' AND
                           c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                           $conditions
                   GROUP BY
                           a.studentId
                   ORDER BY
                           $order $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }




    //----------------------------------------------------------------------------------------------------
    // function created for fetching groups for a class subject for which test is taken
    // Author :Ajinder Singh
    // Created on : 31-03-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
	public function getSubjectTestGroups($classId,$subjectId) {
        $query = "
                    SELECT
                                DISTINCT a.groupId,
                                b.groupShort,
                                b.groupName
                    FROM        ".TEST_TABLE." a, `group` b, subject s
                    WHERE        a.classId = $classId
                    AND         s.subjectId = a.subjectId
                    AND            a.subjectId = $subjectId
                    AND            a.groupId = b.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //----------------------------------------------------------------------------------------------------
    // function created for fetching groups for a class subject for which test is taken
    // Author :Ajinder Singh
    // Created on : 31-03-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
	public function getSubjectUserTestGroups($classId,$subjectId, $userId) {
        $query = "
                    SELECT
                                DISTINCT a.groupId,
                                b.groupShort,
                                b.groupName
                    FROM        ".TEST_TABLE." a, `group` b, subject s, classes_visible_to_role cvtr
                    WHERE        a.classId = $classId
                    AND         s.subjectId = a.subjectId
                    AND         a.subjectId = $subjectId
                    AND         a.groupId = b.groupId
					AND			a.classId = cvtr.classId
					AND			a.groupId = cvtr.groupId
					AND			cvtr.userId = $userId
					";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //----------------------------------------------------------------------------------------------------
    // function created for fetching groups for a class subject for which test is taken
    // Author :Ajinder Singh
    // Created on : 31-03-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
	public function getClassSubjectGroups($classId,$subjectId) {
        $query = "
                    SELECT
                                DISTINCT t.groupId
                    FROM         ".TIME_TABLE_TABLE."  t, time_table_labels ttl, `group` g, subject s
                    WHERE        t.timeTableLabelId = ttl.timeTableLabelId
                    AND         t.subjectId = s.subjectId
                    AND            ttl.isActive = 1
                    AND            t.subjectId = $subjectId
                    AND            t.groupId = g.groupId
                    AND            g.classId = $classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //----------------------------------------------------------------------------------------------------
    // function created for fetching teacher name which is teaching to a subject and a group
    // Author :Ajinder Singh
    // Created on : 31-03-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
	public function getClassSubjectGroupTeacher($subjectId,$groupId) {
		$query = "
					SELECT
								DISTINCT a.employeeId,
								b.employeeName
					FROM		 ".TIME_TABLE_TABLE."  a, employee b
					WHERE		a.employeeId = b.employeeId
					AND			a.subjectId = $subjectId
					AND			a.groupId = $groupId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

    //----------------------------------------------------------------------------------------------------
    // function created for fetching groups linked to a class
    // Author :Ajinder Singh
    // Created on : 31-03-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
	public function getClassGroups($classId) {
		$query = "
					SELECT
								DISTINCT b.groupId,
								a.groupShort,
								a.groupName
					FROM		 ".TIME_TABLE_TABLE."  b, `group` a, time_table_labels c
					WHERE		a.groupId = b.groupId
					AND			a.classId = $classId
					AND			b.toDate is null
					AND			b.timeTableLabelId = c.timeTableLabelId
					AND			c.isActive = 1";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getSubjectInternalMarksTheory($condition='') {

		$query = "SELECT subjectId,subjectCode, SUM( if( ceil(marksScored)
				BETWEEN 0
				AND 9 , 1, 0 ) ) AS 'total0', SUM( if( ceil(marksScored)
				BETWEEN 10
				AND 13 , 1, 0 ) ) AS 'total1', SUM( if( ceil(marksScored)
				BETWEEN 14
				AND 16 , 1, 0 ) ) AS 'total2', SUM( if( ceil(marksScored)
				BETWEEN 17
				AND 24 , 1, 0 ) ) AS 'total3', SUM( if( ceil(marksScored)
				BETWEEN 25
				AND 30 , 1, 0 ) ) AS 'total4', SUM( if( ceil(marksScored)
				BETWEEN 31
				AND 35 , 1, 0 ) ) AS 'total5' , SUM( if( ceil(marksScored)
				BETWEEN 36
				AND 40 , 1, 0 ) ) AS 'total6'
				FROM (
				SELECT ttm.studentId,s.subjectCode, ttm.classId, ttm.subjectId, IF(tgm.graceMarks IS NULL,sum( marksScored ),(sum( marksScored)  + tgm.graceMarks))  AS marksScored
				FROM  time_table_classes ttc, subject s,time_table_labels ttl,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
				LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON (tgm.classId = ttm.classId AND ttm.subjectId = tgm.subjectId AND ttm.studentId = tgm.studentId)
				WHERE ttm.classId = ttc.classId
				 $condition
				AND ttm.conductingAuthority
				IN ( 1, 3 )
				AND ttc.timeTableLabelId=ttl.timeTableLabelId
				AND ttl.isActive=1
				AND ttm.subjectId = s.subjectId

				GROUP BY ttm.subjectId, ttm.studentId, ttm.classId
				ORDER BY ttm.subjectId, ttm.studentId, ttm.classId


				) AS t
				GROUP BY subjectId, classId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getSubjectInternalMarksPractical($condition='') {

		$query = "SELECT subjectId,subjectCode, SUM( if( ceil(marksScored)
				BETWEEN 0
				AND 9 , 1, 0 ) ) AS 'total0', SUM( if( ceil(marksScored)
				BETWEEN 10
				AND 12 , 1, 0 ) ) AS 'total1', SUM( if( ceil(marksScored)
				BETWEEN 13
				AND 15 , 1, 0 ) ) AS 'total2', SUM( if( ceil(marksScored)
				BETWEEN 16
				AND 18 , 1, 0 ) ) AS 'total3', SUM( if( ceil(marksScored)
				BETWEEN 19
				AND 21 , 1, 0 ) ) AS 'total4', SUM( if( ceil(marksScored)
				BETWEEN 22
				AND 24 , 1, 0 ) ) AS 'total5' , SUM( if( ceil(marksScored)
				BETWEEN 25
				AND 27 , 1, 0 ) ) AS 'total6' , SUM( if( ceil(marksScored)
				BETWEEN 28
				AND 30 , 1, 0 ) ) AS 'total7'
				FROM (
				SELECT ttm.studentId,s.subjectCode, ttm.classId, ttm.subjectId, IF(tgm.graceMarks IS NULL,sum( marksScored ),(sum( marksScored)  + tgm.graceMarks))  AS marksScored
				FROM  time_table_classes ttc, subject s,time_table_labels ttl,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
				LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON (tgm.classId = ttm.classId AND ttm.subjectId = tgm.subjectId AND ttm.studentId = tgm.studentId)
				WHERE ttm.classId = ttc.classId
				 $condition
				AND ttm.conductingAuthority
				IN ( 1, 3 )
				AND ttc.timeTableLabelId=ttl.timeTableLabelId
				AND ttl.isActive=1
				AND ttm.subjectId = s.subjectId

				GROUP BY ttm.subjectId, ttm.studentId, ttm.classId
				ORDER BY ttm.subjectId, ttm.studentId, ttm.classId


				) AS t
				GROUP BY subjectId, classId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getSubjectPercentageInternalMarks($condition='') {

		$query = "SELECT subjectId,subjectCode,  SUM( if( maxMarks1 BETWEEN 0 AND 25 , 1, 0 ) ) AS 'total0',
			       SUM( if( ceil(maxMarks1) BETWEEN 26 AND 30 , 1, 0 ) ) AS 'total1',
			       SUM( if( ceil(maxMarks1) BETWEEN 31 AND 35 , 1, 0 ) ) AS 'total2',
			       SUM( if( ceil(maxMarks1) BETWEEN 36 AND 40 , 1, 0 ) ) AS 'total3',
			       SUM( if( ceil(maxMarks1) BETWEEN 41 AND 45 , 1, 0 ) ) AS 'total4',
			       SUM( if( ceil(maxMarks1) BETWEEN 46 AND 50 , 1, 0 ) ) AS 'total5',
				   SUM( if( ceil(maxMarks1) BETWEEN 51 AND 55 , 1, 0 ) ) AS 'total6',
				   SUM( if( ceil(maxMarks1) BETWEEN 56 AND 60 , 1, 0 ) ) AS 'total7',
				   SUM( if( ceil(maxMarks1) BETWEEN 61 AND 65 , 1, 0 ) ) AS 'total8',
				   SUM( if( ceil(maxMarks1) BETWEEN 66 AND 70 , 1, 0 ) ) AS 'total9',
				   SUM( if( ceil(maxMarks1) BETWEEN 71 AND 75 , 1, 0 ) ) AS 'total10',
				   SUM( if( ceil(maxMarks1) BETWEEN 76 AND 80 , 1, 0 ) ) AS 'total11',
				   SUM( if( ceil(maxMarks1) BETWEEN 81 AND 85 , 1, 0 ) ) AS 'total12',
				   SUM( if( ceil(maxMarks1) BETWEEN 86 AND 90 , 1, 0 ) ) AS 'total13',
				   SUM( if( ceil(maxMarks1) BETWEEN 91 AND 95 , 1, 0 ) ) AS 'total14',
				   SUM( if( ceil(maxMarks1) BETWEEN 96 AND 100 , 1, 0 )) AS 'total15'
				FROM (


				SELECT s.subjectCode, ttm.classId, sum( marksScored ) AS marksScored,CEIL((sum(marksScored )/sum(maxMarks)*100))  AS maxMarks1, ttm.subjectId
				FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, time_table_classes ttc, subject s,time_table_labels ttl,student_groups sg
				WHERE ttm.classId = ttc.classId
				$condition
				AND ttm.conductingAuthority
				IN ( 1, 3 )
				AND ttc.timeTableLabelId=ttl.timeTableLabelId
				AND  sg.studentId = ttm.studentId
				AND  sg.classId = ttm.classId

				AND ttm.subjectId = s.subjectId
				GROUP BY ttm.subjectId, ttm.studentId, ttm.classId
				ORDER BY ttm.subjectId, ttm.studentId, ttm.classId

				) AS t
				GROUP BY subjectId, classId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getLabelClass($labelId,$orderBy=' cls.degreeId,cls.branchId,cls.studyPeriodId') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$sepratorLen = strlen(CLASS_SEPRATOR);

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
			$query = "SELECT distinct cls.classId,cls.className
					 FROM
					 class cls,time_table_classes ttc, time_table_labels ttl, classes_visible_to_role cvtr
					 WHERE
					 cls.instituteId='".$instituteId."' AND
					 cls.sessionId='".$sessionId."' AND
					 cls.isActive IN(1,3)  AND
					 cls.classId = ttc.classId AND
					 ttc.timeTableLabelId = ttl.timeTableLabelId AND
					 cls.classId IN ($insertValue) AND
					 cvtr.classId = cls.classId AND
					 cvtr.classId = ttc.classId AND
					 ttl.timeTableLabelId =$labelId

					 ORDER BY $orderBy ASC";
			return $systemDatabaseManager->executeQuery($query,"Query: $query");
		}
		else {

			$query = "SELECT cls.classId,cls.className
					 FROM
					 class cls,time_table_classes ttc, time_table_labels ttl
					 WHERE
					 cls.instituteId='".$instituteId."' AND
					 cls.sessionId='".$sessionId."' AND
					 cls.isActive IN(1,3)  AND
					 cls.classId = ttc.classId AND
					 ttc.timeTableLabelId = ttl.timeTableLabelId AND
					 ttl.timeTableLabelId =$labelId

					 ORDER BY $orderBy ASC";
			return $systemDatabaseManager->executeQuery($query,"Query: $query");
			}
	}


	public function getLabelMarksTransferredClass($labelId, $conditions = '') {
      $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT distinct c.classId,c.className from class c, time_table_classes t, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm where c.classId = t.classId and t.timeTableLabelId = $labelId and c.classId = tm.classId $conditions ORDER BY c.degreeId, c.branchId, c.studyPeriodId";
      return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	public function getLabelMarksTransferredClassTeacher($labelId, $employeeId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "
			select distinct grp.classId, cls.className from  ".TIME_TABLE_TABLE."  tt, time_table_classes ttc, `group` grp, class cls, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm
			where tt.timeTableLabelId = $labelId
			and tt.employeeId = $employeeId
			and tt.timeTableLabelId = ttc.timeTableLabelId
			and grp.classId = ttc.classId
			and tt.groupId = grp.groupId
			and grp.classId = cls.classId
			and cls.classId = tm.classId
			ORDER BY cls.degreeId, cls.branchId, cls.studyPeriodId ";
            
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}
    
    public function getLabelMarksTransferredTeacher($labelId) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT 
                        DISTINCT emp.employeeName, emp.employeeId, emp.employeeCode
                  FROM
                         ".TIME_TABLE_TABLE."  tt, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm, employee emp
                  WHERE 
                       tt.classId = tm.classId
                       AND tt.subjectId = tm.subjectId 
                       AND tt.employeeId = emp.employeeId
                       AND tt.timeTableLabelId = '$labelId'
                       AND tt.toDate IS NULL
                       AND tt.instituteId = '$instituteId'
                  GROUP BY
                       emp.employeeId      
                  ORDER BY 
                        emp.employeeName";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
     public function getLabelMarksTransferredSubject($condition) {
    
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT 
                        DISTINCT sub.subjectId, sub.subjectName, sub.subjectCode
                  FROM
                         ".TIME_TABLE_TABLE."  tt, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm, subject sub
                  WHERE 
                       tt.classId = tm.classId
                       AND tt.subjectId = tm.subjectId 
                       AND tt.subjectId = sub.subjectId
                       AND tt.toDate IS NULL
                       AND tt.instituteId = '$instituteId'
                       $condition    
                  GROUP BY
                       sub.subjectId      
                  ORDER BY 
                       sub.subjectCode";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getFinalTransferredStudent($condition='',$orderBy='rollNo',$limit='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT
                        tt.studentId, tt.rollNo, tt.universityRollNo, tt.studentName
                  FROM 
                      (SELECT
                            DISTINCT sg.studentId, sg.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                    IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                                    IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo
                      FROM
                            class c,  ".TIME_TABLE_TABLE."  tt, student_groups sg, student s, subject_to_class stc
                      WHERE
                            tt.groupId = sg.groupId        AND
                            tt.subjectId = stc.subjectId   AND
                            tt.instituteId = c.instituteId AND
                            tt.sessionId = c.sessionId     AND
                            sg.studentId = s.studentId     AND
                            sg.classId =  c.classId        AND
                            stc.classId = c.classId        AND 
                            tt.instituteId = '$instituteId'   
                            $condition                  
                      UNION
                      SELECT
                            DISTINCT s.studentId, ss.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                            IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo
                      FROM
                            class c,  ".TIME_TABLE_TABLE."  tt, student s, student_optional_subject ss
                      WHERE
                            ss.groupId = tt.groupId        AND
                            ss.subjectId = tt.subjectId    AND
                            tt.instituteId = c.instituteId AND
                            tt.sessionId = c.sessionId     AND
                            ss.studentId = s.studentId     AND
                            ss.classId =  c.classId       AND 
                            tt.instituteId = '$instituteId' 
                          $condition) AS tt
                  ORDER BY 
                       $orderBy $limit";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
     public function getFinalTransferredClass($condition) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT 
                        DISTINCT c.classId, c.className
                  FROM
                         ".TIME_TABLE_TABLE."  tt, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm, class c
                  WHERE 
                       tt.classId = tm.classId
                       AND tt.subjectId = tm.subjectId 
                       AND tt.classId = c.classId
                       AND tt.toDate IS NULL
                       AND tt.instituteId = '$instituteId'
                  $condition    
                  GROUP BY
                       c.classId      
                  ORDER BY 
                       c.className";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
     public function getFinalTransferredGroup($condition) {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query = "SELECT 
                        DISTINCT grp.groupId, grp.groupName, grp.groupShort
                  FROM
                         ".TIME_TABLE_TABLE."  tt, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm, `group` grp
                  WHERE 
                       tt.classId = tm.classId
                       AND tt.subjectId = tm.subjectId 
                       AND tt.groupId = grp.groupId
                       AND tt.classId = grp.classId
                       AND tt.toDate IS NULL
                       AND tt.instituteId = '$instituteId'
                  $condition    
                  GROUP BY
                       grp.groupId     
                  ORDER BY 
                       grp.groupName";
                        
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	public function getLabelMarksTransferredClassRole($labelId, $userId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = " SELECT DISTINCT(cvtr.classId), cls.className FROM classes_visible_to_role cvtr, class cls WHERE cvtr.userId = $userId AND cvtr.classId IN (SELECT DISTINCT classId FROM ".TOTAL_TRANSFERRED_MARKS_TABLE.") AND cvtr.classId = cls.classId AND cvtr.classId IN (SELECT grp.classId FROM `group` grp,  ".TIME_TABLE_TABLE."  tt WHERE tt.timeTableLabelId = $labelId AND tt.groupId = grp.groupId) ORDER BY cls.degreeId, cls.branchId, cls.studyPeriodId";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


	public function getLabelClassTeacher($labelId,$employeeId,$orderBy=' ttc.timeTableLabelId') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
         $query = "
			select distinct grp.classId, cls.className from  ".TIME_TABLE_TABLE."  tt, time_table_classes ttc, `group` grp, class cls
			where tt.timeTableLabelId = $labelId
			and tt.employeeId = $employeeId
			and tt.timeTableLabelId = ttc.timeTableLabelId
            and tt.instituteId = '$instituteId'
			and grp.classId = ttc.classId
			and tt.groupId = grp.groupId
			and grp.classId = cls.classId
			";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getSubjectTypeClass($classId,$subjectTypeId='') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		if($subjectTypeId){

			$condition .=" AND a.subjectTypeId=$subjectTypeId";
		}
		$query = "SELECT a.subjectId, a.subjectCode,a.subjectName

		FROM subject a, subject_to_class b ,subject_type st

		WHERE a.subjectId = b.subjectId AND a.subjectTypeId = st.subjectTypeId AND b.classId IN ($classId) $condition";

         /*$query = "SELECT sub.subjectId,sub.subjectCode
				 FROM
				 class cls,subject sub,subject_type st
				 WHERE
				 cls.instituteId='".$instituteId."' AND
				 cls.sessionId='".$sessionId."' AND
				 sub.subjectTypeId = st.subjectTypeId AND
				 st.universityId = cls.universityId AND
				 cls.classId = $classId AND
				 st.subjectTypeId=$subjectTypeId

				 ORDER BY sub.subjectCode ";*/
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getSubjectTypeGroup($classId,$subjectTypeId) {

		$query = "SELECT
				 gr.groupId,gr.groupName

				 FROM
				 `subject_type` st,`group_type` gt,`group` gr

				 WHERE
				 gt.groupTypeCode=st.subjectTypeCode AND
				 gt.groupTypeId=gr.groupTypeId AND
				 st.subjectTypeId=$subjectTypeId AND
				 gr.classId=$classId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for total test type
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getCountTestTypeGroup($condition,$orderBy=' sub.subjectCode,tt.groupId') {

		global $sessionHandler;

		$query = "SELECT

			 COUNT(*) as totalRecords, tt.subjectId,tt.testTypeCategoryId,ttc1.testTypeName,sub.subjectCode,tt.groupId,gr.groupShort

			 FROM
			 `".TEST_TABLE."` tt,test_type_category ttc1, time_table_classes ttc,subject sub,`group` gr ,`subject_type` st,`group_type` gt

			 WHERE
			 tt.testTypeCategoryId = ttc1.testTypeCategoryId AND
			 ttc.classId = tt.classId AND
			 tt.subjectId = sub.subjectId AND
			 sub.subjectTypeId =  st.subjectTypeId AND
			 st.subjectTypeCode = gt.groupTypeCode AND
			 gr.groupTypeId = gt.groupTypeId  AND

			 tt.groupId = gr.groupId AND
			 instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." AND
			 sessionId = ".$sessionHandler->getSessionVariable('SessionId')."

			 $condition



			 GROUP BY
			 tt.subjectId,ttc1.testTypeCategoryId,tt.groupId

			 ORDER BY  $orderBy";





        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for total test type
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getTestTypeList($condition,$orderBy=' tt.testDate DESC') {

		global $sessionHandler;

		$query = "SELECT
				tt.testTopic,tt.testAbbr,tt.testIndex,tt.maxMarks,tt.testDate,sub.subjectCode,emp.employeeName,emp.employeeCode,sub.subjectCode

				FROM

				`".TEST_TABLE."` tt,`subject` sub,`employee` emp

				WHERE
				tt.subjectId=sub.subjectId AND
				tt.employeeId = emp.employeeId
				$condition

				 ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getAllTransferredDetails($condition='',$lowerValue='',$upperValue='',$orderBy) {



	$query = "SELECT CONCAT(stu.firstName,' ',stu.lastName) as studentName,stu.rollNo,stu.universityRollNo,s.subjectCode, ttm.classId, IF(tgm.graceMarks IS NULL,ceil(sum( marksScored )),ceil((sum( marksScored)  + tgm.graceMarks))) AS tmarksScored, ttm.subjectId
	FROM  time_table_classes ttc, subject s,student stu,time_table_labels ttl,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
	LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON (tgm.classId = ttm.classId AND ttm.subjectId = tgm.subjectId AND ttm.studentId = tgm.studentId)
	WHERE ttm.classId = ttc.classId

	 AND stu.studentId = ttm.studentId

	AND ttm.conductingAuthority
	IN ( 1, 3 )

	AND ttc.timeTableLabelId=ttl.timeTableLabelId
	AND ttl.isActive=1
	AND ttm.subjectId = s.subjectId
	$condition
	GROUP BY ttm.subjectId, ttm.studentId, ttm.classId
	having   tmarksScored between $lowerValue and $upperValue
	ORDER BY $orderBy

		";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getAllTeacherWiseDetails($condition='',$lowerValue='',$upperValue='',$orderBy) {



	$query = "SELECT DISTINCT(sg.studentId),CONCAT(stu.firstName,' ',stu.lastName) as studentName,stu.rollNo,stu.universityRollNo,s.subjectCode, ttm.classId, sum( marksScored ) AS marksScored,CEIL((sum(marksScored )/sum(maxMarks)*100))  AS maxMarks1, ttm.subjectId
				FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, time_table_classes ttc, subject s,time_table_labels ttl,student_groups sg,student stu
				WHERE ttm.classId = ttc.classId
				AND sg.studentId = stu.studentId
				AND ttm.conductingAuthority
				IN ( 1, 3 )
				AND ttc.timeTableLabelId=ttl.timeTableLabelId
				AND  sg.studentId = ttm.studentId
				AND  sg.classId = ttm.classId
				AND ttm.subjectId = s.subjectId
				$condition
				GROUP BY ttm.subjectId, ttm.studentId, ttm.classId
				having   maxMarks1 between $lowerValue and  $upperValue
				ORDER BY $orderBy




		";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getConsolidatedAttendanceDetails($conditions,$conditions2,$sortField=' universityRollNo',$sortOrderBy=' ASC', $group='') {

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query = "SELECT
				scs.studentId,scs.universityRollNo,CONCAT(scs.firstName,' ',scs.lastName) AS studentName,sub.subjectId,sub.subjectCode, scs.rollNo,ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS attended,SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,sg.groupId,sub.subjectTypeId

			 FROM
				student scs,class c,subject sub,student_groups sg,".ATTENDANCE_TABLE." att

			 LEFT JOIN attendance_code ac  ON   (ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = $instituteId)

			 WHERE
				att.studentId = scs.studentId AND
				c.classId=att.classId AND
				sg.groupId= att.groupId AND
				sg.studentId = att.studentId AND
				sub.subjectId = att.subjectId AND
				c.sessionId=$sessionId  AND
				c.instituteId=$instituteId

				$conditions


			GROUP BY
				att.subjectId, att.studentId, att.classId

			$conditions2
			Order by $sortField $sortOrderBy,scs.studentId,att.subjectId
			 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getConsolidatedMarksDetails($conditions,$conditions2,$sortField=' universityRollNo',$sortOrderBy=' ASC',$includeGrace='') {

	        global $sessionHandler;
	        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
	        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
            
            $includeField=', hh.marksScored';
            $includeCondition = '';
            if($includeGrace!='') {
               $includeCondition = ", IFNULL((SELECT
                                             graceMarks
                                         FROM
                                             ".TEST_GRACE_MARKS_TABLE."
                                         WHERE
                                            classId = ttm.classId AND subjectId = ttm.subjectId AND 
                                            studentId = ttm.studentId),'0') AS graceMarks";
               $includeField= ", (IFNULL(hh.marksScored,0)+IFNULL(hh.graceMarks,0)) AS marksScored";
            }

	        $query = "SELECT
                          DISTINCT hh.studentId, hh.studentName, hh.rollNo, hh.universityRollNo,
                          hh.maxMarks, hh.subjectCode, hh.subjectId,hh.subjectTypeId,
                          hh.internalTotalMarks, hh.externalTotalMarks, hh.grade $includeField
                          
                      FROM
                      (SELECT        
                            DISTINCT s.studentId,CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            s.rollNo,s.universityRollNo, CEIL(SUM(ttm.maxMarks)) AS maxMarks,
                            SUM(ttm.marksScored) AS marksScored,sub.subjectCode,ttm.subjectId,sub.subjectTypeId,
                            IFNULL(sot.internalTotalMarks,'') as internalTotalMarks,
                            IFNULL(sot.externalTotalMarks,'') as externalTotalMarks, 
                            IFNULL((SELECT
			                             CONCAT(grd.gradeLabel,'%^%',grd.gradePoints)
 	                                FROM
			                            grades grd, grades_set grdset
	                                WHERE
		                                ttm.gradeId=grd.gradeId
		                                AND grd.gradeSetId=grdset.gradeSetId
		                                AND grdset.isActive=1),'') AS grade
                            $includeCondition
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
                      UNION
                      SELECT
                            DISTINCT s.studentId,CONCAT(s.firstName,' ',s.lastName) AS studentName,s.rollNo,s.universityRollNo,
                            CEIL(SUM(DISTINCT ttm.maxMarks)) AS maxMarks,
                            (SUM(DISTINCT ttm.marksScored)) AS marksScored,sub.subjectCode,ttm.subjectId,sub.subjectTypeId,
                            IFNULL(sot.internalTotalMarks,'') as internalTotalMarks,
                            IFNULL(sot.externalTotalMarks,'') as externalTotalMarks, 
                            IFNULL((SELECT
                                         CONCAT(grd.gradeLabel,'%^%',grd.gradePoints)
                                   FROM
                                        grades grd,grades_set grdset
                                  WHERE
                                       ttm.gradeId=grd.gradeId
                                       and grd.gradeSetId=grdset.gradeSetId
                                       and grdset.isActive=1),'') AS grade
                            $includeCondition
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
                      UNION
                      SELECT
                            DISTINCT s.studentId,CONCAT(s.firstName,' ',s.lastName) AS studentName,s.rollNo,s.universityRollNo,
                            CEIL(SUM(DISTINCT ttm.maxMarks)) AS maxMarks,
                            (SUM(DISTINCT ttm.marksScored)) AS marksScored,sub.subjectCode,ttm.subjectId,sub.subjectTypeId,
                            IFNULL(sot.internalTotalMarks,'') as internalTotalMarks,
                            IFNULL(sot.externalTotalMarks,'') as externalTotalMarks, 
                            IFNULL((SELECT
                                         CONCAT(grd.gradeLabel,'%^%',grd.gradePoints)
                                   FROM
                                        grades grd,grades_set grdset
                                  WHERE
                                       ttm.gradeId=grd.gradeId
                                       and grd.gradeSetId=grdset.gradeSetId
                                       and grdset.isActive=1),'') AS grade
                            $includeCondition
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
                      GROUP BY ttm.classId, ttm.subjectId,ttm.studentId, sg.groupId) hh
                      $conditions2                                                                                     
		              ORDER BY 
                            $sortField $sortOrderBy, studentId, subjectTypeId, subjectCode ";
                    
             return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getTotalConsolidatedMarksDetails($conditions,$conditions2,$sortField=' universityRollNo',$sortOrderBy=' ASC') {

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query = "SELECT
					 SUM(tt.maxMarks) AS maxMarks, SUM(tt.marksScored) AS marksScored, tt.subjectCode,tt.subjectId,tt.subjectTypeId
			FROM
			(SELECT
				   DISTINCT s.studentId,CONCAT(s.firstName,' ',s.lastName) AS studentName,s.rollNo,s.universityRollNo,
				   ceil(sum(ttm.maxMarks)) AS maxMarks,
				   ceil(sum(ttm.marksScored)) AS marksScored,sub.subjectCode,sub.subjectId,sub.subjectTypeId

			FROM
				   student_groups sg,class c,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s,subject sub
			WHERE
				   s.studentId=sg.studentId
				   and sg.classId=c.classId
				   and sub.subjectId = ttm.subjectId
				   and ttm.classId=sg.classId
				   and ttm.studentId=s.studentId
				   $conditions
			 group by ttm.subjectId,ttm.studentId, sg.groupId
			$conditions2
			 Order by $sortField $sortOrderBy,s.studentId,ttm.subjectId) AS tt
		 GROUP BY
			tt.subjectTypeId, tt.subjectId, tt.subjectCode
		ORDER BY
			subjectTypeId,  subjectCode,  subjectId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getConsolidatedTeacherDetails($conditions) {

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query = "SELECT
			 DISTINCT s.studentId,
			 sub.subjectId,  sum(ttm.maxMarks) AS maxMarks,sum(ttm.marksScored) AS marksScored ,(sum(marksScored)/sum(maxMarks))*100,c.className,sub.subjectCode,sub.subjectName
			,

            (SELECT divisionName FROM division_class_detail dcd

                        WHERE highMarksValue > ceil((sum(marksScored)/sum(maxMarks))*100) AND lowMarksValue <=ceil((sum(marksScored)/sum(maxMarks))*100)) AS divName


			 FROM
			 student_groups sg,class c,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s,subject sub,division_class_detail dcd

			 WHERE
			 s.studentId=sg.studentId
			 AND sg.classId=c.classId
			 AND sub.subjectId = ttm.subjectId
			 AND ttm.classId=sg.classId
			 AND ttm.studentId=s.studentId

			 AND ceil((marksScored/maxMarks)*100) Between lowMarksValue   AND highMarksValue


			 $conditions

		 group by ttm.subjectId,ttm.studentId, sg.groupId

		 Order by divName



	 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getConsolidatedStudents($conditions) {

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query ="SELECT
				scs.studentId,CONCAT(scs.firstName,' ',scs.lastName) AS studentName, scs.rollNo , scs.universityRegNo
			 FROM
				student scs,class c,subject sub,student_groups sg,".ATTENDANCE_TABLE." att

			 LEFT  JOIN attendance_code ac  ON   (ac.attendanceCodeId = att.attendanceCodeId  and ac.instituteId = $instituteId)

			 WHERE
				att.studentId = scs.studentId AND
				c.classId=att.classId AND
				sg.groupId= att.groupId AND
				sg.studentId = att.studentId AND
				sub.subjectId = att.subjectId AND
				c.sessionId=$sessionId  AND
				c.instituteId=$instituteId
				$conditions

			GROUP BY
				 att.studentId

			ORDER BY
			   scs.rollNo";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for transferred marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getTeachers($conditions) {

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');

	$query = "SELECT
				DISTINCT tt.employeeId,emp.employeeName

			 FROM
				`employee` emp, ".TIME_TABLE_TABLE."  tt



			 WHERE
				emp.employeeId = tt.employeeId AND
				tt.sessionId=$sessionId  AND
				tt.instituteId=$instituteId
				AND `toDate` IS NULL
				$conditions


			GROUP BY
				tt.employeeId

			$conditions2
			ORDER BY
				emp.employeeName ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//----------------------------------------------------------------------------------------------------
//function created for fetching subjects for class wise
// Author :Parveen Sharma
// Created on : 28-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function classwiseSubjects($condition='',$orderBy=' sub.subjectCode') {

        $query = "SELECT
                        sub.subjectName, sub.subjectCode, sub.subjectId, st.subjectTypeName
                  FROM
                        `subject_type` st, `subject` sub LEFT JOIN `subject_to_class` stc ON  sub.subjectId = stc.subjectId
                  WHERE
                        st.subjectTypeId = sub.subjectTypeId
                        $condition
                  ORDER BY
                        $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

	public function getRanges() {
		$query = "SELECT lowMarksValue, highMarksValue from division_class_detail order by lowMarksValue";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getRangeStudentCount($studentIdList2, $classId, $subjectId, $lowMarksValue, $highMarksValue) {
		$query = "select count(t.studentId) as studentCount from (select studentId, sum(marksScored) as cnt  from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId IN ($studentIdList2) and conductingAuthority in (1,3) group by studentId having cnt between '$lowMarksValue' and '$highMarksValue') as t";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getRangeStudentCountWithGrace($studentIdList2, $classId, $subjectId, $lowMarksValue, $highMarksValue) {
		$query = "select count(t.studentId) as studentCount from (select a.studentId, sum(a.marksScored)+ifnull(b.graceMarks,0) as cnt from ".TOTAL_TRANSFERRED_MARKS_TABLE."  a left join ".TEST_GRACE_MARKS_TABLE." b on (a.classId = b.classId and a.subjectId = b.subjectId and a.studentId = b.studentId) where a.classId = $classId and a.subjectId = $subjectId and a.studentId IN ($studentIdList2) and a.conductingAuthority in (1,3) group by a.studentId having cnt between '$lowMarksValue' and '$highMarksValue') as t";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    public function getRangeStudentCountWithGraceNEW($studentIdList2, $classId, $subjectId, $lowMarksValue, $highMarksValue,$isGrace='1') {
        
       $lowMarksValue =  trim($lowMarksValue);
       $highMarksValue =  trim($highMarksValue);
       
       if($isGrace==1) {
         $fieldName = "perWithGrace "; 
       }
       else {
         $fieldName = "perWithoutGrace "; 
       }
       $query = "SELECT 
                        count(t.studentId) as studentCount 
                 FROM 
                        (SELECT 
                                a.studentId, SUM(a.marksScored)+IFNULL(b.graceMarks,0) as cnt, 
                                SUM(a.marksScored) AS cnt1, SUM(a.maxMarks) AS maxMarks,
                                IF(IFNULL(SUM(a.maxMarks),0)=0,0,
                                    ROUND((((SUM(a.marksScored)+IFNULL(b.graceMarks,0))/SUM(a.maxMarks))*100.0),0)) AS perWithGrace,
                                IF(IFNULL(SUM(a.maxMarks),0)=0,0,
                                    ROUND((((SUM(a.marksScored))/SUM(a.maxMarks))*100.0),0)) AS perWithoutGrace
                         FROM 
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." a LEFT JOIN ".TEST_GRACE_MARKS_TABLE." b on 
                                (a.classId = b.classId and a.subjectId = b.subjectId and a.studentId = b.studentId) 
                         WHERE 
                                a.classId = $classId AND a.subjectId = $subjectId AND a.studentId IN ($studentIdList2) AND 
                                a.conductingAuthority in (1,3) 
                         GROUP BY 
                                a.studentId 
                         HAVING 
                                $fieldName >= '$lowMarksValue' AND $fieldName <= '$highMarksValue' ) as t";
                                
                                
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	public function getStudentCountWithGrace($studentIdList2, $classId, $subjectId, $lowMarksValue, $highMarksValue) {
		$query = "select count(t.studentId) as studentCount from (select ttm.studentId, IF(tgm.graceMarks IS NULL,sum( marksScored ),(sum( marksScored)  + tgm.graceMarks)) as cnt  from ".TOTAL_TRANSFERRED_MARKS_TABLE."  ttm
				LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON (tgm.classId = ttm.classId AND ttm.subjectId = tgm.subjectId AND ttm.studentId = tgm.studentId) where ttm.classId = $classId and ttm.subjectId = $subjectId and ttm.studentId IN ($studentIdList2) and conductingAuthority in (1,3) group by ttm.studentId having cnt between '$lowMarksValue' and '$highMarksValue') as t";





		/*$query = "SELECT subjectId,subjectCode, SUM( if( ceil(marksScored)
				BETWEEN 0
				AND 9 , 1, 0 ) ) AS 'total0', SUM( if( ceil(marksScored)
				BETWEEN 10
				AND 13 , 1, 0 ) ) AS 'total1', SUM( if( ceil(marksScored)
				BETWEEN 14
				AND 16 , 1, 0 ) ) AS 'total2', SUM( if( ceil(marksScored)
				BETWEEN 17
				AND 24 , 1, 0 ) ) AS 'total3', SUM( if( ceil(marksScored)
				BETWEEN 25
				AND 30 , 1, 0 ) ) AS 'total4', SUM( if( ceil(marksScored)
				BETWEEN 31
				AND 35 , 1, 0 ) ) AS 'total5' , SUM( if( ceil(marksScored)
				BETWEEN 36
				AND 40 , 1, 0 ) ) AS 'total6'
				FROM (
				SELECT ttm.studentId,s.subjectCode, ttm.classId, ttm.subjectId, IF(tgm.graceMarks IS NULL,sum( marksScored ),(sum( marksScored)  + tgm.graceMarks))  AS marksScored
				FROM  time_table_classes ttc, subject s,time_table_labels ttl,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
				LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON (tgm.classId = ttm.classId AND ttm.subjectId = tgm.subjectId AND ttm.studentId = tgm.studentId)
				WHERE ttm.classId = ttc.classId
				 $condition
				AND ttm.conductingAuthority
				IN ( 1, 3 )
				AND ttc.timeTableLabelId=ttl.timeTableLabelId
				AND ttl.isActive=1
				AND ttm.subjectId = s.subjectId

				GROUP BY ttm.subjectId, ttm.studentId, ttm.classId
				ORDER BY ttm.subjectId, ttm.studentId, ttm.classId


				) AS t
				GROUP BY subjectId, classId";*/
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------------------------------------------------------------------------------------------
//function created for fetching Student External Marks
// Author :Parveen Sharma
// Created on : 28-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function getStudentExternalMarks($condition='',$orderBy=' s.universityRollNo',$limit='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                       DISTINCT
                                s.studentId,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                IF(IFNULL(s.studentEmail,'')='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                                IF(IFNULL(s.corrCityId,'')='','".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = s.corrCityId)) AS corrCityId,
                                CONCAT(IFNULL(s.firstName,''), ' ',IFNULL(s.lastName,'')) AS studentName,
                                br.branchName, ins.instituteName
                 FROM
                       institute ins, branch br, class cls,
                       student s LEFT JOIN student_groups sg ON sg.studentId = s.studentId
                 WHERE
                       sg.classId = cls.classId          AND
                       br.branchId = cls.branchId        AND
                       ins.instituteId = cls.instituteId AND
                       cls.instituteId =  $instituteId   AND
                       cls.sessionId =  $sessionId
                 $condition
                 GROUP BY
                       sg.studentId
                 ORDER BY
                       $orderBy
                 $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//function created for fetching Student External Marks
// Author :Parveen Sharma
// Created on : 28-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function getStudentTotalExternalMarks($condition='') {

        $query = "SELECT
                       DISTINCT
                                ttm.classId, ttm.studentId, su.subjectId, ttm.marksScored
                  FROM
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, `subject` su
                  WHERE
                        ttm.conductingAuthority = 2 AND
                        su.subjectId = ttm.subjectId
                  $condition
                  ORDER BY
                        ttm.classId, ttm.studentId, su.subjectTypeId, su.subjectCode, su.subjectId";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for internal marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getCountInternalPerformanceData($conditions,$orderBy) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "SELECT

				tm.studentId,CONCAT(stu.firstName,' ',stu.lastName) as studentName,stu.studentId FROM `test_type_category` ttc,`".TEST_TABLE."` tt,`".TEST_MARKS_TABLE."` tm,`student` stu WHERE ttc.testTypeCategoryId = tt.testTypeCategoryId and ttc.examType='PC' aND instituteId=$instituteId and	sessionId=$sessionId  AND tm.testId=tt.testId AND stu.studentId=tm.studentId
				$conditions
				group by

				tm.studentId

				ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for internal marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getInternalPerformanceData($conditions,$orderBy,$limit) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "SELECT

				 tm.studentId,CONCAT(stu.firstName,' ',stu.lastName) as studentName,stu.universityRegNo,stu.rollNo,stu.studentMobileNo,stu.studentEmail,stu.studentGender FROM `test_type_category` ttc,`".TEST_TABLE."` tt,`".TEST_MARKS_TABLE."` tm,`student` stu WHERE ttc.testTypeCategoryId = tt.testTypeCategoryId and ttc.examType='PC' aND instituteId=$instituteId and	sessionId=$sessionId  AND tm.testId=tt.testId AND stu.studentId=tm.studentId
				$conditions
				group by

				tm.studentId

				ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

    //-----------------------------------------------------------------------------------------------
    // function created for fetching Student Detail
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------

    public function getStudentAddressPhoto($classId='',$studentIds='') {

      $query = "SELECT
                      DISTINCT
                            sss.studentId, sss.classId,
                            CONCAT(IFNULL(corrAddress1,''),' ',IFNULL(corrAddress2,''),'<br>',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(stu.corrPinCode IS NULL OR stu.corrPinCode='','',CONCAT('-',stu.corrPinCode))) AS corrAdd,
                            CONCAT(IFNULL(permAddress1,''),' ',IFNULL(permAddress2,''),'<br>',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(stu.permPinCode IS NULL OR stu.permPinCode='','',CONCAT('-',stu.permPinCode))) AS permAdd,
                            CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                            IFNULL(fatherName,'') AS fatherName,
                            stu.rollNo AS rollNo,  IFNULL(studentPhoto,'') AS studentPhoto,
                            stu.universityRollNo AS universityRollNo,
                            IF(stu.studentMobileNo='','".NOT_APPLICABLE_STRING."',stu.studentMobileNo) AS studentMobileNo ,
                            SUBSTRING_INDEX(cls.classname,'".CLASS_SEPRATOR."',-4)  AS className
                FROM
                        student stu, class cls,
                        student_groups sss
                WHERE
                        sss.studentId = stu.studentId AND
                        cls.classId = sss.classId AND
                        cls.classId = $classId AND
                        stu.studentId IN ($studentIds)
                ORDER BY
                        sss.studentId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for internal marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getStudentArray($conditions) {

		$query = "SELECT sub.subjectId,sub.subjectCode,sub.subjectName From subject sub, subject_to_class stc WHERE

				 stc.subjectId= sub.subjectId
				 $conditions ORDER BY sub.subjectId
				";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for internal marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getStudentMarksArray($conditions) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "SELECT
					sub.subjectCode,
					sub.subjectName,
					sub.subjectTypeId,
					tm.studentId,
					tt.testAbbr,
					tm.subjectId,
					SUM(tm.maxMarks) as totalMaxMarks,
					SUM(tm.marksScored) as totalMarksScored
				FROM
					`test_type_category` ttc,
					`".TEST_TABLE."` tt,
					`".TEST_MARKS_TABLE."` tm,
					`student` stu,
					 subject sub
				WHERE
				ttc.testTypeCategoryId = tt.testTypeCategoryId
				And	ttc.examType='PC'
				And	instituteId=$instituteId
				And	sessionId=$sessionId

				AND	tm.testId=tt.testId
				AND	stu.studentId=tm.studentId

				And	tm.subjectId = sub.subjectId

				 $conditions
					group by sub.subjectId
					order by sub.subjectId,sub.subjectTypeId

				";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

    //-----------------------------------------------------------------------------------------------
    // function created for fetching records for students for internal marks
    // Author :Rajeev Aggarwal
    // Created on : 21-04-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------------------------------
	public function getStudentAttendanceArray($conditions) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId   = $sessionHandler->getSessionVariable('SessionId');

		$query = "
				SELECT
					scs.studentId,CONCAT(scs.firstName,' ',scs.lastName) AS studentName,sub.subjectId,sub.subjectCode, scs.rollNo,ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS attended,SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,sg.groupId,sub.subjectTypeId

				 FROM
					student scs,class c,subject sub,student_groups sg,".ATTENDANCE_TABLE." att

				 LEFT JOIN attendance_code ac  ON   (ac.attendanceCodeId = att.attendanceCodeId  and ac.instituteId = $instituteId)

				 WHERE
					att.studentId = scs.studentId AND
					c.classId=att.classId AND
					sg.groupId= att.groupId AND
					sg.studentId = att.studentId AND
					sub.subjectId = att.subjectId AND
					c.sessionId=$sessionId  AND
					c.instituteId=$instituteId
					 $conditions
				GROUP BY
					att.subjectId, att.studentId, att.classId


				ORDER BY
					 att.subjectId

				";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//----------------------------------------------------------------------------------------------------
	//function created for counting students programme wise

	// Author :Rajeev Aggarwal
	// Created on : 04-05-2009
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getProgrammeWiseHostel($conditions='') {
		global $sessionHandler;
		$query = "SELECT
					COUNT(*) as totalRecords, dg.degreeCode, br.branchCode, st.classId, st.studentGender, st.hostelId,cls.degreeId,cls.branchId

				FROM
					`student` st, `class` cls,`branch` br,`degree` dg

				WHERE
					br.branchId = cls.branchId AND
					dg.degreeId = cls.degreeId AND
					st.classId = cls.classId AND
					st.hostelId IS NOT NULL AND		cls.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' 
					$conditions
				GROUP BY
					studentGender,cls.branchId
                ORDER BY cls.branchId,studentGender"; 

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getRoomAllocationList($conditions='', $limit = '', $orderBy=' studentName') {
     
        $query = "SELECT 
                        s.studentId,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IFNULL(s.rollNo,'---') AS rollNo,
                        IFNULL(s.regNo,'---') AS regNo,
                        h.hostelCode,h.hostelName,
                        r.roomName,
                        hs.dateOfCheckIn,
                        IF(hs.dateOfCheckOut='0000-00-00','".NOT_APPLICABLE_STRING."',hs.dateOfCheckOut) AS dateOfCheckOut,
                        hs.hostelStudentId
                 FROM 
                       student s,hostel h,hostel_room r,hostel_students hs
                 WHERE 
                       s.studentId=hs.studentId
                       AND hs.hostelRoomId=r.hostelRoomId
                       AND h.hostelId=r.hostelId
                       $conditions 
                       ORDER BY $orderBy 
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
	//----------------------------------------------------------------------------------------------------
	//function created for counting students programme wise

	// Author :Rajeev Aggarwal
	// Created on : 04-05-2009
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getAllHostelDetails($conditions='',$orderBy='cls.degreeId,cls.branchId,studentGender') {
		global $sessionHandler;
		$query = "SELECT
					st.studentId,CONCAT(st.firstName,' ',st.lastName) AS studentName, st.rollNo, st.studentMobileNo,ht.hostelName,hr.roomName,st.studentGender,st.studentEmail,cls.className, dg.degreeCode, br.branchCode

				  FROM
					`student` st, `hostel` ht, `hostel_room` hr,`class` cls,`branch` br,`degree` dg

				  WHERE
				    br.branchId = cls.branchId AND
					dg.degreeId = cls.degreeId AND
					st.classId = cls.classId AND
					st.hostelId = ht.hostelId AND
					st.hostelRoomId = hr.hostelRoomId AND
					st.hostelId IS NOT NULL AND
					cls.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
					$conditions
				  ORDER BY $orderBy";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getClassInternalTestTypes($classId, $subjectId) {
		
        $query = "SELECT 
                        DISTINCT ttm.testTypeId, ttc.testTypeCategoryId, ttc.testTypeName, 
                                 ttc.isAttendanceCategory, ttm.maxMarks, ttc.testTypeAbbr 
                  FROM 
                        ".TEST_TRANSFERRED_MARKS_TABLE." ttm, test_type tt, test_type_category ttc 
                  WHERE 
                        ttm.classId = $classId AND ttm.subjectId = $subjectId AND 
                        ttm.testTypeId = tt.testTypeId AND tt.testTypeCategoryId = ttc.testTypeCategoryId AND 
                        ttc.examType = 'PC' 
                  ORDER BY 
                        ttc.isAttendanceCategory DESC, ttc.testTypeName ASC";
                        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getFinalReportData($classId, $groupStr = '', $queryPart, $sortBy, $limit, $tableName) {
		$query = "select distinct stu.studentId, stu.rollNo, concat(stu.firstName,' ',stu.lastName) as studentName, universityRollNo $queryPart from student stu, $tableName sg where stu.studentId = sg.studentId and sg.classId = $classId $groupStr order by $sortBy $limit";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function checkSubjectMM($classId, $subjectId) {
		$query = "SELECT COUNT(studentId) as cnt from student_optional_subject where classId = $classId and subjectId = $subjectId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getDistinctTests($testTypeCategoryId, $classId, $subjectId, $conditions = "") {
		$query = "select distinct testIndex from ".TEST_TABLE." where testTypeCategoryId = $testTypeCategoryId and classId = $classId and subjectId = $subjectId $conditions order by testIndex";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countFinalReportData($classId,$groupStr = '', $tableName) {
		$query = "select count(distinct(stu.studentId)) as cnt from student stu, $tableName sg, ".TOTAL_TRANSFERRED_MARKS_TABLE." t where stu.studentId = sg.studentId and sg.classId = $classId and s.studentId = t.studentId and t.classId = $classId $groupStr";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//---------------------------------------------------------------------------------------------------------------------
//// Function gets records for test time period
//
// Author :Parveen Sharma
// Created on : 19-05-09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getTestRecord($conditions='',$orderBy='employeeName',$limit='')
    {
        global $REQUEST_DATA;
        global $sessionHandler;

        $query="SELECT
                        CONCAT(ttc.testTypeName,'-',testIndex) AS testType,
                        CONCAT(gr.groupName,' ',gt.groupTypeName) AS groupName,
                        e.employeeName, t.maxMarks, t.testDate,
                        IFNULL(s.subjectName,'".NOT_APPLICABLE_STRING."') AS subjectName,
                        IFNULL(s.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode
                FROM
                        ".TEST_TABLE." t,test_type tt, `subject` s,`group` gr,group_type gt, employee e, test_type_category ttc
                WHERE
                        ttc.testTypeCategoryId=tt.testTypeCategoryId
                        AND t.testTypeCategoryId=tt.testTypeCategoryId
                        AND gr.groupTypeId = gt.groupTypeId
                        AND s.subjectId=t.subjectId
                        AND gr.groupId=t.groupId
                        AND e.employeeId=t.employeeId
                        AND t.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                        AND t.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        $conditions
                GROUP BY
                        e.employeeId, s.subjectId, gr.groupId, gr.groupTypeId, t.testTypeCategoryId, ttc.testTypeCategoryId
                ORDER BY
                        $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

  public function getTestRecordNew($conditions='',$orderBy='employeeName',$limit='')
    {
        global $REQUEST_DATA;
        global $sessionHandler;

        $query="SELECT
                        CONCAT(ttc.testTypeName,'(',testAbbr,'-',testIndex,')') AS testType,
                        CONCAT(gr.groupName,' ',gt.groupTypeName)  AS groupName,
                        e.employeeName, t.maxMarks, t.testDate,
                        IFNULL(s.subjectName,'".NOT_APPLICABLE_STRING."') AS subjectName,
                        IFNULL(s.subjectCode,'".NOT_APPLICABLE_STRING."') AS subjectCode
                  FROM  ".TEST_TABLE." AS `t`
						INNER JOIN `test_type_category` AS `ttc` 
							ON (`t`.`testTypeCategoryId` = `ttc`.`testTypeCategoryId`)
						INNER JOIN `employee` AS `e`
							ON (`t`.`employeeId` = `e`.`employeeId`)
						INNER JOIN `subject` AS `s`
							ON (`t`.`subjectId` = `s`.`subjectId`)
						INNER JOIN `group` AS `gr`
							ON (`t`.`groupId` = `gr`.`groupId`)
						INNER JOIN `group_type` AS `gt`
							ON (`gr`.`groupTypeId` = `gt`.`groupTypeId`)
				 where 	t.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                        AND t.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        $conditions
                ORDER BY
                       ttc.testTypeName,testAbbr,testIndex $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------------------
//// Function gets records for test time period Counts
//
// Author :Parveen Sharma
// Created on : 19-05-09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getCountTestRecord($conditions='')
    {
        global $REQUEST_DATA;
        global $sessionHandler;
        $query="SELECT
                      COUNT(*) AS cnt
                FROM
                    (SELECT
                        CONCAT(ttc.testTypeName,'-',testIndex) AS testType,
                        CONCAT(gr.groupName,' ',gt.groupTypeName) AS groupName,
                        e.employeeName, t.maxMarks, t.testDate, s.subjectCode
                    FROM
                        ".TEST_TABLE." t,test_type tt, `subject` s,`group` gr,group_type gt, employee e, test_type_category ttc
                    WHERE
                        ttc.testTypeCategoryId=tt.testTypeCategoryId
                        AND t.testTypeCategoryId=tt.testTypeCategoryId
                        AND gr.groupTypeId = gt.groupTypeId
                        AND s.subjectId=t.subjectId
                        AND gr.groupId=t.groupId
                        AND e.employeeId=t.employeeId
                        AND t.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                        AND t.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                        $conditions
                    GROUP BY
                        e.employeeId, s.subjectId, gr.groupId, gr.groupTypeId, t.testTypeCategoryId, ttc.testTypeCategoryId ) AS temp ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------
	//function created for fetching User Data

	// Author :Jaineesh
	// Created on : 19-Jan-2009
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getRoleName($roleId='') {
		global $sessionHandler;

	$query = "
		 SELECT  roleName
		 FROM  role
		 WHERE  roleId = $roleId
		 ORDER BY roleId ASC
			$limit";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//----------------------------------------------------------------------------------------------------
	//function created for fetching User Data

	// Author :Jaineesh
	// Created on : 19-Jan-2009
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getUserData($limit='',$orderBy='',$roleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$query = "
		 SELECT  userName, userId, roleId,
			(CASE roleId
			WHEN 1
			THEN if((SELECT employeeName FROM employee WHERE userId=user.userId) is null,'-', (SELECT employeeName FROM employee WHERE userId=user.userId))
			WHEN 2
			THEN if((SELECT employeeName FROM employee WHERE userId=user.userId) is null,'-', (SELECT employeeName FROM employee WHERE userId=user.userId))
			WHEN 3
			THEN (
			 select stu.fatherName from student stu  where stu.fatherUserId = user.userId
			 union
			 select stu.motherName from student stu  where stu.motherUserId = user.userId
			 union
			 select stu.guardianName from student stu  where stu.guardianUserId = user.userId
			)
			WHEN 4
			THEN if((SELECT firstName FROM student WHERE userId=user.userId) is null,'-', (SELECT firstName FROM student WHERE userId=user.userId))
			ELSE
			if((SELECT employeeName FROM employee WHERE userId=user.userId) is null,'-', (SELECT employeeName FROM employee WHERE userId=user.userId))
			END) AS name
		 FROM  user
		 WHERE  user.roleId = $roleId
		 AND	user.instituteId = $instituteId
		 ORDER BY $orderBy
			$limit";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }


//---------------------------------------------------------------------------------------------------
//function created for fetching 3 months previous date from current date

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
	public function getUserThreeMonthsLoginData() {
	$query = "
				SELECT	DATE_SUB(CURDATE(), INTERVAL 90 DAY) as previousDay
			 ";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//---------------------------------------------------------------------------------------------------
//function created for fetching User Data

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
	public function getUserThreeLoginData($userId) {
	$query = "
				SELECT	COUNT(userId) AS totalCount,
						DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime,
						DATE_FORMAT(dateTimeIn,'%d-%b-%y') as loggedTime
				FROM 	user_log ul
				WHERE 	dateTimeIn >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
				AND		ul.userId = $userId
						GROUP BY loggedInTime
						ORDER BY loggedInTime ASC";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//---------------------------------------------------------------------------------------------------
//function created for fetching User Data

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
	public function getUserSixLoginData($userId) {
	$query = "
				SELECT	COUNT(userId) AS  totalCount,
						DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime,
						DATE_FORMAT(dateTimeIn,'%d-%b-%y') as loggedTime
				FROM	user_log ul
				WHERE	dateTimeIn >= DATE_SUB(CURDATE(), INTERVAL 180 DAY)
				AND		ul.userId = $userId
						GROUP BY loggedInTime
						ORDER BY loggedInTime ASC";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }


//---------------------------------------------------------------------------------------------------
//function created for fetching 6s months previous date from current date

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
	public function getUserSixMonthsLoginData() {
	$query = "
				SELECT	DATE_SUB(CURDATE(), INTERVAL 180 DAY) as previousDay
			 ";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//---------------------------------------------------------------------------------------------------
//function created for fetching User Data

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
	public function getUserNineLoginData($userId) {
	$query = "
				SELECT	COUNT(userId) AS  totalCount,
						DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime,
						DATE_FORMAT(dateTimeIn,'%d-%b-%y') as loggedTime
				FROM	user_log ul
				WHERE	dateTimeIn >= DATE_SUB(CURDATE(), INTERVAL 270 DAY)
				AND		ul.userId = $userId
						GROUP BY loggedInTime
						ORDER BY loggedInTime ASC";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//---------------------------------------------------------------------------------------------------
//function created for fetching 9 months previous date from current date

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------
	public function getUserNineMonthsLoginData() {
	$query = "
				SELECT	DATE_SUB(CURDATE(), INTERVAL 270 DAY) as previousDay
			 ";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//---------------------------------------------------------------------------------------------------
//function created for fetching total count one year

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
	public function getUserYearLoginData($userId) {
	$query = "
				SELECT	COUNT(userId) AS  totalCount,
						DATE_FORMAT(dateTimeIn,'%Y-%m-%d') as loggedInTime,
						DATE_FORMAT(dateTimeIn,'%d-%b-%y') as loggedTime
				FROM	user_log ul
				WHERE	dateTimeIn >= DATE_SUB(CURDATE(), INTERVAL 360 DAY)
				AND		ul.userId = $userId
						GROUP BY loggedInTime
						ORDER BY loggedInTime ASC";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//--------------------------------------------------------------------------------------------------
//function created for fetching 1 year previous date from current date

// Author :Jaineesh
// Created on : 09-April-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
	public function getUserOneYearLoginData() {
	$query = "
				SELECT	DATE_SUB(CURDATE(), INTERVAL 360 DAY) as previousDay
			 ";
	  return SystemDatabaseManager::getInstance()->executeQuery($query);
	 }

//-------------------------------------------------------------------------------------------------
//function created for fetching User Data

// Author :Jaineesh
// Created on : 19-Jan-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
	public function getTotalUserData($roleId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "
					SELECT		COUNT(*) as totalRecords
					FROM		user
					WHERE		user.roleId = $roleId
					AND			user.instituteId = $instituteId
					ORDER BY	userId ASC ";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Offence/Achievements List
//
// Author :Parveen Sharma
// Created on : (29.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentOffenceList($condition='',$orderBy='offenseName',$limit=''){

        global $sessionHandler;

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query="SELECT
                       sd.disciplineId, s.rollNo, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                       REPLACE(SUBSTRING_INDEX( cls.className, '-' , -3 ) , '-', '')  AS className,
                       gr.groupName, off.offenseName, sd.offenseDate,
                       sd.remarks, sd.reportedBy, e.employeeName, e.employeeCode, sp.periodName
                FROM
                       `offense` off, `student_discipline` sd, `class` cls, `study_period` sp,
                        student_groups sg, `group` gr, student s,  ".TIME_TABLE_TABLE."  tt, employee e
                WHERE
                       e.employeeId = tt.employeeId AND
                       sp.studyPeriodId = cls.studyPeriodId AND
                       s.studentId = sd.studentId AND
                       off.offenseId = sd.offenseId AND
                       sd.classId = cls.classId AND
                       sd.classId = sg.classId AND
                       sd.studentId = sg.studentId AND
                       sg.classId = cls.classId AND
                       gr.groupId = sg.groupId  AND
                       cls.instituteId = $instituteId AND
                       cls.sessionId = $sessionId  AND
                       gr.groupId = tt.groupId    AND
                       tt.toDate is NULL AND
                       tt.instituteId = $instituteId AND
                       tt.sessionId = $sessionId
                $condition
                GROUP BY
                       sd.studentId
                ORDER BY $orderBy
                $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Offence/Achievements List Count
//
// Author :Parveen Sharma
// Created on : (29.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getTotalStudentOffence($condition=''){

        global $sessionHandler;

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query="SELECT
                       COUNT(*) AS cnt
                FROM
                    (SELECT
                            sd.studentId
                     FROM
                           `offense` off, `student_discipline` sd, `class` cls, `study_period` sp,
                            student_groups sg, `group` gr, student s,  ".TIME_TABLE_TABLE."  tt
                     WHERE
                            s.studentId = sd.studentId AND
                            sp.studyPeriodId = cls.studyPeriodId AND
                            off.offenseId = sd.offenseId AND
                            sd.classId = cls.classId AND
                            sd.classId = sg.classId AND
                            sd.studentId = sg.studentId AND
                            sg.classId = cls.classId AND
                            gr.groupId = sg.groupId  AND
                            cls.instituteId = $instituteId AND
                            cls.sessionId = $sessionId  AND
                            gr.groupId = tt.groupId    AND
                            tt.toDate is NULL AND
                            tt.instituteId = $instituteId AND
                            tt.sessionId = $sessionId
                     $condition
                     GROUP BY
                            sd.studentId) AS t ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Offence/Achievements Details
//
// Author :Parveen Sharma
// Created on : (29.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getOffenceDetail($condition=''){

        $query="SELECT
                       remarks
                FROM
                      `student_discipline`
                $condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


/*********************THESE FUNCTIONS WILL BE USED FOR STUDENT ACADEMIC PERFORMANCE REPORT***************************/


//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Details
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentInfo($studentIds){

        $query="SELECT
                        s.studentId,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        CONCAT( IF(s.firstName IS NULL OR s.firstName='','',s.firstName),' ', IF(s.lastName IS NULL OR s.lastName='','',s.lastName) ) AS studentName,
                        SUBSTRING_INDEX(c.className,'-',-1) AS semName,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(c.className,'-',-2),'-',1) AS stremName,
                        className
                FROM
                        student s,`class` c
                WHERE
                        s.classId=c.classId
                        AND s.studentId IN ($studentIds)
                        $condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Details
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getHODInfo($condition){

        $query="SELECT
                       DISTINCT
                                IFNULL(e.employeeName,'') AS employeeName,
                                IFNULL(d.departmentName,'') AS departmentName,
                                IFNULL(e.mobileNumber,'') AS mobileNumber
                FROM
                       classes_visible_to_role r, class c, role ro,
                       employee e LEFT JOIN department d ON e.departmentId = d.departmentId
                WHERE
                       ro.roleId = r.roleId  AND
                       c.classId = r.classId AND
                       e.userId = r.userId   AND
                       e.isActive = 1
                       $condition
                ORDER BY
                       employeeName ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function getClassInfo($classId,$conditions=''){

        $query="SELECT
                        classId,className
                FROM
                        class
                WHERE
                        classId=$classId
                        $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Attendance & subject
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentAttendanceDate($classId,$studentId){

    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');

    $query = "SELECT
                        MIN(att.fromDate) AS fromDate, MAX(att.toDate) AS toDate
                FROM
                        subject_type st,
                        student s
                        INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                        LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId   and ac.instituteId = $instituteId)
                        INNER JOIN subject sub ON sub.subjectId = att.subjectId
                        INNER JOIN class cl ON cl.classId = att.classId AND cl.instituteId=".$instituteId." AND cl.sessionId=".$sessionId."
                WHERE   att.studentId = $studentId
                        AND att.classId = $classId
                        AND st.subjectTypeId = sub.subjectTypeId
                GROUP BY
                        att.studentId
                ORDER BY
                        st.subjectTypeId
                $condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Details
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentClassDetail($condition='',$orderBy=' rollNO'){
       global $sessionHandler;
       $query="SELECT
						s.studentId,
						IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        CONCAT( IF(s.firstName IS NULL OR s.firstName='','',s.firstName),' ', IF(s.lastName IS NULL OR s.lastName='','',s.lastName) ) AS studentName,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo
                FROM
						student s,student_groups sg
                WHERE
                        s.studentId=sg.studentId
                        AND sg.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND sg.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
						$condition
                        GROUP BY sg.studentId
                        ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//get the subject type details for this class through university
public function getSubjectTypeDetails($classId){
    global $sessionHandler;

    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');

    $query="
            SELECT
                  st.subjectTypeId,
                  st.subjectTypeName,
                  st.subjectTypeCode,
                  c.classId
            FROM
                  `class` c,subject_type st
            WHERE
                  c.universityId=st.universityId
                  AND c.instituteId=$instituteId
                  AND c.sessionId=$sessionId
                  AND c.classId=$classId
            GROUP BY st.subjectTypeId
            ORDER BY st.subjectTypeId
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Attendance & subject
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentAttendanceSubject($classId,$studentId,$subjectTypeId){

	global $sessionHandler;
	$instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');

	$query = "SELECT
						sub.subjectId,
						sub.subjectCode,
						sub.subjectName,
						st.subjectTypeId,
						st.subjectTypeName,
						FORMAT( SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) ) , 1 ) AS lectureAttended,
						SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS lectureDelivered
                FROM
						subject_type st,
						student s
						INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
						LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId   and ac.instituteId = $instituteId)
						INNER JOIN subject sub ON sub.subjectId = att.subjectId
						INNER JOIN class cl ON cl.classId = att.classId AND cl.instituteId=".$instituteId." AND cl.sessionId=".$sessionId."
				WHERE	att.studentId = $studentId
				AND		att.classId = $classId
				AND		st.subjectTypeId = sub.subjectTypeId
                AND     st.subjectTypeId=$subjectTypeId
						GROUP BY att.subjectId, att.studentId
						ORDER BY st.subjectTypeId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student marks
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentAttendanceTransferredMarks($classId,$studentId,$subjectIdList){

	global $sessionHandler;
	$instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
	      $query="SELECT
						st.subjectTypeName,
						st.subjectTypeId,
						sub.subjectId,
						ttm.marksScored,
                        ttm.maxMarks,
                        ttm.conductingAuthority
                FROM
						subject sub,
						subject_type st,
						".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
						student s,
						class cl
				WHERE	ttm.classId = cl.classId
				AND		ttm.subjectId = sub.subjectId
				AND		ttm.studentId = s.studentId
				AND		s.studentId = $studentId
				AND		cl.classId = $classId
                AND     cl.instituteId = $instituteId
                AND     cl.sessionId = $sessionId
				AND		st.subjectTypeId = sub.subjectTypeId
				AND		ttm.subjectId IN ($subjectIdList)
				AND     ttm.conductingAuthority=3
                ORDER BY ttm.conductingAuthority DESC
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getStudentTotalTransferredMarks($classId,$studentId,$subjectIdList,$condition=''){

    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    if($condition == '') {
       $condition = " AND ttm.conductingAuthority IN (1,3) ";
    }


          $query="SELECT
                        st.subjectTypeName,
                        st.subjectTypeId,
                        sub.subjectId,
                        SUM(ttm.marksScored) AS marksScored,
                        SUM(ttm.maxMarks) AS maxMarks,
                        ttm.conductingAuthority
                FROM
                        subject sub,
                        subject_type st,
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
                        student s,
                        class cl
                WHERE   ttm.classId = cl.classId
                AND     ttm.subjectId = sub.subjectId
                AND     ttm.studentId = s.studentId
                AND     s.studentId = $studentId
                AND     cl.classId = $classId
                AND     cl.instituteId = $instituteId
                AND     cl.sessionId = $sessionId
                AND     st.subjectTypeId = sub.subjectTypeId
                AND     ttm.subjectId IN ($subjectIdList)
                $condition
                GROUP BY ttm.subjectId
                ORDER BY ttm.conductingAuthority DESC";

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

public function getTestMarksDetails($studentId,$classId,$subjectIds){
    global $sessionHandler;

    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');

    $query="
            SELECT
                  t.subjectId, t.testIndex,
                  t.testAbbr,
                  t.maxMarks,
                  tm.marksScored,
                  ttc.testTypeCategoryId
            FROM
                  ".TEST_TABLE." t, ".TEST_MARKS_TABLE." tm,
                  test_type_category ttc, `subject` sub
            WHERE
                  t.testId = tm.testId AND t.subjectId = tm.subjectId
                  AND t.subjectId = sub.subjectId
                  AND t.testTypeCategoryId = ttc.testTypeCategoryId
                  AND tm.studentId =$studentId
                  AND t.classId =$classId
                  AND t.sessionId=$sessionId ANd t.instituteId=$instituteId
                  AND t.subjectId IN ( $subjectIds )
                  AND t.instituteId=$instituteId
                  AND t.sessionId=$sessionId
                  ORDER BY t.subjectId,ttc.testTypeCategoryId, t.testIndex
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


public function getTestTypesDetails($testTypeCategoryIds){

    $query="
            SELECT
                  tt.testTypeId,
                  tt.testTypeName,
                  tt.testTypeCode
            FROM
                  test_type tt
            WHERE
                  testTypeCategoryId IN ($testTypeCategoryIds)
            ORDER BY tt.testTypeId
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


public function getTestTransferredMarks($testTypeIds,$studentId,$classId,$subjectIds){
     
     global $sessionHandler;

     $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
    
     $query="
            SELECT
                  ttf.classId, ttf.subjectId, ttf.studentId, ttf.maxMarks, ttf.marksScored,
                  tt.conductingAuthority, tt.testTypeCategoryId
            FROM
                  ".TEST_TRANSFERRED_MARKS_TABLE." ttf,test_type tt
            WHERE
                  ttf.testTypeId IN ($testTypeIds)
                  AND ttf.studentId=$studentId
                  AND ttf.classId=$classId
                  AND ttf.subjectId in ($subjectIds)
                  AND ttf.testTypeId=tt.testTypeId
            ORDER BY tt.testTypeCategoryId, ttf.subjectId
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


public function getSubjectWiseTestTransferredMarks($testTypeIds,$studentId,$classId,$subjectIds,$condition=''){

     global $sessionHandler;

     $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     $sessionId = $sessionHandler->getSessionVariable('SessionId');
     
     if($testTypeIds=='') {
      $testTypeIds='0';   
     }
     
     if($studentId=='') {
      $studentId='0';   
     }
     
     if($classId=='') {
      $classId='0';   
     }
     
     if($subjectIds=='') {
      $subjectIds='0';   
     }
        
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


//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student marks
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentTestTypeCategory($subjectTypeId,$subjectId){

	global $sessionHandler;

		$query="SELECT
						ttc.testTypeCategoryId,
						ttc.subjectTypeId,
						sub.subjectId
				FROM
						test_type_category ttc,
						subject_type st,
						subject sub
				WHERE	ttc.subjectTypeId = $subjectTypeId
				AND		ttc.subjectTypeId = st.subjectTypeId
				AND		sub.subjectTypeId = st.subjectTypeId
				AND		sub.subjectId = $subjectId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student tests
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentTestMarks($getSubjectIds,$studentId,$testId){

	global $sessionHandler;

	$query="SELECT
						tm.studentId,
						tm.maxMarks,
						tm.marksScored,
						tm.subjectId,
						tm.testId,
						CONCAT(t.testAbbr,'',t.testIndex) AS testName,
						t.testTypeCategoryId
				FROM
						".TEST_MARKS_TABLE." tm,
						".TEST_TABLE." t,
						student s,
						test_type_category ttc
				WHERE	tm.studentId = s.studentId
				AND		tm.studentId = $studentId
				AND		tm.testId = $testId
				AND		tm.testId = t.testId
				AND		tm.subjectId IN ($getSubjectIds)
				AND		t.testTypeCategoryId = ttc.testTypeCategoryId
						ORDER BY t.testTypeCategoryId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student tests
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getMaxStudentTest($getSubjectIds,$classId,$studentId){

	global $sessionHandler;

	$query="SELECT
						count(t.testId) as totalTest,
						t.subjectId,
						t.classId,
						t.testTypeCategoryId,
						s.studentId
				FROM
						".TEST_TABLE." t,
						student s,
						student_groups sg
				WHERE	t.classId = $classId
				AND		t.classId = s.classId
				AND		s.studentId = $studentId
				AND		t.groupId = sg.groupId
				AND		sg.classId = $classId
				AND		sg.studentId = $studentId
				AND		t.subjectId IN ($getSubjectIds)
						GROUP BY t.testTypeCategoryId, t.subjectId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student tests
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentTestId($getSubjectIds,$classId,$studentId){

	global $sessionHandler;

	$query="SELECT
						t.subjectId,
						sub.subjectName,
						t.classId,
						t.testTypeCategoryId,
						s.studentId,
						t.testId
				FROM
						".TEST_TABLE." t,
						subject sub,
						student s,
						student_groups sg
				WHERE	t.classId = $classId
				AND		t.classId = s.classId
				AND		t.subjectId = sub.subjectId
				AND		s.studentId = $studentId
				AND		t.groupId = sg.groupId
				AND		sg.classId = $classId
				AND		sg.studentId = $studentId
				AND		t.subjectId IN ($getSubjectIds)
						ORDER BY t.testTypeCategoryId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//----------------------------------------------------------------------------------------------------
//function created for fetching marks distribution list for one subject

// Author :Jaineesh
// Created on : 09-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getMarksOneSubject($degree, $subjectTypeId, $subjectId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$query = "SELECT
						conductingAuthority,
						IF(b.conductingAuthority = 1, 'INTERNAL', 'EXTERNAL') as conductingAuthority2,
						a.subjectId,
						a.subjectCode,
						b.testTypeName,
						b.weightageAmount,
						ROUND(b.weightagePercentage,1) AS weightagePercentage,
						b.testTypeId,
						b.testTypeCategoryId
				FROM	subject a, test_type b
				WHERE	a.subjectId = $subjectId AND
						b.instituteId = $instituteId AND
						a.subjectId = b.subjectId AND
						a.subjectTypeId = $subjectTypeId
				UNION
				SELECT
						conductingAuthority,
						IF(b.conductingAuthority = 1, 'INTERNAL', 'EXTERNAL') as conductingAuthority2,
						a.subjectId,
						a.subjectCode,
						b.testTypeName,
						b.weightageAmount,
						ROUND(b.weightagePercentage,1) AS weightagePercentage,
						b.testTypeId,
						b.testTypeCategoryId
				FROM	subject a, test_type b, class c
				WHERE	c.classId = $degree AND
						a.subjectId = $subjectId AND
						a.subjectTypeId = $subjectTypeId AND
						b.instituteId = $instituteId AND
						b.subjectTypeId = $subjectTypeId AND
						a.subjectId NOT in (SELECT IF(subjectId IS NULL,0, subjectId) FROM test_type WHERE instituteId = $instituteId) AND
						CASE
							WHEN (b.subjectId IS NULL) AND
							(b.studyPeriodId = c.studyPeriodId OR b.studyPeriodId is null) AND
							(b.branchId = c.branchId OR b.branchId IS NULL) AND
							(b.degreeId = c.degreeId or b.degreeId is null) AND b.universityId = c.universityId THEN 2
						ELSE
							NULL
						END
						IS NULL
						";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student marks
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getTotalTransferredMarks($classId,$studentId,$subjectId,$testTypeId){

	global $sessionHandler;

		 $query="SELECT
						ttm.maxMarks,
						ttm.marksScored,
						concat(sum(ttm.maxMarks),'/',sum(ttm.marksScored)) AS totalMarks
				FROM
						".TEST_TRANSFERRED_MARKS_TABLE." ttm
				WHERE	ttm.classId = $classId
				AND		ttm.studentId = $studentId
				AND		ttm.subjectId = $subjectId
				AND		ttm.testTypeId = $testTypeId
						GROUP BY ttm.studentId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student marks
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getTotalTestMarks($classId,$studentId,$subjectId,$testTypeId){

	global $sessionHandler;

		 $query="SELECT
						ttm.maxMarks,
						ttm.marksScored,
						concat(sum(ttm.maxMarks),'/',sum(ttm.marksScored)) AS totalMarks
				FROM
						".TEST_TRANSFERRED_MARKS_TABLE." ttm
				WHERE	ttm.classId = $classId
				AND		ttm.studentId = $studentId
				AND		ttm.subjectId = $subjectId
				AND		ttm.testTypeId = $testTypeId
						GROUP BY ttm.studentId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Test Type Ids
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentTestType($subjectTypeList,$testTypeCategoryList){

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and tt.instituteId = $instituteId";
		}
		else {
			$conditions .= " where tt.instituteId = $instituteId";
		}

		  $query="SELECT
						tt.testTypeId,
						tt.subjectTypeId,
						testTypeCategoryId
				FROM
						test_type tt
				WHERE	tt.subjectTypeId IN ($subjectTypeList)
				AND		tt.testTypeCategoryId IN ($testTypeCategoryList)
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Test Type Ids
//
// Author :Jaineesh
// Created on : (17.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentTestTransferredMarks($testTypeId,$classId,$studentId,$getSubjectIds){

	global $sessionHandler;

		 $query="SELECT
						ttm.testTypeId,
						ttm.subjectId,
						ttm.classId,
						ttm.studentId,
						CONCAT(ttm.marksScored,'/',ttm.maxMarks) as totalMarks
				FROM
						".TEST_TRANSFERRED_MARKS_TABLE." ttm
				WHERE	ttm.testTypeId = $testTypeId
				AND		ttm.classId = $classId
				AND		ttm.subjectId IN ($getSubjectIds)
				AND		ttm.studentId = $studentId
						$condition ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


/*********************THESE FUNCTIONS WILL BE USED FOR STUDENT ACADEMIC PERFORMANCE REPORT***************************/

    //----------------------------------------------------------------------------------------------------
    // function created for students receipt no findout
    // Author :Parveen Sharma
    // Created on : 29-06-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getReceiptNo($conditions='') {
        global $sessionHandler;
        $query = "SELECT
                            DISTINCT
                            CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                            a.rollNo, a.regNo,
                            CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) as programme,
                            CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode,' ',f.periodName) as className,
                            f.periodName ,
                            a.classId as class_id,
                            a.studentId,
                            e.branchCode AS Course,
                            d.degreeName AS collegeName,
                            CONCAT(YEAR(btch.startDate),'-',YEAR(btch.endDate)) AS studentSession,
                            IF(a.studentEmail='','".NOT_APPLICABLE_STRING."',a.studentEmail) AS studentEmail,
                            universityRollNo,
                            IF(a.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = a.corrCityId)) AS corrCityId,
                            IF(a.classId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT periodName FROM study_period sp, class cls WHERE sp.studyPeriodId = cls.studyPeriodId and cls.classId = a.classId)) AS studyPeriod,
                            IF(a.studentMobileNo='','".NOT_APPLICABLE_STRING."',a.studentMobileNo) AS studentMobileNo ,
                            IF(corrAddress1 IS NULL OR corrAddress1='','', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=a.corrCityId),' ',(SELECT stateName from states where states.stateId=a.corrStateId),' ',(SELECT countryName from countries where countries.countryId=a.corrCountryId),IF(a.corrPinCode IS NULL OR a.corrPinCode='','',CONCAT('-',a.corrPinCode)))) AS corrAddress,
                            IF(permAddress1 IS NULL OR permAddress1='','', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=a.permCityId),' ',(SELECT stateName from states where states.stateId=a.permStateId),' ',(SELECT countryName from countries where countries.countryId=a.permCountryId),IF(a.permPinCode IS NULL OR a.permPinCode='','',CONCAT('-',a.permPinCode)))) AS permAddress,
                            fatherName AS fatherName, dateOfBirth AS DOB, studentPhoto AS Photo,
                            ins.instituteId,ins.instituteCode,ins.instituteName,ins.instituteAbbr,ins.instituteLogo,
                            ins.instituteAddress1,ins.instituteAddress2, ins.employeePhone AS insPhone,
                            ins.instituteEmail,ins.instituteWebsite,
                            IF(IFNULL(bpass.busRouteId,0)=0,'0',(SELECT routeCode FROM bus_route broute WHERE broute.busRouteId=bpass.busRouteId)) AS routeCode,
                            IF(IFNULL(bpass.busStopId,0) =0,'0',(SELECT stopName  FROM bus_stop bstop   WHERE bstop.busStopId=bpass.busStopId)) AS stopName,
                            a.compExamBy, a.compExamRank, a.compExamRollNo, bpass.busRouteId,  bpass.busStopId,
                            bpass.receiptNo, bpass.validUpto, bpass.busPassStatus, bpass.busPassId,
                            b.instituteId, b.sessionId, b.classId
                  FROM      class b, university c, degree d,  branch e, study_period f,
                            batch  btch, institute ins, student a,
                            student_groups sg LEFT JOIN bus_pass  bpass  ON (sg.studentId = bpass.studentId AND sg.classId = bpass.classId AND bpass.busPassStatus = 1)
                  WHERE
                            a.studentId = sg.studentId
                            AND sg.classId = b.classId
                            AND btch.batchId = b.batchId
                            AND b.universityId = c.universityId
                            AND b.degreeId = d.degreeId
                            AND b.branchId = e.branchId
                            AND b.studyPeriodId = f.studyPeriodId
                            AND a.studentStatus = 1
                            AND b.instituteId = ins.instituteId
                            $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
	//  THIS FUNCTION IS USED TO GET CLASS
	//
	// Author :Jaienesh
	// Created on : (25.08.09)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	public function getSelectedTimeTableClasses($labelId) {
		global $sessionHandler;
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
        $roleName = $sessionHandler->getSessionVariable('RoleName'); 
		$systemDatabaseManager = SystemDatabaseManager::getInstance();

        if($roleId>=2 && $roleId<=4) {
          $classCondition1 = " AND c.isActive IN (1) ";
        }
        else {
          $classCondition1 = " AND c.isActive IN (1,3) ";
        }
        
        $tableName = "";
        $hodCondition = "";
        if($roleName== 'HOD_COORDINATOR') {
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

            
            if ($count > 0) {
                $tableName = ", `group` gr, classes_visible_to_role cvtr ";
                $hodCondition = " AND  cvtr.groupId = gr.groupId
                                  AND  cvtr.classId = gr.classId
                                  AND  cvtr.classId = c.classId
                                  AND  cvtr.userId = $userId
                                  AND  cvtr.roleId = $roleId
                                  AND  c.classId IN $insertValue ";
            }
        }
        
        $query = "SELECT
						DISTINCT ttc.classId, className 
		          FROM	
                        time_table_classes ttc, class c $tableName
			      WHERE	
                        ttc.classId = c.classId
			            AND	ttc.timeTableLabelId = $labelId
                        $hodCondition
                        $classCondition1
                  ORDER BY 
                        c.degreeId,c.branchId,c.studyPeriodId";

		return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
	}


  //-------------------------------------------------------
    //  THIS FUNCTION IS to fetch the role
    //
    // Author :Jaineesh
    // Created on : (01-10-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
	public function getRoleUser($userId) {
		 $query = "
				SELECT
							COUNT(*) as totalRecords
				FROM		classes_visible_to_role cvtr,
							user_role ur
				WHERE		cvtr.userId = ur.userId
				AND			ur.userId = $userId
				AND			cvtr.userId = $userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//---------------------------------------------------------------------------------------------------------------------
// Function gets records for Percentage Attendance wise report
//
// Author :Arvind Singh Rawat
// Created on : 23-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getStudentDetailPercentageAttendance($filter='',$conditions='',$having='',$orderBy='studentName',$limit='')
    {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query=" SELECT
                                CONCAT(scs.firstName,' ',scs.lastName) AS studentName,
                                su.subjectName, su.subjectCode, scs.rollNo, sp.periodName,
                                d.degreeCode,
                                SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,
                                          (ac.attendanceCodePercentage /100), att.lectureAttended ) ) ) AS attended,
                                SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,
                                grp.groupName

                  FROM
                                student scs, ".ATTENDANCE_TABLE." att
                                INNER JOIN student_groups sg   ON   att.studentId = sg.studentId AND att.classId = sg.classId
                                INNER JOIN class c             ON   c.classId=att.classId
                                INNER JOIN degree d            ON   c.degreeId = d.degreeId
                                INNER JOIN study_period sp     ON   c.studyPeriodId = sp.studyPeriodId
                                LEFT  JOIN attendance_code ac  ON   (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = $instituteId)
                                INNER JOIN subject su          ON   su.subjectId = att.subjectId AND su.hasAttendance = 1
                                INNER JOIN `group` grp         ON   att.groupId = grp.groupId
                  WHERE
                                sg.studentId = scs.studentId AND
                                c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."  AND
                                c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                $conditions
                  GROUP BY
                                att.subjectId, sg.studentId, sg.classId, sg.groupId
                                $having
                  ORDER BY
                                $orderBy  $limit";
                  //having ((attended/delivered)*100) <40 "
                 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------------------
// Function gets records for class wise Subject fetch
//
// Author :Parveen Sharma
// Created on : 14-oct-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getClasswiseSubject($conditions='', $having='') {
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');


        $query="SELECT
                        DISTINCT att.studentId, att.classId, su.subjectId, su.subjectTypeId,
                        ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                        SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered,
                        IFNULL((SELECT
                                      SUM(IF(att1.isMemberOfClass=0,0,IF(att1.attendanceType=2,1,0))) AS dutyLeave
                                FROM
                                       ".DUTY_LEAVE_TABLE."  dl, ".ATTENDANCE_TABLE." att1
                                      LEFT JOIN  attendance_code ac1 ON
                                      (ac1.attendanceCodeId = att1.attendanceCodeId AND ac1.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                                WHERE
                                        att1.studentId = dl.studentId AND
                                        att1.classId = dl.classId AND
                                        att1.subjectId = dl.subjectId AND
                                        att1.groupId = dl.groupId AND
                                        att1.periodId  = dl.periodId AND
                                        att1.fromDate = dl.dutyDate AND
                                        att1.toDate = dl.dutyDate AND
                                        dl.studentId = att.studentId AND
                                        dl.classId = att.classId AND
                                        dl.subjectId = att.subjectId AND
                                        dl.rejected = ".DUTY_LEAVE_APPROVE."),0) AS leavesTaken
                FROM
                        ".ATTENDANCE_TABLE." att
                        LEFT  JOIN attendance_code ac  ON   (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$instituteId.")
                        INNER JOIN class c             ON   c.classId=att.classId
                        INNER JOIN degree d            ON   c.degreeId = d.degreeId
                        INNER JOIN study_period sp     ON   c.studyPeriodId = sp.studyPeriodId
                        INNER JOIN periodicity p       ON   p.periodicityId = sp.periodicityId
                        INNER JOIN `subject` su        ON   su.subjectId = att.subjectId
                        INNER JOIN `group` gr          ON   att.groupId = gr.groupId
                WHERE
                        c.sessionId = ".$sessionId."
                        AND  c.instituteId = ".$instituteId."
                $conditions
                GROUP BY
                      att.classId, att.studentId, su.subjectTypeId, su.subjectCode
                $having
                ORDER BY
                      att.classId, att.studentId, su.subjectTypeId, su.subjectCode " ;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------------------
// Function gets fetch student records
//
// Author :Parveen Sharma
// Created on : 14-oct-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getClasswiseStudent($conditions='',$orderBy=' classId, rollNo, studentId',$limit='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        DISTINCT sg.studentId, sg.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo
                  FROM
                        class c,  ".TIME_TABLE_TABLE."  tt, student_groups sg, student s, subject_to_class stc
                  WHERE
                        tt.groupId = sg.groupId        AND
                        tt.subjectId = stc.subjectId   AND
                        tt.instituteId = c.instituteId AND
                        tt.sessionId = c.sessionId     AND
                        sg.studentId = s.studentId     AND
                        sg.classId =  c.classId        AND
                        stc.classId = c.classId        AND
                        c.instituteId = $instituteId   AND
                        c.sessionId = $sessionId
                  $conditions
                  UNION
                  SELECT
                        DISTINCT s.studentId, ss.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo
                  FROM
                        class c,  ".TIME_TABLE_TABLE."  tt, student s, student_optional_subject ss
                  WHERE
                        ss.groupId = tt.groupId        AND
                        ss.subjectId = tt.subjectId    AND
                        tt.instituteId = c.instituteId AND
                        tt.sessionId = c.sessionId     AND
                        ss.studentId = s.studentId     AND
                        ss.classId =  c.classId        AND
                        c.instituteId = $instituteId   AND
                        c.sessionId = $sessionId
                        $conditions
                  ORDER BY
                        $orderBy
                  $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------------------------------------
// Function gets fetch student duty leaves
//
// Author :Parveen Sharma
// Created on : 14-oct-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------------------
    public function getStudentDutyLeaves($conditions='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                      att.classId, att.studentId, att.groupId, att.subjectId,
                      IF(att.isMemberOfClass=0,0,IF(att.attendanceType=2,1,0)) leavesTaken, a.dutyDate
                  FROM
                       ".DUTY_LEAVE_TABLE."  a, ".ATTENDANCE_TABLE." att
                      LEFT JOIN  attendance_code ac1 ON
                      (ac1.attendanceCodeId = att.attendanceCodeId AND ac1.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                  WHERE
                      att.studentId = a.studentId AND att.subjectId = a.subjectId AND
                      att.periodId = a.periodId AND att.groupId=a.groupId AND
                      (a.dutyDate >= att.fromDate  AND a.dutyDate <= att.toDate) AND
                      a.rejected= ".DUTY_LEAVE_APPROVE."
                      $conditions
                 ORDER BY
                      att.classId, att.studentId, att.subjectId ";

        /*$query = "SELECT
                        a.classId, a.studentId, a.groupId, a.subjectId, a.leavesTaken,
                        a.employeeId, a.userId, a.timeTableLabelId, a.dated
                  FROM
                        attendance_leave a, time_table_labels ttl
                  WHERE
                        a.timeTableLabelId  = ttl.timeTableLabelId AND
                        ttl.instituteId = $instituteId AND
                        ttl.sessionId = $sessionId AND
                        ttl.isActive = 1
                  $conditions
                  ORDER BY
                        a.classId, a.studentId, a.subjectId ";
         */
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //----------------------------------------------------------------------------------------------------
    //function created for fetching groups allocated to a subject

    // Author :Parveen Sharma
    // Created on : 12-04-08
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getSubjectwiseGroups($conditions='') {

         global $sessionHandler;

        $query = "SELECT
                          DISTINCT  g.groupId, g.groupName
                  FROM
                          `group` g, student_groups sg, subject_to_class sc
                  WHERE
                          sg.groupId=g.groupId    AND
                          sg.classId = sc.classId
                          $conditions
                  GROUP BY g.groupName
                  ORDER BY g.groupName";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//----------------------------------------------------------------------------------------------------
    //function created for fetching groups allocated to a subject

    // Author :Jaineesh
    // Created on : 12-04-08
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getTimeTableClass($conditions='') {

         global $sessionHandler;

         $query = "	SELECT
							distinct cl.classId
					FROM
							`class` cl,
							time_table_classes ttc,
							time_table_labels ttl
					WHERE
							cl.classId = ttc.classId
					AND		ttl.timeTableLabelId = ttc.timeTableLabelId
							$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//----------------------------------------------------------------------------------------------------
    //function created for fetching marks status report

    // Author :Ajinder Singh
    // Created on : 29-Dec-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
	public function getMarksStatusReport($labelId, $sortField, $sortOrderBy, $conditions = '', $limit = '') {
		$query = "
				SELECT
							ttc.classId,
							t.subjectId,
							cls.className,
							sub.subjectCode,
							emp.employeeName,
							gr.groupShort,
							concat(ttct.testTypeName,'-', t.testIndex) as testTypeName,
							t.testAbbr,
							t.maxMarks,
							(select count(tm.studentId) from ".TEST_MARKS_TABLE." tm where tm.testId = t.testId) as studentCount
				FROM
							time_table_classes ttc,
							class cls,
							".TEST_TABLE." t,
							subject sub,
							employee emp,
							`group` gr,
							test_type_category ttct
				WHERE		ttc.timeTableLabelId = $labelId
				AND			ttc.classId = cls.classId
				AND			ttc.classId = t.classId
				AND			t.subjectId = sub.subjectId
				AND			t.employeeId = emp.employeeId
				AND			t.testTypeCategoryId = ttct.testTypeCategoryId
				AND			t.groupId = gr.groupId
				$conditions
				ORDER BY	$sortField $sortOrderBy
				$limit
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getTTSubjectList($classId) {
		$query = "
				SELECT
				DISTINCT	a.subjectId, b.subjectCode
				FROM		subject_to_class a, subject b, `group` c,  ".TIME_TABLE_TABLE."  d
				WHERE		a.subjectId = b.subjectId
				AND			a.classId = $classId
				AND			a.classId = c.classId
				AND			c.groupId = d.groupId
				AND			a.subjectId = d.subjectId
				ORDER BY	b.subjectCode
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}

	public function getAttendanceStatusReport($labelId='', $sortField='', $sortOrderBy='', $conditions='', $limit='',$isTimeTableCheck='') {
		
        
        $query = "
				SELECT
							DISTINCT tt.groupId, g.groupShort, g.classId, cls.className, tt.subjectId, sub.subjectCode,
							(SELECT 
                                 MAX(toDate) 
                             FROM 
                                 ".ATTENDANCE_TABLE." 
                             WHERE 
                                 groupId = tt.groupId AND classId = g.classId AND subjectId = tt.subjectId) AS tillDate,
                            (SELECT 
                                 GROUP_CONCAT(DISTINCT CONCAT(employeeName,'(',employeeCode,')') ORDER BY employeeName ASC SEPARATOR ', ') 
                             FROM 
                                 employee e 
                             WHERE 
                                 e.employeeId = tt.employeeId) AS employeeName,
                             (SELECT 
                               GROUP_CONCAT(DISTINCT IF(ee.employeeId=att.employeeId,'---',CONCAT(ee.employeeName,'(',ee.employeeCode,')')) ORDER BY employeeName ASC SEPARATOR ', ')  
                              FROM 
                                 ".ATTENDANCE_TABLE." att, employee ee
                              WHERE 
                                 ee.userId = att.userId AND
                                 att.groupId = tt.groupId AND att.classId = g.classId AND att.subjectId = tt.subjectId AND att.employeeId = tt.employeeId
                             ) AS beHalfEmployeeName    
				FROM		 ".TIME_TABLE_TABLE."  tt, `group` g, class cls, `subject` sub
				WHERE		tt.timeTableLabelId = $labelId
                AND         tt.toDate IS NULL
				AND			tt.groupId = g.groupId
				AND			g.classId = cls.classId
				AND			tt.subjectId = sub.subjectId
				$conditions
				ORDER BY	$sortField $sortOrderBy
				$limit
		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    
    public function getLastNotAttendanceTaken($labelId, $sortField, $sortOrderBy, $conditions, $limit,$toDate='',$isTimeTableCheck='') {
        
      
       $query = "SELECT
                       DISTINCT tt.groupId, g.groupShort, g.classId, cls.className, tt.subjectId, sub.subjectCode, 
                       CONCAT(p.periodNumber,' ',p.startTime,p.startAmPm,'-',p.endTime,p.endAmPm) AS periodName,
                      (SELECT 
                           GROUP_CONCAT(DISTINCT CONCAT(employeeName,'(',employeeCode,')') ORDER BY employeeName ASC SEPARATOR ', ') 
                       FROM 
                           employee e 
                       WHERE 
                           e.employeeId = tt.employeeId) AS employeeName,
                       (SELECT 
                           GROUP_CONCAT(DISTINCT CONCAT(employeeName,'(',employeeCode,')') ORDER BY employeeName ASC SEPARATOR ', ') 
                        FROM 
                           employee ee
                        WHERE 
                           ee.userId = att.userId) AS beHalfEmployeeName
                FROM       
                       `group` g, `class` cls, `subject` sub, `period` p,
                        ".TIME_TABLE_TABLE."  tt 
                        LEFT JOIN ".ATTENDANCE_TABLE." att ON 
                        (tt.groupId = att.groupId AND tt.classId = att.classId AND tt.employeeId = att.employeeId AND 
                         tt.periodId = att.periodId AND tt.subjectId = att.subjectId AND 
                         att.fromDate = '$toDate' AND att.toDate = '$toDate')
                WHERE  
                      tt.timeTableLabelId LIKE '$labelId'
                      AND tt.periodId = p.periodId
                      AND tt.daysOfWeek LIKE DATE_FORMAT('$toDate','%w')
                      AND tt.toDate IS NULL
                      AND tt.groupId = g.groupId
                      AND g.classId = cls.classId
                      AND tt.subjectId = sub.subjectId
                      AND IFNULL(att.periodId,'')= ''
                $conditions
                ORDER BY    
                      $sortField $sortOrderBy $limit";
                      
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //----------------------------------------------------------------------------------------------------
    // function created for students bus pass
    // Author :Parveen Sharma
    // Created on : 12-01-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getAllBusPassDetails($conditions='', $order='', $limit='') {
        global $sessionHandler;

        $query = "SELECT
                           DISTINCT
                           CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                           IFNULL(a.rollNo,'') AS rollNo, IFNULL(a.regNo,'') AS regNo,
                           SUBSTRING_INDEX(c.className,'-',-3) AS className,
                           IFNULL(bpass.busPassId,'') AS busPassId, a.classId,
                           a.studentId , IFNULL(bpass.receiptNo,'') AS receiptNo,
                           IFNULL(bpass.validUpto,'0000-00-00') AS validUpto,
                           IFNULL(bpass.addedOnDate,'0000-00-00') AS addedOnDate,
                           IFNULL(bpass.cancelOnDate,'0000-00-00') AS cancelOnDate,
                           IF(IFNULL(bpass.busRouteId,0)=0,'".NOT_APPLICABLE_STRING."',(SELECT routeCode FROM bus_route broute WHERE broute.busRouteId=bpass.busRouteId)) AS routeCode,
                           IF(IFNULL(bpass.busStopId,0)=0,'".NOT_APPLICABLE_STRING."', (SELECT stopName  FROM bus_stop bstop   WHERE bstop.busStopId=bpass.busStopId)) AS stopName
                   FROM
                           class c, student a, bus_pass bpass
                   WHERE
                           a.classId = c.classId AND
                           bpass.studentId=a.studentId AND
                           bpass.busPassStatus = 1
                   $conditions
                   ORDER BY $order $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //----------------------------------------------------------------------------------------------------
    // function created for students bus pass
    // Author :Parveen Sharma
    // Created on : 12-01-2009
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getCountAllBusPassDetails($conditions='') {

        global $sessionHandler;

        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                       (SELECT
                               DISTINCT
                               CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                               IFNULL(a.rollNo,'') AS rollNo, IFNULL(a.regNo,'') AS regNo,
                               SUBSTRING_INDEX(c.className,'-',-3) AS className,
                               IFNULL(bpass.busPassId,'') AS busPassId, a.classId,
                               a.studentId , IFNULL(bpass.receiptNo,'') AS receiptNo,
                               IFNULL(bpass.validUpto,'0000-00-00') AS validUpto,
                               IFNULL(bpass.addedOnDate,'0000-00-00') AS addedOnDate,
                               IFNULL(bpass.cancelOnDate,'0000-00-00') AS cancelOnDate,
                               IF(IFNULL(bpass.busRouteId,0)=0,'".NOT_APPLICABLE_STRING."',(SELECT routeCode FROM bus_route broute WHERE broute.busRouteId=bpass.busRouteId)) AS routeCode,
                               IF(IFNULL(bpass.busStopId,0)=0,'".NOT_APPLICABLE_STRING."', (SELECT stopName  FROM bus_stop bstop   WHERE bstop.busStopId=bpass.busStopId)) AS stopName
                       FROM
                               class c, student a, bus_pass bpass
                       WHERE
                               a.classId = c.classId AND
                               bpass.studentId=a.studentId AND
                               bpass.busPassStatus = 1
                       $conditions) AS t";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getAllSubjectList($classId) {
		 
         $query = "SELECT 
                        DISTINCT a.subjectId, b.subjectCode   
                   FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a, `subject` b 
                   WHERE 
                        a.subjectId = b.subjectId AND a.classId = $classId 
                   UNION 
                   SELECT 
                        DISTINCT a.subjectId, b.subjectCode 
                   FROM 
                        subject_to_class a, `subject` b 
                   WHERE 
                        a.subjectId = b.subjectId AND a.classId = $classId AND a.hasParentCategory = 0 
                   UNION 
                   SELECT 
                        DISTINCT a.subjectId, b.subjectCode 
                   FROM 
                        student s, student_optional_subject a, `subject` b 
                   WHERE 
                        s.studentId = a.studentId  AND a.subjectId = b.subjectId AND a.classId = $classId 
                   ORDER BY 
                        subjectCode";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }
     
      public function getAllSubjectGradeList($classId) {
         
         $query = "SELECT 
                        DISTINCT a.subjectId, b.subjectCode   
                   FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a, `subject` b 
                   WHERE 
                        a.subjectId = b.subjectId AND a.classId = $classId 
                   ORDER BY 
                        subjectCode";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }
     
    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR GETTING Teacher subjects
    //
    // Author :Parveen Sharma
    // Created on : (12.07.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------------------------------------------------
    public function getClassSubjects($condition='') {
        global $sessionHandler;

        $query = "SELECT
                          DISTINCT
                                   a.subjectId, d.classId, b.subjectName, b.subjectCode, b.hasAttendance, b.hasMarks
                  FROM
                          ".TIME_TABLE_TABLE."  a, `subject` b, `group` c, class d,
                         employee e
                  WHERE
                         a.subjectId = b.subjectId
                         AND  a.groupId = c.groupId
                         AND  c.classId = d.classId
                         AND  a.employeeId = e.employeeId
                  $condition
                  ORDER BY c.classId, b.subjectName, b.subjectCode, b.subjectId ";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------
//funciton return records for student attendance
// Author :Parveen Sharma
// Created on : 16-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function getStudentAttendanceData($fieldName='', $condition='', $orderBy='', $limit='',$conslidated='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $groupBy = "";
        if($fieldName=='') {
            $fieldName = "  att.classId, att.subjectId, att.groupId, att.studentId,
                            IFNULL(att.periodId,'-1') AS periodId, gt.groupTypeId,
                            IF(att.isMemberOfClass=0,-2,IFNULL(att.attendanceCodeId,'-1')) AS attendanceCodeId,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                            IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                            att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber,
                            SUM(IF(att.isMemberOfClass=0, 0,att.lectureDelivered)) AS lectureDelivered,
                            SUM(IF(att.isMemberOfClass=0, 0,att.lectureAttended)) AS lectureAttended,
                            IF(att.isMemberOfClass=0,-2,IFNULL(ac.attendanceCode,'-1')) AS attendanceCode,
                            SUM(IF(att.isMemberOfClass=0,0,IFNULL(ac.attendanceCodePercentage/100,'-1'))) AS attendanceCodePercentage,
                            SUM(IF(att.isMemberOfClass =0,0,IF(att.attendanceType=2,(ac.attendanceCodePercentage /100),att.lectureAttended))) AS attended";
            if($conslidated=='') {
               $groupBy = " GROUP BY att.classId, att.subjectId, att.groupId, att.studentId, att.fromDate, att.periodId";
            }
            else {
               $groupBy = " GROUP BY att.classId, att.subjectId, att.groupId, att.studentId, att.fromDate, att.periodId";
            }
        }

     $query = "SELECT
                        $fieldName
                  FROM
                       group_type gt, `group` grp, class c, `subject` su,
                       student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                       LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                       LEFT JOIN period p ON att.periodId = p.periodId
                  WHERE
                        gt.groupTypeId = grp.groupTypeId  AND
                        att.groupId   = grp.groupId       AND
                        att.subjectId = su.subjectId      AND
                        att.classId   = c.classId         AND
                        c.instituteId = $instituteId      AND
                        c.sessionId = $sessionId
                  $condition
                  $groupBy
                  ORDER BY
                        $orderBy
                  $limit";
				  

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//funciton return records for student attendance
// Author :Parveen Sharma
// Created on : 16-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function getTotalDeliveredAttendance($condition='',$field='',$groupBy='',$filter='',$groupBy1='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                           $filter
                  FROM
                          (SELECT
                                $field
                           FROM
                               group_type gt, `group` grp, class c, `subject` su,
                               student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                               LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                               LEFT JOIN period p ON att.periodId = p.periodId
                           WHERE
                                gt.groupTypeId = grp.groupTypeId AND
                                att.groupId   = grp.groupId      AND
                                att.subjectId = su.subjectId     AND
                                att.classId   = c.classId        AND
                                c.instituteId = $instituteId     AND
                                c.sessionId = $sessionId
                           $condition
                           GROUP BY
                                  $groupBy) AS tt
                   $groupBy1 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//funciton return records for attendance short report.
// Author :Parveen Sharma
// Created on : 16-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------
     public function getAttendanceThresholdRecords($fieldName='',$condition='',$having='',$orderBy=' att.classId, att.subjectId  ',$limit='') {

         global $sessionHandler;

          $instituteId        =    $sessionHandler->getSessionVariable('InstituteId');

         /*$fieldName= "ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 ,
                                 IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                          SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered,
                          IF(ROUND(SUM(IF(att.isMemberOfClass=0,0, att.lectureDelivered))*100,2)<=0,0,
                                 ROUND(SUM(IF(att.isMemberOfClass=0,0, IF(att.attendanceType =2,
                                       (ac.attendanceCodePercentage /100), att.lectureAttended )) ) /
                          SUM(IF(att.isMemberOfClass=0,0, att.lectureDelivered))*100,2)) AS percentage";
          */

          $query = "SELECT
                                 $fieldName
                    FROM
                                `group` f, employee e, subject sub, class cls, student stu, student_groups sg
                                INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = sg.studentId and att.classId = sg.classId)
                    LEFT JOIN
                                attendance_code ac
                    ON          (ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
                    WHERE       sg.studentId = stu.studentId
                    AND         sg.classId = cls.classId
                    AND         att.subjectId = sub.subjectId
                    AND         att.employeeId = e.employeeId
                    AND         att.groupId = f.groupId
                    $condition
                    GROUP BY
                            sg.studentId, att.subjectId,sg.groupId
                    $having
                    ORDER BY
                        $orderBy $limit";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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

    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR fetching Subject
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getSubjectData($conditions='',$orderBy=' sub.subjectCode') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        DISTINCT sub.subjectId ,sub.subjectName,sub.subjectCode
                  FROM
                        `class` c, `subject_to_class` subTocls, `subject` sub, student_groups sss
                  WHERE
                        sss.classId = c.classId              AND
                        c.classId = subTocls.classId         AND
                        c.instituteId='".$instituteId."'     AND
                        sss.instituteId='".$instituteId."'   AND
                        sub.subjectId = subTocls.subjectId
                  $conditions
                  ORDER BY $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR fetching Student List (Final Marks Result)
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getFoxproStudentInfo($conditions='',$orderBy=' universityRollNo') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        DISTINCT
                        s.studentId, IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        IF(s.studentEmail='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                        IF(s.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',
                        (SELECT cityName FROM city WHERE cityId = s.corrCityId)) AS corrCityId,
                        CONCAT(IFNULL(s.firstName,''), ' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        br.branchName, ins.instituteName
                  FROM
                        student s LEFT JOIN student_groups sg ON sg.studentId = s.studentId
                        LEFT JOIN class cls ON cls.classId = sg.classId
                        LEFT JOIN branch br ON br.branchId = cls.branchId
                        LEFT JOIN institute ins ON ins.instituteId = cls.instituteId
                  WHERE
                        ins.instituteId='".$instituteId."'
                  $conditions
                  ORDER BY $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR fetching Subject List (Final Marks Result)
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getFoxproSubjectInfo($conditions='',$orderBy=' a.subjectId') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        DISTINCT
                                a.subjectId, a.subjectCode, a.subjectName,
                                a.hasAttendance, a.hasMarks
                  FROM
                        `subject` a, ".TOTAL_TRANSFERRED_MARKS_TABLE." b
                  WHERE
                         a.subjectId = b.subjectId  AND
                         b.conductingAuthority IN (1,3)
                  $conditions
                  ORDER BY $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR fetching Final Marks
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getFoxproFinalMarksInfo($conditions='',$orderBy=' ttm1.classId, ttm1.studentId, ttm1.subjectId') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                         ttm1.classId, ttm1.studentId, ttm1.subjectId,
                         SUM(IFNULL(ttm1.marksScored,0))+IFNULL(tgm.graceMarks,0) AS marksScored
                  FROM
                         `subject` sub, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm1 LEFT JOIN  ".TEST_GRACE_MARKS_TABLE." tgm ON
                         ttm1.studentId = tgm.studentId AND ttm1.subjectId = tgm.subjectId AND ttm1.classId = tgm.classId
                  WHERE
                         sub.subjectId = ttm1.subjectId     AND
                         ttm1.conductingAuthority IN (1,3)
                  $conditions
                  GROUP BY
                         ttm1.classId, ttm1.studentId, ttm1.subjectId
                  ORDER BY
                         ttm1.classId, ttm1.studentId, ttm1.subjectId";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }




    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR Student Rankwise Report
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getStudentRankWise($conditions='',$orderBy='',$limit='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT
                        DISTINCT s.studentId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo, c.className AS className,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        IFNULL(compExamBy,'') AS compExamBy, IFNULL(compExamRollNo,'') AS compExamRollNo,
                        IFNULL(compExamRank,'') AS compExamRank
                  FROM
                        class c, student s
                  WHERE
                       s.classId = c.classId AND
                       c.instituteId = $instituteId AND
                       c.sessionId = $sessionId
                  $conditions
                  $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR Student Rankwise Report Count
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getStudentRankWiseCount($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');

        $query = " SELECT
                           COUNT(*) AS cnt
                   FROM
                       (SELECT
                            DISTINCT s.studentId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                            IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                            IFNULL(compExamBy,'') AS compExamBy, IFNULL(compExamRollNo,'') AS compExamRollNo,
                            IFNULL(compExamRank,'') AS compExamRank
                        FROM
                            class c, student s
                       WHERE
                           s.classId = c.classId AND
                           c.instituteId = $instituteId AND
                           c.sessionId = $sessionId
                       $conditions) AS t";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR Student Rankwise Report
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getStudentAcademic($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT
                        DISTINCT ac.studentId, ac.previousClassId, ac.previousRollNo, ac.previousSession,
                        ac.previousInstitute, ac.previousBoard, ac.previousMarks, ac.previousMaxMarks,
                        ac.previousPercentage, ac.previousEducationStream
                  FROM
                        student_academic ac
                  WHERE
                        ac.studentId IN (SELECT
                                                DISTINCT studentId
                                         FROM
                                                student_groups sg
                                         WHERE
                                                sg.instituteId = $instituteId AND
                                                sg.sessionId = $sessionId
                                         $conditions)
                  ORDER BY ac.studentId, ac.previousClassId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR types of test taken on this subjects
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------

    public function getSubjctWiseTestType($conditions='',$filter='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');


        if($filter=='') {
           $filter = " DISTINCT  a.testTypeCategoryId, b.testTypeName, a.testDate, s.subjectId, s.subjectCode";
        }

        $query = "SELECT
                         $filter
                    FROM
                         ".TEST_TABLE." a, test_type_category b,  ".TEST_MARKS_TABLE." c, `subject` s
                    WHERE
                         s.subjectId = a.subjectId  AND
                         a.instituteId = $instituteId AND
                         a.sessionId   = $sessionId   AND
                         a.testTypeCategoryId = b.testTypeCategoryId AND
                         a.testId = c.testId
                    $conditions
                    GROUP BY
                         a.classId, s.subjectTypeId, s.subjectCode,  a.testTypeCategoryId
                    ORDER BY
                         a.classId, s.subjectTypeId, s.subjectCode,  a.testTypeCategoryId";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    //----------------------------------------------------------------------------------------------------
    //function created for fetching all tests by testTypeId

    // Author :Ajinder Singh
    // Created on : 09-Aug-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getTestWiseDetails($conditions='',$fieldName='',$groupBy='', $orderBy='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                          DISTINCT
                                    a.testId, a.classId, a.subjectId, c.testTypeName, CONCAT(c.testTypeAbbr, '-',a.testIndex) AS testName,
                                    GROUP_CONCAT(DISTINCT gt.groupTypeId ORDER BY gt.groupTypeId DESC) AS cnt, COUNT(DISTINCT gt.groupTypeId) AS cnt1
                                    $fieldName
                  FROM
                          ".TEST_TABLE." a, ".TEST_MARKS_TABLE." b, test_type_category c,
                          `group` g, group_type gt, `subject` s
                  WHERE
                          s.subjectId = a.subjectId AND
                          g.groupTypeId = gt.groupTypeId AND
                          a.groupId = g.groupId AND
                          c.testTypeCategoryId = a.testTypeCategoryId AND
                          a.instituteId = $instituteId AND
                          a.sessionId   = $sessionId   AND
                          a.subjectId = b.subjectId AND
                          a.testId = b.testId
                          $conditions
                  GROUP BY
                          a.classId, s.subjectTypeId, s.subjectCode, a.testTypeCategoryId, testName $groupBy
                  ORDER BY
                          a.classId, s.subjectTypeId, s.subjectCode, a.testTypeCategoryId, testName $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    //----------------------------------------------------------------------------------------------------
    //function created for fetching all tests by testTypeId

    // Author :Ajinder Singh
    // Created on : 09-Aug-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getStudentTestDetails($conditions='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        DISTINCT
                        c.testTypeCategoryId,
                        CONCAT(c.testTypeAbbr, '-',tm.testIndex) AS testName,
                        tm1.studentId, tm.subjectId, tm.groupId, gt.groupTypeId,
                        tm1.marksScored, tm1.maxMarks,
                        tm.maxMarks AS maxMarks2,
                        IF(CONCAT(tm1.isPresent,tm1.isMemberOfClass)=11,tm1.marksScored,
                            IF(CONCAT(tm1.isPresent,tm1.isMemberOfClass)=01,'A','N/A')) AS marks
                  FROM
                        ".TEST_MARKS_TABLE." tm1, ".TEST_TABLE." tm, test_type_category c, `group` g, group_type gt, `subject` s
                  WHERE
                        tm.subjectId = s.subjectId AND
                        tm.groupId = g.groupId AND
                        g.groupTypeId = gt.groupTypeId AND
                        tm.instituteId = $instituteId AND
                        tm.sessionId   = $sessionId   AND
                        c.testTypeCategoryId = tm.testTypeCategoryId AND
                        tm.testId = tm1.testId        AND
                        tm.subjectId = tm1.subjectId
                  $conditions
                  ORDER BY
                        tm.classId, tm1.studentId, s.subjectTypeId, s.subjectCode, c.testTypeCategoryId, testName, gt.groupTypeId DESC";
		

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

//----------------------------------------------------------------------------------------------------
//funciton return records for student attendance
// Author :Parveen Sharma
// Created on : 16-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function getDeliveredLectureCount($condition='',$fieldName='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');


        if($fieldName!='') {
           $field1=", tt1.groupName";
           $field2=", tt.groupName";
           $field3=", grp.groupName";
           $field4=" GROUP BY tt1.groupName";
        }

        $query = "SELECT
                           MAX(IFNULL(tt1.delivered,0)) AS cnt $field1
                  FROM
                         (SELECT
                                  tt.studentId, SUM(IFNULL(tt.delivered,0)) AS delivered $field2
                          FROM
                              (SELECT
                                    att.classId, att.subjectId, att.groupId, att.studentId, att.periodId, att.fromDate,
                                    MAX(IF(att.isMemberOfClass=0, 0,att.lectureDelivered)) AS  delivered $field3
                               FROM
                                   group_type gt, `group` grp, class c, `subject` su, student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                                   LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                                   LEFT JOIN period p ON att.periodId = p.periodId
                                   LEFT JOIN employee e ON e.employeeId = att.employeeId
                               WHERE
                                    gt.groupTypeId = grp.groupTypeId    AND
                                    att.groupId   = grp.groupId    AND
                                    att.subjectId = su.subjectId   AND
                                    att.classId   = c.classId      AND
                                    c.instituteId = $instituteId   AND
                                    c.sessionId = $sessionId
                               $condition
                               GROUP BY
                                      att.classId, att.subjectId, att.fromDate $field3, att.groupId, att.periodId, att.studentId ) AS tt
                          GROUP BY
                                  tt.studentId $field2) AS tt1
                    $field4 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTimeTableClassTeachers($labelId,$classId,$conditions='') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "
                    SELECT
                            DISTINCT e.employeeId,e.employeeName
                    FROM
                            employee e,class c,`group` g, ".TIME_TABLE_TABLE."  t,".ATTENDANCE_TABLE." att
                    WHERE
                            c.classId=g.classId
                            AND g.groupId=t.groupId
                            AND t.employeeId=e.employeeId
                            AND t.employeeId=att.employeeId
                            AND t.groupId=att.groupId
                            AND c.classId=att.classId
                            AND t.timeTableLabelId=$labelId
                            AND c.classId=$classId
                            AND t.sessionId=$sessionId
                            AND t.instituteId=$instituteId
                            $conditions
                    ORDER BY e.employeeName
                    ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------
//this function is used to calculate attendance summery of a teacher
//-------------------------------------------------------------------------------------------
public function getTeacherAttendanceSummeryList($filter,$limit='',$orderBy='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        subjectCode,subjectName,groupName, employeeName,className, MAX( lectureDelivered ) AS totalDelivered, attendanceTaken, adjustmentTaken
                        FROM (
                                SELECT
                                        s.subjectCode,
                                        s.subjectName,
                                        SUM( att.lectureDelivered ) AS lectureDelivered,
                                        c.className,
                                        e.employeeName,
                                        g.groupName,
                                        g.groupShort,
                                        att.classId,att.subjectId,att.employeeId,att.groupId,
                                        SUM(IF(e.userId=att.userId,1,0)) AS attendanceTaken,
                                        SUM(IF(e.userId=att.userId,0,1)) AS adjustmentTaken
                                FROM
                                       ".ATTENDANCE_TABLE." att, time_table_classes ttc, subject s,class c,employee e,
                                       `group` g
                                WHERE
                                        att.classId = ttc.classId
                                        AND c.classId=g.classId
                                        AND g.groupId=att.groupId
                                        AND c.instituteId = $instituteId
                                        AND c.sessionId = $sessionId
                                        AND att.subjectId = s.subjectId
                                        AND att.classId=c.classId
                                        AND ttc.classId=c.classId
                                        AND att.employeeId=e.employeeId
                                        $filter
                                        GROUP BY att.studentId,att.employeeId,att.subjectId
                            ) AS t
                        GROUP BY  classId,employeeId,subjectId,groupId
                        $orderBy,subjectId,groupId,employeeId
                        $limit";

                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------------------------------------------------------------------------------------------

public function getTeacherAttendanceSummeryListClassWise($filter,$limit='',$orderBy='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        subjectCode,subjectName,groupName, employeeName,className, MAX( lectureDelivered ) AS totalDelivered
                        FROM (
                                SELECT
                                        s.subjectCode,
                                        s.subjectName,
                                        SUM( att.lectureDelivered ) AS lectureDelivered,
                                        c.className,
                                        e.employeeName,
                                        g.groupName,
                                        g.groupShort,
                                        att.classId,att.subjectId,att.employeeId,att.groupId
                                FROM
                                       ".ATTENDANCE_TABLE." att, time_table_classes ttc, subject s,class c,employee e,
                                       `group` g
                                WHERE
                                        att.classId = ttc.classId
                                        AND c.classId=g.classId
                                        AND g.groupId=att.groupId
                                        AND c.instituteId = $instituteId
                                        AND c.sessionId = $sessionId
                                        AND att.subjectId = s.subjectId
                                        AND att.classId=c.classId
                                        AND ttc.classId=c.classId
                                        AND att.employeeId=e.employeeId
                                        $filter
                                        GROUP BY att.studentId,att.employeeId,att.subjectId
                            ) AS t
                        GROUP BY  classId,employeeId,subjectId
                        $orderBy
                        $limit";
    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	 public function getStudents($classId, $subjectId, $tableName, $groupId, $sortBy, $limit='') {
		 $condition = "";
		 if ($groupId != 'all') {
			 $condition = " AND a.studentId in (select distinct studentId from $tableName where groupId = $groupId)";
		 }
		 $query = "SELECT DISTINCT a.studentId, a.rollNo, concat(a.firstName,' ',a.lastName) as studentName, a.universityRollNo from student a, ".TOTAL_TRANSFERRED_MARKS_TABLE." b where a.studentId = b.studentId AND b.classId = $classId and b.subjectId = $subjectId $condition ORDER BY $sortBy $limit";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function countStudents($classId, $subjectId, $tableName, $groupId) {
		 $condition = "";
		 if ($groupId != 'all') {
			 $condition = " AND a.studentId in (select distinct studentId from $tableName where groupId = $groupId)";
		 }
		 $query = "SELECT COUNT(DISTINCT(a.studentId)) AS cnt from student a, ".TOTAL_TRANSFERRED_MARKS_TABLE." b where a.studentId = b.studentId AND b.classId = $classId and b.subjectId = $subjectId $condition";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function countClassStudents($classId,$groupCondition='') {
		 $query = "select count(studentId) as cnt from 
                   (SELECT DISTINCT(a.studentId) from student_groups a, student b 
                    where a.studentId = b.studentId and a.classId = $classId  $groupCondition
                    UNION 
                    SELECT DISTINCT(a.studentId) from student_optional_subject a, student b 
                    where a.studentId = b.studentId and a.classId = $classId $groupCondition) as t ";
                    
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getClassStudents($classId, $sortBy, $limit = '',$groupCondition='') {
		 $query = "SELECT
                         t.studentId, s.rollNo, s.universityRollNo, 
                         CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, 
                         s.fatherName 
                   FROM 
                        (SELECT 
                                DISTINCT(a.studentId) 
                         FROM 
                                student_groups a, student b 
                         WHERE
                                a.studentId = b.studentId and a.classId = $classId 
                                $groupCondition
                         UNION 
                         SELECT 
                                DISTINCT(a.studentId) 
                         FROM 
                                student_optional_subject a, student b 
                         WHERE 
                                a.studentId = b.studentId and a.classId = $classId
                                $groupCondition
                         ) as t, student s 
                                
                   WHERE 
                         t.studentId = s.studentId 
                   ORDER BY 
                         $sortBy $limit";
                         
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getInternalMarks($studentIdList, $classId, $subjectId) {
         
         if($studentIdList=='') {
           $studentIdList='0';  
         }
         
         if($subjectId=='') {
           $subjectId='0';  
         }
         
		 $query = "SELECT 
                         a.marksScoredStatus, a.studentId, a.subjectId, round(sum(a.marksScored) + ifnull(b.graceMarks,0),1) as marksScored,  
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, gr.gradeSetId, IFNULL(gr.failGrade,'') AS failGrade
                   FROM 
                         subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN ".TEST_GRACE_MARKS_TABLE." b ON  a.subjectId = b.subjectId and a.studentId = b.studentId AND a.classId = b.classId
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        sc.optional=0 AND
                        sc.classId = a.classId AND
                        sc.subjectId = a.subjectId AND
                        a.classId = '$classId' and a.subjectId IN ($subjectId) and 
                        a.studentId in ($studentIdList) and a.conductingAuthority IN (1,3) and 
                        a.classId = '$classId'  
                   GROUP BY a.studentId, a.subjectId
                   UNION
                   SELECT 
                         a.marksScoredStatus, a.studentId, a.subjectId, round(sum(a.marksScored) + ifnull(b.graceMarks,0),1) as marksScored,  
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, gr.gradeSetId, IFNULL(gr.failGrade,'') AS failGrade
                   FROM 
                         optional_subject_to_class oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN ".TEST_GRACE_MARKS_TABLE." b ON  a.subjectId = b.subjectId and a.studentId = b.studentId AND a.classId = b.classId
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        oc.classId = sc.classId AND sc.optional=1 AND 
                        oc.parentOfSubjectId = sc.subjectId AND
                        oc.subjectId IN  ($subjectId) AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.classId = '$classId' and a.subjectId IN ($subjectId) and 
                        a.studentId in ($studentIdList) and a.conductingAuthority IN (1,3) and 
                        a.classId = '$classId'  
                   GROUP BY a.studentId, a.subjectId
                   UNION
                   SELECT 
                         a.marksScoredStatus, a.studentId, a.subjectId, round(sum(a.marksScored) + ifnull(b.graceMarks,0),1) as marksScored,  
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, gr.gradeSetId, IFNULL(gr.failGrade,'') AS failGrade
                   FROM 
                         student_optional_subject oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN ".TEST_GRACE_MARKS_TABLE." b ON  a.subjectId = b.subjectId and a.studentId = b.studentId  AND a.classId = b.classId
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        oc.classId = sc.classId AND sc.optional=1 AND 
                        oc.subjectId = sc.subjectId AND
                        oc.subjectId IN ($subjectId) AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.classId = '$classId' and a.subjectId IN ($subjectId) and 
                        a.studentId in ($studentIdList) and a.conductingAuthority IN (1,3) and 
                        a.classId = '$classId' AND a.studentId = oc.studentId 
                   GROUP BY a.studentId, a.subjectId";
                   
                  
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }     

	 public function getExternalMarks($studentIdList, $classId, $subjectId) {
		 
         $query = "SELECT 
                        a.marksScoredStatus, a.studentId, a.subjectId, round(sum(a.marksScored),1) as marksScored,
                        IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                        IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, gr.gradeSetId, IFNULL(gr.failGrade,'') AS failGrade 
                   FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a
                        LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId 
                        LEFT JOIN subject_to_class sc ON sc.subjectId = a.subjectId AND sc.classId = a.classId
                   WHERE 
                        a.classId = $classId and a.subjectId IN ($subjectId) and 
                        a.studentId in ($studentIdList) and a.conductingAuthority IN (2) 
                   GROUP BY 
                        a.studentId, a.subjectId";
                        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getTotalStudentMarks($studentIdList, $classId, $subjectId) {
		 $query = "SELECT a.studentId, a.subjectId, round(sum(a.marksScored) + ifnull(b.graceMarks,0),1) as marksScored  from ".           
                     TOTAL_TRANSFERRED_MARKS_TABLE." a 
                     LEFT JOIN ".TEST_GRACE_MARKS_TABLE." b ON a.subjectId = b.subjectId and a.studentId = b.studentId  AND a.classId = b.classId
                     where a.classId = $classId and a.subjectId IN ($subjectId) and a.studentId in ($studentIdList)  
                     and a.classId = $classId  group by a.studentId, a.subjectId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getAttendance($studentIdList, $classId, $subjectId) {
		 global $sessionHandler;
		 $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 $query = "SELECT studentId, subjectId, SUM(IF(att.isMemberOfClass=0, 0,att.lectureDelivered)) as lectureDelivered, FORMAT(SUM(IF(att.isMemberOfClass=0,0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ))),1) as lectureAttended FROM ".ATTENDANCE_TABLE." att LEFT JOIN attendance_code ac ON  (ac.attendanceCodeId = att.attendanceCodeId  and ac.instituteId = $instituteId) where att.classId = $classId and att.subjectId IN ($subjectId) and att.studentId in ($studentIdList) group by att.studentId, att.subjectId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	//----------------------------------------------------------------------------------------------------
	//function created for fetching subjects for a class for which marks have been transferred

	// Author :Ajinder Singh
	// Created on : 20-oct-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getClassMarksTotalSubjects($classIdList) {
		$query = "
					SELECT
								DISTINCT(a.subjectId),
								b.subjectCode,
								substring(b.subjectName,1,30) as subjectName
					FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." a, subject b
					WHERE		a.subjectId = b.subjectId
					AND			a.classId IN ($classIdList)
					";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
	//----------------------------------------------------------------------------------------------------
	//function created for fetching test marks data

	// Author :Ajinder Singh
	// Created on : 28-nov-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function getSubjectMarks($classIdList,$subjectId) {
		$query = "
				SELECT
							DISTINCT
                                    (SELECT DISTINCT MAX(a.maxMarks) FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." a, student b 
                                     WHERE a.subjectId = $subjectId and a.classId IN ($classIdList) and a.conductingAuthority = 1 and a.studentId = b.studentId) as internal, 
                                    (SELECT DISTINCT MAX(a.maxMarks) FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." a, student b 
                                     WHERE a.subjectId = $subjectId and a.classId IN ($classIdList) and a.conductingAuthority = 2 and a.studentId = b.studentId) AS externalMarks, 
                                    (SELECT DISTINCT MAX(a.maxMarks) FROM ".TOTAL_TRANSFERRED_MARKS_TABLE." a, student b 
                                     WHERE a.subjectId = $subjectId and a.classId IN ($classIdList) and a.conductingAuthority = 3  and a.studentId = b.studentId) as attendance
				from 	".TOTAL_TRANSFERRED_MARKS_TABLE." c, student d
				WHERE	c.conductingAuthority IN (1,2,3)
				AND	c.classId IN ($classIdList)
				AND	c.subjectId = $subjectId
				AND	c.studentId = d.studentId
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}

	//----------------------------------------------------------------------------------------------------
	//function created for fetching test marks data

	// Author :Ajinder Singh
	// Created on : 28-nov-2008
	// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
	//
	//----------------------------------------------------------------------------------------------------
	public function countTotalMarksData($classIdList, $conditions = '') {
		$query = "
					SELECT
								COUNT(DISTINCT(a.studentId)) AS count
								$conditions
					FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." a, student d
					WHERE		a.classId in ($classIdList)
					AND			a.studentId = d.studentId
				 ";
		return SystemDatabaseManager::getInstance()->executeQuery($query);
	}
	 public function getInternalMaxMarks($classId, $subjectId) {
		$query = "select t.subjectId, max(t.maxMarks) as maxMarks from (
		SELECT  subjectId, studentId, sum(maxMarks) as maxMarks from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId  IN ($subjectId) and conductingAuthority IN (1,3) group by studentId, subjectId) as t group by t.subjectId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getExternalMaxMarks($classId, $subjectId) {
		$query = "select t.subjectId, max(t.maxMarks) as maxMarks from (
		SELECT  subjectId, studentId, sum(maxMarks) as maxMarks from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId  IN ($subjectId) and conductingAuthority IN (2) group by studentId, subjectId) as t group by t.subjectId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getTotalMaxMarks($classId, $subjectId) {
		$query = "select t.subjectId, max(t.maxMarks) as maxMarks from (
		SELECT  subjectId, studentId, sum(maxMarks) as maxMarks from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId  IN ($subjectId) group by studentId, subjectId) as t group by t.subjectId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getFinalMaxMarks($classId, $subjectId) {
		 $query = "select max(u.maxMarks) as finalMaxMarks from (select sum(t.maxMarks) as maxMarks from (SELECT  subjectId, studentId, sum(maxMarks) as maxMarks from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId  IN ($subjectId)  group by studentId, subjectId) as t group by t.studentId) as u";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }


	 public function getFinalMarksScored($studentIdList, $classId, $subjectId) {
		 $query = "SELECT a.studentId, round(sum(a.marksScored) + ifnull(b.graceMarks,0),1) as marksScored from ".TOTAL_TRANSFERRED_MARKS_TABLE." a, ".TEST_GRACE_MARKS_TABLE." b where a.classId = $classId and a.subjectId IN ($subjectId) and a.studentId in ($studentIdList) and a.classId = $classId and a.subjectId = b.subjectId and a.studentId = b.studentId group by a.studentId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

     public function getInternalExternalSubjectMarks($degreeId, $subjectId) {  
           
         $query = "SELECT 
                            DISTINCT sub.subjectId, sub.subjectName, sub.subjectCode, stc.externalTotalMarks, stc.internalTotalMarks 
                    FROM 
                            `subject` sub, subject_to_class stc
                    WHERE    
                            stc.optional=0 AND stc.classId = '$degreeId' AND stc.subjectId IN ($subjectId) 
                            AND sub.subjectId = stc.subjectId 
                    UNION
                    SELECT 
                            DISTINCT sub.subjectId, sub.subjectName, sub.subjectCode, stc.externalTotalMarks, stc.internalTotalMarks 
                    FROM 
                           `subject` sub, subject_to_class stc, optional_subject_to_class oc 
                    WHERE    
                            sub.subjectId = oc.subjectId AND
                            oc.classId = stc.classId AND stc.optional=1 AND 
                            oc.parentOfSubjectId = stc.subjectId AND
                            oc.subjectId IN ($subjectId) AND
                            stc.classId = '$degreeId'  
                    UNION
                    SELECT 
                            DISTINCT sub.subjectId, sub.subjectName, sub.subjectCode, stc.externalTotalMarks, stc.internalTotalMarks 
                    FROM 
                            `subject` sub, subject_to_class stc, student_optional_subject oc 
                    WHERE    
                            oc.classId = stc.classId AND stc.optional=1 AND 
                            oc.subjectId = stc.subjectId AND
                            oc.subjectId IN  ($subjectId) AND
                            stc.classId = '$degreeId' AND 
                            sub.subjectId = oc.subjectId ";
         
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
     }
     
     
     public function getStudentExteralMarks($studentIdList, $classId, $subjectId) {
         
         if($studentIdList=='') {
           $studentIdList='0';  
         }
         
         $query = "SELECT 
                          ttm.classId, ttm.studentId, ttm.subjectId, 
                          IFNULL(SUM(maxMarks),'".NOT_APPLICABLE_STRING."') AS maxMarks, 
                          IFNULL(SUM(marksScored),'".NOT_APPLICABLE_STRING."') AS marksScored
                   FROM 
                          ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
                   WHERE 
                          ttm.classId = '$classId' AND 
                          ttm.subjectId = '$subjectId' AND 
                          ttm.studentId IN ($studentIdList) AND
                          ttm.conductingAuthority IN (2)  
                   GROUP BY 
                          ttm.classId, ttm.studentId, ttm.subjectId ";
                          
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }

	 public function getLectureDeliveredSum($studentIdList, $classId, $subjectId) {
         
         if($studentIdList=='') {
           $studentIdList='0';  
         }
         
		 $query = "SELECT 
                          att.studentId, 
                          SUM(IF(isMemberOfClass=0,0, lectureDelivered))  as lectureDelivered  
                   FROM 
                          ".ATTENDANCE_TABLE." att, `group` grp 
                   WHERE 
                          grp.classId = att.classId AND
                          grp.groupId = att.groupId AND
                          att.classId = '$classId' and att.subjectId = '$subjectId' and att.studentId IN ($studentIdList) 
                   GROUP BY 
                          att.studentId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }
	 public function getLectureAttendedSum($studentIdList, $classId, $subjectId) {
         
         if($studentIdList=='') {
           $studentIdList='0';  
         }
         
         
		 $query = "SELECT 
                        att.studentId, ROUND(SUM( IF( att.isMemberOfClass =0, 0,
                           IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS lectureAttended 
                 FROM  `group` grp,  ".ATTENDANCE_TABLE." att  
                        LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId 
                 WHERE 
                        grp.classId = att.classId AND
                        grp.groupId = att.groupId AND
                        att.classId = '$classId' and att.subjectId = '$subjectId' and att.studentId IN ($studentIdList) 
                 GROUP BY 
                        att.studentId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getSubjectTotalMarks($studentIdList2, $classId, $subjectId) {
		 $query = "SELECT sum(marksScored) as marksScored, sum(maxMarks) as maxMarks from ".TOTAL_TRANSFERRED_MARKS_TABLE." where classId = $classId and subjectId = $subjectId and studentId in ($studentIdList2)";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getSubjectTeachers($subjectId, $str) {
		 $query = "SELECT distinct  a.employeeId, b.employeeName from  ".TIME_TABLE_TABLE."  a, employee b where a.employeeId = b.employeeId and a.subjectId = $subjectId and a.toDate is null $str";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getDutyLeaveSum($studentIdList, $classId, $subjectId) {
     
            $query = "SELECT
                           tt.studentId, IFNULL(COUNT(tt.rejected),0) AS dutyLeave   
                      FROM
                          (SELECT
                                DISTINCT dl.studentId, dl.subjectId, dl.groupId, dl.classId, dl.periodId, dl.rejected,
                                dl.dutyDate, att.fromDate, att.toDate, att.isMemberOfClass      
                           FROM     
                                ".ATTENDANCE_TABLE." att,   ".DUTY_LEAVE_TABLE."  dl, attendance_code c  
                           WHERE
                                dl.studentId = att.studentId AND
                                dl.subjectId = att.subjectId AND
                                dl.groupId = att.groupId     AND
                                dl.classId = att.classId     AND 
                                dl.periodId = att.periodId   AND 
                                dl.rejected = ".DUTY_LEAVE_APPROVE." AND 
                                dl.dutyDate = att.fromDate   AND 
                                dl.dutyDate = att.toDate     AND        
                                att.isMemberOfClass = 1      AND
                                att.attendanceCodeId = c.attendanceCodeId AND
                                c.attendanceCodePercentage = 0    AND
                                dl.studentId  IN ($studentIdList) AND        
                                dl.classId    =   $classId        AND        
                                dl.subjectId  =   $subjectId) AS tt 
                      GROUP BY tt.studentId";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getAttendanceResultMarks($studentIdList, $classId, $subjectId, $testTypeId) {
		 $query = "SELECT 
                        studentId, round(marksScored,1) as ms_attendance 
                   FROM 
                        ".TEST_TRANSFERRED_MARKS_TABLE." 
                   WHERE 
                        classId = $classId and subjectId = $subjectId AND studentId in ($studentIdList) AND testTypeId = $testTypeId";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks) {
         
         if($testTypeMaxMarks=='') {
           $testTypeMaxMarks='0';  
         }
         
         if($studentIdList=='') {
           $studentIdList='0';  
         }
         
         if($classId=='') {
           $classId='0';  
         }
         
         if($subjectId=='') {
           $subjectId='0';  
         }
         
         if($testTypeCategoryId=='') {
           $testTypeCategoryId='0';  
         }
         
		 $query = "SELECT 
                        b.studentId,  
                        concat('ms_', $testTypeCategoryId,'_', a.testIndex) as testName, 
                        '$testTypeMaxMarks' as maxMarks, 
                        IF(CONCAT(b.isPresent,b.isMemberOfClass)=11,(b.marksScored/b.maxMarks) * IFNULL(trim('$testTypeMaxMarks'),0),
                        IF(CONCAT(b.isPresent,b.isMemberOfClass)=01,'A','N/A')) as marksScored,
                        b.maxMarks AS actualMaxMarks, b.marksScored AS actualMarksScored
                   FROM 
                        ".TEST_MARKS_TABLE." b, ".TEST_TABLE."  a 
                   WHERE 
                        a.testId = b.testId AND a.classId = $classId AND a.subjectId = $subjectId AND 
                        a.testTypeCategoryId IN ($testTypeCategoryId) AND b.studentId IN ($studentIdList)";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getTestTransferredResultMarks($studentIdList, $classId, $subjectId, $testTypeList) {
		$query = "SELECT studentId, maxMarks AS maxMarks, marksScored AS marksScored FROM ".TEST_TRANSFERRED_MARKS_TABLE." WHERE classId = $classId and subjectId = $subjectId AND studentId in ($studentIdList) AND testTypeId IN ($testTypeList)";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getTotalTransferredResultMarks($studentIdList, $classId, $subjectId) {
		$query = "SELECT 
                        studentId, round(SUM(marksScored),1) AS marksScored 
                 FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." 
                 WHERE 
                        classId = $classId and subjectId = $subjectId AND studentId in ($studentIdList) 
                        AND conductingAuthority in (1,3) GROUP BY studentId";
                        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }
     
      public function getTotalTransferredResultMarksNew($studentIdList, $classId, $subjectId) {
        $query = "SELECT 
                        studentId, round(SUM(marksScored),1) AS marksScored 
                 FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." 
                 WHERE 
                        classId = $classId and subjectId = $subjectId AND studentId in ($studentIdList) 
                        AND conductingAuthority in (1,3) 
                 GROUP BY 
                        studentId";
                        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }

	 public function getGraceMarks($studentIdList, $classId, $subjectId) {
         
		 $query = "SELECT 
                        studentId, classId, subjectId, 
                        IFNULL(graceMarks,0) AS graceMarks, IFNULL(internalGraceMarks,0) AS internalGraceMarks,
                        IFNULL(externalGraceMarks,0) AS externalGraceMarks, IFNULL(totalGraceMarks,0) AS totalGraceMarks
                   FROM 
                        ".TEST_GRACE_MARKS_TABLE." 
                   WHERE
                        classId = $classId AND subjectId = $subjectId AND studentId IN ($studentIdList) ";
                        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }

	 public function getTotalGraceMarks($studentIdList2, $classId, $subjectId) {
		 $query = "SELECT SUM(graceMarks) as marksScored from ".TEST_GRACE_MARKS_TABLE." where classId = '$classId' and subjectId = '$subjectId' and studentId IN ($studentIdList2)";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	 }


    public function getAttendanceDistribution($classId, $subjectId='',$groupId='', $queryCondition) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $subjectCodintion='';
        if($subjectId!=''){
            $subjectCodintion=' AND att.subjectId='.$subjectId;
        }
        $groupCodintion='';
        if($groupId!=''){
            $groupCodintion=' AND att.groupId IN ('.$groupId.')';
        }

        $query = "SELECT
                        subjectId,subjectCode,per,studentName,
                        $queryCondition
                        FROM (
                            SELECT
                                CONCAT(IFNULL(scs.firstName,''),' ',IFNULL(scs.lastName,'')) AS studentName,
                                su.subjectName, su.subjectCode, scs.rollNo,
                                ROUND(((SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ))/(SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )))*100) AS per,
                                ROUND( ( ( SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ) + IFNULL((SELECT SUM(leavesTaken) FROM attendance_leave al WHERE al.studentId = att.studentId AND al.classId=att.classId AND al.subjectId=att.subjectId  GROUP BY al.classId,al.studentId,al.subjectId),0) )/(SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )))*100) AS per2,
                                su.subjectId, att.studentId, att.classId
                            FROM
                                student scs
                                LEFT JOIN ".ATTENDANCE_TABLE." att  ON scs.studentId = att.studentId
                                INNER JOIN class c             ON   c.classId=att.classId
                                LEFT  JOIN attendance_code ac  ON   (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = $instituteId)
                                INNER JOIN subject su          ON   su.subjectId = att.subjectId
                            WHERE
                                c.sessionId=$sessionId
                                AND c.instituteId=$instituteId
                                AND c.classId=$classId
                                $subjectCodintion
                                $groupCodintion
                            GROUP BY att.classId, att.studentId, att.subjectId
                ) AS t
           GROUP BY subjectId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

      public function getAttendanceDistributionWithRollNo($classId, $subjectId='',$groupId='',$havingCondition='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $subjectCodintion='';
        if($subjectId!=''){
            $subjectCodintion=' AND att.subjectId='.$subjectId;
        }
        $groupCodintion='';
        if($groupId!=''){
            $groupCodintion=' AND att.groupId IN ('.$groupId.')';
        }

        $query = "SELECT
                        GROUP_CONCAT(t.universityRollNo1 ORDER BY t.universityRollNo1 ASC  SEPARATOR ', ') AS concatenatedRollNo,
                        GROUP_CONCAT(t.universityRollNo2 ORDER BY t.universityRollNo3 ASC  SEPARATOR ', ') AS concatenatedName,
                        GROUP_CONCAT(t.universityRollNo3 ORDER BY t.universityRollNo3 ASC  SEPARATOR ',<br/>') AS concatenatedBoth
                  FROM
                       (
                         SELECT
                                CONCAT(IFNULL(scs.universityRollNo,'".NOT_APPLICABLE_STRING."'),'--',IFNULL(scs.firstName,''),' ',IFNULL(scs.lastName,'')) AS universityRollNo3,
                                IFNULL(scs.universityRollNo,'".NOT_APPLICABLE_STRING."') AS universityRollNo1,
                                CONCAT(IFNULL(scs.firstName,''),' ',IFNULL(scs.lastName,'')) AS universityRollNo2,
                                ROUND(((SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ))/(SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )))*100) AS per,
                                ROUND( ( ( SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ) +
                                           IFNULL((SELECT
                                                         SUM(IF(att1.isMemberOfClass=0,0,IF(att1.attendanceType=2,1,0))) AS dutyLeave
                                                   FROM
                                                          ".DUTY_LEAVE_TABLE."  dl, ".ATTENDANCE_TABLE." att1
                                                         LEFT JOIN  attendance_code ac1 ON
                                                         (ac1.attendanceCodeId = att1.attendanceCodeId AND ac1.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                                                   WHERE
                                                        att1.studentId = dl.studentId AND
                                                        att1.classId = dl.classId AND
                                                        att1.subjectId = dl.subjectId AND
                                                        att1.groupId = dl.groupId AND
                                                        att1.periodId  = dl.periodId AND
                                                        att1.fromDate = dl.dutyDate AND
                                                        att1.toDate = dl.dutyDate AND
                                                        dl.studentId = att.studentId AND
                                                        dl.classId = att.classId AND
                                                        dl.subjectId = att.subjectId AND
                                                        dl.rejected = ".DUTY_LEAVE_APPROVE."
                                                   GROUP BY
                                                        dl.classId,dl.studentId,dl.subjectId),0) ) /(SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )))*100) AS per2
                            FROM
                                student scs
                                LEFT JOIN ".ATTENDANCE_TABLE." att  ON scs.studentId = att.studentId
                                INNER JOIN class c             ON   c.classId=att.classId
                                LEFT  JOIN attendance_code ac  ON   (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = $instituteId)
                                INNER JOIN subject su          ON   su.subjectId = att.subjectId
                            WHERE
                                c.sessionId=$sessionId
                                AND c.instituteId=$instituteId
                                AND c.classId=$classId
                                $subjectCodintion
                                $groupCodintion
                            GROUP BY att.classId, att.studentId, att.subjectId
                            $havingCondition
                         ) AS t";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//This function is used for fetching start and end date of a time table label
// Author :Dipanjan Bhattacharjee
// Created on : 1-05-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------

    public function getSelectedTimeTableDates($conditions='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                         timeTableLabelId,
                         startDate,
                         endDate
                  FROM
                         time_table_labels
                  WHERE
                         sessionId=$sessionId
                         AND instituteId=$instituteId
                         $conditions
                  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//This function is used for fetching teachers of a subject
// Author :Dipanjan Bhattacharjee
// Created on : 1-05-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------

    public function getTimeTableClassSubjectTeacher($conditions='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                         DISTINCT e.employeeId,
                         IF(e.employeeName IS NULL OR e.employeeName='','".NOT_APPLICABLE_STRING."',e.employeeName) AS employeeName,
                         IF(e.employeeCode IS NULL OR e.employeeCode='','".NOT_APPLICABLE_STRING."',e.employeeCode) AS employeeCode
                  FROM
                          ".TIME_TABLE_TABLE."  t,`class` c,`group` g,employee e
                  WHERE
                         c.classId=g.classId
                         AND g.groupId=t.groupId
                         AND t.employeeId=e.employeeId
                         AND t.toDate IS NULL
                         AND t.sessionId=$sessionId
                         AND t.instituteId=$instituteId
                         $conditions
                  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

//----------------------------------------------------------------------------------------------------
//This function is used for fetching groups of a subject and a class of particular time table
// Author :Dipanjan Bhattacharjee
// Created on : 1-05-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------
public function getTimeTableClassSubjectGroup($conditions='',$orderBy=' g.groupName') {
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        DISTINCT g.groupId,g.groupName,g.groupShort
                  FROM
                        `group` g,`class` c, ".TIME_TABLE_TABLE."  t
                  WHERE
                         c.classId=g.classId
                         AND g.groupId=t.groupId
                         AND t.sessionId=$sessionId
                         AND t.instituteId=$instituteId
                         AND t.toDate IS NULL
                         $conditions
                  ORDER BY $orderBy
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//This function is used for fetching subjects of a class of particular time table
// Author :Dipanjan Bhattacharjee
// Created on : 1-05-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------
public function getTimeTableClassSubject($conditions='',$orderBy=' s.subjectCode') {
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        DISTINCT s.subjectId,s.subjectName,s.subjectCode
                  FROM
                        `group` g,`class` c, ".TIME_TABLE_TABLE."  t,`subject` s
                  WHERE
                         c.classId=g.classId
                         AND g.groupId=t.groupId
                         AND t.subjectId=s.subjectId
                         AND t.sessionId=$sessionId
                         AND t.instituteId=$instituteId
                         AND t.toDate IS NULL
                         $conditions
                  ORDER BY $orderBy
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------------------------------
//This function is used for finding group hierarchy
// Author :Dipanjan Bhattacharjee
// Created on : 1-05-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------
public function getBranch($degreeId,$orderBy=' branchCode'){
	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId = $sessionHandler->getSessionVariable('SessionId');
    $query = "SELECT DISTINCT b.branchCode, b.branchId from class a, branch b where a.degreeId = $degreeId and a.instituteId = $instituteId and a.sessionId = $sessionId and a.branchId = b.branchId ORDER BY $orderBy";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}

public function getBranches($degreeId,$orderBy=' branchCode'){
	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId = $sessionHandler->getSessionVariable('SessionId');
	//$query = "SELECT DISTINCT b.branchCode, b.branchId from class a, branch b where a.degreeId = $degreeId and a.instituteId = $instituteId and a.sessionId = //$sessionId and a.branchId = b.branchId ORDER BY $orderBy";
	//SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query")

	if($degreeId == "NULL"){
		$query = "SELECT * FROM branch ORDER BY $orderBy";
	}
	else{
		$query = "SELECT DISTINCT b.branchCode, b.branchId from class a, branch b where a.degreeId = $degreeId and a.instituteId = $instituteId and a.sessionId = $sessionId and a.branchId = b.branchId ORDER BY $orderBy";
	}

	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}





public function getGroupHierarchy($classId,$groupId) {

       $groupHierarchyString = $groupId;
      //first check whether this group is parent or not.
      $query = "SELECT
                       COUNT(*) AS cnt
                FROM
                        `group` g
                WHERE
                         parentGroupId=$groupId
                ";
      $groupArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      if($groupArray[0]['cnt']>0){
        //then found childrens
        $query = "SELECT
                        t1.groupName,
                        IF(t1.parentGroupId IS null,-1,t1.parentGroupId) as parentId,
                        t1.groupId
                  FROM
                        `group` AS t1
                         LEFT JOIN `group` AS t2 ON ( t1.parentGroupId = t2.groupId )
                  WHERE
                        t1.classId=$classId
                        AND t1.parentGroupId=$groupId
                  ";
         $groupArray=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         if(is_array($groupArray) and count($groupArray)>0){
            $groupHierarchyString .=','.UtilityManager::makeCSList($groupArray,'groupId');
         }
      }

      return $groupHierarchyString;

    }


      //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR Student Rankwise Report
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getStudentAcademicList($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT
                        DISTINCT ac.studentId, ac.previousClassId, ac.previousRollNo, ac.previousSession,
                        ac.previousInstitute, ac.previousBoard, ac.previousMarks, ac.previousMaxMarks,
                        ac.previousPercentage, ac.previousEducationStream
                  FROM
                        student_academic ac
                  WHERE
                        ac.previousClassId IN (1,2)
                        $conditions
                  ORDER BY ac.studentId, ac.previousClassId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR Student Rankwise Report
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getAdmittedStudentList($condition='',$orderBy='',$limit='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        DISTINCT s.studentId, s.classId, c.className,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo, c.className AS className,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        IFNULL(compExamBy,'".NOT_APPLICABLE_STRING."') AS compExamBy, IFNULL(compExamRollNo,'".NOT_APPLICABLE_STRING."') AS compExamRollNo,
                        IFNULL(compExamRank,'".NOT_APPLICABLE_STRING."') AS compExamRank,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        IF(IFNULL(s.motherName,'')='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                        IFNULL(IF(corrAddress1 IS NULL OR corrAddress1='','', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=s.corrCityId),' ',(SELECT stateName from states where states.stateId=s.corrStateId),' ',(SELECT countryName from countries where countries.countryId=s.corrCountryId),IF(s.corrPinCode IS NULL OR s.corrPinCode='','',CONCAT('-',s.corrPinCode)))),'".NOT_APPLICABLE_STRING."')  AS corrAddress,
                        IFNULL(IF(permAddress1 IS NULL OR permAddress1='','', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=s.permCityId),' ',(SELECT stateName from states where states.stateId=s.permStateId),' ',(SELECT countryName from countries where countries.countryId=s.permCountryId),IF(s.permPinCode IS NULL OR s.permPinCode='','',CONCAT('-',s.permPinCode)))),'".NOT_APPLICABLE_STRING."')  AS permAddress,
                        IFNULL(IF(q.parentQuotaId=0,q.quotaName,CONCAT((SELECT qq.quotaName FROM `quota` qq  WHERE qq.quotaId = q.parentQuotaId),'-',q.quotaName)),'".NOT_APPLICABLE_STRING."')  AS quotaName1,
                        IF(IFNULL(s.studentGender,'')='','".NOT_APPLICABLE_STRING."',s.studentGender) AS studentGender,
                        IF(s.dateOfBirth='0000-00-00','".NOT_APPLICABLE_STRING."',s.dateOfBirth) AS dateOfBirth,
                        IFNULL(IF(q1.parentQuotaId=0,q1.quotaName,CONCAT((SELECT qq.quotaName FROM `quota` qq  WHERE qq.quotaId = q1.parentQuotaId),'-',q1.quotaName)),'".NOT_APPLICABLE_STRING."') AS managementCategory1,
                        IFNULL(s.studentMobileNo,'') AS studentMobileNo, IFNULL(s.studentPhone,'') AS studentPhone
                  FROM
                        class c, student s
                        LEFT JOIN quota q ON s.quotaId = q.quotaId
                        LEFT JOIN quota q1 ON s.managementCategory = q1.quotaId
                  WHERE
                       s.classId = c.classId AND
                       c.instituteId = $instituteId AND
                       c.sessionId = $sessionId
                  $condition
                  $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

    public function getAdmittedStudentCount($condition='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        COUNT(t.studentId) AS cnt
                  FROM
                      (SELECT
                            DISTINCT s.studentId, s.classId,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo, c.className AS className,
                            IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                            IFNULL(compExamBy,'') AS compExamBy, IFNULL(compExamRollNo,'') AS compExamRollNo,
                            IFNULL(compExamRank,'') AS compExamRank,
                            IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                            IF(IFNULL(s.motherName,'')='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                            IFNULL(IF(corrAddress1 IS NULL OR corrAddress1='','', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=s.corrCityId),' ',(SELECT stateName from states where states.stateId=s.corrStateId),' ',(SELECT countryName from countries where countries.countryId=s.corrCountryId),IF(s.corrPinCode IS NULL OR s.corrPinCode='','',CONCAT('-',s.corrPinCode)))),'".NOT_APPLICABLE_STRING."')  AS corrAddress,
                            IFNULL(IF(permAddress1 IS NULL OR permAddress1='','', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=s.permCityId),' ',(SELECT stateName from states where states.stateId=s.permStateId),' ',(SELECT countryName from countries where countries.countryId=s.permCountryId),IF(s.permPinCode IS NULL OR s.permPinCode='','',CONCAT('-',s.permPinCode)))),'".NOT_APPLICABLE_STRING."')  AS permAddress,
                            IFNULL(IF(q.parentQuotaId=0,q.quotaName,CONCAT((SELECT qq.quotaName FROM `quota` qq  WHERE qq.quotaId = q.parentQuotaId),'-',q.quotaName)),'".NOT_APPLICABLE_STRING."')  AS quotaName,
                            IF(IFNULL(s.studentGender,'')='','".NOT_APPLICABLE_STRING."',s.studentGender) AS studentGender,
                            IF(s.dateOfBirth='0000-00-00','".NOT_APPLICABLE_STRING."',s.dateOfBirth) AS dateOfBirth,
                            IFNULL(IF(q1.parentQuotaId=0,q1.quotaName,CONCAT((SELECT qq.quotaName FROM `quota` qq  WHERE qq.quotaId = q1.parentQuotaId),'-',q1.quotaName)),'".NOT_APPLICABLE_STRING."') AS managementCategory,
                            IFNULL(s.studentMobileNo,'') AS studentMobileNo, IFNULL(s.studentPhone,'') AS studentPhone
                      FROM
                            class c, student s
                            LEFT JOIN quota q ON s.quotaId = q.quotaId
                            LEFT JOIN quota q1 ON s.managementCategory = q1.quotaId
                      WHERE
                           s.classId = c.classId AND
                           c.instituteId = $instituteId AND
                           c.sessionId = $sessionId
                      $condition) AS t ";



        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }


//----------------------------------------------------------------------------------------------------
// Function find student duty leaves
//
// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    public function getStudentPreviousDutyLeave($condition='',$field='',$groupBy='',$filter='',$groupBy1='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                           $filter
                  FROM
                          (SELECT
                                $field
                            FROM
                                   `group` grp, group_type gt,
                                    ".DUTY_LEAVE_TABLE."  dl, ".ATTENDANCE_TABLE." att
                                   LEFT JOIN  attendance_code ac ON
                                   (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                           WHERE
                                   att.studentId = dl.studentId AND
                                   att.classId = dl.classId AND
                                   att.subjectId = dl.subjectId AND
                                   att.groupId = dl.groupId AND
                                   att.periodId  = dl.periodId AND
                                   att.fromDate = dl.dutyDate AND
                                   att.toDate = dl.dutyDate AND
                                   dl.sessionId='".$sessionId."' AND
                                   dl.instituteId='".$instituteId."' AND
                                   dl.groupId = grp.groupId AND
                                   gt.groupTypeId = grp.groupTypeId AND
                                   (IF(att.isMemberOfClass=0,0,IF(att.attendanceType=2,1,0))) = 1 AND
                                   dl.rejected = ".DUTY_LEAVE_APPROVE."
                           $condition
                           GROUP BY
                                  $groupBy) AS tt
                   $groupBy1 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


     public function getStudentFindDutyLeave($fieldName='', $condition='', $orderBy='', $limit='',$conslidated='') {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId  = $sessionHandler->getSessionVariable('SessionId');


        $groupBy = "";
        if($fieldName=='') {
            $fieldName = "  dl.classId, dl.subjectId, dl.groupId, dl.studentId,
                            IFNULL(dl.periodId,'-1') AS periodId, gt.groupTypeId,
                            dl.dutyDate, SUM(IF(dl.rejected=1, 1,0)) AS attended";
            if($conslidated=='') {
               $groupBy = " GROUP BY dl.classId, dl.subjectId, dl.groupId, dl.studentId, dl.dutyDate, dl.periodId";
            }
            else {
               $groupBy = " GROUP BY dl.classId, dl.subjectId, dl.groupId, dl.studentId, dl.dutyDate";
            }
        }

        $query = "SELECT
                        $fieldName
                  FROM
                        `group` grp, group_type gt,
                         ".DUTY_LEAVE_TABLE."  dl, ".ATTENDANCE_TABLE." att
                        LEFT JOIN  attendance_code ac1 ON
                        (ac1.attendanceCodeId = att.attendanceCodeId AND ac1.instituteId = ".$sessionHandler->getSessionVariable('InstituteId').")
                  WHERE
                       att.studentId = dl.studentId AND
                       att.classId = dl.classId AND
                       att.subjectId = dl.subjectId AND
                       att.groupId = dl.groupId AND
                       att.periodId  = dl.periodId AND
                       att.fromDate = dl.dutyDate AND
                       att.toDate = dl.dutyDate AND
                       dl.sessionId='".$sessionId."' AND
                       dl.instituteId='".$instituteId."' AND
                       dl.groupId = grp.groupId AND
                       (IF(att.isMemberOfClass=0,0,IF(att.attendanceType=2,1,0))) = 1 AND
                       gt.groupTypeId = grp.groupTypeId AND
                       dl.rejected = ".DUTY_LEAVE_APPROVE."

                  $condition
                  $groupBy
                  ORDER BY
                        $orderBy
                  $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------
// Usage : To fetch student's address
// Author :Dipanjan Bhattacharjee
// Created on : 06.10.2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------
    public function getStudentAddress($studentIds) {
    $query = " SELECT
                     DISTINCT studentId,
                             CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                             IFNULL(fatherName,'') AS fatherName,
                             IFNULL(permAddress1,'') AS permAddress1,
                             IFNULL(permAddress2,'') AS permAddress2,
                             IFNULL(corrAddress1,'') AS corrAddress1,
                             IFNULL(corrAddress2,'') AS corrAddress2,
							 (select c.cityName from city c where c.cityId = student.permCityId) as permanantCity,
							 (select s.stateName from states s where s.stateId = student.permStateId) as permanantState,
							 (select c.cityName from city c where c.cityId = student.corrCityId) as corrCity,
							 (select s.stateName from states s where s.stateId = student.corrStateId) as corrState
                FROM
                     student
                WHERE
                     studentId IN ($studentIds)
             ";
      return SystemDatabaseManager::getInstance()->executeQuery($query);
     }
     
     
    public function getStudentTestTypeCategoryCount($classId,$subjectIds){
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');

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
                        AND t.subjectId = tm.subjectId
                        AND t.subjectId = sub.subjectId
                        AND t.testTypeCategoryId = ttc.testTypeCategoryId
                        AND t.classId =$classId
                        AND t.instituteId=$instituteId
                        AND t.sessionId=$sessionId
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
    
     public function getInternalMarksWithGrade($studentIdList, $classId, $subjectId) {
         $query = "SELECT 
                        a.studentId, a.subjectId, round(sum(a.marksScored) + ifnull(b.graceMarks,0),1) as marksScored,
                        IFNULL(gr.gradeLabel,'') AS gradeLabel  
                   FROM 
                        ".TEST_GRACE_MARKS_TABLE." b, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                        LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId
                   WHERE 
                        a.classId = $classId and a.subjectId IN ($subjectId) and a.studentId in ($studentIdList) and 
                        a.conductingAuthority IN (1,3) and a.subjectId = b.subjectId and a.classId = b.classId and
                        a.studentId = b.studentId group by a.studentId, a.subjectId";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }
     
     
      public function getStudentGradeCount($studentIdList='', $classId='', $subjectId='') {  
          
         if($studentIdList=='') {
           $studentIdList='0';  
         } 
         
         if($subjectId=='') {
           $subjectId='0';  
         } 
          
         $query = "SELECT 
                        SUM(IF(IFNULL(gr.gradeLabel,'')='',0,1)) AS studentCount, 
                        SUM(IF(IFNULL(gr.gradeLabel,'I')='I',1,0)) AS studentNotCount,                 
                        IFNULL(gr.gradeLabel,'I') AS gradeLabel, sub.subjectName, sub.subjectCode,
                        gr.gradeSetId, gr.gradeId  
                   FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                        LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId
                        LEFT JOIN `subject` sub ON a.subjectId = sub.subjectId
                   WHERE 
                        a.classId = '$classId' and a.subjectId IN ($subjectId) and a.studentId IN ($studentIdList) and 
                        a.conductingAuthority IN (1,3) and a.classId = '$classId'  
                   GROUP BY
                        a.classId, a.subjectId, gr.gradeId
                   ORDER BY
                        sub.subjectName, gr.gradePoints DESC";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }   
     
     
      public function getStudentGraphGrading($classId='', $condition='') {  
          
         if($subjectId=='') {
           $subjectId='0';  
         } 
          
         $query = "SELECT 
                        DISTINCT gr.gradeSetId, gr.gradeId, gr.gradeLabel, gr.gradePoints  
                   FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a, `grades` gr 
                   WHERE 
                        a.gradeId = gr.gradeId AND
                        a.classId = '$classId' 
                        $condition
                   GROUP BY
                        a.classId, a.subjectId
                   UNION
                   SELECT 
                        '', '', 'I', '0'  
                   FROM 
                        `grades` gr ";
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }       
     
     public function getStudentGraphGradingNew($classId='', $condition='') {  
          
         if($subjectId=='') {
           $subjectId='0';  
         } 
          
         $query = "SELECT 
                        DISTINCT gr.gradeSetId 
                   FROM 
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." a, `grades` gr 
                   WHERE 
                        a.gradeId = gr.gradeId AND
                        a.classId = '$classId' 
                        $condition
                   GROUP BY
                        a.classId, a.subjectId";
         $resultArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");               
         
         $gradeSetId='';
         if(is_array($resultArray) && count($resultArray)>0 ) {
           $gradeSetId=$resultArray[0]['gradeSetId'];  
         }
                        
         $query = "SELECT 
                       g.gradeSetId, g.gradeId, g.gradeLabel, g.gradePoints 
                   FROM 
                       grades g, grades_set gs
                   WHERE 
                       g.gradeSetId = gs.gradeSetId
                       AND gs.gradeSetId IN ('$gradeSetId') 
                   ORDER BY
                       g.gradeSetId, g.gradePoints DESC ";
                        
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }       
     
     
     
      public function getGradeList($gradeSetId='',$subjectName='',$subjectCode='') {
          
           if($gradeSetId=='') {
             $gradeSetId='0';  
           }
          
            $query = "SELECT 
                          IFNULL(gradeStatus,'') AS gradeStatus, gradeId, gradeLabel, gradePoints, gradeSetId,  '0' AS studentCount,
                          '$subjectName' AS subjectName, '$subjectCode' AS subjectCode   
                      FROM 
                          `grades`
                      WHERE 
                           gradeSetId IN ($gradeSetId)
                      ORDER BY
                          gradeSetId, gradePoints DESC ";
           
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
      }  
      
      
      public function getStudentPreviousGrade($condition='') {
          
         $query = "SELECT 
                         a.marksScoredStatus, a.studentId, a.subjectId, 
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, IFNULL(gr.gradePoints,'0')*IFNULL(sc.credits,'0') AS totalGrade
                   FROM 
                         class c, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        c.classId = sc.classId AND
                        sc.optional=0 AND
                        sc.classId = a.classId AND
                        sc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,3)  
                        $condition
                   GROUP BY 
                        a.studentId, a.classId, a.subjectId
                   UNION
                   SELECT 
                         a.marksScoredStatus, a.studentId, a.subjectId,  
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, IFNULL(gr.gradePoints,'0')*IFNULL(sc.credits,'0') AS totalGrade  
                   FROM 
                         class c, optional_subject_to_class oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        c.classId = oc.classId AND
                        oc.classId = sc.classId AND sc.optional=1 AND 
                        oc.parentOfSubjectId = sc.subjectId AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,3) 
                        $condition
                   GROUP BY 
                        a.studentId, a.classId, a.subjectId
                   UNION     
                   SELECT 
                         a.marksScoredStatus, a.studentId, a.subjectId, 
                         IFNULL(gr.gradeLabel,'') AS gradeLabel, IFNULL(gr.gradeSetId,'') AS gradeSetId, IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints, sc.credits, IFNULL(gr.gradePoints,'0')*IFNULL(sc.credits,'0') AS totalGrade  
                   FROM 
                         class c, student_optional_subject oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        c.classId = oc.classId AND
                        oc.classId = sc.classId AND sc.optional=1 AND 
                        oc.subjectId = sc.subjectId AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,3) AND a.studentId = oc.studentId
                        $condition
                   GROUP BY 
                        a.studentId, a.classId, a.subjectId";
           
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
      }  
      
      public function getStudentPreviousGradeCheck($condition='') {
          
         $query = "SELECT 
                         IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints
                   FROM 
                         class c, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        c.classId = sc.classId AND
                        sc.optional=0 AND
                        sc.classId = a.classId AND
                        sc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,3)  
                        $condition
                   GROUP BY 
                        a.studentId, a.classId, a.subjectId
                   UNION
                   SELECT 
                         IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints
                   FROM 
                         class c, optional_subject_to_class oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        c.classId = oc.classId AND
                        oc.classId = sc.classId AND sc.optional=1 AND 
                        oc.parentOfSubjectId = sc.subjectId AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,3) 
                        $condition
                   GROUP BY 
                        a.studentId, a.classId, a.subjectId
                   UNION     
                   SELECT 
                         IFNULL(gr.gradeId,'') AS gradeId,
                         IFNULL(gr.gradePoints,'0') AS gradePoints
                   FROM 
                         class c, student_optional_subject oc, subject_to_class sc, ".TOTAL_TRANSFERRED_MARKS_TABLE." a 
                         LEFT JOIN `grades` gr ON a.gradeId = gr.gradeId    
                   WHERE 
                        c.classId = oc.classId AND
                        oc.classId = sc.classId AND sc.optional=1 AND 
                        oc.subjectId = sc.subjectId AND
                        oc.classId = a.classId AND
                        oc.subjectId = a.subjectId AND
                        a.conductingAuthority IN (1,3)   AND a.studentId = oc.studentId
                        $condition
                   GROUP BY 
                        a.studentId, a.classId, a.subjectId";
           
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
      }  
      
      
      public function getStudentClasswiseCGPA($condition='') {

            $query = "SELECT
                           sg.classId, c.className, sg.studentId, 
                           sg.previousCredit, sg.previousGradePoint, sg.previousCreditEarned,
                           sg.lessCredit, sg.lessGradePoint, sg.lessCreditEarned,  
                           sg.currentCredit, sg.currentGradePoint, sg.currentCreditEarned,
                           sg.netCredit, sg.netGradePoint, sg.netCreditEarned,
                           sg.gradeIntoCredits, sg.credits, sg.cgpa, sg.gpa
                     FROM
                          `student` s, `student_cgpa` sg , class c 
                     WHERE
                           s.studentId = sg.studentId AND
                           sg.classId = c.classId
                     $condition
                     ORDER BY
                           sg.studentId, sg.classId ";
                     

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }
      
      
     //-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET CLASS Privileges Classes
    //
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getPrivilegesClasses($labelId) {
       
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $roleName = $sessionHandler->getSessionVariable('RoleName'); 
        
        
        $classCondition1 = " AND c.isActive IN (1,3) ";
        
        $tableName = "";
        $insertValue = '';
        $classCondition1 ='';
        $cnt = 0;
        if($roleId!=1) { 
            
           $query = "SELECT
                            DISTINCT sessionId, isActive
                      FROM
                            time_table_labels
                      WHERE  
                            timeTableLabelId = $labelId AND
                            instituteId = $instituteId ";
            $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");   
            
            $isActive = "1";
            $sessionId = '0';
            if(count($result) > 0 ) {
              $sessionId = $result[0]['sessionId'];  
              $isActive = $result[0]['isActive'];  
              if($isActive=='0') {
                $isActive = "3";
              }
            } 
            
            
            $query = "SELECT
                            DISTINCT cvtr.classId
                      FROM
                            classes_visible_to_role cvtr, class cc
                      WHERE  
                            cc.classId = cvtr.classId AND
                            cvtr.userId = $userId AND
                            cvtr.roleId = $roleId AND
                            cc.instituteId = $instituteId AND
                            cc.sessionId = $sessionId AND
                            cc.isActive IN ($isActive) ";

            $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
            $cnt = count($result);
            
            $insertValue = "0";
            for($i=0;$i<$cnt; $i++) {
              $insertValue .= ",".$result[$i]['classId'];
            }
        }
        
        if($cnt > 0) {
          $classCondition1 = " AND c.classId IN  ($insertValue) ";  
        }
        
        $query = "SELECT
                        DISTINCT ttc.classId, c.className 
                  FROM    
                        time_table_classes ttc, class c 
                  WHERE    
                        ttc.classId = c.classId
                        AND ttc.timeTableLabelId = $labelId
                        AND c.instituteId = $instituteId   
                        $classCondition1
                  ORDER BY 
                        c.degreeId,c.branchId,c.studyPeriodId";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    function getShowCourseCredits($condition='',$currentClassId='') {
       
        $query = "SELECT 
                        DISTINCT IFNULL(s.credits,'') AS credits 
                  FROM 
                        subject_to_class s, class c, study_period sp    
                  WHERE
                        s.classId = c.classId AND sp.studyPeriodId = c.studyPeriodId
                        AND 
                        (CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) IN
                         (SELECT CONCAT_WS(',',c1.batchId,c1.degreeId,c1.branchId) FROM class c1 WHERE c1.classId = '$currentClassId'))
                  $condition";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");  
        
    }
    
    function getAttendanceShortComments($mHeading='',$mMessage='',$mSignature='') {
        
        global $sessionHandler;        
        
        if($mHeading=='' && $mMessage=='') {
          return true;  
        }
        
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');  
        if($instituteId=='') {
          $instituteId=0;  
        }
        
        if($mMessage!='') { 
           $query = "DELETE FROM config WHERE param LIKE 'ATTENDANCE_SHORT_MESSAGE' AND instituteId = '$instituteId'  ";
           $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
           if($ret===false) {
             return false;  
           }
           $query  = " INSERT INTO config (param,labelName,`value`,tabGroup,instituteId) VALUES ";
           $query .= " ('ATTENDANCE_SHORT_MESSAGE','Student Attendance Short Report Message','$mMessage','OTHER',$instituteId)";   
           $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);      
           if($ret===false) {
             return false;  
           }
           $sessionHandler->setSessionVariable('ATTENDANCE_SHORT_MESSAGE',$mMessage);
        }
        
        if($mHeading!='') { 
           $query = "DELETE FROM config WHERE param LIKE 'ATTENDANCE_SHORT_HEADING' AND instituteId = '$instituteId'  ";
           $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
           if($ret===false) {
             return false;  
           }
           $query  = " INSERT INTO config (param,labelName,`value`,tabGroup,instituteId) VALUES ";
           $query .= " ('ATTENDANCE_SHORT_HEADING','Student Attendance Short Report Heading Message','$mHeading','OTHER',$instituteId)";   
           $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);      
           if($ret===false) {
             return false;  
           }
           $sessionHandler->setSessionVariable('ATTENDANCE_SHORT_HEADING',$mHeading);
        }
        
        if($mSignature!='') { 
           $query = "DELETE FROM config WHERE param LIKE 'ATTENDANCE_SHORT_SIGNATURE' AND instituteId = '$instituteId'  ";
           $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
           if($ret===false) {
             return false;  
           }
           $query  = " INSERT INTO config (param,labelName,`value`,tabGroup,instituteId) VALUES ";
           $query .= " ('ATTENDANCE_SHORT_SIGNATURE','Student Attendance Short Report Message','$mSignature','OTHER',$instituteId)";   
           $ret = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);      
           if($ret===false) {
             return false;  
           }
           $sessionHandler->setSessionVariable('ATTENDANCE_SHORT_SIGNATURE',$mSignature);
        }
        
        return true;   
    }
function getAlternateSubject($subjectId,$classId) {
	 $query = "SELECT 
						 sub.subjectId AS subjectId,
                         IF(s.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
                         IF(s.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName
                  FROM 
                        subject_to_class s, subject sub
                  WHERE
                       s.subjectId=sub.subjectId
                       AND sub.subjectId IN ($subjectId)
                       AND s.classId IN ($classId)
                       ORDER BY subjectCode";
                 

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");  
        
}

function getAlternateSubjectList($classId) {
	 $query = "SELECT 
					DISTINCT	 sub.subjectId AS subjectId,s.classId,
                         IF(s.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
                         IF(s.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName
                  FROM 
                        subject_to_class s, subject sub,".TOTAL_TRANSFERRED_MARKS_TABLE." a
                  WHERE
                       s.subjectId=sub.subjectId AND
                      a.subjectId = sub.subjectId AND a.classId = $classId 
                       AND s.classId IN ($classId)
                       ORDER BY subjectId";
            

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");  
        
}


function getAlternateSubjects($subjectId,$classId) {
	 $query = "SELECT 
					 DISTINCT sub.subjectId AS subjectId,sub.hasAttendance, sub.hasMarks, '0' AS testCount, '0' AS totalCount,
                     IF(s.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
                     IF(s.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName, sub.subjectTypeId
               FROM 
                    subject_to_class s, subject sub
               WHERE
                   s.subjectId=sub.subjectId
                   AND sub.hasAttendance = 1 
                   AND sub.hasMarks = 1
                   AND sub.subjectId IN ($subjectId)
                   AND s.classId IN ($classId)
               UNION      
               SELECT 
                     DISTINCT sub.subjectId AS subjectId,sub.hasAttendance, sub.hasMarks, '0' AS testCount, '0' AS totalCount,
                     IF(s.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
                     IF(s.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName, sub.subjectTypeId
               FROM 
                    subject_to_class s, student_optional_subject sos, subject sub
               WHERE
                   sos.classId=s.classId 
                   AND sos.subjectId=sub.subjectId 
                   AND s.subjectId=sub.subjectId
                   AND sos.subjectId IN ($subjectId)
                   AND sos.classId IN ($classId)
              UNION      
              SELECT 
                     DISTINCT sub.subjectId AS subjectId,sub.hasAttendance, sub.hasMarks, '0' AS testCount, '0' AS totalCount,
                     IF(s.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
                     IF(s.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName, sub.subjectTypeId
               FROM 
                    subject_to_class s, optional_subject_to_class sos, subject sub
               WHERE
                   sos.classId=s.classId 
                   AND sos.subjectId=sub.subjectId 
                   AND s.subjectId=sos.parentOfSubjectId
                   AND sos.subjectId IN ($subjectId)
                   AND sos.classId IN ($classId)     
              ORDER BY subjectTypeId, subjectCode";
              

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");  
        
}

    function getAllGroup($classId) {
         
        $query = "SELECT 
                       DISTINCT groupId, groupName, groupShort, IFNULL(parentGroupId,'') AS parentGroupId, 
                       IFNULL(groupTypeId,'') AS groupTypeId, classId, isOptional, IFNULL(optionalSubjectId,'') AS  optionalSubjectId
                   FROM
                       `group` 
                   WHERE
                      classId = '$classId'
                   ORDER BY
                      groupName";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");  
    }

}

?>
