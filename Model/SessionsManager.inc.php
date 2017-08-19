<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Sessions" Module
//
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class SessionsManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    
    private function __construct() {
    }
    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SessionsManager" CLASS
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
// THIS FUNCTION IS USED FOR ADDING A Session
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    
    
    
    public function addSession() {
       global $REQUEST_DATA;
       global $sessionHandler;
      
       $fromDate = $REQUEST_DATA['fromDate'];
       $toDate = $REQUEST_DATA['toDate'];  
       
        $sessionYear = date("Y",strtotime($fromDate));
        $sessionAbbr = $sessionYear."-".substr(date("Y",strtotime($toDate)),2,2);
         
        
        return SystemDatabaseManager::getInstance()->runAutoInsert('session', 
            array('sessionName','startDate','endDate','sessionYear','abbreviation','active'), 
            array(trim($REQUEST_DATA['sessionName']),$fromDate,$toDate,$sessionYear,$sessionAbbr,$REQUEST_DATA['Active']));
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Session
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    
    
    public function editSession($id) {
        global $REQUEST_DATA;
         global $sessionHandler;
        
        $fromDate = $REQUEST_DATA['fromDate'];
        $toDate = $REQUEST_DATA['toDate'];  
       
        $sessionYear = date("Y",strtotime($fromDate));
        $sessionAbbr = $sessionYear."-".substr(date("Y",strtotime($toDate)),2,2);

        return SystemDatabaseManager::getInstance()->runAutoUpdate('session', 
            array('sessionName','startDate','endDate','sessionYear','abbreviation','active'), 
            array(trim($REQUEST_DATA['sessionName']),$fromDate,$toDate, $sessionYear,$sessionAbbr,$REQUEST_DATA['Active']), "sessionId=$id" );
    }    
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session Name
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    
    public function getSessionName($conditions='') {
     
        $query = "SELECT sessionId, sessionName,sessionYear,abbreviation,active, startDate, endDate  
        FROM session 
        $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session Year
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    
    public function getSessionYear($conditions='') {
     
        $query = "SELECT sessionId,sessionName,sessionYear, abbreviation, active, startDate, endDate   
        FROM session 
        $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Session" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function deleteSession($sessionId) {
     
        $query = "DELETE 
        FROM session 
        WHERE sessionId=$sessionId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getSession($conditions='') {
     
       $query = "SELECT sessionId,sessionName,sessionYear, abbreviation, active, startDate, endDate 
        FROM session 
        $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    
    public function getSessionList($conditions='', $limit = '', $orderBy=' sessionYear') {
       global $sessionHandler;
        $query = "SELECT sessionId, sessionName, sessionYear, abbreviation, IF(active=1,'Yes','No') AS active,  startDate, endDate   
        FROM session $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Session" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
        
     
    public function getTotalSession($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM session ";
        if ($conditions != '') {
            $query .= " $conditions ";
        }
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkSessionNameExists() {

    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All Session Labels Inactive
//----------------------------------------------------------------------------------------      
    public function makeAllSessionInActive($conditions='') {
        
        $query = "UPDATE session tt1
        SET active=0
        WHERE tt1.active=1
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
	
//-------------------------------------------------------------------------------
//
//addSessionInTransaction() function used to Add room from Excel
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 10.11.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 

	public function addSessionInTransaction($str) {
		$query = "INSERT IGNORE INTO `session` (sessionYear,sessionName,abbreviation,active,startDate,endDate) VALUES $str";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    
    public function getSessionCheck($sessionId='') {
     
        global $sessionHandler;
        $query = "SELECT 
                         COUNT(*) AS cnt 
                  FROM 
                        class
                  WHERE 
                        sessionId = $sessionId ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>