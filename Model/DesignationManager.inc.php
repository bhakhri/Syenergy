<?php

//-------------------------------------------------------------------------------
//
//EmployeeManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class DesignationManager {
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
    
  //-------------------------------------------------------------------------------
//
//addDesignation() is used to add new record in database.
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addDesignation() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('designation', array('designationName','designationCode','description'), array($REQUEST_DATA['designationName'],strtoupper($REQUEST_DATA['designationCode']),$REQUEST_DATA['description']));
    }
    
    //-------------------------------------------------------------------------------
//
//editDesignation() is used to edit the existing record through id.
//Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editDesignation($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('designation', array('designationName','designationCode','description'), array($REQUEST_DATA['designationName'],strtoupper($REQUEST_DATA['designationCode']),$REQUEST_DATA['description']) , "designationId=$id" );
    }    
  //-------------------------------------------------------------------------------
//
//deleteDesignation() is used to delete the existing record through id.
//Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteDesignation($designationId) {
     
        $query = "DELETE 
        FROM designation 
        WHERE designationId=$designationId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getDesignation() is used to get the list of data 
//Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getDesignation($conditions='') {
     
        $query = "SELECT designationId, designationName, designationCode, description 
        FROM designation $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getDesignationList() is used to get the list of data order by name.
//Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getDesignationList($conditions='', $limit = '', $orderBy='designationName') {
     
        $query = "SELECT designationId, designationName, designationCode, description,
                   ( 
                        SELECT COUNT(employeeId) 
                        FROM employee
                        WHERE employee.designationId = designation.designationId
                    ) AS employeeCount
                   FROM designation 
                   HAVING 1=1
                   $conditions 
                   ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     //-------------------------------------------------------------------------------
//
//getTotalDesignation() is used to get total no. of records
//Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalDesignation($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM ( 
                SELECT designationId, designationName, designationCode,description,( 
                        SELECT COUNT(employeeId) 
                        FROM employee
                        WHERE employee.employeeId = designation.designationId
                    ) AS employeeCount
                FROM designation 
                HAVING 1=1
                $conditions ) AS t";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET Designation Id IS USING IN ANOTHER TABLE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (24.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
    
    public function getCheckDuplicateDesignation($conditions='') {
        
    $query = "    SELECT    count(des.designationId) as designationId
                    FROM    designation des,
                            employee emp
                    WHERE    des.designationId = emp.designationId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET Designation Id IS USING IN Institute
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (22.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
    
    public function getCheckDesignationInstitute($conditions='') {
        
    $query = "    SELECT    count(des.designationId) as designationId
                    FROM    designation des,
                            institute ins
                    WHERE    des.designationId = ins.designationId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET Designation Id IS USING IN University
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (22.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------- 
    
    public function getCheckDesignationUniversity($conditions='') {
        
    $query = "    SELECT    count(des.designationId) as designationId
                    FROM    designation des,
                            university univ
                    WHERE   des.designationId = univ.designationId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}
?>
<?php 
// $History: DesignationManager.inc.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/22/09    Time: 11:28a
//Updated in $/LeapCC/Model
//fixed bug no.0000610
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Model
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:26p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/01/08    Time: 11:44a
//Updated in $/Leap/Source/Model
//modified in comments
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/19/08    Time: 2:52p
//Updated in $/Leap/Source/Model
?>
