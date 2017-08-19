<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "role" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class UserRoleManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "UserRoleManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "UserRoleManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (1.07.2008)
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
    
 public function getEmployees($conditions='') {
        
        $query = "SELECT 
                         DISTINCT e.employeeId,
                         e.userId,
                         u.roleId,
                         e.instituteId
                  FROM
                         employee e,`user` u
                  WHERE
                         e.userId=u.userId 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 public function getStudentInfo($conditions='') {
        
        $query = "SELECT 
                         DISTINCT s.userId,
                         u.instituteId
                  FROM
                         student s,`user` u
                  WHERE
                         s.userId=u.userId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
public function getParentInfo($type) {
    
    if($type=='Father'){
      $field=' s.fatherUserId';
    }
    if($type=='Mother'){
      $field=' s.motherUserId';
    }
    if($type=='Guardian'){
      $field=' s.guardianUserId';
    }
    
    $query = "SELECT 
                     DISTINCT $field AS userId,
                     u.instituteId
              FROM
                     student s,`user` u
              WHERE
                     $field=u.userId
              ";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
    
    
 public function getEmployeeCanTeachIn($empId) {
        
        $query = "SELECT 
                         DISTINCT employeeId,
                         instituteId
                  FROM
                         employee_can_teach_in
                  WHERE
                         employeeId=$empId
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

 public function getUsersWhoAreNotEmployees() {
        
        $query = "SELECT 
                         u.userId,u.roleId,u.instituteId
                  FROM
                         `user` u
                  WHERE
                         u.roleId NOT IN (3,4) AND u.userId NOT IN (SELECT DISTINCT userId FROM employee)
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 public function checkUserRoles($conditions='') {
        
        $query = "SELECT 
                         COUNT(*) AS cnt
                  FROM
                         user_role
                         $conditions 
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }        
    
 public function getEmpUserRoles($userId,$roleId='',$instituteId='') {
        if($roleId!=''){
            $roleConditions=' AND roleId='.$roleId;
        }
        if($instituteId!=''){
            $instituteConditions=' AND instituteId='.$instituteId;
        }
        $query = "SELECT 
                         userId,roleId,
                         IFNULL(instituteId,-1) AS instituteId
                  FROM
                         user_role
                  WHERE
                         userId=$userId
                         $roleConditions
                         $instituteConditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 public function updateUserRole($userId,$roleId,$instituteId) { 
     $query="UPDATE user_role SET instituteId=$instituteId WHERE userId=$userId AND roleId=$roleId";
     //echo $query.'<br/>';
     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
 }
 
 public function insertUserRole($userId,$roleId,$instituteId) { 
     $query="INSERT INTO user_role (userId,roleId,instituteId) VALUES ($userId,$roleId,$instituteId)";
     //echo $query.'<br/>';
     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
 }
    

}
// $History: UserRoleManager.inc.php $
?>