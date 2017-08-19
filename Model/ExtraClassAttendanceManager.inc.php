<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the Subject  Module
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

//Main responsible for operation in subject table in database

class ExtraClassAttendanceManager {
	private static $instance = null;
	
	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
    
    public function addExtraClassAttendance() {
     
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $userId=$sessionHandler->getSessionVariable('UserId');

        $insertDate = date('Y-m-d');
        return SystemDatabaseManager::getInstance()->runAutoInsert('extra_class', 
        array('classId','groupId','subjectId','employeeId','periodId','fromDate',
              'toDate','userId','insertDate','substituteEmployeeId','comments','timeTableLabelId'), 
        array($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['employeeId'],
              $REQUEST_DATA['periodId'],$REQUEST_DATA['forDate'],$REQUEST_DATA['forDate'],$userId,$insertDate,
              $REQUEST_DATA['substituteEmployeeId'], $REQUEST_DATA['commentTxt'],$REQUEST_DATA['timeTableLabelId'] ));
    }
    

    public function editExtraClassAttendance($id) {
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $userId=$sessionHandler->getSessionVariable('UserId');
        $insertDate = date('Y-m-d');
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('extra_class', 
        array('classId','groupId','subjectId','employeeId','periodId','fromDate',
              'toDate','userId','insertDate','substituteEmployeeId','comments','timeTableLabelId'), 
        array($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['employeeId'],
              $REQUEST_DATA['periodId'],$REQUEST_DATA['forDate1'],$REQUEST_DATA['forDate1'],$userId,$insertDate,
              $REQUEST_DATA['substituteEmployeeId'], $REQUEST_DATA['commentTxt'] ,$REQUEST_DATA['timeTableLabelId'] ), "extraAttendanceId=$id");
    }
    
    public function deleteExtraClassAttendance($id='') {
        
        global $REQUEST_DATA;
        
        $query = "DELETE FROM extra_class WHERE extraAttendanceId='$id'";
        
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    


    public function getExtraClassAttendanceList( $conditions='', $limit = '', $orderBy='className') {
        
        global $REQUEST_DATA;
        
        $query = "SELECT 
                        e.timeTableLabelId,  e.timeTableLabelId, e.extraAttendanceId ,e.classId ,e.groupId,
                        e.subjectId ,e.employeeId, e.periodId, e.fromDate, e.toDate ,e.userId ,e.insertDate, 
                        IFNULL(e.substituteEmployeeId,'') AS substituteEmployeeId, 
                        IFNULL(e.comments,'') AS comments, 
                        CONCAT(e1.employeeName, ' (',e1.employeeCode,')') AS employeeName, 
                        IFNULL(CONCAT(e2.employeeName, ' (',e2.employeeCode,')'),'') AS substituteEmployee,
                        sub.subjectCode, sub.subjectName, c.className, grp.groupName, 
                        CONCAT(p.periodNumber,' (',p.startTime,p.startAmPm,' to ',p.endTime,p.endAmPm,')') AS periodTime
                   FROM 
                         `subject` sub, `group` grp, `period` p, class c, employee e1, 
                         extra_class e LEFT JOIN employee e2 ON e.substituteEmployeeId = e2.employeeId   
                   WHERE
                        e.subjectId = sub.subjectId AND
                        e.groupId = grp.groupId AND
                        e.classId = c.classId AND
                        e.periodId = p.periodId AND
                        e.employeeId = e1.employeeId
                        $conditions                   
                   ORDER BY 
                        $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getEmployeeTimeTable($fieldName='',$condition='', $orderBy='') {   
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        
        $query = "SELECT 
                        $fieldName
                  FROM 
                         ".TIME_TABLE_TABLE." tt, `subject` sub, class c, `group` grp, period p, employee e
                  WHERE
                        tt.subjectId = sub.subjectId AND
                        tt.classId = c.classId AND
                        tt.groupId = grp.groupId AND
                        tt.periodId = p.periodId AND
                        tt.employeeId = e.employeeId AND 
                        tt.toDate IS NULL AND
                        c.instituteId = '$instituteId'
                  $condition $orderBy ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
      public function getFetchName($fieldName='',$tableName='',$condition='') {   
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        
        $query = "SELECT 
                        $fieldName
                   FROM 
                        $tableName
                   WHERE
                        $condition ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }
    
    public function getAllPeriods($condition='') {
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT  
                        p.periodSlotId, p.periodId, p.periodNumber, CONCAT(p.startTime,p.startAmPm,' to ',p.endTime,p.endAmPm) AS periodTime 
                  FROM 
                        `period` p, period_slot ps
                  WHERE      
                        ps.periodSlotId = p.periodSlotId AND
                        ps.instituteId = '$instituteId'    
                  $condition
                  ORDER BY
                        p.periodSlotId, p.periodId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getEmployeeAttendance($fieldName='',$condition='', $orderBy='') {   
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        
        $query = "SELECT 
                        $fieldName
                  FROM 
                         ".TIME_TABLE_TABLE." tt, `subject` sub, class c, `group` grp, period p, employee e, 
                        ".ATTENDANCE_TABLE." att
                  WHERE
                        att.subjectId = sub.subjectId AND
                        att.classId = c.classId AND
                        att.groupId = grp.groupId AND
                        att.periodId = p.periodId AND
                        att.userId = e.userId AND
                        att.toDate IS NULL AND
			att.classId = tt.classId AND
                        c.instituteId = '$instituteId'
                        $condition 
                  $orderBy ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }
    
}
