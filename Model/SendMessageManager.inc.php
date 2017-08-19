<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class SendMessageManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SendMessageManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SendMessageManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
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

//---------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching student email and mobileno
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------
public function getStudentInfo($studentId){

    $query="SELECT studentMobileNo,studentEmail FROM student WHERE studentId IN ($studentId)";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//---------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching parent email and mobileno
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------
public function getParentInfo($studentId){

    $query="SELECT
                    fatherMobileNo,fatherEmail,motherMobileNo,motherEmail,guardianMobileNo,guardianEmail,
                    fatherUserId, motherUserId, guardianUserId, userId, studentId
            FROM
                    student
            WHERE
                    studentId IN ($studentId)";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//----------------------------------------------------------------
// THIS FUNCTION IS USED FOR sending message students/+parents
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------

public function sendMessage($query){
    return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
}



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING group list
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
public function getGroup($conditions=''){
    global $sessionHandler;

    $query="SELECT groupId,groupName,class.classId
            FROM `group`, class
            WHERE  group.classId=class.classId AND instituteId=".$sessionHandler->getSessionVariable('InstituteId') . " AND sessionId=".$sessionHandler->getSessionVariable('SessionId') .
            " $conditions $conditions";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getStudentList($conditions='', $limit = '', $orderBy=' studentId') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                            s.studentId, rollNo, universityRollNo, concat(firstName,' ',lastName) AS studentName,
                            deg.degreeAbbr,br.branchCode,stp.periodName,cl.className,
                            fatherName, motherName, guardianName,
                            s.studentMobileNo, s.studentEmail,
                            s.fatherMobileNo, s.fatherEmail,
                            s.motherMobileNo, s.motherEmail,
                            s.motherMobileNo, s.motherEmail,
                            s.guardianMobileNo, s.guardianEmail,
                            s.guardianMobileNo, s.guardianEmail,
                            s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
                  FROM
                            student s,class cl,degree deg,branch br,study_period stp
                  WHERE
                            s.classId=cl.classId
                            AND cl.degreeId=deg.degreeId AND cl.branchId=br.branchId AND cl.studyPeriodId=stp.studyPeriodId
                            AND cl.instituteId='".$instituteId."' AND cl.sessionId='".$sessionId."'
                  $conditions
                  ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 //---------------------------------------------------------------------------------------
 // THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
 //
 //$conditions :db clauses
 // Author :Dipanjan Bhattacharjee
 // Created on : (8.7.2008)
 // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
 //
 //----------------------------------------------------------------------------------------
        public function getTotalStudent($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT studentId
        FROM student s,class cl,degree deg,branch br,study_period stp
        WHERE s.classId=cl.classId
        AND cl.degreeId=deg.degreeId AND cl.branchId=br.branchId AND cl.studyPeriodId=stp.studyPeriodId
        AND cl.instituteId=".$instituteId." AND cl.sessionId=".$sessionId."
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING employee LIST
//
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getEmployeeList($conditions='', $limit = '', $orderBy=' e.employeeName') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                            e.employeeId, e.employeeName,e.employeeCode,e.employeeAbbreviation,
                            IF(e.isTeaching=1,'YES','NO') AS isTeaching,e.qualification,
                            e.dateOfJoining,
                            d.designationName,br.branchCode,r.roleName, e.userId, e.emailAddress, e.mobileNumber
                  FROM
                            employee e,designation d,`user` u,`role` r,branch br
                  WHERE
                            e.designationId=d.designationId
                            AND e.isActive=1
                            AND e.branchId=br.branchId
                            AND e.instituteId=".$instituteId."
                            AND e.userId=u.userId AND u.roleId=r.roleId
                $conditions
                ORDER BY $orderBy $limit";

        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalEmployee($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                      COUNT(*) AS totalRecords
                  FROM
                        employee e,designation d,`user` u,`role` r,branch br
                 WHERE
                   e.designationId=d.designationId
                      AND e.isActive=1
                      AND e.branchId=br.branchId
                        AND e.instituteId=".$instituteId."
                        AND e.userId=u.userId AND u.roleId=r.roleId
                        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students upon selection of class,subject and group dropdown
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getParentList($conditions='', $limit = '', $orderBy='') {

         global $REQUEST_DATA;
         global $sessionHandler;
         $instituteId=$sessionHandler->getSessionVariable('InstituteId');
         $sessionId=$sessionHandler->getSessionVariable('SessionId');

         $query="SELECT
                       s.studentId,
                       rollNo,
                       universityRollNo,
                       CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                       s.fatherName,s.motherName,s.guardianName,
                       deg.degreeAbbr,br.branchCode,stp.periodName,
                       s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
                FROM
                       student s,class cl,degree deg,branch br,study_period stp
                WHERE
                       s.classId=cl.classId
                       AND cl.degreeId=deg.degreeId
                       AND cl.branchId=br.branchId
                       AND cl.studyPeriodId=stp.studyPeriodId
                       AND cl.instituteId=".$instituteId."
                       AND cl.sessionId=".$sessionId."
                $conditions
                ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

     //--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students upon selection of class,subject and group dropdown
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTotalParentList($conditions='') {

         global $REQUEST_DATA;
         global $sessionHandler;

         $instituteId=$sessionHandler->getSessionVariable('InstituteId');
         $sessionId=$sessionHandler->getSessionVariable('SessionId');

         $query="
                 SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        student s,class cl,degree deg,branch br,study_period stp
                 WHERE
                        s.classId=cl.classId
                        AND cl.degreeId=deg.degreeId
                        AND cl.branchId=br.branchId
                        AND cl.studyPeriodId=stp.studyPeriodId
                        AND cl.instituteId=".$instituteId."
                        AND cl.sessionId=".$sessionId."
                        $conditions
           ";
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

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');

       if($conditions=='') {
          $conditions1 = "WHERE
                                s.classId=cl.classId
                                AND cl.degreeId=deg.degreeId AND cl.branchId=br.branchId AND cl.studyPeriodId=stp.studyPeriodId
                                AND cl.instituteId=".$instituteId." AND cl.sessionId=".$sessionId;
       }
       else {
           $conditions1 = $conditions ." AND s.classId=cl.classId
                                         AND cl.degreeId=deg.degreeId AND cl.branchId=br.branchId AND cl.studyPeriodId=stp.studyPeriodId
                                         AND cl.instituteId=".$instituteId." AND cl.sessionId=".$sessionId;
       }

       $query = "SELECT
                            s.studentId,
                            IFNULL(rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                            IFNULL(universityRollNo,'".NOT_APPLICABLE_STRING."') AS universityRollNo,
                            CONCAT(firstName,' ',lastName) AS studentName,
                            deg.degreeAbbr,br.branchCode,stp.periodName,cl.className,
                            s.studentMobileNo, s.studentEmail,
                            s.fatherMobileNo, s.fatherEmail,
                            s.motherMobileNo, s.motherEmail,
                            s.motherMobileNo, s.motherEmail,
                            s.guardianMobileNo, s.guardianEmail,
                            s.guardianMobileNo, s.guardianEmail,
                            s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
                  FROM
                            student s,class cl,degree deg,branch br,study_period stp
                  $conditions1
                  ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Inserting falied sms details
//
//$conditions :db clauses
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function insertIntoAdminMsgsFailed($lastMsgId,$receiverType,$curDate,$smsNotSendStudentId,$sub,$msg,$type){
		global $sessionHandler;
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$userId =$sessionHandler->getSessionVariable('UserId');
		$query="INSERT INTO
						admin_messages_failed (messageId,receiverType,dated,receiverIds,messageSubject,message,messageType,senderId,instituteId,sessionId)
										VALUES('$lastMsgId','$receiverType','$curDate','$smsNotSendStudentId','".add_slashes($sub)."','".add_slashes($msg)."','SMS','$userId','$instituteId','$sessionId')";
		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}
//--------------------------------------------------------------------------------------------------------------
// This function is used to update  recever ids in admin_messages_failed and admin_messages tables it remove #0 from the rows which is used to	differen-shiate //failed messages data for send messages data.
//$conditions :db clauses
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
	public function updateIdInAdminMessages($table, $condition){
		$query ="
					UPDATE			`$table`
					$condition
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO INSERT MULTPILE ROWS IN ADMIN MESSAGES
//
//$conditions :db clauses
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

	public function insertBulkFailedMessages(){
		$query = "
					INSERT
					INTO		`admin_messages_failed`
								(messageId,receiverType,dated,receiverIds,messageSubject,message,messageType,senderId,instituteId,sessionId)
					SELECT		messageId,
								receiverType,
								dated,
								receiverIds,
								subject,
								message,
								messageType,
								senderId,
								instituteId,
								sessionId
					FROM		`admin_messages`
					WHERE		receiverIds like '%#0%'
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of employees mobilenos and emails
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getEmployeeEmailMobileNoList($conditions='', $orderBy=' employeeId') {

        $query="SELECT employeeId,employeeName,emailAddress , mobileNumber
        FROM employee
        $conditions order by  $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of parents  mobilenos and emails
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getParentEmailMobileNoList($conditions='', $orderBy=' studentId') {

        $query="SELECT
        studentId,fatherTitle,fatherName,fatherMobileNo,fatherEmail,
        motherTitle,motherName,motherMobileNo,motherEmail,
        guardianTitle,guardianName,guardianMobileNo,guardianEmail,
        CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName, studentMobileNo, studentEmail
        fatherUserId, motherUserId, guardianUserId, userId
        FROM student
         $conditions order by  $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting sms/email records sent sms/email to students/+employees
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function adminMessageEmailSMSRecord($conditions='') {

       $query="INSERT INTO `admin_messages` (receiverIds,receiverType,dated,subject,message,messageType,visibleFromDate,visibleToDate,senderId,instituteId,
        sessionId) VALUES
         $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting sms/email records sent sms/email to students/+employees in Transaction
//
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

	public function insertIntoAdminMessagesInTranscation($conditions='') {

       $query="INSERT
						INTO `admin_messages`
								(receiverIds,receiverType,dated,subject,message,messageType,visibleFromDate,visibleToDate,senderId,instituteId,
								sessionId)
						VALUES
						$conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
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

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student LIST role wise
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (01.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getRoleStudentList($conditions='', $limit = '', $orderBy=' studentId',$userId) {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
	   $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "SELECT
                            distinct s.studentId, rollNo, universityRollNo, concat(firstName,' ',lastName) AS studentName,
                            deg.degreeAbbr,br.branchCode,stp.periodName,cl.className,
                            fatherName, motherName, guardianName,
                            s.studentMobileNo, s.studentEmail,
                            s.fatherMobileNo, s.fatherEmail,
                            s.motherMobileNo, s.motherEmail,
                            s.motherMobileNo, s.motherEmail,
                            s.guardianMobileNo, s.guardianEmail,
                            s.guardianMobileNo, s.guardianEmail,
                            s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
                  FROM
							class cl,
							degree deg,
							branch br,
							study_period stp,
							classes_visible_to_role cvtr,
							student s
				  LEFT JOIN student_groups sg ON s.studentId = sg.studentId
				  LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
                  WHERE
                            s.classId=cl.classId
                  AND		cl.degreeId=deg.degreeId
				  AND		cl.branchId=br.branchId
				  AND		cl.studyPeriodId=stp.studyPeriodId
				  AND		cvtr.classId = s.classId
				  AND		cvtr.classId = cl.classId
				  AND		cvtr.groupId = grp.groupId
				  AND		cvtr.groupId = sg.groupId
				  AND		cvtr.userId = $userId
				  AND		cvtr.roleId = $roleId
                  AND		cl.instituteId=".$instituteId."
				  AND		cl.sessionId=".$sessionId."
							$conditions
                  ORDER BY $orderBy $limit";

        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalRoleStudent($conditions='',$userId) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
		$roleId = $sessionHandler->getSessionVariable('RoleId');

              $query = "	SELECT
							distinct s.studentId
					FROM	class cl,
							degree deg,
							branch br,
							study_period stp,
							classes_visible_to_role cvtr,
							student s
					LEFT JOIN student_groups sg ON s.studentId = sg.studentId
				    LEFT JOIN `group` grp ON ( sg.groupId = grp.groupId )
					WHERE	s.classId=cl.classId
					AND		cl.degreeId=deg.degreeId
					AND		cl.branchId=br.branchId
					AND		cvtr.classId = s.classId
					AND		cvtr.classId = cl.classId
					AND		cvtr.groupId = grp.groupId
					AND		cvtr.groupId = sg.groupId
					AND		cvtr.userId = $userId
					AND		cvtr.roleId = $roleId
					AND		cl.studyPeriodId=stp.studyPeriodId
					AND		cl.instituteId=".$instituteId."
					AND		cl.sessionId=".$sessionId."
					        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET STUDENT ENQUIRY DATA
//
// Author :Parveen Sharma
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getStudentEnquiryData($condition='',$orderBy='',$limit='') {

        $query = "SELECT
                        *
                  FROM
                       student_enquiry
                  $condition
                  $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



    public function getClasses($conditions=''){

        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "    SELECT
                            distinct cvtr.classId
                      FROM
                           classes_visible_to_role cvtr
                      WHERE
                           cvtr.userId = $userId
                           AND cvtr.roleId = $roleId";

        $result =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $cnt=count($result);
        $insertValue = "";
        for($i=0;$i<$count; $i++) {
                if($insertValue!='') {
                    $insertValue = ",";
                }
                $insertValue .= " ('".$result[$i]['classId']."')";
        }
        if($insertValue!=''){
            $classCondition =' AND c.classId IN '.$insertValue;
        }

        $query="SELECT
                      DISTINCT c.classId,
                      SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                FROM
                      time_table_classes ttc,time_table_labels ttl,`class` c
                WHERE
                      c.classId=ttc.classId
                      AND ttc.timeTableLabelId=ttl.timeTableLabelId
                      AND ttl.sessionId=$sessionId
                      AND ttl.instituteId=$instituteId
                      $conditions
                      $classCondition
                ORDER BY c.studyPeriodId
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getGroups($conditions=''){

        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        $query = "    SELECT
                            distinct cvtr.groupId
                      FROM
                            classes_visible_to_role cvtr
                      WHERE
                            cvtr.userId = $userId
                            AND cvtr.roleId = $roleId";

        $result =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        $cnt=count($result);
        $insertValue = "";
        for($i=0;$i<$count; $i++) {
                if($insertValue!='') {
                    $insertValue = ",";
                }
                $insertValue .= " ('".$result[$i]['groupId']."')";
        }
        if($insertValue!=''){
            $groupCondition =' AND g.groupId IN '.$insertValue;
        }

        $query="SELECT
                      DISTINCT g.groupId,
                      g.groupName,
                      g.groupShort
                FROM
                      time_table_classes ttc,time_table_labels ttl,`class` c,
                      `group` g
                WHERE
                      g.classId=c.classId
                      AND c.classId=ttc.classId
                      AND ttc.timeTableLabelId=ttl.timeTableLabelId
                      AND ttl.sessionId=$sessionId
                      AND ttl.instituteId=$instituteId
                      $conditions
                      $groupCondition
                ORDER BY g.groupName
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getTests($conditions=''){

        global $sessionHandler;
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query="SELECT
                      DISTINCT t.testIndex,tc.testTypeName,
                      t.testId,tc.testTypeCategoryId
                FROM
                      time_table_classes ttc, time_table_labels ttl, `class` c,
                      `group` g, ".TEST_TABLE." t, test_type_category tc
                WHERE
                      t.groupId=g.groupId
                      AND t.classId=c.classId
                      AND t.testTypeCategoryId=tc.testTypeCategoryId
                      AND tc.examType='PC'
                      AND g.classId=c.classId
                      AND c.classId=ttc.classId
                      AND ttc.timeTableLabelId=ttl.timeTableLabelId
                      AND ttl.sessionId=$sessionId
                      AND ttl.instituteId=$instituteId
                      $conditions
                GROUP BY t.testTypeCategoryId,t.testIndex
                ORDER BY tc.testTypeName,t.testIndex
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



public function getParentListForStudentPerformance($conditions='', $limit = '', $orderBy='') {

         global $REQUEST_DATA;
         global $sessionHandler;
         $instituteId=$sessionHandler->getSessionVariable('InstituteId');
         $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query="SELECT
                       s.studentId,
                       rollNo,
                       universityRollNo,
                       CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                       s.fatherName,s.motherName,s.guardianName,
                       s.fatherUserId, s.motherUserId, s.guardianUserId, s.userId
                FROM
                        student s
                WHERE
                        s.classId
                                  IN
                                     (
                                       SELECT
                                             DISTINCT
                                                      c.classId
                                       FROM
                                             student_groups sg,class c,
                                             time_table_classes ttc
                                       WHERE
                                             sg.classId=c.classId
                                             AND ttc.classId=c.classId
                                             AND c.instituteId=".$instituteId."
                                             AND c.sessionId=".$sessionId."
                                             AND c.classId=ttc.classId
                                             $conditions
                                     )
                ORDER BY $orderBy
                $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

     //--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING list of students upon selection of class,subject and group dropdown
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
    public function getTotalParentListForStudentPerformance($conditions='') {

         global $REQUEST_DATA;
         global $sessionHandler;

         $instituteId=$sessionHandler->getSessionVariable('InstituteId');
         $sessionId=$sessionHandler->getSessionVariable('SessionId');

         $query="
                 SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        student s
                 WHERE
                        s.classId
                                  IN
                                     (
                                       SELECT
                                             DISTINCT
                                                      c.classId
                                       FROM
                                             student_groups sg,class c,
                                             time_table_classes ttc
                                       WHERE
                                             sg.classId=c.classId
                                             AND ttc.classId=c.classId
                                             AND c.instituteId=".$instituteId."
                                             AND c.sessionId=".$sessionId."
                                             AND c.classId=ttc.classId
                                             $conditions
                                     )
           ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


     public function getTestInfo($testId) {

         $query="
                  SELECT
                         testTypeCategoryId,testIndex
                  FROM
                         ".TEST_TABLE."
                  WHERE
                         testId=$testId
               ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }


//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR updating "messageFile" field of "admin_message" table
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (11.05.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function updateAdminMessageFile($messageIds,$fileName='') {

       $query="UPDATE admin_messages SET messageFile='".$fileName."' WHERE messageId IN ($messageIds)";
       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting sms msgIds for failed messages
//
//$conditions :db clauses
// Author :Abhiraj 
// Created on : (24.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function saveSmsMessageIdFailedSms($mobileNo,$smsText,$providerString) {
        
       global $sessionHandler;
       $userId=$sessionHandler->getSessionVariable('UserId');
	   $roleId=$sessionHandler->getSessionVariable('RoleId');
       $query="INSERT INTO sms_messages (smsFrom,smsTo,msgId,sentDate,smsText,smsStatus,roleId,providerString) VALUES  
        ('$userId','$mobileNo','0','".date('Y-m-d')."','$smsText','0','$roleId','$providerString') ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }

//--------------------------------------------------------------------------------------------------------------
//
// THIS FUNCTION IS USED FOR inserting sms msgIds for messages that have been sent successfully but are in pending
//,delivered or undelivered state
// $conditions :db clauses
// Author :Abhiraj 
// Created on : (24.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function saveSmsMessageId($mobileNo,$smsText,$msgId,$providerString) {
        global $sessionHandler;
        $userId=$sessionHandler->getSessionVariable('UserId');
		$roleId=$sessionHandler->getSessionVariable('RoleId');
        $smsStatus=1;
        $query="INSERT INTO sms_messages(smsFrom,smsTo,msgId,sentDate,smsText,smsStatus,roleId,providerString) VALUES
         ('$userId','".$mobileNo."','$msgId','".date('Y-m-d')."','$smsText','$smsStatus','$roleId','$providerString') ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }

}

// $History: SendMessageManager.inc.php $
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 9/04/10    Time: 17:22
//Updated in $/LeapCC/Model
//Done bug fixing.
//Fixed bug ids---
// 0003259
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 22/03/10   Time: 16:04
//Updated in $/LeapCC/Model
//corrected query for HOD role
//
//*****************  Version 14  *****************
//User: Parveen      Date: 3/10/10    Time: 3:44p
//Updated in $/LeapCC/Model
//getStudentEnquiryData function added
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 23/01/10   Time: 11:09
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---0002690 to 0002698
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Model
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/01/09   Time: 10:47a
//Updated in $/LeapCC/Model
//modification in code & query for HOD role
//
//*****************  Version 10  *****************
//User: Parveen      Date: 6/04/09    Time: 7:17p
//Updated in $/LeapCC/Model
//all query format is update
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/04/09    Time: 1:23p
//Updated in $/LeapCC/Model
//getStudentList update
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/04/09    Time: 12:25p
//Updated in $/LeapCC/Model
//all queries (fatherUserId, motherUserId, guardianUserId, userId,
//studentId )  updated
//
//*****************  Version 7  *****************
//User: Administrator Date: 1/06/09    Time: 17:32
//Updated in $/LeapCC/Model
//Added the functionality : sms & email not sent to how many students
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 26/05/09   Time: 13:27
//Updated in $/LeapCC/Model
//Added functions for "Send Message for Parents"
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 13/12/08   Time: 17:55
//Updated in $/LeapCC/Model
//Corrected Database fields
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 13/12/08   Time: 16:48
//Updated in $/LeapCC/Model
//Renamed fromDate to visibleFrom and toDate to visibleTo fields in
//admin_messages
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Model
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/03/08   Time: 5:18p
//Updated in $/LeapCC/Model
//Create Send Message to All
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/03/08   Time: 11:42a
//Updated in $/Leap/Source/Model
//Corrected "From" field in mail sending and employee name field
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/17/08    Time: 4:14p
//Updated in $/Leap/Source/Model
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/05/08    Time: 12:11p
//Updated in $/Leap/Source/Model
//Added employee search filter
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/01/08    Time: 6:42p
//Updated in $/Leap/Source/Model
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/11/08    Time: 4:32p
//Updated in $/Leap/Source/Model
//Added function for sending msg from admin end to students and
//employees(or users).
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/08/08    Time: 7:29p
//Updated in $/Leap/Source/Model
//Added comments
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/08/08    Time: 5:49p
//Updated in $/Leap/Source/Model
//Created sendMessage module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/05/08    Time: 6:16p
//Created in $/Leap/Source/Model
//Initial Checkin
?>
