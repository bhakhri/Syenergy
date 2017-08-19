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

class ConfigsManager {
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
// THIS FUNCTION IS USED FOR EDITING A Config
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       	
	
	
    public function editConfig($id,$value) {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        return SystemDatabaseManager::getInstance()->runAutoUpdate('config', array('value'), array($value), "configId=$id AND instituteId=$instituteId" );
    }
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Reminder Config
//
// Author :Rajeev Aggarwal
// Created on : 10-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       	
    public function editReminderConfig($id,$value) {
		
		if($value!=''){
			
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			return SystemDatabaseManager::getInstance()->runAutoUpdate('reminder', array('value'), array($value), "reminderId=$id AND instituteId=$instituteId");
		}
    }    
		
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Config LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
   	
	
    public function getConfigList($conditions='', $limit = '', $orderBy=' configId ') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT *  FROM config WHERE instituteId=$instituteId $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING reminder Config LIST 
//
// Author :Rajeev Aggarwal 
// Created on : 08-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
   	
	
    public function getReminderConfigList($conditions='', $limit = '', $orderBy=' reminderId ') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT *  FROM reminder WHERE instituteId=$instituteId $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING reminder Config LIST 
//
// Author :Rajeev Aggarwal 
// Created on : 08-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
   	
	
    public function updateImage($paramValue,$fileName) {

		if($fileName){
			
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');

			$query = "UPDATE `reminder` SET  value = '".$fileName."' WHERE param='$paramValue' AND instituteId=$instituteId";
			SystemDatabaseManager::getInstance()->executeUpdate($query);
		}
    }  
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING reminder Config LIST 
//
// Author :Rajeev Aggarwal 
// Created on : 08-July-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
   	
	
    public function updateConfigImage($paramValue,$fileName) {

		if($fileName){
			
			global $sessionHandler;
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$query = "UPDATE `config` SET  value = '".$fileName."' WHERE param='$paramValue' AND instituteId=$instituteId";
			SystemDatabaseManager::getInstance()->executeUpdate($query);
		}
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