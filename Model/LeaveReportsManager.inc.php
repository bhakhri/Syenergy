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

class LeaveReportsManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "LeaveReportsManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "LeaveReportsManager" CLASS
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
    
    public function checkLeaveRecords($sourceEmployeeId,$leaveType,$currYear) {
        
        $query =  "SELECT  
                        IFNULL(SUM(DATEDIFF(leaveToDate,IF(YEAR(leaveFromDate)=$currYear,leaveFromDate,'".$currYear."-01-01'))+1),0) AS leavesTaken 
                   FROM 
                        leave_employee 
                   WHERE
                        employeeId=$sourceEmployeeId
                        AND YEAR(leaveToDate)=$currYear
                        AND leaveStatus=2
                   ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------       
    
    public function getLeavesTakenList($conditions='', $limit = '', $orderBy=' e.employeeCode') {
     
        global $sessionHandler;  
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        e.employeeName,
                        e.employeeCode,
                        lt.leaveTypeName,
                        l.leaveFromDate,
                        l.leaveToDate,
                        l.leaveStatus,        
                        (datediff(l.leaveToDate,l.leaveFromDate)+1) AS noOfDays
                  FROM
                        employee e,leave_type lt,
                        leave_employee l
                  WHERE
                        e.employeeId=l.employeeId
                        AND l.leaveTypeId=lt.leaveTypeId
                        AND l.leaveStatus NOT IN (3,4) 
                        AND lt.instituteId = $instituteId
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalLeavesTaken($conditions='') {
        
        global $sessionHandler;  
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');    
        if($leaveAuthorizersId=='') {
           $leaveAuthorizersId=1;  
        }
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords
                  FROM
                        employee e,leave_type lt,
                        leave_employee l
                  WHERE
                        e.employeeId=l.employeeId
                        AND l.leaveTypeId=lt.leaveTypeId
                        AND lt.instituteId = $instituteId
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

public function getLeavesHistoryList($conditions='', $limit = '', $orderBy=' e.employeeCode') {
    
        global $sessionHandler;  
        $instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
    
        $query = "SELECT
                        e.employeeName,
                        e.employeeCode,
                        lt.leaveTypeName,
                        l.leaveId,
                        l.leaveFromDate,
                        l.leaveToDate,
                        l.leaveStatus,
                        (datediff(l.leaveToDate,l.leaveFromDate)+1) AS noOfDays
                  FROM
                        employee e,leave_type lt,
                        leave_employee l
                  WHERE
                        e.employeeId=l.employeeId
                        AND l.leaveTypeId=lt.leaveTypeId
                        AND lt.instituteId = $instituteId     
                        $conditions 
                 ORDER BY $orderBy 
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
 public function getTotalLeavesHistory($conditions='') {
    
        global $sessionHandler;  
        $instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords
                  FROM
                        employee e,leave_type lt,
                        leave_employee l
                  WHERE
                        e.employeeId=l.employeeId
                        AND l.leaveTypeId=lt.leaveTypeId
                        AND lt.instituteId = $instituteId     
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

public function getLeavesHistoryGraphData($conditions='') {
    
        global $sessionHandler;  
        $instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
     
    
        $query = "SELECT 
                        t.leaveStatus,
                        SUM(t.noOfDays) AS totalDays,
                        t.leaveTypeName,
                        t.leaveTypeId
                  FROM
                       (
                         SELECT
                               l.leaveStatus,
                               lt.leaveTypeName,
                               l.leaveId,
                               l.leaveFromDate,
                               l.leaveToDate,
                               l.leaveTypeId,
                               (datediff(l.leaveToDate,l.leaveFromDate)+1) AS noOfDays
                         FROM
                               employee e,leave_type lt,
                               leave_employee l
                         WHERE
                               e.employeeId=l.employeeId
                               AND l.leaveTypeId=lt.leaveTypeId
                               AND lt.instituteId = $instituteId    
                               $conditions
                         ) AS t
                  GROUP BY t.leaveStatus,t.leaveTypeId
                  ORDER BY t.leaveStatus
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }            


    public function getLeavesAnalysisList($conditions1='',$conditions2='', $limit = '', $orderBy=' e.employeeCode') {
        
        global $sessionHandler;  
        $instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
        
        $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');    
        if($leaveAuthorizersId=='') {
           $leaveAuthorizersId=1;  
        }
    
     
        $query = "SELECT
                        e.employeeName,
                        e.employeeCode,
                        lt.leaveTypeName,  
                        SUM(TO_DAYS(leaveToDate) - TO_DAYS(leaveFromDate)+1) AS noOfDays   
                  FROM
                        employee e,leave_type lt,
                        leave_employee l
                  WHERE
                        e.employeeId=l.employeeId
                        AND l.leaveTypeId=lt.leaveTypeId
                        AND lt.instituteId=$instituteId
                        AND l.leaveStatus IN ($leaveAuthorizersId)
                        $conditions1 
                  GROUP BY l.leaveSessionId,l.leaveTypeId
                  $conditions2
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }      
    
}
// $History: LeaveReportsManager.inc.php $
?>