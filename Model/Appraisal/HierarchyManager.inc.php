<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class HierarchyManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "HierarchyManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "HierarchyManager" CLASS
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
    
    
    public function getEmployeeList($superiorEmpId,$conditions='', $limit = '', $orderBy=' e.employeeName') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                            e.employeeId, e.employeeName,e.employeeCode,e.employeeAbbreviation,
                            IF(e.isTeaching=1,'YES','NO') AS isTeaching,e.qualification,
                            e.dateOfJoining,
                            d.designationName,br.branchCode,r.roleName,
                            IF(eh.hierarchyId IS NULL,0,1) AS empUsed,
                            (
                              SELECT 
                                    DISTINCT employeeName 
                              FROM 
                                    employee 
                              WHERE 
                                    employeeId=(SELECT DISTINCT superiorEmployeeId FROM employee_hierarchy WHERE subordinateEmployeeId=e.employeeId AND sessionId=$sessionId AND instituteId=$instituteId)
                                    
                            ) AS superiorEmployee
                  FROM 
                            designation d,`user` u,`role` r,`branch` br,
                            employee e
                            LEFT JOIN employee_hierarchy eh ON ( eh.subordinateEmployeeId=e.employeeId AND eh.superiorEmployeeId=$superiorEmpId AND eh.sessionId=$sessionId AND eh.instituteId=$instituteId )
                  WHERE     
                            e.designationId=d.designationId
                            AND e.isActive=1
                            AND e.branchId=br.branchId
                            AND e.instituteId=".$instituteId."
                            AND e.employeeId !=$superiorEmpId
                            AND e.userId=u.userId AND u.roleId=r.roleId
                  $conditions 
                  ORDER BY $orderBy $limit";
        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function checkCyclickRelations($subordinateEmpIds,$superiorEmpId,$instituteId,$sessionId) {
  $query="SELECT 
                COUNT(*) AS cnt
          FROM
               employee_hierarchy
          WHERE
                superiorEmployeeId IN ($subordinateEmpIds)
                AND subordinateEmployeeId=$superiorEmpId 
                AND instituteId=$instituteId
                AND sessionId=$sessionId" ;
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getSuperiorEmployee($supEmpId,$sessionId,$instituteId) {
  $query="SELECT 
                superiorEmployeeId
          FROM
               employee_hierarchy
          WHERE
                subordinateEmployeeId=$supEmpId 
                AND instituteId=$instituteId
                AND sessionId=$sessionId" ;
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function deleteSubordinateEmployees($superiorEmpId,$sessionId,$instituteId,$allSearchedEmployees='') {
  $extraDeleteCondition='';
  if($allSearchedEmployees!='' and $allSearchedEmployees!=0){
    $extraDeleteCondition =" AND subordinateEmployeeId IN ( $allSearchedEmployees ) ";
  }  
  $query="DELETE 
          FROM
               employee_hierarchy
          WHERE
                superiorEmployeeId=$superiorEmpId 
                AND instituteId=$instituteId
                AND sessionId=$sessionId
                $extraDeleteCondition 
          " ;
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteSubordinateEmployeesMapping($subordinateEmpId,$sessionId,$instituteId) {
  $query="DELETE 
          FROM
               employee_hierarchy
          WHERE
                subordinateEmployeeId=$subordinateEmpId 
                AND instituteId=$instituteId
                AND sessionId=$sessionId" ;
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}

public function doEmployeeHierarchy($insertString) {
  $query="INSERT INTO
               employee_hierarchy (superiorEmployeeId,subordinateEmployeeId,sessionId,instituteId)
          VALUES $insertString" ;
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}    
  
}
// $History: HierarchyManager.inc.php $
?>