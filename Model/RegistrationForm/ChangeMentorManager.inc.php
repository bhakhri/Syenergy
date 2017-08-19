<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
class ChangeMentorManager {
    private static $instance = null;
    
    private function __construct() {
    }
    
    public static function getInstance() {         
        if (self::$instance === null) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
 
//<--------------------------------------------------------->//
    
    public function getHoldClasses($condition='') {
    
        $query = "SELECT 
                        holdCompreMarks, holdPreCompreMarks, holdTestMarks, holdGrade 
                   FROM 
                        time_table_classes
                  WHERE 
                        $condition";
                  
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getAllEmployees($condition='') {
          
          global $sessionHandler;
          $systemDatabaseManager = SystemDatabaseManager::getInstance();

          $query = "SELECT
                        DISTINCT e.employeeId, e.employeeName, e.employeeCode, e.isTeaching,    
                        CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName1, e.isActive,
                        e.userId, i.instituteName, i.instituteCode
                    FROM    
                        `employee` e, `user` u, `institute` i
                    WHERE
                        e.userId = u.userId AND
                        u.instituteId = i.instituteId
                        $condition
                    ORDER BY 
                        e.isActive DESC, TRIM(e.employeeName) ASC ";

            return $systemDatabaseManager->executeQuery($query,"Query: $query");
   }
   
   public function addMentorStudentStatus($isAllowRegistration,$condition='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "UPDATE 
                      `student_teacher_mentorship` 
                  SET 
                      isAllowRegistration = '$isAllowRegistration'
                  WHERE
                      $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }



//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE 
//orderBy: on which column to sort
// Author :Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.

//
//--------------------------------------------------------
    public function getFeeCycleNew($orderBy=' cycleName',$condition='') {
	global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT	feeCycleId,cycleName 
        		FROM 	`fee_cycle_new`
        		WHERE	instituteid= '".$sessionHandler->getSessionVariable('InstituteId')."'
        		AND	sessionId = '".$sessionHandler->getSessionVariable('SessionId')."'
        		 $condition  
        		 ORDER BY $orderBy";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
   
   public function getCurrentMentorNew($condition='') {
       
       global $sessionHandler;
       $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                     DISTINCT 
                     sm.userId, u.userName, e.employeeId, e.employeeName, e.employeeCode, e.isTeaching,    
                     CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName1, e.isActive,
                     i.instituteName, i.instituteCode 
                 FROM
                     class c, student_teacher_mentorship sm, `user` u, employee e, `institute` i 
                 WHERE     
                     e.userId = u.userId                         
                     AND c.classId = sm.classId
                     AND sm.userId = u.userId
                     AND c.instituteId = '$instituteId' 
                     AND c.sessionId = '$sessionId'
                     AND i.instituteId = u.instituteId
                 ORDER BY
                     e.employeeName ";
                  
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function getStudentMentorList($condition='',$orderBy='',$limit='') {
       
       global $sessionHandler;
       $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                     DISTINCT 
                     sm.mentorshipId,
                     CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) as studentName,
                     sm.userId, u.userName, e.employeeId, e.employeeName, e.employeeCode, e.isTeaching,    
                     CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName1, e.isActive,
                     IF(s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo, c.className,
                     IF(s.studentEmail='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                     IF(s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo, 
                     IF(s.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = s.corrCityId)) AS corrCityId,
                     IF(s.studentMobileNo='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo ,
                     IFNULL(IF(IFNULL(corrAddress1,'')='','', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=s.corrCityId),' ',(SELECT stateName from states where states.stateId=s.corrStateId),' ',(SELECT countryName from countries where countries.countryId=s.corrCountryId),IF(IFNULL(s.corrPinCode,'')='','',CONCAT('-',s.corrPinCode)))),'".NOT_APPLICABLE_STRING."') AS corrAddress,
                     IFNULL(IF(IFNULL(permAddress1,'')='','', CONCAT(permAddress1,' ',IFNULL(permAddress2,''),' ',(SELECT cityName from city where city.cityId=s.permCityId),' ',(SELECT stateName from states where states.stateId=s.permStateId),' ',(SELECT countryName from countries where countries.countryId=s.permCountryId),IF(IFNULL(s.permPinCode,'')='','',CONCAT('-',s.permPinCode)))),'".NOT_APPLICABLE_STRING."') AS permAddress,
                     IF(s.fatherName='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                     IF(s.dateOfBirth='0000-00-00','".NOT_APPLICABLE_STRING."',s.dateOfBirth) AS dateOfBirth,
                     IFNULL(studentPhoto,'') AS studentPhoto, sm.isAllowRegistration
                 FROM
                     class c, student s, student_teacher_mentorship sm, `user` u, employee e
                 WHERE     
                     e.userId = u.userId                         
                     AND c.classId = sm.classId
                     AND sm.userId = u.userId
                     AND c.instituteId = '$instituteId' 
                     AND c.sessionId = '$sessionId'
                     AND sm.studentId = s.studentId 
                     $condition
                 ORDER BY
                     $orderBy $limit ";
                       
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   
   public function getStudentMentorCount($condition='') {
       
       global $sessionHandler;
       $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                     COUNT(DISTINCT sm.mentorshipId) AS totalRecords
                 FROM
                     class c, student s, student_teacher_mentorship sm, `user` u, employee e
                 WHERE     
                     e.userId = u.userId                         
                     AND c.classId = sm.classId
                     AND sm.userId = u.userId
                     AND c.instituteId = '$instituteId' 
                     AND c.sessionId = '$sessionId'
                     AND sm.studentId = s.studentId 
                     $condition";
                       
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

public function getNewMentorNew($currentMentorId) {

	$query = "SELECT 
			u.userId,e.employeeId, 
			e.employeeName, e.employeeCode,
			CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeNameCode 
		  FROM 
			`user` u, `employee` e 
		  WHERE 
			u.userId  = e.employeeId AND
			u.userId != '$currentMentorId' AND 
			u.roleId  = 2 
	          ORDER BY 
			e.employeeName";
	  

   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  
    }
    
    public function updateMentor($currentMentorId, $newMentorId,$studentChk='') {
   $query = "UPDATE student_teacher_mentorship SET  userId = '$newMentorId' WHERE userId='$currentMentorId' AND mentorshipId IN ($studentChk) ";
   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
} 
   
}

?>
