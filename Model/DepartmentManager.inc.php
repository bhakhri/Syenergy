<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "department" TABLE
//
//
// Author :Jaineesh 
// Created on : (20.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DepartmentManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DepartmentManager" CLASS
//
// Author : Jaineesh 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DepartmentManager" CLASS
//
// Author :Jaineesh 
// Created on : (20.11.2008)
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
// THIS FUNCTION IS USED FOR ADDING DEPARTMENT
//
// Author :Jaineesh 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addDepartment() {
        global $REQUEST_DATA;

    //    return SystemDatabaseManager::getInstance()->runAutoInsert('department', array('departmentName','abbr'), array($REQUEST_DATA['departmentName'],strtoupper($REQUEST_DATA['abbr'])));   
    return SystemDatabaseManager::getInstance()->runAutoInsert('department', array('departmentName','abbr','description'), array($REQUEST_DATA['departmentName'],strtoupper($REQUEST_DATA['abbr']),$REQUEST_DATA['description']));   
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A DEPARTMENT
//
//$id:departmentId
// Author :Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editDepartment($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('department', array('departmentName','abbr','description'), array($REQUEST_DATA['departmentName'],strtoupper($REQUEST_DATA['abbr']),$REQUEST_DATA['description']), "departmentId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEPARTMENT LIST
//
// Author :Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getDepartment($conditions='') {
     
        $query = "SELECT departmentId,departmentName,abbr,description
        FROM department
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING DEPARTMENT
//
//$departmentId :departmentid of the City
// Author :Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteDepartment($departmentId) {
     
        $query = "DELETE 
        FROM department 
        WHERE departmentId=$departmentId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEGREE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
  public function getDepartmentList($conditions='', $limit = '', $orderBy=' departmentName') {
     
       /* $query = "SELECT departmentId, departmentName, abbr
        FROM department 
        $conditions 
        ORDER BY $orderBy $limit";         */
        
        $query = "SELECT departmentId, departmentName, abbr, description,  
                (
                                SELECT 
                                COUNT(emp.employeeId)    
                                FROM employee emp
                                WHERE emp.departmentId = department.departmentId
                 )  
                AS employeeCount 
                FROM department
                HAVING 1=1
                $conditions 
                ORDER BY $orderBy $limit"; 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
                            
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF DEPARTMENT
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalDepartment($conditions='') {
    
     /*   $query = "SELECT COUNT(*) AS totalRecords 
        FROM department dg
        $conditions ";        */
       $query = "SELECT COUNT(*) AS totalRecords 
        FROM  
               (SELECT departmentId, departmentName, abbr, description,
                (
                                SELECT 
                                COUNT(emp.employeeId)    
                                FROM employee emp
                                WHERE emp.departmentId = department.departmentId
                 )  
                AS employeeCount 
                FROM department
                HAVING 1=1
                $conditions 
                ) as t";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEPARTMENT LIST
//
// Author :Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function checkDepartmentInNotice($conditions='') {
     
        $query = "SELECT 
                        COUNT(departmentId) AS found
                  FROM 
                       notice
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
          
  
}
?>
<?php
// $History: DepartmentManager.inc.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:36
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids---
//0000971 to 0000976,0000979
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:51p
//Created in $/Leap/Source/Model
//get the queries for add, edit, delete & list
//

?>
