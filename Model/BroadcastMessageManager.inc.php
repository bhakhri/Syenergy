<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BroadcastMessageManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BroadcastMessageManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BroadcastMessageManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
  
	public function addMessage() {
		global $REQUEST_DATA;
        global $sessionHandler;
		return SystemDatabaseManager::getInstance()->runAutoInsert('broadcast_message', array('messageDate','messageText','userId'), array( trim($REQUEST_DATA['msgDate']),trim($REQUEST_DATA['msgText']),$sessionHandler->getSessionVariable('UserId') ) );
	}

    public function editMessage($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('broadcast_message', array('messageDate','messageText','userId'), array(trim($REQUEST_DATA['msgDate']),trim($REQUEST_DATA['msgText']),$sessionHandler->getSessionVariable('UserId')), "messageId=$id" );
    }   
    
    public function getMessage($conditions='') {
     
        $query = "SELECT 
                        * 
                  FROM 
                        broadcast_message
                  $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function deleteMessage($messageId) {
     
        $query = "DELETE 
                  FROM 
                       broadcast_message 
                  WHERE 
                       messageId=$messageId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
   
    
    public function getMessageList($conditions='', $limit = '', $orderBy=' messageDate DESC') {
     
        $query = "SELECT 
                        *
                  FROM 
                        broadcast_message
                  $conditions 
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

    public function getTotalMessage($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        broadcast_message
                        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

}
?>