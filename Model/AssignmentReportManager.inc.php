<?php
//---------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "teacher" module
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class AssignmentReportManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.07.2008)
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

    //--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying total nos assignment for teacher.
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (04.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getTotalTeacherAssignment($conditions='') {
        global $sessionHandler;

        $query="SELECT
                      COUNT(*) AS totalRecords
                FROM
                      assignment aa, time_table_classes tt  
                WHERE
                      tt.classId = aa.classId
                      AND aa.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
                      AND aa.instituteId= '".$sessionHandler->getSessionVariable('InstituteId')."'
                $conditions";
                
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR displaying List assignment for teacher.
// $conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (04.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------
    public function getTeacherAssignmentList($conditions='',$limit='',$orderBy=' aa.assignedOn') {

        global $sessionHandler;
        $query="SELECT
                      count(*) as totalAssignment,
                      aa.assignmentId,
                      aa.topicTitle,
                      aa.topicDescription,
                      aa.assignedOn,
                      aa.tobeSubmittedOn,
                      aa.addedOn,
                      aa.attachmentFile,
                      IF(aa.isVisible=1,'Yes','No') AS isVisible,
                      aa.isVisible AS isVisible2 , 
                      CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName 
                FROM
                     `assignment_detail` ad, `assignment` aa, time_table_classes tt, employee e
                WHERE
                      tt.classId = aa.classId 
                      AND e.employeeId = aa.employeeId
                      AND ad.assignmentId = aa.assignmentId
                      AND aa.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'
                      AND aa.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                $conditions
                GROUP BY ad.assignmentId
                ORDER BY $orderBy $limit ";
                
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
    
     public function getEmployeeTimeTable($fieldName='',$condition='', $orderBy='',$labelId='') {   
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $systemDatabaseManager = SystemDatabaseManager::getInstance();   
        
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $userId= $sessionHandler->getSessionVariable('UserId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');
        $roleName = $sessionHandler->getSessionVariable('RoleName'); 
        
        $tableName = "";
        $insertValue = '';
        $classCondition1 ='';
        
        if($labelId!='') {
            
            $cnt = 0;
            if($roleId!=1) { 
               $classCondition1 = " AND c.isActive IN (1,3) "; 
               $query = "SELECT
                                DISTINCT sessionId, isActive
                          FROM
                                time_table_labels
                          WHERE  
                                timeTableLabelId = $labelId AND
                                instituteId = $instituteId ";
                $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");   
                
                $isActive = "1";
                $sessionId = '0';
                if(count($result) > 0 ) {
                  $sessionId = $result[0]['sessionId'];  
                  $isActive = $result[0]['isActive'];  
                  if($isActive=='0') {
                    $isActive = "3";
                  }
                } 
                
                $query = "SELECT
                                DISTINCT cvtr.classId
                          FROM
                                classes_visible_to_role cvtr, class cc
                          WHERE  
                                cc.classId = cvtr.classId AND
                                cvtr.userId = $userId AND
                                cvtr.roleId = $roleId AND
                                cc.instituteId = $instituteId AND
                                cc.sessionId = $sessionId AND
                                cc.isActive IN ($isActive) ";

                $result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
                $cnt = count($result);
                
                $insertValue = "0";
                for($i=0;$i<$cnt; $i++) {
                  $insertValue .= ",".$result[$i]['classId'];
                }
            }
            if($cnt > 0) {
              $classCondition1 = " AND c.classId IN  ($insertValue) ";  
            }
            else {
               $classCondition1 = "";  
            }
        }
        
        $query = "SELECT 
                        $fieldName
                  FROM 
                         ".TIME_TABLE_TABLE."  tt, `subject` sub, class c, `group` grp, period p, employee e
                  WHERE
                        tt.subjectId = sub.subjectId AND
                        tt.classId = c.classId AND
                        tt.groupId = grp.groupId AND
                        tt.periodId = p.periodId AND
                        tt.employeeId = e.employeeId AND 
                        tt.toDate IS NULL AND
                        c.instituteId = '$instituteId'
                        $classCondition1
                        $condition 
                  $orderBy ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>
