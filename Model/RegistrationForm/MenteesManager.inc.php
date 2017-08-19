<?php 
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "studentregistration" TABLE

//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class MenteesManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "studentRegistrationManager" CLASS

//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "studentRegistrationManager" CLASS

//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
    
    public function getstudentRegistrationDetails($condition='',$orderBy='',$limit='') {
       
       global $sessionHandler;
       $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                     DISTINCT 
                     sm.mentorshipId, s.studentId, s.classId,
                     CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) as studentName,
                     sm.userId, u.userName, e.employeeId, e.employeeName, e.employeeCode, e.isTeaching,    
                     CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName1, e.isActive,
                     IF(s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo, c.className,
                     IF(s.studentEmail='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                     IF(s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo, 
                     IF(s.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = s.corrCityId)) AS corrCityId,
                     IFNULL(IF(IFNULL(s.corrAddress1,'')='','', CONCAT(s.corrAddress1,' ',(SELECT cityName from city where city.cityId=s.corrCityId),' ',(SELECT stateName from states where states.stateId=s.corrStateId),' ',(SELECT countryName from countries where countries.countryId=s.corrCountryId),IF(IFNULL(s.corrPinCode,'')='','',CONCAT('-',s.corrPinCode)))),'".NOT_APPLICABLE_STRING."') AS corrAddress,
                     IFNULL(IF(IFNULL(s.permAddress1,'')='','', CONCAT(s.permAddress1,' ',IFNULL(s.permAddress2,''),' ',(SELECT cityName from city where city.cityId=s.permCityId),' ',(SELECT stateName from states where states.stateId=s.permStateId),' ',(SELECT countryName from countries where countries.countryId=s.permCountryId),IF(IFNULL(s.permPinCode,'')='','',CONCAT('-',s.permPinCode)))),'".NOT_APPLICABLE_STRING."') AS permAddress,
                     IF(s.fatherName='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                     IF(s.dateOfBirth='0000-00-00','".NOT_APPLICABLE_STRING."',s.dateOfBirth) AS dateOfBirth,
                     IFNULL(studentPhoto,'') AS studentPhoto, sm.isAllowRegistration, sr.registrationDate, sr.studentMobileNo
                 FROM
                     class c, student s, `user` u, employee e,
                     student_teacher_mentorship sm 
                     LEFT JOIN  sc_student_registration sr ON sm.studentId = sr.studentId AND  sm.classId = sr.classId  
                 WHERE     
                     e.userId = u.userId                         
                     AND c.classId = sm.classId
                     AND sm.userId = u.userId 
                     AND c.sessionId = '$sessionId'
                     AND sm.studentId = s.studentId 
                     $condition
                 ORDER BY
                     $orderBy $limit ";
                
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }
   //IF(s.studentMobileNo='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo ,
   public function getstudentRegistrationCount($condition='') {
       
       global $sessionHandler;
       $systemDatabaseManager = SystemDatabaseManager::getInstance(); 
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                     COUNT(DISTINCT sm.mentorshipId) AS totalRecords
                 FROM
                     class c, student s, `user` u, employee e,
                     student_teacher_mentorship sm 
                     LEFT JOIN  sc_student_registration sr ON sm.studentId = sr.studentId AND  sm.classId = sr.classId     
                 WHERE     
                     e.userId = u.userId                         
                     AND c.classId = sm.classId
                     AND sm.userId = u.userId 
                     AND c.sessionId = '$sessionId'
                     AND sm.studentId = s.studentId 
                     $condition";
                       
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }

    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING BUSROUTE LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
      public function getstudentRegistrationDetailsOld($conditions='',$having='',$classIds='',$orderBy='', $limit='') {
         
         global $sessionHandler; 
         
         if($classIds=='') {
           $classIds='0';  
         }
         if($orderBy=='') {
           $orderBy = " registrationDate ASC";  
         }
         
         $query = "SELECT
                        tt.studentName, tt.fatherName, tt.universityRollNo, tt.studentMobileNo,
                        tt.studentEmail, tt.registrationStatus, tt.registrationDate, tt.fatherMobile,
                        tt.landlineNo, tt.parentEmail, tt.permAddress1, tt.mentorName, tt.className,
                        tt.classId, tt.studentId, tt.mentorEmployeeId
                   FROM                                                    
                      (SELECT 
	                        DISTINCT CONCAT(ss.firstName,' ',ss.lastName) AS studentName,
                                ss.fatherName, ss.universityRollNo,
                                IF(IFNULL(sr.studentMobileNo,'')='','---',sr.studentMobileNo) AS studentMobileNo,
                                sr.studentEmail, IF(IFNULL(sr.studentId,'')<>'',1,2) AS registrationStatus,
                                sr.registrationDate, sr.fatherMobile, sr.landlineNo, sr.parentEmail, sr.permAddress1,
                                c.className, c.classId, ss.studentId,
                                IFNULL((SELECT 
                                     DISTINCT employeeId 
                                     FROM 
                                     student_teacher_mentorship sm, employee e 
                                     WHERE 
                                     sm.userId = e.userId AND sm.classId = ss.classId AND sm.studentId = ss.studentId LIMIT 0,1),
                                         (SELECT DISTINCT employeeId FROM employee 
                                          WHERE employeeName LIKE 'Kuldeep Sharma' LIMIT 0,1)) mentorEmployeeId, 
                                IFNULL((SELECT 
                                     DISTINCT CONCAT(employeeName,' (',employeeCode,')') AS mentor 
                                     FROM 
                                     student_teacher_mentorship sm, employee e 
                                     WHERE 
                                     sm.userId = e.userId AND sm.classId = ss.classId AND sm.studentId = ss.studentId LIMIT 0,1),
                                         (SELECT DISTINCT CONCAT(employeeName,' (',employeeCode,')') AS mentor FROM employee 
                                           WHERE employeeName LIKE 'Kuldeep Sharma' LIMIT 0,1)) AS mentorName                             
                       FROM 
                             class c, student ss
                             LEFT JOIN sc_student_registration sr ON ss.studentId = sr.studentId AND ss.classId = sr.classId
                       WHERE	
                            ss.classId = c.classId AND
	                        ss.classId IN ($classIds) 
                            $conditions
                       GROUP BY
                            ss.studentId, ss.classId ) AS tt
                    $having        
                    ORDER BY $orderBy $limit ";
         
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }  
      
      public function getstudentRegistrationCountOld($conditions='',$having='',$classIds='') {
         
         global $sessionHandler;           
         
         if($classIds=='') {
           $classIds='0';  
         }
         $query = "SELECT
                        COUNT(*) AS totalRecords
                   FROM                                                    
                      (SELECT 
                                DISTINCT CONCAT(ss.firstName,' ',ss.lastName) AS studentName,
                                ss.fatherName, ss.universityRollNo,
                                IF(IFNULL(sr.studentMobileNo,'')='','---',sr.studentMobileNo) AS studentMobileNo,
                                sr.studentEmail, IF(IFNULL(sr.studentId,'')<>'',1,2) AS registrationStatus,
                                sr.registrationDate, sr.fatherMobile, sr.landlineNo, sr.parentEmail, sr.permAddress1,
                                c.className, c.classId, ss.studentId,
                                IFNULL((SELECT 
                                     DISTINCT employeeId 
                                     FROM 
                                     student_teacher_mentorship sm, employee e 
                                     WHERE 
                                     sm.userId = e.userId AND sm.classId = ss.classId AND sm.studentId = ss.studentId LIMIT 0,1),
                                         (SELECT DISTINCT employeeId FROM employee 
                                          WHERE employeeName LIKE 'Kuldeep Sharma' LIMIT 0,1)) mentorEmployeeId, 
                                IFNULL((SELECT 
                                     DISTINCT CONCAT(employeeName,' (',employeeCode,')') AS mentor 
                                     FROM 
                                     student_teacher_mentorship sm, employee e 
                                     WHERE 
                                     sm.userId = e.userId AND sm.classId = ss.classId AND sm.studentId = ss.studentId LIMIT 0,1),
                                         (SELECT DISTINCT CONCAT(employeeName,' (',employeeCode,')') AS mentor FROM employee 
                                           WHERE employeeName LIKE 'Kuldeep Sharma' LIMIT 0,1)) AS mentorName                             
                       FROM 
                             class c, sc_student ss
                             LEFT JOIN sc_student_registration sr ON ss.studentId = sr.studentId AND ss.classId = sr.classId
                       WHERE    
                            ss.classId = c.classId AND
                            ss.classId IN ($classIds) 
                            $conditions
                       GROUP BY
                            ss.studentId, ss.classId ) AS tt
                       $having ";
                    
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      } 
      
      public function getEmployeeName($id='') {  
          
          $query = "SELECT
                        DISTINCT CONCAT(IFNULL(employeeName,''),' (',IFNULL(employeeCode,''),')') AS employeeNames
                   FROM       
                        employee 
                   WHERE 
                        employeeId = '$id' ";
                    
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      } 
      
      public function getConfigClass($condition='') {  
          
          $query = "SELECT
                        DISTINCT param, `value`, instituteId
                   FROM       
                        config 
                   WHERE 
                        param IN ('ENABLE_REGISTRATION') 
                        $condition ";
                    
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
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
    
public function getMentorshipDetail($mentorshipId) {
    global $sessionHandler;
    $userId = $sessionHandler->getSessionVariable('UserId');
    $query = "

		 SELECT
				 count(a.mentorshipCommentId) as totalRecords
		 from 
                sc_teacher_mentorship_comments a,
				student_teacher_mentorship b
		  WHERE 
				a.studentId = b.studentId 
		        AND b.mentorshipId = '$mentorshipId' ";
     
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 
public function getStudentMentorshipListDetail ($mentorshipId) {
	
     global $sessionHandler;
	 $userId = $sessionHandler->getSessionVariable('UserId'); //as teacherId is EmployeeId
    
     $query = "SELECT
				  a.mentorshipCommentId, a.classId, a.commentDate, a.comments,
                  IFNULL(CONCAT(e.employeeName,' (',e.employeeCode,')'),'Admin') AS employeeNameCode
	          FROM 
				  student_teacher_mentorship b, 
                  sc_teacher_mentorship_comments a LEFT JOIN employee e ON (e.userId = a.userId)
		      WHERE
				  a.studentId = b.studentId 
                  AND b.mentorshipId = '$mentorshipId'";
      
	  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
} 
 
public function getMentorshipStudentId($mentorshipId) {
		$query = "
           SELECT 
                DISTINCT s.studentId, s.classId, 
                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                IFNULL(s.rollNo,'') AS rollNo, c.className, 
                IF(IFNULL(e.employeeName,'')='','Admin',CONCAT(IFNULL(e.employeeName,''),' (',IFNULL(e.employeeCode,''),')'))   AS employeeName 
			FROM   
                student s, class c, 
                student_teacher_mentorship a LEFT JOIN employee e ON a.userId = e.userId
			WHERE  
                s.studentId = a.studentId AND
                c.classId = s.classId AND
			    a.mentorshipId = $mentorshipId ";
                
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   

}
  public function addComments($studentId, $mentorshipComments, $currentUserId, $classId)  {
	$query="INSERT INTO sc_teacher_mentorship_comments (studentId,comments,userId, classId, commentDate) values ($studentId, '$mentorshipComments', $currentUserId,$classId,NOW())";
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
}
    
 public function deleteMentorship($id) {
     
        $query = "DELETE FROM sc_teacher_mentorship_comments
                  WHERE mentorshipCommentId=$id ";
        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
    public function getStudentFeeDetails($studentId='',$classId='') {
		$query = "
           SELECT 
                DISTINCT frd.studentId, frd.classId,SUM(frd.amount) AS paidAmount 
            FROM   
                fee_receipt_details frd
			WHERE
				frd.studentId ='$studentId' AND
				frd.classId='$classId' AND
               frd.isDelete = 0 AND
               frd.feeType IN(1,4)
			GROUP BY
                 frd.studentId, frd.classId, frd.receiptNo  ";
           
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   

	}        
}

?>
