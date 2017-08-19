<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "EmployeeLeaveCarryForward" TABLE
//
// Author :Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class EmployeeLeaveCarryForwardManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "EmployeeLeaveCarryForwardManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "EmployeeLeaveCarryForwardManager" CLASS
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
 
 
   public function addEmployeeCarryForwad($fieldValue) {
     
        $query = "INSERT INTO `leave_carry_forward` 
                  (leaveSessionId,employeeId,leaveTypeId,leavesAdded)
                  VALUES $fieldValue ";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }   
    
    
    public function deleteEmployeeCarryForwad($condition='') {
     
        $query = "DELETE FROM `leave_carry_forward` WHERE $condition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    } 
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Carry Forward Employee LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getEmployeeCarryForwardList($conditions='',$orderBy='',$limit='',$nextLeaveSessionId='0') {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');    
        if($leaveAuthorizersId=='') {
           $leaveAuthorizersId=1;  
        }
        
        $query = "SELECT 
                        DISTINCT     
                        lt.leaveTypeId, ls.leaveSetId, ls.leaveSetName, lt.leaveTypeName, lse.employeeId, lsm.leaveValue, lse.leaveSessionId,lt.leaveFormat,IF(lt.leaveFormat='1','Full Day','Half Day') AS leaveDay,
                        IFNULL((SELECT  
                                    SUM(TO_DAYS(leaveToDate) - TO_DAYS(leaveFromDate)+1) AS leaveTaken
                                FROM 
                                    leave_employee le 
                                WHERE
                                     le.employeeId = lse.employeeId AND 
                                     le.leaveSessionId = lsm.leaveSessionId  AND
                                     le.leaveTypeId = lt.leaveTypeId AND
                                     le.leaveStatus = $leaveAuthorizersId AND
              			     lt.leaveFormat = 1 
                                GROUP BY
                                     le.leaveSessionId, le.employeeId, le.leaveTypeId ),0) +
IFNULL((SELECT  

                                    SUM(TO_DAYS(leaveToDate) - TO_DAYS(leaveFromDate)+1)/2 AS leaveTaken

                                FROM 

                                    leave_employee le 

                                WHERE

                                     le.employeeId = lse.employeeId AND 

                                     le.leaveSessionId = lsm.leaveSessionId  AND

                                     le.leaveTypeId = lt.leaveTypeId AND

                                     le.leaveStatus = 2 AND

				     lt.leaveFormat = 2

                                GROUP BY

                                     le.leaveSessionId, le.employeeId, le.leaveTypeId ),0) AS taken,

                        IFNULL((SELECT  
                                     leavesAdded 
                                FROM 
                                     `leave_carry_forward` lcf 
                                WHERE
                                     lcf.employeeId = lse.employeeId AND 
                                     lcf.leaveSessionId = lsm.leaveSessionId  AND
                                     lcf.leaveTypeId = lt.leaveTypeId
                                GROUP BY
                                     lcf.leaveSessionId, lcf.employeeId, lcf.leaveTypeId ),0) AS carryForward,
                        IFNULL((SELECT                                                   
                                     employeeId 
                                FROM 
                                     `leave_carry_forward` nextlcf 
                                WHERE
                                     nextlcf.employeeId = lse.employeeId AND 
                                     nextlcf.leaveSessionId = $nextLeaveSessionId AND
                                     nextlcf.leaveTypeId = lt.leaveTypeId
                                GROUP BY
                                     nextlcf.leaveSessionId, nextlcf.employeeId, nextlcf.leaveTypeId),-1) AS carryForwardStatus
                  FROM 
                        leave_set_employee lse, 
                        leave_set ls, leave_set_mapping lsm, leave_type lt
                  WHERE
                        lse.leaveSetId = ls.leaveSetId  AND
                        ls.isActive=1 AND 
                        lsm.leaveSetId = lse.leaveSetId AND
                        lsm.leaveSessionId = lse.leaveSessionId AND
                        lsm.leaveTypeId = lt.leaveTypeId AND
                        ls.instituteId = lt.instituteId AND
                        lsm.instituteId = lt.instituteId AND
                        lt.isActive = 1 AND
                        lt.carryForward = 1  AND
                        ls.instituteId = $instituteId 
                  $conditions
                  ORDER BY
                        lse.leaveSessionId, lse.employeeId, lt.leaveTypeId
                  $limit ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
}
// $History: EmployeeLeaveSetMappingManager.inc.php $
?>
