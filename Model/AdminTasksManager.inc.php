<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Delete Attendance"
// Author :Dipanjan Bhattacharjee
// Created on : (14.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class AdminTasksManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "AdminTasksManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AdminTasksManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
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

//------------------------------------------------------------
// getStudentMobileNumber() is used to get the student mobile number
//Created on : (16.03.2011)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------
public function getStudentMobileNumber($studentIds){
	$query= "SELECT
						studentMobileNo
				FROM	`student`
				WHERE	studentId IN($studentIds)";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (14.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
   public function getTimeTableClasses($condition='',$orderBy=' ttc.timeTableLabelId') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');

        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $roleName = $sessionHandler->getSessionVariable('RoleName');

        $query = "SELECT
                          DISTINCT
                                    cvtr.classId
                  FROM
                          classes_visible_to_role cvtr
                  WHERE   cvtr.userId = $userId
                          AND cvtr.roleId = $roleId ";

        $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
        $count = count($result);
        $classId = '';
        if($count>0) {
           $classId = " AND cls.classId IN (0";
           for($i=0; $i<count($result); $i++) {
              $classId .= ",".$result[$i]['classId'];
           }
           $classId .= ")";
        }
        
        $query = "SELECT
                         DISTINCT cls.classId,cls.className
                 FROM
                         class cls,  ".TIME_TABLE_TABLE." tt, time_table_classes ttc, time_table_labels ttl
                 WHERE
                         cls.instituteId='".$instituteId."' AND
                         cls.sessionId='".$sessionId."' AND
                         tt.timeTableLabelId = ttl.timeTableLabelId AND
                         tt.classId = ttc.classId AND
                         cls.isActive IN (1,3)  AND
                         cls.classId = ttc.classId AND
                         ttc.timeTableLabelId = ttl.timeTableLabelId
                         $condition $classId
                 ORDER BY degreeId,branchId,studyPeriodId ASC";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

	 public function getMappedTimeTableClasses($timeTableLabelId) {
		global $sessionHandler;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "SELECT a.classId, b.className from time_table_classes a, class b where a.classId = b.classId and a.timeTableLabelId = $timeTableLabelId ORDER BY b.degreeId, b.branchId, b.studyPeriodId ASC";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	 }

//------------------------------------------------------------
//THIS FUNCTION IS USED TO GET CLASSNAME OF A PARTICULAR CLASS
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (30.07.2008)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------
	public function getClassName($classId) {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "
					SELECT
							className
					FROM	class
					WHERE	classId = $classId;";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}


//------------------------------------------------------------
// getMobileNumber() is used to get the mobile numbers of father,mother,guardian against student Ids
//Created on : (16.03.2011)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------

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



//------------------------------------------------------------
//THIS FUNCTION IS USED TO GET CLASSNAME OF A PARTICULAR CLASS
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (30.07.2008)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------
	public function getSubjectCode($subjectId) {
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$query = "
					SELECT
							subjectCode
					FROM	subject
					WHERE	subjectId = $subjectId;";
		return $systemDatabaseManager->executeQuery($query,"Query: $query");
	}






    //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (14.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
   public function getTimeTableClassesWithSubjects($condition='',$orderBy=' ttc.timeTableLabelId') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');

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

        $classId = '';
        if($count>0) {
           $classId = " AND cls.classId IN (0";
           for($i=0; $i<count($result); $i++) {
              $classId .= ",".$result[$i]['classId'];
           }
           $classId .= ")";
        }

        $query = "SELECT
                         DISTINCT cls.classId,cls.className
                 FROM
                         class cls, time_table_classes ttc, time_table_labels ttl
                 WHERE
                         cls.instituteId='".$instituteId."' AND
                         cls.sessionId='".$sessionId."' AND
                         cls.isActive IN (1,3)  AND
                         cls.classId = ttc.classId AND
                         ttc.timeTableLabelId = ttl.timeTableLabelId
                         $condition $classId
                 ORDER BY degreeId,branchId,studyPeriodId ASC";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET A LIST OF SUBJECT CORRESPONDING TO A CLASS
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (30.07.2008)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------

// It checks the value of hasAttendance, hasMarks field for every subject

    public function getClassSubject($orderBy=' sub.subjectCode',$condition='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                         sub.subjectId ,sub.subjectName,sub.subjectCode, sub.hasAttendance, sub.hasMarks
                  FROM
                         subject sub, subject_to_class subTocls, ".TIME_TABLE_TABLE." tt
                  WHERE
                         sub.subjectId=tt.subjectId
                         $condition
                  GROUP BY sub.subjectId
                  ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET A LIST OF Groups based on class
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (06.03.2009)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getClassGroups($orderBy = '',$condition='') {

        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        if($orderBy=='') {
          $orderBy = 'groupName';
        }

        $query = "SELECT
                        DISTINCT g.groupId, g.groupName, g.groupShort
                  FROM
                        `group` g , ".TIME_TABLE_TABLE." tt, class c
                  WHERE
                        c.classId = g.classId AND
                        g.groupId=tt.groupId AND
                        c.instituteId = $instituteId
                        $condition
                  GROUP BY
                        g.groupId
                  ORDER BY
                        $orderBy ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET A LIST OF Attendance
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (06.03.2009)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getAttendanceList($conditions='', $limit = '', $orderBy=' e.employeeName') {
        global $sessionHandler;

        $query = "SELECT
                        e.employeeName,e.employeeId,sub.subjectId,c.classId,
                        g.groupId,g.groupName,g.groupShort,att.fromDate,att.toDate,att.attendanceType,
                        IF(p.periodNumber IS NULL OR p.periodNumber='','---',p.periodNumber) as periodNumber,
                        IF(p.periodId IS NULL OR p.periodId='','-1',p.periodId) as periodId
                  FROM
                        `group` g ,employee e,`subject` sub,class c,time_table_classes ttc,
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
                        $conditions
                        GROUP BY att.fromDate,att.employeeId,att.groupId,att.periodId,att.classId
                        ORDER BY $orderBy
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET A LIST OF Attendance
//orderBy: on which column to sort
//Author :Dipanjan Bhattacharjee
//Created on : (06.03.2009)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
    public function getTotalAttendance($conditions='') {
        global $sessionHandler;

        $query = "SELECT
                        att.attendanceId
                  FROM
                        `group` g ,employee e,".ATTENDANCE_TABLE." att,`subject` sub,class c,time_table_classes ttc
                  WHERE
                        att.groupId=g.groupId
                        AND att.classId=c.classId
                        AND att.subjectId=sub.subjectId
                        AND att.employeeId=e.employeeId
                        AND att.classId = ttc.classId
                        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                        $conditions
                        GROUP BY att.fromDate,att.employeeId,att.groupId,att.periodId,att.classId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------------------------
//To qurantine attendance from main attendance table and insert into quarantine table
//Author :Dipanjan Bhattacharjee
//Created on : (06.03.2009)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------
  public function qurantineAttendance($str){
      global $sessionHandler;
      $userId=$sessionHandler->getSessionVariable('UserId');
      $deletionType=3;
      $req=explode('~',$str);
      if($req[7]!=-1){ //for daily attendance
      $insQuery="INSERT INTO ".QUARANTINE_ATTENDANCE_TABLE."
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
              CONCAT(employeeId,'~',classId,'~',subjectId,'~',groupId,'~',fromDate,'~',toDate,'~',attendanceType,'~',periodId)
              IN ('".$str."')";
      }
      else{ //for bulk attendance(omitting periodId)
          $sstr='';
          for($i=0;$i<7;$i++){
              if($sstr!=''){
                  $sstr .='~';
              }
              $sstr .=$req[$i];
          }
       $insQuery="INSERT INTO ".QUARANTINE_ATTENDANCE_TABLE."
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
              CONCAT(employeeId,'~',classId,'~',subjectId,'~',groupId,'~',fromDate,'~',toDate,'~',attendanceType)
              IN ('".$sstr."')";

     }

      //insert into quarantine table
      return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($insQuery);

  }

//----------------------------------------------------------------------------------------------
//To delete attendance from main attendance table
//Author :Dipanjan Bhattacharjee
//Created on : (06.03.2009)
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------
 public function deleteAttendance($str){
    $req=explode('~',$str);
    if($req[7]!=-1){ //for daily attendance
    $delQuery="DELETE
                   FROM
                         ".ATTENDANCE_TABLE."
                   WHERE
                         CONCAT(employeeId,'~',classId,'~',subjectId,'~',groupId,'~',fromDate,'~',toDate,'~',attendanceType,'~',periodId)
                         IN ('".$str."')";
    }
    else{ //for bulk attendance(omitting periodId)
          $sstr='';
          for($i=0;$i<7;$i++){
              if($sstr!=''){
                  $sstr .='~';
              }
              $sstr .=$req[$i];
          }

     $delQuery="DELETE
                   FROM
                         ".ATTENDANCE_TABLE."
                   WHERE
                         CONCAT(employeeId,'~',classId,'~',subjectId,'~',groupId,'~',fromDate,'~',toDate,'~',attendanceType)
                         IN ('".$sstr."')";
    }

   //delete from main attendance table
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($delQuery);
  }

//*************************FUNCTIONS USED FOR DUTY LEAVES MODULE*****************************


public function updateDutyLeave($classId,$subjectId,$groupId,$dutyDate,$periodId,$sessionId,$instituteId){

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

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of class of a teacher
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherList($conditions='') {

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
			 $query = "	SELECT
							DISTINCT(emp.employeeId),
							CONCAT(IFNULL(emp.employeeName,''),' (',IFNULL(emp.employeeCode,''),')') AS employeeName,
							emp.employeeCode
					FROM	classes_visible_to_role cvtr,
							 ".TIME_TABLE_TABLE." tt,
							`group` gr,
							`employee` emp
					LEFT JOIN employee_can_teach_in ec ON emp.employeeId = ec.employeeId
					WHERE	cvtr.groupId = tt.groupId
					AND		tt.employeeId = emp.employeeId
					AND		(emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					OR		ec.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
					AND		gr.classId = cvtr.classId
					AND		gr.groupId = cvtr.groupId
					AND		tt.toDate IS NULL
					AND		gr.classId IN ($insertValue)
					AND		emp.isTeaching = 1
					AND		emp.isActive=1
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
							ORDER BY employeeName";
		}
		else {
        $query = "SELECT
                         DISTINCT  e.employeeId,
                           CONCAT(IFNULL(e.employeeName,''),' (',IFNULL(e.employeeCode,''),')') AS employeeName
                  FROM
                         ".TIME_TABLE_TABLE."  t , employee e, time_table_labels ttl
                  WHERE
                         t.employeeId=e.employeeId
                         AND t.timeTableLabelId=ttl.timeTableLabelId
                         AND e.isTeaching=1
                         AND e.isActive=1

                         $conditions
                  ORDER BY employeeName";
		}
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
    public function getAllTeacherClass($conditions='') {

        global $sessionHandler;
        global $REQUEST_DATA;
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
        //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        //$teacherId=$REQUEST_DATA['employeeId'];
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
			$query = "	SELECT
								c.classId,
								SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
						FROM	 ".TIME_TABLE_TABLE."  t,
								`group` g,
								`class` c,
								classes_visible_to_role cvtr
						WHERE	t.groupId=g.groupId
						AND		g.classId=c.classId
						AND		cvtr.classId = g.classId
						AND		cvtr.groupId = g.groupId
						AND		cvtr.classId = c.classId
						AND		cvtr.groupId = t.groupId
						AND		c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
						AND		t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
						AND		t.toDate IS NULL
						AND		c.isActive=1
						AND		cvtr.userId = $userId
						AND		cvtr.roleId = $roleId
						AND		c.classId IN ($insertValue)
								$conditions GROUP BY c.classId";
		}
		else {

			$query = "SELECT c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
			FROM  ".TIME_TABLE_TABLE."  t,`group` g,`class` c
			WHERE t.groupId=g.groupId AND g.classId=c.classId
			AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
			AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
			AND t.toDate IS NULL
			AND c.isActive=1
			$conditions GROUP BY c.classId";
    }

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

// It checks the value of hasAttendance, hasMarks field for every subject
    public function getAllTeacherSubject($conditions='') {

        global $sessionHandler;
        global $REQUEST_DATA;
        //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        //$teacherId=$REQUEST_DATA['employeeId'];
        $teacherId=$REQUEST_DATA['employeeId'];
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
		 $query = "	SELECT
							s.subjectId,
							s.subjectName,
							s.subjectCode,
							s.hasAttendance,
							s.hasMarks
					FROM	 ".TIME_TABLE_TABLE." t,
							`group` g,
							subject s,
							classes_visible_to_role cvtr,
							class c
					WHERE	t.groupId=g.groupId
					AND		g.classId=c.classId
					AND		t.subjectId=s.subjectId
					AND		cvtr.groupId = g.groupId
					AND		cvtr.classId = g.classId
					AND		cvtr.groupId = t.groupId
					AND		cvtr.classId = c.classId
					AND		c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND		t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND		t.toDate IS NULL
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
							$conditions GROUP BY t.subjectId
							ORDER BY s.subjectCode";
		}
		else {
        $query = "SELECT s.subjectId,s.subjectName,s.subjectCode, s.hasAttendance, s.hasMarks
        FROM  ".TIME_TABLE_TABLE." t,`group` g,subject s,class c
        WHERE t.groupId=g.groupId AND g.classId=c.classId AND t.subjectId=s.subjectId
        AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
        AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
        AND t.toDate IS NULL
        $conditions GROUP BY t.subjectId
        ORDER BY s.subjectCode";
		}
       //echo $query;
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
        global $REQUEST_DATA;
        //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
		$teacherId=$REQUEST_DATA['employeeId'];
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

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
		  $query = "	SELECT
							c.classId,
							SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
					FROM	 ".TIME_TABLE_TABLE."  t,
							classes_visible_to_role cvtr,
							`group` g,
							`class` c
					WHERE	t.groupId=g.groupId
					AND		g.classId=c.classId
					AND		cvtr.groupId = t.groupId
					AND		cvtr.classId = g.classId
					AND		cvtr.groupId = g.groupId
					AND		cvtr.classId = c.classId
					AND		t.employeeId=$teacherId
					AND		c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND		t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND		t.toDate IS NULL
					AND		c.classId IN ($insertValue)
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
					AND		c.isActive=1
							$conditions GROUP BY c.classId";
		}
		else {
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
		}
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getTeacherAdjustedClass($startDate,$endDate,$timeTableConditions='') {

        global $sessionHandler;
        global $REQUEST_DATA;
        //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $teacherId=trim($REQUEST_DATA['employeeId']);
        $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
        if($teacherId==''){
             $teacherId=-1;
        }
        if($timeTableLabelId==''){
             $timeTableLabelId=-1;
        }
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

        $query = "    SELECT
                            distinct cvtr.classId
                    FROM    classes_visible_to_role cvtr
                    WHERE    cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId";

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
          $query = "    SELECT
                            c.classId,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                    FROM     ".TIME_TABLE_TABLE."  t,
                            classes_visible_to_role cvtr,
                            `group` g,
                            `class` c
                    WHERE    t.groupId=g.groupId
                    AND        g.classId=c.classId
                    AND        cvtr.groupId = t.groupId
                    AND        cvtr.classId = g.classId
                    AND        cvtr.groupId = g.groupId
                    AND        cvtr.classId = c.classId
                    AND        t.employeeId=$teacherId
                    $timeTableConditions
                    AND        c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND        t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND        t.toDate IS NULL
                    AND        c.classId IN ($insertValue)
                    AND        cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId
                    AND        c.isActive IN (1,3)
                            $conditions GROUP BY c.classId";
        }
        else {
        $query = "SELECT
                        c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                  FROM
                         ".TIME_TABLE_TABLE."  t,`group` g,`class` c
                  WHERE
                         t.groupId=g.groupId AND g.classId=c.classId AND t.employeeId=$teacherId
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND t.toDate IS NULL
                         AND c.isActive IN (1,3)
                         AND t.timeTableLabelId=$timeTableLabelId
                         $timeTableConditions
                         $conditions
                         GROUP BY c.classId
                  ";
        }
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


public function getDutyLeaveClasses($employeeId,$timeTableLabelId,$startDate,$endDate) {

        global $sessionHandler;
        if($employeeId!=0){
             $teacherCondition1= ' AND t.employeeId='.$employeeId;
             $teacherCondition2= ' AND tt.employeeId='.$employeeId;
        }
        if($timeTableLabelId==''){
             $timeTableLabelId=-1;
        }
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

        $query = "    SELECT
                            distinct cvtr.classId
                    FROM    classes_visible_to_role cvtr
                    WHERE    cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId";

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
          $query = "    SELECT
                            c.classId,
                            SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                    FROM     ".TIME_TABLE_TABLE."  t,
                            classes_visible_to_role cvtr,
                            `group` g,
                            `class` c
                    WHERE    t.groupId=g.groupId
                    AND        g.classId=c.classId
                    AND        cvtr.groupId = t.groupId
                    AND        cvtr.classId = g.classId
                    AND        cvtr.groupId = g.groupId
                    AND        cvtr.classId = c.classId
                    $teacherCondition1
                    AND        c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND        t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND        t.toDate IS NULL
                    AND        c.classId IN ($insertValue)
                    AND        cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId
                    AND        c.isActive IN (1,3)
                            $conditions GROUP BY c.classId";
        }
        else {
        $query = "SELECT
                        c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        c.studyPeriodId
                  FROM
                         ".TIME_TABLE_TABLE."  t,`group` g,`class` c
                  WHERE
                         t.groupId=g.groupId AND g.classId=c.classId
                         $teacherCondition1
                         AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                         AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                         AND t.toDate IS NULL
                         AND c.isActive IN (1,3)
                         AND t.timeTableLabelId=$timeTableLabelId
                         AND t.timeTableId NOT IN
                              (
                                SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      $teacherCondition2
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
                                      AND tt.timeTableLabelId=$timeTableLabelId
                              )
                         $conditions
                         GROUP BY c.classId
                  UNION
                        SELECT
                           c.classId,SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                           c.studyPeriodId
                        FROM
                           `time_table_adjustment` t,`group` g,`class` c
                        WHERE
                           t.groupId=g.groupId AND g.classId=c.classId
                           $teacherCondition1
                           AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                           AND t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                           AND c.isActive IN (1,3)
                           AND t.isActive=1
                           ANd t.timeTableLabelId=$timeTableLabelId
                           AND (
                                  (t.fromDate BETWEEN '$startDate' AND '$endDate')
                                   OR
                                  (t.toDate BETWEEN '$startDate' AND '$endDate')
                                    OR
                                  (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                               )
                           $teacherCondition1
                           $conditions
                           GROUP BY c.classId
                   ORDER BY studyPeriodId
                  ";
        }
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
        global $REQUEST_DATA;
        //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $teacherId=$REQUEST_DATA['employeeId'];
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

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
		$query = "	SELECT
							s.subjectId,
							s.subjectName,
							s.subjectCode,
							s.hasAttendance,
							s.hasMarks
					FROM	 ".TIME_TABLE_TABLE." t,
							classes_visible_to_role cvtr,
							`group` g,
							subject s,
							class c
					WHERE	t.groupId=g.groupId
					AND		g.classId=c.classId
					AND		t.subjectId=s.subjectId
					AND		cvtr.groupId = g.groupId
					AND		cvtr.classId = g.classId
					AND		cvtr.groupId = t.groupId
					AND		cvtr.classId = c.classId
					AND		c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
					AND		t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND		t.toDate IS NULL
					AND		t.employeeId=$teacherId
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
							$conditions GROUP BY t.subjectId ";
		}
		else {
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
                                      AND tt.employeeId=$teacherId
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
                            AND t.employeeId=$teacherId
                            AND t.isActive=1
                            AND '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions
                            GROUP BY t.subjectId
                  ";
		}
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getTeacherAdjustedSubject($conditions='',$startDate,$endDate,$timeTableConditions='') {

        global $sessionHandler;
        global $REQUEST_DATA;
        //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
        $teacherId=trim($REQUEST_DATA['employeeId']);
        $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
        if($teacherId==''){
             $teacherId=-1;
        }
        if($timeTableLabelId==''){
             $timeTableLabelId=-1;
        }
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

        $query = "    SELECT
                            distinct cvtr.classId
                    FROM    classes_visible_to_role cvtr
                    WHERE    cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId";

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
        $query = "    SELECT
                            s.subjectId,
                            s.subjectName,
                            s.subjectCode,
                            s.hasAttendance,
                            s.hasMarks
                    FROM     ".TIME_TABLE_TABLE." t,
                            classes_visible_to_role cvtr,
                            `group` g,
                            subject s,
                            class c
                    WHERE    t.groupId=g.groupId
                    AND        g.classId=c.classId
                    AND        t.subjectId=s.subjectId
                    AND        cvtr.groupId = g.groupId
                    AND        cvtr.classId = g.classId
                    AND        cvtr.groupId = t.groupId
                    AND        cvtr.classId = c.classId
                    AND        c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND        t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND        t.toDate IS NULL
                    AND        t.employeeId=$teacherId
                    $timeTableConditions
                    AND        cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId
                            $conditions GROUP BY t.subjectId ";
        }
        else {
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
                            AND t.employeeId=$teacherId
                            AND t.timeTableLabelId=$timeTableLabelId
                            $timeTableConditions
                             $conditions
                             GROUP BY t.subjectId
                  ";
        }
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getDutyLeaveSubjects($employeeId,$classId,$timeTableLabelId,$startDate,$endDate) {

        global $sessionHandler;

        if($employeeId!=0){
            $teacherCondition1=' AND t.employeeId='.$employeeId;
            $teacherCondition2=' AND tt.employeeId='.$employeeId;
        }
        if($classId!=0){
            $classCondition=' AND c.classId='.$classId;
        }
        if($timeTableLabelId==''){
             $timeTableLabelId=-1;
        }
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

        $query = "    SELECT
                            distinct cvtr.classId
                    FROM    classes_visible_to_role cvtr
                    WHERE    cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId";

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
         $query = "    SELECT
                            s.subjectId,
                            s.subjectName,
                            s.subjectCode,
                            s.hasAttendance,
                            s.hasMarks
                    FROM     ".TIME_TABLE_TABLE." t,
                            classes_visible_to_role cvtr,
                            `group` g,
                            subject s,
                            class c
                    WHERE    t.groupId=g.groupId
                    AND        g.classId=c.classId
                    AND        t.subjectId=s.subjectId
                    AND        cvtr.groupId = g.groupId
                    AND        cvtr.classId = g.classId
                    AND        cvtr.groupId = t.groupId
                    AND        cvtr.classId = c.classId
                    AND        c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND        t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND        t.toDate IS NULL
                    $teacherCondition1
                    AND        cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId
                    $classCondition
                    GROUP BY t.subjectId ";
        }
        else {
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
                            $teacherCondition1
                            $classCondition
                            AND t.timeTableLabelId=$timeTableLabelId
                            AND t.timeTableId NOT IN
                             (
                                SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      $teacherCondition2
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
                                      AND tt.timeTableLabelId=$timeTableLabelId
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
                            $teacherCondition1
                            $classCondition
                            AND t.timeTableLabelId=$timeTableLabelId
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
                            GROUP BY t.subjectId
                  ORDER BY LENGTH(subjectName)+0,subjectName
                  ";
        }
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getSubjectGroup($subjectId,$classId, $conditions = '',$timeTableConditions='') {

        global $sessionHandler;
        global $REQUEST_DATA;
         //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
		$teacherId=$REQUEST_DATA['employeeId'];
        $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
        if($timeTableLabelId!=''){
            $timeTableCondition2=' AND ttl.timeTableLabelId='.$timeTableLabelId;
        }
		$systemDatabaseManager = SystemDatabaseManager::getInstance();
		$userId= $sessionHandler->getSessionVariable('UserId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

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
		$query = "    SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
							classes_visible_to_role cvtr,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE   t.groupId=g.groupId
                    AND     g.classId=c.classId
					AND		cvtr.groupId = g.groupId
					AND		cvtr.classId = g.classId
					AND		cvtr.groupId = t.groupId
					AND		cvtr.classId = c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    $timeTableCondition2
                    $timeTableConditions
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId = $subjectId
                    AND     c.classId=$classId
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
                            $conditions
                            GROUP BY t.groupId ";
		}
		else {
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
                    $timeTableCondition2
                    $timeTableConditions
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
                    $timeTableCondition2
                    $timeTableConditions
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId = $subjectId
                    AND     c.classId=$classId
                    AND     t.isActive=1
                    AND     '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions
                            GROUP BY t.groupId";;
		}
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getSubjectGroupOtherTeacher($subjectId, $classId, $conditions = '') {
         global $sessionHandler;
         global $REQUEST_DATA;
         $teacherId=$REQUEST_DATA['employeeId'];
         $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
         if($timeTableLabelId!=''){
             $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
         }
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
                            $timeTableCondition
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
                                $timeTableCondition
                                AND  t.employeeId != $teacherId
                                AND  t.subjectId = $subjectId
                                AND  t.isActive=1
                                AND  '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                                     $conditions
                                GROUP BY t.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getSubjectGroupTypes($subjectId,$classId) {

         global $sessionHandler;
         global $REQUEST_DATA;

         $teacherId=$REQUEST_DATA['employeeId'];
         $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
         if($timeTableLabelId!=''){
             $timeTableCondition=' AND ttl.timeTableLabelId='.$timeTableLabelId;
         }
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
                    $timeTableCondition
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
                            $timeTableCondition
                            AND t.employeeId=$teacherId
                            AND t.subjectId = $subjectId
                            AND c.classId=$classId
                            AND t.isActive=1
                            AND '".$loginDate."' BETWEEN t.fromDate AND t.toDate
                            $conditions
                            GROUP BY t.groupId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getAdjustedSubjectGroup($subjectId,$classId, $startDate,$endDate,$conditions = '') {

         global $sessionHandler;
         global $REQUEST_DATA;
         //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         $teacherId=trim($REQUEST_DATA['employeeId']);
         $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
         if($teacherId==''){
             $teacherId=-1;
         }
         if($timeTableLabelId==''){
             $timeTableLabelId=-1;
         }
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

        $query = "    SELECT
                            distinct cvtr.classId
                    FROM    classes_visible_to_role cvtr
                    WHERE    cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId";

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
        $query = "    SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            classes_visible_to_role cvtr,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE   t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND        cvtr.groupId = g.groupId
                    AND        cvtr.classId = g.classId
                    AND        cvtr.groupId = t.groupId
                    AND        cvtr.classId = c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     ttl.isActive = 1
                    AND     t.employeeId=$teacherId
                    AND     t.subjectId IN ( $subjectId )
                    AND     c.classId IN ( $classId )
                    AND     cvtr.userId = $userId
                    AND     cvtr.roleId = $roleId
                            $conditions
                            GROUP BY t.groupId ";
        }
        else {
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

                    AND     t.employeeId=$teacherId
                    AND     t.subjectId IN ( $subjectId )
                    AND     c.classId IN ( $classId )
                    AND     ttl.timeTableLabelId=$timeTableLabelId
                            $conditions
                            GROUP BY t.groupId
				";
        }
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//to get group list irrespective of teachers
public function getAllSubjectGroup($subjectId,$classId, $conditions = '') {

          global $sessionHandler;
          global $REQUEST_DATA;
         //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         //$teacherId=$REQUEST_DATA['employeeId'];
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
		$query = "    SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
							classes_visible_to_role cvtr,
                            time_table_labels ttl
                    WHERE
                            t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					AND		cvtr.groupId = g.groupId
					AND		cvtr.classId = g.classId
					AND		cvtr.groupId = t.groupId
					AND		cvtr.classId = c.classId
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     ttl.isActive = 1
                    AND     t.subjectId = $subjectId
                    AND     c.classId=$classId
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
                            $conditions
                            GROUP BY t.groupId ";
		}
		else {
        $query = "    SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE
                               t.groupId=g.groupId
                    AND        g.classId=c.classId
                    AND        c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND        t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND        t.toDate IS NULL
                    AND        t.timeTableLabelId = ttl.timeTableLabelId
                    AND        ttl.isActive = 1
                    AND        t.subjectId = $subjectId
                    AND     c.classId=$classId
                            $conditions
                            GROUP BY t.groupId ";
		}
		/*echo $query;
		die;*/
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//to get group list irrespective of teachers
public function getAllSubjectGroups($subjectId,$classId, $conditions = '') {

          global $sessionHandler;
          global $REQUEST_DATA;
         //$teacherId=$sessionHandler->getSessionVariable('EmployeeId');
         //$teacherId=$REQUEST_DATA['employeeId'];
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
		$query = "  SELECT
								distinct g.groupId, g.groupName
					FROM		 ".TIME_TABLE_TABLE." t,`group` g, classes_visible_to_role cvtr
                    WHERE		t.groupId=g.groupId
                    AND			t.subjectId = $subjectId
                    AND			t.classId = $classId
					AND			t.classId = g.classId
					AND			cvtr.groupId = g.groupId
					AND			cvtr.classId = g.classId
					AND			cvtr.userId = $userId
					AND			cvtr.roleId = $roleId";

		}
		else {
        $query = "  SELECT
								distinct g.groupId, g.groupName
                    FROM		 ".TIME_TABLE_TABLE." t,`group` g
                    WHERE		t.groupId=g.groupId
                    AND			t.subjectId = $subjectId
                    AND			t.classId = $classId
					AND			t.classId = g.classId";
		}
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


public function getDutyLeaveGroups($employeeId,$classId,$subjectId,$timeTableLabelId,$startDate,$endDate) {

         global $sessionHandler;
         global $REQUEST_DATA;

         if($employeeId!=0){
             $teacherCondition1 =' AND t.employeeId='.$employeeId;
             $teacherCondition2 =' AND tt.employeeId='.$employeeId;
         }
         if($classId!=0){
            $classCondition=' AND c.classId='.$classId;
         }
         if($subjectId!=0){
            $subjectCondition=' AND t.subjectId='.$subjectId;
         }
         if($timeTableLabelId==''){
             $timeTableLabelId=-1;
         }
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $loginDate=date('Y-m-d');

        $query = "    SELECT
                            distinct cvtr.classId
                    FROM    classes_visible_to_role cvtr
                    WHERE    cvtr.userId = $userId
                    AND        cvtr.roleId = $roleId";

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
        $query = "  SELECT
                            g.groupId,
                            g.groupName
                    FROM     ".TIME_TABLE_TABLE." t,
                            classes_visible_to_role cvtr,
                            `group` g,
                            class c,
                            time_table_labels ttl
                    WHERE   t.groupId=g.groupId
                    AND     g.classId=c.classId
                    AND     cvtr.groupId = g.groupId
                    AND     cvtr.classId = g.classId
                    AND     cvtr.groupId = t.groupId
                    AND     cvtr.classId = c.classId
                    AND     c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                    AND     t.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    AND     t.toDate IS NULL
                    AND     t.timeTableLabelId = ttl.timeTableLabelId
                    AND     ttl.isActive = 1
                    $teacherCondition1
                    $subjectCondition
                    $classCondition
                    AND     cvtr.userId = $userId
                    AND     cvtr.roleId = $roleId
                    $conditions
                    GROUP BY t.groupId ";
        }
        else {
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

                    $teacherCondition1
                    $subjectCondition
                    $classCondition
                    AND     ttl.timeTableLabelId=$timeTableLabelId
                    AND     t.timeTableId NOT IN
                              (
                                SELECT
                                      t.timeTableId
                                FROM
                                      time_table_adjustment t,
                                       ".TIME_TABLE_TABLE." tt
                                WHERE
                                      tt.timeTableId=t.timeTableId
                                      $teacherCondition2
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
                                      ANd t.timeTableLabelId=$timeTableLabelId
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

                    $teacherCondition1
                    $subjectCondition
                    $classCondition
                    AND t.timeTableLabelId=$timeTableLabelId
                    AND (
                            (t.fromDate BETWEEN '$startDate' AND '$endDate')
                             OR
                            (t.toDate BETWEEN '$startDate' AND '$endDate')
                             OR
                            (t.fromDate <= '$startDate' AND t.toDate>= '$endDate')
                         )
                    AND t.isActive=1
                    $conditions
                    GROUP BY t.groupId
                ORDER BY groupName
                ";
        }
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//populate student duty lists
public function getStudentDutyLeaveList($conditions = '',$limit='',$orderBy=' rollNo') {
  global $sessionHandler;
  $sessionId=$sessionHandler->getSessionVariable('SessionId');
  $instituteId=$sessionHandler->getSessionVariable('InstituteId');

      $query= "SELECT
                        DISTINCT s.studentId,
                        CONCAT(s.firstName,' ' ,s.lastName) as studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.regNo IS NULL OR s.regNo='','".NOT_APPLICABLE_STRING."',s.regNo) AS regNo,
                        ttc.timeTableLabelId,
                        c.classId,
                        sc.subjectId,
                        g.groupId,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className,
                        g.groupName,
                        su.subjectCode,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        (SELECT
                              IFNULL(SUM(leavesTaken),0) AS
                         FROM
                               ".DUTY_LEAVE_TABLE."  dl
                         WHERE
                              dl.studentId = s.studentId
                              AND dl.classId=c.classId
                              AND dl.subjectId=sc.subjectId
                              AND dl.groupId=g.groupId
                              AND dl.rejected   = ".DUTY_LEAVE_APPROVE.") AS leavesTaken,
                        (SELECT
                                comments
                         FROM
                                attendance_leave
                         WHERE
                               studentId = s.studentId
                               AND classId=c.classId
                               AND subjectId=sc.subjectId
                               AND groupId=g.groupId
                               AND timeTableLabelId=ttc.timeTableLabelId
                        ) AS comments,
                        IF(
                            (SELECT
                                   DISTINCT  s.studentId
                              FROM
                                   ".DUTY_LEAVE_TABLE."  dl
                              WHERE
                                  dl.studentId = s.studentId
                                  AND dl.classId=c.classId
                                  AND dl.subjectId=sc.subjectId
                                  AND dl.groupId=g.groupId
                                  AND dl.rejected   = ".DUTY_LEAVE_APPROVE."
                            ) IS NULL,-1,s.studentId
                          )
                         AS derivedStudentId,
                       (
                        SELECT
                              SUM(lectureDelivered)
                        FROM
                              ".ATTENDANCE_TABLE."
                        WHERE
                              classId=c.classId
                              AND  subjectId=sc.subjectId
                              AND  studentId = s.studentId
                              AND  groupId=g.groupId
                       ) AS delivered,
                      (
                        SELECT
                              SUM(IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )  AS attended
                        FROM
                              ".ATTENDANCE_TABLE." att
                        LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=$instituteId)
                        WHERE
                             classId=c.classId
                             AND  subjectId=sc.subjectId
                             AND  studentId = s.studentId
                             AND  groupId=g.groupId
                      ) AS attended
                FROM
                   class c,`group` g,subject_to_class sc,student_groups sg,
                   student s,time_table_classes ttc , ".TIME_TABLE_TABLE." t,`subject` su
                WHERE
                  s.studentId=sg.studentId
                  AND sg.classId=c.classId

                  AND sg.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND sc.subjectId=su.subjectId
                  AND c.classId=ttc.classId
                  AND t.groupId=g.groupId
                  AND t.subjectId=sc.subjectId
                  AND t.timeTableLabelId=ttc.timeTableLabelId
                  AND t.toDate IS NULL
                  AND c.instituteId = $instituteId
                  AND c.sessionId   = $sessionId
                  $conditions
                  ORDER BY $orderBy
                  $limit
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getTotalStudentDutyLeave($conditions = '') {
        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query ="SELECT
                           COUNT(DISTINCT s.studentId) AS totalRecords
                 FROM
                   class c,`group` g,subject_to_class sc,student_groups sg,
                   student s,time_table_classes ttc, ".TIME_TABLE_TABLE." t,`subject` su
                 WHERE
                  s.studentId=sg.studentId
                  AND sg.classId=c.classId
                  AND s.classId = c.classId
                  AND sg.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND sc.subjectId=su.subjectId
                  AND c.classId=ttc.classId
                  AND t.groupId=g.groupId
                  AND t.subjectId=sc.subjectId
                  AND t.timeTableLabelId=ttc.timeTableLabelId
                  AND t.toDate IS NULL
                  AND c.instituteId=$instituteId
                  AND c.sessionId=$sessionId
                  $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getStudentAttendanceOptionsForDutyLeaves($conditions,$orderBy=' rollNo'){
  global $sessionHandler;
  $sessionId=$sessionHandler->getSessionVariable('SessionId');
  $instituteId=$sessionHandler->getSessionVariable('InstituteId');
  $query="
           SELECT
                 DISTINCT CONCAT(s.studentId,'~',c.classId,'~',sc.subjectId,'~',g.groupId) AS aOptions
            FROM
                   class c,`group` g,subject_to_class sc,student_groups sg,
                   student s,time_table_classes ttc , ".TIME_TABLE_TABLE." t,`subject` su
                WHERE
                  s.studentId=sg.studentId
                  AND sg.classId=c.classId
                  AND s.classId = c.classId
                  AND sg.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND sc.subjectId=su.subjectId
                  AND c.classId=ttc.classId
                  AND t.groupId=g.groupId
                  AND t.subjectId=sc.subjectId
                  AND t.timeTableLabelId=ttc.timeTableLabelId
                  AND t.toDate IS NULL
                  AND c.instituteId = $instituteId
                  AND c.sessionId   = $sessionId
                  $conditions
             ORDER BY $orderBy
           ";
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getStudentAttendanceForDutyLeaves($condition=''){
    global $sessionHandler;

    $query="SELECT
                   s.studentId,
                   SUM(IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )  AS attended,
                   SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,
                   att.classId,att.subjectId,att.groupId
            FROM
                   student s
                   INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                   LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
                   INNER JOIN subject su ON su.subjectId = att.subjectId
                   INNER JOIN class c ON c.classId = s.classId
                   AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                   $condition
                   GROUP BY att.subjectId, att.studentId,att.groupId
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



 public function deleteDutyLeavesAdvanced($deleteString) {

        $query = " DELETE
                   FROM
                      attendance_leave
                   WHERE
                        CONCAT(studentId,'~!~',classId,'~!~',groupId,'~!~',subjectId,'~!~',timeTableLabelId) IN ($deleteString)
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

 public function insertDutyLeavesAdvanced($insertString) {

        $query = " INSERT
                   INTO
                         attendance_leave
                          (
                           timeTableLabelId,studentId,leavesTaken,dated,comments,classId,subjectId,groupId,employeeId,userId
                          )
                   VALUES
                  ".$insertString;

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

//*************************FUNCTIONS USED FOR DUTY LEAVES MODULE*****************************

//**********************FUNCTIONS NEEDED FOR DISPLAYING CLASS WISE GRADES AND MARKS LIST*************************


 public function getGraceMarksList($conditions='', $orderBy = ' studentName'){

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
  $query="
			SELECT
						s.studentId,
						CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
		                IFNULL(s.rollNo,'---') AS rollNo,
				        IFNULL(s.universityRollNo,'---') AS universityRollNo,
						SUM( ttm.maxMarks ) AS maxMarks,
					    SUM( ttm.marksScored ) AS marksScored,
						IFNULL((select tgm.graceMarks from ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as graceMarks
			FROM
					    student_groups sg, ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
			WHERE		sg.studentId = ttm.studentId
			AND			sg.studentId = s.studentId
			AND			sg.classId = ttm.classId
			AND			ttm.conductingAuthority IN ( 1,2, 3 )
			$conditions
			GROUP BY 	ttm.studentId
            ORDER BY $orderBy
         ";
}
else{
    $query="
			SELECT
					    s.studentId,
						CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
		                IFNULL(s.rollNo,'---') AS rollNo,
				        IFNULL(s.universityRollNo,'---') AS universityRollNo,
		                SUM( ttm.maxMarks ) AS maxMarks,
				        SUM( ttm.marksScored ) AS marksScored,
						IFNULL((select tgm.graceMarks from  ".TEST_GRACE_MARKS_TABLE." tgm where tgm.studentId= sg.studentId and tgm.classId = sg.classId and tgm.subjectId = ttm.subjectId),0) as graceMarks
			FROM
					    student_optional_subject sg,  ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,student s
			WHERE 		sg.studentId = ttm.studentId
			AND 		sg.studentId = s.studentId
			AND 		sg.classId = ttm.classId
			AND 		ttm.conductingAuthority IN (  1,2, 3 )
			$conditions
			GROUP BY 	ttm.studentId
            ORDER BY $orderBy ";
}
/*echo $query;
die;*/
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


    //used to delete old records
    public function deleteGraceMarks($studentId,$classId,$subjectId){
        $query="DELETE from ".TEST_GRACE_MARKS_TABLE." WHERE studentId=".$studentId." AND classId=".$classId." AND subjectId=".$subjectId;
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    //used to insert new records
    public function insertGraceMarks($studentId,$classId,$subjectId,$graceMarks){
        $query="INSERT INTO ".TEST_GRACE_MARKS_TABLE." (studentId,classId,subjectId,internalGraceMarks,graceMarks) 
                VALUES($studentId,$classId,$subjectId,$graceMarks,$graceMarks)";
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//**********************FUNCTIONS NEEDED FOR DISPLAYING CLASS WISE GRADES AND MARKS LIST*************************

//**********************FUNCTIONS NEEDED FOR Test Marks Module***************************************************


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
          AND sc.classId=$classId AND sc.subjectId=$subjectId AND sc.optional=1" ;

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
                    FROM
                            test_type_category ttc,
                            subject s
                    WHERE
                            ttc.subjectTypeId=s.subjectTypeId
                            AND s.subjectId = $subjectId
                            $conditions
                    ORDER BY ttc.testTypeName
                            ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of classes for grace marks
//
// Author :Dipanjan Bhattacharjee
// Created on : (23.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------

	public function getClasses($labelId) {
		$query = "
					SELECT
								ttc.classId, c.className
					FROM		time_table_classes ttc, class c
					WHERE		ttc.timeTableLabelId = $labelId
					AND			ttc.classId IN( SELECT
											    DISTINCT classId
												FROM	 ".TOTAL_TRANSFERRED_MARKS_TABLE.")
					AND			ttc.classId = c.classId
					ORDER BY	c.degreeId,c.branchId,c.studyPeriodId
				";
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
			   testId,testAbbr,testIndex
			 FROM
			   ".TEST_TABLE."
			 WHERE
			   testTypeCategoryId=".$testTypeId. "
			   AND subjectId=".$REQUEST_DATA['subjectId']."
			   AND classId=".$REQUEST_DATA['classId']."
			   AND groupId=".$REQUEST_DATA['groupId']."
			   AND sessionId=".$sessionHandler->getSessionVariable('SessionId')."
			   AND instituteId=".$sessionHandler->getSessionVariable('InstituteId')."

			   ORDER BY testId DESC"; //so that newly created "test" comes at the bottom
	//echo $query;                                                                     //of "test" drop down
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
		   FROM ".TEST_TABLE." WHERE testId=".$testId;

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
			   testTypeCategoryId=".$testTypeId. "
			   AND subjectId=".$REQUEST_DATA['subjectId']."
			   AND classId=".$REQUEST_DATA['classId']."
			   AND groupId=".$REQUEST_DATA['groupId']."
			   AND sessionId=".$sessionHandler->getSessionVariable('SessionId')."
			   AND instituteId=".$sessionHandler->getSessionVariable('InstituteId');

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
	public function getStudenttMarksList($conditions='',$limit='',$orderBy=''){
	 global $REQUEST_DATA;

	 if(trim($REQUEST_DATA['test'])=="" or trim($REQUEST_DATA['test'])=="NT"){
	   $extCondition=" AND b.testId IS NULL ";
	 }
	 else{
		$extCondition=" AND b.testId = ".$REQUEST_DATA['test'];
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
							   AND b.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 LEFT JOIN ".TEST_TABLE." c ON
							(
							   b.testId = c.testId
							   AND c.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 WHERE
				  c.employeeId = emp.employeeId AND
				  c.subjectId = sub.subjectId AND
				  a.studentId=s.studentId
				  AND s.classId = cl.classId
				  AND a.classId = ".$REQUEST_DATA['classId']."
				  AND a.groupId =".$REQUEST_DATA['group']."
				  AND a.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
				  AND a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
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
							   AND b.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 LEFT JOIN ".TEST_TABLE." c ON
							(
							   b.testId = c.testId
							   AND c.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 WHERE
				  c.employeeId = emp.employeeId AND
				  a.subjectId = sub.subjectId AND	
				  a.studentId=s.studentId
				  AND s.classId = cl.classId
				  AND a.classId = ".$REQUEST_DATA['classId']."
				  AND a.groupId =".$REQUEST_DATA['group']."
				  AND a.subjectId =".$REQUEST_DATA['subject']."
				  AND cl.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
				  AND cl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
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
	public function getTestMarksList($conditions='',$limit='',$orderBy=''){
	 
     global $REQUEST_DATA;
     global $sessionHandler;   

	 if(trim($REQUEST_DATA['test'])=="" or trim($REQUEST_DATA['test'])=="NT"){
	   $extCondition=" AND b.testId IS NULL ";
	 }
	 else{
		$extCondition=" AND b.testId = ".$REQUEST_DATA['test'];
	 }
     
     $roleId = $sessionHandler->getSessionVariable('RoleId');     
     $session1 ='';
     $session2 ='';
     if($roleId >= 2 && $roleId <=4) {
       $session1 = " AND a.sessionId=".$sessionHandler->getSessionVariable('SessionId');
       $session2 = " AND cl.sessionId=".$sessionHandler->getSessionVariable('SessionId');
     }
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
							   AND b.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 LEFT JOIN ".TEST_TABLE." c ON
							(
							   b.testId = c.testId
							   AND c.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 WHERE
				  a.studentId=s.studentId
				  AND s.classId = cl.classId
				  AND a.classId = ".$REQUEST_DATA['classId']."
				  AND a.groupId =".$REQUEST_DATA['group']."
				  $session1
				  AND a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
				  ORDER BY $orderBy
				  $limit
				  " ;
	 }
	else{
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
				   class cl, student s , student_optional_subject a
				 LEFT JOIN ".TEST_MARKS_TABLE." b ON
							(
							   a.studentId = b.studentId
							   AND b.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 LEFT JOIN ".TEST_TABLE." c ON
							(
							   b.testId = c.testId
							   AND c.subjectId=".$REQUEST_DATA['subject']."
							   $extCondition
							)
				 WHERE
				  a.studentId=s.studentId
				  AND s.classId = cl.classId
				  AND a.classId = ".$REQUEST_DATA['classId']."
				  AND a.groupId =".$REQUEST_DATA['group']."
				  AND a.subjectId =".$REQUEST_DATA['subject']."
				  $session2
				  AND cl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
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
         global $sessionHandler; 
         
	 //if the testId is blanck or NT(new test)
	 if(trim($REQUEST_DATA['test'])=="" or trim($REQUEST_DATA['test'])=="NT"){
	   $extCondition=" AND b.testId IS NULL ";
	 }
	 else{
		$extCondition=" AND b.testId = ".$REQUEST_DATA['test'];
	 }
          
     $roleId = $sessionHandler->getSessionVariable('RoleId');     
     $session1 ='';
     $session2 ='';
     if($roleId >= 2 && $roleId <=4) {
       $session1 = " AND a.sessionId=".$sessionHandler->getSessionVariable('SessionId');
       $session2 = " AND cl.sessionId=".$sessionHandler->getSessionVariable('SessionId');
     }

     
	
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
  $query="SELECT
             COUNT(*) AS totalRecords
             FROM
               class cl, student s ,student_groups a
             LEFT JOIN ".TEST_MARKS_TABLE." b ON
                        (
                           a.studentId = b.studentId
                           AND b.subjectId=".$REQUEST_DATA['subject']."
                           $extCondition
                        )
             LEFT JOIN ".TEST_TABLE." c ON
                        (
                           b.testId = c.testId
                           AND c.subjectId=".$REQUEST_DATA['subject']."
                           $extCondition
                        )
             WHERE
              a.studentId=s.studentId
              AND s.classId = cl.classId
              AND a.classId = ".$REQUEST_DATA['classId']."
              AND a.groupId =".$REQUEST_DATA['group']."
              $session1
              AND a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
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
                           AND b.subjectId=".$REQUEST_DATA['subject']."
                           $extCondition
                        )
             LEFT JOIN ".TEST_TABLE." c ON
                        (
                           b.testId = c.testId
                           AND c.subjectId=".$REQUEST_DATA['subject']."
                           $extCondition
                        )
             WHERE
              a.studentId=s.studentId
              AND s.classId = cl.classId
              AND a.classId = ".$REQUEST_DATA['classId']."
              AND a.groupId =".$REQUEST_DATA['group']."
              AND a.subjectId =".$REQUEST_DATA['subject']."
              $session2
              AND cl.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
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
					 student s , ".TEST_TABLE."  t,class c
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
					     IFNULL(MAX(t.testIndex),0) AS maxIndex
			      FROM
					     ".TEST_TABLE."  t,class c
			      WHERE
					     t.classId=c.classId
					     AND t.classId = ".$classId."
					     AND t.groupId =".$groupId."
					     AND t.subjectId =".$subjectId."
					     AND t.testTypeCategoryId =".$testTypeCategoryId."
					     AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
					     AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
			      GROUP BY t.testTypeCategoryId " ;
                  
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
    $teacherId=$REQUEST_DATA['employeeId'];

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
    $teacherId=$REQUEST_DATA['employeeId'];

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
    //,userId=$userId
    $query="UPDATE
                   ".TEST_MARKS_TABLE."
            SET
                   maxMarks=$maxMarks,
                   marksScored=$marksScored,
                   isPresent=$present,
                   isMemberOfClass=$mclass
            WHERE
                   testMarksId=$testMarksId
                   $conditions ";
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

//**********************FUNCTIONS NEEDED FOR Test Marks Module***************************************************


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
            LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId=".$sessionHandler->getSessionVariable('InstituteId').")
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

               $extC
               $conditions
               GROUP BY att.subjectId, att.groupId
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

    $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0 ));
    $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
    $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
    $fromDate=((trim($REQUEST_DATA['startDate'])!=""?$REQUEST_DATA['startDate']:'0'));
    $toDate=((trim($REQUEST_DATA['endDate'])!=""?$REQUEST_DATA['endDate']:'0'));

    $query="SELECT
                s.studentId,abl.attendanceId,abl.lectureDelivered,abl.lectureAttended,
                abl.isMemberOfClass,
                CONCAT(tts.subjectTopicId,'~',tts.topicsTaughtId) AS taughtId,
                tts.subjectTopicId,
                tts.comments, tts.topicsTaughtId, sub.hasAttendance, sub.hasMarks
            FROM
                ".ATTENDANCE_TABLE." abl,student s,`group` g,topics_taught tts, subject sub
            WHERE
               attendanceType=1
               AND abl.subjectId=sub.subjectId
               AND abl.topicsTaughtId=tts.topicsTaughtId
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
// THIS FUNCTION IS USED FOR checking duplicate entries in "attendance" table
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (07.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
public function bulkAttendanceDuplicateCheck($class,$group,$subject,$fromDate,$toDate,$conditions=''){

    $query="SELECT
                s.studentId,abl.attendanceId,abl.lectureDelivered,abl.lectureAttended,
                abl.isMemberOfClass, sub.hasAttendance, sub.hasMarks
            FROM
                ".ATTENDANCE_TABLE." abl,student s,`group` g, subject sub
            WHERE
               attendanceType=1
               AND sub.subjectId = abl.subjectId
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


   $query="SELECT (abl.fromDate) AS fromDate,(abl.toDate) AS toDate
    FROM ".ATTENDANCE_TABLE." abl,student s
    WHERE attendanceType=1
    AND s.studentId=abl.studentId
    AND s.classId=abl.classId
    AND (
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
                    tts.topicsTaughtId, sub.hasAttendance, sub.hasMarks
            FROM    ".ATTENDANCE_TABLE." adl,
                    student s,
                    `group` g,
                    topics_taught tts,
                    subject sub
            WHERE    attendanceType=2
            AND        adl.topicsTaughtId=tts.topicsTaughtId
            AND        s.studentId=adl.studentId
            AND        fromDate ='$forDate'
            AND        adl.classId=$class
            AND        adl.subjectId=$subject
            AND        g.groupId=$group
            AND        adl.groupId=g.groupId
            AND        adl.periodId=$period
            AND        adl.subjectId=sub.subjectId
     $conditions ORDER BY $orderBy ";
     //echo $query;
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
                   ".ATTENDANCE_TABLE." adl,student s,`group` g, subject sub
            WHERE
                   attendanceType=2
                   AND s.studentId=adl.studentId
                   AND fromDate ='$forDate'
                   AND adl.classId=$class
                   AND adl.subjectId=$subject
                   AND adl.subjectId=sub.subjectId
                   AND g.groupId=$group
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

    $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0 ));
    $group=((trim($REQUEST_DATA['group'])!=""?$REQUEST_DATA['group']:0));
    $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
    $fromDate=((trim($REQUEST_DATA['startDate'])!=""?$REQUEST_DATA['startDate']:'0'));
    $toDate=((trim($REQUEST_DATA['endDate'])!=""?$REQUEST_DATA['endDate']:'0'));

    $query="SELECT COUNT(*) AS totalRecords
    FROM ".ATTENDANCE_TABLE." abl,student s,`group` g,subject sub
    WHERE attendanceType=1 AND s.studentId=abl.studentId AND (fromDate >='$fromDate' AND toDate <='$toDate' ) AND abl.classId=$class AND abl.subjectId=$subject AND abl.subjectId=sub.subjectId AND g.groupId=$group AND abl.classId=g.classId
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
    //check from daiky attendance*********

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
    //check from daiky attendance*********

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
    $employeeId=$REQUEST_DATA['employeeId'];


    $query="UPDATE ".ATTENDANCE_TABLE." SET lectureDelivered=$ldel , lectureAttended=$latt , isMemberOfClass=$memc ,
     fromDate='$fromDate',toDate='$toDate' , employeeId=".$employeeId." , userId=".$sessionHandler->getSessionVariable('UserId').",topicsTaughtId=".$topicsTaughtId."
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

    $query="UPDATE ".ATTENDANCE_TABLE." SET isMemberOfClass=$memc ,attendanceCodeId=$attendanceCodeId,
     employeeId=".$sessionHandler->getSessionVariable('EmployeeId')." ,
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
             AND stt.sessionId =".$sessionHandler->getSessionVariable('SessionId')."
             AND stt.instituteId =".$sessionHandler->getSessionVariable('InstituteId')."
            GROUP BY stt.subjectId
            ORDER BY dated DESC $limit ";
   //echo $query;

   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying attendance details for a particular attendance
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
public function editTopicsTaught($subjectTopicId,$topicsTaughtId,$comments){

      $query="UPDATE topics_taught SET subjectTopicId='$subjectTopicId',comments='".add_slashes($comments)."' WHERE topicsTaughtId=".$topicsTaughtId;
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
public function getTeacherSubjectTopic($subjectId){
     global $sessionHandler;
     //$empId=$sessionHandler->getSessionVariable('EmployeeId');
     global $REQUEST_DATA;
     $empId=$REQUEST_DATA['employeeId'];

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
            ORDER BY topicAbbr,att.topicsTaughtId
           ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }



//this function will check whether a group is optional group or not
public function checkOptionalGroup($groupId){
    $query="SELECT
                  g.groupId,g.isOptional
            FROM
                  `group` g
            WHERE
                   g.groupId=$groupId";

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

         $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0));
         $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
         $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));
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
							DISTINCT s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
							s.isLeet,
							s.fatherName,s.motherName,s.guardianName,s.rollNo,
							CONVERT(SUBSTRING(LEFT( s.rollNo, length(s.rollNo) - LENGTH(c.rollNoSuffix)) , LENGTH( c.rollNoPrefix ) +1), UNSIGNED) AS numericRollNo,
							IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
							deg.degreeName,deg.degreeAbbr,br.branchName,
							br.branchCode,ba.batchName,
							IF(s.corrCityId Is NULL,'--',(SELECT cityName FROM city WHERE cityId = s.corrCityId)) AS cityName,
							s.fatherTitle,s.fatherName,s.fatherMobileNo,s.fatherEmail,
							s.motherTitle,s.motherName,s.motherMobileNo,s.motherEmail,
							s.guardianTitle,s.guardianName,s.guardianMobileNo,s.guardianEmail,
							s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
					FROM
					   student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba,student_groups sg
					WHERE
					$extC
					  s.studentId=sg.studentId
					  AND sg.classId=c.classId

					  AND sg.groupId=g.groupId
					  AND sc.classId=c.classId
					  AND c.degreeId=deg.degreeId
					  AND c.branchId=br.branchId
					  AND c.batchId=ba.batchId
				 $conditions
				 ORDER BY $orderBy
				 $limit
				 ";
	  }
	  else{
		  $query= "SELECT
							DISTINCT s.studentId,concat(s.firstName,' ' ,s.lastName) as studentName,
							s.isLeet,
							s.fatherName,s.motherName,s.guardianName,s.rollNo,
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
					   student s,class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc
					WHERE
					  s.studentId=sc.studentId
					  AND sc.classId=c.classId

					  AND sc.groupId=g.groupId
					  AND sc.classId=c.classId
					  AND c.degreeId=deg.degreeId
					  AND c.branchId=br.branchId
					  AND c.batchId=ba.batchId
				 $conditions
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
	public function getStudentsList($conditions='', $limit = '', $orderBy='') {

		global $REQUEST_DATA;

		$class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0));
		$subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
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
		if($optionalGroup==0){ //if this group is not optional
			$query="
					SELECT
									DISTINCT s.studentId,
									concat(s.firstName,' ' ,s.lastName) as studentName,
									s.rollNo,
									IF(s.universityRollNo IS NULL OR s.universityRollNo='','---',s.universityRollNo) AS universityRollNo
						FROM
								student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba,student_groups sg
						WHERE
									s.studentId=sg.studentId
							AND		sg.classId=c.classId
							AND		sg.groupId=g.groupId
							AND		sc.classId=c.classId
							AND		c.degreeId=deg.degreeId
							AND		c.branchId=br.branchId
							AND		c.batchId=ba.batchId
							$conditions
							ORDER BY $orderBy
							$limit
					";
		}
		else{
			$query="
					SELECT
									DISTINCT s.studentId,
									concat(s.firstName,' ' ,s.lastName) as studentName,
									s.rollNo,
									IF(s.universityRollNo IS NULL OR s.universityRollNo='','---',s.universityRollNo) AS universityRollNo,
									SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
						FROM
								student s,class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc
						WHERE
									s.studentId=sc.studentId
							AND		sc.classId=c.classId
							AND		sc.groupId=g.groupId
							AND		sc.classId=c.classId
							AND		c.degreeId=deg.degreeId
							AND		c.branchId=br.branchId
							AND		c.batchId=ba.batchId
							$conditions
							ORDER BY $orderBy
							$limit
					";
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

     $class=((trim($REQUEST_DATA['classId'])!=""?$REQUEST_DATA['classId']:0));
     $subject=((trim($REQUEST_DATA['subject'])!=""?$REQUEST_DATA['subject']:0));
     $group=((trim($REQUEST_DATA['group'])!=""?trim($REQUEST_DATA['group']):0));

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
                   student s,class c,`group` g,subject_to_class sc,degree deg,branch br,batch ba,student_groups sg
                WHERE
                $extC
                  s.studentId=sg.studentId
                  AND sg.classId=c.classId
                  AND sg.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND c.degreeId=deg.degreeId
                  AND c.branchId=br.branchId
                  AND c.batchId=ba.batchId
             $conditions   ";
     }
     else{
        $query= "SELECT
                    COUNT(DISTINCT s.studentId) AS totalRecords
                FROM
                   student s,class c,`group` g,degree deg,branch br,batch ba,student_optional_subject sc
                WHERE
                  s.studentId=sc.studentId
                  AND sc.classId=c.classId
                  AND s.classId = c.classId
                  AND sc.groupId=g.groupId
                  AND sc.classId=c.classId
                  AND c.degreeId=deg.degreeId
                  AND c.branchId=br.branchId
                  AND c.batchId=ba.batchId
             $conditions";
     }
      //echo $query;

      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }

 /*
This function is used to calculate student attendace upto a certain date
Author : Dipanjan  Bhattacharjee
//Date : 02.04.2009
*/
public function getStudentAttendanceTillDate($condition='',$limit='',$orderBy=' s.firstName'){

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

//**********************FUNCTIONS NEEDED FOR DISPLAING STUDENT ATTENDANCE SUBMODULE IN TEACHER MODULE*********************



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
                              ttt.topicsTaughtId = att.topicsTaughtId  AND
                              sst.subjectId = att.subjectId
                              AND ttt.employeeId=att.employeeId
                              AND INSTR(ttt.subjectTopicId, CONCAT('~',sst.subjectTopicId,'~'))>0
                         ) AS topic
                  FROM
                        `group` g ,employee e,`subject` sub,class c,time_table_classes ttc,time_table_labels ttl,`subject` s,
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
                        AND att.subjectId=s.subjectId
                        $conditions
                        GROUP BY att.fromDate,att.employeeId,att.groupId,att.periodId,att.classId
                        ORDER BY $orderBy
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//**********************FUNCTIONS NEEDED FOR DISPLAING Attendance Summary************

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
                        AND att.subjectId=s.subjectId
                        $conditions
                        GROUP BY att.fromDate,att.employeeId,att.groupId,att.periodId,att.classId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//**********************FUNCTIONS NEEDED FOR DISPLAING Attendance Summary************


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
     /***************************/
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
     /********************************/
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


 public function getActiveTimeTableLabel(){
     global $sessionHandler;
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $query="
             SELECT
                    labelName,
                    timeTableLabelId
             FROM
                    time_table_labels
             WHERE
                   sessionId=$sessionId
                   AND instituteId=$instituteId
                   AND isActive=1
           ";
     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
 }


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

public function getTransferredSubjects($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT
                        DISTINCT
                                s.subjectId,s.subjectCode,s.subjectName
                        FROM
                                `subject` s, ".TOTAL_TRANSFERRED_MARKS_TABLE." tm
                        WHERE
                                s.subjectId = tm.subjectId
                                $conditions
                                ORDER BY s.subjectCode
                  ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
}
// $History: AdminTasksManager.inc.php $
//
//*****************  Version 39  *****************
//User: Dipanjan     Date: 20/04/10   Time: 11:33
//Updated in $/LeapCC/Model
//Made changes in "Attendance" and "Test" module in admin end for
//DAILY_TIMETABLE issues
//
//*****************  Version 38  *****************
//User: Dipanjan     Date: 19/04/10   Time: 13:47
//Updated in $/LeapCC/Model
//Added "Topics" column in "Attendance History Div"
//
//*****************  Version 37  *****************
//User: Parveen      Date: 4/02/10    Time: 1:04p
//Updated in $/LeapCC/Model

//orderBy clause updated (getTimeTableClasses function)
//
//*****************  Version 36  *****************
//User: Parveen      Date: 2/22/10    Time: 12:19p
//Updated in $/LeapCC/Model
//getTimeTableClasses function updated (HOD roles base show classes)
//
//*****************  Version 35  *****************
//User: Dipanjan     Date: 11/01/10   Time: 15:26
//Updated in $/LeapCC/Model
//Updated student data fetching query so that students of past time table
//and past classes are also displayed
//
//*****************  Version 34  *****************
//User: Dipanjan     Date: 29/12/09   Time: 15:58
//Updated in $/LeapCC/Model
//Corrected query in test marks module
//
//*****************  Version 33  *****************
//User: Dipanjan     Date: 17/12/09   Time: 16:38
//Updated in $/LeapCC/Model
//Corrected server side checking on "Test Marks" module data entry
//
//*****************  Version 32  *****************
//User: Dipanjan     Date: 17/12/09   Time: 11:01
//Updated in $/LeapCC/Model
//Corrected coding in Attendance history display logic
//
//*****************  Version 31  *****************
//User: Dipanjan     Date: 14/12/09   Time: 13:49
//Updated in $/LeapCC/Model
//Modified query related to fetching "Groups"
//
//*****************  Version 30  *****************
//User: Dipanjan     Date: 8/12/09    Time: 11:52
//Updated in $/LeapCC/Model
//Corrected grace marks query
//
//*****************  Version 29  *****************
//User: Parveen      Date: 12/04/09   Time: 3:52p
//Updated in $/LeapCC/Model
//
//*****************  Version 28  *****************
//User: Dipanjan     Date: 26/11/09   Time: 11:30
//Updated in $/LeapCC/Model
//Made enhancements in Attendance History : Teacher can now view other
//teachers attendance and also edit & delete them,if they have the same
//time table allocation.
//
//*****************  Version 27  *****************
//User: Dipanjan     Date: 21/11/09   Time: 17:49
//Updated in $/LeapCC/Model
//Modified getDutyLeaveGroups() function to change the sorting logic
//
//*****************  Version 26  *****************
//User: Dipanjan     Date: 21/11/09   Time: 12:55
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids :
//0002087 to 0002093
//
//*****************  Version 25  *****************
//User: Dipanjan     Date: 19/11/09   Time: 15:25
//Updated in $/LeapCC/Model
//Completed/Modified duty leaves module in teacher end
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 18/11/09   Time: 13:12
//Updated in $/LeapCC/Model
//Modified Duty Leaves module in admin section
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:07
//Updated in $/LeapCC/Model
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 13/11/09   Time: 18:21
//Updated in $/LeapCC/Model
//Corrected query condition columns
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 12/11/09   Time: 17:21
//Updated in $/LeapCC/Model
//Modified logic in bulk attendance module and corrected flaws in coding
//and removed check which prevents taking attendance across months or
//years.
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 6/11/09    Time: 16:48
//Updated in $/LeapCC/Model
//Added "Attendance History" option in bulk attendance from admin section
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Model
//Added code for "Time table adjustment"
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Model
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 14/10/09   Time: 18:16
//Updated in $/LeapCC/Model
//Made code and logic changes to take care of optional subjects repaled
//problems
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 10/09/09   Time: 5:34p
//Updated in $/LeapCC/Model
//fixed bug nos.0001748, 0001749, 0001747, 0001746, 0001745, 0001744,
//0001742, 0001731
//
//*****************  Version 13  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Model
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Model
//worked on role to class
//
//*****************  Version 11  *****************
//User: Parveen      Date: 9/28/09    Time: 12:25p
//Updated in $/LeapCC/Model
//getTeacherSubject query update (hasAttendance, hasMarks fields added)
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Model
//Corrected look and feel of teacher module logins
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:51
//Updated in $/LeapCC/Model
//Corrected "Overlapping Attendance Problem" in Leap,LeapCC and SNS.
//
//*****************  Version 7  *****************
//User: Administrator Date: 11/06/09   Time: 16:01
//Updated in $/LeapCC/Model
//Created "Bulk Attendance" modules in admin section in leapcc
//
//*****************  Version 6  *****************
//User: Administrator Date: 10/06/09   Time: 11:18
//Updated in $/LeapCC/Model
//Created "Test Marks" module in admin section
//
//*****************  Version 5  *****************
//User: Administrator Date: 5/06/09    Time: 15:12
//Updated in $/LeapCC/Model
//Corrected attendance deletion module's logic
//
//*****************  Version 4  *****************
//User: Administrator Date: 4/06/09    Time: 10:47
//Updated in $/LeapCC/Model
//Created grace marks module in admin end
//
//*****************  Version 3  *****************
//User: Administrator Date: 27/05/09   Time: 12:32
//Updated in $/LeapCC/Model
//Added "comments" field in duty leave module in admin & teacher section
//
//*****************  Version 2  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Updated in $/LeapCC/Model
//Created "Duty Leave" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:22
//Created in $/LeapCC/Model
//Created Attendance Delete Module
?>
