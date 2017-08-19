<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class AuthorizeLeaveManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "AuthorizeLeaveManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AuthorizeLeaveManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
    public function addEmployeeLeaveComments($leaveId,$reason,$reasonDate,$employeeId,$leaveSessionId) {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO
                         leave_comments  (leaveId,employeeId,reason,reasonDate,instituteId,leaveSessionId)
                  VALUES ($leaveId,$employeeId,'".add_slashes(trim($reason))."','$reasonDate',$instituteId,$leaveSessionId )";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    public function editEmployeeLeave($leaveId,$leaveStatus,$employeeApprovingField,$leaveSessionId) {
     
        $query = "UPDATE
                        leave_employee   
                  SET
                        leaveStatus=$leaveStatus
                        $employeeApprovingField
                  WHERE
                        leaveId=$leaveId 
                        AND leaveSessionId = $leaveSessionId ";
                        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getLeavesForAuthorizeList($employeeId,$conditions='', $limit = '', $orderBy=' employeeCode') {
           global $sessionHandler;
           $instituteId=$sessionHandler->getSessionVariable('InstituteId');
            
           if($employeeId!='') {
              $cond = " AND laa1.firstApprovingEmployeeId=$employeeId "; 
           } 
        
           $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');   
           $query = "SELECT
                        l.leaveId,     
                        l.employeeId,
                        e.employeeName,
                        e.employeeCode,
                        l.leaveFromDate,
                        l.leaveToDate,
                        l.leaveStatus,
			lt.leaveTypeName,
			IFNULL(l.documentAttachment,'') AS documentAttachment,
			IF(l.leaveFormat='1','Full Day','Half Day') AS leaveDay,
			IFNULL(l.substituteEmployee,'') AS substituteEmployee,
	 (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.firstApprovingEmployeeId) AS firstEmployee,
                        laa1.firstApprovingEmployeeId AS auth
                    FROM
                        leave_type lt,
                        employee e, 
                        leave_employee l
                        INNER JOIN  leave_approving_authority laa1 ON ( laa1.employeeId=l.employeeId AND laa1.leaveTypeId=l.leaveTypeId AND laa1.leaveSessionId = l.leaveSessionId )
                    WHERE
                         l.leaveTypeId=lt.leaveTypeId
                         AND l.employeeId=e.employeeId
                         AND e.instituteId=$instituteId
                         AND e.isActive=1
                         AND lt.instituteId= $instituteId
                         $cond AND l.leaveStatus=0
                         $conditions ";
                         
          if($employeeId!='') {
             $cond = " AND laa2.secondApprovingEmployeeId=$employeeId ";
           } 
                         
           if($leaveAuthorizersId==2) {                                        
             $query .= "UNION
                       SELECT
                        l.leaveId,
                        l.employeeId,
                        e.employeeName,
                        e.employeeCode,
                        l.leaveFromDate,
                        l.leaveToDate,
                        l.leaveStatus,
                        lt.leaveTypeName,
			IFNULL(l.documentAttachment,'') AS documentAttachment,
			IF(l.leaveFormat='1','Full Day','Half Day') AS leaveDay,
			IFNULL(l.substituteEmployee,'') AS substituteEmployee,
 (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.secondApprovingEmployeeId) AS secondEmployee,
                        laa2.secondApprovingEmployeeId AS auth
                    FROM
                        leave_type lt,
                        employee e, 
                        leave_employee l
                        INNER JOIN  leave_approving_authority laa2 ON ( laa2.employeeId=l.employeeId AND laa2.leaveTypeId=l.leaveTypeId AND laa2.leaveSessionId = l.leaveSessionId )
                    where
                         l.leaveTypeId=lt.leaveTypeId
                         AND l.employeeId=e.employeeId
                         AND lt.instituteId= $instituteId
                         AND e.instituteId=$instituteId
                         AND e.isActive=1
                         $cond AND l.leaveStatus=1
                         $conditions ";
           }
           $query .= "ORDER BY $orderBy 
                     $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
 
 public function getEmployeeInformation($userId) {
        
        $query = "SELECT 
                         employeeId,
                         employeeName,
                         employeeCode
                   FROM 
                         employee
                   WHERE
                         userId=$userId ";
                         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
  public function checkAuthorizationData($sourceEmployeeId,$leaveType,$leaveSessionId) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
            
        
        $query =  "SELECT 
                         *
                   FROM 
                         leave_approving_authority
                   WHERE
                         employeeId=$sourceEmployeeId
                         AND leaveTypeId=$leaveType
                         AND leaveSessionId = $leaveSessionId
                         AND instituteId=$instituteId";
                         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
     
  public function checkAuthorizationComments($leaveId,$employeeId,$leaveSessionId) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
              
      
        $query =  "SELECT 
                         reason,
                         reasonDate
                   FROM 
                         leave_comments
                   WHERE
                         leaveId=$leaveId
                         AND employeeId=$employeeId
                         AND instituteId=$instituteId 
                         AND leaveSessionId=$leaveSessionId";
                         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkLeaveRecords($sourceEmployeeId,$leaveType,$currYear,$leaveSessionId) {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query =  "SELECT  
                        IFNULL(SUM(IF(YEAR(leaveFromDate)!=YEAR(leaveToDate),(IF(YEAR(leaveFromDate)=$currYear,(DATEDIFF('".$currYear."-12-31',leaveFromDate)+1),DATEDIFF(leaveToDate,'".$currYear."-01-01')+1)),DATEDIFF(leaveToDate,leaveFromDate)+1)),0) AS leavesTaken 
                   FROM 
                        leave_employee 
                   WHERE
                        employeeId=$sourceEmployeeId
                        AND (YEAR(leaveFromDate)=$currYear OR YEAR(leaveToDate)=$currYear)
                        AND leaveStatus=2
                        AND leaveSessionId=$leaveSessionId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
     public function checkLeaveLimit($sourceEmployeeId,$leaveType) {
        
        $query =  "SELECT 
                         lsm.leaveValue
                   FROM 
                         leave_set_mapping lsm,
                         leave_set_employee lse
                   WHERE
                         lsm.leaveSetId=lse.leaveSetId
                         AND lse.employeeId=$sourceEmployeeId
                         AND lsm.leaveTypeId=$leaveType ";
                         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }              
   
  
}
// $History: AuthorizeLeaveManager.inc.php $
?>
