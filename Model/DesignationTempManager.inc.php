<?php

//-------------------------------------------------------------------------------
//
//EmployeeManager is used having all the Add, edit, delete function..
// Author : Gurkeerat Sidhu
// Created on : 29.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
  include_once(DA_PATH ."/SystemDatabaseManager.inc.php");
  
  class DesignationTempManager {
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
// Author : Gurkeerat Sidhu
// Created on : 29.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addDesignation() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('designation_temp', array('designationName','designationCode'), array($REQUEST_DATA['designationName'],strtoupper($REQUEST_DATA['designationCode'])));
    }
    
    //-------------------------------------------------------------------------------
//
//editDesignation() is used to edit the existing record through id.
//Author : Gurkeerat Sidhu
// Created on : 29.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editDesignation($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('designation_temp', array('designationName','designationCode'), array($REQUEST_DATA['designationName'],strtoupper($REQUEST_DATA['designationCode'])) , "tempDesignationId=$id" );
    }    
  //-------------------------------------------------------------------------------
//
//deleteDesignation() is used to delete the existing record through id.
//Author : Gurkeerat Sidhu
// Created on : 29.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------  
    public function deleteDesignation($tempDesignationId) {
     
        $query = "DELETE 
        FROM designation_temp 
        WHERE tempDesignationId=$tempDesignationId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
   //-------------------------------------------------------------------------------
//
//getDesignation() is used to get the list of data 
//Author : Gurkeerat Sidhu
// Created on : 29.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getDesignation($conditions='') {
     
        $query = "SELECT tempDesignationId, designationName, designationCode 
        FROM designation_temp $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    //-------------------------------------------------------------------------------
//
//getDesignationList() is used to get the list of data order by name.
//Author : Gurkeerat Sidhu
// Created on : 29.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getDesignationList($conditions='', $limit = '', $orderBy='designationName') {
     
        $query = "SELECT tempDesignationId, designationName, designationCode
        FROM designation_temp $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
     //-------------------------------------------------------------------------------
//
//getTotalDesignation() is used to get total no. of records
//Author : Gurkeerat Sidhu
// Created on : 29.04.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    
    public function getTotalDesignation($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM designation_temp $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
       
}
?>

