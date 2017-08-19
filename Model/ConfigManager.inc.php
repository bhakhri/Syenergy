<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Config" Module
//
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ConfigManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "ConfigManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "ConfigManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
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
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Config
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

	
	public function addConfig() {
		global $REQUEST_DATA;
		return SystemDatabaseManager::getInstance()->runAutoInsert('config', array('param','labelName', 'value'), array($REQUEST_DATA['param'],$REQUEST_DATA['label'], $REQUEST_DATA['val']));
	}
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Config
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       	
	
	
    public function editConfig($id) {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('config', array('param','labelName', 'value'), array($REQUEST_DATA['param'],$REQUEST_DATA['label'], $REQUEST_DATA['val']), "configId=$id" );
    }    
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Config Name
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
 	
	
    public function getParam($conditions='') {
        $query = "SELECT param, labelName, value 
        FROM config 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Config Name
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
 	
	
    public function getLabel($conditions='') {
        $query = "SELECT param, labelName, value 
        FROM config 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Config" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function deleteConfig($configId) {
     
        $query = "DELETE 
        FROM config 
        WHERE configId=$configId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Config LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getConfig($conditions='') {
     
        $query = "SELECT * FROM config $conditions";
		queryLog($query);
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Config LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
   	
	
    public function getConfigList($conditions='', $limit = '', $orderBy=' param') {
        $query = "SELECT *  FROM config $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Config" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
		
	 
    public function getTotalConfig($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords FROM config ";
		if ($conditions != '') {
			$query .= " $conditions ";
		}
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
}
?>