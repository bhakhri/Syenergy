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

class ApplyLeaveManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ApplyLeaveManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ApplyLeaveManager" CLASS
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
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getEmployeeLeaveTypes($conditions='') {
     
        global $sessionHandler;
        $query = "SELECT 
                        e.employeeId,
                        lt.leaveTypeId,
                        lt.leaveTypeName 
                  FROM 
                        employee e,leave_type lt, leave_set_employee lse, leave_set_mapping lsm
                  WHERE
                        e.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND lt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        AND e.employeeId=lse.employeeId
                        AND lse.leaveSetId=lsm.leaveSetId
                        AND lsm.leaveTypeId=lt.leaveTypeId
                        AND lt.isActive=1
                        AND lsm.instituteId = lt.instituteId
                        AND lsm.leaveSessionId = lse.leaveSessionId
                        $conditions
                  ORDER BY lt.leaveTypeName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getEmployeeLeaveSetMapping($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                        lsm.*
                  FROM
                        leave_set_mapping lsm,leave_set_employee lse,leave_set ls
                  WHERE
                        lse.leaveSessionId = lsm.leaveSessionId
                        AND lse.leaveSetId=lsm.leaveSetId
                        AND lse.leaveSetId=ls.leaveSetId
                        AND ls.isActive=1
                        AND lsm.instituteId=ls.instituteId
                        AND ls.instituteId=$instituteId
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
    
    public function getEmployeeLeaveInfo($conditions='') {
        $query = "SELECT 
                        *
                  FROM 
                        leave_employee
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
        
        
    public function addEmployeeLeave($leaveSessionId,$employeeId,$leaveTypeId,$applicateDate,$leaveFromDate,$leaveToDate,$leaveStatus,$substituteEmployee,$leaveFormat) {
        
        $query = "INSERT INTO
                         leave_employee(leaveSessionId,employeeId,leaveTypeId,applicateDate,leaveFromDate,leaveToDate,leaveStatus,substituteEmployee,leaveFormat)
                  VALUES ($leaveSessionId,$employeeId,$leaveTypeId,'$applicateDate','$leaveFromDate','$leaveToDate',$leaveStatus,'$substituteEmployee','$leaveFormat')";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function addEmployeeLeaveComments($leaveId,$employeeId,$reason,$reasonDate,$leaveSessionId) {
        
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
                                                                                                             
        $query = "INSERT INTO
                         leave_comments  (leaveId,employeeId,reason,reasonDate,instituteId,leaveSessionId)
                  VALUES ($leaveId,$employeeId,'".add_slashes(trim($reason))."','$reasonDate',$instituteId,$leaveSessionId )";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function editEmployeeLeave($leaveId,$leaveType,$applyDate,$fromDate,$toDate,$substituteEmployee,$leaveFormat) {
     
        $query = "UPDATE
                        leave_employee   
                  SET
                        leaveTypeId=$leaveType,
                        applicateDate='$applyDate',
                        leaveFromDate='$fromDate',
                        leaveToDate='$toDate',
			            substituteEmployee='$substituteEmployee',
			            leaveFormat='$leaveFormat'
                  WHERE
                        leaveId=$leaveId";
                        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function editEmployeeLeaveComments($leaveId,$employeeId,$leaveReason,$reasonDate,$leaveSessionId) {
     
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
                                                                                        
        $query = "UPDATE
                        leave_comments  
                  SET    
                        reason='".add_slashes(trim($leaveReason))."',
                        reasonDate='$reasonDate'
                  WHERE
                        leaveId=$leaveId
                        AND employeeId=$employeeId
                        AND instituteId=$instituteId
                        AND leaveSessionId = $leaveSessionId
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    public function cancelAppliedLeave($condition) {
        
        if($condition==''){
            return false;
        } 
        $query = "UPDATE
                        leave_employee  
                  SET    
                        leaveStatus=4
                  $condition
                  ";
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
    

    public function getEmployeeLeavesList($conditions='', $limit = '', $orderBy=' emp.employeeCode') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');    
        if($leaveAuthorizersId=='') {
           $leaveAuthorizersId=1;  
        }
        
        $query = "SELECT 
                         l.leaveId, l.applicateDate, l.leaveFromDate, l.leaveToDate, l.leaveStatus,l.firstApprovingEmployeeId, l.secondApprovingEmployeeId,  
                         emp.employeeId, emp.employeeCode, emp.employeeName, lt.leaveTypeId,  lt.leaveTypeName,
                         l.leaveFormat, IFNULL(l.documentAttachment,'') AS documentAttachment, 
                         IF(l.leaveFormat='1','Full Day','Half Day') AS leaveDay,
			IFNULL(l.substituteEmployee,'') AS substituteEmployee,
	 (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.firstApprovingEmployeeId) AS firstEmployee,
                         (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.secondApprovingEmployeeId) AS secondEmployee,
                         
                         IFNULL((SELECT  
                                    SUM(TO_DAYS(leaveToDate) - TO_DAYS(leaveFromDate)+1) AS leaveTaken
                                FROM 
                                    leave_employee le 
                                WHERE
                                     le.employeeId = l.employeeId AND 
                                     le.leaveSessionId = l.leaveSessionId  AND
                                     le.leaveTypeId = l.leaveTypeId AND
                                     le.leaveStatus = $leaveAuthorizersId AND
                                     le.leaveFormat = 1 
                                GROUP BY
                                     le.leaveSessionId, le.employeeId, le.leaveTypeId ),0) +
                         IFNULL((SELECT  
                                    SUM(TO_DAYS(leaveToDate) - TO_DAYS(leaveFromDate)+1)/2 AS leaveTaken
                                FROM 
                                    leave_employee le 
                                WHERE
                                     le.employeeId = l.employeeId AND 
                                     le.leaveSessionId = l.leaveSessionId  AND
                                     le.leaveTypeId = l.leaveTypeId AND
                                     le.leaveStatus = $leaveAuthorizersId AND
                                     le.leaveFormat = 2 
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
                                     lcf.leaveSessionId, lcf.employeeId, lcf.leaveTypeId ),0)+IFNULL(lsm.leaveValue,0) AS allowed
                   FROM 
                         leave_type lt, employee emp, leave_employee l, leave_set ls,
                         leave_set_mapping lsm, leave_set_employee lse
                   WHERE
                        lse.leaveSetId = ls.leaveSetId  AND
                        ls.isActive=1 AND 
                        lsm.leaveSetId = lse.leaveSetId AND
                        lsm.leaveSessionId = lse.leaveSessionId AND
                        lsm.leaveSessionId = l.leaveSessionId AND
                        lsm.leaveTypeId = lt.leaveTypeId AND
                        lse.employeeId = l.employeeId AND
                        ls.instituteId = lt.instituteId AND
                        lsm.instituteId = lt.instituteId AND
                        lt.isActive = 1 AND
                        l.employeeId=emp.employeeId AND
                        l.leaveTypeId=lt.leaveTypeId  AND
                        lt.instituteId=$instituteId AND
                        emp.instituteId=$instituteId
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
    public function getTotalEmployeeLeaves($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       COUNT(*) AS totalRecords 
                  FROM 
                      (SELECT 
                             l.leaveId,
                             l.applicateDate,
                             l.leaveFromDate,
                             l.leaveToDate,
                             l.leaveStatus,
                             emp.employeeId,
                             emp.employeeCode,
                             emp.employeeName,
                             lt.leaveTypeId,
                             lt.leaveTypeName,
                             l.leaveFormat, l.substituteEmployee, l.documentAttachment,    
                             (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.firstApprovingEmployeeId) AS firstEmployee,
                             (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.secondApprovingEmployeeId) AS secondEmployee 
                       FROM 
                             leave_type lt,employee emp,leave_employee l
                       WHERE
                             l.employeeId=emp.employeeId
                             AND l.leaveTypeId=lt.leaveTypeId
                             AND lt.instituteId=$instituteId
                             AND emp.instituteId=$instituteId
                             $conditions) AS tt";
                             
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
  public function getEmployeeLeavesComments($conditions='', $limit = '', $orderBy=' lc.reason') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         l.leaveId,
                         lc.reason,
                         lc.reason,
                         emp.employeeId
                   FROM 
                         employee emp,leave_employee l, leave_comments lc
                   WHERE
                         l.employeeId=emp.employeeId
                         AND l.leaveId=lc.leaveId 
                         AND l.leaveSessionId = lc.leaveSessionId
                         AND lc.instituteId=$instituteId
                         AND emp.instituteId=$instituteId
                         $conditions 
                   ORDER BY $orderBy 
                   $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
  public function getEmployeeLeavesCommentsFromAuthorizers($conditions1,$conditions2,$leaveSessionId) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query =  "SELECT 
                         lc1.reason AS reason1,
                         lc2.reason AS reason2          
                   FROM 
                         leave_approving_authority laa
                         LEFT JOIN leave_comments lc1 ON ( lc1.employeeId=laa.firstApprovingEmployeeId $conditions1 )
                         LEFT JOIN leave_comments lc2 ON ( lc2.employeeId=laa.secondApprovingEmployeeId $conditions2 )
                  WHERE
                       laa.instituteId = $instituteId AND   
                       laa.leaveSessionId = $leaveSessionId";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }          
     public function updateAttachmentFilenameInLeave($id, $fileName) {
       // echo $id;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('leave_employee',
        array('documentAttachment'),
        array($fileName), "leaveId=$id" );
    }
    
    public function deleteLeaveFailedUpload($leaveId) {
        global $sessionHandler;

        //First Delete the records into notice_visible_to_role table
        $query = "DELETE FROM leave_comments WHERE leaveId=$leaveId ";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($ret===false){
           return false;
        }
        //Then delete records from notice table
        $query = "DELETE FROM leave_employee WHERE leaveId=$leaveId ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
    
    public function getSearchList($fieldName='',$tableName='',$condition='') {
       global $sessionHandler;

       $query = "SELECT $fieldName FROM $tableName $condition ";
       
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
    public function getCodeList($fieldName='',$tableName='',$condition='') {
       global $sessionHandler;

       $query = "SELECT $fieldName FROM $tableName $condition ";
       
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
   public function checkLeaveExists($leaveId){

        $query="SELECT documentAttachment
                FROM leave_employee
                WHERE leaveId=".$leaveId;

          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
public function updateAttachmentFilenameInNotice($id, $fileName) {
       // echo $id;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('leave_employee',
        array('documentAttachment'),
        array($fileName), "leaveId=$id" );
    }

}
// $History: ApplyLeaveManager.inc.php $
?>
