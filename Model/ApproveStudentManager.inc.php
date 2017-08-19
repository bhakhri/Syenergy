<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ApproveStudentManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ApproveStudentManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ApproveStudentManager" CLASS
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

    public function getAdmApplList($conditions='', $limit = '', $orderBy=' studentName') {
     
         global $sessionHandler;
         $sessionId=$sessionHandler->getSessionVariable('SessionId');
         $instituteId=$sessionHandler->getSessionVariable('InstituteId');
         
         $query = "SELECT 
                        s.studentId,
                        s.userId,
                        CONCAT(IFNULL(s.firstName,''),' ', IFNULL(s.lastName,'')) AS studentName,
                        s.phNo,s.emailId,s.address,s.isApproved,
                        c.className
                  FROM 
                        admappl_student_information s,class c
                  WHERE 
                        s.classId=c.classId 
                        $conditions 
                        ORDER BY $orderBy 
                        $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

     
    public function getTotalAdmAppl($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        admappl_student_information s,class c
                  WHERE 
                        s.classId=c.classId
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

    public function getUserIdOfStudent($studentId) {
    
        $query = "SELECT 
                        studentId,userId 
                  FROM 
                        admappl_student_information
                  WHERE 
                        studentId=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
   public function changeStudentRole($userId) {
    
        $query = "UPDATE 
                        `user`
                  SET
                        roleId=4
                  WHERE 
                        userId=$userId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
   }
   
   public function copyDataToUserRole($userId) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "INSERT INTO 
                             user_role 
                               (userId,roleId,defaultRoleId,instituteId)
                             VALUES ($userId,4,4,$instituteId)
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
   }
   
    public function copyStudentData($studentId,$userId) {
        global $sessionHandler;
        $query = "INSERT INTO 
                             student 
                               (userId,classId,firstName,lastName,studentMobileNo,studentEmail,permAddress1,permCountryId,permStateId,permCityId,permPinCode)
                  SELECT
                         $userId,classId,firstName,lastName,phNo,emailId,address,countryId,stateId,cityId,pinNo
                  FROM
                         admappl_student_information
                  WHERE
                         studentId=$studentId AND userId=$userId
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
   }
   
   public function updateAdmApplData($studentId,$mode) {
    
        $query = "UPDATE 
                        admappl_student_information
                  SET
                        isApproved=$mode
                  WHERE 
                        studentId=$studentId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
   }
   
   public function checkStudentStatus($studentId) {
    
        $query = "SELECT 
                        studentId,isApproved,emailId 
                  FROM 
                        admappl_student_information
                  WHERE 
                        studentId=$studentId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
  
}


?>