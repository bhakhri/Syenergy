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

class EmployeeLeaveAuthorizerManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "EmployeeLeaveAuthorizerManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "EmployeeLeaveAuthorizerManager" CLASS
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
    public function getEmployees($conditions='') {

        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT
                        employeeId,CONCAT(employeeName,' (',employeeCode,')') AS employeeName,employeeCode
                  FROM
                        employee
                  WHERE
                        instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                        $conditions
                  ORDER BY employeeName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function checkCyclicData($employeeId,$firstEmp,$secondEmp) {
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                        leave_approving_authority
                  WHERE
                        ( employeeId IN ($firstEmp,$secondEmp ) AND ( firstApprovingEmployeeId=$employeeId OR secondApprovingEmployeeId=$employeeId ) )";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getMappingData($conditions='') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');

       if($conditions == '') {
         $conditions = " WHERE instituteId = $instituteId";
       }
       else {
         $conditions .= " AND instituteId = $instituteId";
       }

       $query = "SELECT
                        *
                  FROM
                        leave_approving_authority
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getMappingDataDetails($conditions='') {
        $query = "SELECT
                        e.employeeId,
                        e.employeeName,
                        e.employeeCode,
                        a.*
                  FROM
                        leave_approving_authority a,employee e
                  WHERE
                        e.employeeId=a.employeeId
                        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function checkEmployeeLeaveAuthorizationMappingUsage($conditions='') {

     /*  if($conditions == '') {
         $conditions = " WHERE instituteId = $instituteId";
       }
       else {
         $conditions .= " AND instituteId = $instituteId";
       }
       */
       $query = "SELECT
                        COUNT(*) AS cnt
                  FROM
                        leave_employee
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function deleteEmployeeLeaveAuthorizerMapping($deleteCondition) {

       if(trim($deleteCondition)==''){
            return false;
        }

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');

       if($deleteCondition == '') {
         $deleteCondition = " WHERE instituteId = $instituteId";
       }
       else {
         $deleteCondition .= " AND instituteId = $instituteId";
       }

       $query = "DELETE
                  FROM
                         leave_approving_authority
				    $deleteCondition";


	   return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function addEmployeeLeaveAuthorizer($employeeId,$firstEmp,$secondEmp,$leaveType,$instituteId,$leaveSessionId) {

        $query = "INSERT INTO
                         leave_approving_authority  (employeeId,firstApprovingEmployeeId,secondApprovingEmployeeId,leaveTypeId,instituteId,leaveSessionId)
                  VALUES ( $employeeId,$firstEmp,$secondEmp,$leaveType,$instituteId,$leaveSessionId)";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function editEmployeeLeaveAuthorizer($mappingId,$firstEmp,$secondEmp,$leaveType) {
        $query = "UPDATE
                        leave_approving_authority
                  SET
                        firstApprovingEmployeeId=$firstEmp,
                        secondApprovingEmployeeId=$secondEmp,
                        leaveTypeId=$leaveType
                  WHERE
                        approvingId=$mappingId";
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getEmployeeLeaveAuthorizerList($conditions='', $limit = '', $orderBy=' e1.employeeCode') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');

        if($leaveAuthorizersId==2) {
          $filedName =  ", e3.employeeName AS secondApprovingEmployee";
          $tableName =  ",employee e3";
          $sCondition = " AND e3.employeeId=l.secondApprovingEmployeeId  AND e3.instituteId=$instituteId ";
        }

        $query = "SELECT
                        l.approvingId,
                        e1.employeeName,e1.employeeCode,
                        e2.employeeName AS firstApprovingEmployee,
                        lt.leaveTypeName $filedName
                  FROM
                        employee e1,employee e2, leave_approving_authority l,
                        leave_type lt $tableName
                  WHERE
                        e1.employeeId=l.employeeId
                        AND e2.employeeId=l.firstApprovingEmployeeId
                        AND l.leaveTypeId=lt.leaveTypeId
                        AND e1.instituteId=$instituteId
                        AND e2.instituteId=$instituteId
                        AND l.instituteId=$instituteId
                        AND e1.isActive=1
                        $sCondition
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
    public function getTotalEmployeeLeaveAuthorizer($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');

        if($leaveAuthorizersId==2) {
          $tableName =  ",employee e3";
          $sCondition = " AND e3.employeeId=l.secondApprovingEmployeeId  AND e3.instituteId=$instituteId ";
        }

        $query = "SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        employee e1,employee e2, leave_approving_authority l,
                        leave_type lt $tableName
                  WHERE
                        e1.employeeId=l.employeeId
                        AND e2.employeeId=l.firstApprovingEmployeeId
                        AND l.leaveTypeId=lt.leaveTypeId
                        AND e1.instituteId=$instituteId
                        AND e2.instituteId=$instituteId
                        AND l.instituteId=$instituteId
                        AND e1.isActive=1
                        $sCondition
                        $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

function getEmpsFromAuthrozerTable($condition='',$orderBy=' employeeName'){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    //0=>Applied,4=>Cancelled
    $query="SELECT
                  e.employeeId,
                  lsa.approvingId,
                  lsa.firstApprovingEmployeeId,
                  lsa.secondApprovingEmployeeId,
                  lsa.leaveTypeId,
                  IFNULL(
                         (SELECT
                                DISTINCT le.employeeId
                          FROM
                                leave_employee le
                          WHERE
                                le.employeeId=e.employeeId
                                AND le.leaveTypeId=lsa.leaveTypeId
                                AND le.leaveStatus NOT IN (0,4)
                                AND le.leaveSessionId = lsa.leaveSessionId),
                          -1
                         ) AS isMapped
            FROM
                  employee e,
                  leave_approving_authority lsa
            WHERE
                  e.employeeId=lsa.employeeId
                  AND e.instituteId=$instituteId
                  AND e.isActive=1
                  $condition
             ORDER BY
                  $orderBy ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

function getAllLeaveTypesForEmps(){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $query="SELECT
                    e.employeeId,
                    lse.leaveSetId,
                    lt.leaveTypeId,
                    lt.leaveTypeName
            FROM
                    employee e,
                    leave_set_employee lse,
                    leave_set_mapping lsm,
                    leave_type lt
            WHERE
                    e.employeeId=lse.employeeId
                    AND lse.leaveSetId=lsm.leaveSetId
                    AND lsm.leaveTypeId=lt.leaveTypeId
                    AND e.instituteId=lt.instituteId
                    AND e.isActive=1
                    AND e.instituteId=$instituteId
            ORDER BY e.employeeId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


function getLeaveTypesForEmployees(){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    $query="SELECT
                    DISTINCT e.employeeId, lse.leaveSetId, lt.leaveTypeId, lt.leaveTypeName, lse.leaveSessionId
            FROM
                    employee e,
                    leave_set_employee lse,
                    leave_set_mapping lsm,
                    leave_type lt, leave_session ls
            WHERE
                    lse.leaveSessionId = ls.leaveSessionId  AND
                    lsm.leaveSessionId = lse.leaveSessionId AND
                    lt.instituteId = lsm.instituteId AND
                    lt.leaveTypeId = lsm.leaveTypeId AND
                    lsm.leaveSetId = lse.leaveSetId AND
                    lsm.instituteId = $instituteId  AND
                    e.employeeId=lse.employeeId  AND
                    e.isActive=1 AND
                    ls.active=1  AND
                    e.instituteId=$instituteId
            ORDER BY
                    leaveTypeName";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


function getLeaveAuthorizerEmployee($condition='',$orderBy=' e.employeeName'){
    global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $query="SELECT
                  DISTINCT e.employeeId,CONCAT('(',employeeCode,') ',employeeName,' ',lastName) AS employeeName
            FROM
                  leave_set_mapping lsm,
                  leave_set_employee lse,
                  leave_session ls,
                  employee e
            WHERE
                  e.employeeId = lse.employeeId AND
                  ls.leaveSessionId = lse.leaveSessionId AND
                  ls.active=1 AND
                  lse.leaveSetId = lsm.leaveSetId AND
                  lse.leaveSessionId = lsm.leaveSessionId AND
                  lsm.instituteId=$instituteId
                  $condition
             ORDER BY
                   $orderBy ";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

}
// $History: EmployeeLeaveAuthorizerManager.inc.php $
?>