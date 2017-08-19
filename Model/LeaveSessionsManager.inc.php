<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Leave_Sessions" Module
//
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class LeaveSessionsManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    
    private function __construct() {
    }
    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
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
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Session
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    
    
    
    public function addSession() {
        global $REQUEST_DATA;
        global $sessionHandler;
       
        return SystemDatabaseManager::getInstance()->runAutoInsert('leave_session', 
            array('sessionName','sessionStartDate','sessionEndDate','active'), 
            array(trim($REQUEST_DATA['sessionName']),$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate'],$REQUEST_DATA['Active']));
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Session
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    
    
    public function editSession($id) {
        global $REQUEST_DATA;
         global $sessionHandler;
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('leave_session', 
            array('sessionName','sessionStartDate','sessionEndDate','active'), 
            array(trim($REQUEST_DATA['sessionName']),$REQUEST_DATA['fromDate1'],$REQUEST_DATA['toDate1'],$REQUEST_DATA['Active']), "leaveSessionId=$id");
    }    
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session Name
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    
    public function getSessionName($conditions='') {
     
        $query = "SELECT 
                        leaveSessionId, sessionName, active, sessionStartDate, sessionEndDate  
                  FROM 
                        `leave_session` 
        $conditions";    
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Session" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function deleteSession($leaveSessionId) {
     
        $query = "DELETE FROM `leave_session` 
                 WHERE leaveSessionId=$leaveSessionId";
                 
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getSession($conditions='') {
     
       $query = "SELECT 
                        leaveSessionId,sessionName, active,  sessionStartDate, sessionEndDate   
                 FROM 
                        leave_session 
                 $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    
    public function getSessionList($conditions='', $limit = '', $orderBy=' sessionYear') {
        global $sessionHandler;
        
        $query = "SELECT 
                         leaveSessionId, sessionName,  sessionStartDate, sessionEndDate,
                         IF(active=1,'Yes','No') AS active
                  FROM leave_session 
                        $conditions 
                  ORDER BY 
                  $orderBy $limit ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Session" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
        
     
    public function getTotalSession($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                      (SELECT 
                              leaveSessionId, sessionName,  sessionStartDate, sessionEndDate,
                              IF(active=1,'Yes','No') AS active
                       FROM 
                              leave_session 
                       $conditions) AS t ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All Session Labels Inactive
//----------------------------------------------------------------------------------------      
    public function makeAllSessionInActive($conditions='') {
        
        $query = "UPDATE 
                        leave_session tt1
                  SET 
                        active=0
                  WHERE 
                        tt1.active=1
                  $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
	
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Leave Session" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function checkLeaveSession($leaveSessionId) {
     
        $query = "SELECT 
                        leaveSessionId  
                  FROM 
                        `leave_set_mapping`
                  WHERE
                        leaveSessionId = $leaveSessionId";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}
?>