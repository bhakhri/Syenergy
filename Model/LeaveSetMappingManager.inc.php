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

class LeaveSetMappingManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "LeaveSetMappingManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "LeaveSetMappingManager" CLASS
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
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addCity() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('city', array('cityCode','cityName','stateId'), array(strtoupper($REQUEST_DATA['cityCode']),$REQUEST_DATA['cityName'],$REQUEST_DATA['states']) );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editCity($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('city', array('cityCode','cityName','stateId'), array(strtoupper($REQUEST_DATA['cityCode']),$REQUEST_DATA['cityName'],$REQUEST_DATA['states']), "cityId=$id" );
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
    public function getLeaveSetMapping($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        if($conditions=='') {
           $conditions = " WHERE instituteId = $instituteId";  
        }
        else {
           $conditions .= " AND instituteId = $instituteId";    
        }
        
        $query = "SELECT 
                        * 
                  FROM 
                        leave_set_mapping
                  $conditions";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

    public function deleteLeaveSetMapping($deleteCondition) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        if(trim($deleteCondition)==''){
            return false;
        }
        
        if($deleteCondition=='') {
           $deleteCondition = " WHERE instituteId = $instituteId";  
        }
        else {
           $deleteCondition .= " AND instituteId = $instituteId";    
        }
        
      $query = "DELETE 
                  FROM 
                         leave_set_mapping  
                  $deleteCondition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function doLeaveSetMapping($insertCondition) {
        if(trim($insertCondition)==''){
            return false;
        }
     
        $query = "INSERT INTO
                         leave_set_mapping (leaveSessionId,leaveSetId,leaveTypeId,leaveValue,instituteId)
                         VALUES $insertCondition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function editLeaveSetMapping($mappingId,$leaveSet,$leaveType,$leaveTypeValue) {
        $query = "UPDATE 
                        leave_set_mapping
                  SET
                        leaveSetId=$leaveSet,
                        leaveTypeId=$leaveType,
                        leaveValue=$leaveTypeValue
                  WHERE
                        leaveSetMappingId=$mappingId";
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
    
    public function getLeaveSetMappingList($conditions='', $limit = '', $orderBy=' ls.leaveSetName') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                         lsm.leaveSetMappingId,
                         lsm.leaveValue,
                         ls.leaveSetId,
                         ls.leaveSetName,
                         lt.leaveTypeId,
                         lt.leaveTypeName,
                         s.sessionName
                   FROM 
                         `leave_session` s, leave_set ls,leave_type lt,leave_set_mapping lsm
                   WHERE
                         s.leaveSessionId = lsm.leaveSessionId
                         AND ls.leaveSetId=lsm.leaveSetId
                         AND lsm.leaveTypeId=lt.leaveTypeId
                         AND ls.instituteId=$instituteId
                         AND lt.instituteId=$instituteId
                         AND lsm.instituteId=$instituteId   
                         AND ls.isActive=1
                         AND lt.isActive=1
                         AND s.active=1
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
    public function getTotalLeaveSetMapping($conditions='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                         `leave_session` s,leave_set ls,leave_type lt,leave_set_mapping lsm
                   WHERE
                         s.leaveSessionId = lsm.leaveSessionId
                         AND ls.leaveSetId=lsm.leaveSetId
                         AND lsm.leaveTypeId=lt.leaveTypeId
                         AND ls.instituteId=$instituteId
                         AND lt.instituteId=$instituteId
                         AND lsm.instituteId=$instituteId  
                         AND ls.isActive=1
                         AND lt.isActive=1
                         AND s.active=1
                         $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
 public function checkLeaveSetUsage($leaveSetId) {
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM 
                        leave_set_employee 
                  WHERE
                        leaveSetId=$leaveSetId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 public function checkLeaveSetUsage2($leaveSetMappingId) {
        $query = "SELECT
                        COUNT(*) AS cnt
                  FROM 
                        leave_set_employee
                  WHERE
                        leaveSetId IN ( SELECT leaveSetId FROM  leave_set_mapping WHERE leaveSetMappingId=$leaveSetMappingId )
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
 public function checkUsageOfLeaveSetMapping($leaveSetId,$condition='') {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT
                        DISTINCT le.leaveTypeId        
                  FROM 
                        leave_set_mapping lsm,leave_set_employee lse,leave_employee le
                  WHERE
                        lsm.leaveSetId=lse.leaveSetId
                        AND lse.employeeId=le.employeeId
                        AND lsm.leaveSessionId=lse.leaveSessionId 
                        AND le.leaveSessionId=lse.leaveSessionId
                        AND lsm.leaveSetId=$leaveSetId     
                        AND lsm.instituteId=$instituteId 
                  $condition";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }              
   
  
}
// $History: LeaveSetMappingManager.inc.php $
?>