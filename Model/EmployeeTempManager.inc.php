<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "employee_temp" TABLE
//
//
// Author :Gurkeerat Sidhu 
// Created on : (29.4.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); 

class TempEmployeeManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TempEmployeeManager" CLASS
//
// Author :Gurkeerat Sidhu 
// Created on : (29.4.2009 )
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "TempEmployeeManager" CLASS
//
// Author :Gurkeerat Sidhu  
// Created on : (29.4.2009 )
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
// THIS FUNCTION IS USED FOR ADDING A TEMPORARY EMPLOYEE
//
// Author :Gurkeerat Sidhu  
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addTempEmployee() {
		global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoInsert('employee_temp', array('tempEmployeeName','address','contactNo','dateOfJoining','status','tempDesignationId'), 
        array($REQUEST_DATA['tempEmployeeName'],$REQUEST_DATA['address'],$REQUEST_DATA['contactNo'],$REQUEST_DATA['dateOfJoining'],$REQUEST_DATA['status'],$REQUEST_DATA['designationName']) 
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A TEMPORARY EMPLOYEE
//
//$id:visitorId
// Author :Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTempEmployee($id) {
        global $REQUEST_DATA;
        
     return SystemDatabaseManager::getInstance()->runAutoUpdate('employee_temp', array('tempEmployeeName','address','contactNo','dateOfJoining','status','tempDesignationId'), 
        array($REQUEST_DATA['tempEmployeeName'],$REQUEST_DATA['address'],$REQUEST_DATA['contactNo'],$REQUEST_DATA['dateOfJoining'],$REQUEST_DATA['status'],$REQUEST_DATA['designationName']), 
        "tempEmployeeId=$id" 
        );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TEMPORARY EMPLOYEE LIST
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu 
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTempEmployee($conditions='') {
     
        $query = "SELECT tempEmployeeId,tempEmployeeName,address,contactNo,dateOfJoining,status,tempDesignationId
        FROM employee_temp
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A TEMPORARY EMPLOYEE
//
// Author :Gurkeerat Sidhu 
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTempEmployee($tempEmployeeId) {
     
        $query = "DELETE 
        FROM employee_temp 
        WHERE tempEmployeeId=$tempEmployeeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TEMPORARY EMPLOYEE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Gurkeerat Sidhu 
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getTempEmployeeList($conditions='', $limit = '', $orderBy=' tempEmployeeName') {
        
        
        $query = "    SELECT et.tempEmployeeId,et.tempEmployeeName,et.address,et.contactNo,et.dateOfJoining,et.status,dt.designationName 
                    FROM employee_temp et, designation_temp dt
                    WHERE et.tempDesignationId=dt.tempDesignationId   
                    $conditions
                    ORDER BY $orderBy 
                    $limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TEMPORARY EMPLOYEE
//
//$conditions :db clauses
// Author :Gurkeerat Sidhu  
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTempEmployee($conditions='') {
        
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM employee_temp et, designation_temp dt
        WHERE et.tempDesignationId=dt.tempDesignationId 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
?>
