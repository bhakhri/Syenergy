<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Sc_Grade_Labels" Module
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GradeLabelManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "GradeLabelManager" CLASS
//
//-------------------------------------------------------------------------------     

	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "GradeManager" CLASS
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
// THIS FUNCTION IS USED FOR ADDING A Grade
//
//
//-------------------------------------------------------------------------------       

	
	public function addGradingLabel() {
		global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
		return SystemDatabaseManager::getInstance()->runAutoInsert('sc_grading_labels', array('gradingLabel','instituteId','sessionId'), array(add_slashes(strtoupper($REQUEST_DATA['gradingLabel'])),$instituteId,$sessionId));
	}
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Grade
//
//-------------------------------------------------------------------------------       	
	
	
    public function editGradingLabel($id) {
        global $REQUEST_DATA;

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('sc_grading_labels', array('gradingLabel','instituteId','sessionId'), array(add_slashes(strtoupper($REQUEST_DATA['gradingLabel'])),$instituteId,$sessionId), "gradingLabelId=$id and instituteId=$instituteId and sessionId=$sessionId" );        
    }    
	
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Grade" RECORD
//
//-------------------------------------------------------------------------------     

    public function deleteGradingLabel($gradingLabelId) {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "DELETE 
        FROM sc_grading_labels 
        WHERE gradingLabelId=$gradingLabelId AND instituteId=$instituteId AND sessionId=$sessionId ";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade LIST 
//-------------------------------------------------------------------------------          
    public function getGradingLabel($conditions='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
     
        $query = "SELECT gradingLabelId, gradingLabel
        FROM sc_grading_labels 
        WHERE instituteId=$instituteId AND sessionId=$sessionId 
        $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade LIST 
//-------------------------------------------------------------------------------          
   	
	
    public function getGradingLabelList($conditions='', $limit = '', $orderBy=' gradingLabel') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT gradingLabelId, gradingLabel
		FROM sc_grading_labels 
        WHERE instituteId=$instituteId AND sessionId=$sessionId 
        $conditions ORDER BY $orderBy $limit ";
        
//        echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Grade" TABLE
//-------------------------------------------------------------------------------       
		
	 
    public function getTotalGradingLabel($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM sc_grading_labels 
        WHERE instituteId=$instituteId AND sessionId=$sessionId ";
		if ($conditions != '') {
			$query .= " $conditions ";
		}
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}

?>