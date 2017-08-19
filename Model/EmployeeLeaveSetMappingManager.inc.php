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

class EmployeeLeaveSetMappingManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "EmployeeLeaveSetMappingManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "EmployeeLeaveSetMappingManager" CLASS
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
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getEmployeeInfo($conditions='') {
     
        global $sessionHandler;
        $query = "SELECT 
                        employeeId,
                        CONCAT_WS(' ',IFNULL(employeeName,''),IFNULL(middleName,''),IFNULL(lastName,'')) AS employeeName, 
                        employeeCode 
                  FROM 
                        employee
                  WHERE
                        instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getEmployeeLeaveSetMapping($conditions='') {
        $query = "SELECT 
                        *
                  FROM 
                        leave_set_employee
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getEmployeeLeaveSetMappingData($conditions='') {
        $query = "SELECT 
                        m.*,
                        e.employeeId,
                        e.employeeCode,
                        e.employeeName
                  FROM 
                        leave_set_employee m,employee e
                  WHERE
                        m.employeeId=e.employeeId
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkEmployeeAuthorzerUsage($employeeId) {
        $query = "SELECT 
                       COUNT(*) AS cnt
                  FROM 
                        leave_approving_authority
                  WHERE
                        employeeId=$employeeId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

    public function deleteEmployeeLeaveSetMapping($deleteCondition) {
        if(trim($deleteCondition)==''){
            return false;
        }
     
        $query = "DELETE 
                  FROM 
                         leave_set_employee   
                  $deleteCondition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function doEmployeeLeaveSetMapping($insertCondition) {
        if(trim($insertCondition)==''){
            return false;
        }
     
        $query = "INSERT INTO
                         leave_set_employee  (leaveSessionId,employeeId,leaveSetId)
                         VALUES $insertCondition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function editEmployeeLeaveSetMapping($mappingId,$leaveSet) {
        $query = "UPDATE 
                        leave_set_employee
                  SET
                        leaveSetId=$leaveSet
                  WHERE
                        employeeLeaveSetMappingId=$mappingId";
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
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
    
    public function getEmployeeLeaveSetMappingList($conditions='', $limit = '', $orderBy=' emp.employeeCode') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         lse.employeeLeaveSetMappingId,
                         emp.employeeId,
                         emp.employeeCode,
                         emp.employeeName,
                         ls.leaveSetId,
                         ls.leaveSetName,
                         s.leaveSessionId,
                             IFNULL((SELECT 
                                            DISTINCT employeeId 
                                      FROM 
                                            leave_employee le 
                                      WHERE 
                                            le.employeeId = emp.employeeId AND le.leaveSessionId=s.leaveSessionId),-1) AS leaveEmployeeId 
                   FROM 
                         leave_session s, leave_set ls,employee emp,leave_set_employee lse
                   WHERE
                         ls.leaveSetId=lse.leaveSetId
                         AND lse.employeeId=emp.employeeId
                         AND ls.instituteId=$instituteId
                         AND emp.instituteId=$instituteId
                         AND ls.isActive=1
                         AND emp.isActive=1
                         AND s.leaveSessionId =  lse.leaveSessionId
                         $conditions 
                   ORDER BY $orderBy 
                   $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalEmployeeLeaveSetMapping($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                         leave_session s, leave_set ls,employee emp,leave_set_employee lse
                   WHERE
                         ls.leaveSetId=lse.leaveSetId
                         AND lse.employeeId=emp.employeeId
                         AND ls.instituteId=$instituteId
                         AND emp.instituteId=$instituteId
                         AND ls.isActive=1
                         AND emp.isActive=1
                         AND s.leaveSessionId =  lse.leaveSessionId
                         $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  


    public function checkEmployeeLeaveSetMappingUsage($conditions='') {
        $query = "SELECT 
                        COUNT(le.leaveTypeId) AS cnt
                  FROM 
                        leave_set_employee l,leave_set_mapping m,leave_employee le
                  WHERE
                        l.leaveSetId=m.leaveSetId
                        AND m.leaveTypeId=le.leaveTypeId
                        AND l.employeeId=le.employeeId
                        AND l.leaveSessionId = le.leaveSessionId  
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

public function getEmployeeList($conditions='', $limit = '', $orderBy=' employeeCode') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         employeeId,
                         employeeName,
                         employeeCode
                   FROM 
                         employee
                   WHERE
                         instituteId=$instituteId
                         AND isActive=1
                         AND (employeeCode IS NOT NULL AND employeeCode!='')
                         $conditions 
                   ORDER BY $orderBy 
                   $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function getTotalEmployees($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         COUNT(*) AS totalRecords
                   FROM 
                         employee
                   WHERE
                         instituteId=$instituteId
                         AND isActive=1
                         AND (employeeCode IS NOT NULL AND employeeCode!='')
                         $conditions 
                   ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }           
  
}
// $History: EmployeeLeaveSetMappingManager.inc.php $
?>