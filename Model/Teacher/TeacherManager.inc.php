<?php
//---------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "teacher" module
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class TeacherManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }

//*******************FUNCTIONS NEEDED FOR DISPLAYING/Add/Edit FeedBack List(For Teacher)********
//-------------------------------------------------------------------------------
//
//getSurveyAttempt() function used to get total no. of attempts
// Author : Jaineesh
// Created on : 05.01.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getSurveyAttempts($condition) {
     $query = "SELECT noAttempts, feedbackSurveyId
               FROM feedback_survey $condition";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }

//-------------------------------------------------------------------------------
//
//getAttempt() function used to get no. of attempts
// Author : Jaineesh
// Created on : 05.01.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getAttempts($condition) {

    global $sessionHandler;
  $userId = $sessionHandler->getSessionVariable('UserId');
  $query ="SELECT
                attempts
           FROM
             feedback_survey_answer sgv,feedback_questions fq,feedback_survey fs
           WHERE
            fq.feedbackSurveyId = fs.feedbackSurveyId
            AND  sgv.feedbackQuestionId = fq.feedbackQuestionId
            AND  sgv.userId = $userId
     $condition";


            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------
//
//insertGeneralSurvey() function used to insert into teacher_feedback table
// Author : Jaineesh
// Created on : 30.12.08
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function insertGeneralSurvey($feedbackInsert) {
$query = "INSERT INTO `feedback_survey_answer` (feedbackQuestionId,feedbackGradeId,dated,userId,attempts) VALUES".$feedbackInsert;
            SystemDatabaseManager::getInstance()->executeUpdate($query);
            return true;

}
	//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Feedback Data
//
// Author :Jaineesh
// Created on : 10-11-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getFeedBackEmpData($condition) {
        global $sessionHandler;

     $query = "
                SELECT
                                fc.feedbackCategoryName,
                                fq.feedbackQuestion,
                                fc.feedbackCategoryId,
                                fq.feedbackQuestionId,
                                fs.feedbackSurveyId

                FROM            feedback_category fc,
                                feedback_questions fq,
                                feedback_survey fs,
                                employee emp
                WHERE              fq.feedbackCategoryId = fc.feedbackCategoryId
                AND                fq.feedbackSurveyId = fs.feedbackSurveyId
                AND                fs.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                AND                fs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                AND                emp.employeeId = ".$sessionHandler->getSessionVariable('EmployeeId')."
                                $condition ORDER BY fc.feedbackCategoryId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//-------------------------------------------------------------------------------
//
//getStudentQuestionGradeId() function used to get answerId
// Author : Jaineesh
// Created on : 05.01.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getStudentQuestionGradeId($questionId) {
        global $sessionHandler;
        $userId = $sessionHandler->getSessionVariable('UserId');
        $query = "
                    SELECT
                                feedbackGradeId
                    FROM        feedback_survey_answer
                    WHERE        feedbackQuestionId = $questionId
                    AND            userId = $userId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }

//**********************FUNCTIONS NEEDED FOR DISPLAING THREE DROP DOWN IN STUDENTSEARCH IN TEACHER MODULE************

//populate student lists
public function getStudents($conditions = '',$orderBy=' studentName') {

         global $sessionHandler;
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');

        $query = "  SELECT
                           DISTINCT s.studentId,
                           CONCAT(IFNULL(s.firstName,''),' ',(IFNULL(s.lastName,''))) AS studentName,
                           IFNULL(s.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                           s.fatherTitle,s.fatherName,s.fatherMobileNo,s.fatherEmail,
                           s.motherTitle,s.motherName,s.motherMobileNo,s.motherEmail,
                           s.guardianTitle,s.guardianName,s.guardianMobileNo,s.guardianEmail,
                           s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
                    FROM
                           student s,student_groups sg
                    WHERE
                           s.studentId=sg.studentId
                           AND s.classId=sg.classId
                           $conditions
                           ORDER BY $orderBy
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

// getMobileNumber() is used to get the mobile numbers of father,mother,guardian against student Ids
public function getMobileNumber($studentIds){
	$query="SELECT
						motherMobileNo AS mobileNumber
			FROM	`student`
			WHERE		studentId IN ($studentIds)

			UNION

			SELECT
						fatherMobileNo AS mobileNumber
			FROM	`student`
			WHERE		studentId IN ($studentIds)

			UNION

			SELECT
						guardianMobileNo AS mobileNumber
			FROM	`student`
			WHERE		studentId IN ($studentIds)";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
// getStudentMobileNumber() is used to get the student mobile number
public function getStudentMobileNumber($studentIds){
	$query= "SELECT
						studentMobileNo
				FROM	`student`
				WHERE	studentId IN($studentIds)";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//populate student duty lists
public function getStudentDutyLeaveList($conditions = '',$limit='',$orderBy=' rollNo') {

       $query= "SELECT
                        DISTINCT s.studentId,
                        CONCAT(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo
                FROM
                   student s,class c,`group` g,subject_to_class sc,student_groups sg
                WHERE
                  s.studentId=sg.studentId
                  AND sg.classId=c.classId
                  AND s.classId = c.classId
                  AND sg.groupId=g.groupId
                  AND sc.classId=c.classId
                $conditions
                ORDER BY $orderBy
                $limit
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getTotalStudentDutyLeave($conditions = '') {

        $query = "  SELECT
                           COUNT(DISTINCT s.studentId) AS totalRecords
                    FROM
                      student s,class c,`group` g,subject_to_class sc,student_groups sg
                   WHERE
                      s.studentId=sg.studentId
                      AND sg.classId=c.classId
                      AND s.classId = c.classId
                      AND sg.groupId=g.groupId
                      AND sc.classId=c.classId
                $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getStudentDutyLeaveDetails($studentId,$classId,$groupId,$subjectId) {

        $query = " SELECT
                          attendanceCodeId,dated,comments
                    FROM
                      attendance_leave
                   WHERE
                       studentId=$studentId
                       AND classId=$classId
                       AND groupId=$groupId
                       AND subjectId=$subjectId
                   ORDER BY dated DESC
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET CLASS NAME
// Created on : (16.03.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getClass($classId){
	$query="
			SELECT
						SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3) AS className
				FROM	`class`
				WHERE	classId = $classId";

	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET SUBJECT NAME
// Created on : (16.03.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getSubject($subjectId){
	$query="
			SELECT
					subjectCode
				FROM	`subject`
				WHERE subjectId = $subjectId";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET SUBJECT NAME
// Created on : (16.03.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getStudentName($studentId){
    global $REQUEST_DATA;
    global $sessionHandler;
    
    $query="
				SELECT 
						firstName, fatherMobileNo 
				FROM 
						student 
				WHERE 
						studentId = '$studentId'
		   ";
     return SystemDatabaseManager::getInstance()->executeQuery($query);
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET TEACHER NAME
// Created on : (16.03.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getTeacherName($employeeId){
	$query="
			SELECT
					employeeName
				FROM	`employee`
				WHERE	employeeId = $employeeId";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET GROUP NAME
// Created on : (16.03.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getGroupName($groupId){
	$query="
			SELECT
					groupName
				FROM	`group`
				WHERE	groupId = $groupId ";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


 public function deleteDutyLeaves($studentId,$classId,$groupId,$subjectId) {

        $query = " DELETE
                   FROM
                      attendance_leave
                   WHERE
                       studentId=$studentId
                       AND classId=$classId
                       AND groupId=$groupId
                       AND subjectId=$subjectId
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

 public function insertDutyLeaves($queryStr) {

        $query = " INSERT
                   INTO
                         attendance_leave
                          (
                           timeTableLabelId,studentId,attendanceCodeId,dated,comments,classId,subjectId,groupId,employeeId,userId
                          )
                   VALUES
                  ".$queryStr;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


public function getClassTimeTableLabel($conditions='') {

        $query = " SELECT
                          timeTableLabelId
                    FROM
                      time_table_classes
                    $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getActiveTimeTable(){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');

    $query="
            SELECT
                   timeTableLabelId,
                   labelName
            FROM
                   time_table_labels
            WHERE
                   sessionId=$sessionId
                   AND instituteId=$instituteId
                   AND isActive=1
                   ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of class of a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherClass($conditions='') {

        global $sessionHandler;
        $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $loginDate=date('Y-m-d');

        $query = "SELECT
                        c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                  FROM
                         ".TIME_TABLE_TABLE."  t,`group` g,`class` c
                  WHERE
                         t.groupId=g.groupId AND g.classId=c.classId AND t.employeeId=$teacherId
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND t.toDate IS NULL
                         AND c.isActive=1
                         AND t.timeTableId NOT IN
                              (
                                SELECT
                                      tta.timeTableId
                                FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=tta.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                              )
                         $conditions
                         GROUP BY c.classId
                  UNION
                        SELECT
                           c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                        FROM
                           `time_table_adjustment` t,`group` g,`class` c
                        WHERE
                           t.groupId=g.groupId AND g.classId=c.classId AND t.employeeId=$teacherId
                           AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                           AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                           AND c.isActive=1
                           AND t.isActive=1
                           AND '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                           AND t.employeeId=$teacherId
                           $conditions
                           GROUP BY c.classId
                  ";
                 //ORDER BY timeTableLabelId

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//this function will fetch original+adjusted class details of a teacher based upon user selected dates
    public function getTeacherAdjustedClass($startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;
        $ttlCondition='';
        $classCondition='';
        $timeTableCondition='';
		$employeeIdCondition ='';
        if($sessionHandler->getSessionVariable('RoleId')==2){
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
			if ($teacherId != '') {
				$employeeIdCondition = "AND t.employeeId=$teacherId";
			}

         $ttlCondition=' AND ttl.isActive=1';
         $classCondition=' AND c.isActive=1';
        }
        else{
		   if($employeeId !=''){
				$teacherId=$employeeId;
				$employeeIdCondition = "AND t.employeeId=$teacherId";
			}
		}
		/*
            if($timeTableLabelId==''){
                $timeTableLabelId=0;
            }
            $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }*/
		/*$teacherId=$employeeId;
        if ($teacherId == '') {
           $teacherId = 0;
        }*/
		if($timeTableLabelId !=''){
                $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
            }

        $query = "SELECT
                        c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        c.studyPeriodId
                  FROM
                         ".TIME_TABLE_TABLE."  t,`group` g,`class` c,time_table_labels ttl
                  WHERE
                         t.groupId=g.groupId AND g.classId=c.classId
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND t.toDate IS NULL
                         AND t.timeTableLabelId=ttl.timeTableLabelId
                         $ttlCondition
                         $classCondition
                         $timeTableCondition
                         $timeTableLabelTypeConditions
						 $employeeIdCondition
                         $conditions
                         GROUP BY c.classId
                   ORDER BY studyPeriodId
                  ";
                 //ORDER BY timeTableLabelId
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getTeacherAdjustedClassMulti($subjectId,$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;
        $ttlCondition='';
        $classCondition='';
        $timeTableCondition='';
        $subjectCondition='';
        if($subjectId!='' and $subjectId!='0'){
            $subjectCondition=' AND t.subjectId='.$subjectId;
        }
        if($sessionHandler->getSessionVariable('RoleId')==2){
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
            if ($teacherId == '') {
                $teacherId = 0;
            }
         $ttlCondition=' AND ttl.isActive=1';
         $classCondition=' AND c.isActive=1';
        }
        else{
            $teacherId=$employeeId;
            if($timeTableLabelId==''){
                $timeTableLabelId=0;
            }
            $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }

        if ($teacherId == '') {
           $teacherId = 0;
        }
        $query = "SELECT
                        GROUP_CONCAT(c.classId) AS classId,
                        GROUP_CONCAT(SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) ORDER BY c.studyPeriodId SEPARATOR ' +') AS className,
                        GROUP_CONCAT(c.studyPeriodId) AS studyPeriodId,
                        COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                  FROM
                         ".TIME_TABLE_TABLE."  t,`group` g,`class` c,time_table_labels ttl
                  WHERE
                         t.groupId=g.groupId AND g.classId=c.classId AND t.employeeId=$teacherId
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND t.toDate IS NULL
                         AND t.timeTableLabelId=ttl.timeTableLabelId
                         $subjectCondition
                         $ttlCondition
                         $classCondition
                         $timeTableCondition
                         $timeTableLabelTypeConditions
                         AND t.timeTableId NOT IN
                              (
                                SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND (
                                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                          )
                                      AND t.isActive=1
                              )
                         $conditions
                         GROUP BY t.roomId,t.daysOfWeek,t.periodId
                         HAVING com > 1
                  UNION
                        SELECT
                              GROUP_CONCAT(c.classId) AS classId,
                              GROUP_CONCAT(SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) ORDER BY c.studyPeriodId SEPARATOR ' +') AS className,
                              GROUP_CONCAT(c.studyPeriodId) AS studyPeriodId,
                              COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                        FROM
                              `time_table_adjustment` t,`group` g,`class` c,time_table_labels ttl
                        WHERE
                           t.groupId=g.groupId AND g.classId=c.classId AND t.employeeId=$teacherId
                           AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                           AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                           $classCondition
                           AND t.timeTableLabelId=ttl.timeTableLabelId
                           $subjectCondition
                           $ttlCondition
                           $timeTableCondition
                           $timeTableLabelTypeConditions
                           AND t.isActive=1
                           AND (
                                  (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                   OR
                                  (t.toDate BETWEEN '$startDate' AND '$endDate')
                                    OR
                                  (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                               )
                           AND t.employeeId=$teacherId
                           $conditions
                           GROUP BY t.roomId,t.daysOfWeek,t.periodId
                           HAVING com > 1
                   ORDER BY studyPeriodId
                  ";
                 //ORDER BY timeTableLabelId

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 public function getSubjectGroups($subjectId) {

	 global $sessionHandler;
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
	 $sessionId=$sessionHandler->getSessionVariable('SessionId');

	 $query = "SELECT
                        DISTINCT gr.groupId, gr.groupName, gr.groupShort, gt.groupTypeName, gt.groupTypeCode
                 FROM
                         ".TIME_TABLE_TABLE." tt, `group` gr, `group_type` gt, subject sub
                 WHERE

                        gr.groupTypeId = gt.groupTypeId AND
                        gr.groupId = tt.groupId AND
                        tt.subjectId = sub.subjectId AND
						tt.subjectid =$subjectId AND
						tt.instituteId = $instituteId AND
						tt.sessionId = $sessionId";
				return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list all classes of a teacher across timetablelabels
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (10.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherAllClass($conditions='') {

        global $sessionHandler;
        $teacherId=$sessionHandler->getSessionVariable('EmployeeId');

        $query = "SELECT c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
        FROM  ".TIME_TABLE_TABLE."  t,`group` g,`class` c
        WHERE t.groupId=g.groupId AND g.classId=c.classId AND t.employeeId=$teacherId
        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
        AND t.toDate IS NULL
        $conditions GROUP BY c.classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of subjects of a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
   public function getTeacherSubject($conditions='') {

        global $sessionHandler;
        $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $loginDate=date('Y-m-d');
		$employeeIdCondtion = '';
		$employeeIdCondtionIner = '';
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		if($roleId != 1 ){
			$employeeIdCondtion =  "AND t.employeeId=$teacherId";
			$employeeIdCondtionIner =  " AND tt.employeeId=$teacherId";
		}
        $query = "SELECT
                            DISTINCT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks
                  FROM
                             ".TIME_TABLE_TABLE." t, `group` g, subject s, class c
                  WHERE
                            t.groupId=g.groupId AND g.classId=c.classId
                            AND t.subjectId=s.subjectId
                            AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.toDate IS NULL
                            $employeeIdCondtion
                            AND t.timeTableId NOT IN
                             (
                                SELECT
                                      tta.timeTableId
                                FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=tta.timeTableId
                                      $employeeIdCondtionIner
                                      AND tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                             )
                             $conditions
                             GROUP BY t.subjectId
                  UNION
                        SELECT
                            DISTINCT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks
                        FROM
                            time_table_adjustment t, `group` g, subject s, class c
                        WHERE
                            t.groupId=g.groupId AND g.classId=c.classId
                            AND t.subjectId=s.subjectId
                            AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            $employeeIdCondtion
                            AND t.isActive=1
                            AND '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions





                            GROUP BY t.subjectId
                  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getTeacherAdjustedSubject($conditions='',$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;
        $ttlCondition='';
        $timeTableCondition='';
        if($sessionHandler->getSessionVariable('RoleId')==2){
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $ttlCondition=' AND ttl.isActive=1';
        }
        else{
            $teacherId=$employeeId;
            $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }
        //$loginDate=date('Y-m-d');

        $query = "SELECT
                            DISTINCT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks
                  FROM
                             ".TIME_TABLE_TABLE." t, `group` g, subject s, class c,time_table_labels ttl
                  WHERE
                            t.groupId=g.groupId AND g.classId=c.classId
                            AND t.subjectId=s.subjectId
                            AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.toDate IS NULL
                            AND t.timeTableLabelId=ttl.timeTableLabelId
                            $ttlCondition
                            $timeTableCondition
                            $timeTableLabelTypeConditions
                            AND t.employeeId=$teacherId
                            $conditions
                            GROUP BY t.subjectId
							ORDER BY LENGTH(subjectName)+0,subjectName
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



public function getTeacherAdjustedSubjectMulti($conditions='',$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;
        $ttlCondition='';
        $timeTableCondition='';
        if($sessionHandler->getSessionVariable('RoleId')==2){
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $ttlCondition=' AND ttl.isActive=1';
        }
        else{
            $teacherId=$employeeId;
            $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }
        //$loginDate=date('Y-m-d');

        $query = "SELECT
                            DISTINCT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks,
                            COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                  FROM
                             ".TIME_TABLE_TABLE." t, `group` g, subject s, class c,time_table_labels ttl
                  WHERE
                            t.groupId=g.groupId AND g.classId=c.classId
                            AND t.subjectId=s.subjectId
                            AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.toDate IS NULL
                            AND t.timeTableLabelId=ttl.timeTableLabelId
                            $ttlCondition
                            $timeTableCondition
                            $timeTableLabelTypeConditions
                            AND t.employeeId=$teacherId
                            AND t.timeTableId NOT IN
                             (
                                SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND (
                                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                          )
                                      AND t.isActive=1
                             )
                             $conditions
                             GROUP BY t.subjectId,t.roomId,t.daysOfWeek,t.periodId
                             HAVING com>1
                  UNION
                        SELECT
                            DISTINCT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks,
                            COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                        FROM
                            time_table_adjustment t, `group` g, subject s, class c,time_table_labels ttl
                        WHERE
                            t.groupId=g.groupId AND g.classId=c.classId
                            AND t.subjectId=s.subjectId
                            AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.employeeId=$teacherId
                            AND t.timeTableLabelId=ttl.timeTableLabelId
                            $ttlCondition
                            $timeTableCondition
                            $timeTableLabelTypeConditions
                            AND t.isActive=1
                            AND (
                                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                          )
                                      AND t.isActive=1
                            $conditions
                            GROUP BY t.subjectId,t.roomId,t.daysOfWeek,t.periodId
                            HAVING com>1
                    ORDER BY LENGTH(subjectName)+0,subjectName
                  ";

       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of groups of a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherGroup($conditions='') {

         global $sessionHandler;
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $loginDate=date('Y-m-d');

         $query = "SELECT
                        g.groupId,g.groupName
                  FROM
                         ".TIME_TABLE_TABLE." t,`group` g,class c
                  WHERE
                        t.groupId=g.groupId AND
                        g.classId=c.classId AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND t.toDate IS NULL
                        AND t.employeeId=$teacherId
                        AND t.timeTableId NOT IN
                          (
                            SELECT
                                      tta.timeTableId
                                FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=tta.timeTableId
                                      AND tt.employeeId='$teacherId'
                                      AND tta.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                                      AND tta.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                                      AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                          )
                        $conditions
                        GROUP BY t.groupId
                  UNION
                        SELECT
                              g.groupId,g.groupName
                        FROM
                              time_table_adjustment t,`group` g,class c
                        WHERE
                            t.groupId=g.groupId AND
                            g.classId=c.classId AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.employeeId='$teacherId'
                            AND t.isActive=1
                            AND '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions
                            GROUP BY t.groupId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }





//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of groups of a subject
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (17.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getSubjectGroupTypes($subjectId,$classId) {

         global $sessionHandler;
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $loginDate=date('Y-m-d');

         $query = " SELECT
                            DISTINCT g.groupTypeId
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId = $subjectId
                    AND     c.classId=$classId
                    AND     t.timeTableId NOT IN
                                  (
                                    SELECT
                                      tta.timeTableId
                                    FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                    WHERE
                                      tt.timeTableId=tta.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                                  )
                            $conditions
                            GROUP BY t.groupId
                  UNION
                      SELECT
                            DISTINCT g.groupTypeId
                      FROM
                            time_table_adjustment t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                      WHERE
                            t.groupId=g.groupId
                            AND g.classId=c.classId
                            AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.timeTableLabelId = ttl.timeTableLabelId
                            AND t.employeeId=$teacherId
                            AND t.subjectId = $subjectId
                            AND c.classId=$classId
                            AND t.isActive=1
                            AND '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions
                            GROUP BY t.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getSubjectGroup($subjectId,$classId, $conditions = '',$timeTableLabelTypeConditions='') {

         global $sessionHandler;
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
		 $roleId = $sessionHandler->getSessionVariable('RoleId');
		 if($roleId != 1){
			$employeeIdCondition =" AND t.employeeId='$teacherId'";
			$employeeIdConditionIner = "AND tt.employeeId='$teacherId'";
		 }
         $loginDate=date('Y-m-d');

         $query =  "SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    $employeeIdCondition
                    AND     t.subjectId = '$subjectId'
                    AND     c.classId='$classId'
                    $timeTableLabelTypeConditions
                    AND     t.timeTableId NOT IN
                              (
                                SELECT
                                      tta.timeTableId
                                FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=tta.timeTableId
                                      $employeeIdConditionIner
                                      AND tta.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                                      AND tta.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                                      AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                              )
                            $conditions
                            GROUP BY t.groupId
                 UNION
                   SELECT
                            g.groupId,
                            g.groupName
                    FROM    time_table_adjustment t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                    AND     t.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    $employeeIdCondition
                    AND     t.subjectId = '$subjectId'
                    AND     c.classId='$classId'
                    $timeTableLabelTypeConditions
                    AND     t.isActive=1
                    AND     '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions
                            GROUP BY t.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



public function getAdjustedSubjectGroup($subjectId,$classId, $startDate,$endDate,$conditions = '',$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

         global $sessionHandler;
         $ttlCondition='';
         $timeTableCondition='';
         if($sessionHandler->getSessionVariable('RoleId')==2){
          $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
          $ttlCondition=' AND ttl.isActive=1';
         }
         else{
            $teacherId=$employeeId;
            $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
         }

        $query =  "SELECT
                            g.groupId,
                            g.groupName AS grpName,
                            g.groupShort AS groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId IN ( $subjectId )
                    AND     c.classId IN ( $classId )
                            $conditions
                            GROUP BY t.groupId
							ORDER BY groupName
                ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getAdjustedSubjectGroupMulti($subjectId,$classId, $startDate,$endDate,$conditions = '',$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

         global $sessionHandler;
         $ttlCondition='';
         $timeTableCondition='';
         if($sessionHandler->getSessionVariable('RoleId')==2){
          $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
          $ttlCondition=' AND ttl.isActive=1';
         }
         else{
            $teacherId=$employeeId;
            $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
         }

        $query =  "SELECT
                            GROUP_CONCAT(g.groupId) AS groupId,
                            GROUP_CONCAT(g.groupName ORDER BY g.groupName SEPARATOR ' + ' ) AS grpName,
                            GROUP_CONCAT(g.groupShort ORDER BY g.groupShort SEPARATOR '+') AS groupName,
                            COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId IN ( $subjectId )
                    AND     c.classId IN ( $classId )
                    AND     t.timeTableId NOT IN
                              (
                                SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND (
                                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                          )
                                      AND t.isActive=1
                              )
                            $conditions
                            GROUP BY t.roomId,t.daysOfWeek,t.periodId
                            HAVING com > 1
                 UNION
                   SELECT
                            GROUP_CONCAT(g.groupId) AS groupId,
                            GROUP_CONCAT(g.groupName ORDER BY g.groupName SEPARATOR ' + ' ) AS grpName,
                            GROUP_CONCAT(g.groupShort ORDER BY g.groupShort SEPARATOR '+') AS groupName,
                            COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                    FROM    time_table_adjustment t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId IN ( $subjectId )
                    AND     c.classId  IN ( $classId )
                    AND (
                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                             OR
                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                             OR
                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                         )
                    AND t.isActive=1
                    $conditions
                    GROUP BY t.roomId,t.daysOfWeek,t.periodId
                    HAVING com > 1
                 ORDER BY groupName
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getAdjustedSubjectGroupForComments($classId, $startDate,$endDate,$conditions = '',$timeTableLabelTypeConditions='') {

         global $sessionHandler;
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         //$loginDate=date('Y-m-d');

         $query =  "SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     ttl.isActive = 1
                    AND     t.employeeId=$teacherId
                    AND     c.classId=$classId
                    $timeTableLabelTypeConditions
                    AND     t.timeTableId NOT IN
                              (
                                SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND (
                                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                          )
                                      AND t.isActive=1
                              )
                            $conditions
                            GROUP BY t.groupId
                 UNION
                   SELECT
                            g.groupId,
                            g.groupName
                    FROM    time_table_adjustment t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     ttl.isActive = 1
                    AND     t.employeeId=$teacherId
                    AND     c.classId=$classId
                    $timeTableLabelTypeConditions
                    AND (
                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                             OR
                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                             OR
                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                         )
                    AND t.isActive=1
                            $conditions
                            GROUP BY t.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getTeacherAdjustedGroup($subjectId,$classId, $conditions = '') {

         global $sessionHandler;
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         //$loginDate=date('Y-m-d');

         $query =  "SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     ttl.isActive = 1
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId = $subjectId
                    AND     c.classId=$classId
                    AND     t.timeTableId NOT IN
                              (
                                SELECT
                                      tta.timeTableId
                                FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=tta.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                              )
                            $conditions
                            GROUP BY t.groupId
                 UNION
                   SELECT
                            g.groupId,
                            g.groupName
                    FROM    time_table_adjustment t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     ttl.isActive = 1
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId = $subjectId
                    AND     c.classId=$classId
                    AND     t.isActive=1
                    AND     '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions
                            GROUP BY t.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //to populate all groups across different timetable labels
    public function getAllSubjectGroup($conditions = '') {

         global $sessionHandler;
         $teacherCondition="";

         if($sessionHandler->getSessionVariable('RoleId')==2){
          $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
          $teacherCondition=" AND t.employeeId=$teacherId";
         }

        $query = "  SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE      t.groupId=g.groupId
                    AND        g.classId=c.classId
                    AND        c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND        t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND        t.toDate IS NULL
                    AND        t.timeTableLabelId = ttl.timeTableLabelId
                    $teacherCondition
                               $conditions
                               GROUP BY t.groupId
                    ORDER BY g.groupName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



    public function getSubjectGroupOtherTeacher($subjectId, $classId, $conditions = '') {
         global $sessionHandler;
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $loginDate=date('Y-m-d');

        $query = "  SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                            AND  g.classId=c.classId
                            AND  g.classId=$classId
                            AND  c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND  t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND  t.toDate IS NULL
                            AND  t.timeTableLabelId = ttl.timeTableLabelId
                            AND  ttl.isActive = 1
                            AND  t.employeeId != $teacherId
                            AND  t.subjectId = $subjectId
                            AND  t.timeTableId NOT IN
                              (
                                SELECT
                                      tta.timeTableId
                                FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=tta.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                              )
                            $conditions
                            GROUP BY t.groupId
                     UNION
                           SELECT
                                g.groupId,
                                g.groupName
                            FROM
                               time_table_adjustment t,
                               `group` g,
                               class c,
                               time_table_labels ttl
                            WHERE
                                t.groupId=g.groupId
                                AND  g.classId=c.classId
                                AND  g.classId=$classId
                                AND  c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                AND  t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                AND  t.timeTableLabelId = ttl.timeTableLabelId
                                AND  ttl.isActive = 1
                                AND  t.employeeId != $teacherId
                                AND  t.subjectId = $subjectId
                                AND  t.isActive=1
                                AND  '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                                     $conditions
                                GROUP BY t.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of test type of a subject
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (04.04.09)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    public function getTestTypeCategory($subjectId, $conditions = '') {

         global $sessionHandler;

       $query = "    SELECT
                            ttc.testTypeCategoryId,
                            ttc.testTypeName
                    FROM    test_type_category ttc,
                            subject s
                    WHERE    ttc.subjectTypeId=s.subjectTypeId
                    AND        s.subjectId = $subjectId
                            $conditions
                            ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

/*
This function is used to calculate student attendace upto a certain date
Author : Dipanjan  Bhattacharjee
//Date : 02.04.2009
*/
public function getStudentAttendanceTillDate($condition='',$limit='',$orderBy=' s.firstName'){

    global $sessionHandler;
    global $REQUEST_DATA;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');

    if($orderBy==''){
        $orderCnt='';
    }
    else{
        $orderCnt='ORDER BY '.$orderBy;
    }
    //echo  $orderCnt;
    //sg.classId=s.classId removed by abhiraj in INNER JOIN student_groups sg
    $query="SELECT
                   s.studentId,
                   CONCAT(s.firstName,' ',s.lastName) AS studentName,
                   CONVERT ( SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - if(LENGTH(c.rollNoSuffix) > 0, LENGTH(c.rollNoSuffix),0)), if(LENGTH( c.rollNoPrefix )>0,LENGTH( c.rollNoPrefix ),0)+1), UNSIGNED) AS numericRollNo,
                   SUM(IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )  AS attended,
                   SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,
                   att.toDate, att.fromDate
            FROM
                   student s
                   INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                   LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
                   INNER JOIN subject su ON su.subjectId = att.subjectId
                   INNER JOIN student_groups sg ON (sg.studentId=s.studentId AND sg.groupId IN (".trim($REQUEST_DATA['group']).") )
                   INNER JOIN class c ON c.classId = sg.classId
                   INNER JOIN time_table_classes ttc ON ttc.classId=c.classId
                   AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                   $condition
                   GROUP BY att.subjectId, att.studentId
                   $orderCnt ";
                  // echo $query;

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }
/*
this function is improved version of getStudentAttendanceTillDate() function
This function is used to calculate student attendace upto a certain date
Author :NISHU BINDAL
//Date : 23.02.2011
*/
  public function getStudentAttendanceTillDateNew($condition='',$limit='',$orderBy=' s.firstName'){

    global $sessionHandler;
    if($orderBy==''){
        $orderCnt='';
    }
    else{
        $orderCnt='ORDER BY '.$orderBy;
    }
    //echo  $orderCnt;
    $query="SELECT
                   s.studentId,
                   CONCAT(s.firstName,' ',s.lastName) AS studentName,
                   CONVERT ( SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - if(LENGTH(c.rollNoSuffix) > 0, LENGTH(c.rollNoSuffix),0)), if(LENGTH( c.rollNoPrefix )>0,LENGTH( c.rollNoPrefix ),0)+1), UNSIGNED) AS numericRollNo,
                   SUM(IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )  AS attended,
                   SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,
                   att.toDate, att.fromDate
            FROM
                   student s
                   INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                   LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
                   INNER JOIN subject su ON su.subjectId = att.subjectId
                   INNER JOIN class c ON c.classId = s.classId
                   AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."

                   $condition
                   GROUP BY att.subjectId, att.studentId
                   $orderCnt ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

 /*
This function is used to calculate student attendace for marking daily attendance
Author : Abhiraj
//Date : 17.03.2011
*/
public function getStudentAttendanceTillDateDailyAttendance($condition='',$limit='',$orderBy=' s.firstName'){

    global $sessionHandler;
    global $REQUEST_DATA;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');

    if($orderBy==''){
        $orderCnt='';
    }
    else{
        $orderCnt='ORDER BY '.$orderBy;
    }
    //echo  $orderCnt;
    //sg.classId=s.classId removed by abhiraj in INNER JOIN student_groups sg
    $query="SELECT
                   s.studentId,
                   CONCAT(s.firstName,' ',s.lastName) AS studentName,
                   CONVERT ( SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - if(LENGTH(c.rollNoSuffix) > 0, LENGTH(c.rollNoSuffix),0)), if(LENGTH( c.rollNoPrefix )>0,LENGTH( c.rollNoPrefix ),0)+1), UNSIGNED) AS numericRollNo,
                   SUM(IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )  AS attended,
                   SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,
                   att.toDate, att.fromDate
            FROM
                   student s
                   INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                   LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
                   INNER JOIN subject su ON su.subjectId = att.subjectId
                   INNER JOIN `group` grp ON grp.groupId in (".trim($REQUEST_DATA['group']).")
                   INNER JOIN class c ON c.classId = grp.classId
                   INNER JOIN time_table_classes ttc ON ttc.classId=c.classId
                   AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                   $condition
                   GROUP BY att.subjectId, att.studentId
                   $orderCnt ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of periods of a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (18.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherPeriod($conditions='',$date='') {

        global $sessionHandler;
        $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        //$loginDate=date('Y-m-d');

       /* $query = "SELECT DISTINCT p.periodId,CONCAT(p.periodNumber,'->',SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-3)) as periodNumber
        FROM  ".TIME_TABLE_TABLE." t,`group` g,class c,period p
        WHERE t.employeeId=$teacherId AND t.groupId=g.groupId AND g.classId=c.classId
         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
          AND t.periodId=p.periodId $conditions ";
       */

       $query = "SELECT
                        DISTINCT p.periodId,p.periodNumber
               FROM
                         ".TIME_TABLE_TABLE." t,`group` g,class c,period p
               WHERE
                        t.employeeId=$teacherId AND t.groupId=g.groupId AND g.classId=c.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND t.toDate IS NULL
                        AND t.periodId=p.periodId
                        AND t.timeTableId NOT IN
                          (
                            SELECT
                                      tta.timeTableId
                                FROM
                                      time_table_adjustment tta,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=tta.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND '".$date."' BETWEEN tta.fromDate AND tta.toDate
                                      AND tta.isActive=1
                          )
                        $conditions
               UNION
                   SELECT
                        DISTINCT p.periodId,p.periodNumber
                   FROM
                        time_table_adjustment t,`group` g,class c,period p
                   WHERE
                        t.employeeId=$teacherId
                        AND t.groupId=g.groupId
                        AND g.classId=c.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND t.periodId=p.periodId
                        AND t.isActive=1
                        AND '".$date."' BETWEEN t.fromDate AND t.toDate
                        $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTeacherAdjustedPeriodNew($conditions='') {
          
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
              
        $query = "SELECT
                        DISTINCT p.periodId,p.periodNumber,ps.slotAbbr,
                        CONCAT(p.startTime,' ',p.startAmPm,'-',p.endTime,' ',p.endAmPm) AS periodTime1,
                        CONCAT(SUBSTRING_INDEX(p.startTime,':',2),' ',p.startAmPm,'-',SUBSTRING_INDEX(p.endTime,':',2),' ',p.endAmPm) AS periodTime
                  FROM
                        period p,period_slot ps,  ".TIME_TABLE_TABLE." t
                  WHERE
                        p.periodSlotId=ps.periodSlotId
                        AND isActive = 1
                        AND t.toDate IS NULL 
                        AND t.periodId = p.periodId 
                        AND t.instituteId = '$instituteId' 
                        AND t.sessionId = '$sessionId'
                  $conditions      
                  ORDER BY  
                        slotAbbr,LENGTH(periodNumber)+0,periodNumber";
    
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
                        

public function getTeacherAdjustedPeriod($conditions='',$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;

        $timeTableCondition='';

        if($sessionHandler->getSessionVariable('RoleId')==2){
          $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        }
        else{
            $teacherId=$employeeId;
            $timeTableCondition=' AND t.timeTableLabelId='.$timeTableLabelId;
        }

       $query = "SELECT
                        DISTINCT p.periodId,p.periodNumber,ps.slotAbbr,
                        CONCAT(p.startTime,' ',p.startAmPm,'-',p.endTime,' ',p.endAmPm) AS periodTime1,
                        CONCAT(SUBSTRING_INDEX(p.startTime,':',2),' ',p.startAmPm,'-',SUBSTRING_INDEX(p.endTime,':',2),' ',p.endAmPm) AS periodTime
                 FROM
                         ".TIME_TABLE_TABLE." t,`group` g,class c,period p,period_slot ps
                 WHERE
                        t.employeeId=$teacherId
                        AND t.groupId=g.groupId
                        AND g.classId=c.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND t.toDate IS NULL
                        AND t.periodId=p.periodId
                        AND p.periodSlotId=ps.periodSlotId
                        $timeTableCondition
                        $timeTableLabelTypeConditions
                        AND t.timeTableId NOT IN
                          (
                            SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND (
                                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                          )
                                      AND t.isActive=1
                          )
                        $conditions
               UNION
                   SELECT
                        DISTINCT p.periodId,p.periodNumber,ps.slotAbbr,
                        CONCAT(p.startTime,' ',p.startAmPm,'-',p.endTime,' ',p.endAmPm) AS periodTime1,
                        CONCAT(SUBSTRING_INDEX(p.startTime,':',2),' ',p.startAmPm,'-',SUBSTRING_INDEX(p.endTime,':',2),' ',p.endAmPm) AS periodTime
                   FROM
                        time_table_adjustment t,`group` g,class c,period p,period_slot ps
                   WHERE
                        t.employeeId=$teacherId
                        AND t.groupId=g.groupId
                        AND g.classId=c.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND t.periodId=p.periodId
                        AND p.periodSlotId=ps.periodSlotId
                        $timeTableCondition
                        $timeTableLabelTypeConditions
                        AND t.isActive=1
                        AND (
                              (t.fromDate BETWEEN '$startDate' AND '$endDate')
                               OR
                              (t.toDate BETWEEN '$startDate' AND '$endDate')
                               OR
                              (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                           )
                        $conditions
                 ORDER BY  slotAbbr,LENGTH(periodNumber)+0,periodNumber
                 ";
    //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getTeacherAdjustedPeriodMulti($conditions='',$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;

        $timeTableCondition='';

        if($sessionHandler->getSessionVariable('RoleId')==2){
          $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        }
        else{
            $teacherId=$employeeId;
            $timeTableCondition=' AND t.timeTableLabelId='.$timeTableLabelId;
        }

       $query = "SELECT
                        DISTINCT p.periodId,
                        p.periodNumber,
                        ps.slotAbbr,
                        CONCAT(p.startTime,' ',p.startAmPm,'-',p.endTime,' ',p.endAmPm) AS periodTime1,
                        CONCAT(SUBSTRING_INDEX(p.startTime,':',2),' ',p.startAmPm,'-',SUBSTRING_INDEX(p.endTime,':',2),' ',p.endAmPm) AS periodTime,
                        COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                 FROM
                         ".TIME_TABLE_TABLE." t,`group` g,class c,period p,period_slot ps
                 WHERE
                        t.employeeId=$teacherId
                        AND t.groupId=g.groupId
                        AND g.classId=c.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND t.toDate IS NULL
                        AND t.periodId=p.periodId
                        AND p.periodSlotId=ps.periodSlotId
                        $timeTableCondition
                        $timeTableLabelTypeConditions
                        AND t.timeTableId NOT IN
                          (
                            SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      AND tt.employeeId=$teacherId
                                      AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                      AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                      AND (
                                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                                             OR
                                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                          )
                                      AND t.isActive=1
                          )
                        $conditions
                        GROUP BY t.roomId,t.daysOfWeek,t.periodId
                        HAVING com > 1
               UNION
                   SELECT
                        DISTINCT p.periodId,p.periodNumber,ps.slotAbbr,
                        CONCAT(p.startTime,' ',p.startAmPm,'-',p.endTime,' ',p.endAmPm) AS periodTime1,
                        CONCAT(SUBSTRING_INDEX(p.startTime,':',2),' ',p.startAmPm,'-',SUBSTRING_INDEX(p.endTime,':',2),' ',p.endAmPm) AS periodTime,
                        COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
                   FROM
                        time_table_adjustment t,`group` g,class c,period p,period_slot ps
                   WHERE
                        t.employeeId=$teacherId
                        AND t.groupId=g.groupId
                        AND g.classId=c.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND t.periodId=p.periodId
                        AND p.periodSlotId=ps.periodSlotId
                        $timeTableCondition
                        $timeTableLabelTypeConditions
                        AND t.isActive=1
                        AND (
                              (t.fromDate BETWEEN '$startDate' AND '$endDate')
                               OR
                              (t.toDate BETWEEN '$startDate' AND '$endDate')
                               OR
                              (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                           )
                        $conditions
                        GROUP BY t.roomId,t.daysOfWeek,t.periodId
                        HAVING com > 1
                 ORDER BY  slotAbbr,LENGTH(periodNumber)+0,periodNumber
                 ";
    //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//this function is used to fetch all periods
 public function getAllPeriods($conditions=''){
   global $sessionHandler;
   $instituteId = $sessionHandler->getSessionVariable('InstituteId');
   $sessionId   =  $sessionHandler->getSessionVariable('SessionId');
   $query="
           SELECT
                 DISTINCT p.periodId,p.periodNumber,ps.slotAbbr,
                 CONCAT(p.startTime,' ',p.startAmPm,'-',p.endTime,' ',p.endAmPm) AS periodTime1,
                 CONCAT(SUBSTRING_INDEX(p.startTime,':',2),' ',p.startAmPm,'-',SUBSTRING_INDEX(p.endTime,':',2),' ',p.endAmPm) AS periodTime

           FROM
                 period p,period_slot ps
           WHERE
                 p.periodSlotId=ps.periodSlotId
                 AND ps.instituteId=$instituteId
                 $conditions
           ORDER BY  slotAbbr,LENGTH(p.periodNumber)+0,p.periodNumber
           ";

   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of classs,subjects,groups of a teacher corresponding to a date
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherAllOptions($conditions='',$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;
        $ttlCondition='';
        $timeTableCondition='';
        if($sessionHandler->getSessionVariable('RoleId')==2){
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $ttlCondition=' AND ttl.isActive=1';
        }
        else{
           $teacherId=$employeeId;
           $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }
        //$loginDate=date('Y-m-d');

       $query = "
               SELECT
                    DISTINCT
                    p.periodId,p.periodNumber,
                    c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    s.subjectId,s.subjectCode,
                    g.groupId,
                    g.groupName AS grpName,
                    g.groupShort AS groupName
               FROM
                     ".TIME_TABLE_TABLE." t,`group` g,class c,period p,subject s,time_table_labels ttl
               WHERE
                    t.employeeId=$teacherId
                    AND t.subjectId=s.subjectId
                    AND s.hasAttendance=1
                    AND t.groupId=g.groupId
                    AND g.classId=c.classId
                    AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND t.toDate IS NULL
                    AND t.periodId=p.periodId
                    AND t.timeTableLabelId=ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND t.timeTableId NOT IN
                          (
                            SELECT
                                  t.timeTableId
                            FROM
                                  time_table_adjustment t,
                                   ".TIME_TABLE_TABLE." tt
                            WHERE
                                  tt.timeTableId=t.timeTableId
                                  AND tt.employeeId=$teacherId
                                  AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                  AND (
                                        (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (t.toDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                      )
                                  AND t.isActive=1
                          )
                    $conditions
               UNION
                    SELECT
                    DISTINCT
                    p.periodId,p.periodNumber,
                    c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    s.subjectId,s.subjectCode,
                    g.groupId,
                    g.groupName AS grpName,
                    g.groupShort AS groupName
               FROM
                    time_table_adjustment t,`group` g,class c,period p,subject s,time_table_labels ttl
               WHERE
                    t.employeeId=$teacherId
                    AND t.subjectId=s.subjectId
                    AND s.hasAttendance=1
                    AND t.groupId=g.groupId
                    AND g.classId=c.classId
                    AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND t.periodId=p.periodId
                    AND t.timeTableLabelId=ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND t.isActive=1
                    AND (
                          (t.fromDate BETWEEN '$startDate' AND '$endDate')
                           OR
                          (t.toDate BETWEEN '$startDate' AND '$endDate')
                           OR
                          (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                        )
                    AND t.isActive=1
                    $conditions";
            //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of classs,subjects,groups of a teacher corresponding to a date
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (26.06.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherSchedule($conditions='',$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;
        $ttlCondition='';
        $timeTableCondition='';
        if($sessionHandler->getSessionVariable('RoleId')==2){
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $ttlCondition=' AND ttl.isActive=1';
        }
        else{
           $teacherId=$employeeId;
           $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }
        //$loginDate=date('Y-m-d');

       $query = "
               SELECT
                    DISTINCT
                    c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    s.subjectId,s.subjectCode,
                    g.groupId,
                    g.groupName AS grpName,
                    g.groupShort AS groupName
               FROM
                     ".TIME_TABLE_TABLE." t,`group` g,class c,period p,subject s,time_table_labels ttl
               WHERE
                    t.employeeId=$teacherId
                    AND t.subjectId=s.subjectId
                    AND s.hasAttendance=1
                    AND t.groupId=g.groupId
                    AND g.classId=c.classId
                    AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND t.toDate IS NULL
                    AND t.periodId=p.periodId
                    AND t.timeTableLabelId=ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND t.timeTableId NOT IN
                          (
                            SELECT
                                  t.timeTableId
                            FROM
                                  time_table_adjustment t,
                                   ".TIME_TABLE_TABLE." tt
                            WHERE
                                  tt.timeTableId=t.timeTableId
                                  AND tt.employeeId=$teacherId
                                  AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                  AND (
                                        (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (t.toDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                      )
                                  AND t.isActive=1
                          )
                    $conditions
               UNION
                    SELECT
                    DISTINCT
                    c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    s.subjectId,s.subjectCode,
                    g.groupId,
                    g.groupName AS grpName,
                    g.groupShort AS groupName
               FROM
                    time_table_adjustment t,`group` g,class c,period p,subject s,time_table_labels ttl
               WHERE
                    t.employeeId=$teacherId
                    AND t.subjectId=s.subjectId
                    AND s.hasAttendance=1
                    AND t.groupId=g.groupId
                    AND g.classId=c.classId
                    AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND t.periodId=p.periodId
                    AND t.timeTableLabelId=ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND t.isActive=1
                    AND (
                          (t.fromDate BETWEEN '$startDate' AND '$endDate')
                           OR
                          (t.toDate BETWEEN '$startDate' AND '$endDate')
                           OR
                          (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                        )
                    AND t.isActive=1
                    $conditions";
            //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getTeacherAllOptionsMulti($conditions='',$startDate,$endDate,$timeTableLabelId='',$employeeId='',$timeTableLabelTypeConditions='') {

        global $sessionHandler;
        $ttlCondition='';
        $timeTableCondition='';
        if($sessionHandler->getSessionVariable('RoleId')==2){
         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $ttlCondition=' AND ttl.isActive=1';
        }
        else{
           $teacherId=$employeeId;
           $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }
        //$loginDate=date('Y-m-d');

       $query = "
               SELECT
                    DISTINCT
                    p.periodId,p.periodNumber,
                    c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    s.subjectId,s.subjectCode,
                    g.groupId,
                    g.groupName AS grpName,
                    g.groupShort AS groupName,
                    COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
               FROM
                     ".TIME_TABLE_TABLE." t,`group` g,class c,period p,subject s,time_table_labels ttl
               WHERE
                    t.employeeId=$teacherId
                    AND t.subjectId=s.subjectId
                    AND s.hasAttendance=1
                    AND t.groupId=g.groupId
                    AND g.classId=c.classId
                    AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND t.toDate IS NULL
                    AND t.periodId=p.periodId
                    AND t.timeTableLabelId=ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND t.timeTableId NOT IN
                          (
                            SELECT
                                  t.timeTableId
                            FROM
                                  time_table_adjustment t,
                                   ".TIME_TABLE_TABLE." tt
                            WHERE
                                  tt.timeTableId=t.timeTableId
                                  AND tt.employeeId=$teacherId
                                  AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                  AND (
                                        (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (t.toDate BETWEEN '$startDate' AND '$endDate')
                                         OR
                                        (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                                      )
                                  AND t.isActive=1
                          )
                    $conditions
                    GROUP BY t.roomId,t.daysOfWeek,t.periodId
                    HAVING com > 1
               UNION
                    SELECT
                    DISTINCT
                    p.periodId,p.periodNumber,
                    c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                    s.subjectId,s.subjectCode,
                    g.groupId,
                    g.groupName AS grpName,
                    g.groupShort AS groupName,
                    COUNT(CONCAT_WS('~',t.roomId,t.daysOfWeek,t.periodId)) AS com
               FROM
                    time_table_adjustment t,`group` g,class c,period p,subject s,time_table_labels ttl
               WHERE
                    t.employeeId=$teacherId
                    AND t.subjectId=s.subjectId
                    AND s.hasAttendance=1
                    AND t.groupId=g.groupId
                    AND g.classId=c.classId
                    AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND t.periodId=p.periodId
                    AND t.timeTableLabelId=ttl.timeTableLabelId
                    $ttlCondition
                    $timeTableCondition
                    $timeTableLabelTypeConditions
                    AND t.isActive=1
                    AND (
                          (t.fromDate BETWEEN '$startDate' AND '$endDate')
                           OR
                          (t.toDate BETWEEN '$startDate' AND '$endDate')
                           OR
                          (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                        )
                    AND t.isActive=1
                    $conditions
                    GROUP BY t.roomId,t.daysOfWeek,t.periodId
                    HAVING com > 1";
            //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//**********************FUNCTIONS NEEDED FOR DISPLAING THREE DROP DOWN IN STUDENTSEARCH.PHP IN TEACHER MODULE(End)************



//**********************FUNCTIONS NEEDED FOR DISPLAING VARIOUS STUDENT INFORMATION IN TEACHER MODULE*************************



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students having optional subject
// upon selection of class,subject and group dropdown
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (29.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getOptionalStudentList($classId='',$subjectId='' ) {
 global $REQUEST_DATA;
 $query="SELECT studentId
          FROM student_optional_subject sos,subject_to_class sc
          WHERE
          sos.classId=sc.classId AND sos.subjectId=sc.subjectId
          AND sc.classId='$classId' AND sc.subjectId='$subjectId' AND sc.optional=1" ;

         $optList=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         $sid="";
         if(count($optList)>0 and is_array($optList)){
             $cnt=count($optList);
             for($i=0;$i<$cnt;$i++){
               if($sid==""){
                   $sid=$optList[$i]['studentId'];
               }
               else{
                   $sid=$sid.",".$optList[$i]['studentId'];
               }
             }
         }

         return $sid;
}

//this function will check whether a group is optional group or not
public function checkOptionalGroup($groupId){
    $query="SELECT
                  g.groupId,g.isOptional
            FROM
                  `group` g
            WHERE
                   g.groupId IN ($groupId)";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students in student info search
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getSearchStudentList($conditions='', $limit = '', $orderBy='') {

         global $REQUEST_DATA;

         $class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0));
         $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
         $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
         $timeTableLabelId=((trim($REQUEST_DATA['timeTableLabelId'])!=""?trim($REQUEST_DATA['timeTableLabelId']):0));
         $timeTableCondition='';
         if($timeTableLabelId!=0 or $timeTableLabelId!=''){
           $timeTableCondition=' AND ttc.timeTableLabelId='.$timeTableLabelId;
         }

         /*
         //get list of students having this subject as optional
         $extC= $this->getOptionalStudentList($class,$subject);
         if($extC!=""){
             $extC=" s.studentId In ($extC) AND";
         }
         */
         $optionalGroup=0;
         if($group!=0){
             //check whether it is optional group or not
             $optGrArray=$this->checkOptionalGroup($group);
             if(count($optGrArray)>0 and is_array($optGrArray)){
                 $optionalGroup=$optGrArray[0]['isOptional'];
             }
             else{
                 $optionalGroup=0;
             }
         }


    if($optionalGroup==0){ //if this group is not optional
     $query= "SELECT
                        DISTINCT s.studentId,c.classId,g.groupId,
                        CONCAT(s.firstName,' ' ,s.lastName) as studentName,
                        s.isLeet,
                        s.fatherName,s.motherName,s.guardianName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(c.rollNoSuffix)) , LENGTH( c.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        deg.degreeName,deg.degreeAbbr,br.branchName,
                        br.branchCode,ba.batchName,
                        IF(s.corrCityId Is NULL,'--',(SELECT cityName FROM city WHERE cityId = s.corrCityId)) AS cityName,
                        s.fatherTitle,s.fatherName,s.fatherMobileNo,s.fatherEmail,
                        s.motherTitle,s.motherName,s.motherMobileNo,s.motherEmail,
                        s.guardianTitle,s.guardianName,s.guardianMobileNo,s.guardianEmail,
                        s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                FROM
                   student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba,student_groups sg,
                   time_table_classes ttc
                WHERE
                  s.studentId=sg.studentId
                  AND sg.classId=c.classId
                  AND sg.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND c.degreeId=deg.degreeId
                  AND c.branchId=br.branchId
                  AND c.batchId=ba.batchId
                  AND c.classId=ttc.classId
                  $timeTableCondition
             $conditions
             GROUP BY s.studentId
             ORDER BY $orderBy
             $limit
             ";
    }
    else{
        $query= "SELECT
                        DISTINCT s.studentId,c.classId,g.groupId,
                        CONCAT(s.firstName,' ' ,s.lastName) as studentName,
                        s.isLeet,
                        s.fatherName,s.motherName,s.guardianName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(c.rollNoSuffix)) , LENGTH( c.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        deg.degreeName,deg.degreeAbbr,br.branchName,
                        br.branchCode,ba.batchName,
                        IF(s.corrCityId Is NULL,'--',(SELECT cityName FROM city WHERE cityId = s.corrCityId)) AS cityName,
                        s.fatherTitle,s.fatherName,s.fatherMobileNo,s.fatherEmail,
                        s.motherTitle,s.motherName,s.motherMobileNo,s.motherEmail,
                        s.guardianTitle,s.guardianName,s.guardianMobileNo,s.guardianEmail,
                        s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                FROM
                   student s,class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc,
                   time_table_classes ttc
                WHERE
                  s.studentId=sc.studentId
                  AND sc.classId=c.classId
                  AND sc.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND c.degreeId=deg.degreeId
                  AND c.branchId=br.branchId
                  AND c.batchId=ba.batchId
                  AND c.classId=ttc.classId
                  $timeTableCondition
             $conditions
             GROUP BY s.studentId
             ORDER BY $orderBy
             $limit";

    }
          //echo $query;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students
//
//$conditions :db clauses
// Created on : (08.02.2011)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function getStudentsList($conditions='', $limit = '', $orderBy='') {

		global $REQUEST_DATA;
		$class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0));
		$subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
		$group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
		$timeTableLabelId=((trim($REQUEST_DATA['timeTableLabelId'])!=""?trim($REQUEST_DATA['timeTableLabelId']):0));
		$timeTableCondition='';
		if($timeTableLabelId!=0 or $timeTableLabelId!=''){
			$timeTableCondition=' AND ttc.timeTableLabelId='.$timeTableLabelId;
		}
		$optionalGroup=0;
		if($group!=0){
			//check whether it is optional group or not
			$optGrArray=$this->checkOptionalGroup($group);
			if(count($optGrArray)>0 and is_array($optGrArray)){
				$optionalGroup=$optGrArray[0]['isOptional'];
			}
			else{
				$optionalGroup=0;
			}
		}
		if($optionalGroup==0){ //if this group is not optional
			$query="SELECT
							DISTINCT s.studentId,
							CONCAT(IFNULL(s.firstName,''),' ' ,IFNULL(s.lastName,'')) as studentName,
							IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                            IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo
					FROM
						student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba,student_groups sg,
						time_table_classes ttc
					WHERE
						s.studentId=sg.studentId
						AND sg.classId=c.classId
						AND sg.groupId=g.groupId
						AND sc.classId=c.classId
						AND c.degreeId=deg.degreeId
						AND c.branchId=br.branchId
						AND c.batchId=ba.batchId
						AND c.classId=ttc.classId
						$timeTableCondition
					$conditions
					GROUP BY s.studentId
					ORDER BY $orderBy
					$limit
				";
		}
		else{
			$query= "SELECT
							DISTINCT s.studentId,
                            CONCAT(IFNULL(s.firstName,''),' ' ,IFNULL(s.lastName,'')) as studentName,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                            IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo
					FROM
						student s,class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc,
						time_table_classes ttc
					WHERE
						s.studentId=sc.studentId
						AND sc.classId=c.classId
						AND sc.groupId=g.groupId
						AND sc.classId=c.classId
						AND c.degreeId=deg.degreeId
						AND c.branchId=br.branchId
						AND c.batchId=ba.batchId
						AND c.classId=ttc.classId
						$timeTableCondition
					$conditions
					GROUP BY s.studentId
					ORDER BY $orderBy
					$limit";

		}
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students in student info search
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
 public function getSearchTotalStudent($conditions='') {

    global $REQUEST_DATA;

     $class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0));
     $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
     $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
     $timeTableLabelId=((trim($REQUEST_DATA['timeTableLabelId'])!=""?trim($REQUEST_DATA['timeTableLabelId']):0));
     $timeTableCondition='';
     if($timeTableLabelId!=0 or $timeTableLabelId!=''){
        $timeTableCondition=' AND ttc.timeTableLabelId='.$timeTableLabelId;
     }

    /*
     //get list of students having this subject as optional
     $extC= $this->getOptionalStudentList($class,$subject);
     if($extC!=""){
         $extC=" s.studentId In ($extC) AND";
     }
    */
    $optionalGroup=0;
    if($group!=0){
         //check whether it is optional group or not
         $optGrArray=$this->checkOptionalGroup($group);
         if(count($optGrArray)>0 and is_array($optGrArray)){
             $optionalGroup=$optGrArray[0]['isOptional'];
         }
         else{
             $optionalGroup=0;
         }
    }
    if($optionalGroup==0){ //if this group is not optional
      $query=  "SELECT
                    COUNT(DISTINCT s.studentId) AS totalRecords
                FROM
                   student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba,student_groups sg,
                   time_table_classes ttc
                WHERE
                $extC
                  s.studentId=sg.studentId
                  AND sg.classId=c.classId
                  AND sg.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND c.degreeId=deg.degreeId
                  AND c.branchId=br.branchId
                  AND c.batchId=ba.batchId
                  AND c.classId=ttc.classId
                  $timeTableCondition
                  $conditions   ";
    }
    else{
        $query= "SELECT
                    COUNT(DISTINCT s.studentId) AS totalRecords
                FROM
                   student s,class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc,
                   time_table_classes ttc
                WHERE
                  s.studentId=sc.studentId
                  AND sc.classId=c.classId
                  AND s.classId = c.classId
                  AND sc.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND c.degreeId=deg.degreeId
                  AND c.branchId=br.branchId
                  AND c.batchId=ba.batchId
                  AND c.classId=ttc.classId
                  $timeTableCondition
                  $conditions";

    }
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }






//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students upon selection of class,subject and group dropdown
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getStudentList($conditions='', $limit = '', $orderBy=' s.firstName') {

        global $REQUEST_DATA;

         $class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0));
         $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
         $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));

         //get list of students having this subject as optional
         $extC= $this->getOptionalStudentList($class,$subject);
         if($extC!=""){
             $extC=" s.studentId In ($extC) AND";
         }

         if(trim($REQUEST_DATA['studentRollNo'])=="")  //if info of all students are needed
         {
          $query="SELECT
                   s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
                   s.fatherName,s.motherName,s.guardianName,
                   s.rollNo,s.universityRollNo,
                   deg.degreeName,deg.degreeAbbr,br.branchName,br.branchCode,ba.batchName,
                   s.fatherTitle,s.fatherName,s.fatherMobileNo,s.fatherEmail,
                   s.motherTitle,s.motherName,s.motherMobileNo,s.motherEmail,
                   s.guardianTitle,s.guardianName,s.guardianMobileNo,s.guardianEmail,
                   s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
                 FROM
                   student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba
                 WHERE
                  $extC
                  s.classId=c.classId
                  AND c.classId=g.classId
                  AND sc.classId=s.classId
                  AND c.degreeId=deg.degreeId
                  AND c.branchId=br.branchId
                  AND c.batchId=ba.batchId AND
                  s.classId=".$class."
                  AND g.groupId=".$group."
                  AND sc.subjectId=".$subject."
                  $conditions
                  ORDER BY $orderBy
                  $limit ";
          //echo $query;
         }
        else{   //if info of a single student is needed

          $query="SELECT s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
                         s.fatherName,s.motherName,s.guardianName,
                         s.rollNo,s.universityRollNo,
                         deg.degreeName,deg.degreeAbbr,br.branchName,br.branchCode,ba.batchName,
                         s.fatherTitle,s.fatherName,s.fatherMobileNo,s.fatherEmail,
                         s.motherTitle,s.motherName,s.motherMobileNo,s.motherEmail,
                         s.guardianTitle,s.guardianName,s.guardianMobileNo,s.guardianEmail,
                         s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
          FROM student s,degree deg,branch br,batch ba,class c
          WHERE s.classId=c.classId AND c.degreeId=deg.degreeId AND c.branchId=br.branchId AND c.batchId=ba.batchId
           AND rollNo LIKE '".trim(add_slashes($REQUEST_DATA['studentRollNo']))."%' ORDER BY $orderBy $limit ";

        }
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students upon selection of class,subject and group dropdown
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTotalStudent($conditions='') {

        global $REQUEST_DATA;

         $class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0));
         $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
         $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));

          //get list of students having this subject as optional
         $extC= $this->getOptionalStudentList($class,$subject);
         if($extC!=""){
             $extC=" s.studentId In ($extC) AND";
         }

        if(trim($REQUEST_DATA['studentRollNo'])=="")  //if info of all students are needed
         {
          $query="SELECT COUNT(*) AS totalRecords
          FROM student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba
          WHERE $extC s.classId=c.classId  AND c.classId=g.classId AND sc.classId=s.classId
          AND c.degreeId=deg.degreeId AND c.branchId=br.branchId AND c.batchId=ba.batchId AND
          s.classId=".$class." AND g.groupId=".$group."
          AND sc.subjectId=".$subject." $conditions";
         }
        else{   //if info of a single student is needed

          $query="SELECT COUNT(*) AS totalRecords
          FROM student s,degree deg,branch br,batch ba,class c
          WHERE s.classId=c.classId AND c.degreeId=deg.degreeId AND c.branchId=br.branchId AND c.batchId=ba.batchId
           AND rollNo LIKE '".trim(add_slashes($REQUEST_DATA['studentRollNo']))."%'";

        }

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations regarding a specific student
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
  public function getStudentInformation($studentId,$conditions='')
  {
      $query="SELECT * FROM student WHERE studentId=$studentId ";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//---------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations(university,batch,branch) i.e,  "course" regarding a specific student
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------------------
  public function getStudentCourse($studentId,$conditions='')
  {
      $query="SELECT un.universityName,un.universityAbbr,ins.instituteName,deg.degreeName,deg.degreeAbbr,
      br.branchName,br.branchCode,ba.batchName,ba.batchYear,stp.periodName
      FROM class c,student s,university un,institute ins,degree deg,branch br,batch ba ,study_period stp
      WHERE s.studentId=".$studentId." AND s.classId=c.classId AND c.universityId=un.universityId AND
      c.instituteId=ins.instituteId AND c.degreeId=deg.degreeId AND c.branchID=br.branchId AND c.batchId=ba.batchId AND c.studyPeriodId=stp.studyPeriodId $conditions";

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//---------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING  groups list regarding a specific student
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------------------
  public function getStudentGroups($studentId,$classId='',$orderBy=' studyPeriod',$limit='')
  {
      global $sessionHandler;
      $sessionId=$sessionHandler->getSessionVariable('SessionId');
      $instituteId=$sessionHandler->getSessionVariable('InstituteId');

      $extC='';
      if($classId!=0){
          $extC=' AND c.classId='.$classId;
      }

        $query="      SELECT
                            gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod
                      FROM
                           `group` gr,student s,group_type gt,student_groups sg,`class` c
                      WHERE
                       s.studentId=$studentId
                       AND gr.classId=c.classId
                       AND s.studentId=sg.studentId
                       AND gr.groupId=sg.groupId
                       AND gr.groupTypeId=gt.groupTypeId
                       AND sg.sessionId=$sessionId
                       AND sg.instituteId=$instituteId
                       $extC
                       $conditions
                   UNION
                       SELECT
                            gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod
                      FROM
                           `group` gr,student s,group_type gt,student_optional_subject sg,`class` c
                      WHERE
                       s.studentId=$studentId
                       AND gr.classId=c.classId
                       AND s.studentId=sg.studentId
                       AND gr.groupId=sg.groupId
                       AND gr.groupTypeId=gt.groupTypeId
                       AND c.sessionId=$sessionId
                       AND c.instituteId=$instituteId
                       $extC
                       $conditions

                       ORDER BY $orderBy
                       $limit
              ";

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//---------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING  total groups regarding a specific student
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (4.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------------------
  public function getTotalStudentGroups($studentId,$classId='',$conditions='')
  {
      global $sessionHandler;
      $sessionId=$sessionHandler->getSessionVariable('SessionId');
      $instituteId=$sessionHandler->getSessionVariable('InstituteId');

      $extC='';
      if($classId!=0){
          $extC=' AND c.classId='.$classId;
      }

      $query="        SELECT
                            COUNT(*) AS totalRecords
                      FROM
                           `group` gr,student s,group_type gt,student_groups sg,`class` c
                      WHERE
                       s.studentId=$studentId
                       AND gr.classId=c.classId
                       AND s.studentId=sg.studentId
                       AND gr.groupId=sg.groupId
                       AND gr.groupTypeId=gt.groupTypeId
                       AND sg.sessionId=$sessionId
                       AND sg.instituteId=$instituteId
                       $extC
                       $conditions
                    UNION
                       SELECT
                            COUNT(*) AS totalRecords
                      FROM
                           `group` gr,student s,group_type gt,student_optional_subject sg,`class` c
                      WHERE
                       s.studentId=$studentId
                       AND gr.classId=c.classId
                       AND s.studentId=sg.studentId
                       AND gr.groupId=sg.groupId
                       AND gr.groupTypeId=gt.groupTypeId
                       AND c.sessionId=$sessionId
                       AND c.instituteId=$instituteId
                       $extC
                       $conditions
              ";

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


/*
//---------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING  practicalgroup regarding a specific student
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------------------
  public function getStudentPrGroup($studentId,$conditions='')
  {
      $query="SELECT groupName
      FROM `group`,student
      WHERE studentId=$studentId AND student.prGroupId=group.groupId  $conditions ";

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }
  */


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations of subjects  regarding a specific student
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (15.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

public function getSubjectClass($classId) {
        $query = "SELECT sub.subjectId, sub.subjectName
        FROM subject sub, subject_to_class subcls
        WHERE sub.subjectId = subcls.subjectId AND subcls.classId='$classId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations of marks obtained  regarding a specific student
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (31.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getStudentMarksClass($subjectId,$studentId) {
        global $REQUEST_DATA;
        $query = "SELECT
                  sub.subjectName,IF( ttype.examType = 'PC', 'Internal', 'External' ) as type,ttype.testTypeName,sum(tm.maxMarks) as TotalMarks,SUM(tm.marksScored) as MarksObtained
                  FROM
                  ".TEST_MARKS_TABLE." tm, ".TEST_TABLE." tt,test_type_category ttype, subject sub
                  WHERE tt.testId=tm.testId and tm.studentId='$studentId' and tm.subjectId='$subjectId' and
                  tt.testTypeId=ttype.testTypeId  and tm.subjectId=sub.subjectId
                  GROUP BY conductingAuthority";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH STUDENT MARKS
//
// Author :Rajeev Aggarwal
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getStudentMarks($studentId,$classId='',$orderBy=' studyPeriod',$limit='') {
        /*$query = "SELECT su.subjectName, IF( tt.conductingAuthority =1, 'Internal', 'External' ) AS
                examType, CONCAT( t.testAbbr, t.testIndex ) AS testName, su.subjectCode, (
                tm.maxMarks
                ) AS totalMarks, (
                tm.marksScored
                ) AS obtained
                FROM test t, test_type tt, test_marks tm, student s, subject su
                WHERE t.testTypeId = tt.testTypeId
                AND t.testId = tm.testId
                AND tm.studentId = s.studentId
                AND tm.subjectId = su.subjectId
                AND tm.studentId =$studentId
                ORDER BY tm.subjectId, tt.conductingAuthority, t.testId, tm.studentId";*/
           /*
           $query = "SELECT su.subjectName,  IF( tt.conductingAuthority =1, 'Internal', 'External' ) AS
                    examType,tt.testTypeName,CONCAT( t.testAbbr, t.testIndex ) AS testName,  su.subjectCode, (
                    tm.maxMarks
                    ) AS totalMarks, IF( tm.isMemberOfClass =0, 'Not MOC',
                    IF(isPresent=1,tm.marksScored,'A'))  AS Obtained
                    FROM test t, test_type tt, test_marks tm, student s, subject su
                    WHERE t.testTypeId = tt.testTypeId
                    AND t.testId = tm.testId
                    AND tm.studentId = s.studentId
                    AND tm.subjectId = su.subjectId
                    AND tm.studentId =$studentId
                    ORDER BY tm.subjectId, tt.conductingAuthority, t.testId, tm.studentId
                    ";
            */

           $extC='';
           if($classId!=0){
               $extC=' AND c.classId='.$classId;
           }

          $query = "SELECT
                          CONCAT(su.subjectName,'(',su.subjectCode,')') AS subjectName,
                          tt.testTypeName,
                          DATE_FORMAT(t.testDate,'%d-%b-%Y') AS testDate,
                          SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod,
                          emp.employeeName,
                          CONCAT( t.testAbbr, t.testIndex ) AS  testName,
                          (tm.maxMarks) AS totalMarks,
                          IF( tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A'))  AS obtainedMarks,
                          IF( tt.examType = 'PC', 'Internal', 'External' ) AS examType
                    FROM
                       ".TEST_TABLE." t, test_type_category tt, ".TEST_MARKS_TABLE." tm, student s, subject su,`class` c,employee emp
                    WHERE
                       t.testTypeCategoryId = tt.testTypeCategoryId
                       AND t.classId=c.classId
                       AND t.employeeId=emp.employeeId
                       AND t.testId = tm.testId
                       AND tm.studentId = s.studentId
                       AND tm.subjectId = su.subjectId
                       AND tm.studentId =$studentId
                       $extC
                       ORDER BY $orderBy
                       $limit
                    ";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH Totla STUDENT MARKS
//
// Author : Dipanjan Bhattacharjee
// Created on : (05.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalStudentMarks($studentId,$classId='') {

           $extC='';
           if($classId!=0){
               $extC=' AND c.classId='.$classId;
           }

          $query = "SELECT
                          COUNT(*) AS totalRecords
                    FROM
                       ".TEST_TABLE." t, test_type_category tt, ".TEST_MARKS_TABLE." tm, student s, subject su,`class` c,employee emp
                    WHERE
                        t.testTypeCategoryId = tt.testTypeCategoryId
                       AND t.classId=c.classId
                       AND t.employeeId=emp.employeeId
                       AND t.testId = tm.testId
                       AND tm.studentId = s.studentId
                       AND tm.subjectId = su.subjectId
                       AND tm.studentId =$studentId
                       $extC
                    ";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE
// Author :Dipanjan Bhattacharjee
// Created on : (05-12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

public function getStudentAttendance($studentId,$classId='',$conditions,$orderBy = ' subjectName',$limit=''){

    global $sessionHandler;
    $extC='';
    if($classId!=0){
        $extC=' AND c.classId='.$classId;
    }
   $query="SELECT
                CONCAT(su.subjectName,'(',su.subjectCode,')') as subjectName ,su.subjectId,
                CONCAT(gr.groupName,' ',grt.groupTypeCode) AS groupName,
                emp.employeeName ,
                ROUND(SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
                DATE_FORMAT( MIN( att.fromDate ) , '%d-%b-%y' ) as fromDate ,
                DATE_FORMAT( MAX( att.toDate ) , '%d-%b-%y' ) as toDate ,
                SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod
            FROM
                student s,`group` gr,group_type grt,
                subject su,employee emp,`class` c,
                ".ATTENDANCE_TABLE." att
            LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
            WHERE
               att.studentId=s.studentId
               AND att.classId=c.classId
               AND att.groupId=gr.groupId
               AND att.subjectId=su.subjectId
               AND att.employeeId=emp.employeeId
               AND gr.groupTypeId=grt.groupTypeId
               AND s.studentId=$studentId
               AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
               AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
               AND su.hasAttendance=1
               $extC
               $conditions
               GROUP BY att.subjectId, att.groupId, emp.employeeId
               ORDER BY $orderBy
               $limit
          ";


    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET total no STUDENT ATTENDANCE
// Author :Dipanjan Bhattacharjee
// Created on : (05-12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

public function getTotalStudentAttendance($studentId,$classId='',$conditions,$orderBy = ' studyPeriod',$limit=''){

    global $sessionHandler;
    $extC='';
    if($classId!=0){
        $extC=' AND c.classId='.$classId;
    }
    $query="
            SELECT
                COUNT(*) AS totalRecords
            FROM
                student s,`group` gr,group_type grt,
                subject su,employee emp,`class` c,
                ".ATTENDANCE_TABLE." att
            LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
            WHERE
               att.studentId=s.studentId
               AND att.classId=c.classId
               AND att.groupId=gr.groupId
               AND att.subjectId=su.subjectId
               AND att.employeeId=emp.employeeId
               AND gr.groupTypeId=grt.groupTypeId
               AND s.studentId=$studentId
               AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
               AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
               AND su.hasAttendance=1
               $extC
               $conditions
               GROUP BY att.subjectId, att.groupId, emp.employeeId
           ";


    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }



//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE(Consolidated)
// Author :Dipanjan Bhattacharjee
// Created on : (05-12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

public function getConsolidatedStudentAttendance($studentId,$classId='',$conditions,$orderBy = ' subjectName',$limit=''){

    global $sessionHandler;
    $extC='';
    if($classId!=0){
        $extC=' AND c.classId='.$classId;
    }
    $query="SELECT
                CONCAT(su.subjectName,'(',su.subjectCode,')') as subjectName ,
                CONCAT(gr.groupName,' ',grt.groupTypeCode) AS groupName,
                emp.employeeName ,
                ROUND(SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
                SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
                DATE_FORMAT( MIN( att.fromDate ) , '%d-%b-%y' ) as fromDate ,
                DATE_FORMAT( MAX( att.toDate ) , '%d-%b-%y' ) as toDate ,
                SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-1) AS studyPeriod
            FROM
                student s,`group` gr,group_type grt,
                subject su,employee emp,`class` c,
                ".ATTENDANCE_TABLE." att
            LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
            WHERE
               att.studentId=s.studentId
               AND att.classId=c.classId
               AND att.groupId=gr.groupId
               AND att.subjectId=su.subjectId
               AND att.employeeId=emp.employeeId
               AND gr.groupTypeId=grt.groupTypeId
               AND s.studentId=$studentId
               AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
               AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
               AND su.hasAttendance=1
               $extC
               $conditions
               GROUP BY att.subjectId
               ORDER BY $orderBy
               $limit
          ";


    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET total no STUDENT ATTENDANCE(Consolidated)
// Author :Dipanjan Bhattacharjee
// Created on : (05-12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------

public function getConsolidatedTotalStudentAttendance($studentId,$classId='',$conditions,$orderBy = ' studyPeriod',$limit=''){

    global $sessionHandler;
    $extC='';
    if($classId!=0){
        $extC=' AND c.classId='.$classId;
    }
    $query="
            SELECT
                COUNT(*) AS totalRecords
            FROM
                student s,`group` gr,group_type grt,
                subject su,employee emp,`class` c,
                ".ATTENDANCE_TABLE." att
            LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
            WHERE
               att.studentId=s.studentId
               AND att.classId=c.classId
               AND att.groupId=gr.groupId
               AND att.subjectId=su.subjectId
               AND att.employeeId=emp.employeeId
               AND gr.groupTypeId=grt.groupTypeId
               AND s.studentId=$studentId
               AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
               AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
               AND su.hasAttendance=1
               $extC
               $conditions
               GROUP BY att.subjectId
           ";


    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }





//**********************FUNCTIONS NEEDED FOR DISPLAING VARIOUS STUDENT INFORMATION IN TEACHER MODULE(End)*********************



//**********************FUNCTIONS NEEDED FOR DISPLAING STUDENT ATTENDANCE SUBMODULE IN TEACHER MODULE*********************

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations of attendance_bulk
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (15.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getBulkAttendanceList($conditions=''){
    global $REQUEST_DATA;

    $class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0 ));
    $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
    $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
    $fromDate=((trim($REQUEST_DATA['startDate'])!=""?$REQUEST_DATA['startDate']:'0'));
    $toDate=((trim($REQUEST_DATA['endDate'])!=""?$REQUEST_DATA['endDate']:'0'));

    $query="SELECT
                s.studentId,abl.attendanceId,abl.lectureDelivered,abl.lectureAttended,
                abl.isMemberOfClass,
                CONCAT(tts.subjectTopicId,'~',tts.topicsTaughtId) AS taughtId,
                tts.subjectTopicId,
                tts.comments, tts.topicsTaughtId
            FROM
                ".ATTENDANCE_TABLE." abl,student s,`group` g,topics_taught tts
            WHERE
               attendanceType=1
               AND abl.topicsTaughtId=tts.topicsTaughtId
               AND s.studentId=abl.studentId AND
               (fromDate ='$fromDate' AND toDate ='$toDate' )
               AND abl.classId=$class

               AND abl.subjectId=$subject
               AND g.groupId=$group
               AND abl.groupId=g.groupId
              $conditions";

     // echo $query; die;
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking duplicate entries in "attendance" table
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (07.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
public function bulkAttendanceDuplicateCheck($class,$group,$subject,$fromDate,$toDate,$conditions=''){

    $query="SELECT
                s.studentId,abl.attendanceId,abl.lectureDelivered,abl.lectureAttended,
                abl.isMemberOfClass
            FROM
                ".ATTENDANCE_TABLE." abl,student s,`group` g
            WHERE
               attendanceType=1
               AND s.studentId=abl.studentId AND
               (fromDate ='$fromDate' AND toDate ='$toDate' )
               AND abl.classId=$class
               AND abl.subjectId=$subject
               AND g.groupId=$group
               AND abl.groupId=g.groupId
              $conditions";
  //echo    $query;
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations of attendance_bulk
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (15.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function checkBulkAttendanceConflict($conditions=''){
    global $REQUEST_DATA;

    $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0));
    $group=((trim($REQUEST_DATA['groupId'])!=""?$REQUEST_DATA['groupId']:0));
    $subject=((trim($REQUEST_DATA['subjectId'])!=""?$REQUEST_DATA['subjectId']:0));
    $fromDate=((trim($REQUEST_DATA['fromDate'])!=""?$REQUEST_DATA['fromDate']:'0'));
    $toDate=((trim($REQUEST_DATA['toDate'])!=""?$REQUEST_DATA['toDate']:'0'));


    $query="SELECT
                  (abl.fromDate) AS fromDate,
                  (abl.toDate) AS toDate
            FROM
                  ".ATTENDANCE_TABLE." abl,student s
            WHERE
                   attendanceType=1
                   AND s.studentId=abl.studentId
                   AND s.classId=abl.classId
                   AND
                   (
                     (abl.fromDate BETWEEN '$fromDate' AND '$toDate')
                     OR
                     (abl.toDate BETWEEN '$fromDate' AND '$toDate')
                     OR
                     (abl.fromDate <= '$fromDate' AND abl.toDate>= '$toDate')
                   )
            AND abl.subjectId=$subject
            AND abl.groupId=$group
            AND abl.classId=$class
            $conditions
            GROUP BY abl.fromDate";
    //echo    $query;
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations of attendance_daily
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (18.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getDailyAttendanceList($conditions='',$orderBy=' firstName'){
    global $REQUEST_DATA;

    $class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0 ));
    $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
    $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
    $period=((trim($REQUEST_DATA['period'])!=""?$REQUEST_DATA['period']:0));
    $forDate=((trim($REQUEST_DATA['forDate'])!=""?$REQUEST_DATA['forDate']:'0'));

    $query="SELECT
                    s.studentId,
                    concat(s.firstName,' ' ,s.lastName) as studentName,
                    adl.attendanceId,
                    adl.attendanceCodeId,
                    adl.isMemberOfClass,
                    CONCAT(tts.subjectTopicId,'~',tts.topicsTaughtId) AS taughtId,
                    tts.subjectTopicId,
                    tts.comments,
                    tts.topicsTaughtId
            FROM
                    ".ATTENDANCE_TABLE." adl,
                    student s,
                    `group` g,
                    topics_taught tts
            WHERE
                    attendanceType=2
            AND     adl.topicsTaughtId=tts.topicsTaughtId
            AND     s.studentId=adl.studentId
            AND     fromDate ='$forDate'
            AND     adl.classId IN ($class )
            AND     adl.subjectId=$subject
            AND     g.groupId IN ($group)
            AND     adl.groupId=g.groupId
            AND     adl.periodId=$period
            $conditions
            ORDER BY $orderBy ";
    //echo $query;
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}




public function getPreviousTopicsTaught($conditions=''){
    global $REQUEST_DATA;
    global $sessionHandler;
    
    $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0 ));
    $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
    $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
    $period=((trim($REQUEST_DATA['period'])!=""?$REQUEST_DATA['period']:0));
    $forDate=((trim($REQUEST_DATA['forDate'])!=""?$REQUEST_DATA['forDate']:'0'));
	$roleId= $sessionHandler->getSessionVariable('RoleId');
    
	if($roleId==2){
	  $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
	  $conditions .=" AND adl.employeeId='$employeeId'";
	}

    $query="SELECT
                    CONCAT(tts.subjectTopicId,'~',tts.topicsTaughtId) AS taughtId,
                    tts.subjectTopicId, tts.comments, tts.topicsTaughtId
            FROM
                    ".ATTENDANCE_TABLE." adl, topics_taught tts
            WHERE
                    attendanceType=2
                    AND adl.topicsTaughtId=tts.topicsTaughtId
                    AND adl.fromDate < '$forDate'
                    AND adl.classId='$class'
                    AND adl.subjectId='$subject'
                    AND adl.groupId='$group'
                    AND adl.periodId='$period'
            $conditions
            ORDER BY 
                    adl.fromDate DESC
            LIMIT 0,1";
            
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking duplicate records
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (18.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function dailyAttendanceDuplicateCheck($class,$group,$subject,$period,$forDate){

    $query="SELECT
                   s.studentId,
                   adl.attendanceId
            FROM
                   ".ATTENDANCE_TABLE." adl,student s,`group` g
            WHERE
                   attendanceType=2
                   AND s.studentId=adl.studentId
                   AND fromDate ='$forDate'
                   AND adl.classId IN ( $class )
                   AND adl.subjectId=$subject
                   AND g.groupId IN ( $group )
                   AND adl.groupId=g.groupId
                   AND adl.periodId=$period
            ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING total number of bulk_attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (15.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTotalBulkAttendance($conditions){
    global $REQUEST_DATA;

    $class=((trim($REQUEST_DATA['class'])!=""?$REQUEST_DATA['class']:0 ));
    $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
    $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
    $fromDate=((trim($REQUEST_DATA['startDate'])!=""?$REQUEST_DATA['startDate']:'0'));
    $toDate=((trim($REQUEST_DATA['endDate'])!=""?$REQUEST_DATA['endDate']:'0'));

    $query="SELECT COUNT(*) AS totalRecords
    FROM ".ATTENDANCE_TABLE." abl,student s,`group` g
    WHERE attendanceType=1 AND s.studentId=abl.studentId AND (fromDate >='$fromDate' AND toDate <='$toDate' ) AND abl.classId=$class AND abl.subjectId=$subject AND g.groupId=$group AND abl.classId=g.classId
     $conditions";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking of daily falls in  bulk
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (15.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
//*************************************Logic****************************************
//If the date range of bulk overlaps with date in daily then do not permit user
//to enter data in bulk otherwise it will create double record for attendance
//This restriction will be applied for same class,subject and group
//***********************************************************************************
public function checkDailyAttendance($conditions=''){
    global $REQUEST_DATA;
    //check from daily attendance*********

    $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0 ));
    $group=((trim($REQUEST_DATA['groupId'])!=""?$REQUEST_DATA['groupId']:0));
    $subject=((trim($REQUEST_DATA['subjectId'])!=""?$REQUEST_DATA['subjectId']:0));
    $fromDate=((trim($REQUEST_DATA['startDate'])!=""?$REQUEST_DATA['startDate']:'0'));
    $toDate=((trim($REQUEST_DATA['endDate'])!=""?$REQUEST_DATA['endDate']:'0'));

    /*
    $query="SELECT s.studentId,d.attendanceId,d.fromDate,d.isMemberOfClass,
    atc.attendanceCode
    FROM attendance d,student s,`group` g,attendance_code atc
    WHERE attendanceType=2 AND s.studentId=d.studentId AND (fromDate >='$fromDate' AND toDate <='$toDate' ) AND d.classId=$class
     AND d.subjectId=$subject AND g.groupId=$group AND d.classId=g.classId
     AND d.attendanceCodeId=atc.attendanceCodeId ORDER BY d.fromDate     $conditions";
   */
   $query="SELECT s.studentId,d.attendanceId,d.fromDate,d.isMemberOfClass
    FROM ".ATTENDANCE_TABLE." d,student s,`group` g
    WHERE attendanceType=2 AND s.studentId=d.studentId
    AND (fromDate >='$fromDate' AND toDate <='$toDate' ) AND d.classId=$class
    AND d.subjectId=$subject AND g.groupId=$group AND d.classId=g.classId
    AND d.groupId = g.groupId
    ORDER BY d.fromDate     $conditions";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking of bulk overlaping with daily
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (15.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
//*************************************Logic****************************************
//If the date range of bulk overlaps with date in daily then do not permit user
//to enter data in bulk otherwise it will create double record for attendance
//This restriction will be applied for same class,subject and group
//***********************************************************************************
public function checkBulkAttendance($conditions=''){
    global $REQUEST_DATA;
    //check from daily attendance*********

    $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0 ));
    $subject=((trim($REQUEST_DATA['subjectId'])!=""?$REQUEST_DATA['subjectId']:0));
    $group=((trim($REQUEST_DATA['groupId'])!=""?$REQUEST_DATA['groupId']:0));
    $forDate=((trim($REQUEST_DATA['forDate'])!=""?$REQUEST_DATA['forDate']:'0'));

    $query="SELECT COUNT(*) as totalRecords
    FROM ".ATTENDANCE_TABLE." b,student s,`group` g
    WHERE attendanceType=1 AND s.studentId=b.studentId AND ('$forDate' >= fromDate AND '$forDate' <= toDate ) AND b.classId=$class
     AND b.subjectId=$subject AND g.groupId=$group AND b.classId=g.classId
     AND b.groupId = g.groupId
       $conditions";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


public function checkBulkAttendanceMulti($classId,$subjectId,$forDate,$conditions=''){
    global $REQUEST_DATA;

    $query="SELECT
                  COUNT(*) as totalRecords
            FROM
                  ".ATTENDANCE_TABLE." b,
                  student s,
                  `group` g
            WHERE
                  attendanceType=1
                  AND s.studentId=b.studentId
                  AND ('$forDate' >= fromDate AND '$forDate' <= toDate )
                  AND b.classId IN ( $classId )
                  AND b.subjectId=$subjectId
                  AND b.classId=g.classId
                  AND b.groupId = g.groupId
                  $conditions";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR adding bulk attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (16.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
 public function addBulkAttendance($conditions=''){

    //as their will be multiple insert at a time normal runAutoInsert() in not used
    $query="INSERT INTO ".ATTENDANCE_TABLE." (classId,groupId,studentId,subjectId,employeeId,attendanceType,attendanceCodeId,
    periodId,fromDate,toDate,isMemberOfClass,lectureDelivered,lectureAttended,userId,topicsTaughtId)
     VALUES $conditions";

    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR adding daily  attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (18.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
 public function addDailyAttendance($conditions=''){

    $query="INSERT INTO ".ATTENDANCE_TABLE." (classId,groupId,studentId,subjectId,employeeId,attendanceType,attendanceCodeId,
    periodId,fromDate,toDate,isMemberOfClass,lectureDelivered,lectureAttended,userId,topicsTaughtId)
     VALUES $conditions";

    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating bulk attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (16.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
 public function editBulkAttendance($attendanceId,$ldel,$latt,$memc,$fromDate,$toDate,$topicsTaughtId,$conditions=''){
    global $REQUEST_DATA;
    global $sessionHandler;


    $query="UPDATE ".ATTENDANCE_TABLE." SET lectureDelivered=$ldel , lectureAttended=$latt , isMemberOfClass=$memc ,
     fromDate='$fromDate',toDate='$toDate' , employeeId=".$sessionHandler->getSessionVariable('EmployeeId')." , userId=".$sessionHandler->getSessionVariable('UserId').",topicsTaughtId=".$topicsTaughtId."
      WHERE attendanceId=$attendanceId $conditions";

     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating daily attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (18.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
 public function editDailyAttendance($attendanceId,$memc,$attendanceCodeId,$topicsTaughtId,$conditions=''){
    global $REQUEST_DATA;
    global $sessionHandler;
    if($sessionHandler->getSessionVariable('RoleId')==2){
      $empId=$sessionHandler->getSessionVariable('EmployeeId');
    }
    else{
      $empId=trim($REQUEST_DATA['employeeId']);
    }

    $query="UPDATE ".ATTENDANCE_TABLE." SET isMemberOfClass=$memc ,attendanceCodeId=$attendanceCodeId,
     employeeId=".$empId." ,
     userId=".$sessionHandler->getSessionVariable('UserId').",topicsTaughtId=".$topicsTaughtId."
      WHERE attendanceId=$attendanceId $conditions";

     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying attendance not taken for a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (28.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function checkAttendanceNotTaken($conditions='', $limit = ''){

  global $sessionHandler;
  $curDate=date('Y')."-".date('m')."-".date('d') ;
  $loginDate=date('Y-m-d');
  /*
  $query=" SELECT su.subjectName,att.attendanceId, max(att.toDate) as date, cl.className
          FROM `attendance` att LEFT JOIN subject su ON att.subjectId = su.subjectId
          INNER JOIN employee emp ON att.employeeId = emp.employeeId
          INNER JOIN class cl ON att.classId = cl.classId
          WHERE att.toDate <= CURRENT_DATE AND att.employeeId=".$sessionHandler->getSessionVariable('EmployeeId')."
          AND cl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
          AND cl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
          $conditions
          group by att.subjectId
          ORDER BY date DESC  $limit ";
          //echo $query;
   */
   $query="SELECT su.subjectId,su.subjectName,su.subjectAbbreviation,su.subjectCode,
            IF(att.attendanceId IS NULL,-1,att.attendanceId) AS attendanceId,
            MAX( att.toDate ) AS dated, emp.employeeName
            FROM  ".TIME_TABLE_TABLE."  stt
            LEFT JOIN ".ATTENDANCE_TABLE." att ON stt.employeeId = att.employeeId
            AND stt.subjectId = att.subjectId
            $conditions
            AND att.toDate <= CURRENT_DATE
            AND stt.toDate IS NULL
            INNER JOIN subject su ON su.subjectId = stt.subjectId
            INNER JOIN employee emp ON emp.employeeId = stt.employeeId
            WHERE
            stt.employeeId =".$sessionHandler->getSessionVariable('EmployeeId')."
            AND stt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
            AND stt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
            AND stt.timeTableId NOT IN
                          (
                            SELECT
                                  tta.timeTableId
                            FROM
                                  time_table_adjustment tta
                            WHERE
                                  tta.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                  AND tta.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                  AND '".$loginDate."' BETWEEN tta.fromDate AND tta.toDate
                                  AND tta.isActive=1
                          )
            GROUP BY stt.subjectId
            ORDER BY dated DESC $limit ";
   //echo $query;

   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying attendance details for a particulra attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (28.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getAttendanceDetail($attendanceId){

    $query="SELECT su.subjectName,SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className,att.toDate
           FROM ".ATTENDANCE_TABLE." att,class cl,subject su
           WHERE cl.classId=att.classId AND su.subjectId=att.subjectId
           AND att.attendanceId=".$attendanceId;

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR adding records topics_taught table for a particulra attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (28.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function addTopicsTaught($employeeId,$subjectTopicId,$comments){

        $str2="INSERT INTO
                       topics_taught(subjectTopicId,employeeId,comments)
                VALUES ('".add_slashes($subjectTopicId)."',$employeeId,'".add_slashes($comments)."')
               ";
         //insert into topics_taught table

          return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($str2);
          //return SystemDatabaseManager::getInstance()->lastInsertId();
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR editing records in topics_taught for a particulra attendance
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (28.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function editTopicsTaught($subjectTopicId,$topicsTaughtId,$employeeId,$comments){

      $query="UPDATE
                     topics_taught
             SET
                     subjectTopicId='$subjectTopicId',
                     employeeId=$employeeId,
                     comments='".add_slashes($comments)."'
             WHERE
                     topicsTaughtId=".$topicsTaughtId;
      //update topics_taught table
     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

}


//------------------------------------------------------------------------------------------------
// This Function  gets the list of topics associated with a subject
//
// Author : Dipanjan Bhattacharjee
// Created on : 15.01.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
public function getTeacherSubjectTopic($subjectId,$employeeId=''){
     global $sessionHandler;
     if($sessionHandler->getSessionVariable('RoleId')==2){
      $empId=$sessionHandler->getSessionVariable('EmployeeId');
     }
     else{
      $empId=$employeeId;
     }

 $query="SELECT
                  DISTINCT st.subjectTopicId,st.topicAbbr, st.topic,
                  IF(att.topicsTaughtId IS NULL,-1,att.topicsTaughtId) AS topicsTaughtId
            FROM
                  subject_topic st
                  LEFT JOIN  topics_taught tt ON tt.subjectTopicId=st.subjectTopicId
                  LEFT JOIN  ".ATTENDANCE_TABLE." att ON att.topicsTaughtId=tt.topicsTaughtId
                  AND att.employeeId=$empId
            WHERE
                 st.subjectId=$subjectId
            ORDER BY st.subjectTopicId
           ";

    //echo $query;
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//**********************FUNCTIONS NEEDED FOR DISPLAING STUDENT ATTENDANCE SUBMODULE IN TEACHER MODULE*********************


//**********************FUNCTIONS NEEDED FOR SENDING EMAIL/SMS/MESSAGE TO STUDENTS/+PARENTS  IN TEACHER MODULE*************************

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students emails
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getStudentEmailList($conditions='', $orderBy=' studentId') {

        $query="SELECT
                        studentId,concat(firstName,' ' ,lastName) as studentName,studentEmail
                FROM student
         $conditions order by $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students mobilenos
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getStudentMobileNoList($conditions='', $orderBy=' studentId') {

        $query="SELECT studentId,concat(firstName,' ' ,lastName) as studentName , studentMobileNo
        FROM student
         $conditions order by  $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students mobilenos and emails
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getStudentEmailMobileNoList($conditions='', $orderBy=' studentId') {

        $query="SELECT studentId,concat(firstName,' ' ,lastName) as studentName , studentEmail , studentMobileNo
        FROM student
         $conditions order by  $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of parents  mobilenos and emails
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getParentEmailMobileNoList($conditions='', $orderBy=' studentId') {

        $query="SELECT studentId,fatherTitle,fatherName,fatherMobileNo,fatherEmail,
        motherTitle,motherName,motherMobileNo,motherEmail,
        guardianTitle,guardianName,guardianMobileNo,guardianEmail,
        fatherUserId, motherUserId, guardianUserId, userId
        FROM student
         $conditions order by  $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting comments in teacher_comment table
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function addTeacherComment($conditions='') {
        global $sessionHandler;
        global $REQUEST_DATA;
        $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $subject=add_slashes(htmlentities($REQUEST_DATA['msgSubject']));
        $comments=add_slashes(htmlentities($REQUEST_DATA['msgBody']));

        $query="INSERT INTO teacher_comment (teacherId,subject,comments,instituteId,sessionId,postedOn)
         VALUES
         ($teacherId,'".$subject."','".$comments."',$instituteId,$sessionId,now())
         $conditions ";
        SystemDatabaseManager::getInstance()->executeUpdate($query);
        return SystemDatabaseManager::getInstance()->lastInsertId();
    }


//--------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting sms/email records sent sms/email to students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function studentEmailSMSRecord($conditions='',$parentConditions='') {

        if($parentConditions=='') {
            $query="INSERT INTO teacher_comment_detail (commentId,studentId,visibleFromDate,visibleToDate,sms,email,dashboard,
                toStudent,toParent) VALUES $conditions ";
        }
        else {
            $query="INSERT INTO teacher_comment_detail (commentId,studentId,visibleFromDate,visibleToDate,sms,email,dashboard,
                toStudent,toParent,receiverType) VALUES $conditions ";
        }

        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying teacher_comments for selected class,subject,group or rollno.
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherCommentList($conditions='', $limit = '', $orderBy=' s.firstName') {

         global $REQUEST_DATA;
         global $sessionHandler;

         $class=((trim($REQUEST_DATA['class'])!="-1"?$REQUEST_DATA['class']:0));
         $group=((trim($REQUEST_DATA['group'])!="-1"?$REQUEST_DATA['group']:0));
         $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
         $date=((trim($REQUEST_DATA['forDate'])!=""?$REQUEST_DATA['forDate']:0));
         $rollNo=trim($REQUEST_DATA['studentRollNo']);

         $classCondition='';
         $groupCondition='';
         $rollNoCondition='';
         $dateCondition='';
         if($class!=0){
             $classCondition="AND s.classId=".$class;
         }
         if($group!=0){
             $groupCondition="AND g.groupId=".$group;
         }
         if($rollNo!=''){
             $rollNoCondition=' AND s.rollNo LIKE "'.add_slashes($rollNo).'%"';
         }
         if($date!=''){
             $dateCondition="AND DATE_FORMAT(tc.postedOn,'%Y-%m-%d')='".$date."'";
         }

         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');

         if($conditions !=""){
           $query="SELECT tc.commentId,tc.comments,tc.subject,tc.postedOn
                 FROM teacher_comment tc
                 $conditions ";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         }

         $optionalGroup=0;
         if($group!=0){
             //check whether it is optional group or not
             $optGrArray=$this->checkOptionalGroup($group);
             if(count($optGrArray)>0 and is_array($optGrArray)){
                 $optionalGroup=$optGrArray[0]['isOptional'];
             }
             else{
                 $optionalGroup=0;
             }
         }


             if($optionalGroup==0){ //if this group is not optional group
              $query="SELECT
                            tc.commentId,tc.comments,
                            tc.subject,tc.postedOn
                      FROM
                            teacher_comment tc
                      WHERE
                            tc.commentId
                            IN
                              (
                               SELECT
                                     DISTINCT tcd.commentId
                               FROM
                                     teacher_comment_detail tcd,student s,
                                     class c,`group` g ,student_groups sg
                               WHERE
                                     tcd.studentId=s.studentId
                                     AND s.classId=c.classId
                                     AND s.studentId=sg.studentId
                                     AND sg.classId=c.classId
                                     AND sg.groupId=g.groupId
                                     $classCondition
                                     $groupCondition
                                     $rollNoCondition
                               )
                        AND tc.teacherId=$teacherId
                        $dateCondition
                        $condition
                        ORDER BY $orderBy
                        $limit ";
             }
             else{
                 $query="SELECT
                               tc.commentId,tc.comments,
                               tc.subject,tc.postedOn
                         FROM
                               teacher_comment tc
                         WHERE
                              tc.commentId
                              IN
                                (
                                  SELECT
                                        DISTINCT tcd.commentId
                                  FROM
                                        teacher_comment_detail tcd,student s,
                                        class c,`group` g ,student_optional_subject sg
                                  WHERE
                                        tcd.studentId=s.studentId
                                        AND s.classId=c.classId
                                        AND s.studentId=sg.studentId
                                        AND sg.classId=c.classId
                                        AND sg.groupId=g.groupId
                                        $classCondition
                                        $groupCondition
                                        $rollNoCondition
                                 )
                         AND tc.teacherId=$teacherId
                         $dateCondition
                         ORDER BY $orderBy
                         $limit ";
             }
                 //echo $query;
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying total nos teacher_comments for selected class,subject,group or rollno.
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTotalTeacherComment($conditions='') {

         global $REQUEST_DATA;
         global $sessionHandler;

         $class=((trim($REQUEST_DATA['class'])!="-1"?$REQUEST_DATA['class']:0));
         $group=((trim($REQUEST_DATA['group'])!="-1"?$REQUEST_DATA['group']:0));
         $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
         $date=((trim($REQUEST_DATA['forDate'])!=""?$REQUEST_DATA['forDate']:0));
         $rollNo=trim($REQUEST_DATA['studentRollNo']);

         $classCondition='';
         $groupCondition='';
         $rollNoCondition='';
         $dateCondition='';
         if($class!=0){
             $classCondition="AND s.classId=".$class;
         }
         if($group!=0){
             $groupCondition="AND g.groupId=".$group;
         }
         if($rollNo!=''){
             $rollNoCondition=' AND s.rollNo LIKE "'.add_slashes($rollNo).'%"';
         }
         if($date!=''){
             $dateCondition="AND DATE_FORMAT(tc.postedOn,'%Y-%m-%d')='".$date."'";
         }

         $teacherId=$sessionHandler->getSessionVariable('EmployeeId');

         if($conditions !=""){
          $query="SELECT
                         COUNT(*) AS totalRecords
                   FROM
                          teacher_comment tc
                 $conditions ";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         }

         $optionalGroup=0;
         if($group!=0){
             //check whether it is optional group or not
             $optGrArray=$this->checkOptionalGroup($group);
             if(count($optGrArray)>0 and is_array($optGrArray)){
                 $optionalGroup=$optGrArray[0]['isOptional'];
             }
             else{
                 $optionalGroup=0;
             }
         }

         if($optionalGroup==0){ //if this group is not optional group
              $query="SELECT
                            COUNT(*) AS totalRecords
                      FROM
                            teacher_comment tc
                      WHERE
                            tc.commentId
                            IN
                              (
                               SELECT
                                     DISTINCT tcd.commentId
                               FROM
                                     teacher_comment_detail tcd,student s,
                                     class c,`group` g ,student_groups sg
                               WHERE
                                     tcd.studentId=s.studentId
                                     AND s.classId=c.classId
                                     AND s.studentId=sg.studentId
                                     AND sg.classId=c.classId
                                     AND sg.groupId=g.groupId
                                     $classCondition
                                     $groupCondition
                                     $rollNoCondition
                               )
                        AND tc.teacherId=$teacherId
                              $dateCondition
                              $condition";
             }
             else{
               $query="SELECT
                              COUNT(*) AS totalRecords
                       FROM
                              teacher_comment tc
                       WHERE
                              tc.commentId
                              IN
                                (
                                  SELECT
                                        DISTINCT tcd.commentId
                                  FROM
                                        teacher_comment_detail tcd,student s,
                                        class c,`group` g ,student_optional_subject sg
                                  WHERE
                                        tcd.studentId=s.studentId
                                        AND s.classId=c.classId
                                        AND s.studentId=sg.studentId
                                        AND sg.classId=c.classId
                                        AND sg.groupId=g.groupId
                                        $classCondition
                                        $groupCondition
                                        $rollNoCondition
                                 )
                         AND tc.teacherId=$teacherId
                        $dateCondition
                        $condition";
             }
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR retriving a comment according to commentId
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
 public function getComments($commenId) {
     $query="SELECT commentId,subject,comments,postedOn
             FROM teacher_comment
             WHERE commentId=".$commenId;

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying list of teacher comments for students based on a commentId
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getStudentCommentList($conditions='', $limit = '', $orderBy=' firstName') {

    $query="SELECT
                  concat(firstName,' ',lastName) AS studentName,
                  IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                  IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                  if(t.toStudent <> 1,'No','Yes') AS toStudent,
                  if(t.toParent <> 1,'No','Yes') AS toParent,
                  if(t.sms <> 1,'No','Yes') AS sms,
                  if(t.email <> 1,'No','Yes') AS email ,
                  if(t.dashboard <> 1,'No','Yes') AS dashboard
           FROM
                  teacher_comment_detail t,
                  student s
           WHERE
                  t.studentId=s.studentId
           $conditions
           ORDER BY $orderBy
           $limit ";

   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying total teacher comments for students based on a commentId
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTotalStudentCommentList($conditions='') {

    $query="SELECT COUNT(*) AS totalRecords
      FROM teacher_comment_detail t,student s
      WHERE t.studentId=s.studentId
      $conditions  ";

   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//**********************FUNCTIONS NEEDED FOR SENDING EMAIL/SMS/MESSAGE TO STUDENTS/+PARENTS  IN TEACHER MODULE*************************



//**********************FUNCTIONS NEEDED FOR SHOWING INSTITUTE NOTICE/EVENTS FOR TEACHERs  IN TEACHER MODULE*************************

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getNoticeList($conditions='', $limit = '', $orderBy=' noticeText'){

    global $sessionHandler;
    $teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');

   
    $query="SELECT 
                    DISTINCT n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName ,
                    n.visibleMode
            FROM    
                    employee emp, department d, notice n 
                    INNER JOIN notice_visible_to_role nvr ON (n.noticeId=nvr.noticeId) 
            WHERE        
                    nvr.instituteId='$instituteId' 
                    AND n.instituteId='$instituteId' 
                    AND nvr.sessionId='$sessionId' 
                    AND n.departmentId = d.departmentId 
                    AND emp.employeeId=$teacherId AND (nvr.branchId=emp.branchId OR nvr.branchId IS NULL) 
                    $conditions 
            GROUP BY 
                    n.noticeId
            UNION  
            SELECT 
                    DISTINCT  n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName,
                    n.visibleMode
              FROM  
                    department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
              WHERE        
                    nvr.noticeInstituteId=$instituteId 
                    AND n.departmentId = d.departmentId 
                    $conditions 
              GROUP BY 
                    n.noticeId
              ORDER BY 
                    $orderBy $limit " ;   
         
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getClassMiscInfo($classId){
    $query="SELECT * FROM class WHERE classId=$classId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute notices for a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTotalNotice($conditions='', $limit = '', $orderBy=' noticeText'){

 global $sessionHandler;
 $teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId
 $roleId=$sessionHandler->getSessionVariable('RoleId');
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
 $curDate=date('Y')."-".date('m')."-".date('d');

     
    $extraCondition=" AND (
                            (nvr.universityId IS NULL OR nvr.universityId='".$classArray[0]['universityId']."')
                             AND
                            (nvr.degreeId IS NULL OR nvr.degreeId='".$classArray[0]['degreeId']."')
                             AND
                            (nvr.branchId IS NULL OR nvr.branchId='".$classArray[0]['branchId']."')
                           )";
       

 $query="SELECT
                    COUNT(*) AS totalRecords
            FROM        
                    (SELECT 
                            DISTINCT n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName ,
                            n.visibleMode
                    FROM    
                            department d, notice n INNER JOIN notice_visible_to_role nvr ON  ( n.noticeId=nvr.noticeId $extraCondition ) 
                    WHERE    
                            nvr.roleId='$roleId'          
                            AND nvr.instituteId='$instituteId' 
                            AND n.instituteId='$instituteId' 
                            AND nvr.sessionId='$sessionId' 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                    GROUP BY 
                            n.noticeId
                    UNION  
                    SELECT 
                            DISTINCT  n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName,
                            n.visibleMode
                      FROM  
                            department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
                      WHERE        
                            nvr.noticeInstituteId='$instituteId' 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                      GROUP BY 
                            n.noticeId ) AS tt " ;    
        //echo  $query;
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying notice details for a particular notice
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (28.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getNoticeDetail($noticeId,$orderBy='',$limit=''){
    global $sessionHandler;

	$roleId=$sessionHandler->getSessionVariable('RoleId');
	$instituteId=$sessionHandler->getSessionVariable('InstituteId');
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
	$curDate=date('Y')."-".date('m')."-".date('d');
	 
	$extraCondition=" AND (
                            (nvr.universityId IS NULL OR nvr.universityId='".$classArray[0]['universityId']."')
                             AND
                            (nvr.degreeId IS NULL OR nvr.degreeId='".$classArray[0]['degreeId']."')
                             AND
                            (nvr.branchId IS NULL OR nvr.branchId='".$classArray[0]['branchId']."')
                           )";
                           
    if($orderBy=='') {
      $orderBy =' visibleFromDate DESC, visibleMode DESC, noticeId DESC';
    }                       
    
    $query="SELECT 
                    DISTINCT n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName ,
                    n.visibleMode
            FROM    
                    department d, notice n INNER JOIN notice_visible_to_role nvr ON  ( n.noticeId=nvr.noticeId $extraCondition ) 
            WHERE    
                    nvr.roleId='$roleId'          
                    AND nvr.instituteId='$instituteId' 
                    AND n.instituteId='$instituteId' 
                    AND nvr.sessionId='$sessionId' 
                    AND n.departmentId = d.departmentId 
                    $conditions 
            GROUP BY 
                    n.noticeId
            UNION  
            SELECT 
                    DISTINCT  n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName,
                    n.visibleMode
              FROM  
                    department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
              WHERE        
                    nvr.noticeInstituteId='$instituteId' 
                    AND n.departmentId = d.departmentId 
                    $conditions 
              GROUP BY 
                    n.noticeId
              ORDER BY 
                    $orderBy $limit " ;              

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute events for a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getEventList($conditions='', $limit = '', $orderBy=' e.eventTitle'){

 global $sessionHandler;
 $roleId=$sessionHandler->getSessionVariable('RoleId');
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');

 $query="SELECT e.eventId,e.eventTitle,e.shortDescription,e.longDescription,e.startDate,e.endDate
       FROM event e
       WHERE e.instituteId='$instituteId' AND e.sessionId='$sessionId'
        AND e.roleIds LIKE '%~$roleId~%'
        $conditions   ORDER BY $orderBy $limit " ;
 //echo $query;
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of institute events for a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTotalEvent($conditions='', $limit = '', $orderBy=' e.eventTitle'){

 global $sessionHandler;
 $roleId=$sessionHandler->getSessionVariable('RoleId');
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');

 $query="SELECT COUNT(*) AS totalRecords
       FROM event e
       WHERE e.instituteId='$instituteId' AND e.sessionId='$sessionId'
        AND e.roleIds LIKE '%~$roleId~%'
        $conditions   ORDER BY $orderBy $limit " ;

  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying event details for a particular event
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (28.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getEventDetail($eventId){

    $query="SELECT e.eventId,e.eventTitle,e.shortDescription,e.longDescription,e.startDate,e.endDate
           FROM event e
           WHERE e.eventId='".$eventId."'";

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//**********************FUNCTIONS NEEDED FOR SHOWING INSTITUTE NOTICE FOR TEACHERs  IN TEACHER MODULE*************************



//**********************FUNCTIONS NEEDED FOR Entering Marks  IN TEACHER MODULE*************************

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of testTypes  for a subject
//
// Author :Dipanjan Bhattacharjee
// Created on : (23.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTestType($subjectId,$classId,$conditions=''){
 global $sessionHandler;
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');

 //**no test type corresponding to evaluation criteria "Percentage[id=5]" or "Slabs[id=6]" should come**
 $query="SELECT t.testTypeId,t.testTypeName,t.testTypeAbbr
       FROM test_type t,class c,`subject` sub where
       c.classId='$classId' and c.sessionId='".$sessionId."' and c.instituteId='".$instituteId."'
       AND (c.universityId=t.universityId or t.universityId is null)
       AND (c.degreeId=t.degreeId or t.degreeId is null)
       AND (c.branchId=t.branchId or t.branchId is null)
       AND (c.degreeId=t.degreeId or t.degreeId is null)
       AND (c.studyPeriodId=t.studyPeriodId or t.studyPeriodId is null)
       AND (t.subjectId='$subjectId' or t.subjectId is null)
       AND t.subjectTypeId=sub.subjectTypeId
       AND sub.subjectId='$subjectId'
       AND t.evaluationCriteriaId NOT IN(5,6)
       AND t.instituteId = '$instituteId'
       $conditions";
  //echo $query;
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of test  for a testType
//
// Author :Dipanjan Bhattacharjee
// Created on : (23.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTest($testTypeId){

global $REQUEST_DATA;
global $sessionHandler;

 $query="SELECT
           testId,testAbbr,testIndex,comments
         FROM
           ".TEST_TABLE."
         WHERE
           testTypeCategoryId='".$testTypeId. "'
           AND subjectId='".$REQUEST_DATA['subjectId']."'
           AND classId='".$REQUEST_DATA['classId']."'
           AND groupId='".$REQUEST_DATA['groupId']."'
           AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
           AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
           ORDER BY testId DESC"; //so that newly created "test" comes at the bottom
		   //of "test" drop down
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting details of a test
//
// Author :Dipanjan Bhattacharjee
// Created on : (23.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTestDetails($testId){

 $query="SELECT testId,testTopic,testAbbr,testIndex,maxMarks,testDate,comments
       FROM ".TEST_TABLE." WHERE testId='".$testId."'";

  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Max test index
//
// Author :Dipanjan Bhattacharjee
// Created on : (23.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getMaxTestIndex($testTypeId){

global $REQUEST_DATA;
global $sessionHandler;

 $query="SELECT
            IF(MAX(CAST(testIndex as unsigned)) IS NULL ,0,MAX(CAST(testIndex as unsigned))) AS testIndex
         FROM
            ".TEST_TABLE."
         WHERE
           testTypeCategoryId='".$testTypeId. "'
           AND subjectId='".$REQUEST_DATA['subjectId']."'
           AND classId='".$REQUEST_DATA['classId']."'
           AND groupId='".$REQUEST_DATA['groupId']."'
           AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
           AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";
 //echo $query;
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations of test_marks
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (23.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTestMarksList($conditions='',$limit='',$orderBy=''){
 global $REQUEST_DATA;

 if(trim($REQUEST_DATA['test'])=="" or trim($REQUEST_DATA['test'])=="NT"){
   $extCondition=" AND b.testId IS NULL ";
 }
 else{
    $extCondition=" AND b.testId = '".$REQUEST_DATA['test']."'";
 }

 global $sessionHandler;
 /*
 //get list of students having this subject as optional
 $extC= $this->getOptionalStudentList($REQUEST_DATA['class'],$REQUEST_DATA['subject']);
 if($extC!=""){
     $extC=" a.studentId In ($extC) AND ";
 }
*/
 $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
 $optionalGroup=0;
 if($group!=0){
     //check whether it is optional group or not
     $optGrArray=$this->checkOptionalGroup($group);
     if(count($optGrArray)>0 and is_array($optGrArray)){
         $optionalGroup=$optGrArray[0]['isOptional'];
     }
     else{
         $optionalGroup=0;
     }
 }

 /*
            $query=" SELECT
                                a.studentId,
                                CONCAT(a.firstName,' ',a.lastName) AS studentName,
                                a.rollNo,
                                a.universityRollNo,
                                b.marksScored,
                                b.isMemberOfClass,
                                b.isPresent,
                                IF(b.testMarksId > 0, b.testMarksId,-1) AS testMarksId
                    FROM        student_groups sg, student a
                    LEFT JOIN    test_marks b
                    ON            (
                                    a.studentId = b.studentId AND
                                    $extCondition
                                    b.subjectId = ".$REQUEST_DATA['subject']."
                                )
                    WHERE        a.classId =  ".$REQUEST_DATA['class']."
                                AND $extC
                                a.classId=sg.classId AND
                                (
                                    sg.groupId = ".$REQUEST_DATA['group']."
                                    AND sg.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                    AND sg.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                )
                    ORDER BY    $orderBy $limit
                    ";
    */
 if($optionalGroup==0){//if this group is not optional
  $query="select
             a.studentId,
             CONCAT(s.firstName,' ',s.lastName) AS studentName,
             s.rollNo,
			 s.regNo,
             CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(cl.rollNoSuffix)) , LENGTH( cl.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
             IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
             b.marksScored,
             c.subjectId ,
             b.marksScored,
             b.isMemberOfClass,
             b.isPresent,
             IF(b.testMarksId > 0, b.testMarksId,-1) AS testMarksId
             FROM
               class cl, student s , student_groups a
             LEFT JOIN ".TEST_MARKS_TABLE." b ON
                        (
                           a.studentId = b.studentId
                           AND b.subjectId='".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             LEFT JOIN ".TEST_TABLE." c ON
                        (
                           b.testId = c.testId
                           AND c.subjectId='".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             WHERE
              a.studentId=s.studentId
              AND s.classId = cl.classId
              AND a.classId = '".$REQUEST_DATA['class']."'
              AND a.groupId = '".$REQUEST_DATA['group']."'
              AND a.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
              AND a.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
              ORDER BY $orderBy
              $limit
              " ;

 }
 else{

     $query="SELECT
             a.studentId,
             CONCAT(s.firstName,' ',s.lastName) AS studentName,
             s.rollNo,
			 s.regNo,
             CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(cl.rollNoSuffix)) , LENGTH( cl.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
             IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
             b.marksScored,
             c.subjectId ,
             b.marksScored,
             b.isMemberOfClass,
             b.isPresent,
             IF(b.testMarksId > 0, b.testMarksId,-1) AS testMarksId
             FROM
               class cl, student s , student_optional_subject a
             LEFT JOIN ".TEST_MARKS_TABLE." b ON
                        (
                           a.studentId = b.studentId
                           AND b.subjectId='".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             LEFT JOIN ".TEST_TABLE." c ON
                        (
                           b.testId = c.testId
                           AND c.subjectId='".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             WHERE
              a.studentId=s.studentId
              AND s.classId = cl.classId
              AND a.classId = '".$REQUEST_DATA['class']."'
              AND a.groupId ='".$REQUEST_DATA['group']."'
              AND a.subjectId ='".$REQUEST_DATA['subject']."'
              AND cl.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
              AND cl.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
              ORDER BY $orderBy
              $limit
              " ;

 }

            //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}



//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING all informations of test_marks
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (23.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function getStudentMarksList($conditions='',$limit='',$orderBy=''){
	 global $REQUEST_DATA;

	 if(trim($REQUEST_DATA['test'])=="" or trim($REQUEST_DATA['test'])=="NT"){
	   $extCondition=" AND b.testId IS NULL ";
	 }
	 else{
		$extCondition=" AND b.testId = '".$REQUEST_DATA['test']."'";
	 }

	 global $sessionHandler;
	 /*
		 //get list of students having this subject as optional
		 $extC= $this->getOptionalStudentList($REQUEST_DATA['classId'],$REQUEST_DATA['subject']);
		 if($extC!=""){
			 $extC=" a.studentId In ($extC) AND ";
		 }
	 */
	 $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
	 $optionalGroup=0;
	 if($group!=0){
		 //check whether it is optional group or not
		 $optGrArray=$this->checkOptionalGroup($group);
		 if(count($optGrArray)>0 and is_array($optGrArray)){
			 $optionalGroup=$optGrArray[0]['isOptional'];
		 }
		 else{
			 $optionalGroup=0;
		 }
	 }

	if($optionalGroup==0){//if this group is not optional
	  $query="select
	             emp.employeeName,
				 SUBSTRING_INDEX(SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',4),'".CLASS_SEPRATOR."',-2) AS className, SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-1) as periodName,
				 subjectName, subjectCode,
				 a.studentId,
				 CONCAT(s.firstName,' ',s.lastName) AS studentName,
				 s.rollNo,
				 s.regNo,
				 CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(cl.rollNoSuffix)) , LENGTH( cl.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
				 IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
				 b.marksScored,
				 c.subjectId ,
				 b.marksScored,
				 b.isMemberOfClass,
				 b.isPresent,
				 IF(b.testMarksId > 0, b.testMarksId,-1) AS testMarksId
				 FROM
				 employee emp,`subject` sub,  class cl, student s , student_groups a
				 LEFT JOIN ".TEST_MARKS_TABLE." b ON
							(
							   a.studentId = b.studentId
							   AND b.subjectId='".$REQUEST_DATA['subject']."'
							   $extCondition
							)
				 LEFT JOIN ".TEST_TABLE." c ON
							(
							   b.testId = c.testId
							   AND c.subjectId='".$REQUEST_DATA['subject']."'
							   $extCondition
							)
				 WHERE
				  c.employeeId = emp.employeeId AND
				  c.subjectId = sub.subjectId AND
				  a.studentId=s.studentId
				  AND s.classId = cl.classId
				  AND a.classId = '".$REQUEST_DATA['class']."'
				  AND a.groupId ='".$REQUEST_DATA['group']."'
				  AND a.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
				  AND a.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
				  ORDER BY $orderBy
				  $limit
				  " ;
	 }
	else{
	  $query="select
				  emp.employeeName,
				 SUBSTRING_INDEX(SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',4),'".CLASS_SEPRATOR."',-2) AS className, SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-1) as periodName,
				 a.studentId,
				 subjectName, subjectCode,
				 CONCAT(s.firstName,' ',s.lastName) AS studentName,
				 s.rollNo,
				 s.regNo,
				 CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(cl.rollNoSuffix)) , LENGTH( cl.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
				 IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
				 b.marksScored,
				 c.subjectId ,
				 b.marksScored,
				 b.isMemberOfClass,
				 b.isPresent,
				 IF(b.testMarksId > 0, b.testMarksId,-1) AS testMarksId
				 FROM
				 employee emp, `subject` sub,  class cl, student s , student_optional_subject a
				 LEFT JOIN ".TEST_MARKS_TABLE." b ON
							(
							   a.studentId = b.studentId
							   AND b.subjectId='".$REQUEST_DATA['subject']."'
							   $extCondition
							)
				 LEFT JOIN ".TEST_TABLE." c ON
							(
							   b.testId = c.testId
							   AND c.subjectId='".$REQUEST_DATA['subject']."'
							   $extCondition
							)
				 WHERE
				  c.employeeId = emp.employeeId AND
				  a.subjectId = sub.subjectId AND	
				  a.studentId=s.studentId
				  AND s.classId = cl.classId
				  AND a.classId = '".$REQUEST_DATA['class']."'
				  AND a.groupId ='".$REQUEST_DATA['group']."'
				  AND a.subjectId ='".$REQUEST_DATA['subject']."'
				  AND cl.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
				  AND cl.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
				  ORDER BY $orderBy
				  $limit
				  " ;
	}

				//echo $query;
		  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING total of test_marks
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (23.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function getTotalTestMarks($conditions=''){
     
    global $REQUEST_DATA;

     //if the testId is blanck or NT(new test)
     if(trim($REQUEST_DATA['test'])=="" or trim($REQUEST_DATA['test'])=="NT"){
       $extCondition=" AND b.testId IS NULL ";
     }
     else{
        $extCondition=" AND b.testId = '".$REQUEST_DATA['test']."'";
     }

 global $sessionHandler;
 /*
 //get list of students having this subject as optional
 $extC= $this->getOptionalStudentList($REQUEST_DATA['class'],$REQUEST_DATA['subject']);
 if($extC!=""){
     $extC=" a.studentId In ($extC) AND ";
 }
 */

 $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
 $optionalGroup=0;
 if($group!=0){
     //check whether it is optional group or not
     $optGrArray=$this->checkOptionalGroup($group);
     if(count($optGrArray)>0 and is_array($optGrArray)){
         $optionalGroup=$optGrArray[0]['isOptional'];
     }
     else{
         $optionalGroup=0;
     }
 }

/*
            $query=" SELECT
                                a.studentId,
                                CONCAT(a.firstName,' ',a.lastName) AS studentName,
                                a.rollNo,
                                a.universityRollNo,
                                b.marksScored,
                                b.isMemberOfClass,
                                b.isPresent,
                                IF(b.testMarksId > 0, b.testMarksId,-1) AS testMarksId
                    FROM        student_groups sg, student a
                    LEFT JOIN    test_marks b
                    ON            (
                                    a.studentId = b.studentId AND
                                    $extCondition
                                    b.subjectId = ".$REQUEST_DATA['subject']."
                                )
                    WHERE        a.classId =  ".$REQUEST_DATA['class']."
                                AND $extC
                                a.classId=sg.classId AND
                                (
                                    sg.groupId = ".$REQUEST_DATA['group']."
                                    AND sg.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                                    AND sg.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                                )
                    ORDER BY    $orderBy $limit
                    ";
    */
if($optionalGroup==0){//if this group is not optional
  $query="SELECT
             COUNT(*) AS totalRecords
             FROM
              class cl, student s ,student_groups a
             LEFT JOIN ".TEST_MARKS_TABLE." b ON
                        (
                           a.studentId = b.studentId
                           AND b.subjectId= '".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             LEFT JOIN ".TEST_TABLE." c ON
                        (
                           b.testId = c.testId
                           AND c.subjectId= '".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             WHERE
              a.studentId=s.studentId
              AND s.classId = cl.classId
              AND a.classId = '".$REQUEST_DATA['class']."'
              AND a.groupId = '".$REQUEST_DATA['group']."'
              AND a.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
              AND a.instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."'
              " ;
 }
else{
     $query="SELECT
             COUNT(*) AS totalRecords
             FROM
              class cl, student s ,student_optional_subject a
             LEFT JOIN ".TEST_MARKS_TABLE." b ON
                        (
                           a.studentId = b.studentId
                           AND b.subjectId= '".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             LEFT JOIN ".TEST_TABLE." c ON
                        (
                           b.testId = c.testId
                           AND c.subjectId= '".$REQUEST_DATA['subject']."'
                           $extCondition
                        )
             WHERE
              a.studentId=s.studentId
              AND s.classId = cl.classId
              AND a.classId = '".$REQUEST_DATA['class']."'
              AND a.groupId = '".$REQUEST_DATA['group']."'
              AND a.subjectId ='".$REQUEST_DATA['subject']."'
              AND cl.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
              AND cl.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
              " ;
}
            //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR checking duplicate marks
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (23.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function checkDuplicateMarksEntry($classId,$groupId,$subjectId,$testTypeCategoryId,$conditions=''){
  global $sessionHandler;

  $query="SELECT
                 s.studentId,
                 t.testIndex
          FROM
                 student s ,".TEST_TABLE." t,class c
          WHERE
                 t.classId=c.classId
                 AND c.classId=s.classId
                 AND t.classId = ".$classId."
                 AND t.groupId =".$groupId."
                 AND t.subjectId =".$subjectId."
                 AND t.testTypeCategoryId =".$testTypeCategoryId."
                 AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                 AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                 GROUP BY t.testIndex
              " ;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
public function checkDuplicateMarksEntryNew($classId,$groupId,$subjectId,$testTypeCategoryId,$conditions=''){
  global $sessionHandler;

  $query="SELECT
                 MAX(t.testIndex) as maxIndex
          FROM
                 student s ,".TEST_TABLE." t,class c
          WHERE
                 t.classId=c.classId
                 AND c.classId=s.classId
                 AND t.classId = ".$classId."
                 AND t.groupId =".$groupId."
                 AND t.subjectId =".$subjectId."
                 AND t.testTypeCategoryId =".$testTypeCategoryId."
                 AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                 AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                 GROUP BY t.testTypeCategoryId
              " ;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Test
//
// Author :Dipanjan Bhattacharjee
// Created on : (23.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function addTest() {
    global $REQUEST_DATA;
    global $sessionHandler;
    $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
    /*
    return SystemDatabaseManager::getInstance()->runAutoInsert('test',
    array('employeeId','subjectId','classId','groupId','testTopic','testAbbr','testIndex','testTypeCategoryId','maxMarks','testDate','sessionId','instituteId'),
    array(
          $teacherId,$REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],add_slashes($REQUEST_DATA['testTopic']),
          add_slashes($REQUEST_DATA['testAbbr']),$REQUEST_DATA['testIndex'],$REQUEST_DATA['testTypeId'],$REQUEST_DATA['maxMarks'],
          $REQUEST_DATA['testDate'],$sessionHandler->getSessionVariable('SessionId'),$sessionHandler->getSessionVariable('InstituteId')
         )
    );
   */
    $query="INSERT INTO
                       ".TEST_TABLE."
                (
                 employeeId,subjectId,classId,groupId,testTopic,testAbbr,testIndex,testTypeCategoryId,maxMarks,
                 testDate,sessionId,instituteId,comments
                )
            VALUES
                 (
                  ".$teacherId.",".$REQUEST_DATA['subjectId'].",".$REQUEST_DATA['classId'].",".$REQUEST_DATA['groupId'].",
                  '".add_slashes(trim($REQUEST_DATA['testTopic']))."','".add_slashes(trim($REQUEST_DATA['testAbbr']))."',
                  '".add_slashes(trim($REQUEST_DATA['testIndex']))."',".$REQUEST_DATA['testTypeId'].",
                  '".add_slashes(trim($REQUEST_DATA['maxMarks']))."','".$REQUEST_DATA['testDate']."',
                  ".$sessionHandler->getSessionVariable('SessionId').",".$sessionHandler->getSessionVariable('InstituteId').",'".add_slashes(trim($REQUEST_DATA['comments']))."'
                 )";

    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Test
//
//$id:cityId
// Author :Dipanjan Bhattacharjee
// Created on : (23.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function editTest($id) {
    global $REQUEST_DATA;
    global $sessionHandler;
    $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
    /*
    return SystemDatabaseManager::getInstance()->runAutoUpdate('test',
    array('employeeId','subjectId','classId','groupId','testTopic','testAbbr','testIndex','testTypeCategoryId','maxMarks','testDate','sessionId','instituteId'),
    array(
          $teacherId,$REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],add_slashes($REQUEST_DATA['testTopic']),
          add_slashes($REQUEST_DATA['testAbbr']),$REQUEST_DATA['testIndex'],$REQUEST_DATA['testTypeId'],$REQUEST_DATA['maxMarks'],
          $REQUEST_DATA['testDate'],$sessionHandler->getSessionVariable('SessionId'),$sessionHandler->getSessionVariable('InstituteId')
          )
          , "testId=$id"
          );
    */
   $query="UPDATE
                 ".TEST_TABLE."
           SET
              employeeId=".$teacherId.",
              subjectId=".$REQUEST_DATA['subjectId'].",
              classId=".$REQUEST_DATA['classId'].",
              groupId=".$REQUEST_DATA['groupId'].",
              testTopic='".add_slashes(trim($REQUEST_DATA['testTopic']))."',
              testAbbr='".add_slashes(trim($REQUEST_DATA['testAbbr']))."',
              testIndex='".add_slashes(trim($REQUEST_DATA['testIndex']))."',
              testTypeCategoryId=".$REQUEST_DATA['testTypeId'].",
              maxMarks='".add_slashes(trim($REQUEST_DATA['maxMarks']))."',
              testDate='".$REQUEST_DATA['testDate']."',
              sessionId=".$sessionHandler->getSessionVariable('SessionId').",
              instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
           WHERE
              testId=".$id;
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



//--------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting testMarks of students(as multiple insert have to be done this form of query is used)
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------------------
    public function addTestMarks($conditions='') {

        $query="INSERT INTO ".TEST_MARKS_TABLE." (testId,studentId,subjectId,maxMarks,marksScored,isPresent,isMemberOfClass)
         VALUES
         $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING testmarks
//
//$id:cityId
// Author :Dipanjan Bhattacharjee
// Created on : (23.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function editTestMarks($testMarksId,$maxMarks,$marksScored,$present,$mclass,$conditions='') {
    global $REQUEST_DATA;

    $query="UPDATE ".TEST_MARKS_TABLE." SET maxMarks=$maxMarks,marksScored=$marksScored,isPresent=$present,isMemberOfClass=$mclass
       WHERE testMarksId=$testMarksId  $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING test marks
//
//$testId :testId of the Test
// Author :Dipanjan Bhattacharjee
// Created on : (3.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteTestMarks($testId,$conditions='') {

        //First Add the records into quarantine table
        $query="INSERT INTO ".QUARANTINE_TEST_MARKS_TABLE." (testMarksId,testId,studentId,subjectId,maxMarks,marksScored,isPresent,isMemberOfClass)  SELECT testMarksId,testId,studentId,subjectId,maxMarks,marksScored,isPresent,isMemberOfClass FROM ".TEST_MARKS_TABLE." s WHERE s.testId=".$testId." $conditions";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

        if($ret===false){
            return false;
        }

        //Then delete records from original table
        $query = "DELETE
        FROM ".TEST_MARKS_TABLE."
        WHERE
        testId=$testId
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING test
//
//$testId :testId of the Test
// Author :Dipanjan Bhattacharjee
// Created on : (3.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteTest($testId) {

        //First Add the records into quarantine table
        $query="INSERT INTO ".QUARANTINE_TEST_TABLE."  (testId,employeeId,subjectId,classId,groupId,testTopic,testAbbr,testIndex,testTypeCategoryId,maxMarks,testDate,instituteId,sessionId) SELECT testId,employeeId,subjectId,classId,groupId,testTopic,testAbbr,testIndex,testTypeCategoryId,maxMarks,testDate,instituteId,sessionId FROM ".TEST_TABLE." s WHERE s.testId=".$testId;
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);

        if($ret===false){
            return false;
        }

        //Then delete records from original table
        $query = "DELETE
        FROM ".TEST_TABLE."
        WHERE
        testId=$testId";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//**********************FUNCTIONS NEEDED FOR Entering Marks  IN TEACHER MODULE*************************


//**********************FUNCTIONS NEEDED FOR Displaying BirthDay,Anniversary wishes  IN TEACHER MODULE*************************

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displayin employee/teacher birthday
//
//$id:cityId
// Author :Dipanjan Bhattacharjee
// Created on : (29.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function checkBirthDay(){
    global $sessionHandler;
    $teacherId=$sessionHandler->getSessionVariable('EmployeeId');

    $query="SELECT COUNT(*) AS birthDay
            FROM employee e WHERE employeeId=".$teacherId." AND dateOfBirth=CURRENT_DATE";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displayin employee/teacher marriage
//
//$id:cityId
// Author :Dipanjan Bhattacharjee
// Created on : (29.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function checkMarriageDay(){
    global $sessionHandler;
    $teacherId=$sessionHandler->getSessionVariable('EmployeeId');

    $query="SELECT COUNT(*) AS marriageDay
            FROM employee e WHERE employeeId=".$teacherId." AND dateOfMarriage=CURRENT_DATE";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//**********************FUNCTIONS NEEDED FOR Displaying BirthDay,Anniversary wishes  IN TEACHER MODULE*************************



//**********************FUNCTIONS NEEDED FOR Displaying Teacher Time Table  IN TEACHER MODULE*************************

//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of teacher
//
// Author : Dipanjan Bhattacharjee
// Created on : 22.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getTeacherTimeTable ($conditions='')
 {
   global $sessionHandler;

   $query = "SELECT tt.periodId, tt.daysOfWeek, p.periodNumber,CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,
            SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) as className, gr.classId,
            sub.subjectName,sub.subjectAbbreviation,sub.subjectCode,
            r.roomName,r.roomAbbreviation,
            gr.groupShort
            FROM  ".TIME_TABLE_TABLE." tt , period p, `group` gr,  subject sub, employee emp, room r, class cl, time_table_labels ttl
            WHERE tt.periodId = p.periodId
            AND tt.groupId = gr.groupId AND gr.classId = cl.classId
            AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
            AND tt.roomId = r.roomId
            AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
            AND tt.periodId=p.periodId
            AND tt.toDate IS NULL
            AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
            AND tt.employeeId=".$sessionHandler->getSessionVariable('EmployeeId')."
            AND tt.timeTableLabelId=ttl.timeTableLabelId
            AND ttl.isActive=1
            $conditions
            order by  p.periodNumber,tt.daysOfWeek";
     //echo $query;
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }


//------------------------------------------------------------------------------------------------
// This Function  used to show alerts when time table of a teacher changed
//
// Author : Dipanjan Bhattacharjee
// Created on : 21.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getTeacherTimeTableAlert ($conditions='')
 {
   global $sessionHandler;

   $query = "SELECT COUNT(*) AS talert
            FROM  ".TIME_TABLE_TABLE." tt , period p, `group` gr,  subject sub, employee emp, room r, class cl,time_table_labels ttl
            WHERE tt.periodId = p.periodId
            AND tt.groupId = gr.groupId AND gr.classId = cl.classId
            AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
            AND tt.roomId = r.roomId
            AND cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
            AND tt.periodId=p.periodId
            AND tt.fromDate =CURRENT_DATE
            AND tt.toDate IS NULL
            AND tt.timeTableLabelId=ttl.timeTableLabelId
            AND ttl.isActive=1
            AND tt.sessionId=".$sessionHandler->getSessionVariable('SessionId'). "
            AND tt.employeeId=".$sessionHandler->getSessionVariable('EmployeeId')."
            $conditions order by  p.periodNumber,tt.daysOfWeek";


            //echo $query;
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

 //**********************FUNCTIONS NEEDED FOR Displaying Teacher Time Table  IN TEACHER MODULE*************************



//**********************FUNCTIONS NEEDED FOR DISPLAYING CLASS WISE GRADES AND MARKS LIST*************************



public function getGraceMarksList($conditions='',$subjectId, $orderBy = ' studentName'){

  global $sessionHandler;
  global $REQUEST_DATA;

  $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
  $optionalGroup=0;
  if($group!=0){
     //check whether it is optional group or not
     $optGrArray=$this->checkOptionalGroup($group);
     if(count($optGrArray)>0 and is_array($optGrArray)){
         $optionalGroup=$optGrArray[0]['isOptional'];
     }
     else{
         $optionalGroup=0;
     }
  }

    if($optionalGroup==0){//if this group is not optional group
     $query="SELECT
                    s.studentId,
                    CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                    IFNULL(s.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                    IFNULL(s.universityRollNo,'".NOT_APPLICABLE_STRING."') AS universityRollNo,
                    SUM( ttm.maxMarks ) AS maxMarks,
                    SUM( ttm.marksScored ) AS marksScored,
                    IF(tgm.graceMarks IS NULL , 0, tgm.graceMarks ) AS graceMarks
             FROM
                    student_groups sg, class c, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                    LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON ( tgm.classId = s.classId AND tgm.studentId = s.studentId AND tgm.subjectId=".$subjectId." )
             WHERE
                    s.studentId = sg.studentId
                    AND s.classId = c.classId
                    AND s.classId = sg.classId
                    AND ttm.classId = s.classId
                    AND ttm.studentId = s.studentId
                    AND ttm.conductingAuthority IN ( 1, 3 )
                    $conditions
                    GROUP BY ttm.studentId
                    ORDER BY $orderBy
             ";
    }
    else{
       $query="SELECT
                    s.studentId,
                    CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                    IFNULL(s.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                    IFNULL(s.universityRollNo,'".NOT_APPLICABLE_STRING."') AS universityRollNo,
                    SUM( ttm.maxMarks ) AS maxMarks,
                    SUM( ttm.marksScored ) AS marksScored,
                    IF(tgm.graceMarks IS NULL , 0, tgm.graceMarks ) AS graceMarks
             FROM
                    student_optional_subject sg, class c, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
                    LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON ( tgm.classId = s.classId AND tgm.studentId = s.studentId AND tgm.subjectId=".$subjectId." )
             WHERE
                    s.studentId = sg.studentId
                    AND s.classId = c.classId
                    AND s.classId = sg.classId
                    AND ttm.classId = s.classId
                    AND ttm.studentId = s.studentId
                    AND ttm.subjectId=sg.subjectId
                    AND ttm.conductingAuthority IN ( 1, 3 )
                    $conditions
                    GROUP BY ttm.studentId
                    ORDER BY $orderBy
             ";
    }
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//used to delete old records
public function deleteGraceMarks($studentId,$classId,$subjectId){
    $query="DELETE from ".TEST_GRACE_MARKS_TABLE." WHERE studentId=".$studentId." AND classId=".$classId." AND subjectId=".$subjectId;
    return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

//used to insert new records
public function insertGraceMarks($studentId,$classId,$subjectId,$graceMarks){
    $query="INSERT INTO ".TEST_GRACE_MARKS_TABLE." 
            (studentId,classId,subjectId,internalGraceMarks,graceMarks) 
             VALUES
            ($studentId,$classId,$subjectId,$graceMarks,$graceMarks)";
    return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


//------------------------------------------------------------------------------------------------
// This Function  gets the grades data of a class
//
// Author : Dipanjan Bhattacharjee
// Created on : 7.08.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getClassWiseGradeList($conditions='', $limit = '',$orderBy=' LENGTH(s.rollNo)+0,s.rollNo'){

  global $sessionHandler;
  global $REQUEST_DATA;

  //get list of students having this subject as optional
  $extC= $this->getOptionalStudentList($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId']);
  if($extC!=""){
     $extC=" AND s.studentId IN ($extC) ";
  }

  //ORDER BY LENGTH(s.rollNo)+0,s.rollNo,tm.subjectId,s.studentId, tt.examType, t.testId, tm.studentId
 $query="SELECT
                CONCAT( s.firstName, ' ', s.lastName ) AS studentName,
                IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                s.studentId,
                su.subjectName,
                su.subjectCode,
                IF( tt.examType = 'PC', 'Internal', 'External' ) AS examType,
                tt.testTypeName,
                CONCAT( t.testAbbr, t.testIndex ) AS testName,
                su.subjectCode,
                (tm.maxMarks) AS totalMarks,
                IF( tm.isMemberOfClass =0, 'Not MOC', IF( isPresent =1, tm.marksScored, 'A' ) ) AS obtainedMarks
        FROM
                ".TEST_TABLE." t, test_type_category tt, ".TEST_MARKS_TABLE." tm,
                student s, subject su,class c,`group` g
        WHERE
                t.testTypeCategoryId = tt.testTypeCategoryId
                AND t.testId = tm.testId
                AND tm.studentId = s.studentId
                AND tm.subjectId = su.subjectId
                AND s.classId=c.classId
                AND c.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                AND c.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                AND t.groupId=g.groupId
                AND t.employeeId='".$sessionHandler->getSessionVariable('EmployeeId')."'
                $extC
         $conditions
         ORDER BY $orderBy
		 $limit";

        // echo $query;
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}

//------------------------------------------------------------------------------------------------
// This Function  gets the list of attendance data of a class
//
// Author : Dipanjan Bhattacharjee
// Created on : 7.08.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
public function getClassWiseAttendanceList($condition='',$orderBy=' s.firstName'){

    global $sessionHandler;
    global $REQUEST_DATA;
    $attendaceThreshold=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
    $attendaceInFraction=$sessionHandler->getSessionVariable('ATTENDANCE_IN_FRACTION');
    $fraction=0;
    if($attendaceInFraction==0){
        $fraction=0;
    }
    else if($attendaceInFraction==1){
        $fraction=1;
    }

   //[WE DONT NEED THIS.BECAUSE IT IS ALREADY FILTERED IN GIVING ATTENDANCE MASTERS.WE ARE USING THIS AS EXTRA SECURITY MEASURE]
   if($REQUEST_DATA['class']!="" and $REQUEST_DATA['subject']!=""){
     //get list of students having this subject as optional
     /*
     $extC= $this->getOptionalStudentList($REQUEST_DATA['class'],$REQUEST_DATA['subject']);
     if($extC!=""){
      $extC=" AND s.studentId IN ($extC) ";
    }
    */
   }

   //AND att.employeeId=".$sessionHandler->getSessionVariable('EmployeeId')."

    $query="SELECT
                    CONCAT(s.firstName,' ',s.lastName) AS studentName,
                    IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                    IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                    su.subjectName,
                    su.subjectCode,
                    ROUND(FORMAT( SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) ) , 1 ),$fraction) AS attended,
                    SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,
                    ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )/SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )*100,2)
                    AS percentage,
                    IF(ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )/SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )*100,2) < $attendaceThreshold,-1,1)
                    AS shortAttendance
            FROM
                   `group` g ,student s
                   INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                   LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
                   INNER JOIN subject su ON su.subjectId = att.subjectId
                   INNER JOIN class c ON c.classId = s.classId
            WHERE
                   att.groupId=g.groupId
                   AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."

                   $extC
                   $condition
            GROUP BY att.subjectId, att.studentId
            ORDER BY $orderBy ";

   // echo $query;
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }


//**********************FUNCTIONS NEEDED FOR DISPLAYING CLASS WISE GRADES AND MARKS LIST*************************


//**********************FUNCTIONS NEEDED FOR DISPLAYING ADMIN MESSGAES FOR A TEACHER******************************

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying admin message list for a a teacher.
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (18.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.


 public function getAdminMessageList($conditions='', $limit = '', $orderBy=' us.userName') {

       global $sessionHandler;

       $query="SELECT
                     adm.messageId,
                     IF(e.employeeName IS NULL,us.userName,e.employeeName) AS userName,
                     adm.subject,adm.message,adm.dated,adm.visibleFromDate,adm.visibleToDate,
                     adm.messageFile
               FROM
                     admin_messages adm,
                     user us
                     LEFT JOIN employee e ON e.userId=us.userId
               WHERE
                     us.userId=adm.senderId AND
                     adm.receiverIds LIKE '%~".$sessionHandler->getSessionVariable('EmployeeId')."~%'
                     AND adm.receiverType='Employee'
                     AND adm.messageType='Dashboard'
                     AND adm.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                     AND adm.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                     AND (CURRENT_DATE >= adm.visibleFromDate AND CURRENT_DATE <= adm.visibleToDate)
                     $conditions
               ORDER BY $orderBy
               $limit ";
       //echo $query;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR counting total no of admin message list for a a teacher.
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (22.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTotalAdminMessage($conditions='') {

       global $sessionHandler;

       $query="SELECT COUNT(*) AS totalRecords
               FROM admin_messages adm,user us
               LEFT JOIN employee e ON e.userId=us.userId
               WHERE
               us.userId=adm.senderId AND
               adm.receiverIds LIKE '%~".$sessionHandler->getSessionVariable('EmployeeId')."~%'
               AND adm.receiverType='Employee'
               AND adm.messageType='Dashboard'
               AND adm.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
               AND adm.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
               AND (CURRENT_DATE >= adm.visibleFromDate AND CURRENT_DATE <= adm.visibleToDate)
               $conditions ";
        //echo  $query;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//**********************FUNCTIONS NEEDED FOR DISPLAYING ADMIN MESSGAES FOR A TEACHER******************************


//*******************FUNCTIONS NEEDED FOR DISPLAYING/ADDING/EDITING/DELETING COURSE RESOURCE FOR A TEACHER********

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of COURSE RESOURCE for a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getResourceList($conditions='', $limit = '', $orderBy=' subject'){

     global $sessionHandler;
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
     $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
/* $query = "SELECT
				 t.courseResourceId, t.resourceName, t.description, t.subject, t.courseGroupId, t.courseGroupName,
				 t.resourceUrl, t.attachmentFile, t.postedDate, t.downloadCount
			FROM
				(SELECT
					DISTINCT courseResourceId,
					resourceName,
					description,
					subjectCode AS SUBJECT,
					GROUP_CONCAT(DISTINCT course_resources.groupId ORDER BY course_resources.groupId) AS courseGroupId,
					GROUP_CONCAT(DISTINCT grp.groupName ORDER BY grp.groupName SEPARATOR ', ') AS courseGroupName,
					IF(IFNULL(resourceUrl,'')='',-1,resourceUrl) AS resourceUrl,
					IF(IFNULL(attachmentFile,'')='',-1,attachmentFile) AS attachmentFile,
					postedDate,
					IFNULL((SELECT COUNT(crd.courseResourceId) FROM course_resource_download crd WHERE crd.courseResourceId=course_resources.courseResourceId AND course_resources.attachmentFile GROUP BY  course_resources.courseResourceId),0) AS downloadCount
			FROM
					course_resources,resource_category,`subject`, `group` grp
			WHERE
				  course_resources.resourceTypeId=resource_category.resourceTypeId
				  AND
				  course_resources.subjectId=subject.subjectId
				  AND
				  course_resources.groupId=grp.groupId
				  AND
				  course_resources.instituteId=$instituteId
				  AND
				  course_resources.sessionId=$sessionId
				  AND
				  course_resources.employeeId=$employeeId
				  AND
				  resource_category.instituteId=$instituteId
				  $conditions
		 GROUP BY
						 course_resources.employeeId, course_resources.subjectId) AS t
		 ORDER BY
					$orderBy  $limit ";  */

     $query="SELECT
                   courseResourceId,
                   resourceName,
                   description,
                   subjectCode AS subject,
                   IF(IFNULL(resourceUrl,'')='',-1,resourceUrl) AS resourceUrl,
                   IF(IFNULL(attachmentFile,'')='',-1,attachmentFile) AS attachmentFile,
                   postedDate,
                   IFNULL((SELECT COUNT(crd.courseResourceId) FROM course_resource_download crd WHERE crd.courseResourceId=course_resources.courseResourceId AND course_resources.attachmentFile GROUP BY  course_resources.courseResourceId),0) AS downloadCount
             FROM
                   course_resources,resource_category,subject
             WHERE
                  course_resources.resourceTypeId=resource_category.resourceTypeId

                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.instituteId=$instituteId
                  AND
                  course_resources.sessionId=$sessionId
                  AND
                  course_resources.employeeId=$employeeId
                  AND  resource_category.instituteId=$instituteId
                  $conditions
                  ORDER BY $orderBy $limit " ;
    // echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR getting total number of COURSE RESOURCE for a teacher
    //
    //$conditions :db clauses
    // Author :Dipanjan Bhattacharjee
    // Created on : (19.07.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------------------------------------------------
    public function getTotalResource($conditions=''){

     global $sessionHandler;
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
     $employeeId=$sessionHandler->getSessionVariable('EmployeeId');

     $query="SELECT COUNT(*) AS totalRecords
             FROM
               course_resources,resource_category,subject
             WHERE
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.instituteId=$instituteId
                  AND
                  course_resources.sessionId=$sessionId
                  AND
                  course_resources.employeeId=$employeeId
                  AND        resource_category.instituteId=$instituteId
                  $conditions
                  " ;
     //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR displaying resource details for a particular resource
    //
    //$conditions :db clauses
    // Author :Dipanjan Bhattacharjee
    // Created on : (05.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------------------------------------------------
    public function getResource($courseResourceId){

        $query="SELECT
                      courseResourceId,
                      resourceTypeId,
                      description,
                      subjectId,
					  groupId,
                      IF(resourceUrl IS NULL,-1,resourceUrl) AS resourceUrl,
                      IF(attachmentFile IS NULL OR attachmentFile='',-1,attachmentFile) AS attachmentFile,
                      DATE_FORMAT(postedDate,'%d-%b-%Y') AS postedDate
                FROM
                      course_resources
                WHERE
                      courseResourceId=".$courseResourceId;

          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR displaying resource details for a particular resource
    //
    //$conditions :db clauses
    // Author :Dipanjan Bhattacharjee
    // Created on : (05.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------------------------------------------------
    public function checkResourceExists($courseResourceId){

        $query="SELECT attachmentFile
                FROM course_resources
                WHERE courseResourceId=".$courseResourceId;

          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Resource
//
// Author :Dipanjan Bhattacharjee
// Created on : (05.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function addResource() {
        global $REQUEST_DATA;
	  global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
        $currentDate=date('Y-m-d');
        return SystemDatabaseManager::getInstance()->runAutoInsert('course_resources',
         array(
                'employeeId','resourceTypeId','description','subjectId','groupId','resourceUrl','postedDate','instituteId','sessionId'
              ),
         array(
               $employeeId, $REQUEST_DATA['resourceType'], add_slashes($REQUEST_DATA['description']),
               $REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],add_slashes($REQUEST_DATA['resourceUrl']),$currentDate,$instituteId,$sessionId
              )
        );
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Resource
//
//$id:cityId
// Author :Dipanjan Bhattacharjee
// Created on : (05.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editResource($id) {
         global $REQUEST_DATA;
         global $sessionHandler;
         $instituteId=$sessionHandler->getSessionVariable('InstituteId');
         $sessionId=$sessionHandler->getSessionVariable('SessionId');
         $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
         $currentDate=date('Y-m-d');

        return SystemDatabaseManager::getInstance()->runAutoUpdate('course_resources',
         array(
                'employeeId','resourceTypeId','description','subjectId','groupId','resourceUrl','postedDate','instituteId','sessionId'
              ),
         array(
               $employeeId, $REQUEST_DATA['resourceType'], add_slashes($REQUEST_DATA['description']),
               $REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],add_slashes($REQUEST_DATA['resourceUrl']),$currentDate,$instituteId,$sessionId
              ),
          "courseResourceId=$id"
         );
    }


    //-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Resource
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee
// Created on : (05.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteResource($courseResourceId) {

        $query = "DELETE
        FROM course_resources
        WHERE courseResourceId=$courseResourceId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting resource attachment  in cource_resource table
//
//$id :id of the notice
//$fileName: name of the file
// Author :Dipanjan Bhattacharjee
// Created on : (05.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function updateCourseResourceFile($id, $fileName) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('course_resources',
        array('attachmentFile'),
        array($fileName), "courseResourceId=$id" );
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of COURSE RESOURCE for a Cource used in student tabs
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (05.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getStudentCourseResourceList($studentId,$classId='',$conditions='', $orderBy=' subject',$limit=''){
     global $REQUEST_DATA;
     global $sessionHandler;

     if($classId!='' and $classId!=0){
      $classCondition=" AND sg.classId=".add_slashes($classId);
     }

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
     $employeeId=$sessionHandler->getSessionVariable('EmployeeId');

     $query="SELECT courseResourceId,resourceName,description,
             CONCAT(subjectName,'(',subjectCode,')') AS subject,
             IF(resourceUrl IS NULL,-1,resourceUrl) AS resourceUrl,
             IF(attachmentFile IS NULL,-1,attachmentFile) AS attachmentFile,
             IF(course_resources.employeeId=$employeeId,'Me',employee.employeeName) AS employeeName,
             postedDate

             FROM
               course_resources,resource_category,subject,employee
             WHERE
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId
                  AND
                  course_resources.sessionId=$sessionId
                  AND        resource_category.instituteId=$instituteId
                  AND
                  course_resources.subjectId
                   IN
                    (
                      SELECT
                            DISTINCT sc.subjectId
                      FROM
                            subject_to_class sc, student_groups sg
                      WHERE
                            sc.classId=sg.classId
                            AND sg.studentId=$studentId
                            AND sg.instituteId=$instituteId
                            AND sg.sessionId=$sessionId
                            $classCondition
                      UNION
                       SELECT
                             DISTINCT sg.subjectId
                       FROM
                             class c, student_optional_subject sg
                       WHERE
                             c.classId=sg.classId
                             AND sg.studentId=$studentId
                             AND c.instituteId=$instituteId
                             AND c.sessionId=$sessionId
                             $classCondition
                    )
                  $conditions
                  ORDER BY $orderBy
                  $limit " ;
     //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting total no of COURSE RESOURCE for a Cource used in student tabs
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (11.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTotalStudentCourseResource($studentId,$classId='',$conditions=''){

     global $REQUEST_DATA;
     global $sessionHandler;

     if($classId!='' and $classId!=0){
      $classCondition=" AND sg.classId=".add_slashes($classId);
     }

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
     $employeeId=$sessionHandler->getSessionVariable('EmployeeId');

     $query="SELECT
                COUNT(*) AS totalRecords
             FROM
               course_resources,resource_category,subject,employee
             WHERE
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId
                  AND
                  course_resources.sessionId=$sessionId
                  AND        resource_category.instituteId=$instituteId
                  AND
                  course_resources.subjectId
                   IN
                    (
                      SELECT
                            DISTINCT sc.subjectId
                      FROM
                            subject_to_class sc, student_groups sg
                      WHERE
                            sc.classId=sg.classId
                            AND sg.studentId=$studentId
                            AND sg.instituteId=$instituteId
                            AND sg.sessionId=$sessionId
                            $classCondition
                      UNION
                       SELECT
                             DISTINCT sg.subjectId
                       FROM
                             class c, student_optional_subject sg
                       WHERE
                             c.classId=sg.classId
                             AND sg.studentId=$studentId
                             AND c.instituteId=$instituteId
                             AND c.sessionId=$sessionId
                             $classCondition
                    )
                  $conditions
                  " ;

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//*******************FUNCTIONS NEEDED FOR DISPLAYING/ADDING/EDITING/DELETING COURSE RESOURCE FOR A TEACHER********

//------------------------------------------------------------------------------------------------
// This Function  gets the employee name entery
//
// Author : Parveen Sharma
// Created on : 01.12.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

      public function getEmployeeNames($conditions=''){

        global $sessionHandler;
               $instituteId=$sessionHandler->getSessionVariable('InstituteId');
               $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT distinct e.employeeId, e.employeeName
                  FROM
                        feedback_teacher ft, feedback_questions fq, feedback_survey fs, employee e
                  WHERE
                        ft.employeeId=e.employeeId AND
                        ft.feedbackQuestionId=fq.feedbackQuestionId AND
                        fq.feedbackSurveyId=fs.feedbackSurveyId AND
                        fs.instituteId=$instituteId AND fs.sessionId=$sessionId
                        $conditions

                  ";
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

      //--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Feedback Data
//
// Author :Parveen Sharma
// Created on : 01-12-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getFeedBackData($fieldValue='',$condition='') {
        global $sessionHandler;

        $query = "
                SELECT
                                fq.feedbackQuestionId,
                                e.employeeName,
                                fq.feedbackQuestion,
                                $fieldValue
                                SUM(fg.feedbackGradeValue)/COUNT(studentId) AS ratio,
                                fs.feedbackSurveyLabel, COUNT(studentId) AS totalStudent
                  FROM          feedback_category fc,
                                feedback_questions fq,
                                feedback_grade fg,
                                feedback_survey fs,
                                feedback_teacher ft,
                                employee e
                  WHERE         ft.feedbackQuestionId  = fq.feedbackQuestionId     AND
                                ft.feedbackGradeId     = fg.feedbackGradeId        AND
                                ft.employeeId          = e.employeeId              AND
                                fq.feedbackCategoryId  = fc.feedbackCategoryId     AND
                                fq.feedbackSurveyId    = fs.feedbackSurveyId       AND
                                fs.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."  AND
                                fc.instituteId = ".$sessionHandler->getSessionVariable('SessionId')."  AND
                                fs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                                $condition
                GROUP BY  fq.feedbackSurveyId, ft.employeeId, fq.feedbackQuestionId,
                          e.employeeName, fq.feedbackQuestion
                ORDER BY  fq.feedbackCategoryId, fq.feedbackQuestionId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

  //--------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET General Feedback Data
//
// Author :Rajeev Aggarwal
// Created on : 06-01-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
  public function getFeedBackData1($fieldValue='',$condition='') {
        global $sessionHandler;

        $query = "
                SELECT
                                fq.feedbackQuestionId,
                                fc.feedbackCategoryName,
                                fq.feedbackQuestion,
                                $fieldValue
                                SUM(fg.feedbackGradeValue)/COUNT(DISTINCT sgs.userId) AS ratio,
                                fs.feedbackSurveyLabel
                  FROM          feedback_category fc,
                                feedback_questions fq,
                                feedback_grade fg,
                                feedback_survey fs,
                                feedback_survey_answer  sgs
                  WHERE         sgs.feedbackQuestionId  = fq.feedbackQuestionId     AND
                                sgs.feedbackGradeId     = fg.feedbackGradeId        AND

                                fq.feedbackCategoryId  = fc.feedbackCategoryId     AND
                                fq.feedbackSurveyId    = fs.feedbackSurveyId       AND
                                fs.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."  AND
                                fc.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."  AND
                                fs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                                $condition
                GROUP BY  fq.feedbackSurveyId,  fq.feedbackQuestionId, fq.feedbackQuestion
                ORDER BY  fq.feedbackCategoryId, fq.feedbackQuestionId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }
  //------------------------------------------------------------------------------------------------
// This Function  give the count of user type of survey
//
// Author : Rajeev Aggarwal
// Created on : 18.05.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
      public function getSurveyDetail ($condition='')
      {
        global $sessionHandler;

        $query = "SELECT
                COUNT(*) as totalRecords,
                if(userType='E','Employee',(IF(userType='S','Student','Parent'))) as surveyUser,
                visibleFrom,
                visibleTo

                FROM

                `survey_visible_to_users` svu,
                feedback_survey fs

                WHERE

                fs.feedbackSurveyId=svu.feedbackSurveyId
                AND fs.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                AND fs.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                $condition

                GROUP BY userType" ;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

//**********************FUNCTIONS NEEDED FOR DISPLAING Attendance Summary************

 //-------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET A LIST OF Attendance
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (06.03.2009)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getAttendanceHistoryList($conditions='', $limit = '', $orderBy=' className') {
        global $sessionHandler;

		   $query = "SELECT
                        e.employeeName,e.employeeId,sub.subjectId,c.classId,
                        g.groupId,g.groupName,g.groupShort,att.fromDate,att.toDate,att.attendanceType,
                        IF(p.periodNumber IS NULL OR p.periodNumber='','---',p.periodNumber) as periodNumber,
                        IF(p.periodId IS NULL OR p.periodId='','-1',p.periodId) as periodId,
                        s.subjectCode,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        MAX(att.lectureDelivered) AS lectureDelivered,
                        att.attendanceId,
                        (SELECT
                              GROUP_CONCAT(DISTINCT topic SEPARATOR ',' )
                         FROM
                              subject_topic sst, topics_taught ttt
                         WHERE
                              ttt.topicsTaughtId = att.topicsTaughtId
							  AND sst.subjectId = att.subjectId
                              AND ttt.employeeId=att.employeeId
                              AND INSTR(ttt.subjectTopicId, CONCAT('~',sst.subjectTopicId,'~'))>0
                         ) AS topic
                  FROM
                        `group` g ,employee e,`subject` sub,class c,time_table_classes ttc,time_table_labels ttl,subject s,
                         ".ATTENDANCE_TABLE." att
                         LEFT JOIN period p ON p.periodId=att.periodId
                  WHERE
                        att.groupId=g.groupId
                        AND att.classId=c.classId
                        AND att.subjectId=sub.subjectId
                        AND att.employeeId=e.employeeId
                        AND att.classId = ttc.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND ttc.timeTableLabelId=ttl.timeTableLabelId
                        AND ttl.isActive=1
                        AND att.subjectId=s.subjectId
                        $conditions
                        GROUP BY att.fromDate,att.employeeId,att.groupId,att.periodId,att.classId
                        ORDER BY $orderBy
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET A LIST OF Attendance
//orderBy: on which column to sort
//Created on : (10.02.2011)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
	public function getAttendanceHistory($conditions='', $limit = '', $orderBy=' className') {
		global $sessionHandler;
		$query ="
					SELECT
								e.employeeName,
								g.groupShort,
								att.fromDate,
								att.toDate,
								IF(p.periodNumber IS NULL OR p.periodNumber='','---',p.periodNumber) AS periodNumber,
								s.subjectCode,
								att.attendanceType,
								att.attendanceId,
								s.subjectId,
								c.classId,
								g.groupId,
								IF(p.periodId IS NULL OR p.periodId='','-1',p.periodId) as periodId,
								SUBSTRING_INDEX(c.className,'-',-3) AS className,
								MAX(att.lectureDelivered) AS lectureDelivered,
								(SELECT
										GROUP_CONCAT(DISTINCT topic SEPARATOR ',' )
									FROM
											`subject_topic` sst,
											`topics_taught` ttt
									WHERE
											ttt.topicsTaughtId = att.topicsTaughtId
									AND		sst.subjectId = att.subjectId
									AND		ttt.employeeId=att.employeeId
									AND		INSTR(ttt.subjectTopicId, CONCAT('~',sst.subjectTopicId,'~'))>0
								) AS topic
					FROM
								`group` g ,employee e,`subject` sub,class c,time_table_classes ttc,time_table_labels ttl,`subject` s,
								".ATTENDANCE_TABLE." att
								LEFT JOIN period p ON p.periodId=att.periodId
					WHERE
								att.groupId=g.groupId
					AND			att.classId=c.classId
					AND			att.subjectId=sub.subjectId
					AND			att.employeeId=e.employeeId
					AND			att.classId = ttc.classId
					AND			c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND			c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND			ttc.timeTableLabelId=ttl.timeTableLabelId
					AND			att.subjectId=s.subjectId
								$conditions
								GROUP BY att.fromDate,att.employeeId,att.groupId,att.periodId,att.classId
								ORDER BY $orderBy
								$limit
			";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getAttendanceHistoryOptions($conditions='') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        DISTINCT att.classId,att.groupId,att.subjectId
                  FROM
                        ".ATTENDANCE_TABLE." att, ".TIME_TABLE_TABLE." t,`group` g,time_table_labels ttl
                  WHERE
                         att.subjectId=t.subjectId
                         AND att.groupId=t.groupId
                         AND att.classId=g.classId
                         AND g.groupId=t.groupId
                         AND t.toDate is null
                         AND t.timeTableLabelId=ttl.timeTableLabelId
                         AND ttl.isActive=1
                         AND t.sessionId=$sessionId
                         ANd t.instituteId=$instituteId
                         $conditions
                  GROUP BY att.classId,att.groupId,att.subjectId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET A LIST OF Attendance
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (06.03.2009)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getTotalAttendanceHistory($conditions='') {
        global $sessionHandler;

        $query = "SELECT
                        att.attendanceId
                  FROM
                        `group` g ,employee e,`subject` sub,class c,time_table_classes ttc,time_table_labels ttl,subject s,
                         ".ATTENDANCE_TABLE." att
                         LEFT JOIN period p ON p.periodId=att.periodId
                  WHERE
                        att.groupId=g.groupId
                        AND att.classId=c.classId
                        AND att.subjectId=sub.subjectId
                        AND att.employeeId=e.employeeId
                        AND att.classId = ttc.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND ttc.timeTableLabelId=ttl.timeTableLabelId
                        AND ttl.isActive=1
                        AND att.subjectId=s.subjectId
                        $conditions
                        GROUP BY att.fromDate,att.employeeId,att.groupId,att.periodId,att.classId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }

//**********************FUNCTIONS NEEDED FOR DISPLAING Attendance Summary************


//*******************FUNCTIONS NEEDED FOR DISPLAYING Test and Attendance Summary acrosss time tables FOR A TEACHER********

public function getTeacherTestsSummery($classId='0',$subjectId='0',$groupId='0',$timeTableLabelId,$orderby=' t.testAbbr',$limit='') {
       if ($classId != "" and $classId != "0") {
            $classCond =" AND c.classId =".add_slashes($classId);
           }
       if ($subjectId != "" and $subjectId != "0") {
            $subjectCond =" AND t.subjectId=".add_slashes($subjectId);
           }
       if ($groupId != "" and $groupId != "0") {
            $groupCond =" AND g.groupId=".add_slashes($groupId);
          }

        global $REQUEST_DATA;
        global $sessionHandler;

       $query = "SELECT
                            CONCAT( sub.subjectName , ' (' , sub.subjectCode , ')' ) as subjectCode,
                            g.groupName,
                            t.testAbbr,t.testTopic,t.testIndex,t.maxMarks,t.testDate,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                            CONCAT(ttc.testTypeName,'-',t.testIndex) AS testTypeName
                     FROM
                            subject sub, `group` g, ".TEST_TABLE." t,
                             ".TIME_TABLE_TABLE." st,".TEST_MARKS_TABLE." tm,test_type_category ttc,
                            class c
                     WHERE
                            st.groupId = t.groupId
                            AND st.subjectId = t.subjectId
                            AND st.employeeId = t.employeeId
                            AND sub.subjectId = t.subjectId
                            AND g.groupId = t.groupId
                            AND c.classId=t.classId
                            AND st.employeeId =".$sessionHandler->getSessionVariable('EmployeeId')."
                            $subjectCond
                            AND st.timeTableLabelId =$timeTableLabelId
                            AND st.toDate IS NULL
                            AND t.testId=tm.testId
                            AND t.testTypeCategoryId=ttc.testTypeCategoryId
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            $classCond
                            $groupCond
                            GROUP BY t.testId
                     ORDER BY $orderby
                     $limit ";
     //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getTeacherTotalTests($classId='0',$subjectId='0',$groupId='0',$timeTableLabelId) {

        if ($classId != "" and $classId != "0") {
            $classCond =" AND c.classId =".add_slashes($classId);
           }
        if ($subjectId != "" and $subjectId != "0") {
            $subjectCond =" AND t.subjectId=".add_slashes($subjectId);
           }
        if ($groupId != "" and $groupId != "0") {
            $groupCond =" AND g.groupId=".add_slashes($groupId);
          }

        global $REQUEST_DATA;
        global $sessionHandler;

        $query = "SELECT
                            sub.subjectId
                     FROM
                            subject sub, `group` g, ".TEST_TABLE." t,
                             ".TIME_TABLE_TABLE." st,".TEST_MARKS_TABLE." tm,test_type_category ttc,class c
                     WHERE
                            st.groupId = t.groupId
                            AND st.subjectId = t.subjectId
                            AND st.employeeId = t.employeeId
                            AND sub.subjectId = t.subjectId
                            AND g.groupId = t.groupId
                            AND c.classId=t.classId
                            AND st.employeeId =".$sessionHandler->getSessionVariable('EmployeeId')."
                            $subjectCond
                            AND st.timeTableLabelId =$timeTableLabelId
                            AND st.toDate IS NULL
                            AND t.testId=tm.testId
                            AND t.testTypeCategoryId=ttc.testTypeCategoryId
                            AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            AND t.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            $classCond
                            $groupCond
                            GROUP BY t.testId
                     ";
     //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//this function is used to calculate attendance summery of a teacher
public function getTeacherAttendanceSummeryList($classId='0',$subjectId='0',$groupId='0',$timeTableLabelId,$orderBy='',$limit='') {
        if ($classId != "" and $classId != "0") {
            $classCond =" AND att.classId =".add_slashes($classId);
           }
        if ($subjectId != "" and $subjectId != "0") {
            $subjectCond =" AND att.subjectId=".add_slashes($subjectId);
           }
        if ($groupId != "" and $groupId != "0") {
            $groupCond =" AND att.groupId=".add_slashes($groupId);
          }

        global $REQUEST_DATA;
        global $sessionHandler;

       $query = "SELECT
                        subjectCode,
                        groupName,className, sum( lectureDelivered ) AS totalDelivered
                        FROM (
                                SELECT
                                        CONCAT( s.subjectName , ' (' , s.subjectCode , ')' ) as subjectCode,
                                        g.groupName,g.groupId,s.subjectId,c.classId,
                                        max( att.lectureDelivered ) AS lectureDelivered,
                                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                                FROM
                                       ".ATTENDANCE_TABLE." att, time_table_classes ttc, `group` g, subject s,class c
                                WHERE
                                        att.classId = ttc.classId
                                        AND ttc.timeTableLabelId ='$timeTableLabelId'
                                        AND att.employeeId = '".$sessionHandler->getSessionVariable('EmployeeId')."'
                                        AND att.groupId = g.groupId
                                        AND att.subjectId = s.subjectId
                                        AND att.classId=c.classId
                                        $subjectCond
                                        $sectionCond
                                        $groupCond
                                        GROUP BY att.fromDate, att.groupId,att.classId
                            ) AS t
                        GROUP BY groupId, subjectId,classId
                        $orderBy
                        $limit";
     //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//this function is used to calculate attendance summery of a teacher
public function getTotalTeacherAttendanceSummery($classId='0',$subjectId='0',$groupId='0',$timeTableLabelId) {
        if ($classId != "" and $classId != "0") {
            $classCond =" AND att.classId =".add_slashes($classId);
           }
        if ($subjectId != "" and $subjectId != "0") {
            $subjectCond =" AND att.subjectId=".add_slashes($subjectId);
           }
        if ($groupId != "" and $groupId != "0") {
            $groupCond =" AND att.groupId=".add_slashes($groupId);
          }

        global $REQUEST_DATA;
        global $sessionHandler;

       $query = "SELECT
                        subjectCode,
                        groupName,className, sum( lectureDelivered ) AS totDelivered
                        FROM (
                                SELECT
                                        CONCAT( s.subjectName , ' (' , s.subjectCode , ')' ) as subjectCode,
                                        g.groupName,g.groupId,s.subjectId,c.classId,
                                        max( att.lectureDelivered ) AS lectureDelivered,
                                        className
                                FROM
                                       ".ATTENDANCE_TABLE." att, time_table_classes ttc, `group` g, subject s,class c
                                WHERE
                                        att.classId = ttc.classId
                                        AND ttc.timeTableLabelId ='$timeTableLabelId'
                                        AND att.employeeId = '".$sessionHandler->getSessionVariable('EmployeeId')."'
                                        AND att.groupId = g.groupId
                                        AND att.subjectId = s.subjectId
                                        AND att.classId=c.classId
                                        $subjectCond
                                        $sectionCond
                                        $groupCond
                                        GROUP BY att.fromDate, att.groupId ,att.classId
                            ) AS t
                        GROUP BY groupId, subjectId,classId
                        ";
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Offence/Achievements List
// Author :Parveen Sharma
// Created on : (29.05.2009)
// Modified By: Dipanjan Bhattacharjee
// Modified on : (14.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
    public function getStudentOffenceList($condition='',$orderBy='offenseName',$limit=''){

        global $sessionHandler;

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query="        SELECT
                               sd.disciplineId, s.rollNo, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                               REPLACE(SUBSTRING_INDEX( cls.className, '-' , -3 ) , '-', '')  AS className,
                               gr.groupName, off.offenseName, sd.offenseDate,
                               sd.remarks, sd.reportedBy, e.employeeName, e.employeeCode, sp.periodName
                        FROM
                               `offense` off, `student_discipline` sd, `class` cls, `study_period` sp,
                                student_groups sg, `group` gr, student s,  ".TIME_TABLE_TABLE." tt, employee e
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
                               cls.instituteId = '$instituteId' AND
                               cls.sessionId = '$sessionId'  AND
                               gr.groupId = tt.groupId    AND
                               tt.toDate is NULL AND
                               tt.instituteId = '$instituteId' AND
                               tt.sessionId = '$sessionId'
                               $condition
                               GROUP BY sd.studentId
                        UNION
                           SELECT
                               sd.disciplineId, s.rollNo, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                               REPLACE(SUBSTRING_INDEX( cls.className, '-' , -3 ) , '-', '')  AS className,
                               gr.groupName, off.offenseName, sd.offenseDate,
                               sd.remarks, sd.reportedBy, e.employeeName, e.employeeCode, sp.periodName
                        FROM
                               `offense` off, `student_discipline` sd, `class` cls, `study_period` sp,
                                student_optional_subject sg, `group` gr, student s,  ".TIME_TABLE_TABLE." tt, employee e
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
                               cls.instituteId = '$instituteId' AND
                               cls.sessionId = '$sessionId'  AND
                               gr.groupId = tt.groupId    AND
                               tt.toDate is NULL AND
                               tt.instituteId = '$instituteId' AND
                               tt.sessionId = '$sessionId'
                               $condition
                               GROUP BY sd.studentId

                               ORDER BY $orderBy
                               $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Student Offence/Achievements List Count
// Author :Parveen Sharma
// Created on : (29.05.2009)
// Modified By: Dipanjan Bhattacharjee
// Modified on : (14.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
    public function getTotalStudentOffence($condition=''){

        global $sessionHandler;

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query="        SELECT
                               COUNT(*) AS cnt
                        FROM
                            (SELECT
                                    sd.studentId
                             FROM
                                   `offense` off, `student_discipline` sd, `class` cls, `study_period` sp,
                                    student_groups sg, `group` gr, student s,  ".TIME_TABLE_TABLE." tt
                             WHERE
                                    s.studentId = sd.studentId AND
                                    sp.studyPeriodId = cls.studyPeriodId AND
                                    off.offenseId = sd.offenseId AND
                                    sd.classId = cls.classId AND
                                    sd.classId = sg.classId AND
                                    sd.studentId = sg.studentId AND
                                    sg.classId = cls.classId AND
                                    gr.groupId = sg.groupId  AND
                                    cls.instituteId = '$instituteId' AND
                                    cls.sessionId = '$sessionId'  AND
                                    gr.groupId = tt.groupId    AND
                                    tt.toDate is NULL AND
                                    tt.instituteId = '$instituteId' AND
                                    tt.sessionId = '$sessionId'
                             $condition
                             GROUP BY sd.studentId) AS t
                    UNION
                          SELECT
                               COUNT(*) AS cnt
                          FROM
                            (SELECT
                                    sd.studentId
                             FROM
                                   `offense` off, `student_discipline` sd, `class` cls, `study_period` sp,
                                    student_optional_subject sg, `group` gr, student s,  ".TIME_TABLE_TABLE." tt
                             WHERE
                                    s.studentId = sd.studentId AND
                                    sp.studyPeriodId = cls.studyPeriodId AND
                                    off.offenseId = sd.offenseId AND
                                    sd.classId = cls.classId AND
                                    sd.classId = sg.classId AND
                                    sd.studentId = sg.studentId AND
                                    sg.classId = cls.classId AND
                                    gr.groupId = sg.groupId  AND
                                    cls.instituteId = '$instituteId' AND
                                    cls.sessionId = '$sessionId'  AND
                                    gr.groupId = tt.groupId    AND
                                    tt.toDate is NULL AND
                                    tt.instituteId = '$instituteId' AND
                                    tt.sessionId = '$sessionId'
                             $condition
                             GROUP BY sd.studentId) AS t
                             ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//this function fetch subjects from test table correspoding to logged in employee
public function getUsedTestSubject($conditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query="SELECT
                  DISTINCT s.subjectId,s.subjectCode,s.subjectName
            FROM
                  `subject` s,test_type tt,".TEST_TABLE." t, ".TIME_TABLE_TABLE." tm,time_table_labels ttl
            WHERE
                   tm.employeeId=t.employeeId
                   AND tm.timeTableLabelId=ttl.timeTableLabelId
                   AND tm.toDate IS NULL
                   AND ttl.isActive=1
                   AND tm.sessionId='$sessionId'
                   AND tm.instituteId='$instituteId'
                   AND t.sessionId='$sessionId'
                   AND t.instituteId='$instituteId'
                   AND t.testTypeCategoryId=tt.testTypeCategoryId
                   AND s.subjectId=t.subjectId
                   $conditions";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getUsedTestSubjectAcrossTimeTables($conditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query="SELECT
                  DISTINCT s.subjectId,s.subjectCode,s.subjectName
            FROM
                  `subject` s,test_type tt,".TEST_TABLE." t, ".TIME_TABLE_TABLE." tm,time_table_labels ttl,`group` g
            WHERE
                   tm.employeeId=t.employeeId
                   AND tm.groupId=g.groupId
                   AND tm.timeTableLabelId=ttl.timeTableLabelId
                   AND tm.toDate IS NULL
                   AND tm.sessionId='$sessionId'
                   AND tm.instituteId='$instituteId'
                   AND t.sessionId='$sessionId'
                   AND t.instituteId='$instituteId'
                   AND t.testTypeCategoryId=tt.testTypeCategoryId
                   AND s.subjectId=t.subjectId
                   $conditions";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getUsedTestSubjectTypesAcrossTimeTables($conditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query="SELECT
                   DISTINCT st.subjectTypeId,st.subjectTypeName
            FROM
                  `subject` s,".TEST_TABLE." t, ".TIME_TABLE_TABLE." tm,time_table_labels ttl,`group` g,
                   subject_type st
            WHERE
                   tm.employeeId=t.employeeId
                   AND s.subjectTypeId=st.subjectTypeId
                   AND tm.groupId=g.groupId
                   AND tm.timeTableLabelId=ttl.timeTableLabelId
                   AND tm.toDate IS NULL
                   AND tm.sessionId='$sessionId'
                   AND tm.instituteId='$instituteId'
                   AND t.sessionId='$sessionId'
                   AND t.instituteId='$instituteId'
                   AND s.subjectId=t.subjectId
                   $conditions";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getUsedTestClassesAcrossTimeTables($conditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query="SELECT
                  DISTINCT
                          c.classId,
                          SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
            FROM
                  `class` c, ".TIME_TABLE_TABLE." tm,time_table_labels ttl,`group` g,".TOTAL_TRANSFERRED_MARKS_TABLE."
            WHERE
                   tm.employeeId=t.employeeId
                   AND tm.timeTableLabelId=ttl.timeTableLabelId
                   AND tm.groupId=g.groupId
                   AND g.classId=c.classId
                   AND tm.toDate IS NULL
                   AND tm.sessionId=$sessionId
                   AND tm.instituteId=$instituteId
                   AND t.sessionId=$sessionId
                   AND t.instituteId=$instituteId
                   AND t.testTypeCategoryId=tt.testTypeCategoryId
                   AND c.classId=t.classId
                   $conditions
            ORDER BY c.studyPeriodId";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getLabelMarksTransferredClass($labelId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        DISTINCT
                                c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                        FROM
                                class c, time_table_classes t, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm
                        WHERE   c.classId = t.classId
                                AND t.timeTableLabelId = $labelId
                                AND c.classId = tm.classId
                                AND c.sessionId=$sessionId
                                AND c.instituteId=$instituteId
                                ORDER BY c.studyPeriodId
                  ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

    public function getLabelMarksTransferredClassTeacher($labelId, $employeeId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "
                  SELECT
                        DISTINCT
                                grp.classId, SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className
                        FROM
                                 ".TIME_TABLE_TABLE." tt, time_table_classes ttc, `group` grp, class cls,
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." tm
                        WHERE
                                tt.timeTableLabelId = $labelId
                                AND tt.employeeId = $employeeId
                                AND tt.timeTableLabelId = ttc.timeTableLabelId
                                AND grp.classId = ttc.classId
                                AND tt.groupId = grp.groupId
                                AND grp.classId = cls.classId
                                AND cls.classId = tm.classId
                                AND cls.sessionId=$sessionId
                                AND cls.instituteId=$instituteId
                    ORDER BY cls.studyPeriodId
                ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

public function getTransferredSubjects($classId,$conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        //$employeeId=$sessionHandler->getSessionVariable('EmployeeId');
        $query = "
                  SELECT
                        DISTINCT
                                s.subjectId,s.subjectCode,s.subjectName
                        FROM
                                 ".TIME_TABLE_TABLE." tt, time_table_classes ttc, `group` grp, class cls,
                                ".TOTAL_TRANSFERRED_MARKS_TABLE." tm ,`subject` s
                        WHERE
                                tt.timeTableLabelId = ttc.timeTableLabelId
                                AND grp.classId = ttc.classId
                                AND tt.groupId = grp.groupId
                                AND tt.subjectId=s.subjectId
                                AND grp.classId = cls.classId
                                AND cls.classId = tm.classId
                                AND cls.sessionId='$sessionId'
                                AND cls.instituteId='$instituteId'
                                AND cls.classId='$classId'
                                AND tm.subjectId=s.subjectId

                                AND tt.toDate IS NULL
                                $conditions
                    ORDER BY s.subjectCode
                ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//this function fetch subjects from test table correspoding to logged in employee
public function getCreatedTestNamesNew($conditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
  $employeeId= $sessionHandler->getSessionVariable('EmployeeId');

   $query="SELECT
                  DISTINCT t.testId, t.testAbbr,t.testIndex,t.maxMarks, grp.groupShort
            FROM
                  `subject` s,test_type tt,".TEST_TABLE." t, ".TEST_MARKS_TABLE." a, `group` grp 
            WHERE
		   grp.groupId = t.groupId 			
		   AND grp.classId = t.classId
		   AND a.subjectId = t.subjectId
		   AND a.testId = t.testId	
		   AND t.sessionId=$sessionId
                   AND t.instituteId=$instituteId
                   AND t.testTypeCategoryId=tt.testTypeCategoryId
                   AND s.subjectId=t.subjectId
                   $conditions
            ORDER BY t.testAbbr,t.testId";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//this function fetch subjects from test table correspoding to logged in employee
public function getCreatedTestNames($conditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

   $query="SELECT
                  DISTINCT t.testId,t.testAbbr,t.testIndex,t.maxMarks
            FROM
                  `subject` s,test_type tt,".TEST_TABLE." t
            WHERE
                   t.sessionId=$sessionId
                   AND t.instituteId=$instituteId
                   AND t.testTypeCategoryId=tt.testTypeCategoryId
                   AND s.subjectId=t.subjectId
                   $conditions
            ORDER BY t.testAbbr,t.testId";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getTestMarksDistribution($testIds,$queryConditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query="SELECT
                 subjectId,subjectCode,testAbbr,testIndex,testId,testName,per,
                 $queryConditions
           FROM (
                  SELECT
                        tm.studentId,
                        tm.testId,
                        SUM(tm.marksScored) AS marksScored,
                        ROUND(IF(t.maxMarks>0,((tm.marksScored/t.maxMarks)*100),0)) AS per,
                        tm.subjectId,
                        sub.subjectCode,
                        t.testAbbr,
                        t.testIndex,
                        CONCAT(t.testAbbr,'-',t.testIndex) AS testName
                  FROM
                        ".TEST_TABLE." t , ".TEST_MARKS_TABLE." tm,`subject` sub 
                  WHERE
                        t.testId=tm.testId
                        AND tm.testId IN ( $testIds )
                        AND tm.subjectId=sub.subjectId
                GROUP BY tm.subjectId, tm.studentId,tm.testId
                ORDER BY t.testAbbr
                ) AS t
           GROUP BY subjectId,testId
           ORDER BY testAbbr,testId";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getTestMarksDistributionNew($testIds,$queryConditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query="SELECT
                 subjectId,subjectCode,testAbbr,testIndex,testId,testName,per,groupShort,
                 $queryConditions
           FROM (
                  SELECT
                        tm.studentId,
                        tm.testId,
                        SUM(tm.marksScored) AS marksScored,
                        ROUND(IF(t.maxMarks>0,((tm.marksScored/t.maxMarks)*100),0)) AS per,
                        tm.subjectId,
                        sub.subjectCode,
                        t.testAbbr,
                        t.testIndex,
                        CONCAT(t.testAbbr,'-',t.testIndex) AS testName,
			grp.groupShort
                  FROM

                        ".TEST_TABLE." t , ".TEST_MARKS_TABLE." tm,`subject` sub, `group` grp
                  WHERE
                        t.testId=tm.testId
			AND grp.groupId = t.groupId
		        AND grp.classId = t.classId
                        AND tm.testId IN ( $testIds )
                        AND tm.subjectId=sub.subjectId
                GROUP BY tm.subjectId, tm.studentId,tm.testId
                ORDER BY t.testAbbr
                ) AS t
           GROUP BY subjectId,testId
           ORDER BY testAbbr,testId";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getTestMarksDistributionDetailData($testIds,$queryConditions=''){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query=" SELECT
                        DISTINCT s.studentId,
                        CONCAT(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        tm.marksScored,
                        t.testAbbr,
                        t.testIndex,
                        t.maxMarks,
                        IF(t.maxMarks>0,((tm.marksScored/t.maxMarks)*100),0) AS per
                  FROM
                        ".TEST_TABLE." t , ".TEST_MARKS_TABLE." tm,`subject` sub ,student s ,`class` c
                  WHERE
                        t.testId=tm.testId
                        AND tm.testId IN ( $testIds )
                        AND tm.subjectId=sub.subjectId
                        AND tm.studentId=s.studentId
                        AND s.classId=c.classId
                        $queryConditions
                  ORDER BY studentName";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getTestMarksComparisonData($testIds){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query=" SELECT
                        MAX(tm.marksScored) AS maxMarksScored,
                        ROUND(AVG(tm.marksScored),2) AS avgMarksScored,
                        t.testAbbr,
                        t.testIndex,
                        t.maxMarks,
                        tm.testId
                  FROM
                        ".TEST_TABLE." t , ".TEST_MARKS_TABLE." tm,`subject` sub
                  WHERE
                        t.testId=tm.testId
                        AND tm.testId IN ( $testIds )
                        AND tm.subjectId=sub.subjectId
                        GROUP BY tm.testId
                  ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getTestMarksIndividualData($testIds,$queryConditions){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

     $query=" SELECT
                        DISTINCT s.studentId,
                        CONCAT(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        tm.marksScored,
                        tm.testId
                  FROM
                        ".TEST_TABLE." t , ".TEST_MARKS_TABLE." tm,`subject` sub ,student s ,`class` c
                  WHERE
                        t.testId=tm.testId
                        AND tm.testId IN ( $testIds )
                        AND tm.subjectId=sub.subjectId
                        AND tm.studentId=s.studentId
                        AND s.classId=c.classId
                        $queryConditions
                  ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getTestMarksGroupWiseComparisonData($conditionName,$queryCondition,$belowCondition=''){
    global $sessionHandler;
    $sessionId   = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $employeeId  = $sessionHandler->getSessionVariable('EmployeeId');
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	$employeeIdCondition ='';
	if($roleId != 1){
	$employeeIdCondition = "AND t.employeeId=$employeeId";
	}
    $query=" SELECT
                        $conditionName,
                        ttc.testTypeName,
                        t.testTypeCategoryId,
                        IF(t.maxMarks>0,((tm.marksScored/t.maxMarks)*100),0) AS per,
                        g.groupName,
                        g.groupId,
                        t.subjectId,
                        t.classId,
                        (
                          SELECT
                            COUNT(testId)
                          FROM
                            ".TEST_TABLE." tt
                          WHERE
                            tt.testTypeCategoryId = t.testTypeCategoryId
                            AND tt.groupId = t.groupId
                            AND tt.subjectId = t.subjectId
                          GROUP BY tt.testTypeCategoryId, tt.subjectId,  tt.groupId
                        ) AS countTests
                  FROM
                        ".TEST_TABLE." t , ".TEST_MARKS_TABLE." tm,test_type_category ttc,
                        `group` g
                  WHERE
                        t.testId=tm.testId
                        AND t.testTypeCategoryId=ttc.testTypeCategoryId
                        AND t.groupId=g.groupId
                        $employeeIdCondition
                        AND t.sessionId=$sessionId
                        AND t.instituteId=$instituteId
                        $queryCondition
                        GROUP BY t.testTypeCategoryId,t.groupId
                        $belowCondition
                        ORDER BY t.groupId,t.testTypeCategoryId
                  ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getSubjectWiseDistribution($selectionCondtions,$queryConditions='',$includeGraceMarks=0,$conductingAuthorityCondition,$groupConditions){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $extraCondition='SUM(ttm.marksScored) AS marksScored,
                     ROUND(IF(SUM(ttm.maxMarks)>0,((SUM(ttm.marksScored)/SUM(ttm.maxMarks))*100),0)) AS per';
    if($includeGraceMarks){
        $extraCondition='(SUM(ttm.marksScored)+IFNULL(tgm.graceMarks,0)) AS marksScored,
                         ROUND(IF(SUM(ttm.maxMarks)>0,(((SUM(ttm.marksScored)+IFNULL(tgm.graceMarks,0))/SUM(ttm.maxMarks))*100),0)) AS per';
    }
    $query="SELECT
                   subjectId,subjectCode,per,classId,
                   $queryConditions
            FROM (

                  SELECT
                        ttm.studentId,
                        $extraCondition,
                        ttm.subjectId,
                        sub.subjectCode,
                        ttm.classId
                  FROM
                        `subject` sub,time_table_classes ttc,student s,
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
                        LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON
                         (
                           tgm.classId=ttm.classId
                           AND tgm.subjectId=ttm.subjectId
                           AND tgm.studentId=ttm.studentId
                         )
                  WHERE
                        ttm.subjectId=sub.subjectId
                        $conductingAuthorityCondition
                        AND s.studentId=ttm.studentId
                        AND ttm.classId=ttc.classId
                        AND CONCAT(s.studentId,ttm.subjectId) IN
                           (
                             SELECT
                                   DISTINCT CONCAT(sg.studentId,sb.subjectId)
                             FROM
                                   student_groups sg,
                                  `group` g,
                                  `subject` sb,
                                   subject_type st,
                                   group_type gt
                             WHERE
                                   sg.classId=g.classId
                                   AND sg.groupId=g.groupId
                                   AND g.groupTypeId=gt.groupTypeId
                                   AND gt.groupTypeCode=st.subjectTypeCode
                                   AND st.subjectTypeId=sb.subjectTypeId
                                   AND sg.instituteId='$instituteId'
                                   AND sg.sessionId='$sessionId'
                                   $groupConditions
                             UNION
                             SELECT
                                    DISTINCT CONCAT(sg.studentId,sb.subjectId)
                             FROM
                                   student_optional_subject sg,
                                  `group` g,
                                  `subject` sb,
                                   subject_type st,
                                   group_type gt
                             WHERE
                                   sg.classId=g.classId
                                   AND sg.groupId=g.groupId
                                   AND g.groupTypeId=gt.groupTypeId
                                   AND gt.groupTypeCode=st.subjectTypeCode
                                   AND st.subjectTypeId=sb.subjectTypeId
                                   $groupConditions
                           )
                        $selectionCondtions
                        GROUP BY ttm.subjectId, ttm.studentId,ttm.classId
                ) AS t
           GROUP BY subjectId,classId";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function getSubjectWiseDistributionDetailData($classId,$subjectId,$queryConditions='',$includeGraceMarks=0,$conductingAuthorityCondition,$groupConditions){

    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');

    $extraCondition='SUM(ttm.marksScored) AS marksScored,
                     ROUND(IF(SUM(ttm.maxMarks)>0,((SUM(ttm.marksScored)/SUM(ttm.maxMarks))*100),0)) AS per';
    if($includeGraceMarks){
        $extraCondition='(SUM(ttm.marksScored)+IFNULL(tgm.graceMarks,0)) AS marksScored,
                         ROUND(IF(SUM(ttm.maxMarks)>0,(((SUM(ttm.marksScored)+IFNULL(tgm.graceMarks,0))/SUM(ttm.maxMarks))*100),0)) AS per';
    }

    $query="
           SELECT
                    ttm.studentId,
                    CONCAT(IFNULL(s.firstName,''),' ',(IFNULL(s.lastName,''))) AS studentName,
                    IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                    IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                    $extraCondition,
                    SUM(ttm.maxMarks) AS maxMarks,
                    ttm.subjectId,
                    sub.subjectCode,
                    ttm.classId
              FROM
                    `subject` sub,time_table_classes ttc,student s,
                    ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
                    LEFT JOIN ".TEST_GRACE_MARKS_TABLE." tgm ON
                     (
                       tgm.classId=ttm.classId
                       AND tgm.subjectId=ttm.subjectId
                       AND tgm.studentId=ttm.studentId
                     )
              WHERE
                    ttm.subjectId=sub.subjectId
                    $conductingAuthorityCondition
                    AND ttm.classId=ttc.classId
                    AND s.studentId=ttm.studentId
                    AND ttm.classId=$classId
                    AND ttm.subjectId=$subjectId
                    AND s.studentId IN
                           (
                             SELECT
                                   DISTINCT sg.studentId
                             FROM
                                   student_groups sg,
                                  `group` g,
                                  `subject` sb,
                                   subject_type st,
                                   group_type gt
                             WHERE
                                   sg.classId=g.classId
                                   AND sg.groupId=g.groupId
                                   AND g.groupTypeId=gt.groupTypeId
                                   AND gt.groupTypeCode=st.subjectTypeCode
                                   AND st.subjectTypeId=sb.subjectTypeId
                                   AND sg.instituteId=$instituteId
                                   AND sg.sessionId=$sessionId
                                   $groupConditions
                             UNION
                             SELECT
                                   DISTINCT sg.studentId
                             FROM
                                   student_optional_subject sg,
                                  `group` g,
                                  `subject` sb,
                                   subject_type st,
                                   group_type gt
                             WHERE
                                   sg.classId=g.classId
                                   AND sg.groupId=g.groupId
                                   AND g.groupTypeId=gt.groupTypeId
                                   AND gt.groupTypeCode=st.subjectTypeCode
                                   AND st.subjectTypeId=sb.subjectTypeId
                                   $groupConditions
                           )
                    GROUP BY ttm.subjectId, ttm.studentId,ttm.classId
                    $queryConditions
              ORDER by marksScored,studentName,rollNo,universityRollNo,marksScored
           ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//Fetch Group Information
public function getGroupInformation($condition=''){
    $query="SELECT
                  g.groupName,g.groupShort
            FROM
                  `group` g
                  $condition
            ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//Fetch Test Type Category Information
public function getTestTypeCategoryInformation($condition=''){
    $query="SELECT
                  t.testTypeName
            FROM
                  test_type_category t
                  $condition
            ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getGroupWiseTestMarksDetails($groupId,$testTypeCategoryId,$subjectId,$classId,$orderBy=' studentName ASC'){
    global $sessionHandler;
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');

   $query="SELECT
                   CONCAT(s.firstName,' ' ,s.lastName) as studentName,
                   IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                   IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                   t.maxMarks,
                   tm.marksScored,
                   CONCAT(t.testAbbr,'-',t.testIndex) AS testName,
                   t.testId
            FROM
                   student s,".TEST_TABLE." t , ".TEST_MARKS_TABLE." tm
            WHERE
                   s.studentId=tm.studentId
                   AND t.testId=tm.testId
                   AND t.sessionId=$sessionId
                   AND t.instituteId=$instituteId
                   AND t.employeeId=$employeeId
                   AND t.groupId=$groupId
                   AND t.subjectId=$subjectId
                   AND t.testTypeCategoryId=$testTypeCategoryId
                   AND t.classId=$classId
                   ORDER BY $orderBy
            ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

/***********THESE FUNCTIONS ARE USED TO DELETE ATTENDANCE FROM "ATTENDANCE HISTORY" SCREEN*************/

 public function fetchAttendanceRecords($attendanceId){
     $query="
             SELECT
                    *
             FROM
                  ".ATTENDANCE_TABLE."
             WHERE
                   attendanceId=$attendanceId
           ";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

 public function quarantineAttendanceRecords($classId,$subjectId,$groupId,$fromDate,$toDate,$periodId,$employeeId){
     global $sessionHandler;
     $userId=$sessionHandler->getSessionVariable('UserId');
     $deletionType=1;
     if($periodId!=-1){
         $periodCondition=' AND periodId='.$periodId;
     }
     /**********THIS CHECK HAS BEEN REMOVED AS INSTRUCTED BY SACHIN SIR AS ON 25.11.2009************
     //AND employeeId=$employeeId
     /**********/
     $query="INSERT INTO ".QUARANTINE_ATTENDANCE_TABLE."
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
                 classId=$classId
                 AND subjectId=$subjectId
                 AND groupId=$groupId
                 AND fromDate='$fromDate'
                 AND toDate='$toDate'

                 $periodCondition
              ";
     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }


 public function deleteAttendanceRecords($classId,$subjectId,$groupId,$fromDate,$toDate,$periodId,$employeeId){
     if($periodId!=-1){
         $periodCondition=' AND periodId='.$periodId;
     }
     /**********THIS CHECK HAS BEEN REMOVED AS INSTRUCTED BY SACHIN SIR AS ON 25.11.2009************
     //AND employeeId=$employeeId
     /**********/
     $query="
              DELETE
              FROM
                   ".ATTENDANCE_TABLE."
              WHERE
                   classId=$classId
                   AND subjectId=$subjectId
                   AND groupId=$groupId
                   AND fromDate='$fromDate'
                   AND toDate='$toDate'

                   $periodCondition
            ";
     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
 }

 /***********THESE FUNCTIONS ARE USED TO DELETE ATTENDANCE FROM "ATTENDANCE HISTORY" SCREEN*************/


  public function fetchStudentRecordsFromTestMarksTable($testId){
     $query="
             SELECT
                    studentId
             FROM
                  ".TEST_MARKS_TABLE."
             WHERE
                   testId=$testId
           ";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

 public function countAttendanceRecords($activeTimeTableLabelId, $concatStr) {
   global $sessionHandler;
	$instituteId		=	$sessionHandler->getSessionVariable('InstituteId');
	$teacherId			=	$sessionHandler->getSessionVariable('EmployeeId');
	$query = "
			SELECT
						COUNT(*) AS cnt
			FROM (
						SELECT
									s.studentId,
									ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage/100), att.lectureAttended)) )/SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered))*100,2) AS percentage
						FROM		student s INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId and att.classId = s.classId)
						LEFT JOIN	attendance_code ac
						ON			(ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
						WHERE		CONCAT(att.subjectId,'#',att.classId)
						IN			($concatStr)
									GROUP BY att.subjectId, att.studentId
				) AS t ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

 public function countAttendanceThresholdRecords($activeTimeTableLabelId, $concatStr) {
   global $sessionHandler;
   $attendaceThreshold =	$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
	$instituteId		=	$sessionHandler->getSessionVariable('InstituteId');
	$teacherId			=	$sessionHandler->getSessionVariable('EmployeeId');
	$query = "
			SELECT
						COUNT(*) AS cnt
			FROM (
						SELECT
									s.studentId,
									ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage/100), att.lectureAttended)) )/SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered))*100,2) AS percentage
						FROM		student s INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId and att.classId = s.classId)
						LEFT JOIN	attendance_code ac
						ON			(ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
						WHERE		CONCAT(att.subjectId,'#',att.classId)
						IN			($concatStr)
									GROUP BY att.subjectId, att.studentId
									having percentage < '$attendaceThreshold'
				) AS t ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

 public function getAttendanceThresholdRecords($activeTimeTableLabelId, $concatStr) {
    global $sessionHandler;
    $attendaceThreshold =	$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
	$instituteId		=	$sessionHandler->getSessionVariable('InstituteId');
	$teacherId			=	$sessionHandler->getSessionVariable('EmployeeId');
	$query = "
						SELECT
									s.studentId, concat(s.firstName,'',s.lastName) as studentName, s.rollNo, s.universityRollNo, SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-3) AS className, sub.subjectCode,s.studentPhoto,
									ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage/100), att.lectureAttended)) )/SUM(IF(att.isMemberOfClass =0, 0, att.lectureDelivered))*100,2) AS percentage
						FROM		subject sub, class cls, student s INNER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId and att.classId = s.classId)
						LEFT JOIN	attendance_code ac
						ON			(ac.attendanceCodeId = att.attendanceCodeId and ac.instituteId = '$instituteId')
						WHERE		CONCAT(att.subjectId,'#',att.classId)
						IN			($concatStr)
						AND			s.classId = cls.classId
						AND			att.subjectId = sub.subjectId
									GROUP BY att.subjectId, att.studentId
									having percentage < '$attendaceThreshold'
						ORDER BY	percentage DESC
									";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

	public function getTeacherSubjects($activeTimeTableLabelId) {
		global $sessionHandler;
		$teacherId = $sessionHandler->getSessionVariable('EmployeeId');

		$query = "
				SELECT
				DISTINCT	a.subjectId,
							d.classId,
							b.subjectCode,
							d.className
				FROM		 ".TIME_TABLE_TABLE." a, subject b, `group` c, class d
				WHERE		a.timeTableLabelId = '$activeTimeTableLabelId'
				AND			a.subjectId = b.subjectId
				AND			a.groupId = c.groupId
				AND			c.classId = d.classId
				AND			a.employeeId = $teacherId";
		 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getTeacherTests($teacherId, $concatStr = '',$orderBy=' d.subjectCode, f.groupShort, testName') {
		$query = "
				SELECT
							d.subjectCode,
							SUBSTRING_INDEX(e.className,'".CLASS_SEPRATOR."',-3) AS className,
							f.groupShort,
							a.testTopic,
							concat(c.testTypeName,'-',a.testIndex) as testName,
							a.maxMarks,
							a.testDate,
							(SELECT MAX(marksScored) as maxMarks from ".TEST_MARKS_TABLE." where testId = a.testId) as maxMarksScored,
							(SELECT MIN(marksScored) from ".TEST_MARKS_TABLE." where isPresent=1 and testId = a.testId) as minMarksScored,
							(SELECT COUNT(studentId) from ".TEST_MARKS_TABLE." where isPresent=1 and testId = a.testId) as presentCount,
							(SELECT COUNT(studentId) from ".TEST_MARKS_TABLE." where isPresent=0 and testId = a.testId) as absentCount,
							(SELECT ROUND(SUM(marksScored)/COUNT(studentId),2) from ".TEST_MARKS_TABLE." where isPresent=1 and testId = a.testId) as avgMarks
				FROM
							".TEST_TABLE." a,
							test_type_category c,
							subject d,
							class e,
							`group` f
				WHERE		a.employeeId = $teacherId
				AND			concat(a.subjectId,'#',a.classId) IN ($concatStr)
				AND			a.testTypeCategoryId = c.testTypeCategoryId
				AND			a.subjectId = d.subjectId
				AND			a.classId = e.classId
				AND			a.groupId = f.groupId
				GROUP BY	a.testId
				ORDER BY	$orderBy

		";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countTopperRecords($concatStrSub) {
		global $sessionHandler;
		$toppers =	$sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT');
		$query = "
				SELECT COUNT(*) AS cnt FROM
				(
					SELECT
								b.studentId, concat(c.firstName,' ', c.lastName) as studentName, c.rollNo, d.className, ROUND(SUM(b.marksScored)*100 / SUM(b.maxMarks),2) as per
					FROM		".TEST_MARKS_TABLE." b, ".TEST_TABLE." a, student c, class d
					WHERE		CONCAT(a.subjectId,'#',a.classId) IN ($concatStrSub)
					AND			a.testId = b.testId
					AND			c.classId = d.classId
					AND			b.studentId = c.studentId
					AND			a.classId = d.classId
					AND			b.isMemberOfClass = 1
					GROUP BY	b.studentId
					ORDER BY	per DESC limit 0,".$toppers."
				) AS t";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getTopperRecords($concatStrSub) {
		global $sessionHandler;
		$toppers =	$sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT');
		$query = "
					SELECT
								b.studentId, c.universityRollNo, c.studentPhoto,concat(c.firstName,' ', c.lastName) as studentName, c.rollNo, SUBSTRING_INDEX(d.className,'".CLASS_SEPRATOR."',-3) AS className, e.subjectCode, ROUND(SUM(b.marksScored)*100 / SUM(b.maxMarks),2) as percentage
					FROM		".TEST_MARKS_TABLE." b, ".TEST_TABLE." a, student c, class d, subject e
					WHERE		CONCAT(a.subjectId,'#',a.classId) IN ($concatStrSub)
					AND			a.testId = b.testId
					AND			c.classId = d.classId
					AND			b.studentId = c.studentId
					AND			a.classId = d.classId
					AND			a.subjectId = e.subjectId
					AND			b.isMemberOfClass = 1
					GROUP BY	b.studentId
					ORDER BY	percentage DESC limit 0,".$toppers."
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countBelowAvgRecords($concatStrSub) {
		global $sessionHandler;
		$belowAvg =	$sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE');
		$query = "
				SELECT COUNT(*) AS cnt FROM
				(
					SELECT
								b.studentId, concat(c.firstName,' ', c.lastName) as studentName, c.rollNo, d.className, ROUND(SUM(b.marksScored)*100 / SUM(b.maxMarks),2) as per
					FROM		".TEST_MARKS_TABLE." b, ".TEST_TABLE." a, student c, class d
					WHERE		CONCAT(a.subjectId,'#',a.classId) IN ($concatStrSub)
					AND			a.testId = b.testId
					AND			c.classId = d.classId
					AND			b.studentId = c.studentId
					AND			a.classId = d.classId
					AND			b.isMemberOfClass = 1
					GROUP BY	b.studentId
					HAVING		per <= $belowAvg ORDER BY per ASC
				) AS t";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getBelowAvgRecords($concatStrSub) {
		global $sessionHandler;
		$belowAvg =	$sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE');
		$query = "
					SELECT
								b.studentId, c.universityRollNo, c.studentPhoto,concat(c.firstName,' ', c.lastName) as studentName, e.subjectCode, c.rollNo, SUBSTRING_INDEX(d.className,'".CLASS_SEPRATOR."',-3) AS className, ROUND(SUM(b.marksScored)*100 / SUM(b.maxMarks),2) as percentage
					FROM		".TEST_MARKS_TABLE." b, ".TEST_TABLE." a, student c, class d, subject e
					WHERE		CONCAT(a.subjectId,'#',a.classId) IN ($concatStrSub)
					AND			a.testId = b.testId
					AND			c.classId = d.classId
					AND			b.studentId = c.studentId
					AND			a.classId = d.classId
					AND			a.subjectId = e.subjectId
					AND			b.isMemberOfClass = 1
					GROUP BY	b.studentId
					HAVING		percentage <= $belowAvg ORDER BY percentage ASC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countAboveAvgRecords($concatStrSub) {
		global $sessionHandler;
		$aboveAvg =	$sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE');
		$query = "
				SELECT COUNT(*) AS cnt FROM
				(
					SELECT
								b.studentId, concat(c.firstName,' ', c.lastName) as studentName, c.rollNo, d.className, ROUND(SUM(b.marksScored)*100 / SUM(b.maxMarks),2) as per
					FROM		".TEST_MARKS_TABLE." b, ".TEST_TABLE." a, student c, class d
					WHERE		CONCAT(a.subjectId,'#',a.classId) IN ($concatStrSub)
					AND			a.testId = b.testId
					AND			c.classId = d.classId
					AND			b.studentId = c.studentId
					AND			a.classId = d.classId
					AND			b.isMemberOfClass = 1
					GROUP BY	b.studentId
					HAVING		per >= $aboveAvg ORDER BY per DESC
				) AS t";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getAboveAvgRecords($concatStrSub) {
		global $sessionHandler;
		$aboveAvg =	$sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE');
		$query = "
					SELECT
								b.studentId, c.universityRollNo, c.studentPhoto,concat(c.firstName,' ', c.lastName) as studentName, e.subjectCode, c.rollNo, d.className, ROUND(SUM(b.marksScored)*100 / SUM(b.maxMarks),2) as percentage
					FROM		".TEST_MARKS_TABLE." b, ".TEST_TABLE." a, student c, class d, subject e
					WHERE		CONCAT(a.subjectId,'#',a.classId) IN ($concatStrSub)
					AND			a.testId = b.testId
					AND			c.classId = d.classId
					AND			b.studentId = c.studentId
					AND			a.classId = d.classId
					AND			a.subjectId = e.subjectId
					AND			b.isMemberOfClass = 1
					GROUP BY	b.studentId
					HAVING		percentage >= $aboveAvg ORDER BY percentage DESC";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


public function getTimeTableLabelType($timeTableLabelId){

    $query="SELECT
                   *
            FROM
                    time_table_labels
            WHERE
                    timeTableLabelId=$timeTableLabelId
            ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}



public function getDashboardLayout(){
    global $sessionHandler;
    $userId=$sessionHandler->getSessionVariable('UserId');
    $query="SELECT
                  IFNULL(dashboardLayout,'') AS dashboardLayout
            FROM
                  user_prefs
            WHERE
                  userId=$userId
            ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


/**********************************THIS FUNCTIONS ARE NEEDED FOR ASSIGNMENT MODULE*****************************************/

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying total nos assignment for teacher.
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (04.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getTotalTeacherAssignment($conditions='') {
        global $sessionHandler;

        $query="SELECT
                      COUNT(*) AS totalRecords
                FROM
                      assignment
                WHERE
                      sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                      AND instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                      AND employeeId=".$sessionHandler->getSessionVariable('EmployeeId')."";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying List assignment for teacher.
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (04.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherAssignmentList($conditions='',$limit='',$orderBy=' aa.assignedOn') {

        global $sessionHandler;
        $query="SELECT
                      count(*) as totalAssignment,
                      aa.assignmentId,
                      aa.topicTitle,
                      aa.topicDescription,
                      aa.assignedOn,
                      aa.tobeSubmittedOn,
                      aa.addedOn,
                      aa.attachmentFile,
                      IF(aa.isVisible=1,'Yes','No') AS isVisible,
                      aa.isVisible AS isVisible2
                FROM
                     `assignment_detail` ad, `assignment` aa
                WHERE
                      ad.assignmentId = aa.assignmentId
                      AND aa.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                      AND aa.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                      AND aa.employeeId=".$sessionHandler->getSessionVariable('EmployeeId')."
                GROUP BY ad.assignmentId
                $conditions
                ORDER BY $orderBy
                $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting comment attachment  in teacher_comment table
// $id :id of the notice
//$fileName: name of the file
// Author :Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function updateTeacherAssignmentFile($id, $fileName) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('assignment',
        array('attachmentFile'),
        array($fileName), "assignmentId=$id" );
    }



//--------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting comments in teacher_assignment table
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (04.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------
    public function addTeacherAssignment($conditions='') {
        global $sessionHandler;
        global $REQUEST_DATA;
        $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $msgBody=add_slashes(htmlentities($REQUEST_DATA['msgSubject']));
        $comments=add_slashes(htmlentities($REQUEST_DATA['msgBody']));
        $assignedDate=add_slashes(htmlentities($REQUEST_DATA['assignedDate']));
        $submissionDate=add_slashes(htmlentities($REQUEST_DATA['submissionDate']));
        $subject=add_slashes(htmlentities($REQUEST_DATA['subject']));
        $group=add_slashes(htmlentities($REQUEST_DATA['group']));
        $class=add_slashes(htmlentities($REQUEST_DATA['classId']));
        $isVisible=add_slashes(htmlentities($REQUEST_DATA['isVisible']));

        $query="INSERT INTO assignment
                    (
                     subjectId,groupId,classId,employeeId,topicTitle,
                     topicDescription,assignedOn,tobeSubmittedOn,
                     addedOn,instituteId,sessionId,isVisible
                    )
                VALUES
                (
                  $subject,$group,$class,$teacherId,'".$msgBody."','".$comments."',
                  '$assignedDate','$submissionDate',now(),$instituteId,
                  $sessionId,$isVisible
                )
         $conditions ";
       SystemDatabaseManager::getInstance()->executeUpdate($query);
       $commentId = SystemDatabaseManager::getInstance()->lastInsertId();
       $sessionHandler->setSessionVariable('IdToFileUpload',$commentId);

        //print_r($_SESSION);
       $student=add_slashes(htmlentities($REQUEST_DATA['student']));
       $studentArr = explode(",", $student);
       $cnt = count($studentArr);

      for($i=0;$i<$cnt; $i++){
           $querySeprator = '';
           if($insertValue!=''){
               $querySeprator = ",";
           }
          $insertValue .= "$querySeprator ($commentId,".$studentArr[$i].",".$subject.")";
      }

        //echo $insertValue;
     $query = "INSERT INTO `assignment_detail`
                      (assignmentId,studentId,subjectId)
                      VALUES ".$insertValue;
        SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//--------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting comments in teacher_assignment table
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (04.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------
    public function updateTeacherAssignment($conditions='') {

        global $sessionHandler;
        global $REQUEST_DATA;

        $teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $msgBody=add_slashes(htmlentities($REQUEST_DATA['msgSubject']));

        $assignmentId=add_slashes(htmlentities($REQUEST_DATA['assignmentId']));
        $comments=add_slashes(htmlentities($REQUEST_DATA['msgBody']));
        $assignedDate=add_slashes(htmlentities($REQUEST_DATA['assignedDate']));
        $submissionDate=add_slashes(htmlentities($REQUEST_DATA['submissionDate']));
        $subject=add_slashes(htmlentities($REQUEST_DATA['subject']));
        $group=add_slashes(htmlentities($REQUEST_DATA['group']));
        $class=add_slashes(htmlentities($REQUEST_DATA['classId']));
        $isVisible=add_slashes(htmlentities($REQUEST_DATA['isVisible']));

        SystemDatabaseManager::getInstance()->runAutoUpdate('assignment',
        array('subjectId','groupId','classId','topicTitle','topicDescription','assignedOn','tobeSubmittedOn','isVisible'),
        array($subject,$group,$class,$msgBody,$comments,$assignedDate,$submissionDate,$isVisible), "assignmentId=$assignmentId" );

        $sessionHandler->setSessionVariable('IdToFileUpload',$assignmentId);

        $query = "DELETE
        FROM `assignment_detail`
        WHERE assignmentId=$assignmentId AND studentRemarks=''";
        SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");

        //print_r($_SESSION);
        $student=add_slashes(htmlentities($REQUEST_DATA['student']));
        $studentArr = explode(",", $student);
        $cnt = count($studentArr);

        for($i=0;$i<$cnt; $i++){
            $querySeprator = '';
            if($insertValue!=''){

                $querySeprator = ",";
            }
            //$studentIdArr = explode("~", $studentArr[$i]);
            $insertValue .= "$querySeprator ($assignmentId,".$studentArr[$i].",".$subject.")";
        }

        //echo $insertValue;

         $query = "INSERT INTO `assignment_detail`
                      (assignmentId,studentId,subjectId)
                      VALUES ".$insertValue;
        SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//-------------------------------------------------------------------------------
// function to fetch teacher assignment detail
// Author : Rajeev Aggarwal
// Created on : 05.02.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
 public function getTeacherDetailAssignment($assignmentId) {

        global $sessionHandler;
        $query = "SELECT
                        a.*,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        s.subjectCode,
                        g.groupShort
                  FROM
                       `assignment` a,
                        class c,
                       `subject` s,
                       `group` g
                  WHERE
                        a.assignmentId  = $assignmentId
                        AND a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND a.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        AND a.classId=c.classId
                        AND a.subjectId=s.subjectId
                        AND a.groupId=g.groupId
                  ";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getStudentAssignmentStatus($conditions, $assignmentId,$group) {

        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $optionalGroup=0;
         if($group!=0){
             //check whether it is optional group or not
             $optGrArray=$this->checkOptionalGroup($group);
             if(count($optGrArray)>0 and is_array($optGrArray)){
                 $optionalGroup=$optGrArray[0]['isOptional'];
             }
             else{
                 $optionalGroup=0;
             }
         }


    if($optionalGroup==0){ //if this group is not optional
     $query= "SELECT
                        DISTINCT s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(c.rollNoSuffix)) , LENGTH( c.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        ad.assignmentId,
                        IF(ad.submittedOn IS NULL,'--',ad.submittedOn) as submitDate,
                        ad.submittedOn,ad.replyAttachmentFile, aa.attachmentFile  
                FROM
                        student s,class c,`group` g,
                        subject_to_class sc,degree deg,branch br,batch ba,student_groups sg,
                        time_table_classes ttc,assignment_detail ad,assignment aa
                WHERE
                        s.studentId=sg.studentId
                        AND sg.classId=c.classId
                        AND sg.groupId=g.groupId
                        AND sc.classId=c.classId
                        AND c.degreeId=deg.degreeId
                        AND c.branchId=br.branchId
                        AND c.batchId=ba.batchId
                        AND c.classId=ttc.classId
                        AND s.studentId=ad.studentId
                        AND aa.assignmentId=ad.assignmentId
                        AND ad.assignmentId=$assignmentId
                        AND aa.sessionId=$sessionId
                        AND aa.instituteId=$instituteId
                        $conditions
                GROUP BY s.studentId
                ORDER BY studentName
             ";
    }
    else{
        $query= "SELECT
                        DISTINCT s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(c.rollNoSuffix)) , LENGTH( c.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        ad.assignmentId,
                        IF(ad.submittedOn IS NULL,'--',ad.submittedOn) as submitDate,
                        ad.submittedOn,ad.replyAttachmentFile, aa.attachmentFile
                FROM
                        student s,class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc,
                        time_table_classes ttc,assignment_detail ad,assignment aa
                WHERE
                        s.studentId=sc.studentId
                        AND sc.classId=c.classId
                        AND sc.groupId=g.groupId
                        AND sc.classId=c.classId
                        AND c.degreeId=deg.degreeId
                        AND c.branchId=br.branchId
                        AND c.batchId=ba.batchId
                        AND c.classId=ttc.classId
                        AND s.studentId=ad.studentId
                        AND aa.assignmentId=ad.assignmentId
                        AND ad.assignmentId=$assignmentId
                        AND aa.sessionId=$sessionId
                        AND aa.instituteId=$instituteId
                        $conditions
             GROUP BY s.studentId
             ORDER BY studentName
            ";

    }

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function checkAssignmentExists($assignmentId){
	$query = "SELECT 	attachmentFile
				FROM	`assignment`
				WHERE	assignmentId = $assignmentId";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
public function updateAttachmentFilenameInAssignment($assignmentId){
	$query ="UPDATE		`assignment`
				SET		attachmentFile=''
				WHERE	assignmentId = $assignmentId";
	 return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function getStudentDetailAssignment($conditions, $assignmentId,$group) {

        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $optionalGroup=0;
         if($group!=0){
             //check whether it is optional group or not
             $optGrArray=$this->checkOptionalGroup($group);
             if(count($optGrArray)>0 and is_array($optGrArray)){
                 $optionalGroup=$optGrArray[0]['isOptional'];
             }
             else{
                 $optionalGroup=0;
             }
         }


    if($optionalGroup==0){ //if this group is not optional
     $query= "SELECT
                        DISTINCT s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(c.rollNoSuffix)) , LENGTH( c.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        ad.assignmentId,
                        IF(ad.submittedOn IS NULL,'-1',ad.submittedOn) as submitDate,
                        ad.submittedOn
                FROM
                        class c,`group` g,
                        subject_to_class sc,degree deg,branch br,batch ba,student_groups sg,
                        time_table_classes ttc, student s
                        LEFT JOIN assignment_detail ad ON (ad.studentId=s.studentId AND ad.assignmentId=$assignmentId)
                WHERE
                        s.studentId=sg.studentId
                        AND sg.classId=c.classId
                        AND sg.groupId=g.groupId
                        AND sc.classId=c.classId
                        AND c.degreeId=deg.degreeId
                        AND c.branchId=br.branchId
                        AND c.batchId=ba.batchId
                        AND c.classId=ttc.classId
                        $conditions
                GROUP BY s.studentId
                ORDER BY studentName
             ";
    }
    else{
        $query= "SELECT
                        DISTINCT s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(c.rollNoSuffix)) , LENGTH( c.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        ad.assignmentId,
                        IF(ad.submittedOn IS NULL,'-1',ad.submittedOn) as submitDate,
                        ad.submittedOn
                FROM
                        class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc,
                        time_table_classes ttc,student s
                        LEFT JOIN assignment_detail ad ON (ad.studentId=s.studentId AND ad.assignmentId=$assignmentId)
                WHERE
                        s.studentId=sc.studentId
                        AND sc.classId=c.classId
                        AND sc.groupId=g.groupId
                        AND sc.classId=c.classId
                        AND c.degreeId=deg.degreeId
                        AND c.branchId=br.branchId
                        AND c.batchId=ba.batchId
                        AND c.classId=ttc.classId
                        AND s.studentId=ad.studentId
                        $conditions
             GROUP BY s.studentId
             ORDER BY studentName
             ";

    }

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



 public function getTimeTableClasses($conditions='') {

        global $sessionHandler;
        $query = "SELECT
                        DISTINCT c.classId,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                  FROM
                        class c,time_table_classes ttc
                  WHERE
                        c.classId=ttc.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        $conditions
                  ORDER BY c.degreeId,c.branchId,c.studyPeriodId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


 public function getTimeTableClassSubject($conditions='') {

        global $sessionHandler;
        $query = "SELECT
                        DISTINCT s.subjectId,
                        s.subjectCode
                  FROM
                        class c,time_table_classes ttc,
                        `subject` s,subject_to_class stc
                  WHERE
                        c.classId=ttc.classId
                        AND c.classId=stc.classId
                        AND stc.subjectId=s.subjectId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        $conditions
                  ORDER BY s.subjectCode
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function getTimeTableClassSubjectGroup($conditions='') {

        global $sessionHandler;
        $query = "SELECT
                        DISTINCT g.groupId,
                        g.groupName AS grpName
                  FROM
                        class c,time_table_classes ttc,
                        `group` g
                  WHERE
                        c.classId=ttc.classId
                        AND c.classId=g.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        $conditions
                  ORDER BY grpName
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function getAssignmentList($conditions='', $limit = '', $orderBy=' s.subjectCode') {

        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                         s.subjectCode,
                         g.groupName,
                         e.employeeName,
                         asg.topicTitle,
                         asg.topicDescription,
                         asg.assignedOn,
                         COUNT(ad.assignmentId) AS totalAssigned
                  FROM
                        `subject` s,`group` g,employee e,
                        class c,time_table_classes ttc,
                        assignment asg,assignment_detail ad
                  WHERE
                        s.subjectId=asg.subjectId
                        AND g.groupId=asg.groupId
                        AND c.classId=asg.classId
                        AND c.classId=ttc.classId
                        AND e.employeeId=asg.employeeId
                        AND asg.assignmentId=ad.assignmentId
                        AND asg.sessionId=$sessionId
                        AND asg.instituteId=$instituteId
                        $conditions
                  GROUP BY ad.assignmentId
                  ORDER BY $orderBy
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


/**********************************THIS FUNCTIONS ARE NEEDED FOR ASSIGNMENT MODULE*****************************************/

public function checkWithAttendance($class,$group,$subject,$period,$forDate){

    $query="SELECT
                   COUNT(*) AS cnt
            FROM
                   ".ATTENDANCE_TABLE." adl
            WHERE
                   adl.attendanceType=2
                   AND adl.fromDate ='$forDate'
                   AND adl.classId IN ( $class )
                   AND adl.subjectId=$subject
                   AND adl.groupId IN ( $group )
                   AND adl.periodId=$period
            ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function checkWithDutyLeave($class,$group,$subject,$period,$forDate){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $query="SELECT
                   COUNT(*) AS cnt
            FROM
                    ".DUTY_LEAVE_TABLE." 
            WHERE
                   dutyDate ='$forDate'
                   AND classId IN ( $class )
                   AND subjectId=$subject
                   AND groupId IN ( $group )
                   AND periodId=$period
                   AND instituteId=$instituteId
                   AND sessionId=$sessionId
                   AND rejected=".DUTY_LEAVE_APPROVE;
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function copyDailyAttance($class,$group,$subject,$period,$forDate,$targetPeriodId){

    $query="INSERT INTO ".ATTENDANCE_TABLE."
                 (
                   classId,groupId,studentId,subjectId,employeeId,
                   attendanceType,attendanceCodeId,periodId,
                   fromDate,toDate,isMemberOfClass,lectureDelivered,
                   lectureAttended,userId,topicsTaughtId
                 )
             SELECT
                    classId,groupId,studentId,subjectId,employeeId,
                    attendanceType,attendanceCodeId,$targetPeriodId,
                    fromDate,toDate,isMemberOfClass,lectureDelivered,
                    lectureAttended,userId,topicsTaughtId
             FROM
                   ".ATTENDANCE_TABLE."
             WHERE
                   classId=$class
                   AND groupId=$group
                   AND subjectId=$subject
                   AND periodId=$period
                   AND fromDate='$forDate'
                   AND attendanceType=2
                  ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

/***********THESE FUNCTIONS ARE USED FOR ATTENDANCE COPYING**********/

/*THESE FUNCTIONS ARE USED FOR DUTY LEAVE UPDATION*/

public function deleteCopyDutyLeave($condition=''){
    
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    
    $query="DELETE FROM  ".DUTY_LEAVE_TABLE."  WHERE $condition ";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function copyDutyLeave($condition='',$targetPeriodId=''){

    $query="INSERT INTO  ".DUTY_LEAVE_TABLE." 
               (eventId, dutyDate, studentId, classId, subjectId, groupId, periodId, 
                achievement, place, rejected, instituteId, sessionId)
            SELECT 
                eventId, dutyDate, studentId, classId, subjectId, groupId, $targetPeriodId, 
                achievement, place, rejected, instituteId, sessionId
            FROM
                 ".DUTY_LEAVE_TABLE." 
            WHERE  
                $condition ";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function updateDutyLeave($studentId,$classId,$periodId,$forDate,$instituteId,$sessionId,$subjectId,$groupId){

    $query="UPDATE
                   ".DUTY_LEAVE_TABLE." 
            SET
                  subjectId=$subjectId,
                  groupId=$groupId
            WHERE
                  studentId=$studentId
                  AND classId=$classId
                  AND periodId=$periodId
                  AND dutyDate='$forDate'
                  AND sessionId=$sessionId
                  AND instituteId=$instituteId
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateDutyLeaveClassWise($classId,$subjectId,$groupId,$dutyDate,$periodId,$sessionId,$instituteId){

    $query="UPDATE
                   ".DUTY_LEAVE_TABLE." 
            SET
                  subjectId=NULL,
                  groupId=NULL
            WHERE
                  classId=$classId
                  AND subjectId=$subjectId
                  AND groupId=$groupId
                  AND dutyDate='$dutyDate'
                  AND periodId=$periodId
                  AND sessionId=$sessionId
                  AND instituteId=$instituteId
            ";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

//------------------------------------------------------------------------------------------------
// This Function is used to check weather the time table has been changed or not
// Created on : 8.03.11
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
public function checkForChangeInTimeTable($classId){
	$query = "SELECT
					distinct 	employeeId
				FROM		 ".TIME_TABLE_TABLE." 
				WHERE		classId = $classId
				AND			fromDate = curdate()
				AND			toDate IS NULL";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//------------------------------------------------------------------------------------------------
// This Function is used to get employee mobile numbers
// Created on : 8.03.11
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
public function getTeachertMobileNumbers($employeeId){
	$query = "
				SELECT
								mobileNumber
					FROM		`employee`
					where		employeeId IN ($employeeId)";

	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


/*THESE FUNCTIONS ARE USED FOR DUTY LEAVE UPDATION*/


}


// $History: TeacherManager.inc.php $
//
//*****************  Version 104  *****************
//User: Dipanjan     Date: 20/04/10   Time: 11:33
//Updated in $/LeapCC/Model/Teacher
//Made changes in "Attendance" and "Test" module in admin end for
//DAILY_TIMETABLE issues
//
//*****************  Version 103  *****************
//User: Dipanjan     Date: 19/04/10   Time: 13:47
//Updated in $/LeapCC/Model/Teacher
//Added "Topics" column in "Attendance History Div"
//
//*****************  Version 102  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Model/Teacher
//Made changes in Teacher module for DAILY_TIMETABLE issues
//
//*****************  Version 101  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Model/Teacher
//Added "Daily Attenance" module in admin end
//
//*****************  Version 100  *****************
//User: Dipanjan     Date: 13/04/10   Time: 17:03
//Updated in $/LeapCC/Model/Teacher
//Done llrit enhancements
//
//*****************  Version 99  *****************
//User: Dipanjan     Date: 12/04/10   Time: 18:58
//Updated in $/LeapCC/Model/Teacher
//Updated "Display Attendance" report
//
//*****************  Version 98  *****************
//User: Dipanjan     Date: 3/11/10    Time: 10:51a
//Updated in $/LeapCC/Model/Teacher
//Modified getTeacherAllOptions() function
//
//*****************  Version 97  *****************
//User: Ajinder      Date: 3/10/10    Time: 3:27p
//Updated in $/LeapCC/Model/Teacher
//done changes, FCSN no.1403
//
//*****************  Version 96  *****************
//User: Dipanjan     Date: 17/02/10   Time: 12:20
//Updated in $/LeapCC/Model/Teacher
//Added the feature :
//Display Teacher Comments:By Default it should show the list of message
//sent by respective employee. after that search filter can be applied
//which are currently mandatory
//
//*****************  Version 95  *****************
//User: Ajinder      Date: 2/12/10    Time: 12:25p
//Updated in $/LeapCC/Model/Teacher
//done changes FCNS No. 1280
//
//*****************  Version 94  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Model/Teacher
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 93  *****************
//User: Dipanjan     Date: 28/01/10   Time: 13:07
//Updated in $/LeapCC/Model/Teacher
//Added "Univ. Roll No." column in "Display Marks" report
//
//*****************  Version 92  *****************
//User: Dipanjan     Date: 28/01/10   Time: 11:31
//Updated in $/LeapCC/Model/Teacher
//Added "Univ. Roll No." column in student list display
//
//*****************  Version 91  *****************
//User: Ajinder      Date: 1/09/10    Time: 12:03p
//Updated in $/LeapCC/Model/Teacher
//added help, changed table names to defines.
//
//*****************  Version 90  *****************
//User: Ajinder      Date: 1/08/10    Time: 3:06p
//Updated in $/LeapCC/Model/Teacher
//file modified to make changes on Teacher Dashboard
//
//*****************  Version 89  *****************
//User: Dipanjan     Date: 29/12/09   Time: 15:58
//Updated in $/LeapCC/Model/Teacher
//Corrected query in test marks module
//
//*****************  Version 88  *****************
//User: Dipanjan     Date: 21/12/09   Time: 15:08
//Updated in $/LeapCC/Model/Teacher
//Changes period display in daily attendance module [ Pediod No (Period
//Slot) ] and changed default sorting logic in test marks module in admin
//and teacher section
//
//*****************  Version 87  *****************
//User: Dipanjan     Date: 17/12/09   Time: 16:38
//Updated in $/LeapCC/Model/Teacher
//Corrected server side checking on "Test Marks" module data entry
//
//*****************  Version 86  *****************
//User: Dipanjan     Date: 17/12/09   Time: 11:01
//Updated in $/LeapCC/Model/Teacher
//Corrected coding in Attendance history display logic
//
//*****************  Version 85  *****************
//User: Dipanjan     Date: 8/12/09    Time: 11:52
//Updated in $/LeapCC/Model/Teacher
//Corrected grace marks query
//
//*****************  Version 84  *****************
//User: Dipanjan     Date: 7/12/09    Time: 16:09
//Updated in $/LeapCC/Model/Teacher
//Modified getSubjectWiseDistribution() function.
//
//*****************  Version 83  *****************
//User: Dipanjan     Date: 3/12/09    Time: 12:46
//Updated in $/LeapCC/Model/Teacher
//Done bug fixing.
//Bug ids---
//0002167,0002168,0002170 to 0002175
//
//*****************  Version 82  *****************
//User: Dipanjan     Date: 2/12/09    Time: 11:08
//Updated in $/LeapCC/Model/Teacher
//Modified "Subject Wise Performance Graph"---Added the option of
//include/exclude grace marks in this report
//
//*****************  Version 81  *****************
//User: Dipanjan     Date: 26/11/09   Time: 17:37
//Updated in $/LeapCC/Model/Teacher
//Done enhancements in "Subject Wise Performance" report
//
//*****************  Version 80  *****************
//User: Dipanjan     Date: 25/11/09   Time: 18:40
//Updated in $/LeapCC/Model/Teacher
//Made enhancements : Teacher can view other teachers attendance and also
//edit & delete them,if they have the same time table allocation
//
//*****************  Version 79  *****************
//User: Dipanjan     Date: 25/11/09   Time: 12:56
//Updated in $/LeapCC/Model/Teacher
//Corrected query for calculating "Subject wise performance" report
//
//*****************  Version 78  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:34
//Updated in $/LeapCC/Model/Teacher
//Created "Subject wise performance" report
//
//*****************  Version 76  *****************
//User: Dipanjan     Date: 21/11/09   Time: 17:50
//Updated in $/LeapCC/Model/Teacher
//Modified getAdjustedSubjectGroup() function to change the sorting logic
//
//*****************  Version 75  *****************
//User: Dipanjan     Date: 21/11/09   Time: 12:55
//Updated in $/LeapCC/Model/Teacher
//Done bug fixing.
//Bug ids :
//0002087 to 0002093
//
//*****************  Version 74  *****************
//User: Dipanjan     Date: 19/11/09   Time: 15:25
//Updated in $/LeapCC/Model/Teacher
//Completed/Modified duty leaves module in teacher end
//
//*****************  Version 73  *****************
//User: Dipanjan     Date: 19/11/09   Time: 10:26
//Updated in $/LeapCC/Model/Teacher
//Modified getSubjectGroupOtherTeacher() function
//
//*****************  Version 72  *****************
//User: Dipanjan     Date: 18/11/09   Time: 10:48
//Updated in $/LeapCC/Model/Teacher
//Done bug fixing for test marks module.Bug : results are duplicated.
//
//*****************  Version 71  *****************
//User: Dipanjan     Date: 16/11/09   Time: 15:43
//Updated in $/LeapCC/Model/Teacher
//Updated logic to fetch periods when loosely coupled attendance is ON
//
//*****************  Version 69  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:07
//Updated in $/LeapCC/Model/Teacher
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
//
//*****************  Version 68  *****************
//User: Dipanjan     Date: 12/11/09   Time: 17:21
//Updated in $/LeapCC/Model/Teacher
//Modified logic in bulk attendance module and corrected flaws in coding
//and removed check which prevents taking attendance across months or
//years.
//
//*****************  Version 67  *****************
//User: Dipanjan     Date: 10/11/09   Time: 13:23
//Updated in $/LeapCC/Model/Teacher
//Modified getSubjectGroupTypes() and getSubjectGroup() function and
//removed the check on active time table labels
//
//*****************  Version 66  *****************
//User: Dipanjan     Date: 4/11/09    Time: 16:11
//Updated in $/LeapCC/Model/Teacher
//Modified "Group Wise Distribution" report
//
//*****************  Version 64  *****************
//User: Dipanjan     Date: 2/11/09    Time: 15:56
//Updated in $/LeapCC/Model/Teacher
//Completed  "Group Wiser Performance Report"
//
//*****************  Version 63  *****************
//User: Dipanjan     Date: 29/10/09   Time: 14:46
//Updated in $/LeapCC/Model/Teacher
//Added Avg. Marks display in "Test marks comparison" report
//
//*****************  Version 61  *****************
//User: Dipanjan     Date: 27/10/09   Time: 15:26
//Updated in $/LeapCC/Model/Teacher
//Added files for "Test Wise Performance Report" module
//
//*****************  Version 59  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Model/Teacher
//Added code for "Time table adjustment"
//
//*****************  Version 58  *****************
//User: Dipanjan     Date: 14/10/09   Time: 18:16
//Updated in $/LeapCC/Model/Teacher
//Made code and logic changes to take care of optional subjects repaled
//problems
//
//*****************  Version 57  *****************
//User: Parveen      Date: 10/09/09   Time: 5:19p
//Updated in $/LeapCC/Model/Teacher
//getTeacherClass function Order by clause added
//
//*****************  Version 56  *****************
//User: Parveen      Date: 10/09/09   Time: 5:14p
//Updated in $/LeapCC/Model/Teacher
//getTeacherSubject function updated
//
//*****************  Version 55  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Model/Teacher
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 54  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Model/Teacher
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 53  *****************
//User: Dipanjan     Date: 6/10/09    Time: 16:27
//Updated in $/LeapCC/Model/Teacher
//Done Bug Fixing.
//Bug ids--
// 0001663: Daily Attendance (Teacher) > Attendance Data is not
//displaying correctly in grid on “Daily Attendance” page.
//
//*****************  Version 52  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Model/Teacher
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 51  *****************
//User: Dipanjan     Date: 7/09/09    Time: 16:38
//Updated in $/LeapCC/Model/Teacher
//Updated editTopicsTaught() function and send employeeId
//
//*****************  Version 50  *****************
//User: Parveen      Date: 9/07/09    Time: 4:07p
//Updated in $/LeapCC/Model/Teacher
//studentEmailSMSRecord function updated (parent receive message checks
//added)
//
//*****************  Version 49  *****************
//User: Dipanjan     Date: 2/09/09    Time: 13:31
//Updated in $/LeapCC/Model/Teacher
//corrected getTeacherSubjectTopic() function
//
//*****************  Version 48  *****************
//User: Dipanjan     Date: 2/09/09    Time: 12:32
//Updated in $/LeapCC/Model/Teacher
//Modified getTeacherSubjectTopic() function so that topics apperar in
//the same order as they are submitted
//
//*****************  Version 47  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Model/Teacher
//Corrected look and feel of teacher module logins
//
//*****************  Version 46  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model/Teacher
//changed queries to add instituteId
//
//*****************  Version 45  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:36
//Updated in $/LeapCC/Model/Teacher
//Done bug fixing.
//bug ids---
//0000971 to 0000976,0000979
//
//*****************  Version 44  *****************
//User: Dipanjan     Date: 13/07/09   Time: 11:59
//Updated in $/LeapCC/Model/Teacher
//Added "Class" column in student display and corrected session changing
//problem
//
//*****************  Version 43  *****************
//User: Dipanjan     Date: 26/06/09   Time: 15:44
//Updated in $/LeapCC/Model/Teacher
//Modified as required by GNIMT as on 26.06.2009
//
//*****************  Version 42  *****************
//User: Dipanjan     Date: 26/06/09   Time: 10:37
//Updated in $/LeapCC/Model/Teacher
//Done GNIMT enhancements as on 26.06.2009
//
//*****************  Version 41  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:51
//Updated in $/LeapCC/Model/Teacher
//Corrected "Overlapping Attendance Problem" in Leap,LeapCC and SNS.
//
//*****************  Version 40  *****************
//User: Administrator Date: 10/06/09   Time: 19:24
//Updated in $/LeapCC/Model/Teacher
//Created "Attendance Summary" and "Test Summary" module in teacher login
//
//*****************  Version 39  *****************
//User: Administrator Date: 8/06/09    Time: 17:58
//Updated in $/LeapCC/Model/Teacher
//Added "lecture delivered" column in attendance summary div in teacher
//login
//
//*****************  Version 38  *****************
//User: Parveen      Date: 6/08/09    Time: 2:57p
//Updated in $/LeapCC/Model/Teacher
//query update StudentList add (studentId, fatherUserId,
//MotherUserId,etc.)
//
//*****************  Version 37  *****************
//User: Administrator Date: 5/06/09    Time: 17:07
//Updated in $/LeapCC/Model/Teacher
//Added "Attendance History" option in teacher module
//
//*****************  Version 36  *****************
//User: Administrator Date: 27/05/09   Time: 12:32
//Updated in $/LeapCC/Model/Teacher
//Added "comments" field in duty leave module in admin & teacher section
//
//*****************  Version 35  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:33p
//Updated in $/LeapCC/Model/Teacher
//Added Feedback Survey reports
//
//*****************  Version 34  *****************
//User: Administrator Date: 20/05/09   Time: 12:09
//Updated in $/LeapCC/Model/Teacher
//Modified getClassWiseGradeList() function
//
//*****************  Version 33  *****************
//User: Dipanjan     Date: 19/05/09   Time: 18:58
//Updated in $/LeapCC/Model/Teacher
//Created "Duty Leave" module
//
//*****************  Version 32  *****************
//User: Dipanjan     Date: 18/05/09   Time: 13:13
//Updated in $/LeapCC/Model/Teacher
//Corrected return statement of "editTopicsTaught()" function
//
//*****************  Version 31  *****************
//User: Dipanjan     Date: 16/05/09   Time: 15:23
//Updated in $/LeapCC/Model/Teacher
//Added "Transaction Support" For Attendance and Marks Modules in
//Leap,LeapCC ans SNS
//
//*****************  Version 30  *****************
//User: Dipanjan     Date: 8/05/09    Time: 11:06
//Updated in $/LeapCC/Model/Teacher
//Corrected query and logic in grace marks modules
//
//*****************  Version 29  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Updated in $/LeapCC/Model/Teacher
//Created "Grace Marks Master"
//
//*****************  Version 28  *****************
//User: Ajinder      Date: 4/14/09    Time: 3:32p
//Updated in $/LeapCC/Model/Teacher
//added function getSubjectGroupOtherTeacher()  to check if tut group is
//taken by any other teacher.
//
//*****************  Version 27  *****************
//User: Dipanjan     Date: 8/04/09    Time: 16:09
//Updated in $/LeapCC/Model/Teacher
//Added class check during group populate
//
//*****************  Version 26  *****************
//User: Dipanjan     Date: 8/04/09    Time: 15:10
//Updated in $/LeapCC/Model/Teacher
//Added server side checks during attendance and marks entry in
//SNS,LeapCC and Leap
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 4/06/09    Time: 12:41p
//Updated in $/LeapCC/Model/Teacher
//modified test marks for test type category
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 4/04/09    Time: 3:25p
//Updated in $/LeapCC/Model/Teacher
//new function getTestTypeCategory() to populate test type category by
//the selection of subject (Theory or practical)

//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 3/04/09    Time: 17:51
//Updated in $/LeapCC/Model/Teacher
//Incorporated new logic for bulk attendance
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 3/31/09    Time: 12:50p
//Updated in $/LeapCC/Model/Teacher
//modified in queries
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 3/30/09    Time: 3:45p
//Updated in $/LeapCC/Model/Teacher
//added function to fetch groupTypeIds
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 3/30/09    Time: 1:09p
//Updated in $/LeapCC/Model/Teacher
//not showing group where parentgroupId!=0
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 3/26/09    Time: 2:19p
//Updated in $/LeapCC/Model/Teacher
//remove the test status field from test table
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 3/26/09    Time: 1:45p
//Updated in $/LeapCC/Model/Teacher
//modified in teachermanager as test type category
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 3/24/09    Time: 6:30p
//Updated in $/LeapCC/Model/Teacher
//modified as per sorting university roll no. Leet & isLett
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 3/21/09    Time: 12:21p
//Updated in $/LeapCC/Model/Teacher
//changes for hostel room and test type category
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Model/Teacher
//modified for test type & put test type category
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 3/16/09    Time: 3:14p
//Updated in $/LeapCC/Model/Teacher
//modified in query check groupid in bulk atttendance & daily attendance
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 3/12/09    Time: 11:49a
//Updated in $/LeapCC/Model/Teacher
//modified the files for topics taught
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 6/03/09    Time: 12:48
//Updated in $/LeapCC/Model/Teacher
//Corrected Query
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 18/12/08   Time: 13:03
//Updated in $/LeapCC/Model/Teacher
//Corrected period.instituteId problem
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 13/12/08   Time: 17:55
//Updated in $/LeapCC/Model/Teacher
//Corrected Database fields
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 13/12/08   Time: 16:48
//Updated in $/LeapCC/Model/Teacher
//Renamed fromDate to visibleFrom and toDate to visibleTo fields in
//admin_messages
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:37
//Updated in $/LeapCC/Model/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:27
//Updated in $/LeapCC/Model/Teacher
//Showing only those admin messages for which login date falls between
//fromDate and toDate
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 11/12/08   Time: 13:42
//Updated in $/LeapCC/Model/Teacher
//Modify "test" queries
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 12/09/08   Time: 3:09p
//Updated in $/LeapCC/Model/Teacher
//Corrected Marks modules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 12/08/08   Time: 4:41p
//Updated in $/LeapCC/Model/Teacher
//Added "SC" enhancements
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:43p
//Updated in $/LeapCC/Model/Teacher
//Corrected Student Tabs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/04/08   Time: 11:21a
//Updated in $/LeapCC/Model/Teacher
//Created "Upload Resource" Module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model/Teacher
//
//*****************  Version 49  *****************
//User: Dipanjan     Date: 10/25/08   Time: 5:12p
//Updated in $/Leap/Source/Model/Teacher
//Added isActive condition in getTeacherTimeTable() function
//
//*****************  Version 48  *****************
//User: Dipanjan     Date: 10/21/08   Time: 4:26p
//Updated in $/Leap/Source/Model/Teacher
//Added functionility for partial roll no search
//
//*****************  Version 47  *****************
//User: Dipanjan     Date: 10/21/08   Time: 12:05p
//Updated in $/Leap/Source/Model/Teacher
//Added alert for time table changes in dashboard
//
//*****************  Version 46  *****************
//User: Dipanjan     Date: 10/21/08   Time: 10:30a
//Updated in $/Leap/Source/Model/Teacher
//Corrected attendance percentage sorting problem
//
//*****************  Version 45  *****************
//User: Arvind       Date: 10/07/08   Time: 12:09p
//Updated in $/Leap/Source/Model/Teacher
//added noticeAttachment field in getNoticeList()
//
//*****************  Version 44  *****************
//User: Dipanjan     Date: 10/03/08   Time: 10:47a
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 43  *****************
//User: Dipanjan     Date: 9/30/08    Time: 12:26p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 42  *****************
//User: Dipanjan     Date: 9/22/08    Time: 2:53p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 41  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 40  *****************
//User: Dipanjan     Date: 9/19/08    Time: 12:53p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 39  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 38  *****************
//User: Dipanjan     Date: 9/17/08    Time: 1:14p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 37  *****************
//User: Dipanjan     Date: 9/16/08    Time: 5:52p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 36  *****************
//User: Dipanjan     Date: 9/16/08    Time: 1:41p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 34  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 33  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 32  *****************
//User: Dipanjan     Date: 9/08/08    Time: 6:06p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 31  *****************
//User: Dipanjan     Date: 9/04/08    Time: 6:03p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 30  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 29  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 27  *****************
//User: Dipanjan     Date: 8/29/08    Time: 7:56p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 26  *****************
//User: Dipanjan     Date: 8/29/08    Time: 6:02p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 25  *****************
//User: Ajinder      Date: 8/29/08    Time: 10:57a
//Updated in $/Leap/Source/Model/Teacher
//changed function getTestMarksList()
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 8/29/08    Time: 10:45a
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 8/28/08    Time: 8:16p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 22  *****************
//User: Ajinder      Date: 8/28/08    Time: 7:45p
//Updated in $/Leap/Source/Model/Teacher
//updated getTestMarksList() function
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 8/28/08    Time: 4:34p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 8/25/08    Time: 7:33p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 8/19/08    Time: 4:48p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 8/18/08    Time: 4:12p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 8/16/08    Time: 4:11p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/11/08    Time: 5:51p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/06/08    Time: 7:27p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/05/08    Time: 7:59p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/02/08    Time: 1:04p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/02/08    Time: 10:07a
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 7/30/08    Time: 12:37p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:55p
//Updated in $/Leap/Source/Model/Teacher
//Make modifications for having  daily and bulk attendance in a single
//table
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/24/08    Time: 11:58a
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/19/08    Time: 6:25p
//Updated in $/Leap/Source/Model/Teacher
//Created sendEmail and sendSMS  to student module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/19/08    Time: 10:34a
//Updated in $/Leap/Source/Model/Teacher
//Created DailyAttendance  Module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/17/08    Time: 7:50p
//Updated in $/Leap/Source/Model/Teacher
//Created BulkAttendance Module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/16/08    Time: 7:11p
//Updated in $/Leap/Source/Model/Teacher
//Created  BulkAttendance Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/15/08    Time: 7:23p
//Updated in $/Leap/Source/Model/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:20p
//Created in $/Leap/Source/Model/Teacher
//Initial Checkin
?>
